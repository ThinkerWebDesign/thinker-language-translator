<?php
/**
 * Defines the core plugin class.
 *
 * Includes both public and admin facing attributes and functions.
 *
 * @link       http://thinkerwebdesign.com/thinker-language-translator-plugin/
 * @since      1.0.0
 *
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 */

/**
 * Contains the core plugin class.
 *
 * Defines internationalization, admin-specific and public-facing site hooks.
 * Maintains the unique identifier of this plugin and its current version.
 *
 * @since      1.0.0
 * @package    Thinker_Language_Translator
 * @subpackage Thinker_Language_Translator/includes
 * @author     ThinkerWebDesign
 */
class Thinker_Language_Translator {

	/**
	 * Maintains and registers all hooks that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Thinker_Language_Translator_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Defines the core functionality of the plugin.
	 *
	 * Sets the plugin name and the plugin version used throughout the plugin.
	 * Loads the dependencies, defines the locale, and sets the hooks for the
	 * admin area and the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'THINKER_TRANSLATOR_VERSION' ) ) {
			$this->version = THINKER_TRANSLATOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'thinker-language-translator';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Loads the required dependencies for this plugin.
	 *
	 * Includes the following files that make up the plugin:
	 *
	 * - Thinker_Language_Translator_Loader. Orchestrates the hooks of the plugin.
	 * - Thinker_Language_Translator_i18n. Defines internationalization.
	 * - Thinker_Language_Translator_Admin. Defines all admin area hooks.
	 * - Thinker_Language_Translator_Public. Defines all public-facing hooks.
	 *
	 * Creates an instance of the loader used to register the hooks with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Orchestrates the actions and filters of the core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-thinker-language-translator-loader.php';

		/**
		 * Defines internationalization functionality of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-thinker-language-translator-i18n.php';

		/**
		 * Defines all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-thinker-language-translator-admin.php';

		/**
		 * Defines all actions that occur in the public-facing side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-thinker-language-translator-public.php';

		$this->loader = new Thinker_Language_Translator_Loader();

	}

	/**
	 * Defines the locale for this plugin for internationalization.
	 *
	 * Uses the Thinker_Language_Translator_i18n class to set the domain
	 * and to register the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Thinker_Language_Translator_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Registers all of the hooks related to the admin area functionality.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Thinker_Language_Translator_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'plugin_action_links_thinker-language-translator/thinker-language-translator.php', $plugin_admin, 'add_settings_link' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'thinker_translator_add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'thinker_translator_settings_init' );

	}

	/**
	 * Registers all of the hooks related to the public-facing functionality.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Thinker_Language_Translator_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'thinker_translator_wp_footer' );
		$this->loader->add_action( 'init', $plugin_public, 'thinker_translator_register_shortcode' );

	}

	/**
	 * Runs the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Retrieves the name of the plugin.
	 *
	 * Returns the name of the plugin used to uniquely identify it within the context
	 * of WordPress and to define internationalization functionality.
	 *
	 * @since 1.0.0
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * References the class that orchestrates the hooks with the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Thinker_Language_Translator_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieves the version number of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Sanitizes style attributes for output.
	 *
	 * Sanitizes and strips extra whitespace.
	 *
	 * @since     1.0.0
	 *
	 * @param  string $string Input to be sanitized.
	 * @return string         The sanitized output.
	 */
	public function sanitize_style( $string ) {

		$sting = esc_attr( $string );
		$sting = trim( $string );
		$sting = preg_replace( '/\s+/', ' ', $string );
		return $string;

	}

	/**
	 * Preserves zero values when used as callback for array_filter.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $val Value to filter.
	 * @return string      Value to filter.
	 */
	public function preserve_zero_values( $val ) {

		if ( is_array( $val ) ) {
			return $val;
		} else {
			return strlen( $val );
		}

	}

