<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/includes
 * @author     Hostinger <info@hostinger.com>
 */
class Hostinger_Ai_Assistant {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hostinger_Ai_Assistant_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'HOSTINGER_AI_ASSISTANT_VERSION' ) ) {
			$this->version = HOSTINGER_AI_ASSISTANT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'hostinger-ai-assistant';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hostinger_Ai_Assistant_Loader. Orchestrates the hooks of the plugin.
	 * - Hostinger_Ai_Assistant_i18n. Defines internationalization functionality.
	 * - Hostinger_Ai_Assistant_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for config values.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-config.php';

		/**
		 * The class responsible for errors.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-errors.php';

		/**
		 * The class responsible for plugin updates.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-updates.php';

		/**
		 * The class responsible for all helper functions.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-helper.php';

		/**
		 * The class responsible requests client.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/requests/class-hostinger-ai-assistant-requests-client.php';

		/**
		 * The class responsible for amplitude actions.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/amplitude/class-hostinger-ai-assistant-amplitude-actions.php';

		/**
		 * The class responsible for amplitude events.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/amplitude/class-hostinger-ai-assistant-amplitude.php';

		/**
		 * The class responsible for SEO.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/seo/class-hostinger-ai-assistant-seo.php';

		/**
		 * The class responsible for content generation.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/content/class-hostinger-ai-assistant-content-generation.php';

		/**
		 * The class responsible for content filters.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/content/class-hostinger-ai-assistant-content-filters.php';

		/**
		 * The class responsible for frontend translations.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-translations.php';

		/**
		 * The class responsible for all admin notices.
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-notices.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hostinger-ai-assistant-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-admin.php';

		/**
		 * The class responsible for defining all admin menus.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-menus.php';

		/**
		 * The class responsible for defining all admin buttons.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-buttons.php';

		/**
		 * The class responsible for defining all redirects.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hostinger-ai-assistant-redirects.php';

		/**
		 * The class responsible for all requests to AI Assistant API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/requests/class-hostinger-ai-assistant-requests.php';

		$this->loader = new Hostinger_Ai_Assistant_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hostinger_Ai_Assistant_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hostinger_Ai_Assistant_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Hostinger_Ai_Assistant_Admin( $this->get_plugin_name(), $this->get_version() );
		$helper       = new Hostinger_Ai_Assistant_Notices();
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		if ( Hostinger_Ai_Assistant_Helper::is_plugin_active( 'hostinger' ) ) {
			$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'enqueue_custom_editor_assets' );
			$this->loader->add_action( 'hostinger_ai_assistant_tab_view', $plugin_admin, 'create_ai_assistant_tab_view' );
		} else {
			$this->loader->add_action( 'admin_notices', $helper, 'main_plugin_notice' );
		}

		if ( ! Hostinger_Ai_Assistant_Helper::get_api_token() ) {
			$this->loader->add_action( 'admin_notices', $helper, 'api_token_plugin_notice' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Hostinger_Ai_Assistant_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
