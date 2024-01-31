<?php
/**
 * Rest API
 *
 * This file is used to register all the rest api.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Rest_API;

use WiseSync\Rest_API\Course;

/**
 * Rest API
 *
 * This class is used to register all the rest api.
 */
class Rest_API {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->register_rest_routes();
	}

	/**
	 * Register Rest Routes
	 */
	public function register_rest_routes() {
		$course = new Course();
	}
}
