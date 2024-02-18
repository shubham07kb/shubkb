<?php
/**
 * Ajax
 *
 * This file is used to register all the ajax.
 *
 * @package WiseSync
 * @since 1.0.0
 *
 * phpcs:disable WordPress.Security.NonceVerification.Recommended
 */

namespace WiseSync\Ajax;

/**
 * Ajax
 *
 * This class is used to register all the ajax.
 */
class Other {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'wp_ajax_set_client_data', array( $this, 'set_client_data' ) );
		add_action( 'wp_ajax_nopriv_set_client_data', array( $this, 'set_client_data' ) );
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function set_client_data() {

		error_log( 'in ajax t' );
		error_log( $_POST['nonce'] );
		// verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'client_data' ) ) {
			wp_send_json_error( array( 'error' => 'nonce not defined' ) );
		}

		// store data in session.
		if ( isset( $_POST['data'] ) ) {
			$_SESSION['client_data'] = json_decode( sanitize_text_field( wp_unslash( $_POST['data'] ) ), true );
		}

		wp_send_json_success( array( 'stored' => true ) );
	}
}
