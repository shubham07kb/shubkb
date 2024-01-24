<?php

class Hostinger_Ai_Assistant_Requests {
	private const GENERATE_CONTENT_IMAGES_ACTION = '/v3/wordpress/plugin/get-images';
	private const GENERATE_CONTENT_ACTION = '/v3/wordpress/plugin/generate-content';
	private const GET_UNSPLASH_IMAGE_ACTION = '/v3/wordpress/plugin/download-image';

	private Hostinger_Ai_Assistant_Requests_Client $client;
	private Hostinger_Ai_Assistant_Amplitude $amplitude;
	private Hostinger_Ai_Assistant_Content_Generation $generate_content;
	private Hostinger_Ai_Assistant_Config $config_handler;
	private Hostinger_Ai_Assistant_Errors $error_handler;
	private Hostinger_Ai_Assistant_Helper $helper;

	public function __construct() {
		$this->helper           = new Hostinger_Ai_Assistant_Helper();
		$this->config_handler   = new Hostinger_Ai_Assistant_Config();
		$this->error_handler    = new Hostinger_Ai_Assistant_Errors();
		$this->client           = new Hostinger_Ai_Assistant_Requests_Client( $this->config_handler->get_config_value( 'base_rest_uri', HOSTINGER_AI_ASSISTANT_REST_URI ), [
			Hostinger_Ai_Assistant_Config::TOKEN_HEADER  => $this->helper::get_api_token(),
			Hostinger_Ai_Assistant_Config::DOMAIN_HEADER => $this->helper->get_host_info()
		] );
		$this->amplitude        = new Hostinger_Ai_Assistant_Amplitude();
		$this->generate_content = new Hostinger_Ai_Assistant_Content_Generation();
		add_action( 'init', [ $this, 'define_ajax_events' ], 0 );
	}

	public function define_ajax_events(): void {
		$events = [
			'get_content_from_description',
			'redirect_to_post_editor_with_content',
			'redirect_to_published_post',
		];

		foreach ( $events as $event ) {
			$ajax_event = 'hts_' . $event;
			add_action( 'wp_ajax_' . $ajax_event, [ $this, $event ] );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, [ $this, $event ] );
		}
	}

	public function get_content_from_description(): void {
		$error_msg        = $this->error_handler->get_error_message( 'action_failed' );
		$unexpected_error = $this->error_handler->get_error_message( 'unexpected_error' );
		$server_error     = $this->error_handler->get_error_message( 'server_error' );

		try {
			$nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
			$description    = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
			$post_type      = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'blog_post';
			$voice_tone     = isset( $_POST['voice_tone'] ) ? sanitize_text_field( $_POST['voice_tone'] ) : 'neutral';
			$focus_keywords = isset( $_POST['focus_keywords'] ) ? sanitize_text_field( $_POST['focus_keywords'] ) : '';
			$content_length = isset( $_POST['content_length'] ) ? sanitize_text_field( $_POST['content_length'] ) : '150-300';

			if ( ! wp_verify_nonce( $nonce, 'generate_content' ) ) {
				$this->helper->ajax_error_message( $error_msg, $error_msg );
			}

			$validated_post_type = $this->generate_content->validate_post_type( $post_type );
			$post_type           = $this->generate_content->map_post_type( $validated_post_type );

			$response = $this->client->get( self::GENERATE_CONTENT_ACTION, [
				'post_type'     => $post_type,
				'tone'          => $voice_tone,
				'length'        => $this->generate_content->validate_content_length( $content_length ),
				'description'   => $description,
				'focus_keyword' => $focus_keywords
			] );

			$response_code = wp_remote_retrieve_response_code( $response );
			$response_body = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $response ) || $response_code !== 200 ) {
				$error_message = isset( json_decode( $response_body )->error->message )
					? json_decode( $response_body )->error->message
					: $unexpected_error;
				$this->helper->ajax_error_message( $error_message, $server_error );
			} else {
				$generated_content = reset( json_decode( $response['body'] )->data );

				if ( isset( $generated_content->tags[0] ) && $generated_content->tags[0] !== '' ) {
					$unsplash_image_data = $this->get_unsplash_image_data( $generated_content->tags[0] );
					if ( $unsplash_image_data->image ) {
						$generated_content->image = $unsplash_image_data->image;
					}
				}

				$this->amplitude->ai_content_created();
				wp_send_json_success( $generated_content );
			}
		} catch ( Exception $exception ) {
			$this->helper->ajax_error_message( 'Error: ' . $exception->getMessage(), $server_error );
		}
	}

	public function redirect_to_post_editor_with_content(): void {
		$this->generate_content->process_post_action( 'create' );
	}

	public function redirect_to_published_post(): void {
		$this->generate_content->process_post_action( 'publish' );
	}

	public function get_uploaded_unsplash_image( object $image_data ): int {
		$server_error = $this->error_handler->get_error_message( 'server_error' );

		try {
			$params        = json_encode( array(
				'domain'             => ( new Hostinger_Ai_Assistant_Helper() )->get_host_info(),
				'download_directory' => wp_upload_dir()['basedir'],
				'unsplash_image'     => $image_data->image,
				'track_download_uri' => $image_data->download_location,
			) );
			$headers       = array( 'Content-Type' => 'application/json' );
			$response      = $this->client->post( self::GET_UNSPLASH_IMAGE_ACTION, $params, $headers );
			$response_code = wp_remote_retrieve_response_code( $response );

			if ( $response_code !== 200 ) {
				return 0;
			}

			if ( is_wp_error( $response ) ) {
				$this->helper->ajax_error_message( $response->get_error_message(), $server_error );
			}

			$image_path = json_decode( $response['body'] )->data->result;
			$image_id   = $this->generate_content->upload_image_to_media_library( $image_path, $image_data );

			return $image_id;
		} catch ( Exception $exception ) {
			$this->helper->ajax_error_message( 'Error: ' . $exception->getMessage(), $server_error );
		}
	}

	public function get_unsplash_image_data( string $tags ): object {
		$unexpected_error = $this->error_handler->get_error_message( 'unexpected_error' );

		if ( ! isset( $tags ) || $tags == '' ) {
			$this->helper->ajax_error_message( $unexpected_error, $unexpected_error );
		}

		$keywords = explode( ',', $tags );

		try {
			$response = $this->client->get( self::GENERATE_CONTENT_IMAGES_ACTION, [
				'keywords' => array( $keywords[0] ),
				'amount'   => 1
			] );

			$response_code = wp_remote_retrieve_response_code( $response );
			$response_body = wp_remote_retrieve_body( $response );
			$response_data = json_decode( $response_body )->data;

			if ( empty( $response_data ) ) {
				return new stdClass();
			}

			if ( is_wp_error( $response ) || $response_code !== 200 ) {
				$error_message = isset( json_decode( $response_body )->error->message )
					? json_decode( $response_body )->error->message
					: $unexpected_error;
				$this->helper->ajax_error_message( $error_message, $unexpected_error );
			} else {
				$response = reset( json_decode( $response_body )->data );

				return $response;
			}
		} catch ( Exception $exception ) {
			$this->helper->ajax_error_message( $exception->getMessage(), $unexpected_error );

		}
	}

}

new Hostinger_Ai_Assistant_Requests();
