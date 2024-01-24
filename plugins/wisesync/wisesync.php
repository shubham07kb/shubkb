<?php
/**
 * Plugin Name: WiseSync
 * Plugin URI: https://shubkb.com
 * Description: WiseSync is a plugin that sync multiple feature in one place.
 * Version: 1.0.0
 * Author: Shubham Kumar
 * Author URI: https://shubkb.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wisesync
 * Domain Path: /languages
 * PHP version 8.0
 *
 * @category Core
 * @package  WiseSync
 * @author   Shubham Kumar Bansal <shub@shubkb.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPL2
 * @link     https://shubkb.com
 */

// Check if the file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'WISESYNC_VERSION', '1.0.0' );
define( 'WISESYNC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WISESYNC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WISESYNC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WISESYNC_PLUGIN_FILE', __FILE__ );

// Load autoloader.
require_once WISESYNC_PLUGIN_DIR . 'autoloader.php';
