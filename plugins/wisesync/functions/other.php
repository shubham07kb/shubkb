<?php
/**
 * Other Functions and definitions
 *
 * This file is used to register all the functions and definitions.
 *
 * @package WiseSync
 * @since 1.0.0
 */

// seesion check.
if ( session_status() === PHP_SESSION_NONE ) {
	session_start();
}

// Cloudflare IP check to get real IP in $_SERVER['REMOTE_ADDR'].
if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
	$_SERVER['REMOTE_ADDR'] = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) );
}
$request_ip = sanitize_text_field(
	wp_unslash(
		isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] :
		( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] :
			( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :
				( isset( $_SERVER['HTTP_X_FORWARDED'] ) ? $_SERVER['HTTP_X_FORWARDED'] :
					( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ? $_SERVER['HTTP_FORWARDED_FOR'] :
						( isset( $_SERVER['HTTP_FORWARDED'] ) ? $_SERVER['HTTP_FORWARDED'] :
							( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' )
						)
					)
				)
			)
		)
	)
);

/**
 * Add Client Data Script
 */
function add_client_data_script() {
	global $request_ip;

	if ( in_array( $request_ip, array( '127.0.0.1', '::1', 'localhost', '10.0.0.1', '192.168.0.1' ), true ) && isset( get_session( 'client_data' )['ip'] ) ) {
		$request_ip = 'localhost';
	}

	wp_enqueue_script( 'client-data', WISESYNC_PLUGIN_URL . '/assets/js/client-data.js', array(), '1.0.2', true );
	wp_localize_script(
		'client-data',
		'clientData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'client_data' ),
			'ip'      => $request_ip,
			'action'  => 'set_client_data',
			'cip'     => get_session( 'client_data' )['ip'],
		)
	);
}
$client_data = array( 'device_string' => 'coming soon' );

/**
 * Set Session variable
 *
 * @param mixed  $key Key for Session.
 * @param string $value Value of Key.
 *
 * @return bool
 */
function set_session( $key, $value ) {
	$_SESSION[ $key ] = $value;
	if ( isset( $_SESSION[ $key ] ) && ! empty( $_SESSION[ $key ] ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Get Session variable
 *
 * @param mixed $key Key for Session.
 *
 * @return mixed
 */
function get_session( $key ) {
	if ( isset( $_SESSION[ $key ] ) && ! empty( $_SESSION[ $key ] ) ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return $_SESSION[ $key ];
	} else {
		return false;
	}
}

/**
 * Delete Session Variable
 *
 * @param mixed $key Key for Session.
 *
 * @return bool true.
 */
function delete_session( $key ) {

	unset( $_SESSION[ $key ] );
	return true;
}

/**
 * Get Most Ancestor Post ID
 *
 * @param int $post_id Post ID.
 */
function get_most_ancestor_post_id( $post_id ) {

	$ancestors = get_post_ancestors( $post_id );
	if ( ! empty( $ancestors ) ) {
		return end( $ancestors );
	}
	return $post_id;
}

/**
 * Remove aliasing
 *
 * @param string $email Email.
 */
function remove_aliasing( $email ) {

	$parts    = explode( '@', $email );
	$username = $parts[0];
	$username = preg_replace( '/\+.*/', '', $username );
	return $username . '@' . $parts[1];
}
