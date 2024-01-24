<?php

class Hostinger_Errors {

	private $error_messages;

	public function __construct() {
		$this->error_messages = array(
			'action_failed'    => array(
				'default' => __( 'Action Failed. Try again or contact support. Apologies.', 'hostinger' ),
			),
			'unexpected_error' => array(
				'default' => __( 'An unexpected error occurred. Please try again or contact support.', 'hostinger' ),
			),
			'server_error'     => array(
				'default' => __( 'We apologize for the inconvenience. The AI content generation process encountered a server error. Please try again later, and if the issue persists, kindly contact our support team for assistance.', 'hostinger' ),
			),
		);
	}

	public function get_error_message( string $error_code ) {
		if ( array_key_exists( $error_code, $this->error_messages ) ) {
			$message_data = $this->error_messages[ $error_code ];

			return $message_data['default'];
		} else {
			return __( 'Unknown error code.', 'hostinger' );
		}
	}


}

$hostiner_errors = new Hostinger_Errors();
