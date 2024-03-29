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
require_once WISESYNC_PLUGIN_DIR . 'functions/functions.php';

// Call Class.
use WiseSync\Post_Types\Post_Types;

new Post_Types();
new \WiseSync\Rewrite\Rewrite();
new \WiseSync\Rest_API\Rest_API();
new \WiseSync\Ajax\Ajax();


// Activation hook.
register_activation_hook(
	__FILE__,
	function () {
		// Flush rewrite rules.
		\WiseSync\Rewrite\Rewrite::custom_courses_rewrite_rules();
		flush_rewrite_rules();
	}
);

/**
 * Display all rewrite rules.
 */
function display_all_rewrite_rules() {
	global $wp_rewrite;

	// Get all rewrite rules.
	$rewrite_rules = $wp_rewrite->rewrite_rules();
}

// Hook the function to a specific action or filter
// For example, you can use 'init' or 'wp_loaded'.
add_action( 'init', 'display_all_rewrite_rules' );

// Cron Log.
add_action( 'shutdown', 'wisesync_log_cron' );
