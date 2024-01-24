<?php

/**
 *
 * The file that defines all redirects
 *
 * @link       https://hostinger.com
 * @since      1.1.2
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */

class Hostinger_Ai_Assistant_Redirects {
	private $helper;

	public function __construct() {
		$this->helper = new Hostinger_Ai_Assistant_Helper();
		add_action( 'admin_init', array( $this, 'redirect_to_ai_assistant' ) );
	}

	public function redirect_to_ai_assistant() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'add-new-with-ai' ) {
			$url = admin_url( 'admin.php?page=hostinger#ai-assistant' );
			wp_safe_redirect( $url );
			exit;
		}
	}
}

$redirects = new Hostinger_Ai_Assistant_Redirects();
