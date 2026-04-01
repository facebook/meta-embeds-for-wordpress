/**
 * Meta Embeds — Block Editor Variations
 *
 * Registers embed block variations for Meta platforms.
 *
 * @param {Object} wp
 * @package
 * @copyright 2026 Meta Platforms, Inc. and affiliates
 * @license GPL-2.0-or-later
 */

(function (wp) {
	const { registerBlockVariation } = wp.blocks;
	const { __ } = wp.i18n;
	const { createElement } = wp.element;

	/**
	 * Threads icon SVG.
	 */
	const ThreadsIcon = createElement(
		'svg',
		{
			xmlns: 'http://www.w3.org/2000/svg',
			viewBox: '0 0 24 24',
		},
		createElement('path', {
			d: 'M16.3 11.3c-.1 0-.2-.1-.2-.1-.1-2.6-1.5-4-3.9-4-1.4 0-2.6.6-3.3 1.7l1.3.9c.5-.8 1.4-1 2-1 .8 0 1.4.2 1.7.7.3.3.5.8.5 1.3-.7-.1-1.4-.2-2.2-.1-2.2.1-3.7 1.4-3.6 3.2 0 .9.5 1.7 1.3 2.2.7.4 1.5.6 2.4.6 1.2-.1 2.1-.5 2.7-1.3.5-.6.8-1.4.9-2.4.6.3 1 .8 1.2 1.3.4.9.4 2.4-.8 3.6-1.1 1.1-2.3 1.5-4.3 1.5-2.1 0-3.8-.7-4.8-2S5.7 14.3 5.7 12c0-2.3.5-4.1 1.5-5.4 1.1-1.3 2.7-2 4.8-2 2.2 0 3.8.7 4.9 2 .5.7.9 1.5 1.2 2.5l1.5-.4c-.3-1.2-.8-2.2-1.5-3.1-1.3-1.7-3.3-2.6-6-2.6-2.6 0-4.7.9-6 2.6C4.9 7.2 4.3 9.3 4.3 12s.6 4.8 1.9 6.4c1.4 1.7 3.4 2.6 6 2.6 2.3 0 4-.6 5.3-2 1.8-1.8 1.7-4 1.1-5.4-.4-.9-1.2-1.7-2.3-2.3zm-4 3.8c-1 .1-2-.4-2-1.3 0-.7.5-1.5 2.1-1.6h.5c.6 0 1.1.1 1.6.2-.2 2.3-1.3 2.7-2.2 2.7z',
		})
	);

	/**
	 * Register Threads embed block variation.
	 */
	registerBlockVariation('core/embed', {
		name: 'threads',
		title: __('Threads Embed', 'meta-embeds'),
		icon: ThreadsIcon,
		description: __('Embed a Threads post.', 'meta-embeds'),
		patterns: [
			/^https?:\/\/(?:www\.)?threads\.(?:com|net)\/(?:@[^/]+\/post\/|t\/).+/i,
		],
		attributes: {
			providerNameSlug: 'threads',
			responsive: true,
		},
	});
})(window.wp);
