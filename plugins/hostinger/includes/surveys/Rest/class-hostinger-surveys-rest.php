<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hostinger_Surveys_Rest {
	public const SUBMIT_SURVEY             = '/v3/wordpress/survey/store';
	public const CLIENT_SURVEY_ELIGIBILITY = '/v3/wordpress/survey/client-eligible';
	public const CLIENT_SURVEY_IDENTIFIER  = 'customer_satisfaction_score';
	public const GET_SURVEY                = '/v3/wordpress/survey/get';

	private Hostinger_Requests_Client $client;

	public function __construct( Hostinger_Requests_Client $client ) {
		$this->client = $client;
	}

	public function is_client_eligible(): bool {
		$response = $this->client->get(
			self::CLIENT_SURVEY_ELIGIBILITY,
			array(
				'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
			)
		);

		$decoded_response = $this->decode_response( $response );
		$response_data    = isset( $decoded_response['response_data']['data'] ) ? $decoded_response['response_data']['data'] : null;

		if ( $response_data !== true ) {
			return false;
		}

		return (bool) $this->get_result( $response );
	}

	public function get_survey_questions(): array {

		$response = $this->client->get(
			self::GET_SURVEY,
			array(
				'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
			)
		);

		if ( ! $this->get_result( $response ) ) {
			return array();
		}

		$response_data = $this->decode_response( $response )['response_data']['data'];

		if ( isset( $response_data['questions'] ) && is_array( $response_data['questions'] ) ) {
			return $response_data['questions'];
		}

		return array();
	}

	public function submit_survey_data( array $data ): bool {
		$response = $this->client->post( self::SUBMIT_SURVEY, $data );
		return $this->get_result( $response );
	}

	/**
	 * @param array|WP_Error $response
	 *
	 * @return mixed
	 */
	public function get_result( $response ) {
		$data = $this->decode_response( $response );

		if ( is_wp_error( $data ) || $data['response_code'] !== 200 ) {
			error_log( 'Error: ' . $data['response_body'] );
			return false;
		}

		return $data['response_data']['data'];
	}

	/**
	 * @param array|WP_Error $response
	 *
	 * @return array
	 */
	public function decode_response( $response ): array {
		$response_body = wp_remote_retrieve_body( $response );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_data = json_decode( $response_body, true );

		if ( ! is_array( $response_data ) ) {
			$response_data = array( 'data' => null );
		}

		return array(
			'response_code' => $response_code,
			'response_data' => $response_data,
			'response_body' => $response_body,
		);
	}
}
