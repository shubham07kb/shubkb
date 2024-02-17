<?php
/**
 * Passkey Ajax
 *
 * @package WiseSync
 * @since 1.0.0
 *
 * phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
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

		global $client_data;

		// Verify nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'passkey' ) ) {
			wp_send_json_error(
				array(
					'message' => 'Invalid request.',
				)
			);
		}

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
				$passkey      = get_user_meta( $current_user->ID, 'passkey', true );
				if ( $passkey && ! empty( $passkey ) ) {
					$passkey = json_decode( $passkey );
					if ( 20 <= count( $passkey ) ) {
						wp_send_json_error(
							array(
								'message' => 'Credential Limit reached.',
							)
						);
					}
				}
				$credentials = $web_authn->getCreateArgs(
					$current_user->user_login,
					$current_user->user_email,
					$current_user->display_name,
					30,
					false,
					true
				);
				$challenge   = ( $web_authn->getChallenge() )->getBinaryString();
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
					$data_store['publicKey']  = $data->credentialPublicKey;
					$data_store['createdAt']  = gmdate( 'Y-m-d H:i:s' );
					$data_store['createdOn']  = $client_data['device_string'];
					$data_store['lastUsed']   = gmdate( 'Y-m-d H:i:s' );
					$data_store['lastUsedOn'] = $client_data['device_string'];
					$passkey                  = get_user_meta( $current_user->ID, 'passkey', true );
					if ( $passkey && ! empty( $passkey ) ) {
						$passkey = json_decode( $passkey );
						if ( 20 <= count( $passkey ) ) {
							wp_send_json_error(
								array(
									'message' => 'Credential Limit Reached.',
								)
							);
						}
						array_push( $passkey, $data_store );
					} else {
						$passkey = array( $data_store );
					}
					$store_status = update_user_meta( $current_user->ID, 'passkey', wp_slash( wp_json_encode( $passkey ) ) );
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
					$passkey = get_user_meta( $user_id, 'passkey', true );
					if ( ! $passkey ) {
						wp_send_json_error(
							array(
								'message' => 'Passkey not found.',
							)
						);
					}
					$passkey = json_decode( $passkey );
					if ( ! $passkey ) {
						wp_send_json_error(
							array(
								'message' => 'Passkey stored Incorrectly, Remove all passkey and try again.',
							)
						);
					}
					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$credentials_id = array();
					$passkey_count  = count( $passkey );
					for ( $i = 0; $i < $passkey_count; $i++ ) {
						array_push( $credentials_id, base64_decode( $passkey[ $i ]->id ) );
					}
					$args = $web_authn->getGetArgs( $credentials_id, 30 );
					// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$challenge = ( $web_authn->getChallenge() )->getBinaryString();
					set_session( 'passkey_challange', $challenge );
					if ( isset( $_POST['login'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['login'] ) ) ) {
						set_session( 'passkey_login', true );
					}
					if ( isset( $_POST['redirect'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['redirect'] ) ) ) {
						set_session( 'passkey_redirect', true );
						if ( isset( $_POST['redirect_url'] ) ) {
							set_session( 'passkey_redirect_url', sanitize_text_field( wp_unslash( $_POST['redirect_url'] ) ) );
						}
					}
					wp_send_json_success(
						array(
							'challenge' => $args,
							'user'      => $user,
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
				$passkey_login = get_session( 'passkey_login' );
				$redirect      = get_session( 'passkey_redirect' );
				$redirect_url  = get_session( 'passkey_redirect_url' );
				if ( $passkey_login ) {
					delete_session( 'passkey_login' );
				}
				if ( $redirect ) {
					delete_session( 'passkey_redirect' );
					delete_session( 'passkey_redirect_url' );
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
				if ( isset( $_POST['user_id'] ) ) {
					$user    = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
					$user_id = is_email( $user ) ? get_user_by( 'email', $user )->ID : get_user_by( 'login', $user )->ID;
					$passkey = get_user_meta( $user_id, 'passkey', true );
					$passkey = get_user_meta( $user_id, 'passkey', true );
					if ( ! $passkey ) {
						wp_send_json_error(
							array(
								'message' => 'Passkey not found.',
							)
						);
					}
					$passkey = json_decode( $passkey );
					if ( ! $passkey ) {
						wp_send_json_error(
							array(
								'message' => 'Passkey stored Incorrectly, Remove all passkey and try again.',
							)
						);
					}
				} else {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. userid not found.',
						)
					);
				}
				if ( ! isset( $_POST['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. id not found.',
						)
					);
				} else {

					// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					$id            = base64_decode( sanitize_text_field( wp_unslash( $_POST['id'] ) ) );
					$passkey_count = count( $passkey );
					$is_matched    = false;
					$public_key    = '';
					for ( $i = 0; $i < $passkey_count; $i++ ) {

						if ( base64_decode( $passkey[ $i ]->id ) === $id ) {

							// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
							$public_key                = $passkey[ $i ]->publicKey;
							$passkey[ $i ]->lastUsed   = gmdate( 'Y-m-d H:i:s' );
							$passkey[ $i ]->lastUsedOn = $client_data['device_string'];
							$is_matched                = true;
							break;
						}
					}
					if ( ! $is_matched ) {

						wp_send_json_error(
							array(
								'message' => 'Invalid request. id not matched.',
								'id'      => $id,
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
						$public_key,
						$challenge
					);
					update_user_meta( $user_id, 'passkey', wp_slash( wp_json_encode( $passkey ) ) );
					if ( $passkey_login ) {

						wp_set_current_user( $user_id );
						wp_set_auth_cookie( $user_id, true );
					}
					if ( $redirect ) {
						if ( empty( $redirect_url ) ) {

							// if user is admin then redirect to admin dashboard, else redirect to home page.
							if ( current_user_can( 'manage_options' ) ) {
								$redirect_url = admin_url();
							} else {
								$redirect_url = home_url();
							}
						}
						wp_safe_redirect( $redirect_url, 302, get_option( 'blogname' ) );
						exit;
					}
					wp_send_json_success( array( 'message' => 'Challenge verified successfully.' ) );
				} catch ( \Exception $ex ) {
					wp_send_json_error(
						array(
							'message' => $ex->getMessage(),
						)
					);
				}
				break;
			case 'remove_credential':
				$current_user = wp_get_current_user();
				if ( ! isset( $_POST['index'] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. id not found.',
						)
					);
				} else {
					$index = absint( $_POST['index'] ) - 1;
				}
				$passkey = get_user_meta( $current_user->ID, 'passkey', true );
				if ( ! $passkey ) {
					wp_send_json_error(
						array(
							'message' => 'Passkey not found.',
						)
					);
				}
				$passkey = json_decode( $passkey );
				if ( ! $passkey ) {
					wp_send_json_error(
						array(
							'message' => 'Passkey stored Incorrectly, Remove all passkey and try again.',
						)
					);
				}
				if ( ! isset( $passkey[ $index ] ) ) {
					wp_send_json_error(
						array(
							'message' => 'Invalid request. id not matched.',
						)
					);
				}
				unset( $passkey[ $index ] );
				$passkey      = array_values( $passkey );
				$store_status = update_user_meta( $current_user->ID, 'passkey', wp_slash( wp_json_encode( $passkey ) ) );
				if ( ! $store_status ) {
					wp_send_json_error(
						array(
							'message' => 'Credential not removed.',
						)
					);
				} else {
					wp_send_json_success(
						array(
							'message' => 'Credential removed successfully.',
						)
					);
				}
				break;
			case 'remove_credential_all':
				$current_user = wp_get_current_user();
				$store_status = delete_user_meta( $current_user->ID, 'passkey' );
				if ( ! $store_status ) {
					wp_send_json_error(
						array(
							'message' => 'Credential not removed.',
						)
					);
				} else {
					wp_send_json_success(
						array(
							'message' => 'Credential removed successfully.',
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
