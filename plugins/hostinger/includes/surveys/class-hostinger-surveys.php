<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hostinger_Surveys {
	public const CACHE_THREE_HOURS                     = 3600 * 3;
	public const TIME_15_MINUTES                       = 900;
	public const TIME_24_HOURS                         = 86400;
	public const CLIENT_SURVEY_IDENTIFIER              = 'customer_satisfaction_score';
	public const CLIENT_WOO_COMPLETED_ACTIONS          = 'woocommerce_task_list_tracked_completed_tasks';
	public const WOO_SURVEY_IDENTIFIER                 = 'wordpress_woocommerce_onboarding';
	public const AI_ONBOARDING_SURVEY_IDENTIFIER       = 'wordpress_ai_onboarding';
	public const AFFILIATE_SURVEY_IDENTIFIER           = 'wordpress_amazon_affiliate';
	public const SUBMITTED_SURVEY_TRANSIENT            = 'submitted_survey_transient';
	public const SURVEY_QUESTIONS_TRANSIENT_REQUEST    = 'survey_questions_transient_request';
	public const SURVEY_QUESTIONS_TRANSIENT_RESPONSE   = 'survey_questions_transient_response';
	public const IS_CLIENT_ELIGIBLE_TRANSIENT_RESPONSE = 'client_eligibility_transient_response';
	public const IS_CLIENT_ELIGIBLE_TRANSIENT_REQUEST  = 'survey_questions_transient_request';
	public const LOCATION_SLUG                         = 'location';
	public const WOOCOMMERCE_PAGES                     = array(
		'/wp-admin/admin.php?page=wc-admin',
		'/wp-admin/edit.php?post_type=shop_order',
		'/wp-admin/admin.php?page=wc-admin&path=/customers',
		'/wp-admin/edit.php?post_type=shop_coupon&legacy_coupon_menu=1',
		'/wp-admin/admin.php?page=wc-admin&path=/marketing',
		'/wp-admin/admin.php?page=wc-reports',
		'/wp-admin/admin.php?page=wc-settings',
		'/wp-admin/admin.php?page=wc-status',
		'/wp-admin/admin.php?page=wc-admin&path=/extensions',
		'/wp-admin/edit.php?post_type=product',
		'/wp-admin/post-new.php?post_type=product',
		'/wp-admin/edit.php?post_type=product&page=product-reviews',
		'/wp-admin/edit.php?post_type=product&page=product_attributes',
		'/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product',
		'/wp-admin/edit-tags.php?taxonomy=product_tag&post_type=product',
		'/wp-admin/admin.php?page=wc-admin&path=/analytics/overview',
		'/wp-admin/admin.php?page=wc-admin',
	);

	private Hostinger_Config $config_handler;
	private Hostinger_Settings $settings;
	private Hostinger_Helper $helper;
	private Hostinger_Surveys_Questions $survey_questions;
	private Hostinger_Surveys_Rest $surveys_rest;

	public function __construct(
		Hostinger_Settings $settings,
		Hostinger_Helper $helper,
		Hostinger_Config $config_handler,
		Hostinger_Surveys_Questions $survey_questions,
		Hostinger_Surveys_Rest $surveys_rest
	) {
		$this->settings         = $settings;
		$this->helper           = $helper;
		$this->config_handler   = $config_handler;
		$this->survey_questions = $survey_questions;
		$this->surveys_rest     = $surveys_rest;
	}

	public function is_woocommerce_admin_page(): bool {
		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$current_uri = sanitize_text_field( $_SERVER['REQUEST_URI'] );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		if ( isset( $current_uri ) && strpos( $current_uri, '/wp-json/' ) !== false ) {
			return false;
		}

		foreach ( self::WOOCOMMERCE_PAGES as $page ) {
			if ( strpos( $current_uri, $page ) !== false ) {
				return true;
			}
		}

		return false;
	}

	public function is_content_generation_survey_enabled(): bool {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		$not_submitted                   = ! get_transient( self::SUBMITTED_SURVEY_TRANSIENT );
		$not_completed                   = ! $this->settings->get_setting( 'feedback_survey_completed' );
		$content_published               = $this->settings->get_setting( 'content_published' );
		$is_client_eligible              = $this->is_client_eligible();
		$is_hostinger_page               = $this->helper->is_hostinger_admin_page();

		if ( ! $is_hostinger_page || empty( $this->get_survey_questions() ) ) {
			return false;
		}

		return $not_submitted && $not_completed && $content_published && $is_client_eligible;
	}

	public function is_woocommerce_survey_enabled(): bool {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		$not_submitted                 = ! get_transient( self::SUBMITTED_SURVEY_TRANSIENT );
		$not_completed                 = ! $this->settings->get_setting( 'woocommerce_survey_completed' );
		$is_woocommerce_page           = $this->is_woocommerce_admin_page();
		$default_woocommerce_completed = $this->default_woocommerce_survey_completed();
		$is_client_eligible            = $this->is_client_eligible();

		if ( empty( $this->get_survey_questions() ) ) {
			return false;
		}

		return $not_submitted && $not_completed && $is_woocommerce_page && $default_woocommerce_completed && $is_client_eligible;
	}

	public function is_ai_onboarding_survey_enabled(): bool {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		$first_login_at                  = strtotime( get_option( 'hostinger_first_login_at', time() ) );
		$not_submitted                   = ! get_transient( self::SUBMITTED_SURVEY_TRANSIENT );
		$not_completed                   = ! $this->settings->get_setting( 'ai_onboarding_survey_completed' );
		$is_client_eligible              = $this->is_client_eligible();
		$is_ai_onboarding_passed         = $this->settings->get_setting( 'ai_onboarding' );
		$is_hostinger_admin_page         = $this->helper->is_hostinger_admin_page();

		if ( ! $is_ai_onboarding_passed || empty( $this->get_survey_questions() ) ) {
			return false;
		}

		if ( $first_login_at && ! $this->is_time_elapsed( $first_login_at, self::TIME_15_MINUTES ) ) {
			return false;
		}

		return $not_submitted && $not_completed && $is_client_eligible && $is_hostinger_admin_page;
	}

	public function is_affiliate_survey_enabled(): bool {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

		$not_submitted                   = ! get_transient( self::SUBMITTED_SURVEY_TRANSIENT );
		$not_completed                   = ! $this->settings->get_setting( 'affiliate_survey_completed' );
		$affiliate_links_exsists         = $this->settings->get_setting( 'affiliate_links_created' );
		$is_hostinger_admin_page         = $this->helper->is_hostinger_admin_page();
		$is_client_eligible              = $this->is_client_eligible();

		if ( ! $is_hostinger_admin_page || empty( $this->get_survey_questions() ) ) {
			return false;
		}

		return $not_submitted && $not_completed && $affiliate_links_exsists && $is_client_eligible;
	}

	public function default_woocommerce_survey_completed(): bool {
		$completed_actions          = get_option( self::CLIENT_WOO_COMPLETED_ACTIONS, array() );
		$required_completed_actions = array( 'products', 'payments' );

		return empty( array_diff( $required_completed_actions, $completed_actions ) );
	}

	public function is_client_eligible(): bool {
		$transient_request_key  = self::IS_CLIENT_ELIGIBLE_TRANSIENT_REQUEST;
		$transient_response_key = self::IS_CLIENT_ELIGIBLE_TRANSIENT_RESPONSE;

		// Check if transient response exists
		$cached_eligibility = get_transient( $transient_response_key );

		if ( $cached_eligibility && ( $cached_eligibility === 'eligible' || $cached_eligibility === 'not_eligible' ) ) {
			return $cached_eligibility === 'eligible';
		}

		// Attempt to set transient request
		if( ! $this->helper->check_transient_eligibility( $transient_request_key, self::CACHE_THREE_HOURS ) ) {
			return false;
		}

		try {
			$is_eligible = $this->surveys_rest->is_client_eligible();

			if ( has_action( 'litespeed_purge_all' ) ) {
				do_action( 'litespeed_purge_all' );
			}

			if ( $is_eligible ) {
				set_transient( $transient_response_key, 'eligible', self::CACHE_THREE_HOURS );
				return $is_eligible;
			}

			set_transient( $transient_response_key, 'not_eligible', self::CACHE_THREE_HOURS );
			return false;

		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error checking eligibility: ' . $exception->getMessage() );

			return false;
		}
	}

	public function get_survey_questions(): array {
		$transient_request_key  = self::SURVEY_QUESTIONS_TRANSIENT_REQUEST;
		$transient_response_key = self::SURVEY_QUESTIONS_TRANSIENT_RESPONSE;

		// Check if transient response exists
		$cached_questions = get_transient( $transient_response_key );

		if ( false !== $cached_questions ) {
			return $cached_questions;
		}

		// Attempt to set transient request
		if( ! $this->helper->check_transient_eligibility( $transient_request_key, self::CACHE_THREE_HOURS ) ) {
			return array();
		}

		try {
			$survey_questions = $this->surveys_rest->get_survey_questions();

			set_transient( $transient_response_key, $survey_questions, self::CACHE_THREE_HOURS );

			return $survey_questions;
		} catch ( Exception $exception ) {
			$this->helper->error_log( 'Error getting survey questions: ' . $exception->getMessage() );

			return array();
		}
	}

	public function submit_survey_answers( array $answers, string $survey_type ): void {
		$required_survey_items = $this->get_required_survey_items( $survey_type );
		$data                  = array(
			'identifier' => self::CLIENT_SURVEY_IDENTIFIER,
			'answers'    => $required_survey_items,
		);

		$answers = $this->add_user_answers( $data, $answers );
		$success = $this->surveys_rest->submit_survey_data( $answers, $survey_type );

		set_transient( self::SUBMITTED_SURVEY_TRANSIENT, true, self::TIME_24_HOURS );

		if ( $success ) {
			delete_transient( self::IS_CLIENT_ELIGIBLE_TRANSIENT_RESPONSE );

			switch ( $survey_type ) {
				case 'woo_survey':
					$setting_key = 'woocommerce_survey_completed';
					break;

				case 'ai_onboarding_survey':
					$setting_key = 'ai_onboarding_survey_completed';
					break;

				default:
					$setting_key = 'feedback_survey_completed';
			}

			$this->settings->update_setting( $setting_key, true );
			wp_send_json( __( 'Survey completed', 'hostinger' ) );

			if ( has_action( 'litespeed_purge_all' ) ) {
				do_action( 'litespeed_purge_all' );
			}
		}
	}

	public function get_wp_survey_questions(): array {
		$all_questions  = $this->get_survey_questions();
		$question_slugs = array( 'score', 'comment' );

		return $this->filter_questions_by_slug( $all_questions, $question_slugs );
	}

	public function get_specified_survey_questions( array $survey_questions, string $survey_type = 'ai_survey' ): array {
		$specified_survey_questions = array(
			'pages'               => array(),
			'showQuestionNumbers' => 'off',
			'showTOC'             => false,
			'pageNextText'        => __( 'Next', 'hostinger' ),
			'pagePrevText'        => __( 'Previous', 'hostinger' ),
			'completeText'        => __( 'Submit', 'hostinger' ),
			'completedHtml'       => __( 'Thank you for completing the survey !', 'hostinger' ),
			'requiredText'        => '*',
		);

		foreach ( $survey_questions as $question ) {
			$title = '';
			switch ( $survey_type ) {
				case 'woo_survey':
					$title = $this->survey_questions->map_survey_questions( $question['slug'] )['woo_question'];
					break;

				case 'ai_onboarding_survey':
					$title = $this->survey_questions->map_survey_questions( $question['slug'] )['ai_question'];
					break;

				case 'affiliate_plugin_survey':
					$title = $this->survey_questions->map_survey_questions( $question['slug'] )['affiliate_question'];
					break;

				default:
					$title = $this->survey_questions->map_survey_questions( $question['slug'] )['question'];
					break;
			}

			$element = array(
				'type'              => $this->survey_questions->map_survey_questions( $question['slug'] )['type'],
				'name'              => $question['slug'],
				'title'             => $title,
				'requiredErrorText' => __( 'Response required.', 'hostinger' ),
			);

			if ( $question['slug'] == 'comment' ) {
				$element['maxLength'] = 250;
			}

			if ( isset( $question['rules'] ) ) {
				$between_rule = $this->get_between_rule_values( $question['rules'] );
				if ( $between_rule ) {
					$element['rateMin']            = $between_rule[0];
					$element['rateMax']            = $between_rule[1];
					$element['minRateDescription'] = __( 'Poor', 'hostinger' );
					$element['maxRateDescription'] = __( 'Excellent', 'hostinger' );
				}

				if ( $this->is_survey_question_required( $question ) ) {
					$element['isRequired'] = true;
				}
			}

			$question_data = array(
				'name'     => $question['slug'],
				'elements' => array( $element ),
			);

			$specified_survey_questions['pages'][] = $question_data;
		}

		return $specified_survey_questions;
	}

	public function get_between_rule_values( array $rules ): array {
		foreach ( $rules as $rule ) {
			$position = strpos( $rule, 'between:' );
			if ( $position !== false ) {
				$between_values = $this->extract_between_values( $rule, $position );
				if ( count( $between_values ) === 2 ) {
					return $between_values;
				}
			}
		}

		return array();
	}

	public function generate_survey_html( string $survey_class ): string {
		ob_start();
		?>
		<div class="hts-survey-wrapper <?php echo esc_attr( $survey_class ); ?>">
			<div class="close-btn">
				<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 24 24">
					<path d="M 4.9902344 3.9902344 A 1.0001 1.0001 0 0 0 4.2929688 5.7070312 L 10.585938 12 L 4.2929688 18.292969 A 1.0001 1.0001 0 1 0 5.7070312 19.707031 L 12 13.414062 L 18.292969 19.707031 A 1.0001 1.0001 0 1 0 19.707031 18.292969 L 13.414062 12 L 19.707031 5.7070312 A 1.0001 1.0001 0 0 0 18.980469 3.9902344 A 1.0001 1.0001 0 0 0 18.292969 4.2929688 L 12 10.585938 L 5.7070312 4.2929688 A 1.0001 1.0001 0 0 0 4.9902344 3.9902344 z"></path>
				</svg>
			</div>
			<div id="hostinger-feedback-survey"></div>
			<div id="hts-questionsLeft">
				<span id="hts-currentQuestion">1</span> 
				<?php
				echo esc_html(
					__(
						'Question',
						'hostinger'
					)
				);
				?>
					<?php echo esc_html( __( 'of ', 'hostinger' ) ); ?>
				<span id="hts-allQuestions"></span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function customer_csat_survey(): void {
		if ( ! did_action( 'customer_csat_survey' ) ) {
			$survey_html = $this->generate_survey_html( 'hts-woocommerce-csat' );
			echo $survey_html;
			do_action( 'customer_csat_survey' );
		}
	}

	public function customer_ai_csat_survey(): void {
		if ( ! did_action( 'customer_ai_csat_survey' ) ) {
			$survey_html = $this->generate_survey_html( 'hts-ai-onboarding-csat' );
			echo $survey_html;
			do_action( 'customer_ai_csat_survey' );
		}
	}

	public function ai_plugin_survey(): void {
		if ( ! did_action( 'ai_plugin_survey' ) ) {
			$survey_html = $this->generate_survey_html( '' );
			echo $survey_html;
			do_action( 'ai_plugin_survey' );
		}
	}

	public function affiliate_plugin_survey(): void {
		if ( ! did_action( 'affiliate_plugin_survey' ) ) {
			$survey_html = $this->generate_survey_html( 'hts-affiliate-csat' );
			echo $survey_html;
			do_action( 'affiliate_plugin_survey' );
		}
	}

	private function extract_between_values( string $rule, int $position ): array {
		$between_prefix_length = strlen( 'between:' );
		$between_values      = explode( ',', substr( $rule, $position + $between_prefix_length ) );

		return count( $between_values ) === 2 ? $between_values : array();
	}

	public function is_time_elapsed( string $first_login_at, int $time_in_seconds ): bool {
		$current_time = time();
		$time_elapsed = $current_time - $time_in_seconds;

		return $time_elapsed >= $first_login_at;
	}

	private function get_required_survey_items( string $survey_type ): array {
		switch ( $survey_type ) {
			case 'woo_survey':
				return array(
					array(
						'question_slug' => self::LOCATION_SLUG,
						'answer'        => self::WOO_SURVEY_IDENTIFIER,
					),
				);
				break;
			case 'ai_onboarding_survey':
				return array(
					array(
						'question_slug' => self::LOCATION_SLUG,
						'answer'        => self::AI_ONBOARDING_SURVEY_IDENTIFIER,
					),
				);
				break;
			case 'affiliate_plugin_survey':
				return array(
					array(
						'question_slug' => self::LOCATION_SLUG,
						'answer'        => self::AFFILIATE_SURVEY_IDENTIFIER,
					),
				);
				break;
			default:
				return array(
					array(
						'question_slug' => self::LOCATION_SLUG,
						'answer'        => 'wordpress_ai_plugin',
					)
				);
		}
	}

	private function add_user_answers( array $data, array $answers ): array {
		foreach ( $answers as $answer_slug => $answer ) {
			$data['answers'][] = array(
				'question_slug' => $answer_slug,
				'answer'        => $answer,
			);
		}

		return $data;
	}

	private function filter_questions_by_slug( array $all_questions, $question_slugs ): array {
		$questions_with_required_rule = array();

		foreach ( $all_questions as $question ) {
			if ( isset( $question['slug'] ) && in_array( $question['slug'], $question_slugs ) ) {
				$questions_with_required_rule[] = array(
					'slug'  => $question['slug'],
					'rules' => $question['rules'],
				);
			}
		}

		return $questions_with_required_rule;
	}

	private function is_survey_question_required( array $question ): bool {
		return isset( $question['rules'] ) && in_array( 'required', $question['rules'] );
	}
}
