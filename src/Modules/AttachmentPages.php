<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class AttachmentPages {

    public static function init( array $settings = [] ): void {
        add_action( 'template_redirect', [ __CLASS__, 'redirect' ] );
    }

    public static function redirect(): void {
        if ( ! is_attachment() ) {
            return;
        }

        $post   = get_post();
        $parent = ( $post && $post->post_parent ) ? get_permalink( $post->post_parent ) : false;
        $url    = $parent ?: home_url( '/' );

        wp_safe_redirect( $url, 301 );
        exit;
    }
}
