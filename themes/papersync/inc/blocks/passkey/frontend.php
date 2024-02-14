<?php
/**
 * Passkey Block Frontend
 *
 * @package PaperSync
 * @since 1.0.0
 */

/**
 * Passkey Block Frontend Script
 *
 * @return void
 */
function enqueue_my_script() {
	if ( has_block( 'your/block/name' ) ) {
		wp_enqueue_script( 'my-script', get_template_directory_uri() . '/my-script.js', array(), '1.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_my_script' );
