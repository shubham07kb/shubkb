<?php

class Hostinger_Amplitude {
	public const AMPLITUDE_ENDPOINT = '/v3/wordpress/plugin/trigger-event';
	private const WOO_ONBOARDING_STARTED = 'woocommerce_onboarding_started';
	private const WOO_WIZARD_PAGE = 'page=wc-admin&path=setup-wizard';
	private const WOO_ONBOARDING_COMPLETED = 'woocommerce_default_onboarding_completed';
	private const AMPLITUDE_HOME_SLUG = 'home';
	private const AMPLITUDE_LEARN_SLUG = 'learn';
	private const AMPLITUDE_AI_ASSISTANT_SLUG = 'ai-assistant';
	private const AMAZON_AFFILIATE_SLUG = 'amazon_affiliate';
	private const WOO_REQUIRED_ONBOARDING_STEPS = array( 'products', 'payments' );

	private Hostinger_Config $config_handler;
	private Hostinger_Requests_Client $client;
	private Hostinger_Helper $helper;
	private Hostinger_Settings $settings;

	public function __construct() {
		$this->helper         = new Hostinger_Helper();
		$this->config_handler = new Hostinger_Config();
		$this->settings       = new Hostinger_Settings();
		$this->client         = new Hostinger_Requests_Client( $this->config_handler->get_config_value( 'base_rest_uri', HOSTINGER_REST_URI ), array(
			Hostinger_Config::TOKEN_HEADER  => $this->helper::get_api_token(),
			Hostinger_Config::DOMAIN_HEADER => $this->helper->get_host_info()
		) );
		add_action( 'admin_init', array( $this, 'woocommerce_onboarding_started' ) );

		if( ! $this->settings->get_setting( self::WOO_ONBOARDING_COMPLETED ) ) {
			add_action( 'admin_init', array( $this, 'woocommerce_onboarding_completed' ) );
		}
	}

	public function send_request( string $endpoint, array $params ): array {
		try {
			$response = $this->client->post( $endpoint, [ 'params' => $params ] );
			return $response;
		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error sending request: ' . $exception->getMessage() );
		}

		return array();
	}

	public function track_menu_action( string $event_action, string $location ): void {
		$endpoint = self::AMPLITUDE_ENDPOINT;
		$action   = $this->map_action( $event_action );

		if ( empty( $action ) ) {
			return;
		}

		$params = array(
			'action'   => $action,
			'location' => $location
		);

		$this->send_request( $endpoint, $params );
	}

	public function setup_store( string $event_action ): void {
		$amplitude_actions = new Hostinger_Amplitude_Actions();
		$endpoint = self::AMPLITUDE_ENDPOINT;
		$action   = sanitize_text_field( $event_action );

		if ( $amplitude_actions::WOO_STORE_SETUP_STORE !== $action ) {
			return;
		}

		$params = array(
			'action' => $action,
		);

		$this->send_request( $endpoint, $params );
	}

	private function map_action( string $event_action ): string {
		$amplitude_actions = new Hostinger_Amplitude_Actions();

		switch ( $event_action ) {
			case self::AMPLITUDE_HOME_SLUG:
				return $amplitude_actions::HOME_ENTER;
			case self::AMPLITUDE_LEARN_SLUG:
				return $amplitude_actions::LEARN_ENTER;
			case self::AMPLITUDE_AI_ASSISTANT_SLUG;
				return $amplitude_actions::AI_ASSISTANT_ENTER;
			case self::AMAZON_AFFILIATE_SLUG;
				return $amplitude_actions::AMAZON_AFFILIATE_ENTER;
			default:
				return '';
		}

	}

	public function woocommerce_onboarding_started(): bool {
		$amplitude_actions = new Hostinger_Amplitude_Actions();

		if( $this->helper->is_this_page( self::WOO_WIZARD_PAGE ) ) {
			$request = $this->send_request( self::AMPLITUDE_ENDPOINT, [
				'action' => $amplitude_actions::WOO_ONBOARDING_STARTED,
			] );

			if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
				return true;
			}
		}

		return false;
	}

	public function woocommerce_onboarding_completed(): bool {
		$amplitude_actions = new Hostinger_Amplitude_Actions();
		$settings = new Hostinger_Settings();

		if( $this->helper->default_woocommerce_survey_steps_completed( self::WOO_REQUIRED_ONBOARDING_STEPS ) ) {
			$request = $this->send_request( self::AMPLITUDE_ENDPOINT, [
				'action' => $amplitude_actions::WOO_STORE_SETUP_COMPLETED,
			] );

			if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
				$settings->update_setting( self::WOO_ONBOARDING_COMPLETED, true );
				return true;
			}
		}
		return false;
	}


}

new Hostinger_Amplitude();
