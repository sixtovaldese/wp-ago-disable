<?php
/**
 * Plugin Name: aGo Disable
 * Plugin URI:  https://ago.cl/herramientas/
 * Description: Dashboard of feature toggles to disable WordPress features. Disable comments, Gutenberg, auto-updates, pingbacks, search, RSS feeds and more.
 * Version:     1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Author:      aGo Lab
 * Author URI:  https://ago.cl
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ago-disable
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'AGODISABLE_VERSION', '1.0.0' );
define( 'AGODISABLE_FILE', __FILE__ );
define( 'AGODISABLE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AGODISABLE_URL', plugin_dir_url( __FILE__ ) );

// PSR-4 Autoloader
spl_autoload_register( function ( string $class ): void {
    $prefix = 'AgoLab\\Disable\\';
    if ( strncmp( $class, $prefix, strlen( $prefix ) ) !== 0 ) {
        return;
    }
    $relative = substr( $class, strlen( $prefix ) );
    $file     = AGODISABLE_PATH . 'src/' . str_replace( '\\', '/', $relative ) . '.php';
    if ( file_exists( $file ) ) {
        require_once $file;
    }
} );

// Boot
add_action( 'plugins_loaded', [ AgoLab\Disable\Plugin::class, 'instance' ] );
