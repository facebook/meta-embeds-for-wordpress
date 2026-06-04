# Meta Embeds for WordPress

Embed [Threads](https://www.threads.com) and [Instagram](https://www.instagram.com) content in your WordPress site. Simply paste a URL and get a rich embed — no access tokens or configuration required.

## Features

- **Threads Embeds**: Paste a Threads post URL and get a rich, interactive embed.
- **Instagram Embeds**: Paste an Instagram image, video, or carousel post URL and get a rich, interactive embed.
- **Block Editor Support**: Dedicated "Threads" and "Instagram" embed blocks with a live preview.
- **Classic Editor Support**: Auto-embeds Threads and Instagram URLs via WordPress oEmbed.
- **Lightweight**: No external dependencies, no settings page, no bloat.
- **Tokenless**: Uses Meta's tokenless oEmbed APIs for instant, frictionless embeds.

## Supported URL Formats

**Threads:**

- Post Permalink: `https://www.threads.com/@{username}/post/{media-shortcode}/`
- Shorthand Post URL: `https://www.threads.com/t/{media-shortcode}/`

**Instagram:**

- Image and Carousel Posts: `https://www.instagram.com/p/{media-shortcode}/`
- Reels: `https://www.instagram.com/reel/{media-shortcode}/`
- Profiles: `https://www.instagram.com/{username}/`

## Installation

Requires WordPress 5.9+ and PHP 7.4+.

### From WordPress.org (Recommended)

1. Go to **Plugins > Add New** in your WordPress admin and search for "Meta Embeds".
2. Click **Install Now**, then **Activate**.

### Manual Installation

1. Download the latest release .zip file from [GitHub Releases](https://github.com/facebook/meta-embeds-for-wordpress/releases).
2. In WordPress admin, go to **Plugins > Add New > Upload Plugin**.
3. Upload the .zip file and click **Install Now**.
4. Activate the plugin through the **Plugins** menu.

That's it — no configuration needed.

## Usage

Paste a Threads or Instagram URL on its own line in the editor:

```
https://www.threads.com/@threads/post/DWjTI0cgH5O
```

```
https://www.instagram.com/p/fA9uwTtkSN/
```

The plugin handles the rest.

**Threads:** Both `threads.com` and legacy `threads.net` URLs are supported, including `/t/` short links (e.g. `threads.com/t/{media-shortcode}`).

**Instagram:** Post URLs (`instagram.com/p/{media-shortcode}`), reel URLs (`instagram.com/reel/{media-shortcode}`), and profile URLs (`instagram.com/{username}`) are supported.

## Coming Soon

- Facebook Post Embeds

## Privacy

This plugin registers Meta's oEmbed API endpoints as providers in WordPress. When a Threads or Instagram URL is embedded:

- WordPress makes a server-side request to `graph.threads.com/oembed` (Threads) or `graph.facebook.com/v25.0/instagram_oembed` (Instagram) to fetch the embed HTML.
- The embed HTML includes a script tag that loads `threads.com/embed.js` or `instagram.com/embed.js` on the frontend to render the embed.
- No user data is collected or stored by this plugin.
- Frontend embed rendering is subject to [Meta's Privacy Policy](https://www.facebook.com/privacy/policy/).
- Access to and use of this plugin is subject to [Meta Platform Terms](https://developers.facebook.com/terms/dfc_platform_terms/), [Developer Policies](https://developers.facebook.com/devpolicy/), and [Meta's Community Standards](https://transparency.meta.com/policies/community-standards/), together with all other applicable terms and policies.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

This project is licensed under the [GNU General Public License v2.0 or later](LICENSE).
