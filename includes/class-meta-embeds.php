<?php
/**
 * Core plugin class.
 *
 * @package MetaEmbeds
 * @copyright 2026 Meta Platforms, Inc. and affiliates
 * @license GPL-2.0-or-later
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Meta_Embeds {

	/**
	 * Singleton instance.
	 *
	 * @var Meta_Embeds|null
	 */
	private static $instance = null;

	/**
	 * OEmbed providers to register.
	 *
	 * Each entry contains:
	 *   - patterns: Array of regex patterns for matching URLs.
	 *   - endpoint: oEmbed API endpoint URL.
	 *   - embed_script: URL of the embed SDK script for rendering.
	 *
	 * @var array
	 */
	private $providers = array(
		'threads' => array(
			'patterns'     => array(
				'#https?://(www\.)?threads\.(com|net)/@[^/]+/post/.+#i',
				'#https?://(www\.)?threads\.(com|net)/t/.+#i',
			),
			'endpoint'     => 'https://graph.threads.com/oembed',
			'embed_script' => 'https://www.threads.com/embed.js',
		),
	);

	/**
	 * Get singleton instance.
	 *
	 * @return Meta_Embeds
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor. Registers hooks.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_oembed_providers' ) );
		add_action( 'init', array( $this, 'register_block_variations' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_embed_scripts' ) );
		add_filter( 'embed_oembed_html', array( $this, 'filter_embed_html' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	/**
	 * Register Meta platforms as oEmbed providers.
	 *
	 * Skips registration if WordPress Core already provides the same endpoint,
	 * avoiding duplicate providers when Core adds native support.
	 */
	public function register_oembed_providers() {
		$oembed = _wp_oembed_get_object();

		foreach ( $this->providers as $provider ) {
			// Skip if Core already registered this endpoint.
			if ( $this->is_provider_registered( $oembed, $provider['endpoint'] ) ) {
				continue;
			}

			foreach ( $provider['patterns'] as $pattern ) {
				wp_oembed_add_provider(
					$pattern,
					$provider['endpoint'],
					true // Uses regex.
				);
			}
		}
	}

	/**
	 * Check if an oEmbed provider endpoint is already registered.
	 *
	 * @param WP_oEmbed $oembed   The oEmbed object.
	 * @param string    $endpoint The endpoint URL to check.
	 * @return bool
	 */
	private function is_provider_registered( $oembed, $endpoint ) {
		foreach ( $oembed->providers as $provider_info ) {
			if ( $provider_info[0] === $endpoint ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Register block editor variations for embed blocks.
	 */
	public function register_block_variations() {
		wp_register_script(
			'meta-embeds-block-variations',
			META_EMBEDS_PLUGIN_URL . 'src/blocks/meta-embed/index.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			META_EMBEDS_VERSION,
			true
		);
	}

	/**
	 * Enqueue embed SDK scripts on the frontend when embeds are present.
	 */
	public function maybe_enqueue_embed_scripts() {
		global $post;

		if ( ! is_singular() || ! $post ) {
			return;
		}

		foreach ( $this->providers as $name => $provider ) {
			if ( $this->post_has_embed( $post, $provider['patterns'] ) ) {
				// phpcs:disable WordPress.WP.EnqueuedResourceParameters.MissingVersion -- External CDN script, no local version.
				wp_enqueue_script(
					"meta-embeds-{$name}-sdk",
					$provider['embed_script'],
					array(),
					null,
					array(
						'strategy'  => 'defer',
						'in_footer' => true,
					)
				);
				// phpcs:enable WordPress.WP.EnqueuedResourceParameters.MissingVersion
			}
		}
	}

	/**
	 * Remove embed SDK script tags from oEmbed HTML.
	 *
	 * The oEmbed response includes an inline script tag for the embed SDK.
	 * Since we enqueue the SDK separately via wp_enqueue_script() with a
	 * deferred loading strategy, we strip the inline tag to prevent the
	 * script from loading twice.
	 *
	 * @param string $html The oEmbed HTML.
	 * @return string Filtered HTML without embed SDK script tags.
	 */
	public function filter_embed_html( $html ) {
		foreach ( $this->providers as $provider ) {
			$html = preg_replace(
				'#<script[^>]*\ssrc=["\']' . preg_quote( $provider['embed_script'], '#' ) . '["\'][^>]*>\s*</script>#i',
				'',
				$html
			);
		}
		return $html;
	}

	/**
	 * Enqueue block editor assets.
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_script( 'meta-embeds-block-variations' );
		wp_enqueue_style(
			'meta-embeds-editor',
			META_EMBEDS_PLUGIN_URL . 'src/blocks/meta-embed/editor.css',
			array(),
			META_EMBEDS_VERSION
		);
	}

	/**
	 * Check if a post contains an embed matching any of the given patterns.
	 *
	 * @param WP_Post  $post     The post to check.
	 * @param string[] $patterns Array of regex patterns to match.
	 * @return bool
	 */
	private function post_has_embed( $post, $patterns ) {
		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $post->post_content ) ) {
				return true;
			}
		}

		return false;
	}
}
