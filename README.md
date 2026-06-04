# Meta Embeds for WordPress

Embed [Threads](https://www.threads.com) and [Instagram](https://www.instagram.com) content in your WordPress site. Paste a URL into the editor and get a rich embed automatically, powered by Meta's tokenless oEmbed APIs. No access tokens or configuration required.

## Features

- Paste a Threads post URL and get a rich, interactive embed
- Paste an Instagram post or Reel URL and get a rich, interactive embed
- Dedicated "Threads Embed" and "Instagram Embed" blocks in the Block Editor with live preview
- Classic Editor support: paste a URL on its own line and it auto-embeds
- No access tokens, API keys, or settings pages needed
- Lightweight with no external dependencies

## Installation

Requires WordPress 5.9+ and PHP 7.4+.

### From WordPress.org (Recommended)

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "Meta Embeds"
3. Click **Install Now**, then **Activate**

### Manual Installation

1. Download the latest release zip from [GitHub Releases](https://github.com/facebook/meta-embeds-for-wordpress/releases)
2. In WordPress admin, go to **Plugins > Add New > Upload Plugin**
3. Upload the zip file and click **Install Now**
4. Activate the plugin through the **Plugins** menu

## Usage

Paste a Threads or Instagram URL on its own line in the editor:

```
https://www.threads.com/@threads/post/DWjTI0cgH5O
```

```
https://www.instagram.com/p/fA9uwTtkSN/
```

The plugin handles the rest.

**Threads:** Both `threads.com` and legacy `threads.net` URLs are supported, including `/t/` short links (e.g. `threads.com/t/POST_ID`).

**Instagram:** Post URLs (`instagram.com/p/SHORTCODE`) and Reel URLs (`instagram.com/reel/SHORTCODE`) are supported.

## Privacy

When a Threads or Instagram URL is embedded, WordPress makes a server-side request to Meta's oEmbed API (`graph.threads.com/oembed` for Threads, `graph.facebook.com/v25.0/instagram_oembed` for Instagram) to fetch the embed HTML. The embed loads `threads.com/embed.js` or `instagram.com/embed.js` on the frontend for rendering. No user data is collected or stored by this plugin. Frontend embed rendering is subject to [Meta's Privacy Policy](https://www.facebook.com/privacy/policy/).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

This project is licensed under the [GNU General Public License v2.0 or later](LICENSE).
