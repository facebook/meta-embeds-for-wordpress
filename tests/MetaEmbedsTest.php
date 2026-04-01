<?php
/**
 * Tests for Meta_Embeds class.
 *
 * @package MetaEmbeds
 * @copyright 2026 Meta Platforms, Inc. and affiliates
 * @license GPL-2.0-or-later
 */

/**
 * Test case for the Meta_Embeds class.
 */
class MetaEmbedsTest extends WP_UnitTestCase {

	/**
	 * Test that the plugin initializes and registers the singleton.
	 */
	public function test_instance() {
		$instance = Meta_Embeds::get_instance();
		$this->assertInstanceOf( Meta_Embeds::class, $instance );
		$this->assertSame( $instance, Meta_Embeds::get_instance() );
	}

	/**
	 * Test that Threads oEmbed provider is registered.
	 */
	public function test_threads_oembed_provider_registered() {
		// Trigger provider registration.
		do_action( 'init' );

		$oembed = _wp_oembed_get_object();

		$found = false;
		foreach ( $oembed->providers as $pattern => $provider_info ) {
			if ( strpos( $provider_info[0], 'graph.threads.com' ) !== false ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Threads oEmbed provider should be registered.' );
	}

	/**
	 * Test that Threads URLs match the registered pattern.
	 *
	 * @dataProvider threads_url_provider
	 * @param string $url      The URL to test.
	 * @param bool   $expected Whether the URL should match.
	 */
	public function test_threads_url_matching( $url, $expected ) {
		$patterns = array(
			'#https?://(www\.)?threads\.(com|net)/@[^/]+/post/.+#i',
			'#https?://(www\.)?threads\.(com|net)/t/.+#i',
		);

		$matched = false;
		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $url ) ) {
				$matched = true;
				break;
			}
		}

		$this->assertSame( $expected, $matched, "URL: {$url}" );
	}

	/**
	 * Data provider for Threads URL tests.
	 */
	public function threads_url_provider() {
		return array(
			// threads.com URLs.
			'com standard URL'          => array( 'https://www.threads.com/@zuck/post/C1234567890', true ),
			'com without www'           => array( 'https://threads.com/@zuck/post/C1234567890', true ),
			'com http URL'              => array( 'http://www.threads.com/@zuck/post/C1234567890', true ),
			'com short URL'             => array( 'https://www.threads.com/t/C1234567890', true ),
			'com short URL without www' => array( 'https://threads.com/t/C1234567890', true ),
			'com short URL http'        => array( 'http://www.threads.com/t/C1234567890', true ),
			// threads.net URLs (backwards compatibility).
			'net standard URL'          => array( 'https://www.threads.net/@zuck/post/DUGwwelEh_K', true ),
			'net without www'           => array( 'https://threads.net/@zuck/post/DUGwwelEh_K', true ),
			'net short URL'             => array( 'https://www.threads.net/t/DUGwwelEh_K', true ),
			'net short URL without www' => array( 'https://threads.net/t/DUGwwelEh_K', true ),
			// Invalid URLs.
			'invalid - profile'         => array( 'https://www.threads.com/@zuck', false ),
			'invalid - homepage'        => array( 'https://www.threads.com/', false ),
			'invalid - search'          => array( 'https://www.threads.com/search', false ),
			'invalid - other site'      => array( 'https://www.instagram.com/p/ABC123', false ),
			'invalid - random text'     => array( 'not a url', false ),
		);
	}

	/**
	 * Test that embed SDK script tags are stripped from oEmbed HTML.
	 */
	public function test_filter_embed_html_strips_script() {
		$instance = Meta_Embeds::get_instance();

		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Test data, not actual script output.
		$html = '<blockquote class="text-post-media">Content</blockquote>'
			. "\n" . '<script async src="https://www.threads.com/embed.js"></script>';
		// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

		$filtered = $instance->filter_embed_html( $html );

		$this->assertStringContainsString( '<blockquote', $filtered );
		$this->assertStringNotContainsString( '<script', $filtered );
		$this->assertStringNotContainsString( 'embed.js', $filtered );
	}

	/**
	 * Test that non-matching script tags are preserved in oEmbed HTML.
	 */
	public function test_filter_embed_html_preserves_other_scripts() {
		$instance = Meta_Embeds::get_instance();

		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Test data, not actual script output.
		$html = '<blockquote>Content</blockquote>'
			. "\n" . '<script src="https://example.com/other.js"></script>';
		// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

		$filtered = $instance->filter_embed_html( $html );

		$this->assertStringContainsString( '<script', $filtered );
		$this->assertStringContainsString( 'other.js', $filtered );
	}

	/**
	 * Test that plugin constants are defined.
	 */
	public function test_constants_defined() {
		$this->assertTrue( defined( 'META_EMBEDS_VERSION' ) );
		$this->assertTrue( defined( 'META_EMBEDS_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'META_EMBEDS_PLUGIN_URL' ) );
		$this->assertSame( '1.0.0', META_EMBEDS_VERSION );
	}
}
