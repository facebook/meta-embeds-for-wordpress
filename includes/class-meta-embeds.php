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
		'threads'   => array(
			'patterns'     => array(
				'#https?://(www\.)?threads\.(com|net)/@[^/]+/post/.+#i',
				'#https?://(www\.)?threads\.(com|net)/t/.+#i',
			),
			'endpoint'     => 'https://graph.threads.com/oembed',
			'embed_script' => 'https://www.threads.com/embed.js',
		),
		'instagram' => array(
			'patterns'     => array(
				'#https?://(www\.)?instagram\.com/(p|reel)/[^/]+#i',
				'#https?://(www\.)?instagram\.com/(?!p/|reel/|stories/|explore/|accounts/|direct/|reels/|tv/|about/|legal/|developer/|api/|static/|nametag/|directory/)([a-zA-Z0-9._]{1,30})/?$#i',
			),
			'endpoint'     => 'https://graph.facebook.com/v25.0/instagram_oembed',
			'embed_script' => 'https://www.instagram.com/embed.js',
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
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 10, 3 );
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
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-dom-ready' ),
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

	/**
	 * Serve local plugin info for the "View details" modal.
	 *
	 * Since this plugin is not hosted on WordPress.org, WordPress has no
	 * remote source for the details modal. This filter reads readme.txt
	 * directly so the modal always reflects the installed version.
	 *
	 * @param false|object|array $result The result object or array.
	 * @param string             $action The API action being performed.
	 * @param object             $args   Plugin API arguments.
	 * @return false|object|array
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( 'plugin_information' !== $action || ! isset( $args->slug ) || 'meta-embeds' !== $args->slug ) {
			return $result;
		}

		$readme_file = META_EMBEDS_PLUGIN_DIR . 'readme.txt';
		if ( ! file_exists( $readme_file ) ) {
			return $result;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file read.
		$readme = file_get_contents( $readme_file );
		if ( false === $readme ) {
			return $result;
		}

		$plugin_data = get_plugin_data( META_EMBEDS_PLUGIN_DIR . 'meta-embeds.php' );

		$info               = new stdClass();
		$info->name         = $plugin_data['Name'];
		$info->slug         = 'meta-embeds';
		$info->version      = META_EMBEDS_VERSION;
		$info->author       = $plugin_data['Author'];
		$info->homepage     = $plugin_data['PluginURI'];
		$info->requires     = $plugin_data['RequiresWP'];
		$info->tested       = $this->parse_readme_header( $readme, 'Tested up to' );
		$info->requires_php = $plugin_data['RequiresPHP'];
		$info->sections     = $this->parse_readme_sections( $readme );

		return $info;
	}

	/**
	 * Extract a header value from readme.txt.
	 *
	 * @param string $readme The raw readme.txt content.
	 * @param string $field  The header field name.
	 * @return string
	 */
	private function parse_readme_header( $readme, $field ) {
		if ( preg_match( '/^' . preg_quote( $field, '/' ) . ':\s*(.+)$/mi', $readme, $matches ) ) {
			return trim( $matches[1] );
		}
		return '';
	}

	/**
	 * Parse readme.txt into sections for the plugin info modal.
	 *
	 * @param string $readme The raw readme.txt content.
	 * @return array Associative array of section slug => HTML content.
	 */
	private function parse_readme_sections( $readme ) {
		$sections    = array();
		$section_map = array(
			'Description'                => 'description',
			'Installation'               => 'installation',
			'Frequently Asked Questions' => 'faq',
			'Changelog'                  => 'changelog',
			'Privacy'                    => 'other_notes',
		);

		if ( ! preg_match_all( '/^==\s+(.+?)\s+==/m', $readme, $matches, PREG_OFFSET_CAPTURE ) ) {
			return $sections;
		}

		$count = count( $matches[0] );
		for ( $i = 0; $i < $count; $i++ ) {
			$title = trim( $matches[1][ $i ][0] );
			$start = $matches[0][ $i ][1] + strlen( $matches[0][ $i ][0] );
			$end   = ( $i + 1 < $count ) ? $matches[0][ $i + 1 ][1] : strlen( $readme );
			$body  = trim( substr( $readme, $start, $end - $start ) );

			if ( isset( $section_map[ $title ] ) && '' !== $body ) {
				$sections[ $section_map[ $title ] ] = $this->convert_readme_to_html( $body );
			}
		}

		return $sections;
	}

	/**
	 * Convert readme.txt markup to HTML.
	 *
	 * @param string $text readme.txt formatted text.
	 * @return string HTML.
	 */
	private function convert_readme_to_html( $text ) {
		// = Subheading = → <h4>.
		$text = preg_replace( '/^=\s+(.+?)\s+=/m', '</p><h4>$1</h4><p>', $text );

		// **bold** → <strong>.
		$text = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text );

		// `code` → <code>.
		$text = preg_replace( '/`([^`]+)`/', '<code>$1</code>', $text );

		// [link text](url) → <a>.
		$text = preg_replace( '/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text );

		// Bullet list items.
		$text = preg_replace( '/^\*\s+(.+)$/m', '<li>$1</li>', $text );

		// Numbered list items.
		$text = preg_replace( '/^\d+\.\s+(.+)$/m', '<li>$1</li>', $text );

		// Wrap consecutive <li> in <ul>.
		$text = preg_replace( '#((?:\s*<li>.*?</li>\s*)+)#s', '<ul>$1</ul>', $text );

		// Paragraphs from double newlines.
		$text = '<p>' . preg_replace( '/\n{2,}/', '</p><p>', $text ) . '</p>';

		// Clean up empty and mis-nested tags.
		$text = str_replace( array( '<p></p>', '<p><h4>', '</h4></p>', '<p><ul>', '</ul></p>' ), array( '', '<h4>', '</h4>', '<ul>', '</ul>' ), $text );

		return $text;
	}
}
