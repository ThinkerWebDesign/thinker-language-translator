<?php
/**
 * Handles the public-facing functionality of the plugin.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/public
 */

/**
 * Contains all public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks to enqueue the public-facing
 * stylesheet and JS. Returns any active translator output. If cache is active,
 * uses cache from database. Otherwise, uses options settings.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/public
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thinker-language-translator-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thinker-language-translator-public.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Retrieves HTML output to be registered with the wp_footer action hook.
	 *
	 * @since    1.0.0
	 */
	public function thinker_translator_wp_footer() {

		$html_output = $this->thinker_language_translator_public();
		echo $html_output['wp_footer_output'];

	}

	/**
	 * Retrieves shortcode output to be registered.
	 *
	 * @since    1.0.0
	 * @return   string     $string     The shortcode HTML output.
	 */
	public function thinker_translator_shortcode() {

		$html_output = $this->thinker_language_translator_public();
		return $html_output['shortcode_output'];

	}

	/**
	 * Adds shortcode to be registered with the init action hook.
	 *
	 * @since    1.0.0
	 */
	public function thinker_translator_register_shortcode() {

		add_shortcode( 'thinker_translator', array( $this, 'thinker_translator_shortcode' ) );

	}

	/**
	 * Handles main public functionality of the plugin.
	 *
	 * Uses cache from database if cache is active.
	 * Otherwise, uses options settings.
	 *
	 * @since    1.0.0
	 * @return   array     $html_output     The shortcode HTML output.
	 */
	public function thinker_language_translator_public() {

		// If not a Google Translated URL.
		if ( isset( $_GET['tltranslated'] ) ) {
			return;
		}

		$plugin  = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		// If cache is active, use cached HTML. Otherwise, use options settings.
		if ( $options['thinker_translator_cache'] ) {

			$wp_footer_output = get_option( 'thinker_translator_cache_wp_footer' );
			$shortcode_output = get_option( 'thinker_translator_cache_shortcode' );

		} else {

			$tlt_count  = 0;
			$tlt_output = '';

			// Floating.
			if ( $options['thinker_translator_floating'] ) {
				$tlt_count++;
				$tlt_output = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Float' );
			}

			// Footer.
			if ( $options['thinker_translator_footer'] ) {
				$tlt_count++;
				$tlt_output = $tlt_output . $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Footer' );
			}

			$wp_footer_output = $tlt_output;

			// Shortcode.
			$tlt_count++;
			$tlt_output       = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Shortcode' );
			$shortcode_output = $tlt_output;

		}

		$html_output = array(
			'wp_footer_output' => $wp_footer_output,
			'shortcode_output' => $shortcode_output,
		);
		return $html_output;

	}

}
