<?php
/**
 * Handles the admin-specific functionality of the plugin.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/admin
 */

/**
 * Contains all admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks to enqueue the admin-specific
 * stylesheet and JS. Adds a settings link to the WP Plugins page, registers
 * a settings page and optionally caches the front-end HTML output to database.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/admin
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator_Admin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initializes the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Registers the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thinker-language-translator-admin.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'public', plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/thinker-language-translator-public.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );

	}

	/**
	 * Registers the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thinker-language-translator-admin.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), THINKER_TRANSLATOR_VERSION, true );

	}

	/**
	 * Caches the plugin output.
	 *
	 * Stores the plugin wp-footer and shortcode output in the options table.
	 *
	 * @since 1.0.0
	 */
	public function thinker_translator_cache_output() {

		$plugin           = new Thinker_Language_Translator();
		$options          = $plugin->get_thinker_translator_options();
		$tlt_count        = '';
		$wp_footer_output = '';

		if ( $options['thinker_translator_floating'] ) {
			$tlt_count++;
			$tlt_output       = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Float' );
			$wp_footer_output = $tlt_output;
		}
		if ( $options['thinker_translator_footer'] ) {
			$tlt_count++;
			$tlt_output       = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Footer' );
			$wp_footer_output = $wp_footer_output . $tlt_output;
		}
		update_option( 'thinker_translator_cache_wp_footer', $wp_footer_output );

		$tlt_count++;
		$tlt_output = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Shortcode' );
		update_option( 'thinker_translator_cache_shortcode', $tlt_output );

	}

	/**
	 * Adds a settings link to the plugin on the WP Plugins page.
	 *
	 * @since 1.0.0
	 *
	 * @param  mixed $links The plugin links.
	 * @return mixed        The settings field.
	 */
	public function add_settings_link( $links ) {

		$mylinks = array( '<a href="options-general.php?page=thinker_language_translator">' . __( 'Settings' ) . '</a>' );
		return array_merge( $links, $mylinks );

	}

	/**
	 * Adds the plugin options page.
	 *
	 * @since 1.0.0
	 */
	public function thinker_translator_add_admin_menu() {

		add_options_page( 'Thinker Language Translator', 'Language Translator', 'manage_options', 'thinker_language_translator', array( $this, 'thinker_translator_options_page' ) );

	}

	/**
	 * Handles the plugin options page.
	 *
	 * @since 1.0.0
	 *
	 * @global string $pagenow
	 */
	public function thinker_translator_options_page() {
		global $pagenow;

		if ( 'options-general.php' === $pagenow && ! empty( $_GET['page'] ) && 'thinker_language_translator' === $_GET['page'] ) {

			$plugin                  = new Thinker_Language_Translator();
			$options                 = $plugin->get_thinker_translator_options();
			$tlt_settings_page_class = '';

			if ( $options['thinker_translator_inline'] ) {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_disabled';
			}

			if ( $options['thinker_translator_hover'] ) {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_active';
			} else {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_inactive';
			}

			if ( $options['thinker_translator_hover_bg'] ) {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_bg_settings_active';
			} else {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_bg_settings_inactive';
			}

			if ( $options['thinker_translator_floating'] ) {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_float_settings_active';
			} else {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_float_settings_inactive';
			}

			if ( $options['thinker_translator_footer'] ) {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_footer_settings_active';
			} else {
				$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_footer_settings_inactive';
			}

			$tlt_settings_page_buttons = '
					<p>
						<button type="reset" value="Reset" onclick="location.reload()" class="thinker_translator_settings_form_remove button button-primary" title="Remove all changes since last save">Remove Changes</button>
						<button type="reset" value="Reset" class="thinker_translator_settings_form_clear button button-primary" title="Reset form to default state (does not save)">Clear All Fields</button>
					</p>
				';

			?>
				<style></style>
				<form id="thinker_translator_settings_form" action="options.php" method="post" autocomplete="off">
					<div id="thinker_translator_settings_page" class="thinker_translator_settings_page <?php echo esc_attr( $tlt_settings_page_class ); ?>">
						<div id="thinker_translator_settings" class="thinker_translator_settings">
							<div>
								<h2 class="thinker_translator_settings_top_heading">THINKER LANGUAGE TRANSLATOR SETTINGS:</h2>
							<?php
								settings_fields( 'pluginPage' );
								do_settings_sections( 'pluginPage' );
								submit_button();
								echo $tlt_settings_page_buttons;
							?>
							</div>
						</div>
						<div id="thinker_translator_preview" class="thinker_translator_preview thinker_translator_preview_open">
							<div>
								<div class="thinker_translator_settings_results">
									<div>
										<div class="thinker_translator_example_wrap">
											<div class="thinker_translator_example_title">
												<h2 class="thinker_translator_settings_top_heading margin-bottom-0">PREVIEW:</h2>
												<small>Excludes Locations Settings</small>
											</div>
										<?php
											// Outputs translator preview.
											$tlt_count  = 1;
											$tlt_class  = 'thinkerLangT-Preview';
											$tlt_output = $plugin->thinker_translator_output( $tlt_count, $tlt_class );
											echo $tlt_output;
										?>

										</div>
									</div>
								</div>
								<div class="thinker_translator_settings_no_results">
									<div>
										<h2 class="thinker_translator_settings_top_heading">SAVE SETTINGS TO SEE RESULTS:</h2>
										<?php
										submit_button();
										echo $tlt_settings_page_buttons;
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</form>
				<?php
		}

	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function thinker_translator_settings_init() {

		$plugin  = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		$this->thinker_language_translator_admin();

		register_setting( 'pluginPage', 'thinker_translator_settings', array( $plugin, 'validate_options' ) );

		add_settings_section(
			'thinker_translator_settings_section_1',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading thinker_translator_settings_field_heading_1"><span><h2>1. Basic Settings</h2></label></div>', 'WordPress' ),
			'__return_empty_string',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_text_color',
			__( '<div class="thinker_translator_settings_field"><span><label>Main Color</label></span></div>', 'WordPress' ),
			'thinker_translator_text_color_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_lang',
			__( '<div class="thinker_translator_settings_field"><span><label>Languages</label></span></div>', 'WordPress' ),
			'thinker_translator_lang_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_display',
			__( '<div class="thinker_translator_settings_field"><span><label>Flags, Text or Both</label></span></div>', 'WordPress' ),
			'thinker_translator_display_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_more',
			__( '<div class="thinker_translator_settings_field"><span><label>More Languages Button</label></span></div>', 'WordPress' ),
			'thinker_translator_more_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_more_wide',
			__( '<div id="thinker_translator_more_wide_settings_desc" class="thinker_translator_settings_field"><span><label>Wide More Button</label></span></div>', 'WordPress' ),
			'thinker_translator_more_wide_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_inline',
			__( '<div class="thinker_translator_settings_field"><span><label>Inline Display</label></span></div>', 'WordPress' ),
			'thinker_translator_inline_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_cache',
			__( '<div class="thinker_translator_settings_field"><span><label>Cache Output</label></span></div>', 'WordPress' ),
			'thinker_translator_cache_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_png',
			__( '<div class="thinker_translator_settings_field"><span><label>PNG Images Only</label></span></div>', 'WordPress' ),
			'thinker_translator_png_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_html',
			__( '<div class="thinker_translator_settings_field"><span><label>HTML Links Method</label></span></div>', 'WordPress' ),
			'thinker_translator_html_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);

		add_settings_section(
			'thinker_translator_settings_section_2',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading"><span><h2>2. Hover/Click Icon</h2></label></div>', 'WordPress' ),
			'__return_empty_string',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_hover',
			__( '<div id="thinker_translator_hover_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Use Icon Method</label></span></div>', 'WordPress' ),
			'thinker_translator_hover_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_size',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span id="thinker_translator_height_settings_desc"><label>Icon Size</label></span></div>', 'WordPress' ),
			'thinker_translator_icon_size_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_type',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon Type</label></span></div>', 'WordPress' ),
			'thinker_translator_icon_type_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_bg_image',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon Image URL</label></span></div>', 'WordPress' ),
			'thinker_translator_icon_bg_image_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon BG Color</label></span></div>', 'WordPress' ),
			'thinker_translator_icon_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg',
			__( '<div id="thinker_translator_hover_bg_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent tlt-hover-dep"><span><label>Icon Container</label></span></div>', 'WordPress' ),
			'thinker_translator_hover_bg_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_height',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_height_settings_desc"><label>Height</label></span></div>', 'WordPress' ),
			'thinker_translator_height_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_width',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_width_settings_desc"><label>Width</label></span></div>', 'WordPress' ),
			'thinker_translator_width_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_border_radius',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_border_radius_settings_desc"><label>Border Radius</label></span></div>', 'WordPress' ),
			'thinker_translator_border_radius_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_nation',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span><label>Nation Flag BG</label></label></div>', 'WordPress' ),
			'thinker_translator_nation_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg_image',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_hover_bg_image_settings_desc"><label>BG Image</label></span></div>', 'WordPress' ),
			'thinker_translator_hover_bg_image_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_hover_bg_color_settings_desc"><label>BG Color</label></span></div>', 'WordPress' ),
			'thinker_translator_hover_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);

		add_settings_section(
			'thinker_translator_settings_section_3',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading"><span><h2>3. Locations</h2></label></div>', 'WordPress' ),
			'__return_empty_string',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_shortcode_padding',
			__( '<div class="thinker_translator_settings_field"><span id="thinker_translator_shortcode_padding_settings_desc"><label>Shortcode Padding</label></span></div>', 'WordPress' ),
			'thinker_translator_shortcode_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_floating',
			__( '<div id="thinker_translator_float_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Floating Translator</label></span></div>', 'WordPress' ),
			'thinker_translator_floating_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_padding',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_padding_settings_desc"><label>Padding</label></label></span></div>', 'WordPress' ),
			'thinker_translator_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_top',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_top_settings_desc"><label>Top Alignment</label></span></div>', 'WordPress' ),
			'thinker_translator_top_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_bottom',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_bottom_settings_desc"><label>Bottom Alignment</label></span></div>', 'WordPress' ),
			'thinker_translator_bottom_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_left',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_left_settings_desc"><label>Left Alignment</label></span></div>', 'WordPress' ),
			'thinker_translator_left_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_right',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_right_settings_desc"><label>Right Alignment</label></label></span></div>', 'WordPress' ),
			'thinker_translator_right_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer',
			__( '<div id="thinker_translator_footer_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Below Footer Translator</label></span></div>', 'WordPress' ),
			'thinker_translator_footer_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_padding',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_padding_settings_desc"><label>Footer Padding</label></span></div>', 'WordPress' ),
			'thinker_translator_footer_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_align',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_align_settings_desc"><label>Footer Align</label></span></div>', 'WordPress' ),
			'thinker_translator_footer_align_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_bg_color_settings_desc"><label>Footer BG Color</label></span></div>', 'WordPress' ),
			'thinker_translator_footer_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_bg_style',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_bg_style_settings_desc"><label>Footer BG Style</label></span></div>', 'WordPress' ),
			'thinker_translator_footer_bg_style_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);

	}

	/**
	 * Handles the main admin functionality of the plugin.
	 *
	 * Handles the settings page and caches the front-end HTML output.
	 *
	 * @since 1.0.0
	 *
	 * @global string $pagenow
	 */
	public function thinker_language_translator_admin() {
		global $pagenow;

		$plugin  = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		// Caches front-end HTML output if on plugin settings page and caching is on.
		if ( 'options-general.php' === $pagenow && ! empty( $_GET['page'] ) && 'thinker_language_translator' === $_GET['page'] ) {

			if ( $options['thinker_translator_cache'] ) {

				$this->thinker_translator_cache_output();

			}
		}

		require_once 'thinker-language-translator-admin-settings-callbacks.php';

	}

}
