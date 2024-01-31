<?php
/**
 * Post Types
 *
 * This file is used to register all the post types.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Post_Types;

/**
 * Post Types
 *
 * This class is used to register all the post types.
 */
class Course {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register Post Types
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		add_action( 'publish_course', array( $this, 'create_child_post' ), 10, 2 );

		$labels = array(
			'name'                  => _x( 'Courses', 'Post type general name', 'shubkbPlugin' ),
			'singular_name'         => _x( 'Course', 'Post type singular name', 'shubkbPlugin' ),
			'menu_name'             => _x( 'Courses', 'Admin Menu text', 'shubkbPlugin' ),
			'name_admin_bar'        => _x( 'Course', 'Add New on Toolbar', 'shubkbPlugin' ),
			'add_new'               => __( 'Add New', 'shubkbPlugin' ),
			'add_new_item'          => __( 'Add New Course', 'shubkbPlugin' ),
			'new_item'              => __( 'New Course', 'shubkbPlugin' ),
			'edit_item'             => __( 'Edit Course', 'shubkbPlugin' ),
			'view_item'             => __( 'View Course', 'shubkbPlugin' ),
			'all_items'             => __( 'All Courses', 'shubkbPlugin' ),
			'search_items'          => __( 'Search Courses', 'shubkbPlugin' ),
			'parent_item_colon'     => __( 'Parent Courses:', 'shubkbPlugin' ),
			'not_found'             => __( 'No Courses found.', 'shubkbPlugin' ),
			'not_found_in_trash'    => __( 'No Courses found in Trash.', 'shubkbPlugin' ),
			'featured_image'        => _x( 'Course Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'shubkbPlugin' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'shubkbPlugin' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'shubkbPlugin' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'shubkbPlugin' ),
			'archives'              => _x( 'Course archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'shubkbPlugin' ),
			'insert_into_item'      => _x( 'Insert into Course', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'shubkbPlugin' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Course', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'shubkbPlugin' ),
			'filter_items_list'     => _x( 'Filter Courses list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'shubkbPlugin' ),
			'items_list_navigation' => _x( 'Courses list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'shubkbPlugin' ),
			'items_list'            => _x( 'Courses list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'shubkbPlugin' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Courses',
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_rest'       => true,
			'has_archive'        => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes' ),
			'rewrite'            => array(
				'slug'       => 'course',
				'with_front' => false,
			),
			'capability_type'    => 'post',
			'taxonomies'         => array( 'category', 'post_tag' ),
			'menu_position'      => 5,
			'query_var'          => true,
			'hierarchical'       => true,
		);

		register_post_type( 'course', $args );
	}

	/**
	 * Create Child Post
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 *
	 * @since 1.0.0
	 */
	public function create_child_post( $post_id, $post ) {

		if ( 'Auto Draft' !== $post->post_title && 0 === $post->post_parent ) {

			// Create child post data.
			$child_post = array(
				'post_title'   => 'Edit',
				'post_content' => 'Content for the edit post.',
				'post_status'  => 'publish',
				'post_type'    => 'course', // Adjust if your custom post type has a different name.
				'post_parent'  => $post_id,
				'post_name'    => 'edit',
			);

			// Insert the child post.
			$child_id = wp_insert_post( $child_post );
			add_post_meta( $post_id, 'course_structure', wp_json_encode( array() ) );
		} elseif ( 'edit' !== $post->post_name ) {

			$parent_array = json_decode( get_post_meta( $post->post_parent, 'course_structure', true ) );
			array_push(
				$parent_array,
				array(
					'id'         => $post_id,
					'visibility' => true,
				)
			);
			update_post_meta( $post->post_parent, 'course_structure', wp_json_encode( $parent_array ) );
			add_post_meta( $post_id, 'course_structure', wp_json_encode( array() ) );
		}
	}
}
