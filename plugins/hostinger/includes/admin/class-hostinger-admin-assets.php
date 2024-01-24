<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Hostinger_Admin_Assets
 *
 * Handles the enqueueing of styles and scripts for the Hostinger admin pages.
 */
class Hostinger_Admin_Assets {
	/**
	 * @var Hostinger_Helper Instance of the Hostinger_Helper class.
	 */
	private Hostinger_Helper $helper;

	public function __construct() {
		$this->helper = new Hostinger_Helper();
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueues styles for the Hostinger admin pages.
	 */
	public function admin_styles(): void {
		if ( $this->helper->is_hostinger_admin_page() ) {
			wp_enqueue_style( 'hostinger_main_styles', HOSTINGER_ASSETS_URL . '/css/main.css', array(), HOSTINGER_VERSION );
		}

		if ( $this->helper->is_preview_domain() && is_user_logged_in() ) {
			wp_enqueue_style( 'hostinger-preview-styles', HOSTINGER_ASSETS_URL . '/css/hts-preview.css', array(), HOSTINGER_VERSION );
		}
	}

	/**
	 * Enqueues scripts for the Hostinger admin pages.
	 */
	public function admin_scripts(): void {
		if ( $this->helper->is_hostinger_admin_page() ) {
			wp_enqueue_script( 'hostinger_main_scripts', HOSTINGER_ASSETS_URL . '/js/main.js', array(
				'jquery',
				'wp-i18n',
			), HOSTINGER_VERSION, false );
		}

		wp_enqueue_script( 'hostinger_global_scripts', HOSTINGER_ASSETS_URL . '/js/global-scripts.js', array(
			'jquery',
			'wp-i18n',
		), HOSTINGER_VERSION, false );

		if ( ! empty( Hostinger_Helper::get_api_token() ) ) {
			wp_enqueue_script( 'hostinger_requests_scripts', HOSTINGER_ASSETS_URL . '/js/requests.js', array(
				'jquery',
				'wp-i18n',
			), HOSTINGER_VERSION, false );
			wp_localize_script( 'hostinger_requests_scripts', 'hostingerContainer', array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'hts-ajax-nonce' ),
			) );
		}
	}
}

new Hostinger_Admin_Assets();
