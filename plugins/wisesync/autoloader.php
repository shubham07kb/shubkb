<?php
/**
 * Auto load class files
 * php version 8.0
 *
 * @category core
 * @package  WiseSync
 * @author   Shubham Kumar Bansal <shub@shubkb.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GNU/GPLv2
 * @link     https://shubkb.com
 */

// Register autoloader for Plugin.
spl_autoload_register(
	function ( $autoloader_class ) {

		$autoloader_class       = str_replace( '_', '-', strtolower( $autoloader_class ) );
		$autoloader_class_array = explode( '\\', $autoloader_class );
		$autoloader_class_array[ count( $autoloader_class_array ) - 1 ] = 'class-' . $autoloader_class_array[ count( $autoloader_class_array ) - 1 ];
		array_shift( $autoloader_class_array );
		$autoloader_class = implode( '/', $autoloader_class_array );
		// Base directory for your plugin classes.

		// Replace namespace separators with directory separators.
		$file = plugin_dir_path( __FILE__ ) . 'includes/' . str_replace( '\\', '/', $autoloader_class ) . '.php';

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

// Load autoloader for Composer.
require_once WISESYNC_PLUGIN_DIR . 'vendor/autoload.php';