	/**
	 * Validates settings page options.
	 *
	 * @since     1.0.0
	 *
	 * @param  array $input Settings options to Validate.
	 * @return array        Validated settings options.
	 */
	public function validate_options( $input ) {

		// Options keys that can only have numbers as values.
		$number_only_values = array(
			'thinker_translator_more_wide',
			'thinker_translator_inline',
			'thinker_translator_cache',
			'thinker_translator_png',
			'thinker_translator_html',
			'thinker_translator_hover',
			'thinker_translator_icon_size',
			'thinker_translator_hover_bg',
			'thinker_translator_height',
			'thinker_translator_width',
			'thinker_translator_floating',
			'thinker_translator_footer',
		);

		$output = array();

		if ( is_array( $input ) ) {

			// Removes all entries in options array that equal FALSE except if value is zero.
			$input = array_filter( $input, array( $this, 'preserve_zero_values' ) );

			foreach ( $input as $key => $value ) {

				if ( isset( $input[ $key ] ) ) {

					// Removes all values in options array that contain invalid characters.
					if ( in_array( $key, $number_only_values, true ) ) {

						// Sanitizes all number-only values in options array.
						if ( ! preg_match( '/[^0-9]/', $input[ $key ] ) ) {
							$output[ $key ] = $input[ $key ];
						}
					} elseif ( ! is_array( $input[ $key ] ) && ! preg_match( '/[<>&”‘"\']/', $input[ $key ] ) ) {

						// Sanitizes all string values in options array.
						$output[ $key ] = sanitize_text_field( wp_strip_all_tags( stripslashes( $input[ $key ] ) ) );

					} elseif ( is_array( $input[ $key ] ) ) {

						// Removes all entries equal to FALSE from array values in options array.
						$output[ $key ] = array_filter( $input[ $key ] );

					}
				}
			}
		}

		return apply_filters( 'validate_options', $output, $input );

	}

	/**
	 * Sanitizes the plugin settings options and sets the default values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Sanitized plugin settings options.
	 */
	public function get_thinker_translator_options() {

		$options = get_option( 'thinker_translator_settings' );

		$clean_options = $this->validate_options( $options );

		$defaults = array(
			'thinker_translator_text_color'        => '',
			'thinker_translator_lang'              => array( 'en', 'es', 'fr', 'ru', 'ja', 'zh-CN' ),
			'thinker_translator_display'           => 'flags',
			'thinker_translator_more'              => 'plus',
			'thinker_translator_more_wide'         => '',
			'thinker_translator_inline'            => '',
			'thinker_translator_cache'             => '',
			'thinker_translator_png'               => '',
			'thinker_translator_html'              => '',
			'thinker_translator_hover'             => '',
			'thinker_translator_icon_size'         => 30,
			'thinker_translator_icon_type'         => 'fa',
			'thinker_translator_icon_bg_image'     => '',
			'thinker_translator_icon_bg_color'     => '',
			'thinker_translator_hover_bg'          => '',
			'thinker_translator_height'            => 30,
			'thinker_translator_width'             => 50,
			'thinker_translator_border_radius'     => '50%',
			'thinker_translator_nation'            => 'us',
			'thinker_translator_hover_bg_image'    => '',
			'thinker_translator_hover_bg_color'    => '',
			'thinker_translator_shortcode_padding' => '5px',
			'thinker_translator_floating'          => '',
			'thinker_translator_padding'           => '0',
			'thinker_translator_top'               => '50px',
			'thinker_translator_bottom'            => 'auto',
			'thinker_translator_left'              => '50px',
			'thinker_translator_right'             => 'auto',
			'thinker_translator_footer'            => '',
			'thinker_translator_footer_padding'    => '5px',
			'thinker_translator_footer_align'      => 'center',
			'thinker_translator_footer_bg_color'   => '',
			'thinker_translator_footer_bg_style'   => '',
		);

		$clean_options = wp_parse_args( $clean_options, $defaults );

		if ( is_array( $clean_options['thinker_translator_lang'] ) ) {
			asort( $clean_options['thinker_translator_lang'] );
		}

		return $clean_options;

	}

