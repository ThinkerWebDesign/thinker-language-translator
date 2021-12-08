<?php
/**
 * Fires when the plugin is uninstalled.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Options to delete upon uninstall.
$thinker_translator_delete_options = array(
	'thinker_translator_db_version',
	'thinker_translator_cache_wp_footer',
	'thinker_translator_cache_shortcode',
	'thinker_translator_settings',
);

foreach ( $thinker_translator_delete_options as $option_name ) {

	// Deletes site options.
	delete_option( $option_name );

	// Deletes site options in Multisite.
	delete_site_option( $option_name );

}
