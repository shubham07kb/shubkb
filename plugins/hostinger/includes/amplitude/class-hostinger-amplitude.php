<?php

class Hostinger_Amplitude {
	public const AMPLITUDE_ENDPOINT = '/v3/wordpress/plugin/trigger-event';
	private const WOO_WIZARD_PAGE = 'page=wc-admin&path=/setup-wizard';
	private const WOO_ONBOARDING_COMPLETED = 'woocommerce_default_onboarding_completed';
	private const AMPLITUDE_HOME_SLUG = 'home';
	private const AMPLITUDE_LEARN_SLUG = 'learn';
	private const AMPLITUDE_AI_ASSISTANT_SLUG = 'ai-assistant';
	private const AMAZON_AFFILIATE_SLUG = 'amazon_affiliate';
	private const WOO_REQUIRED_ONBOARDING_STEPS = array( 'products', 'payments' );
	private const WOO_ONBOARDING_TRANSIENT_REQUEST = 'woocommerce_onboarding_request';
	private const WOO_ONBOARDING_TRANSIENT_RESPONSE = 'woocommerce_onboarding_response';
	private const WOO_ONBOARDING_TRANSIENT_ATTEMPTS = 'woocommerce_onboarding_attempts';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_ATTEMPTS = 'woocommerce_started_attempts';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST = 'woocommerce_started_request';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_RESPONSE = 'woocommerce_started_response';
	private const CACHE_SIX_HOURS = 3600 * 6;
	private const CACHE_ONE_HOUR = 3600;
	private const CACHE_ONE_DAY = 86400;

	private Hostinger_Config $config_handler;
	private Hostinger_Requests_Client $client;
	private Hostinger_Helper $helper;
	private Hostinger_Settings $settings;

	public function __construct() {
		$this->helper         = new Hostinger_Helper();
		$this->config_handler = new Hostinger_Config();
		$this->settings       = new Hostinger_Settings();
		$this->client         = new Hostinger_Requests_Client(
			$this->config_handler->get_config_value( 'base_rest_uri', HOSTINGER_REST_URI ),
			array(
				Hostinger_Config::TOKEN_HEADER  => $this->helper::get_api_token(),
				Hostinger_Config::DOMAIN_HEADER => $this->helper->get_host_info(),
			)
		);

		if ( $this->helper::is_plugin_active( 'woocommerce' ) ) {
			$this->setup_woocommerce_onboarding_events();
		}
	}

	private function setup_woocommerce_onboarding_events(): void {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( $this->helper->is_this_page( self::WOO_WIZARD_PAGE ) ) {
			add_action( 'admin_init', array( $this, 'woocommerce_onboarding_started' ) );
		}

		if ( ! $this->settings->get_setting( self::WOO_ONBOARDING_COMPLETED ) ) {
			add_action( 'admin_init', array( $this, 'woocommerce_onboarding_completed' ) );
		}
	}

	public function send_request( string $endpoint, array $params ): array {
		try {

			if ( isset( $params['action'] ) && isset( $params['location'] ) && ! $this->should_send_amplitude_event( $params['action'], $params['location'] ) ) {
				return array();
			}

			$response = $this->client->post( $endpoint, array( 'params' => $params ) );

			return $response;
		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error sending request: ' . $exception->getMessage() );
		}

		return array();
	}

	public function should_send_amplitude_event( string $event_action, string $location ): bool {
		$amplitude_actions = new Hostinger_Amplitude_Actions();
		$one_time_per_day  = array(
			$amplitude_actions::HOME_ENTER,
			$amplitude_actions::LEARN_ENTER,
			$amplitude_actions::AI_ASSISTANT_ENTER,
			$amplitude_actions::AMAZON_AFFILIATE_ENTER,
			$amplitude_actions::WOO_ONBOARDING_STARTED,
			$amplitude_actions::WOO_STORE_SETUP_STORE,
			$amplitude_actions::WOO_STORE_SETUP_COMPLETED,
		);

		$event_action = sanitize_text_field( $event_action );

		if ( in_array( $event_action, $one_time_per_day ) && get_transient( $event_action . '-' . $location ) ) {
			return false;
		}

		if ( in_array( $event_action, $one_time_per_day ) ) {
			set_transient( $event_action . '-' . $location, true, self::CACHE_ONE_DAY );
		}

		return true;
	}

