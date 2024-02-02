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
require_once PAPERSYNC_THEME_DIR . '/functions/functions.php';

// Load Blocks.
add_action( 'init', 'include_blocks' );

/**
 * Locate template.
 *
 * @param string $template Template.
 */
function locate_theme_templates( $template ) {

	if ( is_singular( 'course' ) && 0 === get_post_field( 'post_parent', get_the_ID() ) && 'edit' !== get_post_field( 'post_name', get_the_ID() ) ) {
		return locate_template( 'templates/single-course-parent.html', true );
	} elseif ( is_singular( 'course' ) && 0 !== get_post_field( 'post_parent', get_the_ID() ) && 'edit' !== get_post_field( 'post_name', get_the_ID() ) ) {
		return locate_template( 'templates/single-course-child.html', true );
	}
	return $template;
}
add_filter( 'single_template', 'locate_theme_templates' );
