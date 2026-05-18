<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class Comments {

    public static function init( array $settings = [] ): void {
        // Close comments and pings on frontend.
        add_filter( 'comments_open', '__return_false', 20, 2 );
        add_filter( 'pings_open', '__return_false', 20, 2 );

        // Hide existing comments.
        add_filter( 'comments_array', '__return_empty_array', 10, 2 );

        // Remove comment support from all post types.
        add_action( 'init', [ __CLASS__, 'remove_post_type_support' ], 100 );

        // Remove admin menu items.
        add_action( 'admin_menu', [ __CLASS__, 'remove_admin_menu' ], 999 );

        // Remove comments from admin bar.
        add_action( 'wp_before_admin_bar_render', [ __CLASS__, 'remove_admin_bar_comments' ] );

        // Remove Recent Comments widget.
        add_action( 'widgets_init', [ __CLASS__, 'unregister_widget' ] );

        // Remove comments meta box from dashboard.
        add_action( 'wp_dashboard_setup', [ __CLASS__, 'remove_dashboard_widget' ] );

        // Redirect comments admin page.
        add_action( 'admin_init', [ __CLASS__, 'redirect_comments_page' ] );
    }

    public static function remove_post_type_support(): void {
        foreach ( get_post_types() as $post_type ) {
            if ( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }

    public static function remove_admin_menu(): void {
        remove_menu_page( 'edit-comments.php' );
        remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    }

    public static function remove_admin_bar_comments(): void {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'comments' );
    }

    public static function unregister_widget(): void {
        unregister_widget( 'WP_Widget_Recent_Comments' );
    }

    public static function remove_dashboard_widget(): void {
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    }

    public static function redirect_comments_page(): void {
        global $pagenow;
        if ( 'edit-comments.php' === $pagenow ) {
            wp_safe_redirect( admin_url() );
            exit;
        }
    }
}
