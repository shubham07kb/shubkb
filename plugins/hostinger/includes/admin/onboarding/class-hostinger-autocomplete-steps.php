<?php

defined( 'ABSPATH' ) || exit;

class Hostinger_AutoComplete_Steps {
	private array $completed_steps;
	private Hostinger_Helper $helper;

	public function __construct() {
		$this->helper		  = new Hostinger_Helper();
		$this->completed_steps = get_option( 'hostinger_onboarding_steps', [] );
		add_action( 'customize_save', [ $this, 'logo_upload' ] );
		add_action( 'wp_handle_upload', [ $this, 'image_upload' ] );
		add_action( 'post_updated', [ $this, 'post_content_change' ], 10, 3 );
		add_action( 'customize_save', [ $this, 'edit_site_title' ] );
		add_action( 'publish_page', [ $this, 'new_page_creation' ], 10, 3 );
		add_action( 'publish_product', [ $this, 'new_product_creation' ], 10, 3 );
		add_action( 'publish_post', [ $this, 'new_post_creation' ], 10, 3 );
		add_action( 'updated_option', [ $this, 'check_option_change' ], 10, 3 );

		if( $this->helper->is_hostinger_admin_page() ) {
			add_action( 'admin_init', [ $this, 'domain_is_connected' ] );
		}
	}

	private function add_completed_step( string $action ): void {
		$this->completed_steps[] = [
			'action' => $action,
			'date'   => date( 'Y-m-d H:i:s' ),
		];
	}

	private function is_step_completed( array $completed_steps, string $step_name ): array {
		$step_completed = array_filter( $completed_steps, function ( $item ) use ( $step_name ) {
			return isset( $item['action'] ) && $item['action'] === $step_name;
		} );

		return $step_completed;
	}

	public function domain_is_connected(): void {
		$action = Hostinger_Admin_Actions::DOMAIN_IS_CONNECTED;

		if ( $this->is_step_completed( $this->completed_steps, $action ) ) {
			return;
		}

		if ( ! $this->helper->is_free_subdomain() && ! $this->helper->is_preview_domain() ) {
			if ( ! did_action( 'hostinger_domain_connected' ) ) {
				$this->add_completed_step( $action );
				Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
				do_action( 'hostinger_domain_connected' );
			}
		}
	}

	public function logo_upload( WP_Customize_Manager $data ): void {
		$action = Hostinger_Admin_Actions::LOGO_UPLOAD;

		$logo_updated = array_filter( $data->changeset_data(), function ( $key ) {
			return strpos( $key, 'custom_logo' ) !== false;
		}, ARRAY_FILTER_USE_KEY );

		$has_logo     = reset( $logo_updated )['value'] ?? false;
		$cookie_value = $_COOKIE[ $action ] ?? '';

		if ( $this->is_step_completed( $this->completed_steps, $action ) || $logo_updated && ! $has_logo ) {
			return;
		}

		if ( $logo_updated && $cookie_value === $action ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}
	}

	public function image_upload( array $data ): array {
		$action       = Hostinger_Admin_Actions::IMAGE_UPLOAD;
		$file_type    = $data['type'] ?? '';
		$cookie_value = $_COOKIE[ $action ] ?? '';

		if ( $this->is_step_completed( $this->completed_steps, $action ) || strpos( $file_type, 'image' ) !== 0 ) {
			return $data;
		}

		if ( $cookie_value === $action ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}

		return $data;
	}

	public function post_content_change( int $post_id, WP_Post $post_after, WP_Post $post_before ) {
		$action         = Hostinger_Admin_Actions::EDIT_DESCRIPTION;
		$post_date      = get_the_date( 'Y-m-d H:i:s', $post_id );
		$modified_date  = get_the_modified_date( 'Y-m-d H:i:s', $post_id );
		$post_type      = get_post_type( $post_id );
		$cookie_value   = $_COOKIE[ $action ] ?? '';
		$content_before = $post_before->post_content;
		$content_after  = $post_after->post_content;

		if ( $this->is_step_completed( $this->completed_steps, $action ) || $post_date === $modified_date ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( $post_type === 'post' && $content_before !== $content_after && $cookie_value === $action ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}

	}

	public function edit_site_title( WP_Customize_Manager $data ): void {
		$action        = Hostinger_Admin_Actions::EDIT_SITE_TITLE;
		$changed_title = $data->changeset_data()['blogname']['value'] ?? '';
		$cookie_value  = $_COOKIE[ $action ] ?? '';

		if ( $this->is_step_completed( $this->completed_steps, $action ) ) {
			return;
		}

		if ( $cookie_value === $action && $changed_title !== '' && get_bloginfo( 'name' ) !== $changed_title ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}
	}

	public function new_post_item_creation( int $post_id, bool $update, string $action ): void {
		$cookie_value = $_COOKIE[ $action ] ?? '';

		if ( $this->is_step_completed( $this->completed_steps, $action ) || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( $update && $cookie_value === $action ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}
	}

	public function new_page_creation( int $post_id, WP_Post $post, bool $update ): void {
		$this->new_post_item_creation( $post_id, $update, Hostinger_Admin_Actions::ADD_PAGE );
	}

	public function new_product_creation( int $post_id, WP_Post $post, bool $update ): void {
		$this->new_post_item_creation( $post_id, $update, Hostinger_Admin_Actions::ADD_PRODUCT );
	}

	public function new_post_creation( int $post_id, WP_Post $post, bool $update ): void {
		$this->new_post_item_creation( $post_id, $update, Hostinger_Admin_Actions::ADD_POST );
	}

	public function check_option_change( string $option_name, $old_value, $new_value ): void {
		$action       = Hostinger_Admin_Actions::EDIT_SITE_TITLE;
		$cookie_value = $_COOKIE[ $action ] ?? '';

		if ( $this->is_step_completed( $this->completed_steps, $action ) ) {
			return;
		}

		if ( $cookie_value === $action && $new_value !== '' && $option_name === Hostinger_Settings::SITE_TITLE_OPTION && $old_value !== $new_value ) {
			$this->add_completed_step( $action );
			Hostinger_Settings::update_setting( 'onboarding_steps', $this->completed_steps );
		}
	}

}

new Hostinger_AutoComplete_Steps();