	public function track_menu_action( string $event_action, string $location ): void {
		$endpoint = self::AMPLITUDE_ENDPOINT;
		$action   = $this->map_action( $event_action );

		if ( empty( $action ) ) {
			return;
		}

		$params = array(
			'action'   => $action,
			'location' => $location,
		);

		$this->send_request( $endpoint, $params );
	}

	public function setup_store( string $event_action ): void {
		$amplitude_actions = new Hostinger_Amplitude_Actions();
		$endpoint          = self::AMPLITUDE_ENDPOINT;
		$action            = sanitize_text_field( $event_action );

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

	public function woocommerce_onboarding_started(): void {
		$transient_response_key = self::WOO_ONBOARDING_STARTED_TRANSIENT_RESPONSE;
		$transient_attempts_key = self::WOO_ONBOARDING_STARTED_TRANSIENT_ATTEMPTS;
		$onboarding_started     = get_transient( $transient_response_key );
		$request_attempts       = get_transient( $transient_attempts_key ) ?: 0;
		$amplitude_actions      = new Hostinger_Amplitude_Actions();

		if ( $onboarding_started || $request_attempts > 20 ) {
			return;
		}

		try {
			set_transient( self::WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST, true, self::CACHE_SIX_HOURS );

			if ( false === get_transient( self::WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST ) ) {
				throw new Exception( 'Unable to create transient in WordPress.' );
			}

			if ( $this->helper->is_this_page( self::WOO_WIZARD_PAGE ) ) {
				$request = $this->send_request( self::AMPLITUDE_ENDPOINT, [ 'action' => $amplitude_actions::WOO_ONBOARDING_STARTED ] );

				if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
					set_transient( $transient_response_key, true, self::CACHE_SIX_HOURS );
				}
			}

			set_transient( $transient_response_key, 0, self::CACHE_ONE_HOUR );
		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error checking onboarding started: ' . $exception->getMessage() );
		} finally {
			set_transient( $transient_attempts_key, ++ $request_attempts, self::CACHE_SIX_HOURS );
		}
	}

	public function woocommerce_onboarding_completed(): void {
		$transient_request_key  = self::WOO_ONBOARDING_TRANSIENT_REQUEST;
		$transient_response_key = self::WOO_ONBOARDING_TRANSIENT_RESPONSE;
		$transient_attempts_key = self::WOO_ONBOARDING_TRANSIENT_ATTEMPTS;
		$onboarding_completed   = get_transient( $transient_response_key );
		$request_attempts       = get_transient( $transient_attempts_key ) ?? 0;
		$amplitude_actions      = new Hostinger_Amplitude_Actions();
		$settings               = new Hostinger_Settings();
		$completed_steps        = $this->helper->default_woocommerce_survey_steps_completed( self::WOO_REQUIRED_ONBOARDING_STEPS );

		try {
			if ( $onboarding_completed || $request_attempts > 20 ) {
				return;
			}

			set_transient( $transient_request_key, true, self::CACHE_SIX_HOURS );

			if ( false === get_transient( $transient_request_key ) ) {
				throw new Exception( 'Unable to create transient in WordPress.' );
			}

			if ( $completed_steps ) {
				$request = $this->send_request( self::AMPLITUDE_ENDPOINT, [ 'action' => $amplitude_actions::WOO_STORE_SETUP_COMPLETED ] );

				if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
					set_transient( $transient_response_key, true, self::CACHE_SIX_HOURS );
					$settings->update_setting( self::WOO_ONBOARDING_COMPLETED, true );
				}

				set_transient( $transient_response_key, 0, self::CACHE_ONE_HOUR );
			}
		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error checking onboarding completion: ' . $exception->getMessage() );
		} finally {
			set_transient( $transient_attempts_key, ++ $request_attempts, self::CACHE_SIX_HOURS );
		}
	}


}

new Hostinger_Amplitude();
