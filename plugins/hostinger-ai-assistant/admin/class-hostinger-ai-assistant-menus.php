<?php

/**
 *
 * The file that defines all admin menus
 *
 * @link       https://hostinger.com
 * @since      1.1.2
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */

class Hostinger_Ai_Assistant_Menus {
	public function __construct() {
		if( Hostinger_Ai_Assistant_Helper::is_plugin_active( 'hostinger' ) ) {
			add_action( 'admin_menu', array( $this, 'add_new_with_ai_menu_item' ) );
		}
	}

	public function add_new_with_ai_menu_item(): void {
		add_submenu_page(
			'edit.php',
			__( 'Add New with AI', 'hostinger-ai-assistant' ),
			__( 'Add New with AI', 'hostinger-ai-assistant' ),
			'manage_options',
			'add-new-with-ai',
			'__return_empty_string',
			1
		);
	}
}

$menus = new Hostinger_Ai_Assistant_Menus();
