<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class Pingbacks {

    public static function init( array $settings = [] ): void {
        // Remove XML-RPC pingback methods.
        add_filter( 'xmlrpc_methods', [ __CLASS__, 'remove_xmlrpc_methods' ] );

        // Close pings on all posts.
        add_filter( 'pings_open', '__return_false', 20, 2 );

        // Remove X-Pingback header.
        add_filter( 'wp_headers', [ __CLASS__, 'remove_pingback_header' ] );

        // Disable self-pings.
        add_action( 'pre_ping', [ __CLASS__, 'disable_self_pings' ] );
    }

    /**
     * @param array<string, mixed> $methods
     * @return array<string, mixed>
     */
    public static function remove_xmlrpc_methods( array $methods ): array {
        unset( $methods['pingback.ping'] );
        unset( $methods['pingback.extensions.getPingbacks'] );
        return $methods;
    }

    /**
     * @param array<string, string> $headers
     * @return array<string, string>
     */
    public static function remove_pingback_header( array $headers ): array {
        unset( $headers['X-Pingback'] );
        return $headers;
    }

    /**
     * @param array<int, string> $links
     */
    public static function disable_self_pings( array &$links ): void {
        $home = home_url();
        foreach ( $links as $i => $link ) {
            if ( str_starts_with( $link, $home ) ) {
                unset( $links[ $i ] );
            }
        }
    }
}
