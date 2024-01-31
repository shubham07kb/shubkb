<?php
/**
 * Course Index
 *
 * A block to display a list of courses and Edit Options.
 *
 * @package PaperSync
 * @since 1.0.0
 */

/**
 * Render
 */
function papersync_block_course_index_render() {

	$post_id     = (int) sanitize_text_field( get_queried_object_id() );
	$ancestor_id = get_post_ancestors( $post_id );
	$ancestor_id = end( $ancestor_id );
	return '<div id="course-index">' . index_process_renderer( $ancestor_id, 'papersyncCourseIndex' ) . '</div>';
}
