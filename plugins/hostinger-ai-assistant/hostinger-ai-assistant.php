<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://hostinger.com
 * @since             1.0.0
 * @package           Hostinger_Ai_Assistant
 *
 * @wordpress-plugin
 * Plugin Name:       Hostinger AI Assistant
 * Plugin URI:        https://hostinger.com
 * Description:       Hostinger AI Assistant plugin.
 * Version:           1.6.8
 * Author:            Hostinger
 * Requires PHP:      7.4
 * Requires at least: 5.0
 * Tested up to:      6.3
 * Author URI:        https://hostinger.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hostinger-ai-assistant
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */

define( 'HOSTINGER_AI_ASSISTANT_VERSION', '1.6.8' );

/**
 * Plugin path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_ABSPATH' ) ) {
	define( 'HOSTINGER_AI_ASSISTANT_ABSPATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin file path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_PLUGIN_FILE' ) ) {
	define( 'HOSTINGER_AI_ASSISTANT_PLUGIN_FILE', __FILE__ );
}

/**
 * Plugin assets path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_ASSETS_URL' ) ) {
	define( 'HOSTINGER_AI_ASSISTANT_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
}

/**
 * Hostinger config path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_CONFIG_PATH' ) ) {
	define( 'HOSTINGER_AI_ASSISTANT_CONFIG_PATH', ABSPATH . '/.private/config.json' );
}
/**
 * Hostinger api token path.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN' ) ) {
	$path = explode('/', __DIR__);
	$serverRootPath = '/' . $path[1] . '/' . $path[2];
	define( 'HOSTINGER_AI_ASSISTANT_WP_AI_TOKEN', $serverRootPath . '/.api_token' );
}

/**
 * Hostinger default rest api url.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_REST_URI' ) ) {
	define( 'HOSTINGER_AI_ASSISTANT_REST_URI', 'https://rest-hosting.hostinger.com' );
}

/**
 * Hostinger preview domain url.
 */

if ( ! defined( 'HOSTINGER_AI_ASSISTANT_PREVIEW_SUFIX' ) ) {
	define('HOSTINGER_AI_ASSISTANT_PREVIEW_SUFIX', 'preview-domain.com');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hostinger-ai-assistant-activator.php
 */
function activate_hostinger_ai_assistant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant-activator.php';
	Hostinger_Ai_Assistant_Activator::activate();
	do_action('activate_hostinger_ai_assistant');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hostinger-ai-assistant-deactivator.php
 */
function deactivate_hostinger_ai_assistant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant-deactivator.php';
	Hostinger_Ai_Assistant_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hostinger_ai_assistant' );
register_deactivation_hook( __FILE__, 'deactivate_hostinger_ai_assistant' );

require_once HOSTINGER_AI_ASSISTANT_ABSPATH . '/vendor/autoload.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hostinger-ai-assistant.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hostinger_ai_assistant() {

	$plugin = new Hostinger_Ai_Assistant();
	$plugin->run();

}
run_hostinger_ai_assistant();
