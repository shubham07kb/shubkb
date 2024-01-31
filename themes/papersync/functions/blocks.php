<?php
/**
 * Add All Blocks Dynamically
 *
 * @package PaperSync
 */

/**
 * Include blocks
 *
 * @return void
 */
function include_blocks() {

	$scan = scandir( PAPERSYNC_THEME_DIR . '/inc/blocks' );

	foreach ( $scan as $file ) {

		if ( is_dir( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" ) && '.' !== $file && '..' !== $file ) {

			$scan_in = scandir( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" );

			if ( in_array( 'block.php', $scan_in, true ) && in_array( 'build', $scan_in, true ) && in_array( 'block.json', $scan_in, true ) ) {

				$upper_file = ucwords( str_replace( '-', '_', $file ) );
				require_once PAPERSYNC_THEME_DIR . "/inc/blocks/$file/block.php";
				register_block_type_from_metadata(
					PAPERSYNC_THEME_DIR . "/inc/blocks/$file",
					array( 'render_callback' => 'papersync_block_' . $file . '_render' ),
				);

			} elseif ( in_array( 'build', $scan_in, true ) && in_array( 'block.json', $scan_in, true ) ) {

				register_block_type_from_metadata( PAPERSYNC_THEME_DIR . "/inc/blocks/$file" );
			}
		}
	}
}
