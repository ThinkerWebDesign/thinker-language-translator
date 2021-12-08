<?php
/**
 * Contains the internationalization functionality.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 */

/**
 * Defines the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'thinker-language-translator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
