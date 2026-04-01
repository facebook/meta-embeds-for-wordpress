# Meta Embeds for WordPress

Embed [Threads](https://www.threads.com) content in your WordPress site. Paste a Threads URL into the editor and get a rich embed automatically, powered by Meta's [tokenless oEmbed API](https://developers.facebook.com/docs/threads/tools-and-resources/embed-a-threads-post). No access tokens or configuration required.

## Features

- Paste a Threads post URL and get a rich, interactive embed
- Dedicated "Threads Embed" block in the Block Editor with live preview
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

Paste a Threads URL on its own line in the editor:

```
https://www.threads.com/@threads/post/DWjTI0cgH5O
```

The plugin handles the rest.

Both `threads.com` and legacy `threads.net` URLs are supported, including `/t/` short links (e.g. `threads.com/t/POST_ID`).

## Privacy

When a Threads URL is embedded, WordPress makes a server-side request to Meta's oEmbed API (`graph.threads.com/oembed`) to fetch the embed HTML. The embed loads `threads.com/embed.js` on the frontend for rendering. No user data is collected or stored by this plugin. Frontend embed rendering is subject to [Meta's Privacy Policy](https://www.facebook.com/privacy/policy/).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

This project is licensed under the [GNU General Public License v2.0 or later](LICENSE).
