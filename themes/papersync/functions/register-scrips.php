<?php
/**
 * Register Scripts
 *
 * @package papersync
 * @since 1.0.0
 */

/**
 * PaperSync Enqueue Script
 */
function papersync_enqueue_scrpts() {

	// Load Block scripts.
}
add_action( 'wp_enqueue_scripts', 'papersync_enqueue_scrpts' );
