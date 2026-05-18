<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class Gutenberg {

    public static function init( array $settings = [] ): void {
        // Disable block editor for posts.
        add_filter( 'use_block_editor_for_post', '__return_false' );
        add_filter( 'use_block_editor_for_post_type', '__return_false' );

        // Dequeue block editor styles from frontend.
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'dequeue_block_styles' ], 100 );
    }

    public static function dequeue_block_styles(): void {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
        wp_dequeue_style( 'global-styles' );
    }
}
