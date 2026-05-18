<?php

namespace AgoLab\Disable\Modules;

defined( 'ABSPATH' ) || exit;

class AdminNotices {

    public static function init( array $settings = [] ): void {
        add_action( 'admin_notices', [ __CLASS__, 'start_buffer' ], 0 );
        add_action( 'admin_notices', [ __CLASS__, 'end_buffer' ], PHP_INT_MAX );
    }

    public static function start_buffer(): void {
        ob_start();
    }

    public static function end_buffer(): void {
        $output = ob_get_clean();
        if ( empty( $output ) ) {
            return;
        }

        // Keep only WP core notices (those with wp- prefix classes or specific core patterns).
        preg_match_all(
            '/<div[^>]*class="[^"]*\b(notice|error|updated|update-nag)\b[^"]*"[^>]*>.*?<\/div>/is',
            $output,
            $matches
        );

        if ( empty( $matches[0] ) ) {
            return;
        }

        foreach ( $matches[0] as $notice ) {
            // Allow WP core notices, filter out third-party ones.
            // Core notices typically don't have plugin-specific prefixes.
            if (
                str_contains( $notice, 'update-nag' ) ||
                str_contains( $notice, 'notice-warning' ) && str_contains( $notice, 'wp-' ) ||
                str_contains( $notice, 'notice-error' ) && ! preg_match( '/class="[^"]*\b[a-z]+-notice\b/i', $notice )
            ) {
                echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
    }
}
