<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

/**
 * Hides the notices that other plugins add to wp-admin, for users who cannot
 * manage options. Administrators keep seeing every notice.
 *
 * Nothing is captured or printed again: the callbacks that other plugins hook
 * to the notice actions are removed before WordPress runs them. Notices from
 * WordPress itself, from themes and from must-use plugins are left alone.
 */
class AdminNotices {

    private const HOOKS = [ 'admin_notices', 'all_admin_notices', 'network_admin_notices', 'user_admin_notices' ];

    public static function init( array $settings = [] ): void {
        add_action( 'in_admin_header', [ __CLASS__, 'remove_plugin_notices' ], PHP_INT_MAX );
    }

    public static function remove_plugin_notices(): void {
        if ( current_user_can( 'manage_options' ) ) {
            return;
        }

        global $wp_filter;

        $plugins_dir = trailingslashit( wp_normalize_path( WP_PLUGIN_DIR ) );
        $own_dir     = trailingslashit( wp_normalize_path( AGODISABLE_PATH ) );

        foreach ( self::HOOKS as $hook ) {
            if ( empty( $wp_filter[ $hook ] ) ) {
                continue;
            }

            foreach ( $wp_filter[ $hook ]->callbacks as $priority => $callbacks ) {
                foreach ( $callbacks as $callback ) {
                    $file = self::callback_file( $callback['function'] );

                    if ( '' !== $file && str_starts_with( $file, $plugins_dir ) && ! str_starts_with( $file, $own_dir ) ) {
                        remove_action( $hook, $callback['function'], $priority );
                    }
                }
            }
        }
    }

    /** Path of the file where a callback is defined, or an empty string. */
    private static function callback_file( $callback ): string {
        try {
            if ( is_string( $callback ) && str_contains( $callback, '::' ) ) {
                $callback = explode( '::', $callback, 2 );
            }

            if ( is_array( $callback ) && 2 === count( $callback ) ) {
                $reflection = new \ReflectionMethod( $callback[0], $callback[1] );
            } elseif ( is_string( $callback ) || $callback instanceof \Closure ) {
                $reflection = new \ReflectionFunction( $callback );
            } elseif ( is_object( $callback ) && method_exists( $callback, '__invoke' ) ) {
                $reflection = new \ReflectionMethod( $callback, '__invoke' );
            } else {
                return '';
            }
        } catch ( \ReflectionException $e ) {
            return '';
        }

        $file = $reflection->getFileName();

        return $file ? wp_normalize_path( $file ) : '';
    }
}