	/**
	 * Handles all the plugin output.
	 *
	 * Handles all functionality of each translator output.
	 *
	 * @since 1.0.0
	 *
	 * @global string $post
	 *
	 * @param int    $tlt_count Iteration number of translator output.
	 * @param string $tlt_class Class of translator location. Accepts
	 *                                'thinkerLangT-Float',
	 *                                'thinkerLangT-Footer',
	 *                                'thinkerLangT-Shortcode',
	 *                                'thinkerLangT-Preview'.
	 * @return string           Translator output.
	 */
	public function thinker_translator_output( $tlt_count, $tlt_class ) {

		$tlt_lang_output     = '';
		$tlt_body_class      = '';
		$tlt_hover_bg_styles = '';
		$tlt_body_styles     = '';
		$tlt_drop_padding    = '';
		$tlt_drop_styles     = '';

		$options = $this->get_thinker_translator_options();

		if ( 1 === $tlt_count ) {
			$tlt_body_class = ' thinkerLangT-Count1';
		} else {
			$tlt_body_class = ' thinkerLangT-Not-first thinkerLangT-Count' . $tlt_count;
		}

		if ( $options['thinker_translator_text_color'] ) {
			$tlt_text_color    = ' color: ' . $options['thinker_translator_text_color'] . ';';
			$tlt_more_bg_color = ' background-color: ' . $options['thinker_translator_text_color'] . ';';
		} else {
			$tlt_text_color    = '';
			$tlt_more_bg_color = '';
		}

		if ( 'thinkerLangT-Shortcode' === $tlt_class ) {

			if ( $options['thinker_translator_shortcode_padding'] ) {
				$tlt_shortcode_padding = trim( $options['thinker_translator_shortcode_padding'] );
				if ( $tlt_shortcode_padding && ctype_digit( $tlt_shortcode_padding ) ) {
					$tlt_shortcode_padding = $tlt_shortcode_padding . 'px';
				}
				$tlt_body_styles = $tlt_body_styles . ' padding: ' . $tlt_shortcode_padding . ';';
			} else {
				$tlt_body_styles = $tlt_body_styles . ' padding: 5px;';
			}
		}

		if ( 'thinkerLangT-Preview' === $tlt_class ) {

			$tlt_class = 'thinkerLangT-Shortcode';
			$tlt_js    = '';

			// Sets dummy HTML output for Google drop down in admin preview.
			$tlt_google = '<div id="thinkerlangt-g" class="thinkerlangt-g"><div class="skiptranslate goog-te-gadget" dir="ltr" style=""><div id=":0.targetLanguage"><select class="goog-te-combo"><option value="">Select Language</option><option value="af">Afrikaans</option><option value="sq">Albanian</option><option value="am">Amharic</option><option value="ar">Arabic</option><option value="hy">Armenian</option><option value="az">Azerbaijani</option><option value="eu">Basque</option><option value="be">Belarusian</option><option value="bn">Bengali</option><option value="bs">Bosnian</option><option value="bg">Bulgarian</option><option value="ca">Catalan</option><option value="ceb">Cebuano</option><option value="ny">Chichewa</option><option value="zh-CN">Chinese (Simplified)</option><option value="zh-TW">Chinese (Traditional)</option><option value="co">Corsican</option><option value="hr">Croatian</option><option value="cs">Czech</option><option value="da">Danish</option><option value="nl">Dutch</option><option value="eo">Esperanto</option><option value="et">Estonian</option><option value="tl">Filipino</option><option value="fi">Finnish</option><option value="fr">French</option><option value="fy">Frisian</option><option value="gl">Galician</option><option value="ka">Georgian</option><option value="de">German</option><option value="el">Greek</option><option value="gu">Gujarati</option><option value="ht">Haitian Creole</option><option value="ha">Hausa</option><option value="haw">Hawaiian</option><option value="iw">Hebrew</option><option value="hi">Hindi</option><option value="hmn">Hmong</option><option value="hu">Hungarian</option><option value="is">Icelandic</option><option value="ig">Igbo</option><option value="id">Indonesian</option><option value="ga">Irish</option><option value="it">Italian</option><option value="ja">Japanese</option><option value="jw">Javanese</option><option value="kn">Kannada</option><option value="kk">Kazakh</option><option value="km">Khmer</option><option value="ko">Korean</option><option value="ku">Kurdish (Kurmanji)</option><option value="ky">Kyrgyz</option><option value="lo">Lao</option><option value="la">Latin</option><option value="lv">Latvian</option><option value="lt">Lithuanian</option><option value="lb">Luxembourgish</option><option value="mk">Macedonian</option><option value="mg">Malagasy</option><option value="ms">Malay</option><option value="ml">Malayalam</option><option value="mt">Maltese</option><option value="mi">Maori</option><option value="mr">Marathi</option><option value="mn">Mongolian</option><option value="my">Myanmar (Burmese)</option><option value="ne">Nepali</option><option value="no">Norwegian</option><option value="ps">Pashto</option><option value="fa">Persian</option><option value="pl">Polish</option><option value="pt">Portuguese</option><option value="pa">Punjabi</option><option value="ro">Romanian</option><option value="ru">Russian</option><option value="sm">Samoan</option><option value="gd">Scots Gaelic</option><option value="sr">Serbian</option><option value="st">Sesotho</option><option value="sn">Shona</option><option value="sd">Sindhi</option><option value="si">Sinhala</option><option value="sk">Slovak</option><option value="sl">Slovenian</option><option value="so">Somali</option><option value="es">Spanish</option><option value="su">Sundanese</option><option value="sw">Swahili</option><option value="sv">Swedish</option><option value="tg">Tajik</option><option value="ta">Tamil</option><option value="te">Telugu</option><option value="th">Thai</option><option value="tr">Turkish</option><option value="uk">Ukrainian</option><option value="ur">Urdu</option><option value="uz">Uzbek</option><option value="vi">Vietnamese</option><option value="cy">Welsh</option><option value="xh">Xhosa</option><option value="yi">Yiddish</option><option value="yo">Yoruba</option><option value="zu">Zulu</option></select></div>Powered by <span style="white-space:nowrap"><a class="goog-logo-link" href="https://translate.google.com" target="_blank"><img src="https://www.gstatic.com/images/branding/googlelogo/1x/googlelogo_color_42x16dp.png" style="padding-right: 3px" alt="Google Translate" width="37px" height="14px">Translate</a></span></div></div>
			';

		} else {
			$tlt_js     = '<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=ThinkerGoogleLanguageTranslatorInit"></script>';
			$tlt_google = ' <div id="thinkerlangt-g" class="thinkerlangt-g"></div>';
		}

		if ( 'thinkerLangT-Footer' === $tlt_class ) {

			if ( $options['thinker_translator_footer_padding'] ) {
				$tlt_footer_padding = trim( $options['thinker_translator_footer_padding'] );
				if ( $tlt_footer_padding && ctype_digit( $tlt_footer_padding ) ) {
					$tlt_footer_padding = $tlt_footer_padding . 'px';
				}
				$tlt_body_styles = $tlt_body_styles . ' padding: ' . $tlt_footer_padding . ';';
			} else {
				$tlt_body_styles = $tlt_body_styles . ' padding: 5px;';
			}

			if ( $options['thinker_translator_footer_align'] ) {
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-FooterAlign-' . $options['thinker_translator_footer_align'];
			}

			if ( $options['thinker_translator_footer_bg_style'] ) {
				$tlt_footer_bg_style = trim( $options['thinker_translator_footer_bg_style'] );
				if ( strpos( $tlt_footer_bg_style, 'http' ) === 0 || strpos( $tlt_footer_bg_style, '/' ) === 0 ) {
					$tlt_footer_bg_style = 'url(' . $tlt_footer_bg_style . ')';
				}
				$tlt_body_styles = $tlt_body_styles . ' background: ' . $tlt_footer_bg_style . ';';
			}
			if ( $options['thinker_translator_footer_bg_color'] ) {
				$tlt_body_styles = $tlt_body_styles . ' background-color:' . $options['thinker_translator_footer_bg_color'] . ';';
			}
		}

		if ( 'thinkerLangT-Float' === $tlt_class ) {

			if ( $options['thinker_translator_top'] ) {
				$tlt_top = trim( $options['thinker_translator_top'] );
				if ( $tlt_top && ctype_digit( $tlt_top ) ) {
					$tlt_top = $tlt_top . 'px';
				}
			} else {
				$tlt_top = 'auto';
			}
			if ( $options['thinker_translator_bottom'] ) {
				$tlt_bottom = trim( $options['thinker_translator_bottom'] );
				if ( $tlt_bottom && ctype_digit( $tlt_bottom ) ) {
					$tlt_bottom = $tlt_bottom . 'px';
				}
			} else {
				$tlt_bottom = 'auto';
			}
			if ( empty( $options['thinker_translator_top'] ) && empty( $options['thinker_translator_bottom'] ) ) {
				$tlt_top        = '50px';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-BottomAuto';
			} elseif ( 'auto' === $options['thinker_translator_top'] && 'auto' === $options['thinker_translator_bottom'] ) {
				$tlt_top        = '50px';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-BottomAuto';
			} elseif ( empty( $options['thinker_translator_top'] ) && 'auto' !== $options['thinker_translator_bottom'] ) {
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-TopAuto';
			} elseif ( 'auto' === $options['thinker_translator_top'] ) {
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-TopAuto';
				if ( empty( $options['thinker_translator_bottom'] ) ) {
					$tlt_bottom = '50px';
				}
			} else {
				$tlt_bottom     = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-BottomAuto';
				if ( empty( $options['thinker_translator_top'] ) ) {
					$tlt_top = '50px';
				}
			}

			if ( $options['thinker_translator_left'] ) {
				$tlt_left = trim( $options['thinker_translator_left'] );
				if ( $tlt_left && ctype_digit( $tlt_left ) ) {
					$tlt_left = $tlt_left . 'px';
				}
			} else {
				$tlt_left = 'auto';
			}
			if ( $options['thinker_translator_right'] ) {
				$tlt_right = trim( $options['thinker_translator_right'] );
				if ( $tlt_right && ctype_digit( $tlt_right ) ) {
					$tlt_right = $tlt_right . 'px';
				}
			} else {
				$tlt_right = 'auto';
			}
			if ( empty( $options['thinker_translator_left'] ) && empty( $options['thinker_translator_right'] ) ) {
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-Center';
			} elseif ( empty( $options['thinker_translator_left'] ) && empty( $options['thinker_translator_right'] ) ) {
				$tlt_left       = '50px';
				$tlt_right      = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-RightAuto';
			} elseif ( 'auto' === $options['thinker_translator_left'] && 'auto' === $options['thinker_translator_right'] ) {
				$tlt_left       = '50px';
				$tlt_right      = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-RightAuto';
			} elseif ( empty( $options['thinker_translator_left'] ) && 'auto' !== $options['thinker_translator_right'] ) {
				$tlt_left       = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-LeftAuto';
			} elseif ( 'auto' === $options['thinker_translator_left'] ) {
				$tlt_left       = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-LeftAuto';
				if ( empty( $options['thinker_translator_right'] ) ) {
					$tlt_right = '50px';
				}
			} else {
				$tlt_right      = 'auto';
				$tlt_body_class = $tlt_body_class . ' thinkerLangT-RightAuto';
				if ( empty( $options['thinker_translator_left'] ) ) {
					$tlt_left = '50px';
				}
			}

			if ( $options['thinker_translator_padding'] ) {
				$tlt_padding = trim( $options['thinker_translator_padding'] );
				if ( $tlt_padding && ctype_digit( $tlt_padding ) ) {
					$tlt_padding = $tlt_padding . 'px';
				}
			} else {
				$tlt_padding = '0';
			}

			$tlt_body_styles = ' top:' . $tlt_top . '; bottom:' . $tlt_bottom . '; left:' . $tlt_left . '; right:' . $tlt_right . '; padding:' . $tlt_padding . ';';

		}

		if ( $options['thinker_translator_hover'] && empty( $options['thinker_translator_inline'] ) ) {

			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Hover';
			if ( $options['thinker_translator_icon_size'] ) {
				$tlt_icon_size = $options['thinker_translator_icon_size'];
			} else {
				$tlt_icon_size = '30';
			}
			$tlt_icon_size_style = ' width: ' . $tlt_icon_size . 'px; height: ' . $tlt_icon_size . 'px; ';
			$tlt_icon_size_font  = 'font-size: ' . ( $tlt_icon_size * 0.68 ) . 'px;';

			if ( $options['thinker_translator_icon_bg_image'] ) {
				$tlt_icon_bg_image = 'background-image: url(' . $options['thinker_translator_icon_bg_image'] . ');';
			} else {
				$tlt_icon_bg_image = '';
			}
			if ( $options['thinker_translator_icon_bg_color'] ) {
				$tlt_icon_bg_color = 'background-color: ' . $options['thinker_translator_icon_bg_color'] . ';';
			} else {
				$tlt_icon_bg_color = '';
			}

			if ( $options['thinker_translator_hover_bg'] ) {

				if ( $options['thinker_translator_width'] ) {
					$tlt_width = $options['thinker_translator_width'];
				} else {
					$tlt_width = '50';
				}
				$tlt_drop_padding_width = $tlt_width;
				if ( $options['thinker_translator_height'] ) {
					$tlt_height = $options['thinker_translator_height'];
				} else {
					$tlt_height = '30';
				}
				$tlt_drop_padding_height = $tlt_height;

				if ( $options['thinker_translator_hover_bg_image'] ) {
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' background-image: url(' . $options['thinker_translator_hover_bg_image'] . ');';
				} elseif ( 'none' === $options['thinker_translator_nation'] ) {
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' background-image: none;';
				} elseif ( $options['thinker_translator_nation'] ) {
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' background-image: url(' . plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/n/' . $options['thinker_translator_nation'];
					$tlt_body_class      = $tlt_body_class . ' thinkerLangT-FlagBG';
					if ( $options['thinker_translator_png'] ) {
						$tlt_hover_bg_styles = $tlt_hover_bg_styles . '.png);';
					} else {
						$tlt_hover_bg_styles = $tlt_hover_bg_styles . '.svg);';
					}
				}
				if ( $options['thinker_translator_hover_bg_color'] ) {
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' background-color: ' . $options['thinker_translator_hover_bg_color'] . ';';
				}

				if ( $options['thinker_translator_border_radius'] ) {
					$tlt_border_radius = $options['thinker_translator_border_radius'];
					if ( $tlt_border_radius && ctype_digit( $tlt_border_radius ) ) {
						$tlt_border_radius = $tlt_border_radius . 'px';
					}
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' border-radius: ' . $tlt_border_radius . ';';
				} else {
					$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' border-radius: 50%;';
				}

				$tlt_hover_bg_styles = $tlt_hover_bg_styles . ' width: ' . $tlt_width . 'px; height: ' . $tlt_height . 'px;';
				$tlt_hover_bg_styles = ' style="' . $this->sanitize_style( $tlt_hover_bg_styles ) . '"';

			} else {
				$tlt_width               = '';
				$tlt_height              = '';
				$tlt_body_class          = $tlt_body_class . ' thinkerLangT-NoIconBG';
				$tlt_hover_bg_styles     = '';
				$tlt_drop_padding_width  = $tlt_icon_size;
				$tlt_drop_padding_height = $tlt_icon_size;
			}

			$tlt_drop_padding_left = $tlt_drop_padding_width / 2;
			$tlt_drop_padding_left = ( $tlt_drop_padding_left * $tlt_drop_padding_left ) / 2;
			$tlt_drop_padding_left = ( $tlt_drop_padding_width / 2 ) + ( sqrt( $tlt_drop_padding_left ) );
			$tlt_drop_padding_left = round( $tlt_drop_padding_left ) + 2;

			$tlt_drop_padding_top = $tlt_drop_padding_height / 2;
			$tlt_drop_padding_top = ( $tlt_drop_padding_top * $tlt_drop_padding_top ) / 2;
			$tlt_drop_padding_top = ( $tlt_drop_padding_height / 2 ) + ( sqrt( $tlt_drop_padding_top ) );
			$tlt_drop_padding_top = round( $tlt_drop_padding_top ) + 2;

			$tlt_drop_padding = ' padding: ' . $tlt_drop_padding_top . 'px ' . $tlt_drop_padding_left . 'px ' . $tlt_drop_padding_top . 'px ' . $tlt_drop_padding_left . 'px;';

			if ( 'fa' === $options['thinker_translator_icon_type'] ) {
				$tlt_icon_span = '<span class="notranslate" style="' . $this->sanitize_style( $tlt_icon_size_font ) . '">A</span>';
				if ( empty( $tlt_icon_bg_image ) ) {
					$tlt_icon_bg_image = ' background-image:none;';
				}
			} else {
				$tlt_icon_span = '';
			}
			if ( 'none' === $options['thinker_translator_icon_type'] ) {
				$tlt_icon_inner = '';
			} else {

				$tlt_icon_styles = $tlt_icon_size_style . $tlt_text_color . $tlt_icon_bg_image . $tlt_icon_bg_color;
				$tlt_icon_inner  = '
					<div class="thinkerlangt-icon" style="' . $this->sanitize_style( $tlt_icon_styles ) . '">
						' . $tlt_icon_span . '
					</div>
				';
			}

			$tlt_icon_output = '
				<div class="thinkerlangt-bg"' . $tlt_hover_bg_styles . '>
					<div class="thinkerlangt-bgin1">
						<div class="thinkerlangt-bgin2">
							' . $tlt_icon_inner . '
						</div>
					</div>
				</div>
			';

		} else {
			$tlt_body_class      = $tlt_body_class . ' thinkerLangT-NoHover';
			$tlt_icon_size_style = '';
			$tlt_icon_size_font  = '';
			$tlt_icon_output     = '';
			$tlt_drop_padding    = '';
		}

		if ( $options['thinker_translator_lang'] && count( array_filter( $options['thinker_translator_lang'] ) ) ) {
			$tlt_lang = array_filter( $options['thinker_translator_lang'] );
		}

		$tlt_lang_keys = array(
			'af'    => 'Afrikaans',
			'sq'    => 'Albanian',
			'am'    => 'Amharic',
			'ar'    => 'Arabic',
			'hy'    => 'Armenian',
			'az'    => 'Azerbaijani',
			'eu'    => 'Basque',
			'be'    => 'Belarusian',
			'bn'    => 'Bengali',
			'bs'    => 'Bosnian',
			'bg'    => 'Bulgarian',
			'ca'    => 'Catalan',
			'ceb'   => 'Cebuano',
			'ny'    => 'Chichewa',
			'zh-CN' => 'Chinese',
			'zh-TW' => 'Chinese (Traditional)',
			'co'    => 'Corsican',
			'hr'    => 'Croatian',
			'cs'    => 'Czech',
			'da'    => 'Danish',
			'nl'    => 'Dutch',
			'en'    => 'English',
			'eo'    => 'Esperanto',
			'et'    => 'Estonian',
			'tl'    => 'Filipino',
			'fi'    => 'Finnish',
			'fr'    => 'French',
			'fy'    => 'Frisian',
			'gl'    => 'Galician',
			'ka'    => 'Georgian',
			'de'    => 'German',
			'el'    => 'Greek',
			'gu'    => 'Gujarati',
			'ht'    => 'Haitian Creole',
			'ha'    => 'Hausa',
			'haw'   => 'Hawaiian',
			'iw'    => 'Hebrew',
			'hi'    => 'Hindi',
			'hmn'   => 'Hmong',
			'hu'    => 'Hungarian',
			'is'    => 'Icelandic',
			'ig'    => 'Igbo',
			'id'    => 'Indonesian',
			'ga'    => 'Irish',
			'it'    => 'Italian',
			'ja'    => 'Japanese',
			'jw'    => 'Javanese',
			'kn'    => 'Kannada',
			'kk'    => 'Kazakh',
			'km'    => 'Khmer',
			'ko'    => 'Korean',
			'ku'    => 'Kurdish (Kurmanji)',
			'ky'    => 'Kyrgyz',
			'lo'    => 'Lao',
			'la'    => 'Latin',
			'lv'    => 'Latvian',
			'lt'    => 'Lithuanian',
			'lb'    => 'Luxembourgish',
			'mk'    => 'Macedonian',
			'mg'    => 'Malagasy',
			'ms'    => 'Malay',
			'ml'    => 'Malayalam',
			'mt'    => 'Maltese',
			'mi'    => 'Maori',
			'mr'    => 'Marathi',
			'mn'    => 'Mongolian',
			'my'    => 'Myanmar (Burmese)',
			'ne'    => 'Nepali',
			'no'    => 'Norwegian',
			'ps'    => 'Pashto',
			'fa'    => 'Persian',
			'pl'    => 'Polish',
			'pt'    => 'Portuguese',
			'pa'    => 'Punjabi',
			'ro'    => 'Romanian',
			'ru'    => 'Russian',
			'sm'    => 'Samoan',
			'gd'    => 'Scots Gaelic',
			'sr'    => 'Serbian',
			'st'    => 'Sesotho',
			'sn'    => 'Shona',
			'sd'    => 'Sindhi',
			'si'    => 'Sinhala',
			'sk'    => 'Slovak',
			'sl'    => 'Slovenian',
			'so'    => 'Somali',
			'es'    => 'Spanish',
			'su'    => 'Sundanese',
			'sw'    => 'Swahili',
			'sv'    => 'Swedish',
			'tg'    => 'Tajik',
			'ta'    => 'Tamil',
			'te'    => 'Telugu',
			'th'    => 'Thai',
			'tr'    => 'Turkish',
			'uk'    => 'Ukrainian',
			'ur'    => 'Urdu',
			'uz'    => 'Uzbek',
			'vi'    => 'Vietnamese',
			'cy'    => 'Welsh',
			'xh'    => 'Xhosa',
			'yi'    => 'Yiddish',
			'yo'    => 'Yoruba',
			'zu'    => 'Zulu',
		);

		if ( $options['thinker_translator_png'] ) {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-PNG';
		} else {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-SVG';
		}

		if ( 'both' === $options['thinker_translator_display'] ) {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Both';
		} elseif ( 'text' === $options['thinker_translator_display'] ) {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Text';
		} else {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Flags';
		}

		if ( $options['thinker_translator_inline'] ) {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Inline';
		}

		$tlt_more_button_wide  = '';
		$tlt_more_button_small = '';

		if ( $options['thinker_translator_more'] ) {
			$tlt_body_class = $tlt_body_class . ' thinkerLangT-More-' . $options['thinker_translator_more'];
			if ( 1 !== $tlt_count ) {
				$tlt_js     = '';
				$tlt_google = '';
			} else {

				if ( 'none' !== $options['thinker_translator_more'] ) {
					$tlt_body_class  = $tlt_body_class . ' thinkerLangT-G thinkerLangT-G-close';
					$tlt_more_button = '
						<div class="thinkerlangt-more">
							<a href="javascript:void(0)" style="' . $this->sanitize_style( $tlt_text_color ) . '" class="thinkerlangt-pull notranslate" title="Choose another language">
								<span class="thinkerlangt-mplus"><span style="' . $this->sanitize_style( $tlt_more_bg_color ) . '"></span></span><span class="thinkerlangt-mtext">More</span>
							</a>
						</div>
					';
					if ( $options['thinker_translator_more_wide'] && empty( $options['thinker_translator_inline'] ) ) {
						$tlt_body_class       = $tlt_body_class . ' thinkerLangT-More-wide';
						$tlt_more_button_wide = $tlt_more_button;
					} else {
						$tlt_body_class        = $tlt_body_class . ' thinkerLangT-More-small';
						$tlt_more_button_small = $tlt_more_button;
					}
				}
			}
		}

		if ( $options['thinker_translator_html'] ) {

			$tlt_body_class = $tlt_body_class . ' thinkerLangT-Html';
			if ( $options['thinker_translator_cache'] ) {
				$tlt_url = rawurlencode( get_home_url() );
			} else {
				if ( is_home() || is_admin() ) {
					$tlt_url = rawurlencode( get_home_url() );
				} else {
					global $post;
					$tlt_url = rawurlencode( get_permalink( $post->ID ) );
				}
			}

			// If question mark exists then use ampersand.
			if ( strpos( $tlt_url, '?' ) !== false ) {
				$tlt_url = $tlt_url . '&tltranslated=1';
			} else {
				$tlt_url = $tlt_url . '?tltranslated=1';
			}
			$tlt_url1 = 'https://translate.google.com/translate?tl=';
			$tlt_url2 = '&u=' . $tlt_url;

			foreach ( $tlt_lang as $tlt_lang_value ) {
				$tlt_lang_key    = $tlt_lang_keys[ $tlt_lang_value ];
				$tlt_lang_output = $tlt_lang_output . '
					<a href="' . $tlt_url1 . $tlt_lang_value . $tlt_url2 . '" rel="nofollow" title="' . $tlt_lang_key . '" class="notranslate flag ' . $tlt_lang_value . '" style="' . $this->sanitize_style( $tlt_text_color ) . '">
						<span>' . $tlt_lang_key . '</span>
					</a>
				';
			}
			$tlt_more_button_wide  = '';
			$tlt_more_button_small = '';
			$tlt_js                = '';
			$tlt_google            = '';

		} else {

			foreach ( $tlt_lang as $tlt_lang_value ) {
				$tlt_lang_key    = $tlt_lang_keys[ $tlt_lang_value ];
				$tlt_lang_output = $tlt_lang_output . '<a href="javascript:void(0)" title="' . $tlt_lang_key . '" class="notranslate flag ' . $tlt_lang_value . '" style="' . $this->sanitize_style( $tlt_text_color ) . '"><span>' . $tlt_lang_key . '</span></a>';
			}
		}

		// Sanitize remaining style attributes for output.
		$tlt_body_styles = ' style="' . $this->sanitize_style( $tlt_body_styles ) . '"';
		if ( $tlt_drop_padding ) {
			$tlt_drop_styles = ' style="' . $this->sanitize_style( $tlt_drop_padding ) . '"';
		}

		// Sanitize remaining class attribute for output.
		$tlt_body_class = esc_attr( 'thinkerlangt-body     ' . $tlt_class . ' ' . $tlt_body_class . ' thinkerLangT-DropClose' );
		$tlt_body_class = trim( $tlt_body_class );
		$tlt_body_class = preg_replace( '/\s+/', ' ', $tlt_body_class );

		$tlt_output = '
			<div class="' . $tlt_body_class . '"' . $tlt_body_styles . ' tabindex="1">
				<div class="thinkerlangt-bin" tabindex="0">
					<div class="thinkerlangt-bin2">

						' . $tlt_icon_output . '

						<div class="thinkerlangt-drop"' . $tlt_drop_styles . ' tabindex="0">
							<div class="thinkerlangt-dtop">
								<div class="thinkerlangt-links">
									<div>
										<div>
											' . $tlt_lang_output . '
											' . $tlt_more_button_small . '
										</div>
									</div>
								</div>
								' . $tlt_more_button_wide . '
							</div>
							' . $tlt_google . '
						</div>

						<div class="clear"></div>

					</div>
				</div>
			</div>
			' . $tlt_js . '
		';
		return $tlt_output;

	}

}
