<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class Heartbeat {

    public static function init( array $settings = [] ): void {
        // Simple on/off toggle: throttle to 60s. Legacy modes "disable" still honored if set in DB.
        $mode = $settings['heartbeat_mode'] ?? ( ! empty( $settings['heartbeat'] ) ? 'reduce' : 'default' );

        if ( 'disable' === $mode ) {
            add_action( 'init', [ __CLASS__, 'deregister' ], 1 );
            add_action( 'admin_enqueue_scripts', [ __CLASS__, 'deregister' ], 1 );
        } elseif ( 'reduce' === $mode ) {
            add_filter( 'heartbeat_settings', [ __CLASS__, 'reduce_interval' ] );
        }
    }

    public static function deregister(): void {
        wp_deregister_script( 'heartbeat' );
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function reduce_interval( array $settings ): array {
        $settings['interval'] = 60;
        return $settings;
    }
}
