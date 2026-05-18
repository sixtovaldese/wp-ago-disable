<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class WpNewsWidget {

    public static function init( array $settings = [] ): void {
        add_action( 'wp_dashboard_setup', [ __CLASS__, 'remove_widget' ] );
    }

    public static function remove_widget(): void {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    }
}
