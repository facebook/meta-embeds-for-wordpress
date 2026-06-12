=== Meta Embeds ===
Contributors: facebook
Tags: threads, instagram, facebook, embed, meta
Requires at least: 5.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.2.2
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed Threads, Instagram, and Facebook content in your WordPress site. Paste a URL and get a rich embed — no access tokens required.

== Description ==

**Meta Embeds** is the official WordPress plugin for embedding social media content from Meta Platforms. Paste a Threads, Instagram, or Facebook URL into the WordPress editor and it automatically renders a rich preview of the media content.

**Zero configuration required.** No app creation, no access tokens, no API keys. Install the plugin and it just works.

= Features =

* **Threads Embeds**: Paste a Threads post URL and get a rich, interactive embed.
* **Instagram Embeds**: Paste an Instagram image, video, or carousel post URL and get a rich, interactive embed.
* **Facebook Embeds**: Paste a Facebook post or reel URL and get a rich, interactive embed.
* **Block Editor Support**: Dedicated "Threads", "Instagram", and "Facebook" embed blocks with a live preview.
* **Classic Editor Support**: Auto-embeds Threads, Instagram, and Facebook URLs via WordPress oEmbed.
* **Lightweight**: No external dependencies, no settings page, no bloat.
* **Tokenless**: Uses Meta's tokenless oEmbed APIs for instant, frictionless embeds.

= Supported URL Formats =

**Threads:**

* Post Permalink: `https://www.threads.com/@{username}/post/{media-shortcode}/`
* Shorthand Post URL: `https://www.threads.com/t/{media-shortcode}/`

**Instagram:**

* Image and Carousel Posts: `https://www.instagram.com/p/{media-shortcode}/`
* Reels: `https://www.instagram.com/reel/{media-shortcode}/`
* Profiles: `https://www.instagram.com/{username}/`

**Facebook:**

* Posts: `https://www.facebook.com/{username}/posts/{post-id}/`
* Reels: `https://www.facebook.com/reel/{reel-id}/`

== Installation ==

1. Go to **Plugins > Add New** in your WordPress admin and search for "Meta Embeds", or...
2. Download the latest release .zip file from [GitHub](https://github.com/facebook/meta-embeds-for-wordpress/releases) and upload it via **Plugins > Add New > Upload Plugin**.
3. Activate the plugin through the 'Plugins' menu.
4. Start embedding Threads, Instagram, or Facebook content into your website.

That's it — no configuration needed.

== Frequently Asked Questions ==

= Do I need a Meta developer account or access token? =

No. This plugin uses Meta's tokenless oEmbed APIs. No app creation, access tokens, or API configuration is required.

= Which Threads URLs are supported? =

The plugin supports all public Threads post URLs in the format `threads.com/@{username}/post/{media-shortcode}` or `threads.com/t/{media-shortcode}`. Legacy `threads.net` URLs are also supported.

= Which Instagram URLs are supported? =

The plugin supports public Instagram post URLs (`instagram.com/p/{media-shortcode}`), reel URLs (`instagram.com/reel/{media-shortcode}`), and profile URLs (`instagram.com/{username}`). Stories are not supported.

= Which Facebook URLs are supported? =

The plugin supports public Facebook post URLs (`facebook.com/{username}/posts/{post-id}`) and reel URLs (`facebook.com/reel/{reel-id}`). Stories are not supported.

= Does this work with the Classic Editor? =

Yes. Paste a Threads, Instagram, or Facebook URL on its own line in the Classic Editor and it will auto-embed, similar to YouTube or Twitter links.

= Will this plugin support Facebook embeds? =

Yes. Facebook post and video embed support is included as of version 1.2.0.

= How is this different from the WordPress Core Threads support? =

WordPress Core is considering adding Threads as a built-in oEmbed provider in a future release. This plugin provides that functionality today, supports older WordPress versions, and includes Facebook and Instagram embeds.

= What happens when WordPress Core adds built-in Threads support? =

The plugin automatically detects if your WordPress version already includes a Threads oEmbed provider and skips duplicate registration. You can safely keep the plugin active for its additional features (dedicated embed blocks, optimized embed script loading, Instagram and Facebook support) or deactivate it if Core support is sufficient for your needs.

== Changelog ==

= 1.2.2 =
* Trimmed short description to meet the 150-character WordPress.org limit.
* Reduced tags to the 5-tag WordPress.org maximum.

= 1.2.1 =
* Fixed Instagram provider test to match `instagram_oembed` specifically instead of the ambiguous `graph.facebook.com`.
* Fixed duplicate embed script removal when multiple providers use the same SDK (e.g. Facebook posts and videos).
* Tightened embed script stripping regex to only allow URL fragments or query strings after the base SDK URL.
* Updated Facebook oEmbed endpoints to use versioned API paths (`v25.0`).
* Fixed Facebook embeds not rendering on published pages by enqueuing the SDK with the required `#xfbml=1` fragment.

= 1.2.0 =
* Added Facebook oEmbed provider registration for post and reel URLs.
* Added Block Editor embed variation with a dedicated Facebook icon.
* Added automatic connect.facebook.net SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides a Facebook endpoint.

= 1.1.0 =
* Added Instagram oEmbed provider registration for post, reel, and profile URLs.
* Added Block Editor embed variation with a dedicated Instagram icon.
* Added automatic instagram.com/embed.js SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides an Instagram endpoint.

= 1.0.0 =
* Initial release.
* Added Threads oEmbed provider registration for threads.com and threads.net URLs.
* Added Block Editor embed variation with a dedicated Threads icon.
* Added automatic threads.com/embed.js SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides a Threads endpoint.

== Upgrade Notice ==

= 1.2.0 =
Adds Facebook embed support — paste Facebook post and reel URLs for rich embeds.

= 1.1.0 =
Adds Instagram embed support — paste Instagram post, reel, and profile URLs for rich embeds.

= 1.0.0 =
Initial release with Threads embed support.

== Privacy ==

This plugin registers Meta's oEmbed API endpoints as providers in WordPress. When a Threads, Instagram, or Facebook URL is embedded:

* WordPress makes a server-side request to `graph.threads.com/oembed` (Threads), `graph.facebook.com/v25.0/instagram_oembed` (Instagram), `graph.facebook.com/v25.0/oembed_post` (Facebook posts), or `graph.facebook.com/v25.0/oembed_video` (Facebook videos) to fetch the embed HTML.
* The embed HTML includes a script tag that loads `threads.com/embed.js`, `instagram.com/embed.js`, or `connect.facebook.net/en_US/sdk.js` on the frontend to render the embed.
* No user data is collected or stored by this plugin.
* Frontend embed rendering is subject to [Meta's Privacy Policy](https://www.facebook.com/privacy/policy/).
* Access to and use of this plugin is subject to [Meta Platform Terms](https://developers.facebook.com/terms/dfc_platform_terms/), [Developer Policies](https://developers.facebook.com/devpolicy/), and [Meta's Community Standards](https://transparency.meta.com/policies/community-standards/), together with all other applicable terms and policies.
