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

use WiseSync\Post_Types\Course;

/**
 * Post Types
 *
 * This class is used to register all the post types.
 */
class Post_Types {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_post_types();
	}

	/**
	 * Register Post Types
	 *
	 * @since 1.0.0
	 */
	public function register_post_types() {

		$course = new Course();
	}
}
