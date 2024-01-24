<?php

/**
 *
 * The file that defines all admin buttons
 *
 * @link       https://hostinger.com
 * @since      1.1.2
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */


class Hostinger_Ai_Assistant_Buttons {

	public function __construct() {

		if ( Hostinger_Ai_Assistant_Helper::is_plugin_active( 'hostinger' ) ) {
			add_action( 'admin_notices', array( $this, 'add_custom_button_to_edit_page' ) );
		}
	}

	public function add_custom_button_to_edit_page() {
		$url         = admin_url( 'admin.php?page=hostinger#ai-assistant' );
		$button_text = __( 'Add New with AI', 'hostinger-ai-assistant' );
		global $typenow;
		if ($typenow === 'post') {
			echo '<div class="notice hts-ai-notice">';
			echo '<p><a href="' . esc_url( $url ) . '" class="page-title-action">' . esc_html( $button_text ) . '</a></p>';
			echo '</div>';
		}
	}

}

$menus = new Hostinger_Ai_Assistant_Buttons();
