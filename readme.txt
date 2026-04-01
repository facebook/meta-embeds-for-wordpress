=== Meta Embeds ===
Contributors: facebook
Tags: threads, embed, oembed, meta, social
Requires at least: 5.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed Threads content in your WordPress site. Simply paste a Threads URL and get a rich embed — no access tokens or configuration required.

== Description ==

**Meta Embeds** is the official WordPress plugin for embedding content from Meta platforms. Paste a Threads post URL into the WordPress editor and it automatically renders a rich embed — just like YouTube or Twitter embeds work today.

**Zero configuration required.** No app creation, no access tokens, no API keys. Install the plugin and it just works.

= Features =

* **Threads embeds** — Paste a Threads post URL and get a rich, interactive embed
* **Block Editor support** — Dedicated "Threads" embed block with live preview
* **Classic Editor support** — Auto-embeds Threads URLs via WordPress oEmbed
* **Lightweight** — No external dependencies, no settings page, no bloat
* **Tokenless** — Uses Meta's tokenless oEmbed API for instant, frictionless embeds

= Supported URL Formats =

* `https://www.threads.com/@username/post/POST_ID`
* `https://threads.com/@username/post/POST_ID`
* `https://www.threads.com/t/POST_ID`
* `https://www.threads.net/@username/post/POST_ID` (legacy)
* `https://www.threads.net/t/POST_ID` (legacy)

= Coming Soon =

* Facebook post embeds
* Instagram post embeds

== Installation ==

1. Go to **Plugins > Add New** in your WordPress admin and search for "Meta Embeds", or
2. Download the latest release zip from [GitHub](https://github.com/facebook/meta-embeds-for-wordpress/releases) and upload it via **Plugins > Add New > Upload Plugin**
3. Activate the plugin through the 'Plugins' menu
4. Start pasting Threads URLs into your posts and pages

That's it — no configuration needed.

== Frequently Asked Questions ==

= Do I need a Meta developer account or access token? =

No. This plugin uses Meta's tokenless oEmbed API. No app creation, access tokens, or API configuration is required.

= Which Threads URLs are supported? =

The plugin supports all public Threads post URLs in the format `threads.com/@username/post/ID` or `threads.com/t/ID`. Legacy `threads.net` URLs are also supported.

= Does this work with the Classic Editor? =

Yes. Paste a Threads URL on its own line in the Classic Editor and it will auto-embed, just like YouTube or Twitter links.

= Will this plugin support Facebook and Instagram embeds? =

Yes — Facebook and Instagram embed support is planned for a future release.

= How is this different from the WordPress Core Threads support? =

WordPress Core is considering adding Threads as a built-in oEmbed provider in a future release. This plugin provides that functionality today, supports older WordPress versions, and will expand to include Facebook and Instagram embeds.

= What happens when WordPress Core adds built-in Threads support? =

The plugin automatically detects if your WordPress version already includes a Threads oEmbed provider and skips duplicate registration. You can safely keep the plugin active for its additional features (dedicated Threads embed block, optimized embed script loading) or deactivate it if Core support is sufficient for your needs.

== Changelog ==

= 1.0.0 =
* Initial release
* Threads oEmbed provider registration
* Block Editor embed variation for Threads
* Automatic embed.js SDK loading

== Upgrade Notice ==

= 1.0.0 =
Initial release with Threads embed support.

== Privacy ==

This plugin registers Meta's oEmbed API endpoint as a provider in WordPress. When a Threads URL is embedded:

* WordPress makes a server-side request to `graph.threads.com/oembed` to fetch the embed HTML.
* The embed HTML includes a script tag that loads `threads.com/embed.js` on the frontend to render the embed.
* No user data is collected or stored by this plugin.
* Frontend embed rendering is subject to [Meta's Privacy Policy](https://www.facebook.com/privacy/policy/).
