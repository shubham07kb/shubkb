<?php
/**
 * Rest API Passkey
 *
 * This file is used to register all the rest api for passkey.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Rest_API;

/**
 * Rest API Passkey
 *
 * This class is used to register all the rest api for passkey.
 */
class Passkey {

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
			'wisesync/v1/passkey',
			'/create',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => 'is_user_logged_in',
			)
		);
		register_rest_route(
			'wisesync/v1/passkey',
			'/store',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => 'is_user_logged_in',
			)
		);
		register_rest_route(
			'wisesync/v1/passkey',
			'/get',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => function () {
					return ! is_user_logged_in();
				},
			)
		);
		register_rest_route(
			'wisesync/v1/passkey',
			'/validate',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => function () {
					return ! is_user_logged_in();
				},
			)
		);
		register_rest_route(
			'wisesync/v1/passkey',
			'/remove',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_child_id_tree' ),
				'permission_callback' => 'is_user_logged_in',
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
						'uploa' => wp_upload_dir(),
					)
				);
			}
		}
		return $child_array;
	}

	/**
	 * Get Child ID Tree
	 *
	 * @since 1.0.0
	 */
	public function get_child_id_tree() {

		$str      = '';
		$blockarr = parse_blocks(
			'<!-- wp:papersync/passkey-button -->
		<div class="wp-block-papersync-passkey-button"><button class="passkey_reg">Register</button></div>
		<!-- /wp:papersync/passkey-button -->
		
		<!-- wp:papersync/passkey-button {"type":"validate"} -->
		<div class="wp-block-papersync-passkey-button"><button class="passkey_val">Validate</button></div>
		<!-- /wp:papersync/passkey-button -->
		
		<!-- wp:papersync/course-index /-->'
		);

		foreach ( $blockarr as $block ) {
			$str .= render_block( $block );
		}

		// Extract child and sub-child post IDs.
		return array(
			'status' => 'success',
			'id'     => 'is it working',
			'login'  => is_user_logged_in(),
			'upload' => wp_upload_dir(),
			'arr'    => $str,
		);
	}
}
