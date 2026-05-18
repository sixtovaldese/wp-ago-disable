<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class AdminBar {

    public static function init( array $settings = [] ): void {
        add_filter( 'show_admin_bar', [ __CLASS__, 'maybe_hide' ] );
    }

    public static function maybe_hide( bool $show ): bool {
        if ( current_user_can( 'manage_options' ) ) {
            return $show;
        }
        return false;
    }
}
