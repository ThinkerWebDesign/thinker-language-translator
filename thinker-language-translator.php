<?php
/**
 * Thinker Language Translator
 *
 * @link              http://www.thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since             1.0.0
 * @package           Thinker_Language_Translator
 * @author            ThinkerWebDesign
 * @license           GPLv3
 *
 * @wordpress-plugin
 * Plugin Name:       Language Translator
 * Plugin URI:        http://www.thinkerwebdesign.com/thinker-language-translator-plugin/
 * Description:       Add a highly customizable language translator to your website.
 * Version:           1.0.2
 * Author:            ThinkerWebDesign
 * Author URI:        http://www.thinkerwebdesign.com
 * Text Domain:       thinker-language-translator
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details:
http://www.gnu.org/licenses/gpl-3.0.txt

CREDITS:
Thanks To:
Google Translate https://translate.google.com
Wikipedia's flag images https://wikipedia.org
SVG flags by lipis https://github.com/lipis/flag-icon-css
Font Awesome http://fontawesome.io https://github.com/FortAwesome/Font-Awesome
Modified JavaScript for Google Translation via the flag icons from
Google Language Translator plugin by Rob Myrick
https://wordpress.org/plugins/google-language-translator/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Maintains the current plugin version.
 *
 * @since    1.0.0
 */
define( 'THINKER_TRANSLATOR_VERSION', '1.0.2' );

/**
 * Requires the core plugin class file.
 *
 * Defines internationalization, admin-specific and public-facing hooks.
 *
 * @since    1.0.0
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-thinker-language-translator.php';

/**
 * Runs the plugin activation code.
 *
 * Documented in includes/class-thinker-language-translator-activator.php
 *
 * @since    1.0.0
 */
function activate_thinker_language_translator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thinker-language-translator-activator.php';
	Thinker_Language_Translator_Activator::activate();
}

/**
 * Runs the plugin deactivation code.
 *
 * Documented in includes/class-thinker-language-translator-deactivator.php
 *
 * @since    1.0.0
 */
function deactivate_thinker_language_translator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thinker-language-translator-deactivator.php';
	Thinker_Language_Translator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_thinker_language_translator' );
register_deactivation_hook( __FILE__, 'deactivate_thinker_language_translator' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_thinker_language_translator() {

	$plugin = new Thinker_Language_Translator();
	$plugin->run();

}
run_thinker_language_translator();
