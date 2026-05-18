<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class AuthorArchives {

    public static function init( array $settings = [] ): void {
        add_action( 'template_redirect', [ __CLASS__, 'redirect' ] );
    }

    public static function redirect(): void {
        if ( is_author() ) {
            wp_safe_redirect( home_url( '/' ), 301 );
            exit;
        }
    }
}
