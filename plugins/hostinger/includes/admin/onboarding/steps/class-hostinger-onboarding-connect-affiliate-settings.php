<?php

class Hostinger_Onboarding_Connect_Affiliate_Settings extends Hostinger_Onboarding_Step {

	private string $amazon_dashboard_url = 'https://advertising.amazon.com/sign-in';

	public function get_title(): string {
		return __( 'Connect Amazon partnership account', 'hostinger' );
	}

	public function get_body(): array {
		return [
			[
				'title'       => __( 'Login to the Amazon Product Advertising dashboard', 'hostinger' ),
				/* translators: %s: link to dashboard */
				'description' => sprintf( __( 'To gather the necessary information, log in to your <a href="%s" target="_blank" rel="noopener">Amazon Product Advertising dashboard</a> using your personal Amazon partnership account.', 'hostinger' ), $this->amazon_dashboard_url )
			],
			[
				'title'       => __( 'Open the Amazon Affiliate plugin configuration', 'hostinger' ),
				/* translators: %s: link to plugin page */
				'description' => sprintf( __( 'Fill in the required details on the <a href="%s" class="hst-open-affiliate-tab">Amazon Affiliate plugin page</a>, where you\'ll find all the necessary information about the plugin.', 'hostinger' ), $this->get_redirect_link() ),
			],
			[
				'title'       => __( 'Copy-Paste the necessary information:', 'hostinger' ),
				/* translators: %s: link to dashboard */
				'description' => sprintf( __( 'Complete the form with required information related to Amazon\'s partnership API. Retrieve all this information from the <a href="%s" target="_blank" rel="noopener">Amazon Product Advertising dashboard</a> and paste it into the form.', 'hostinger' ), $this->amazon_dashboard_url ),
			],
		];
	}

	public function step_identifier(): string {
		return 'connect_affiliate_settings';
	}

	public function get_redirect_link(): string {
		return admin_url( 'admin.php?page=hostinger' );
	}

	public function button_text(): string {
		return __( 'Connect Amazon Account', 'hostinger' );
	}
}
