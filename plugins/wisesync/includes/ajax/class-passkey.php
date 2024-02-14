<?php
/**
 * Passkey Ajax
 *
 * @package WiseSync
 * @since 1.0.0
 *
 * phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
 * phpcs:disable WordPress.Security.NonceVerification.Missing
 */

namespace WiseSync\Ajax;

use lbuchs\WebAuthn\WebAuthn;

/**
 * Class Passkey
 */
class Passkey {

	/**
	 * Passkey Ajax Constructor
	 */
	public function __construct() {

		add_action( 'wp_ajax_passkey', array( $this, 'passkey' ) );
		add_action( 'wp_ajax_nopriv_passkey', array( $this, 'passkey' ) );
	}

	/**
	 * Passkey Ajax
	 */
	public function passkey() {

		// Verify request type.
		$request_type = isset( $_POST['request_type'] ) ? sanitize_text_field( wp_unslash( $_POST['request_type'] ) ) : '';
		if ( empty( $request_type ) ) {
			wp_send_json_error(
				array(
					'message' => 'Correct request type is required.',
				)
			);
		}

		$web_authn = new WebAuthn( get_option( 'blogname' ), wp_parse_url( get_option( 'home' ) )['host'] );
		switch ( $request_type ) {
			case 'get_credential_json':
				$current_user = wp_get_current_user();
				$credentials  = $web_authn->getCreateArgs(
					$current_user->user_login,
					$current_user->user_email,
					$current_user->display_name,
					30,
					false,
					true
				);
				$challenge    = ( $web_authn->getChallenge() )->getBinaryString();
				set_session( 'passkey_challange', $challenge );
				wp_send_json_success( array( 'credential' => $credentials ) );
				break;
			case 'store_credential':
				$current_user = wp_get_current_user();
				if ( ! isset( $_POST['client'] ) || ! isset( $_POST['attest'] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request not have client and attest.',
						)
					);
				} else {
					// Sanitize the input.
					$client = sanitize_text_field( wp_unslash( $_POST['client'] ) );
					$attest = sanitize_text_field( wp_unslash( $_POST['attest'] ) );
				}
				if ( empty( get_session( 'passkey_challange' ) ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request not able to find challange.',
						)
					);
				} else {
					$challenge = get_session( 'passkey_challange' );
					delete_session( 'passkey_challange' );
				}
				try {
					$data = $web_authn->processCreate(
						// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						base64_decode( $client ),
						base64_decode( $attest ),
						// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						$challenge,
						true,
						true,
						false
					);
					$data_store = array();
					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					$data_store['id'] = base64_encode( $data->credentialId );
					// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					$data_store['publicKey'] = $data->credentialPublicKey;
					$store_status            = update_user_meta( $current_user->ID, 'passkey', wp_slash( wp_json_encode( $data_store ) ) );
					if ( ! $store_status ) {
						wp_send_json_error(
							array(
								'message' => 'Credential not stored.',
							)
						);
					} else {
						wp_send_json_success(
							array(
								'message' => 'Credential stored successfully.',
							)
						);
					}
				} catch ( \Exception $ex ) {
					delete_session( 'passkey_challange' );
					wp_send_json_error(
						array(
							'message' => $ex->getMessage(),
						)
					);
				}
				break;
			case 'get_challenge':
				if ( isset( $_POST['user'] ) ) {
					$user    = sanitize_text_field( wp_unslash( $_POST['user'] ) );
					$user_id = is_email( $user ) ? get_user_by( 'email', $user )->ID : get_user_by( 'login', $user )->ID;
					$passkey = json_decode( get_user_meta( $user_id, 'passkey', true ) );
					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$args = $web_authn->getGetArgs( array( base64_decode( $passkey->id ) ), 30 );
					// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$challenge = ( $web_authn->getChallenge() )->getBinaryString();
					set_session( 'passkey_challange', $challenge );
					wp_send_json_success(
						array(
							'challenge' => $args,
							'user'      => $user,
							'publicKey' => $passkey->publicKey,
						),
					);
				} else {
					wp_send_json_error(
						array(
							'message' => 'Invalid request.',
						)
					);
				}
				break;
			case 'verify_challenge':
				if ( isset( $_POST['user_id'] ) ) {
					$user    = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
					$user_id = is_email( $user ) ? get_user_by( 'email', $user )->ID : get_user_by( 'login', $user )->ID;
					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
					$passkey = json_decode( get_user_meta( $user_id, 'passkey', true ) );
					// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize
				} else {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. userid not found.',
						)
					);
				}
				if ( empty( get_session( 'passkey_challange' ) ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. challange session not found.',
						)
					);
				} else {
					$challenge = get_session( 'passkey_challange' );
					delete_session( 'passkey_challange' );
				}
				if ( ! isset( $_POST['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. id not found.',
						)
					);
				} else {
					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$id = base64_decode( sanitize_text_field( wp_unslash( $_POST['id'] ) ) );
					if ( base64_decode( $passkey->id ) !== $id ) {
						// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						wp_send_json_error(
							array(
								'message' => 'Invalid request. id not matched.',
							)
						);
					}
				}
				if ( ! isset( $_POST['client'] ) || ! isset( $_POST['auth'] ) || ! isset( $_POST['sig'] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. missing client, auth and sig.',
						)
					);
				} else {
					$client = sanitize_text_field( wp_unslash( $_POST['client'] ) );
					$auth   = sanitize_text_field( wp_unslash( $_POST['auth'] ) );
					$sig    = sanitize_text_field( wp_unslash( $_POST['sig'] ) );
				}
				try {
					$web_authn->processGet(
						// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						base64_decode( $client ),
						base64_decode( $auth ),
						base64_decode( $sig ),
						// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						$passkey->publicKey,
						$challenge
					);
					wp_send_json_success( array( 'message' => 'Challenge verified successfully.' ) );
				} catch ( \Exception $ex ) {
					wp_send_json_error(
						array(
							'message' => $ex->getMessage(),
						)
					);
				}
				break;
			default:
				wp_send_json_error(
					array(
						'message' => 'Invalid request type.',
					)
				);
		}
	}
}
