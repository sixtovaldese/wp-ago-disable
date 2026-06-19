<?php

namespace AgoLab\Disable;

defined( 'ABSPATH' ) || exit;

class Plugin {

    private static ?self $instance = null;

    /** Toggle keys mapped to module classes. */
    private const MODULES = [
        'comments'             => Modules\Comments::class,
        'gutenberg'            => Modules\Gutenberg::class,
        'auto_updates_core'    => Modules\AutoUpdates::class,
        'auto_updates_plugins' => Modules\AutoUpdates::class,
        'auto_updates_themes'  => Modules\AutoUpdates::class,
        'pingbacks'            => Modules\Pingbacks::class,
        'author_archives'      => Modules\AuthorArchives::class,
        'attachment_pages'     => Modules\AttachmentPages::class,
        'search'               => Modules\Search::class,
        'rss_feeds'            => Modules\RssFeeds::class,
        'admin_bar'            => Modules\AdminBar::class,
        'admin_notices'        => Modules\AdminNotices::class,
        'wp_news_widget'       => Modules\WpNewsWidget::class,
        'heartbeat'            => Modules\Heartbeat::class,
    ];

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Seed safe defaults the first time the plugin runs (idempotent).
        $this->maybe_seed_defaults();

        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Init active modules.
        $this->init_modules();
    }

    /** Seed recommended defaults on first run. Idempotent. */
    private function maybe_seed_defaults(): void {
        if ( false === get_option( 'agodisable_settings', false ) ) {
            update_option( 'agodisable_settings', [
                'attachment_pages' => true,
                'author_archives'  => true,
                'pingbacks'        => true,
                'admin_notices'    => true,
                'wp_news_widget'   => true,
                'heartbeat'        => true,
            ] );
        }
    }

    /* ───── Textdomain ───── */

    public function load_textdomain(): void {
        load_plugin_textdomain( 'ago-disable', false, dirname( plugin_basename( AGODISABLE_FILE ) ) . '/languages' );
    }

    /* ───── Admin menu (smart pattern) ───── */

    public function register_admin_menu(): void {
        if ( empty( $GLOBALS['admin_page_hooks']['agolab-tools'] ) ) {
            add_menu_page(
                __( 'aGo Tools', 'ago-disable' ),
                __( 'aGo Tools', 'ago-disable' ),
                'manage_options',
                'agolab-tools',
                '__return_null',
                'dashicons-hammer',
                81
            );
        }

        add_submenu_page(
            'agolab-tools',
            __( 'aGo Disable', 'ago-disable' ),
            __( 'Disable', 'ago-disable' ),
            'manage_options',
            'ago-disable',
            [ Admin\Page::class, 'render' ]
        );

        remove_submenu_page( 'agolab-tools', 'agolab-tools' );
    }

    /* ───── REST routes ───── */

    public function register_rest_routes(): void {
        register_rest_route( 'ago-disable/v1', '/settings', [
            [
                'methods'             => 'GET',
                'callback'            => [ $this, 'handle_get_settings' ],
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                },
            ],
            [
                'methods'             => 'POST',
                'callback'            => [ $this, 'handle_save_settings' ],
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                },
            ],
        ] );
    }

    public function handle_get_settings(): \WP_REST_Response {
        return new \WP_REST_Response( $this->get_settings() );
    }

    public function handle_save_settings( \WP_REST_Request $request ): \WP_REST_Response {
        $input    = $request->get_json_params();
        $settings = [];

        foreach ( array_keys( self::MODULES ) as $key ) {
            $settings[ $key ] = ! empty( $input[ $key ] );
        }

        // Heartbeat mode: disable | reduce | default
        $heartbeat_mode = sanitize_text_field( $input['heartbeat_mode'] ?? 'default' );
        if ( ! in_array( $heartbeat_mode, [ 'disable', 'reduce', 'default' ], true ) ) {
            $heartbeat_mode = 'default';
        }
        $settings['heartbeat_mode'] = $heartbeat_mode;

        update_option( 'agodisable_settings', $settings );

        return new \WP_REST_Response( [ 'saved' => true, 'settings' => $settings ] );
    }

    /* ───── Assets ───── */

    public function enqueue_assets( string $hook ): void {
        if ( ! str_ends_with( $hook, '_page_ago-disable' ) ) {
            return;
        }

        wp_enqueue_style(
            'agodisable-admin',
            AGODISABLE_URL . 'assets/css/admin.css',
            [],
            AGODISABLE_VERSION
        );

        wp_enqueue_script(
            'agodisable-admin',
            AGODISABLE_URL . 'assets/js/admin.js',
            [],
            AGODISABLE_VERSION,
            true
        );

        wp_localize_script( 'agodisable-admin', 'agodisableData', [
            'restUrl'  => rest_url( 'ago-disable/v1' ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
            'settings' => $this->get_settings(),
        ] );
    }

    /* ───── Modules ───── */

    private function init_modules(): void {
        $settings = $this->get_settings();
        $loaded   = [];

        foreach ( self::MODULES as $key => $class ) {
            if ( ! empty( $settings[ $key ] ) && ! isset( $loaded[ $class ] ) ) {
                $class::init( $settings );
                $loaded[ $class ] = true;
            }
        }

        // Heartbeat: a simple on/off toggle now (throttles to 60s). Legacy `heartbeat_mode` still honored.
        if ( ! empty( $settings['heartbeat'] ) || ( ( $settings['heartbeat_mode'] ?? 'default' ) !== 'default' ) ) {
            if ( ! isset( $loaded[ Modules\Heartbeat::class ] ) ) {
                Modules\Heartbeat::init( $settings );
            }
        }
    }

    /** @return array<string, mixed> */
    public function get_settings(): array {
        $saved    = get_option( 'agodisable_settings', [] );
        $settings = [];

        foreach ( array_keys( self::MODULES ) as $key ) {
            $settings[ $key ] = ! empty( $saved[ $key ] );
        }

        $settings['heartbeat_mode'] = $saved['heartbeat_mode'] ?? 'default';

        return $settings;
    }
}
