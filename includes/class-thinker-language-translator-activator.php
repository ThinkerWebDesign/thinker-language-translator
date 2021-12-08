<?php
/**
 * Fires during plugin activation.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 */

/**
 * Defines all code necessary to run during plugin activation.
 *
 * Adds named option/value pairs to the options database.
 * Then caches the HTML output to database if caches is enabled.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator_Activator {

	/**
	 * Runs the plugin activation functionality.
	 *
	 * Adds named option/value pairs to the options database table on activation.
	 * Then caches the HTML output if the cache setting is enabled.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		add_option( 'thinker_translator_db_version', THINKER_TRANSLATOR_VERSION );
		add_option( 'thinker_translator_cache_wp_footer', '' );
		add_option( 'thinker_translator_cache_shortcode', '' );

		$plugin  = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		if ( $options['thinker_translator_cache'] ) {

			$plugin_name  = $plugin->get_plugin_name();
			$version      = $plugin->get_version();
			$plugin_admin = new Thinker_Language_Translator_Admin( $plugin_name, $version );
			$plugin_admin->thinker_translator_cache_output();

		}

	}

}
