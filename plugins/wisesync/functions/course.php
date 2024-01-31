<?php
/**
 * Course Post Type Functions
 *
 * This file is used to register all the course post type functions.
 *
 * @package WiseSync
 */

/**
 * Index Process and Response
 *
 * @param int    $post_id Post ID.
 * @param string $enqueue_object_name Enqueue object name.
 */
function index_process_renderer( $post_id, $enqueue_object_name = 'wisesyncCourseIndex' ) {
	global $post;

	$can_edit_course = false;
	$current_user    = wp_get_current_user();
	if ( $post->post_author === $current_user->ID || current_user_can( 'edit_others_posts' ) ) {

		$can_edit_course = true;
		wp_enqueue_script(
			'wisesync-course-index',
			get_template_directory_uri() . '/inc/blocks/course-index/js/course-index.js',
			array(),
			'1.0.0',
			true
		);
		wp_localize_script(
			'wisesync-course-index',
			$enqueue_object_name,
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	$post_data_array = array(
		'id'    => $post_id,
		'title' => get_the_title( $post_id ),
		'url'   => get_permalink( $post_id ),
		'child' => get_child_data( $post_id, $can_edit_course ),
	);
	$block_data      = '<ul>';
	$block_data     .= generate_html_list( array( $post_data_array ), 1, $can_edit_course );
	$block_data     .= '</ul>';
	return $block_data;
}

/**
 * Get child Data
 *
 * @param int  $post_id Post ID.
 * @param bool $can_edit_course Can edit course.
 */
function get_child_data( $post_id, $can_edit_course = false ) {

	$child_pages = json_decode( get_post_meta( $post_id, 'course_structure', true ) );

	$child_array = array();
	foreach ( $child_pages as $child_page ) {
		if ( ( true === $child_page->visibility || $can_edit_course ) && 'publish' === get_post_status( $child_page->id ) ) {

			array_push(
				$child_array,
				array(
					'id'         => $child_page->id,
					'title'      => get_the_title( $child_page->id ),
					'url'        => get_permalink( $child_page->id ),
					'child'      => get_child_data( $child_page->id ),
					'visibility' => ! $child_page->visibility,
				)
			);
		}
	}
	return $child_array;
}

/**
 * Generate HTML list
 *
 * @param array $data Data.
 * @param int   $level Level.
 * @param bool  $can_edit_course Can edit course.
 */
function generate_html_list( $data, $level, $can_edit_course ) {

	global $post;
	$html = '';

	$total_items = count( $data );
	foreach ( $data as $index => $item ) {
		$class = "title-index-$level";

		$edit_buttons = '';
		if ( $can_edit_course ) {

			if ( in_array( $level, array( 1, 2 ), true ) ) {
				$edit_buttons .= '<button onclick="doFetch(' . "'add_course', " . $item['id'] . ')">+</button>';
			}
			if ( 1 !== $level ) {
				$edit_buttons .= '<a href="' . get_edit_post_link( $item['id'] ) . '"><button>Edit</button></a>';
				if ( 0 !== $index ) {

					$edit_buttons .= '<button onclick="doFetch(' . "'up_course', " . $item['id'] . ')">↑</button>';
				}
				if ( $index !== $total_items - 1 ) {

					$edit_buttons .= '<button onclick="doFetch(' . "'down_course', " . $item['id'] . ')">↓</button>';
				}
				$visible_text  = $item['visibility'] ? 'visible' : 'hidden';
				$edit_buttons .= '<button onclick="doFetch(' . "'hide_course', " . $item['id'] . ')">' . $visible_text . '</button>';
				$edit_buttons .= '<button onclick="doFetch(' . "'remove_course', " . $item['id'] . ')">-</button>';
			}
		}

		$html .= "<li class='$class'><a href='{$item['url']}'>{$item['title']}</a>$edit_buttons";

		if ( ! empty( $item['child'] ) ) {
			$html .= '<ul>';
			$html .= generate_html_list( $item['child'], $level + 1, $can_edit_course );
			$html .= '</ul>';
		}

		$html .= '</li>';
	}

	return $html;
}

/**
 * Get All Child Post IDs
 *
 * @param int $parent_post_id Post ID.
 *
 * @since 1.0.0
 */
function get_all_course_child_post_ids( $parent_post_id ) {

	$args = array(
		'post_parent' => $parent_post_id,
		'post_type'   => 'course',
		'post_status' => 'publish',
	);

	$child_posts = get_children( $args );

	$child_post_ids = array();
	foreach ( $child_posts as $child_post ) {
		$child_post_ids[] = $child_post->ID;
	}

	return $child_post_ids;
}
