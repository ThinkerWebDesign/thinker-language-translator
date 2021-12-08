<?php
/**
 * Fires during plugin deactivation.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 */

/**
 * Defines all code necessary to run during plugin deactivation.
 *
 * Deletes temporary options from database.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator_Deactivator {

	/**
	 * Runs the plugin deactivation functionality.
	 *
	 * Deletes temporary options from database on deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Deletes temporary options from database on deactivation.
		delete_option( 'thinker_translator_db_version' );
		delete_option( 'thinker_translator_cache_wp_footer' );
		delete_option( 'thinker_translator_cache_shortcode' );

	}

}
