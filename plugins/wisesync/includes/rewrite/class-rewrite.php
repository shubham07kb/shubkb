<?php
/**
 * Rewrite
 *
 * This file is used to register all the rewrite.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Rewrite;

/**
 * Rewrite
 *
 * This class is used to register all the rewrite.
 */
class Rewrite {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'custom_courses_rewrite_rules' ) );
	}

	/**
	 * Custom Courses Rewrite Rules
	 *
	 * @since 1.0.0
	 */
	public static function custom_courses_rewrite_rules() {

		add_rewrite_rule( '^course/([^/]+)$', 'index.php?post_type=course&name=$matches[1]', 'top' );
	}
}
