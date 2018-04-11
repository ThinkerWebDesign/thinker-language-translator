(function( $ ) {
	'use strict';

	/**
	 * All public-facing JavaScript.
	 *
	 * @link   http://thinkerwebdesign.com/thinker-language-translator-plugin/
	 * @file   All public-facing JavaScript.
	 * @author ThinkerWebDesign.
	 * @since  1.0.0
	 *
	 */

	// Is available after the document is loaded.
	$(function() {

		// Opens and closes the thinkerLangT-Drop class.
		$('.thinkerLangT-Float.thinkerLangT-Hover .thinkerlangt-bg').on('click', function(e) {
			$('.thinkerlangt-body, .thinkerlangt-body *').blur();
			$('.thinkerLangT-Float')
				.toggleClass('thinkerLangT-DropClose')
				.toggleClass('thinkerLangT-DropOpen');
		});
		$('.thinkerLangT-Footer.thinkerLangT-Hover .thinkerlangt-bg').on('click', function(e) {
			$('.thinkerlangt-body, .thinkerlangt-body *').blur();
			$('.thinkerLangT-Footer')
				.toggleClass('thinkerLangT-DropClose')
				.toggleClass('thinkerLangT-DropOpen');
		});
		$('.thinkerLangT-Shortcode.thinkerLangT-Hover .thinkerlangt-bg').on('click', function(e) {
			$('.thinkerlangt-body, .thinkerlangt-body *').blur();
			$('.thinkerLangT-Shortcode')
				.toggleClass('thinkerLangT-DropClose')
				.toggleClass('thinkerLangT-DropOpen');
		});
		$('.thinkerLangT-Float.thinkerLangT-Hover .thinkerlangt-drop *').on('click', function(e) {
			$('.thinkerLangT-Float.thinkerLangT-DropClose')
				.removeClass('thinkerLangT-DropClose')
				.addClass('thinkerLangT-DropOpen');
		});
		$('.thinkerLangT-Footer.thinkerLangT-Hover .thinkerlangt-drop *').on('click', function(e) {
			$('.thinkerLangT-Footer.thinkerLangT-DropClose')
				.removeClass('thinkerLangT-DropClose')
				.addClass('thinkerLangT-DropOpen');
		});
		$('.thinkerLangT-Shortcode.thinkerLangT-Hover .thinkerlangt-drop *').on('click', function(e) {
			$('.thinkerLangT-Shortcode.thinkerLangT-DropClose')
				.removeClass('thinkerLangT-DropClose')
				.addClass('thinkerLangT-DropOpen');
		});

		// Opens and closes the thinkerLangT-G class.
		$('.thinkerlangt-body .thinkerlangt-pull').on('click', function(e) {
			$('.thinkerLangT-G')
				.toggleClass('thinkerLangT-G-close')
				.toggleClass('thinkerLangT-G-open');
		});

		// Closes the thinkerLangT-Drop and thinkerLangT-G classes on click of body.
		$('body').click(function(e) {
			if (
				! $(e.target).hasClass('thinkerlangt-body') &&
				$(e.target).parents('.thinkerlangt-body').length === 0
			) {
				$('.thinkerLangT-Hover.thinkerLangT-DropOpen')
					.removeClass('thinkerLangT-DropOpen')
					.addClass('thinkerLangT-DropClose');
				$('.thinkerLangT-G-open')
					.removeClass('thinkerLangT-G-open')
					.addClass('thinkerLangT-G-close');
			}
		});

		/*
		 * Most code below here is highly modified from Google Language Translator plugin
		 * created by Rob Myrick at:
		 * https://wordpress.org/plugins/google-language-translator/
		 * http://www.wp-studio.net/
		 * Google Language Translator Free Version is licensed under GNU/GPL license.
		 * Changes include making it compliant with WordPress JavaScript Coding Standards and
		 * JSHint like defining variables and adding them as parameters to functions
		 * as well as debugging errors and adding documentation.
		 */

		$(document.body).on('click', 'a.flag', function() {

			/**
			 * Handles functionality when selected language is the default language.
			 *
			 * @since  1.0.0
			 *
			 * @param  string    lang_text       Displayed name of selected language.
			 * @param  string    default_lang    Default language.
			 * @param  string    lang_prefix     Selected language.
			 */
			function l(lang_text, default_lang, lang_prefix) {
				doThinkerLanguageTranslator(
					lang_text,
					default_lang,
					lang_prefix,
					default_lang + '|' + default_lang
				);
			}

			/**
			 * Handles functionality when selected language is not the default language.
			 *
			 * @since  1.0.0
			 *
			 * @param  string    lang_text       Displayed name of selected language.
			 * @param  string    default_lang    Default language.
			 * @param  string    lang_prefix     Selected language.
			 */
			function n(lang_text, default_lang, lang_prefix) {
				doThinkerLanguageTranslator(
					lang_text,
					default_lang,
					lang_prefix,
					default_lang + '|' + lang_prefix
				);
			}

			var lang_text = $(this).attr('data-lang');
			var default_lang = 'en';
			var lang_prefix = $(this).attr('class').split(' ')[2];
			if (lang_prefix == default_lang) {
				l(lang_text, default_lang, lang_prefix);
			} else {
				n(lang_text, default_lang, lang_prefix);
			}
		});

	});

	/**
	* Creates and fires event to translate page.
	*
	* @since  1.0.0
	*
	* @param  string    lang_pair    Default language followed by selected language.
	* @param  string    lang_dest    Default language.
	*/
	function ThinkerLanguageTranslatorFireEvent(lang_pair, lang_dest) {
		var event;
		try {
			if (document.createEvent) {
				event = document.createEvent('HTMLEvents');
				event.initEvent(lang_dest, true, true);
				lang_pair.dispatchEvent(event);
			} else {
				event = document.createEventObject();
				lang_pair.fireEvent('on' + lang_dest, event);
			}
		} catch (e) {}
	}

	/**
	* Handles main functionality to translate page when a language is clicked.
	*
	* @since  1.0.0
	*
	* @param  string    lang_text       Displayed name of selected language.
	* @param  string    default_lang    Default language.
	* @param  string    lang_prefix     Selected language.
	* @param  string    lang_pair       Default language followed by selected language.
	*/
	function doThinkerLanguageTranslator(lang_text, default_lang, lang_prefix, lang_pair) {
		if (lang_pair.value) {
			lang_pair = lang_pair.value;
		}
		if (lang_pair == null) {
			return;
		}
		var lang_dest = lang_pair.split('|')[1];
		var event;
		var classic = $('.goog-te-combo');
		var simple = $('.goog-te-menu-frame:first');
		var simpleValue = simple.contents()
			.find('.goog-te-menu2-item span.text:contains(' + lang_text + ')');
		var i;
		if (classic.length === 0) {
			for (i = 0; i < simple.length; i++) {
				event = simple[i];
				//alert('Simple is active.');
			}
		} else {
			for (i = 0; i < classic.length; i++) {
				event = classic[i];
				//alert('Classic is active.');
			}
		}
		if (document.getElementById('thinkerlangt-g') != null) {
			if (classic.length !== 0) {
				if (lang_prefix != default_lang) {
					event.value = lang_dest;
					ThinkerLanguageTranslatorFireEvent(event, 'change');
				} else if ($('.goog-te-banner-frame:first').length > 0) {
					$('.goog-te-banner-frame:first').contents().find('.goog-close-link').get(0).click();
				}
			} else {
				event.value = lang_dest;
				if (lang_prefix != default_lang) {
					simpleValue.click();
				} else if ($('.goog-te-banner-frame:first').length > 0) {
					$('.goog-te-banner-frame:first').contents().find('.goog-close-link').get(0).click();
				}
			}
		}
	}

})( jQuery );

/**
* Initiates the Google Translator.
*
* @since   1.0.0
*
* @return  mixed   google_translate
*/
function ThinkerGoogleLanguageTranslatorInit() {
	'use strict';
	var google_translate = new google.translate
		.TranslateElement({pageLanguage: '', autoDisplay: false}, 'thinkerlangt-g');
	return google_translate;
}
