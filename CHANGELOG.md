# Changelog

All notable changes to this project will be documented in this file.

## 1.2.2

* Trimmed short description to meet the 150-character WordPress.org limit.
* Reduced tags to the 5-tag WordPress.org maximum.

## 1.2.1

* Fixed Instagram provider test to match `instagram_oembed` specifically instead of the ambiguous `graph.facebook.com`.
* Fixed duplicate embed script removal when multiple providers use the same SDK (e.g. Facebook posts and videos).
* Tightened embed script stripping regex to only allow URL fragments or query strings after the base SDK URL.
* Updated Facebook oEmbed endpoints to use versioned API paths (`v25.0`).
* Fixed Facebook embeds not rendering on published pages by enqueuing the SDK with the required `#xfbml=1` fragment.

## 1.2.0

* Added Facebook oEmbed provider registration for post and reel URLs.
* Added Block Editor embed variation with a dedicated Facebook icon.
* Added automatic connect.facebook.net SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides a Facebook endpoint.

## 1.1.0

* Added Instagram oEmbed provider registration for post, reel, and profile URLs.
* Added Block Editor embed variation with a dedicated Instagram icon.
* Added automatic instagram.com/embed.js SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides an Instagram endpoint.

## 1.0.0

* Initial release.
* Added Threads oEmbed provider registration for threads.com and threads.net URLs.
* Added Block Editor embed variation with a dedicated Threads icon.
* Added automatic threads.com/embed.js SDK loading with deferred strategy.
* Added core deduplication to skip registration when WordPress already provides a Threads endpoint.
