<?php
/**
 * Meta Embeds for WordPress
 *
 * @package     MetaEmbeds
 * @author      Meta Platforms, Inc. and affiliates
 * @copyright   2026 Meta Platforms, Inc. and affiliates
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Meta Embeds
 * Plugin URI:  https://github.com/facebook/meta-embeds-for-wordpress
 * Description: Embed Threads, Instagram, and Facebook content in your WordPress site. Simply paste a URL and get a rich embed — no access tokens or configuration required.
 * Version:     1.2.1
 * Requires at least: 5.9
 * Tested up to:      6.9
 * Requires PHP:      7.4
 * Author:      Meta Platforms, Inc. and affiliates
 * Author URI:  https://developers.facebook.com/
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: meta-embeds
 * Domain Path: /languages
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'META_EMBEDS_VERSION', '1.2.1' );
define( 'META_EMBEDS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'META_EMBEDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once META_EMBEDS_PLUGIN_DIR . 'includes/class-meta-embeds.php';

/**
 * Initialize the plugin.
 *
 * @return Meta_Embeds
 */
function meta_embeds_init() {
	return Meta_Embeds::get_instance();
}

add_action( 'plugins_loaded', 'meta_embeds_init' );
