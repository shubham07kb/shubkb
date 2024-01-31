<?php
/**
 * Rest API Course
 *
 * This file is used to register all the rest api course.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Rest_API;

/**
 * Rest API Course
 *
 * This class is used to register all the rest api course.
 */
class Course {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register Rest Routes
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {

		register_rest_route(
			'wisesync/v1/course',
			'/getchilds',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Get child Data
	 *
	 * @param int $post_id Post ID.
	 */
	public function get_child_data( $post_id ) {

		$child_pages = get_children(
			array(
				'post_parent' => $post_id,
				'post_type'   => 'course',
				'post_status' => 'publish',
			)
		);
		$child_array = array();
		foreach ( $child_pages as $child_page ) {
			if ( 'edit' !== $child_page->post_name ) {
				array_push(
					$child_array,
					array(
						'id'    => $child_page->ID,
						'url'   => get_permalink( $child_page->ID ),
						'child' => $this->get_child_data( $child_page->ID ),
					)
				);
			}
		}
		return $child_array;
	}

	/**
	 * Get Child ID Tree
	 *
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @since 1.0.0
	 */
	public function get_child_id_tree( \WP_REST_Request $request ) {

		$post_id     = (int) sanitize_text_field( $request->get_param( 'id' ) );
		$ancestor_id = get_post_ancestors( $post_id );

		$custom_array = array(
			'id'    => end( $ancestor_id ),
			'url'   => get_permalink( end( $ancestor_id ) ),
			'child' => $this->get_child_data( end( $ancestor_id ) ),
		);

		// Extract child and sub-child post IDs.
		return array(
			'status' => 'success',
			'id'     => $post_id,
			'edit'   => $custom_array,
		);
	}
}
