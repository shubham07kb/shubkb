<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_Deactivator {
	public static function deactivate(): void {
		wp_clear_scheduled_hook( 'weekly_admin_surveys_event' );
	}
}
