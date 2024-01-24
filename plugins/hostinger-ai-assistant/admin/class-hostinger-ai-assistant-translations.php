<?php

class Hostinger_Frontend_Translations {
	protected $frontend_translations;

	public function __construct() {
		$this->setup_translations();
	}

	public function get_frontend_translations(): array {
		return $this->frontend_translations;
	}

	protected function setup_translations(): void {
		$this->frontend_translations = array(
			'tones_selected'   => esc_html__('tones selected', 'hostinger-ai-assistant'),
			'voice_tones'      => array(
				'neutral'     => esc_html__('Neutral', 'hostinger-ai-assistant'),
				'formal'      => esc_html__('Formal', 'hostinger-ai-assistant'),
				'trustworthy' => esc_html__('Trustworthy', 'hostinger-ai-assistant'),
				'friendly'    => esc_html__('Friendly', 'hostinger-ai-assistant'),
				'witty'       => esc_html__('Witty', 'hostinger-ai-assistant'),
			),
			'example_keywords' => esc_html__('Example: website development, WordPress tutorial, ...', 'hostinger-ai-assistant'),
			'at_least_ten'     => esc_html__('Enter at least 10 characters', 'hostinger-ai-assistant'),
			'let_us_now_more'  => esc_html__('Let us now more about your post idea. Share more details for better results', 'hostinger-ai-assistant'),
			'youre_good'       => esc_html__('You\'re good to go, but you can share more details for better results', 'hostinger-ai-assistant'),
			'add_new_with_ai'  => esc_html__('Add New with AI', 'hostinger-ai-assistant'),
		);
	}
}
