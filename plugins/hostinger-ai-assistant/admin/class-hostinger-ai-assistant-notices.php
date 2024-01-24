<?php

/**
 * The file that defines all admin notices
 *
 * A class definition that includes notices used across admin area.
 *
 * @link       https://hostinger.com
 * @since      1.0.0
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */

class Hostinger_Ai_Assistant_Notices {

	/**
	 *
	 * Main plugin notice
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function main_plugin_notice() { ?>

		<div class="notice notice-error is-dismissible hts-theme-settings">
			<p>
				<strong><?= __( 'Attention:', 'hostinger-ai-assistant' ) ?></strong> <?= __( 'The Hostinger AI Assistant plugin requires the main Hostinger plugin to work properly.', 'hostinger-ai-assistant' ) ?>
			</p>
			<p><?= __( 'To activate the main Hostinger plugin, follow these steps: ', 'hostinger-ai-assistant' ) ?></p>
			<ul>
				<li><b><?= __( 'Navigate to the Plugins section in the left-hand menu.', 'hostinger-ai-assistant' ) ?></b></li>
				<li><b><?= __( 'Look for the Hostinger plugin in the list of installed plugins.', 'hostinger-ai-assistant' ) ?></b></li>
				<li><b><?= __( 'If it is deactivated, click on the "Activate" button below the plugin\'s name.', 'hostinger-ai-assistant' ) ?></b></li>
			</ul>
			<p><?= __( 'If the main Hostinger plugin is not listed in the Plugins section, follow these steps instead:', 'hostinger-ai-assistant' ) ?></p>
			<ul>
				<li><b><?= __( 'Deactivate and delete the Hostinger AI Assistant plugin.', 'hostinger-ai-assistant' ) ?></b></li>
				<li><b><?= __( 'From your Hostinger member area (hPanel) reinstall the Hostinger AI Assistant plugin.', 'hostinger-ai-assistant' ) ?></b></li>
				<li><b><?= __( 'The main Hostinger plugin will be installed along with it.', 'hostinger-ai-assistant' ) ?></b></li>
			</ul>
		</div>

	<?php }

	public function api_token_plugin_notice() { ?>

		<div class="notice notice-error is-dismissible hts-theme-settings hts-admin-notice">
			<p>
				<strong><?= __( 'Attention:', 'hostinger-ai-assistant' ) ?></strong> <?= __( 'To unlock the exclusive features of <b>Hostinger AI Assistant</b>, you must possess a unique API token, which is exclusively provided to Hostinger clients', 'hostinger-ai-assistant' ) ?>
			</p>
		</div>

	<?php }
}
