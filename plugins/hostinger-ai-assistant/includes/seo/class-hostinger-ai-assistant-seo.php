<?php

/**
 * The file that defines all related to SEO
 *
 * @link       https://hostinger.com
 * @since      1.3.1
 *
 * @package    Hostinger_Ai_Assistant
 * @subpackage Hostinger_Ai_Assistant/admin
 */
class Hostinger_Ai_Assistant_Seo {
	const YOAST_SEO_PLUGIN_SLUG = 'yoast';
	const RANK_MATH_SEO_PLUGIN_SLUG = 'rank-math';
	private Hostinger_Ai_Assistant_Helper $helper;

	public function __construct() {
		$this->helper = new Hostinger_Ai_Assistant_Helper();
		add_action( 'wp_head', array( $this, 'print_seo_meta_tags' ) );
	}

	public function add_seo_meta_data( string $keywords, string $description, int $post_id ): void {
		$title = get_the_title( $post_id );
		update_post_meta( $post_id, 'hostinger_ai_assistant_seo_keywords', $keywords );
		update_post_meta( $post_id, 'hostinger_ai_assistant_seo_description', $description );
		update_post_meta( $post_id, 'hostinger_ai_assistant_seo_title', $title );

		$this->add_seo_meta_tags( $post_id );
	}

	public function add_seo_meta_tags( $post_id ): void {
		$seo_meta             = $this->get_seo_meta( $post_id );
		$yoast_seo_active     = $this->is_yoast_seo_active();
		$rank_math_seo_active = $this->is_rank_math_seo_active();

		if ( $yoast_seo_active && ! $seo_meta['yoast_seo_tags_created'] ) {
			$this->add_seo_tags_for_plugin( $post_id, $seo_meta, self::YOAST_SEO_PLUGIN_SLUG );
		} elseif ( $rank_math_seo_active && ! $seo_meta['rank_seo_tags_created'] ) {
			$this->add_seo_tags_for_plugin( $post_id, $seo_meta, self::RANK_MATH_SEO_PLUGIN_SLUG );
		}
	}

	public function print_seo_meta_tags(): void {
		$post_id  = get_the_ID();
		$seo_meta = $this->get_seo_meta( $post_id );

		if ( ! $this->is_seo_plugin_active() ) {
			$this->output_meta_tags( $seo_meta['keywords'], $seo_meta['description'], $seo_meta['title'] );
		}
	}

	private function get_seo_meta( int $post_id ): array {
		return [
			'keywords'               => get_post_meta( $post_id, 'hostinger_ai_assistant_seo_keywords', true ),
			'description'            => get_post_meta( $post_id, 'hostinger_ai_assistant_seo_description', true ),
			'title'                  => get_post_meta( $post_id, 'hostinger_ai_assistant_seo_title', true ),
			'yoast_seo_tags_created' => get_post_meta( $post_id, 'hts_yoast_seo_tags_created', true ),
			'rank_seo_tags_created'  => get_post_meta( $post_id, 'hts_rank_seo_tags_created', true ),
		];
	}

	private function add_seo_tags_for_plugin( int $post_id, array $seo_meta, string $plugin ): void {
		if ( $plugin === self::YOAST_SEO_PLUGIN_SLUG ) {
			$this->add_yoast_meta_tags( $post_id, $seo_meta['description'], $this->get_single_keyword( $seo_meta['keywords'] ), $seo_meta['title'] );
			update_post_meta( $post_id, 'hts_yoast_seo_tags_created', true );
		} elseif ( $plugin === self::RANK_MATH_SEO_PLUGIN_SLUG ) {
			$this->add_rank_math_meta_tags( $post_id, $seo_meta['description'], $this->get_keywords( $seo_meta['keywords'], 4 ), $seo_meta['title'] );
			update_post_meta( $post_id, 'hts_rank_seo_tags_created', true );
		}
	}

	private function is_seo_plugin_active(): bool {
		return $this->is_yoast_seo_active() || $this->is_rank_math_seo_active();
	}

	public function add_yoast_meta_tags( int $post_id, string $meta_description, string $keyword, string $meta_title ): void {
		if ( $meta_title ) {
			update_post_meta( $post_id, '_yoast_wpseo_title', $meta_title );
		}

		if ( $meta_description ) {
			update_post_meta( $post_id, '_yoast_wpseo_metadesc', $meta_description );
		}
		if ( $keyword ) {
			update_post_meta( $post_id, '_yoast_wpseo_focuskw', $keyword );
		}
	}

	public function add_rank_math_meta_tags( int $post_id, string $meta_description, string $keyword, string $meta_title ): void {
		if ( $meta_description ) {
			update_post_meta( $post_id, 'rank_math_description', $meta_description );
		}
		if ( $keyword ) {
			update_post_meta( $post_id, 'rank_math_focus_keyword', $keyword );
		}
		if ( $meta_title ) {
			update_post_meta( $post_id, 'rank_math_title', $meta_title );
		}
	}

	private function output_meta_tags( string $seo_keywords, string $seo_description, string $seo_title = '' ): void {
		if ( ! empty( $seo_keywords ) ) {
			echo '<meta name="keywords" content="' . esc_attr( $seo_keywords ) . '" />' . "\n";
		}
		if ( ! empty( $seo_description ) ) {
			echo '<meta name="description" content="' . esc_attr( $seo_description ) . '" />' . "\n";
		}

		if ( ! empty( $seo_title ) ) {
			echo '<meta name="title" content="' . esc_attr( $seo_title ) . '" />' . "\n";
		}
	}

	private function get_single_keyword( string $keywords ): string {
		$keywords = explode( ',', $keywords );
		if ( ! empty( $keywords ) ) {
			return trim( $keywords[0] );
		} else {
			return '';
		}
	}

	private function get_keywords( string $keywords, int $max_count = 1 ): string {
		$keywords = explode( ',', $keywords );
		$keywords = array_slice( $keywords, 0, $max_count );
		$keywords = array_map( 'trim', $keywords );
		$keywords = array_filter( $keywords );

		return implode( ', ', $keywords );
	}

	private function is_yoast_seo_active(): bool {
		return $this->helper->is_plugin_active( 'wp-seo' );
	}

	private function is_rank_math_seo_active(): bool {
		return $this->helper->is_plugin_active( 'rank-math' );
	}
}

$seo = new Hostinger_Ai_Assistant_Seo();

