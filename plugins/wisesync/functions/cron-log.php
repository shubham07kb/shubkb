<?php
/**
 * Function to Log Cron
 *
 * @package WiseSync
 */

/**
 * Function to Log Cron
 */
function wisesync_log_cron() {

	// Check if it's a cron request.
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		global $wp_filesystem;
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();

		// Get the current time.
		$current_time = current_time( 'mysql' );
		$log_path     = WP_CONTENT_DIR . '/uploads/cron-log.txt';
		if ( ! $wp_filesystem->exists( $log_path ) ) {

			$wp_filesystem->touch( $log_path );
			$wp_filesystem->put_contents( $log_path, 'Cron Log File' . PHP_EOL, FS_CHMOD_FILE );
		}
		$pre_cron_log = $wp_filesystem->get_contents( $log_path );
		$wp_filesystem->put_contents( $log_path, $pre_cron_log . $current_time . ' : Run' . PHP_EOL, FS_CHMOD_FILE );

	}
}
