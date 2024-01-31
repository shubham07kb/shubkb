<?php
/**
 * Other Functions and definitions
 *
 * This file is used to register all the functions and definitions.
 *
 * @package WiseSync
 * @since 1.0.0
 */

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
