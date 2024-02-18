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

		// verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'client_data' ) ) {
			wp_send_json_error( array( 'error' => 'nonce not defined' ) );
		}

		// store data in session.
		if ( isset( $_POST['data'] ) ) {
			$client_data             = json_decode( sanitize_text_field( wp_unslash( $_POST['data'] ) ), true );
			$_SESSION['client_data'] = $client_data;
		}
		$_SESSION['client_data_ua'] = $client_data['data']['device']['ua'];
		if ( null === $client_data['data']['device']['parsed'] ) {
			$_SESSION['client_data_ua_type'] = 'ua';
		} else {
			$_SESSION['client_data_ua_type'] = 'parsed';
			$browser                         = $client_data['data']['device']['parsed']['brands'][1]['brand'];
			$platform                        = $client_data['data']['device']['parsed']['platform'];
			$device                          = $client_data['data']['device']['parsed']['model'];
			$_SESSION['client_data_broswer'] = $browser;
			$_SESSION['client_data_os']      = $platform;
			$_SESSION['client_data_device']  = $device;
			$_SESSION['client_data_pasred']  = ( '' !== $browser ? $browser . ( '' !== $platform ? ', ' . $platform . ( '' !== $device ? ', ' . $device : '' ) : ( '' !== $device ? ', ' . $device : '' ) ) : ( '' !== $platform ? $platform . ( '' !== $device ? ', ' . $device : '' ) : ( '' !== $device ? $device : '' ) ) );
		}

		wp_send_json_success( array( 'stored' => true ) );
	}
}
