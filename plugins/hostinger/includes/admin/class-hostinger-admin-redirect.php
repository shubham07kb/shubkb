<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Admin_Redirect {
	private string $platform;
	public const PLATFORM_HPANEL = 'hpanel';

	public function __construct() {

		if ( ! Hostinger_Settings::get_setting( 'first_login_at' ) ) {
			Hostinger_Settings::update_setting( 'first_login_at', date( 'Y-m-d H:i:s' ) );
		}

		if ( ! isset( $_GET['platform'] ) ) {
			return;
		}

		$this->platform = $_GET['platform'];
		$this->loginRedirect();
	}

	private function loginRedirect(): void {
		if ( $this->platform === self::PLATFORM_HPANEL ) {
			add_action( 'init', static function () {
				$redirect_url = admin_url( 'admin.php?page=hostinger' );
				wp_redirect( $redirect_url );
				exit;
			} );
		}
	}
}

new Hostinger_Admin_Redirect();
