<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Admin_Ajax {
	public function __construct() {
		add_action( 'init', [ $this, 'define_ajax_events' ], 0 );
	}

	public function define_ajax_events(): void {
		$events = [
			'complete_onboarding_step',
			'publish_website',
			'identify_action',
			'menu_action',
			'woocommerce_setup_store',
		];

		foreach ( $events as $event ) {
			add_action( 'wp_ajax_hostinger_' . $event, [ __CLASS__, $event ] );
		}
	}

	public static function woocommerce_setup_store(): void {
		$nonce        = sanitize_text_field( $_POST['nonce'] );
		$event_action = sanitize_text_field( $_POST['event_action'] );

		$security_check = self::request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$amplitude = new Hostinger_Amplitude();
		$amplitude->setup_store( $event_action );

		wp_send_json_success( [] );
	}

	public static function publish_website(): void {
		$publish = (bool) $_POST['maintenance'];
		$nonce         = sanitize_text_field( $_POST['nonce'] );

		$security_check = self::request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		Hostinger_Settings::update_setting( 'maintenance_mode', $publish ? 1 : 0 );

		require_once HOSTINGER_ABSPATH . 'includes/admin/onboarding/class-hostinger-onboarding.php';
		$content = new Hostinger_Onboarding();

		if ( has_action( 'litespeed_purge_all' ) ) {
			do_action( 'litespeed_purge_all' );
		}

		wp_send_json_success( [
			'published'   => $publish,
			'title'       => __( 'Website is published', 'hostinger' ),
			'description' => __( 'Congratulations! Your website is online.', 'hostinger' ),
			'content'     => $content->get_content(),
			'preview_url' => home_url(),
		] );
	}

	public static function complete_onboarding_step(): void {
		$step            = $_POST['step'];
		$nonce         = sanitize_text_field( $_POST['nonce'] );

		$security_check = self::request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$completed_steps = get_option( 'hostinger_onboarding_steps', [] );
		if ( ! in_array( $step, array_column($completed_steps, 'action'), true ) ) {
			$completed_steps[] = [
				'action' => $step,
				'date'   => date( 'Y-m-d H:i:s' ),
			];
		}
		Hostinger_Settings::update_setting( 'onboarding_steps', $completed_steps );

		wp_send_json_success( [] );
	}

	public static function identify_action(): void {
		$action = sanitize_text_field( $_POST['action_name'] ) ?? '';

		if ( in_array( $action, Hostinger_Admin_Actions::ACTIONS_LIST, true ) ) {
			setcookie($action, $action, time() + (86400), '/');
			wp_send_json_success( $action );
		} else {
			wp_send_json_error( 'Invalid action' );
		}
	}

	public static function menu_action(): void {
		$nonce         = sanitize_text_field( $_POST['nonce'] );
		$location      = sanitize_text_field( $_POST['location'] ) ?? '';
		$event_action  = sanitize_text_field( $_POST['event_action'] ) ?? '';

		$security_check = self::request_security_check( $nonce );

		if ( ! empty( $security_check ) ) {
			wp_send_json_error( $security_check );
		}

		$amplitude = new Hostinger_Amplitude();
		$amplitude->track_menu_action( $event_action, $location );

		wp_send_json_success( [] );
	}

	public static function request_security_check( $nonce ) {
		if ( ! wp_verify_nonce( $nonce, 'hts-ajax-nonce' ) ) {
			return 'Invalid nonce';
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return 'Lack of permissions';
		}

		return false;
	}
}

new Hostinger_Admin_Ajax();
