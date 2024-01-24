<?php

/**
 * The file that defines all content filters
 *
 * @link       https://hostinger.com
 * @since      1.1.2
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */
class Hostinger_Ai_Assistant_Content_Filters {

	private Hostinger_Ai_Assistant_Helper $helper;

	public function __construct() {
		$this->helper = new Hostinger_Ai_Assistant_Helper();

		if ( $this->helper->is_preview_domain() ) {
			add_action( 'wp_get_attachment_url', array(
				$this,
				'replace_media_attachment_url_with_wp_get_attachment_url'
			), 10, 2 );
		}
		add_filter( 'wp_kses_allowed_html', array( $this, 'custom_kses_allowed_html' ) );

	}

	public function replace_media_attachment_url_with_wp_get_attachment_url( $url, $post_id ) {
		$parsed_url = parse_url( $url );
		$domain     = $this->helper->format_preview_domain( $parsed_url['host'] );

		return $parsed_url['scheme'] . '://' . $domain . $parsed_url['path'];
	}

	public function custom_kses_allowed_html( $allowed_html ) {
		if ( ! isset( $allowed_html['a'] ) || ! is_array( $allowed_html['a'] ) ) {
			$allowed_html['a'] = array();
		}

		$allowed_html['a'] = array_merge(
			$allowed_html['a'],
			array(
				'href'   => true,
				'title'  => true,
				'target' => true,
			)
		);

		return $allowed_html;
	}


}

$filters = new Hostinger_Ai_Assistant_Content_Filters();

