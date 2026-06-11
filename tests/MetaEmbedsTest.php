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
	 * Test that Instagram oEmbed provider is registered.
	 */
	public function test_instagram_oembed_provider_registered() {
		// Trigger provider registration.
		do_action( 'init' );

		$oembed = _wp_oembed_get_object();

		$found = false;
		foreach ( $oembed->providers as $pattern => $provider_info ) {
			if ( strpos( $provider_info[0], 'graph.facebook.com' ) !== false ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Instagram oEmbed provider should be registered.' );
	}

	/**
	 * Test that Facebook post oEmbed provider is registered.
	 */
	public function test_facebook_post_oembed_provider_registered() {
		// Trigger provider registration.
		do_action( 'init' );

		$oembed = _wp_oembed_get_object();

		$found = false;
		foreach ( $oembed->providers as $pattern => $provider_info ) {
			if ( strpos( $provider_info[0], 'oembed_post' ) !== false ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Facebook post oEmbed provider should be registered.' );
	}

	/**
	 * Test that Facebook video oEmbed provider is registered.
	 */
	public function test_facebook_video_oembed_provider_registered() {
		// Trigger provider registration.
		do_action( 'init' );

		$oembed = _wp_oembed_get_object();

		$found = false;
		foreach ( $oembed->providers as $pattern => $provider_info ) {
			if ( strpos( $provider_info[0], 'oembed_video' ) !== false ) {
				$found = true;
				break;
			}
		}

		$this->assertTrue( $found, 'Facebook video oEmbed provider should be registered.' );
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
	 * Test that Instagram URLs match the registered pattern.
	 *
	 * @dataProvider instagram_url_provider
	 * @param string $url      The URL to test.
	 * @param bool   $expected Whether the URL should match.
	 */
	public function test_instagram_url_matching( $url, $expected ) {
		$patterns = array(
			'#https?://(www\.)?instagram\.com/(p|reel)/[^/]+#i',
			'#https?://(www\.)?instagram\.com/(?!stories/|explore/|accounts/|direct/|tv/|about/|legal/|developer/|api/|static/|nametag/|directory/)([a-zA-Z0-9._]{1,30})/?(\?.*)?$#i',
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
	 * Data provider for Instagram URL tests.
	 */
	public function instagram_url_provider() {
		return array(
			// Post URLs.
			'post with www'                => array( 'https://www.instagram.com/p/fA9uwTtkSN/', true ),
			'post without www'             => array( 'https://instagram.com/p/fA9uwTtkSN/', true ),
			'post without trailing slash'  => array( 'https://www.instagram.com/p/fA9uwTtkSN', true ),
			'post http'                    => array( 'http://www.instagram.com/p/fA9uwTtkSN/', true ),
			// Reel URLs.
			'reel with www'                => array( 'https://www.instagram.com/reel/ABC123/', true ),
			'reel without www'             => array( 'https://instagram.com/reel/ABC123/', true ),
			'reel without trailing slash'  => array( 'https://www.instagram.com/reel/ABC123', true ),
			'reel http'                    => array( 'http://www.instagram.com/reel/ABC123/', true ),
			// Profile URLs.
			'profile with www'             => array( 'https://www.instagram.com/zuck', true ),
			'profile without www'          => array( 'https://instagram.com/zuck', true ),
			'profile with trailing slash'  => array( 'https://www.instagram.com/zuck/', true ),
			'profile http'                 => array( 'http://www.instagram.com/zuck', true ),
			'profile with dots'            => array( 'https://www.instagram.com/some.user', true ),
			'profile with underscores'     => array( 'https://www.instagram.com/some_user', true ),
			'profile with query params'    => array( 'https://www.instagram.com/zuck?hl=en', true ),
			'profile with slash and query' => array( 'https://www.instagram.com/zuck/?utm_source=share', true ),
			// Invalid URLs.
			'invalid - homepage'           => array( 'https://www.instagram.com/', false ),
			'invalid - stories'            => array( 'https://www.instagram.com/stories/zuck/123456', false ),
			'invalid - explore'            => array( 'https://www.instagram.com/explore/', false ),
			'invalid - accounts'           => array( 'https://www.instagram.com/accounts/login/', false ),
			'invalid - direct'             => array( 'https://www.instagram.com/direct/inbox/', false ),
			'invalid - other site'         => array( 'https://www.threads.com/@zuck/post/C123', false ),
			'invalid - random text'        => array( 'not a url', false ),
		);
	}

	/**
	 * Test that Facebook post URLs match the registered pattern.
	 *
	 * @dataProvider facebook_post_url_provider
	 * @param string $url      The URL to test.
	 * @param bool   $expected Whether the URL should match.
	 */
	public function test_facebook_post_url_matching( $url, $expected ) {
		$patterns = array(
			'#https?://(www\.)?facebook\.com/[^/]+/posts/[^/]+#i',
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
	 * Data provider for Facebook post URL tests.
	 */
	public function facebook_post_url_provider() {
		return array(
			// Post URLs.
			'post with www'            => array( 'https://www.facebook.com/kevinloveofficial/posts/pfbid0nWhZeiMVjz', true ),
			'post without www'         => array( 'https://facebook.com/kevinloveofficial/posts/pfbid0nWhZeiMVjz', true ),
			'post with trailing slash' => array( 'https://www.facebook.com/kevinloveofficial/posts/pfbid0nWhZeiMVjz/', true ),
			'post http'                => array( 'http://www.facebook.com/kevinloveofficial/posts/pfbid0nWhZeiMVjz', true ),
			'post numeric id'          => array( 'https://www.facebook.com/123456789/posts/987654321', true ),
			// Invalid URLs.
			'invalid - homepage'       => array( 'https://www.facebook.com/', false ),
			'invalid - profile only'   => array( 'https://www.facebook.com/kevinloveofficial', false ),
			'invalid - reel'           => array( 'https://www.facebook.com/reel/3305054673010377', false ),
			'invalid - other site'     => array( 'https://www.instagram.com/p/ABC123', false ),
			'invalid - random text'    => array( 'not a url', false ),
		);
	}

	/**
	 * Test that Facebook video URLs match the registered pattern.
	 *
	 * @dataProvider facebook_video_url_provider
	 * @param string $url      The URL to test.
	 * @param bool   $expected Whether the URL should match.
	 */
	public function test_facebook_video_url_matching( $url, $expected ) {
		$patterns = array(
			'#https?://(www\.)?facebook\.com/reel/[^/]+#i',
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
	 * Data provider for Facebook video URL tests.
	 */
	public function facebook_video_url_provider() {
		return array(
			// Reel URLs.
			'reel with www'            => array( 'https://www.facebook.com/reel/3305054673010377', true ),
			'reel without www'         => array( 'https://facebook.com/reel/3305054673010377', true ),
			'reel with trailing slash' => array( 'https://www.facebook.com/reel/3305054673010377/', true ),
			'reel http'                => array( 'http://www.facebook.com/reel/3305054673010377', true ),
			// Invalid URLs.
			'invalid - homepage'       => array( 'https://www.facebook.com/', false ),
			'invalid - profile only'   => array( 'https://www.facebook.com/kevinloveofficial', false ),
			'invalid - post'           => array( 'https://www.facebook.com/kevinloveofficial/posts/pfbid0nWhZeiMVjz', false ),
			'invalid - other site'     => array( 'https://www.instagram.com/reel/ABC123', false ),
			'invalid - random text'    => array( 'not a url', false ),
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
	 * Test that Instagram embed SDK script tags are stripped from oEmbed HTML.
	 */
	public function test_filter_embed_html_strips_instagram_script() {
		$instance = Meta_Embeds::get_instance();

		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Test data, not actual script output.
		$html = '<blockquote class="instagram-media">Content</blockquote>'
			. "\n" . '<script async src="https://www.instagram.com/embed.js"></script>';
		// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

		$filtered = $instance->filter_embed_html( $html );

		$this->assertStringContainsString( '<blockquote', $filtered );
		$this->assertStringNotContainsString( '<script', $filtered );
		$this->assertStringNotContainsString( 'embed.js', $filtered );
	}

	/**
	 * Test that Facebook embed SDK script tags are stripped from oEmbed HTML.
	 */
	public function test_filter_embed_html_strips_facebook_script() {
		$instance = Meta_Embeds::get_instance();

		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Test data, not actual script output.
		$html = '<div id="fb-root"></div>'
			. "\n" . '<script async="1" defer="1" crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v25.0"></script>'
			. '<div class="fb-post" data-href="https://www.facebook.com/test/posts/123"></div>';
		// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript

		$filtered = $instance->filter_embed_html( $html );

		$this->assertStringContainsString( 'fb-root', $filtered );
		$this->assertStringContainsString( 'fb-post', $filtered );
		$this->assertStringNotContainsString( '<script', $filtered );
		$this->assertStringNotContainsString( 'sdk.js', $filtered );
	}

	/**
	 * Test that plugin constants are defined.
	 */
	public function test_constants_defined() {
		$this->assertTrue( defined( 'META_EMBEDS_VERSION' ) );
		$this->assertTrue( defined( 'META_EMBEDS_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'META_EMBEDS_PLUGIN_URL' ) );
		$this->assertSame( '1.2.0', META_EMBEDS_VERSION );
	}
}
