<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class Search {

    public static function init( array $settings = [] ): void {
        // Remove search form.
        add_filter( 'get_search_form', '__return_empty_string' );

        // Neutralize search queries.
        add_action( 'parse_query', [ __CLASS__, 'neutralize_query' ] );

        // Redirect search results page.
        add_action( 'template_redirect', [ __CLASS__, 'redirect' ] );

        // Remove search widget.
        add_action( 'widgets_init', [ __CLASS__, 'unregister_widget' ] );
    }

    public static function neutralize_query( \WP_Query $query ): void {
        if ( ! is_admin() && $query->is_search ) {
            $query->is_search       = false;
            $query->query_vars['s'] = false;
            $query->query['s']      = false;
        }
    }

    public static function redirect(): void {
        if ( is_search() ) {
            wp_safe_redirect( home_url( '/' ), 301 );
            exit;
        }
    }

    public static function unregister_widget(): void {
        unregister_widget( 'WP_Widget_Search' );
    }
}
