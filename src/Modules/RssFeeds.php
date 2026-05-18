<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class RssFeeds {

    public static function init( array $settings = [] ): void {
        // Redirect all feed types.
        add_action( 'do_feed', [ __CLASS__, 'redirect' ], 1 );
        add_action( 'do_feed_rdf', [ __CLASS__, 'redirect' ], 1 );
        add_action( 'do_feed_rss', [ __CLASS__, 'redirect' ], 1 );
        add_action( 'do_feed_rss2', [ __CLASS__, 'redirect' ], 1 );
        add_action( 'do_feed_atom', [ __CLASS__, 'redirect' ], 1 );

        // Remove feed links from <head>.
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
    }

    public static function redirect(): void {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
}
