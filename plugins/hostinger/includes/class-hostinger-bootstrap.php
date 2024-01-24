<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Bootstrap {
	protected Hostinger_Loader $loader;

	public function __construct() {
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-loader.php';
		$this->loader = new Hostinger_Loader();
	}

	public function run(): void {
		$this->load_dependencies();
		$this->set_locale();
		$this->loader->run();
	}

	private function load_dependencies(): void {
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-config.php';
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-helper.php';
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-errors.php';
		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-settings.php';
		require_once HOSTINGER_ABSPATH . 'includes/requests/class-hostinger-requests-client.php';

		if ( ! empty( Hostinger_Helper::get_api_token() ) ) {
			require_once HOSTINGER_ABSPATH . 'includes/amplitude/class-hostinger-amplitude-actions.php';
			require_once HOSTINGER_ABSPATH . 'includes/amplitude/class-hostinger-amplitude.php';
		}

		$this->load_onboarding_dependencies();
		$this->load_public_dependencies();

		if ( is_admin() ) {
			$this->load_admin_dependencies();
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-cli.php';
		}

		require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-i18n.php';

		if ( get_option( 'hostinger_maintenance_mode', 0 ) ) {
			require_once HOSTINGER_ABSPATH . 'includes/class-hostinger-coming-soon.php';
		}
	}

	private function set_locale() {

		$plugin_i18n = new Hostinger_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function load_admin_dependencies(): void {
		require_once HOSTINGER_ABSPATH . 'includes/admin/class-hostinger-admin-assets.php';
		require_once HOSTINGER_ABSPATH . 'includes/admin/class-hostinger-admin-menu.php';
		require_once HOSTINGER_ABSPATH . 'includes/admin/class-hostinger-admin-ajax.php';
		require_once HOSTINGER_ABSPATH . 'includes/admin/class-hostinger-admin-redirect.php';
	}

	private function load_public_dependencies(): void {
		require_once HOSTINGER_ABSPATH . 'includes/public/class-hostinger-public-assets.php';
	}

	private function load_onboarding_dependencies(): void {
		require_once HOSTINGER_ABSPATH . 'includes/admin/class-hostinger-admin-actions.php';
		require_once HOSTINGER_ABSPATH . 'includes/admin/onboarding/class-hostinger-onboarding-settings.php';

		if ( ! Hostinger_Onboarding_Settings::all_steps_completed() ) {
			require_once HOSTINGER_ABSPATH . 'includes/admin/onboarding/class-hostinger-autocomplete-steps.php';
		}
	}
}
