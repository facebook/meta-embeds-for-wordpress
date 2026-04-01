<?php
/**
 * Uninstall handler.
 *
 * Fired when the plugin is deleted via the WordPress admin.
 *
 * @package MetaEmbeds
 * @copyright 2026 Meta Platforms, Inc. and affiliates
 * @license GPL-2.0-or-later
 */

// Exit if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// This plugin does not store any options or data.
// Nothing to clean up on uninstall.
