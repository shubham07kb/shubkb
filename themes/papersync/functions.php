<?php
/**
 * Functions and definitions
 *
 * This file is used to register all the functions and definitions.
 *
 * PHP version 7.2
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
