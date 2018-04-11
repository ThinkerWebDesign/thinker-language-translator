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
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initializes the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Registers the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thinker-language-translator-admin.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'public', plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/thinker-language-translator-public.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-color-picker' );

	}

	/**
	 * Registers the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thinker-language-translator-admin.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

	}

	/**
	 * Caches the plugin output.
	 *
	 * Stores the plugin wp-footer and shortcode output in the options table.
	 *
	 * @since    1.0.0
	 */
	public function thinker_translator_cache_output() {

		$plugin = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();
		$tlt_count = '';
		$wp_footer_output = '';

		if ( $options['thinker_translator_floating'] != '' ) {
			$tlt_count = $tlt_count+1;
			$tlt_output = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Float' );
			$wp_footer_output = $tlt_output;
		}
		if ( $options['thinker_translator_footer'] != '' ) {
			$tlt_count = $tlt_count+1;
			$tlt_output = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Footer' );
			$wp_footer_output = $wp_footer_output . $tlt_output;
		}
		update_option( 'thinker_translator_cache_wp_footer', $wp_footer_output );

		$tlt_count = $tlt_count+1;
		$tlt_output = $plugin->thinker_translator_output( $tlt_count, 'thinkerLangT-Shortcode' );
		update_option( 'thinker_translator_cache_shortcode', $tlt_output );

	}

	/**
	 * Adds a settings link to the plugin on the WP Plugins page.
	 *
	 * @since    1.0.0
	 * @param    mixed     $links
	 * @return   mixed     The settings field.
	 */
	public function add_settings_link( $links ) {

		$mylinks = array( '<a href="options-general.php?page=thinker_language_translator">' . __( 'Settings' ) . '</a>', );
		return array_merge( $links, $mylinks );

	}

	/**
	 * Adds the plugin options page.
	 *
	 * @since    1.0.0
	 */
	public function thinker_translator_add_admin_menu() {

		add_options_page( 'Thinker Language Translator', 'Language Translator', 'manage_options', 'thinker_language_translator', array( $this, 'thinker_translator_options_page' ) );

	}

	/**
	 * Handles the plugin options page.
	 *
	 * @since    1.0.0
	 *
	 * @global   string    $pagenow
	 */
	public function thinker_translator_options_page() {

			global $pagenow;
			if ( $pagenow == 'options-general.php' && $_GET['page'] == 'thinker_language_translator' ) {

				$plugin = new Thinker_Language_Translator();
				$options = $plugin->get_thinker_translator_options();
				$tlt_settings_page_class = '';

				if ( $options['thinker_translator_inline'] != '' ) {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_disabled';
				}

				if ( $options['thinker_translator_hover'] != '' ) {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_active';
				} else {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_settings_inactive';
				}

				if ( $options['thinker_translator_hover_bg'] != '' ) {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_bg_settings_active';
				} else {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_hover_bg_settings_inactive';
				}

				if ( $options['thinker_translator_floating'] != '' ) {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_float_settings_active';
				} else {
					$tlt_settings_page_class = $tlt_settings_page_class . ' thinker_translator_float_settings_inactive';
				}

				if ( $options['thinker_translator_footer'] != '' ) {
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
					<div id="thinker_translator_settings_page" class="thinker_translator_settings_page <?php echo $tlt_settings_page_class; ?>">
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
												$tlt_count = 1;
												$tlt_class = 'thinkerLangT-Preview';
												$tlt_output = $plugin->thinker_translator_output( $tlt_count, $tlt_class );
												echo $tlt_output;
											?>

										</div>
									</div>
								</div>
								<div class="thinker_translator_settings_no_results">
									<div>
										<h2 class="thinker_translator_settings_top_heading">SAVE SETTINGS TO SEE RESULTS:</h2>
										<?php submit_button(); echo $tlt_settings_page_buttons; ?>
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
	 * @since    1.0.0
	 */
	public function thinker_translator_settings_init() {

		$plugin = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		$this->thinker_language_translator_admin();

		register_setting( 'pluginPage', 'thinker_translator_settings', array( $plugin, 'validate_options' ) );

		add_settings_section(
			'thinker_translator_settings_section_1',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading thinker_translator_settings_field_heading_1"><span><h2>1. Basic Settings</h2></label></div>', 'wordpress' ),
			'thinker_translator_settings_section_1_callback',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_text_color',
			__( '<div class="thinker_translator_settings_field"><span><label>Main Color</label></span></div>', 'wordpress' ),
			'thinker_translator_text_color_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_lang',
			__( '<div class="thinker_translator_settings_field"><span><label>Languages</label></span></div>', 'wordpress' ),
			'thinker_translator_lang_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_display',
			__( '<div class="thinker_translator_settings_field"><span><label>Flags, Text or Both</label></span></div>', 'wordpress' ),
			'thinker_translator_display_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_more',
			__( '<div class="thinker_translator_settings_field"><span><label>More Languages Button</label></span></div>', 'wordpress' ),
			'thinker_translator_more_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_more_wide',
			__( '<div id="thinker_translator_more_wide_settings_desc" class="thinker_translator_settings_field"><span><label>Wide More Button</label></span></div>', 'wordpress' ),
			'thinker_translator_more_wide_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_inline',
			__( '<div class="thinker_translator_settings_field"><span><label>Inline Display</label></span></div>', 'wordpress' ),
			'thinker_translator_inline_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_cache',
			__( '<div class="thinker_translator_settings_field"><span><label>Cache Output</label></span></div>', 'wordpress' ),
			'thinker_translator_cache_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_png',
			__( '<div class="thinker_translator_settings_field"><span><label>PNG Images Only</label></span></div>', 'wordpress' ),
			'thinker_translator_png_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);
		add_settings_field(
			'thinker_translator_html',
			__( '<div class="thinker_translator_settings_field"><span><label>HTML Links Method</label></span></div>', 'wordpress' ),
			'thinker_translator_html_render',
			'pluginPage',
			'thinker_translator_settings_section_1',
			$options
		);

		add_settings_section(
			'thinker_translator_settings_section_2',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading"><span><h2>2. Hover/Click Icon</h2></label></div>', 'wordpress' ),
			'thinker_translator_settings_section_2_callback',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_hover',
			__( '<div id="thinker_translator_hover_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Use Icon Method</label></span></div>', 'wordpress' ),
			'thinker_translator_hover_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_size',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span id="thinker_translator_height_settings_desc"><label>Icon Size</label></span></div>', 'wordpress' ),
			'thinker_translator_icon_size_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_type',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon Type</label></span></div>', 'wordpress' ),
			'thinker_translator_icon_type_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_bg_image',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon Image URL</label></span></div>', 'wordpress' ),
			'thinker_translator_icon_bg_image_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_icon_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep"><span><label>Icon BG Color</label></span></div>', 'wordpress' ),
			'thinker_translator_icon_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg',
			__( '<div id="thinker_translator_hover_bg_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent tlt-hover-dep"><span><label>Icon Container</label></span></div>', 'wordpress' ),
			'thinker_translator_hover_bg_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_height',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_height_settings_desc"><label>Height</label></span></div>', 'wordpress' ),
			'thinker_translator_height_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_width',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_width_settings_desc"><label>Width</label></span></div>', 'wordpress' ),
			'thinker_translator_width_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_border_radius',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_border_radius_settings_desc"><label>Border Radius</label></span></div>', 'wordpress' ),
			'thinker_translator_border_radius_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_nation',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span><label>Nation Flag BG</label></label></div>', 'wordpress' ),
			'thinker_translator_nation_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg_image',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_hover_bg_image_settings_desc"><label>BG Image</label></span></div>', 'wordpress' ),
			'thinker_translator_hover_bg_image_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);
		add_settings_field(
			'thinker_translator_hover_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep"><span id="thinker_translator_hover_bg_color_settings_desc"><label>BG Color</label></span></div>', 'wordpress' ),
			'thinker_translator_hover_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_2',
			$options
		);

		add_settings_section(
			'thinker_translator_settings_section_3',
			__( '<div class="thinker_translator_settings_field thinker_translator_settings_field_heading"><span><h2>3. Locations</h2></label></div>', 'wordpress' ),
			'thinker_translator_settings_section_3_callback',
			'pluginPage'
		);
		add_settings_field(
			'thinker_translator_shortcode_padding',
			__( '<div class="thinker_translator_settings_field"><span id="thinker_translator_shortcode_padding_settings_desc"><label>Shortcode Padding</label></span></div>', 'wordpress' ),
			'thinker_translator_shortcode_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_floating',
			__( '<div id="thinker_translator_float_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Floating Translator</label></span></div>', 'wordpress' ),
			'thinker_translator_floating_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_padding',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_padding_settings_desc"><label>Padding</label></label></span></div>', 'wordpress' ),
			'thinker_translator_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_top',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_top_settings_desc"><label>Top Alignment</label></span></div>', 'wordpress' ),
			'thinker_translator_top_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_bottom',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_bottom_settings_desc"><label>Bottom Alignment</label></span></div>', 'wordpress' ),
			'thinker_translator_bottom_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_left',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_left_settings_desc"><label>Left Alignment</label></span></div>', 'wordpress' ),
			'thinker_translator_left_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_right',
			__( '<div class="thinker_translator_settings_field tlt-float-dep"><span id="thinker_translator_right_settings_desc"><label>Right Alignment</label></label></span></div>', 'wordpress' ),
			'thinker_translator_right_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer',
			__( '<div id="thinker_translator_footer_settings_desc" class="thinker_translator_settings_field thinker_translator_settings_parent"><span><label>Below Footer Translator</label></span></div>', 'wordpress' ),
			'thinker_translator_footer_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_padding',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_padding_settings_desc"><label>Footer Padding</label></span></div>', 'wordpress' ),
			'thinker_translator_footer_padding_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_align',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_align_settings_desc"><label>Footer Align</label></span></div>', 'wordpress' ),
			'thinker_translator_footer_align_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_bg_color',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_bg_color_settings_desc"><label>Footer BG Color</label></span></div>', 'wordpress' ),
			'thinker_translator_footer_bg_color_render',
			'pluginPage',
			'thinker_translator_settings_section_3',
			$options
		);
		add_settings_field(
			'thinker_translator_footer_bg_style',
			__( '<div class="thinker_translator_settings_field tlt-footer-dep"><span id="thinker_translator_footer_bg_style_settings_desc"><label>Footer BG Style</label></span></div>', 'wordpress' ),
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
	 * @since    1.0.0
	 *
	 * @global   string    $pagenow
	 */
	public function thinker_language_translator_admin() {

		$plugin = new Thinker_Language_Translator();
		$options = $plugin->get_thinker_translator_options();

		// Caches front-end HTML output if on plugin settings page and caching is on.
		global $pagenow;
		if ( $pagenow == 'options-general.php' && isset($_GET['page']) && $_GET['page'] == 'thinker_language_translator' ) {

			if ( $options['thinker_translator_cache'] != '' ) {

				$this->thinker_translator_cache_output();

			}

		}

		function thinker_translator_settings_section_1_callback() {
			echo __( '', 'wordpress' );
		}

		function thinker_translator_text_color_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input id="thinker_translator_text_color_settings" class="color-field" type='text' name='thinker_translator_settings[thinker_translator_text_color]' value='<?php echo $options['thinker_translator_text_color']; ?>' placeholder="#3C3B6E">
				</label>
				<small class="description">Icon, Links and More Button <br />CSS rules | Examples: <br />black, #fff, rgba(0,0,0,0.9)</small>
			</div>
			<?php

		}

		function thinker_translator_lang_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<select multiple name='thinker_translator_settings[thinker_translator_lang][]'>

						<option value="" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "", $options['thinker_translator_lang'] ) AND count( array_filter( $options['thinker_translator_lang'] ) ) == '' ) { echo ' selected="selected" '; } ?>>Only Default</option>
						<option value="af" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "af", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Afrikaans</option>
						<option value="sq" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sq", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Albanian</option>
						<option value="am" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "am", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Amharic</option>
						<option value="ar" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ar", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Arabic</option>
						<option value="hy" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "hy", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Armenian</option>
						<option value="az" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "az", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Azerbaijani</option>
						<option value="eu" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "eu", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Basque</option>
						<option value="be" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "be", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Belarusian</option>
						<option value="bn" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "bn", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Bengali</option>
						<option value="bs" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "bs", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Bosnian</option>
						<option value="bg" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "bg", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Bulgarian</option>
						<option value="ca" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ca", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Catalan</option>
						<option value="ceb" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ceb", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Cebuano</option>
						<option value="ny" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ny", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Chichewa</option>
						<option value="zh-CN" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "zh-CN", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Chinese (Simplified)</option>
						<option value="zh-TW" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "zh-TW", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Chinese (Traditional)</option>
						<option value="co" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "co", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Corsican</option>
						<option value="hr" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "hr", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Croatian</option>
						<option value="cs" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "cs", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Czech</option>
						<option value="da" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "da", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Danish</option>
						<option value="nl" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "nl", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Dutch</option>
						<option value="en" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "en", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>English</option>
						<option value="eo" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "eo", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Esperanto</option>
						<option value="et" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "et", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Estonian</option>
						<option value="tl" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "tl", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Filipino</option>
						<option value="fi" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "fi", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Finnish</option>
						<option value="fr" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "fr", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>French</option>
						<option value="fy" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "fy", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Frisian</option>
						<option value="gl" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "gl", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Galician</option>
						<option value="ka" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ka", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Georgian</option>
						<option value="de" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "de", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>German</option>
						<option value="el" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "el", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Greek</option>
						<option value="gu" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "gu", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Gujarati</option>
						<option value="ht" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ht", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Haitian Creole</option>
						<option value="ha" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ha", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hausa</option>
						<option value="haw" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "haw", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hawaiian</option>
						<option value="iw" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "iw", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hebrew</option>
						<option value="hi" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "hi", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hindi</option>
						<option value="hmn" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "hmn", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hmong</option>
						<option value="hu" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "hu", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Hungarian</option>
						<option value="is" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "is", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Icelandic</option>
						<option value="ig" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ig", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Igbo</option>
						<option value="id" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "id", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Indonesian</option>
						<option value="ga" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ga", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Irish</option>
						<option value="it" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "it", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Italian</option>
						<option value="ja" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ja", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Japanese</option>
						<option value="jw" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "jw", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Javanese</option>
						<option value="kn" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "kn", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Kannada</option>
						<option value="kk" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "kk", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Kazakh</option>
						<option value="km" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "km", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Khmer</option>
						<option value="ko" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ko", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Korean</option>
						<option value="ku" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ku", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Kurdish (Kurmanji)</option>
						<option value="ky" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ky", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Kyrgyz</option>
						<option value="lo" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "lo", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Lao</option>
						<option value="la" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "la", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Latin</option>
						<option value="lv" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "lv", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Latvian</option>
						<option value="lt" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "lt", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Lithuanian</option>
						<option value="lb" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "lb", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Luxembourgish</option>
						<option value="mk" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mk", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Macedonian</option>
						<option value="mg" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mg", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Malagasy</option>
						<option value="ms" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ms", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Malay</option>
						<option value="ml" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ml", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Malayalam</option>
						<option value="mt" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mt", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Maltese</option>
						<option value="mi" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mi", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Maori</option>
						<option value="mr" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mr", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Marathi</option>
						<option value="mn" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "mn", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Mongolian</option>
						<option value="my" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "my", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Myanmar (Burmese)</option>
						<option value="ne" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ne", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Nepali</option>
						<option value="no" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "no", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Norwegian</option>
						<option value="ps" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ps", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Pashto</option>
						<option value="fa" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "fa", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Persian</option>
						<option value="pl" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "pl", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Polish</option>
						<option value="pt" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "pt", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Portuguese</option>
						<option value="pa" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "pa", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Punjabi</option>
						<option value="ro" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ro", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Romanian</option>
						<option value="ru" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ru", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Russian</option>
						<option value="sm" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sm", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Samoan</option>
						<option value="gd" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "gd", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Scots Gaelic</option>
						<option value="sr" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sr", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Serbian</option>
						<option value="st" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "st", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Sesotho</option>
						<option value="sn" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sn", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Shona</option>
						<option value="sd" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sd", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Sindhi</option>
						<option value="si" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "si", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Sinhala</option>
						<option value="sk" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sk", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Slovak</option>
						<option value="sl" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sl", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Slovenian</option>
						<option value="so" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "so", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Somali</option>
						<option value="es" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "es", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Spanish</option>
						<option value="su" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "su", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Sundanese</option>
						<option value="sw" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sw", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Swahili</option>
						<option value="sv" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "sv", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Swedish</option>
						<option value="tg" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "tg", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Tajik</option>
						<option value="ta" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ta", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Tamil</option>
						<option value="te" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "te", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Telugu</option>
						<option value="th" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "th", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Thai</option>
						<option value="tr" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "tr", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Turkish</option>
						<option value="uk" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "uk", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Ukrainian</option>
						<option value="ur" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "ur", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Urdu</option>
						<option value="uz" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "uz", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Uzbek</option>
						<option value="vi" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "vi", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Vietnamese</option>
						<option value="cy" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "cy", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Welsh</option>
						<option value="xh" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "xh", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Xhosa</option>
						<option value="yi" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "yi", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Yiddish</option>
						<option value="yo" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "yo", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Yoruba</option>
						<option value="zu" <?php if ( is_array( $options['thinker_translator_lang'] ) AND in_array( "zu", $options['thinker_translator_lang'] ) ) { echo ' selected="selected" '; } ?>>Zulu</option>

					</select>
				</label>
				<small class="description"> Hold CTRL or SHIFT for multiple <br />SHIFT + END to select all from top</small>
			</div>

			<?php

		}

		function thinker_translator_display_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<select name='thinker_translator_settings[thinker_translator_display]'>
						<option value="flags" <?php selected( $options['thinker_translator_display'], 'flags' ); ?> >Flags Only</option>
						<option value="both" <?php selected( $options['thinker_translator_display'], 'both' ); ?> >Flags &amp; Text</option>
						<option value="text" <?php selected( $options['thinker_translator_display'], 'text' ); ?> >Text Only</option>
					</select>
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_more_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<select name='thinker_translator_settings[thinker_translator_more]'>

						<option value="plus" <?php selected( $options['thinker_translator_more'], 'plus' ); ?> >Plus Sign Only</option>
						<option value="text" <?php selected( $options['thinker_translator_more'], 'text' ); ?> >More Text Only</option>
						<option value="both" <?php selected( $options['thinker_translator_more'], 'both' ); ?> >Plus Sign &amp; Text</option>
						<option value="none" <?php selected( $options['thinker_translator_more'], 'none' ); ?> >Disable More Languages</option>

					</select>
				</label>
				<small class="description">Excludes HTML Links Method</small>
			</div>
			<?php

		}

		function thinker_translator_more_wide_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input id="thinker_translator_wide_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_more_wide]' <?php checked( $options['thinker_translator_more_wide'], 1 ); ?> value='1'>
			Enabled
				</label>
				<small class="description">Great with Text & Flags <br />Great with many Languages <br />Excludes Inline Display </small>
			</div>
			<?php

		}

		function thinker_translator_inline_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input id="thinker_translator_inline_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_inline]' <?php checked( $options['thinker_translator_inline'], 1 ); ?> value='1'> Enabled
				</label>
				<small class="description">Disables Hover Icon <br />Great with Shortcode, <br />Flags Only, few Languages</small>
			</div>
			<?php

		}

		function thinker_translator_cache_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input type='checkbox' name='thinker_translator_settings[thinker_translator_cache]' <?php checked( $options['thinker_translator_cache'], 1 ); ?> value='1'> Enabled
				</label>
				<small class="description">Recommended for fastest speed <br /> HTML Links Method points to home</small>
			</div>
			<?php

		}

		function thinker_translator_png_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input type='checkbox' name='thinker_translator_settings[thinker_translator_png]' <?php checked( $options['thinker_translator_png'], 1 ); ?> value='1'> Enabled
				</label>
				<small class="description">Not recommended</small>
			</div>
			<?php

		}

		function thinker_translator_html_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input type='checkbox' name='thinker_translator_settings[thinker_translator_html]' <?php checked( $options['thinker_translator_html'], 1 ); ?> value='1'> Enabled
				</label>
				<small class="description">Not recommended</small>
			</div>
			<?php

		}

		function thinker_translator_settings_section_2_callback() {

			echo __( '', 'wordpress' );

		}

		function thinker_translator_hover_render( $options ) {

			?>
			<div class="thinker_translator_settings_field thinker_translator_hover_settings_field">
				<label>
					<input id="thinker_translator_hover_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_hover]' <?php checked( $options['thinker_translator_hover'], 1 ); ?> value='1'>
			Enabled
				</label>
				<small class="description">Works on mouse click and hover</small>
			</div>
			<?php

		}

		function thinker_translator_icon_size_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep">
				<label>
					<input id="thinker_translator_height_settings" name='thinker_translator_settings[thinker_translator_icon_size]' value='<?php echo $options['thinker_translator_icon_size']; ?>' type="number" min="10" max="200" placeholder="30"> px
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_icon_type_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep">
				<label>
					<select name='thinker_translator_settings[thinker_translator_icon_type]'>
						<option value="fa" <?php selected( $options['thinker_translator_icon_type'], 'fa' ); ?> >Font Awesome</option>
						<option value="img" <?php selected( $options['thinker_translator_icon_type'], 'img' ); ?> >Image Only</option>
						<option value="none" <?php selected( $options['thinker_translator_icon_type'], 'none' ); ?> <?php if ( $options['thinker_translator_hover_bg'] != 1 ) { echo 'disabled'; } ?> title="Requires Icon Container (below)">None</option>
					</select>
				</label>
				<small class="description">Enter a custom image below</small>
			</div>
			<?php

		}

		function thinker_translator_icon_bg_image_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep">
				<label>
					<input id="thinker_translator_icon_bg_image_settings" type='text' name='thinker_translator_settings[thinker_translator_icon_bg_image]' value='<?php echo $options['thinker_translator_icon_bg_image']; ?>' placeholder="none">
				</label>
				<small class="description">CSS background-image | Examples: <br />none, http://ex.com/ex.png</small>
			</div>
			<?php

		}

		function thinker_translator_icon_bg_color_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep">
				<label>
					<input id="thinker_translator_icon_bg_color_settings" class="color-field" type='text' name='thinker_translator_settings[thinker_translator_icon_bg_color]' value='<?php echo $options['thinker_translator_icon_bg_color']; ?>' placeholder="#fff">
				</label>
				<small class="description">CSS background-color | Examples: <br />transparent, #fff, rgba(0,0,0,0.5)</small>
			</div>
			<?php

		}

		function thinker_translator_hover_bg_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep">
				<label>
					<input id="thinker_translator_hover_bg_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_hover_bg]' <?php checked( $options['thinker_translator_hover_bg'], 1 ); ?> value='1'>
			Enabled
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_height_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
					<input id="thinker_translator_height_settings" name='thinker_translator_settings[thinker_translator_height]' value='<?php echo $options['thinker_translator_height']; ?>' type="number" min="10" max="200" placeholder="30"> px
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_width_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
					<input id="thinker_translator_width_settings" name='thinker_translator_settings[thinker_translator_width]' value='<?php echo $options['thinker_translator_width']; ?>' type="number" min="10" max="200" placeholder="50"> px
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_border_radius_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
					<input id="thinker_translator_border_radius_settings" type='text' name='thinker_translator_settings[thinker_translator_border_radius]' value='<?php echo $options['thinker_translator_border_radius']; ?>' placeholder="50%">
				</label>
				<small class="description">CSS rules | Examples: <br />50%, 0, 5px, 0 0 50% 50%</small>
			</div>
			<?php

		}

		function thinker_translator_nation_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
					<select name='thinker_translator_settings[thinker_translator_nation]'>

						<option value="us" <?php selected( $options['thinker_translator_nation'], 'us' ); ?>>United States</option>
						<option value="none" <?php selected( $options['thinker_translator_nation'], 'none' ); ?>> - None - </option>
						<option value="af" <?php selected( $options['thinker_translator_nation'], 'af' ); ?>>Afghanistan</option>
						<option value="ax" <?php selected( $options['thinker_translator_nation'], 'ax' ); ?>>Aland Islands</option>
						<option value="al" <?php selected( $options['thinker_translator_nation'], 'al' ); ?>>Albania</option>
						<option value="dz" <?php selected( $options['thinker_translator_nation'], 'dz' ); ?>>Algeria</option>
						<option value="as" <?php selected( $options['thinker_translator_nation'], 'as' ); ?>>American Samoa</option>
						<option value="ad" <?php selected( $options['thinker_translator_nation'], 'ad' ); ?>>Andorra</option>
						<option value="ao" <?php selected( $options['thinker_translator_nation'], 'ao' ); ?>>Angola</option>
						<option value="ai" <?php selected( $options['thinker_translator_nation'], 'ai' ); ?>>Anguilla</option>
						<option value="ag" <?php selected( $options['thinker_translator_nation'], 'ag' ); ?>>Antigua and Barbuda</option>
						<option value="ar" <?php selected( $options['thinker_translator_nation'], 'ar' ); ?>>Argentina</option>
						<option value="am" <?php selected( $options['thinker_translator_nation'], 'am' ); ?>>Armenia</option>
						<option value="aw" <?php selected( $options['thinker_translator_nation'], 'aw' ); ?>>Aruba</option>
						<option value="au" <?php selected( $options['thinker_translator_nation'], 'au' ); ?>>Australia</option>
						<option value="at" <?php selected( $options['thinker_translator_nation'], 'at' ); ?>>Austria</option>
						<option value="az" <?php selected( $options['thinker_translator_nation'], 'az' ); ?>>Azerbaijan</option>
						<option value="bs" <?php selected( $options['thinker_translator_nation'], 'bs' ); ?>>Bahamas</option>
						<option value="bh" <?php selected( $options['thinker_translator_nation'], 'bh' ); ?>>Bahrain</option>
						<option value="bd" <?php selected( $options['thinker_translator_nation'], 'bd' ); ?>>Bangladesh</option>
						<option value="bb" <?php selected( $options['thinker_translator_nation'], 'bb' ); ?>>Barbados</option>
						<option value="by" <?php selected( $options['thinker_translator_nation'], 'by' ); ?>>Belarus</option>
						<option value="be" <?php selected( $options['thinker_translator_nation'], 'be' ); ?>>Belgium</option>
						<option value="bz" <?php selected( $options['thinker_translator_nation'], 'bz' ); ?>>Belize</option>
						<option value="bj" <?php selected( $options['thinker_translator_nation'], 'bj' ); ?>>Benin</option>
						<option value="bm" <?php selected( $options['thinker_translator_nation'], 'bm' ); ?>>Bermuda</option>
						<option value="bt" <?php selected( $options['thinker_translator_nation'], 'bt' ); ?>>Bhutan</option>
						<option value="bo" <?php selected( $options['thinker_translator_nation'], 'bo' ); ?>>Bolivia</option>
						<option value="ba" <?php selected( $options['thinker_translator_nation'], 'ba' ); ?>>Bosnia and Herzegovina</option>
						<option value="bw" <?php selected( $options['thinker_translator_nation'], 'bw' ); ?>>Botswana</option>
						<option value="br" <?php selected( $options['thinker_translator_nation'], 'br' ); ?>>Brazil</option>
						<option value="io" <?php selected( $options['thinker_translator_nation'], 'io' ); ?>>British Indian Ocean Terr</option>
						<option value="bn" <?php selected( $options['thinker_translator_nation'], 'bn' ); ?>>Brunei Darussalam</option>
						<option value="bg" <?php selected( $options['thinker_translator_nation'], 'bg' ); ?>>Bulgaria</option>
						<option value="bf" <?php selected( $options['thinker_translator_nation'], 'bf' ); ?>>Burkina Faso</option>
						<option value="bi" <?php selected( $options['thinker_translator_nation'], 'bi' ); ?>>Burundi</option>
						<option value="cv" <?php selected( $options['thinker_translator_nation'], 'cv' ); ?>>Cabo Verde</option>
						<option value="kh" <?php selected( $options['thinker_translator_nation'], 'kh' ); ?>>Cambodia</option>
						<option value="cm" <?php selected( $options['thinker_translator_nation'], 'cm' ); ?>>Cameroon</option>
						<option value="ca" <?php selected( $options['thinker_translator_nation'], 'ca' ); ?>>Canada</option>
						<option value="bq" <?php selected( $options['thinker_translator_nation'], 'bq' ); ?>>Caribbean Netherlands</option>
						<option value="ky" <?php selected( $options['thinker_translator_nation'], 'ky' ); ?>>Cayman Islands</option>
						<option value="cf" <?php selected( $options['thinker_translator_nation'], 'cf' ); ?>>Central African Republic</option>
						<option value="td" <?php selected( $options['thinker_translator_nation'], 'td' ); ?>>Chad</option>
						<option value="cl" <?php selected( $options['thinker_translator_nation'], 'cl' ); ?>>Chile</option>
						<option value="cn" <?php selected( $options['thinker_translator_nation'], 'cn' ); ?>>China</option>
						<option value="cx" <?php selected( $options['thinker_translator_nation'], 'cx' ); ?>>Christmas Island</option>
						<option value="cc" <?php selected( $options['thinker_translator_nation'], 'cc' ); ?>>Cocos (Keeling) Islands</option>
						<option value="co" <?php selected( $options['thinker_translator_nation'], 'co' ); ?>>Colombia</option>
						<option value="km" <?php selected( $options['thinker_translator_nation'], 'km' ); ?>>Comoros</option>
						<option value="cg" <?php selected( $options['thinker_translator_nation'], 'cg' ); ?>>Rep of the Congo</option>
						<option value="ck" <?php selected( $options['thinker_translator_nation'], 'ck' ); ?>>Cook Islands</option>
						<option value="cr" <?php selected( $options['thinker_translator_nation'], 'cr' ); ?>>Costa Rica</option>
						<option value="hr" <?php selected( $options['thinker_translator_nation'], 'hr' ); ?>>Croatia</option>
						<option value="cu" <?php selected( $options['thinker_translator_nation'], 'cu' ); ?>>Cuba</option>
						<option value="cw" <?php selected( $options['thinker_translator_nation'], 'cw' ); ?>>Cura&ccedil;ao</option>
						<option value="cy" <?php selected( $options['thinker_translator_nation'], 'cy' ); ?>>Cyprus</option>
						<option value="cz" <?php selected( $options['thinker_translator_nation'], 'cz' ); ?>>Czech Republic</option>
						<option value="ci" <?php selected( $options['thinker_translator_nation'], 'ci' ); ?>>C&ocirc;te d'Ivoire</option>
						<option value="cd" <?php selected( $options['thinker_translator_nation'], 'cd' ); ?>>Dem Rep of the Congo</option>
						<option value="dk" <?php selected( $options['thinker_translator_nation'], 'dk' ); ?>>Denmark</option>
						<option value="dj" <?php selected( $options['thinker_translator_nation'], 'dj' ); ?>>Djibouti</option>
						<option value="dm" <?php selected( $options['thinker_translator_nation'], 'dm' ); ?>>Dominica</option>
						<option value="do" <?php selected( $options['thinker_translator_nation'], 'do' ); ?>>Dominican Republic</option>
						<option value="ec" <?php selected( $options['thinker_translator_nation'], 'ec' ); ?>>Ecuador</option>
						<option value="eg" <?php selected( $options['thinker_translator_nation'], 'eg' ); ?>>Egypt</option>
						<option value="sv" <?php selected( $options['thinker_translator_nation'], 'sv' ); ?>>El Salvador</option>
						<option value="gq" <?php selected( $options['thinker_translator_nation'], 'gq' ); ?>>Equatorial Guinea</option>
						<option value="er" <?php selected( $options['thinker_translator_nation'], 'er' ); ?>>Eritrea</option>
						<option value="ee" <?php selected( $options['thinker_translator_nation'], 'ee' ); ?>>Estonia</option>
						<option value="et" <?php selected( $options['thinker_translator_nation'], 'et' ); ?>>Ethiopia</option>
						<option value="fk" <?php selected( $options['thinker_translator_nation'], 'fk' ); ?>>Falkland Islands</option>
						<option value="fo" <?php selected( $options['thinker_translator_nation'], 'fo' ); ?>>Faroe Islands</option>
						<option value="fj" <?php selected( $options['thinker_translator_nation'], 'fj' ); ?>>Fiji</option>
						<option value="fi" <?php selected( $options['thinker_translator_nation'], 'fi' ); ?>>Finland</option>
						<option value="fr" <?php selected( $options['thinker_translator_nation'], 'fr' ); ?>>France</option>
						<option value="gf" <?php selected( $options['thinker_translator_nation'], 'gf' ); ?>>French Guiana</option>
						<option value="pf" <?php selected( $options['thinker_translator_nation'], 'pf' ); ?>>French Polynesia</option>
						<option value="tf" <?php selected( $options['thinker_translator_nation'], 'tf' ); ?>>French Southern Terr</option>
						<option value="ga" <?php selected( $options['thinker_translator_nation'], 'ga' ); ?>>Gabon</option>
						<option value="gm" <?php selected( $options['thinker_translator_nation'], 'gm' ); ?>>Gambia</option>
						<option value="ge" <?php selected( $options['thinker_translator_nation'], 'ge' ); ?>>Georgia</option>
						<option value="de" <?php selected( $options['thinker_translator_nation'], 'de' ); ?>>Germany</option>
						<option value="gh" <?php selected( $options['thinker_translator_nation'], 'gh' ); ?>>Ghana</option>
						<option value="gi" <?php selected( $options['thinker_translator_nation'], 'gi' ); ?>>Gibraltar</option>
						<option value="gr" <?php selected( $options['thinker_translator_nation'], 'gr' ); ?>>Greece</option>
						<option value="gl" <?php selected( $options['thinker_translator_nation'], 'gl' ); ?>>Greenland</option>
						<option value="gd" <?php selected( $options['thinker_translator_nation'], 'gd' ); ?>>Grenada</option>
						<option value="gp" <?php selected( $options['thinker_translator_nation'], 'gp' ); ?>>Guadeloupe</option>
						<option value="gu" <?php selected( $options['thinker_translator_nation'], 'gu' ); ?>>Guam</option>
						<option value="gt" <?php selected( $options['thinker_translator_nation'], 'gt' ); ?>>Guatemala</option>
						<option value="gg" <?php selected( $options['thinker_translator_nation'], 'gg' ); ?>>Guernsey</option>
						<option value="gn" <?php selected( $options['thinker_translator_nation'], 'gn' ); ?>>Guinea</option>
						<option value="gw" <?php selected( $options['thinker_translator_nation'], 'gw' ); ?>>Guinea-Bissau</option>
						<option value="gy" <?php selected( $options['thinker_translator_nation'], 'gy' ); ?>>Guyana</option>
						<option value="ht" <?php selected( $options['thinker_translator_nation'], 'ht' ); ?>>Haiti</option>
						<option value="va" <?php selected( $options['thinker_translator_nation'], 'va' ); ?>>Holy See</option>
						<option value="hn" <?php selected( $options['thinker_translator_nation'], 'hn' ); ?>>Honduras</option>
						<option value="hk" <?php selected( $options['thinker_translator_nation'], 'hk' ); ?>>Hong Kong</option>
						<option value="hu" <?php selected( $options['thinker_translator_nation'], 'hu' ); ?>>Hungary</option>
						<option value="is" <?php selected( $options['thinker_translator_nation'], 'is' ); ?>>Iceland</option>
						<option value="in" <?php selected( $options['thinker_translator_nation'], 'in' ); ?>>India</option>
						<option value="id" <?php selected( $options['thinker_translator_nation'], 'id' ); ?>>Indonesia</option>
						<option value="ir" <?php selected( $options['thinker_translator_nation'], 'ir' ); ?>>Iran</option>
						<option value="iq" <?php selected( $options['thinker_translator_nation'], 'iq' ); ?>>Iraq</option>
						<option value="ie" <?php selected( $options['thinker_translator_nation'], 'ie' ); ?>>Ireland</option>
						<option value="im" <?php selected( $options['thinker_translator_nation'], 'im' ); ?>>Isle of Man</option>
						<option value="il" <?php selected( $options['thinker_translator_nation'], 'il' ); ?>>Israel</option>
						<option value="it" <?php selected( $options['thinker_translator_nation'], 'it' ); ?>>Italy</option>
						<option value="jm" <?php selected( $options['thinker_translator_nation'], 'jm' ); ?>>Jamaica</option>
						<option value="jp" <?php selected( $options['thinker_translator_nation'], 'jp' ); ?>>Japan</option>
						<option value="je" <?php selected( $options['thinker_translator_nation'], 'je' ); ?>>Jersey</option>
						<option value="jo" <?php selected( $options['thinker_translator_nation'], 'jo' ); ?>>Jordan</option>
						<option value="kz" <?php selected( $options['thinker_translator_nation'], 'kz' ); ?>>Kazakhstan</option>
						<option value="ke" <?php selected( $options['thinker_translator_nation'], 'ke' ); ?>>Kenya</option>
						<option value="ki" <?php selected( $options['thinker_translator_nation'], 'ki' ); ?>>Kiribati</option>
						<option value="kw" <?php selected( $options['thinker_translator_nation'], 'kw' ); ?>>Kuwait</option>
						<option value="kg" <?php selected( $options['thinker_translator_nation'], 'kg' ); ?>>Kyrgyzstan</option>
						<option value="la" <?php selected( $options['thinker_translator_nation'], 'la' ); ?>>Laos</option>
						<option value="lv" <?php selected( $options['thinker_translator_nation'], 'lv' ); ?>>Latvia</option>
						<option value="lb" <?php selected( $options['thinker_translator_nation'], 'lb' ); ?>>Lebanon</option>
						<option value="ls" <?php selected( $options['thinker_translator_nation'], 'ls' ); ?>>Lesotho</option>
						<option value="lr" <?php selected( $options['thinker_translator_nation'], 'lr' ); ?>>Liberia</option>
						<option value="ly" <?php selected( $options['thinker_translator_nation'], 'ly' ); ?>>Libya</option>
						<option value="li" <?php selected( $options['thinker_translator_nation'], 'li' ); ?>>Liechtenstein</option>
						<option value="lt" <?php selected( $options['thinker_translator_nation'], 'lt' ); ?>>Lithuania</option>
						<option value="lu" <?php selected( $options['thinker_translator_nation'], 'lu' ); ?>>Luxembourg</option>
						<option value="mo" <?php selected( $options['thinker_translator_nation'], 'mo' ); ?>>Macau</option>
						<option value="mk" <?php selected( $options['thinker_translator_nation'], 'mk' ); ?>>Macedonia</option>
						<option value="mg" <?php selected( $options['thinker_translator_nation'], 'mg' ); ?>>Madagascar</option>
						<option value="mw" <?php selected( $options['thinker_translator_nation'], 'mw' ); ?>>Malawi</option>
						<option value="my" <?php selected( $options['thinker_translator_nation'], 'my' ); ?>>Malaysia</option>
						<option value="mv" <?php selected( $options['thinker_translator_nation'], 'mv' ); ?>>Maldives</option>
						<option value="ml" <?php selected( $options['thinker_translator_nation'], 'ml' ); ?>>Mali</option>
						<option value="mt" <?php selected( $options['thinker_translator_nation'], 'mt' ); ?>>Malta</option>
						<option value="mh" <?php selected( $options['thinker_translator_nation'], 'mh' ); ?>>Marshall Islands</option>
						<option value="mq" <?php selected( $options['thinker_translator_nation'], 'mq' ); ?>>Martinique</option>
						<option value="mr" <?php selected( $options['thinker_translator_nation'], 'mr' ); ?>>Mauritania</option>
						<option value="mu" <?php selected( $options['thinker_translator_nation'], 'mu' ); ?>>Mauritius</option>
						<option value="yt" <?php selected( $options['thinker_translator_nation'], 'yt' ); ?>>Mayotte</option>
						<option value="mx" <?php selected( $options['thinker_translator_nation'], 'mx' ); ?>>Mexico</option>
						<option value="fm" <?php selected( $options['thinker_translator_nation'], 'fm' ); ?>>Micronesia</option>
						<option value="md" <?php selected( $options['thinker_translator_nation'], 'md' ); ?>>Moldova</option>
						<option value="mc" <?php selected( $options['thinker_translator_nation'], 'mc' ); ?>>Monaco</option>
						<option value="mn" <?php selected( $options['thinker_translator_nation'], 'mn' ); ?>>Mongolia</option>
						<option value="me" <?php selected( $options['thinker_translator_nation'], 'me' ); ?>>Montenegro</option>
						<option value="ms" <?php selected( $options['thinker_translator_nation'], 'ms' ); ?>>Montserrat</option>
						<option value="ma" <?php selected( $options['thinker_translator_nation'], 'ma' ); ?>>Morocco</option>
						<option value="mz" <?php selected( $options['thinker_translator_nation'], 'mz' ); ?>>Mozambique</option>
						<option value="mm" <?php selected( $options['thinker_translator_nation'], 'mm' ); ?>>Myanmar</option>
						<option value="na" <?php selected( $options['thinker_translator_nation'], 'na' ); ?>>Namibia</option>
						<option value="nr" <?php selected( $options['thinker_translator_nation'], 'nr' ); ?>>Nauru</option>
						<option value="np" <?php selected( $options['thinker_translator_nation'], 'np' ); ?>>Nepal</option>
						<option value="nl" <?php selected( $options['thinker_translator_nation'], 'nl' ); ?>>Netherlands</option>
						<option value="nc" <?php selected( $options['thinker_translator_nation'], 'nc' ); ?>>New Caledonia</option>
						<option value="nz" <?php selected( $options['thinker_translator_nation'], 'nz' ); ?>>New Zealand</option>
						<option value="ni" <?php selected( $options['thinker_translator_nation'], 'ni' ); ?>>Nicaragua</option>
						<option value="ne" <?php selected( $options['thinker_translator_nation'], 'ne' ); ?>>Niger</option>
						<option value="ng" <?php selected( $options['thinker_translator_nation'], 'ng' ); ?>>Nigeria</option>
						<option value="nu" <?php selected( $options['thinker_translator_nation'], 'nu' ); ?>>Niue</option>
						<option value="nf" <?php selected( $options['thinker_translator_nation'], 'nf' ); ?>>Norfolk Island</option>
						<option value="kp" <?php selected( $options['thinker_translator_nation'], 'kp' ); ?>>North Korea</option>
						<option value="mp" <?php selected( $options['thinker_translator_nation'], 'mp' ); ?>>Northern Mariana Islands</option>
						<option value="no" <?php selected( $options['thinker_translator_nation'], 'no' ); ?>>Norway</option>
						<option value="om" <?php selected( $options['thinker_translator_nation'], 'om' ); ?>>Oman</option>
						<option value="pk" <?php selected( $options['thinker_translator_nation'], 'pk' ); ?>>Pakistan</option>
						<option value="pw" <?php selected( $options['thinker_translator_nation'], 'pw' ); ?>>Palau</option>
						<option value="pa" <?php selected( $options['thinker_translator_nation'], 'pa' ); ?>>Panama</option>
						<option value="pg" <?php selected( $options['thinker_translator_nation'], 'pg' ); ?>>Papua New Guinea</option>
						<option value="py" <?php selected( $options['thinker_translator_nation'], 'py' ); ?>>Paraguay</option>
						<option value="pe" <?php selected( $options['thinker_translator_nation'], 'pe' ); ?>>Peru</option>
						<option value="ph" <?php selected( $options['thinker_translator_nation'], 'ph' ); ?>>Philippines</option>
						<option value="pn" <?php selected( $options['thinker_translator_nation'], 'pn' ); ?>>Pitcairn</option>
						<option value="pl" <?php selected( $options['thinker_translator_nation'], 'pl' ); ?>>Poland</option>
						<option value="pt" <?php selected( $options['thinker_translator_nation'], 'pt' ); ?>>Portugal</option>
						<option value="pr" <?php selected( $options['thinker_translator_nation'], 'pr' ); ?>>Puerto Rico</option>
						<option value="qa" <?php selected( $options['thinker_translator_nation'], 'qa' ); ?>>Qatar</option>
						<option value="cg" <?php selected( $options['thinker_translator_nation'], 'cg' ); ?>>Republic of the Congo</option>
						<option value="ro" <?php selected( $options['thinker_translator_nation'], 'ro' ); ?>>Romania</option>
						<option value="ru" <?php selected( $options['thinker_translator_nation'], 'ru' ); ?>>Russia</option>
						<option value="rw" <?php selected( $options['thinker_translator_nation'], 'rw' ); ?>>Rwanda</option>
						<option value="re" <?php selected( $options['thinker_translator_nation'], 're' ); ?>>R&eacute;union</option>
						<option value="bl" <?php selected( $options['thinker_translator_nation'], 'bl' ); ?>>St Barth&eacute;lemy</option>
						<option value="sh" <?php selected( $options['thinker_translator_nation'], 'sh' ); ?>>St Helena...</option>
						<option value="kn" <?php selected( $options['thinker_translator_nation'], 'kn' ); ?>>St Kitts and Nevis</option>
						<option value="lc" <?php selected( $options['thinker_translator_nation'], 'lc' ); ?>>St Lucia</option>
						<option value="mf" <?php selected( $options['thinker_translator_nation'], 'mf' ); ?>>St Martin</option>
						<option value="pm" <?php selected( $options['thinker_translator_nation'], 'pm' ); ?>>St Pierre and Miquelon</option>
						<option value="vc" <?php selected( $options['thinker_translator_nation'], 'vc' ); ?>>St Vincent and the Gren</option>
						<option value="ws" <?php selected( $options['thinker_translator_nation'], 'ws' ); ?>>Samoa</option>
						<option value="sm" <?php selected( $options['thinker_translator_nation'], 'sm' ); ?>>San Marino</option>
						<option value="st" <?php selected( $options['thinker_translator_nation'], 'st' ); ?>>Sao Tome and Principe</option>
						<option value="sa" <?php selected( $options['thinker_translator_nation'], 'sa' ); ?>>Saudi Arabia</option>
						<option value="sn" <?php selected( $options['thinker_translator_nation'], 'sn' ); ?>>Senegal</option>
						<option value="rs" <?php selected( $options['thinker_translator_nation'], 'rs' ); ?>>Serbia</option>
						<option value="sc" <?php selected( $options['thinker_translator_nation'], 'sc' ); ?>>Seychelles</option>
						<option value="sl" <?php selected( $options['thinker_translator_nation'], 'sl' ); ?>>Sierra Leone</option>
						<option value="sg" <?php selected( $options['thinker_translator_nation'], 'sg' ); ?>>Singapore</option>
						<option value="sx" <?php selected( $options['thinker_translator_nation'], 'sx' ); ?>>Sint Maarten</option>
						<option value="sk" <?php selected( $options['thinker_translator_nation'], 'sk' ); ?>>Slovakia</option>
						<option value="si" <?php selected( $options['thinker_translator_nation'], 'si' ); ?>>Slovenia</option>
						<option value="sb" <?php selected( $options['thinker_translator_nation'], 'sb' ); ?>>Solomon Islands</option>
						<option value="so" <?php selected( $options['thinker_translator_nation'], 'so' ); ?>>Somalia</option>
						<option value="za" <?php selected( $options['thinker_translator_nation'], 'za' ); ?>>South Africa</option>
						<option value="gs" <?php selected( $options['thinker_translator_nation'], 'gs' ); ?>>South Georgia</option>
						<option value="kr" <?php selected( $options['thinker_translator_nation'], 'kr' ); ?>>South Korea</option>
						<option value="ss" <?php selected( $options['thinker_translator_nation'], 'ss' ); ?>>South Sudan</option>
						<option value="es" <?php selected( $options['thinker_translator_nation'], 'es' ); ?>>Spain</option>
						<option value="lk" <?php selected( $options['thinker_translator_nation'], 'lk' ); ?>>Sri Lanka</option>
						<option value="ps" <?php selected( $options['thinker_translator_nation'], 'ps' ); ?>>State of Palestine</option>
						<option value="sd" <?php selected( $options['thinker_translator_nation'], 'sd' ); ?>>Sudan</option>
						<option value="sr" <?php selected( $options['thinker_translator_nation'], 'sr' ); ?>>Suriname</option>
						<option value="sj" <?php selected( $options['thinker_translator_nation'], 'sj' ); ?>>Svalbard and Jan Mayen</option>
						<option value="sz" <?php selected( $options['thinker_translator_nation'], 'sz' ); ?>>Swaziland</option>
						<option value="se" <?php selected( $options['thinker_translator_nation'], 'se' ); ?>>Sweden</option>
						<option value="ch" <?php selected( $options['thinker_translator_nation'], 'ch' ); ?>>Switzerland</option>
						<option value="sy" <?php selected( $options['thinker_translator_nation'], 'sy' ); ?>>Syrian Arab Republic</option>
						<option value="tw" <?php selected( $options['thinker_translator_nation'], 'tw' ); ?>>Taiwan</option>
						<option value="tj" <?php selected( $options['thinker_translator_nation'], 'tj' ); ?>>Tajikistan</option>
						<option value="tz" <?php selected( $options['thinker_translator_nation'], 'tz' ); ?>>Tanzania</option>
						<option value="th" <?php selected( $options['thinker_translator_nation'], 'th' ); ?>>Thailand</option>
						<option value="tl" <?php selected( $options['thinker_translator_nation'], 'tl' ); ?>>Timor-Leste</option>
						<option value="tg" <?php selected( $options['thinker_translator_nation'], 'tg' ); ?>>Togo</option>
						<option value="tk" <?php selected( $options['thinker_translator_nation'], 'tk' ); ?>>Tokelau</option>
						<option value="to" <?php selected( $options['thinker_translator_nation'], 'to' ); ?>>Tonga</option>
						<option value="tt" <?php selected( $options['thinker_translator_nation'], 'tt' ); ?>>Trinidad and Tobago</option>
						<option value="tn" <?php selected( $options['thinker_translator_nation'], 'tn' ); ?>>Tunisia</option>
						<option value="tr" <?php selected( $options['thinker_translator_nation'], 'tr' ); ?>>Turkey</option>
						<option value="tm" <?php selected( $options['thinker_translator_nation'], 'tm' ); ?>>Turkmenistan</option>
						<option value="tc" <?php selected( $options['thinker_translator_nation'], 'tc' ); ?>>Turks and Caicos Islands</option>
						<option value="tv" <?php selected( $options['thinker_translator_nation'], 'tv' ); ?>>Tuvalu</option>
						<option value="ug" <?php selected( $options['thinker_translator_nation'], 'ug' ); ?>>Uganda</option>
						<option value="ua" <?php selected( $options['thinker_translator_nation'], 'ua' ); ?>>Ukraine</option>
						<option value="ae" <?php selected( $options['thinker_translator_nation'], 'ae' ); ?>>United Arab Emirates</option>
						<option value="gb" <?php selected( $options['thinker_translator_nation'], 'gb' ); ?>>United Kingdom</option>
						<option value="um" <?php selected( $options['thinker_translator_nation'], 'um' ); ?>>US Minor Outlying Isl</option>
						<option value="uy" <?php selected( $options['thinker_translator_nation'], 'uy' ); ?>>Uruguay</option>
						<option value="uz" <?php selected( $options['thinker_translator_nation'], 'uz' ); ?>>Uzbekistan</option>
						<option value="vu" <?php selected( $options['thinker_translator_nation'], 'vu' ); ?>>Vanuatu</option>
						<option value="ve" <?php selected( $options['thinker_translator_nation'], 've' ); ?>>Venezuela</option>
						<option value="vn" <?php selected( $options['thinker_translator_nation'], 'vn' ); ?>>Vietnam</option>
						<option value="vg" <?php selected( $options['thinker_translator_nation'], 'vg' ); ?>>Virgin Islands (British)</option>
						<option value="vi" <?php selected( $options['thinker_translator_nation'], 'vi' ); ?>>Virgin Islands (U.S.)</option>
						<option value="wf" <?php selected( $options['thinker_translator_nation'], 'wf' ); ?>>Wallis and Futuna</option>
						<option value="eh" <?php selected( $options['thinker_translator_nation'], 'eh' ); ?>>Western Sahara</option>
						<option value="ye" <?php selected( $options['thinker_translator_nation'], 'ye' ); ?>>Yemen</option>
						<option value="zm" <?php selected( $options['thinker_translator_nation'], 'zm' ); ?>>Zambia</option>
						<option value="zw" <?php selected( $options['thinker_translator_nation'], 'zw' ); ?>>Zimbabwe</option>

					</select>
				</label>
				<small class="description">Represents location of website.</small>
			</div>
			<?php

		}

		function thinker_translator_hover_bg_image_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
					<input id="thinker_translator_hover_bg_image_settings" type='text' name='thinker_translator_settings[thinker_translator_hover_bg_image]' value='<?php echo $options['thinker_translator_hover_bg_image']; ?>' placeholder="/wp-content/uploads/ex.png">
				</label>
				<small class="description">Overrides Flag BG <br />CSS background-image | Examples: <br />none, http://ex.com/ex.png <br /></small>
			</div>
			<?php

		}

		function thinker_translator_hover_bg_color_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-hover-dep tlt-icon-bg-dep">
				<label>
				<label>
					<input id="thinker_translator_hover_bg_color_settings" class="color-field" type='text' name='thinker_translator_settings[thinker_translator_hover_bg_color]' value='<?php echo $options['thinker_translator_hover_bg_color']; ?>' placeholder="transparent">
				</label>
				<small class="description">CSS background-color | Examples: <br />transparent, #fff, rgba(0,0,0,0.5)</small>
			</div>
			<?php

		}

		function thinker_translator_settings_section_3_callback() {

			echo __( '', 'wordpress' );

		}

		function thinker_translator_shortcode_padding_render( $options ) {

			?>
			<div class="thinker_translator_settings_field">
				<label>
					<input id="thinker_translator_shortcode_padding_settings" type='text' name='thinker_translator_settings[thinker_translator_shortcode_padding]' value='<?php echo $options['thinker_translator_shortcode_padding']; ?>' placeholder="5px">
				</label>
				<small class="description">Only applies to Shortcode: <br /> [thinker_translator] <br />CSS rules | Examples: <br />25px, 5px 0 5px 50px, 0 0 0 10%</small>
			</div>
			<?php

		}

		function thinker_translator_floating_render( $options ) {

			?>
			<div class="thinker_translator_settings_field thinker_translator_settings_parent">
				<label>
					<input id="thinker_translator_float_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_floating]' <?php checked( $options['thinker_translator_floating'], 1 ); ?> value='1'>
			Enabled
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_padding_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-float-dep">
				<label>
					<input id="thinker_translator_padding_settings" type='text' name='thinker_translator_settings[thinker_translator_padding]' value='<?php echo $options['thinker_translator_padding']; ?>' placeholder="0">
				</label>
				<small class="description">CSS rules | Examples: <br />25px, 50px 0 0 50px, 0 0 0 10%</small>
			</div>
			<?php

		}

		function thinker_translator_top_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-float-dep">
				<label>
					<input id="thinker_translator_top_settings" type='text' name='thinker_translator_settings[thinker_translator_top]' value='<?php echo $options['thinker_translator_top']; ?>' placeholder="50px">
				</label>
				<small class="description">CSS rules | Overrides Bottom</small>
			</div>
			<?php

		}

		function thinker_translator_bottom_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-float-dep">
				<label>
					<input id="thinker_translator_bottom_settings" type='text' name='thinker_translator_settings[thinker_translator_bottom]' value='<?php echo $options['thinker_translator_bottom']; ?>' placeholder="auto">
				</label>
				<small class="description">CSS rules</small>
			</div>
			<?php

		}

		function thinker_translator_left_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-float-dep">
				<label>
					<input id="thinker_translator_left_settings" type='text' name='thinker_translator_settings[thinker_translator_left]' value='<?php echo $options['thinker_translator_left']; ?>' placeholder="50px">
				</label>
				<small class="description">CSS rules | Left 0 &amp; Right 0 = Center</small>
			</div>
			<?php

		}

		function thinker_translator_right_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-float-dep">
				<label>
					<input id="thinker_translator_right_settings" type='text' name='thinker_translator_settings[thinker_translator_right]' value='<?php echo $options['thinker_translator_right']; ?>' placeholder="auto">
				</label>
				<small class="description">CSS rules | Left 0 &amp; Right 0 = Center</small>
			</div>
			<?php

		}

		function thinker_translator_footer_render( $options ) {

			?>
			<div class="thinker_translator_settings_field thinker_translator_settings_parent">
				<label>
					<input id="thinker_translator_footer_settings" type='checkbox' name='thinker_translator_settings[thinker_translator_footer]' <?php checked( $options['thinker_translator_footer'], 1 ); ?> value='1'> Enabled
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_footer_padding_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-footer-dep">
				<label>
					<input id="thinker_translator_footer_padding_settings" type='text' name='thinker_translator_settings[thinker_translator_footer_padding]' value='<?php echo $options['thinker_translator_footer_padding']; ?>' placeholder="5px">
				</label>
				<small class="description">CSS rules | Examples: <br />25px, 5px 0 5px 50px, 0 0 0 10%</small>
			</div>
			<?php

		}

		function thinker_translator_footer_align_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-footer-dep">
				<label>
					<select id="thinker_translator_footer_align_settings" name='thinker_translator_settings[thinker_translator_footer_align]'>
						<option value="center" <?php selected( $options['thinker_translator_footer_align'], 'center' ); ?> >Center</option>
						<option value="left" <?php selected( $options['thinker_translator_footer_align'], 'left' ); ?> >Left</option>
						<option value="right" <?php selected( $options['thinker_translator_footer_align'], 'right' ); ?> >Right</option>
						<option value="inherit" <?php selected( $options['thinker_translator_footer_align'], 'inherit' ); ?> >Inherit</option>
					</select>
				</label>
				<small class="description"></small>
			</div>
			<?php

		}

		function thinker_translator_footer_bg_color_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-footer-dep">
				<label>
					<input id="thinker_translator_footer_bg_color_settings" class="color-field" type='text' name='thinker_translator_settings[thinker_translator_footer_bg_color]' value='<?php echo $options['thinker_translator_footer_bg_color']; ?>' placeholder="transparent">
				</label>
				<small class="description">Great to match website <br />CSS rules | Examples: <br />transparent, white</small>
			</div>
			<?php

		}

		function thinker_translator_footer_bg_style_render( $options ) {

			?>
			<div class="thinker_translator_settings_field tlt-footer-dep">
				<label>
					<input id="thinker_translator_footer_bg_style_settings" type='text' name='thinker_translator_settings[thinker_translator_footer_bg_style]' value='<?php echo $options['thinker_translator_footer_bg_style']; ?>' placeholder="none transparent ">
				</label>
				<small class="description">Great to match website <br />CSS background rules | Examples: <br />rgba(0,0,0,0.5), #fff, blue, <br />url(/ex.png) center top / cover </small>
			</div>
			<?php

		}

	}

}
