<?php
/**
 * Ajax
 *
 * This file is used to register all the ajax.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Ajax;

use WiseSync\Ajax\Course;
use WiseSync\Ajax\Passkey;
use WiseSync\Ajax\Other;

/**
 * Ajax
 *
 * This class is used to register all the ajax.
 */
class Ajax {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_ajax_actions();
	}

	/**
	 * Register Ajax Actions
	 *
	 * @since 1.0.0
	 */
	public function register_ajax_actions() {

		new Course();
		new Passkey();
		new Other();
	}
}
