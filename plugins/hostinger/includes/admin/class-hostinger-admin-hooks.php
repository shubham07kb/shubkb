<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Admin_Hooks {
	private $settings;

	public function __construct() {
		$this->settings = new Hostinger_Settings();

		add_action( 'admin_footer', array( $this, 'rate_plugin' ) );
	}

	public function rate_plugin(): void {
		$promotional_banner_hidden = get_transient( 'hts_hide_promotional_banner_transient' );
		$two_hours_in_seconds      = 7200;

		if ( $promotional_banner_hidden && time() > $promotional_banner_hidden + $two_hours_in_seconds ) {
			require_once HOSTINGER_ABSPATH . 'includes/admin/views/partials/hostinger-rate-us.php';
		}
	}
}

$hostinger_admin_hooks = new Hostinger_Admin_Hooks();
