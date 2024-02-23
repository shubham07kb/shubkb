<?php
/**
 * Init
 *
 * This file is used to initialize the plugin.
 *
 * @package WiseSync
 * @since 1.0.0
 */

namespace WiseSync\Init;

use WiseSync\Init\Mail;

/**
 * Init
 *
 * This class is used to initialize the plugin.
 */
class Init {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_classes();
	}

	/**
	 * Initialize Classes
	 *
	 * @since 1.0.0
	 */
	public function init_classes() {

		new Mail();
	}
}
