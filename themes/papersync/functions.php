<?php
/**
 * Functions and definitions
 *
 * This file is used to register all the functions and definitions.
 *
 * PHP version 8.0
 *
 * @category Core
 * @package  PaperSync
 * @author   Shubham Kumar Bansal <shub@shubkb.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPL2
 * @link     https://shubkb.com
 */

// Check if the file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants.
define( 'PAPERSYNC_VERSION', '1.0.0' );
define( 'PAPERSYNC_THEME_DIR', get_template_directory() );
define( 'PAPERSYNC_THEME_URL', get_template_directory_uri() );
define( 'PAPERSYNC_THEME_BASENAME', get_template() );
define( 'PAPERSYNC_THEME_FILE', __FILE__ );

// Load autoloader.
require_once PAPERSYNC_THEME_DIR . '/autoloader.php';

/**
 * Include blocks
 *
 * @return void
 */
function include_blocks() {
	$scan = scandir( PAPERSYNC_THEME_DIR . '/inc/blocks' );
	foreach ( $scan as $file ) {
		if ( is_dir( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" ) && '.' !== $file && '..' !== $file ) {
			$scan_in = scandir( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" );
			if ( in_array( 'block.php', $scan_in, true ) && in_array( 'build', $scan_in, true ) && in_array( 'block.json', $scan_in, true ) ) {
				$upper_file = ucwords( str_replace( '-', '_', $file ) );
				$lower_file = str_replace( '-', '_', $file );
				register_block_type_from_metadata(
					PAPERSYNC_THEME_DIR . "/inc/blocks/$file",
					array( 'render_callback' => array( "PaperSync\Blocks\\$upper_file\Block\\$upper_file", $lower_file ) )
				);
			} elseif ( in_array( 'build', $scan_in, true ) && in_array( 'block.json', $scan_in, true ) ) {
				register_block_type_from_metadata( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" );
			}
		}
	}
}
add_action( 'init', 'include_blocks' );
