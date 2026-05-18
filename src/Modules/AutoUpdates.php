<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class AutoUpdates {

    private static bool $initialized = false;

    public static function init( array $settings = [] ): void {
        if ( self::$initialized ) {
            return;
        }
        self::$initialized = true;

        if ( ! empty( $settings['auto_updates_core'] ) ) {
            add_filter( 'auto_update_core', '__return_false' );
            add_filter( 'allow_major_auto_core_updates', '__return_false' );
            add_filter( 'allow_minor_auto_core_updates', '__return_false' );
        }

        if ( ! empty( $settings['auto_updates_plugins'] ) ) {
            add_filter( 'auto_update_plugin', '__return_false' );
        }

        if ( ! empty( $settings['auto_updates_themes'] ) ) {
            add_filter( 'auto_update_theme', '__return_false' );
        }
    }
}
