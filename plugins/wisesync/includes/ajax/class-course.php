<?php
/**
 * Ajax
 *
 * This file is used to register all the ajax.
 *
 * @package WiseSync
 * @since 1.0.0
 *
 * phpcs:disable WordPress.Security.NonceVerification.Recommended
 */

namespace WiseSync\Ajax;

/**
 * Ajax
 *
 * This class is used to register all the ajax.
 */
class Course {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'wp_ajax_index_course', array( $this, 'index_course' ) );
		add_action( 'wp_ajax_nopriv_index_course', array( $this, 'index_course' ) );
		add_action( 'wp_ajax_add_course', array( $this, 'add_course' ) );
		add_action( 'wp_ajax_nopriv_add_course', array( $this, 'add_course' ) );
		add_action( 'wp_ajax_remove_course', array( $this, 'remove_course' ) );
		add_action( 'wp_ajax_nopriv_remove_course', array( $this, 'remove_course' ) );
		add_action( 'wp_ajax_up_course', array( $this, 'up_course' ) );
		add_action( 'wp_ajax_nopriv_up_course', array( $this, 'up_course' ) );
		add_action( 'wp_ajax_down_course', array( $this, 'down_course' ) );
		add_action( 'wp_ajax_nopriv_down_course', array( $this, 'down_course' ) );
		add_action( 'wp_ajax_hide_course', array( $this, 'hide_course' ) );
		add_action( 'wp_ajax_nopriv_hide_course', array( $this, 'hide_course' ) );
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function index_course() {

		$post_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		$post_id = get_most_ancestor_post_id( $post_id );

		wp_send_json_success(
			array(
				'id'   => $post_id,
				'html' => index_process_renderer( $post_id ),
			)
		);
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function add_course() {

		$post_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

		$random_string = substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyz' ), 0, 10 );
		$child_post    = array(
			'post_title'   => 'New Course Post',
			'post_content' => 'Content for the edit post.',
			'post_status'  => 'publish',
			'post_type'    => 'course', // Adjust if your custom post type has a different name.
			'post_parent'  => $post_id,
			'post_name'    => $random_string,
		);
		$child_id      = wp_insert_post( $child_post );
		wp_send_json_success(
			array(
				'id'          => $child_id,
				'ancestor_id' => get_most_ancestor_post_id( $post_id ),
			)
		);
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function remove_course() {

		$post_id        = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		$child_post_ids = get_all_course_child_post_ids( $post_id );
		$ancestor_id    = get_most_ancestor_post_id( $post_id );
		$parent_id      = get_post( $post_id )->post_parent;

		foreach ( $child_post_ids as $child_post_id ) {
			wp_delete_post( $child_post_id, true );
		}
		wp_delete_post( $post_id, true );

		$parent_ids = json_decode( get_post_meta( $parent_id, 'course_structure', true ) );
		foreach ( $parent_ids as $index => $parent_id_in ) {
			if ( $parent_id_in->id === $post_id ) {
				unset( $parent_ids[ $index ] );
			}
		}
		$parent_ids = array_values( $parent_ids );
		update_post_meta( $parent_id, 'course_structure', wp_json_encode( $parent_ids ) );

		array_unshift( $child_post_ids, $post_id );
		wp_send_json_success(
			array(
				'ids'         => $child_post_ids,
				'ancestor_id' => $ancestor_id,
				'parent_id'   => $parent_id,
			)
		);
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function up_course() {

		$post_id     = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		$ancestor_id = get_most_ancestor_post_id( $post_id );
		$parent      = get_post( $post_id )->post_parent;
		$post_meta   = json_decode( get_post_meta( $parent, 'course_structure', true ) );

		foreach ( $post_meta as $index => $item ) {
			if ( $item->id === $post_id ) {
				$index_to_swap = $index;
			}
		}

		list( $post_meta[ $index_to_swap ], $post_meta[ $index_to_swap - 1 ] ) = array( $post_meta[ $index_to_swap - 1 ], $post_meta[ $index_to_swap ] );
		update_post_meta( $parent, 'course_structure', wp_json_encode( $post_meta ) );

		wp_send_json_success(
			array(
				'id'          => $post_id,
				'index'       => $index_to_swap,
				'ancestor_id' => $ancestor_id,
			)
		);
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function down_course() {

		$post_id     = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		$ancestor_id = get_most_ancestor_post_id( $post_id );
		$parent      = get_post( $post_id )->post_parent;
		$post_meta   = json_decode( get_post_meta( $parent, 'course_structure', true ) );

		foreach ( $post_meta as $index => $item ) {
			if ( $item->id === $post_id ) {
				$index_to_swap = $index;
			}
		}

		list( $post_meta[ $index_to_swap ], $post_meta[ $index_to_swap + 1 ] ) = array( $post_meta[ $index_to_swap + 1 ], $post_meta[ $index_to_swap ] );
		update_post_meta( $parent, 'course_structure', wp_json_encode( $post_meta ) );

		wp_send_json_success(
			array(
				'id'          => $post_id,
				'index'       => $index_to_swap,
				'ancestor_id' => $ancestor_id,
			)
		);
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function hide_course() {

		$post_id     = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		$parent      = get_post( $post_id )->post_parent;
		$ancestor_id = get_most_ancestor_post_id( $post_id );
		$post_meta   = json_decode( get_post_meta( $parent, 'course_structure', true ) );

		foreach ( $post_meta as $item ) {
			if ( $item->id === $post_id ) {
				$item->visibility = ! $item->visibility;
				break;
			}
		}
		update_post_meta( $parent, 'course_structure', wp_json_encode( $post_meta ) );
		wp_send_json_success(
			array(
				'id'          => $post_id,
				'parent_id'   => $parent,
				'ancestor_id' => $ancestor_id,
			)
		);
	}
}
