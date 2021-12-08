(function( $ ) {
	'use strict';

	/**
	 * All admin-facing JavaScript.
	 *
	 * @link   http://thinkerwebdesign.com/thinker-language-translator-plugin/
	 * @file   All admin-facing JavaScript.
	 * @author ThinkerWebDesign.
	 * @since  1.0.0
	 *
	 */

	// Is available after the document is loaded.
	$(function() {

		// Adds Color Picker to all inputs that have 'color-field' class.
		$('.color-field').wpColorPicker();

		// Toggles option if parent span is clicked.
		$('th .thinker_translator_settings_field span').on('click', function(e) {
			var selected = $(this).parent().attr('id').slice(0, -5);
			$('#' + selected).trigger('click');
		});

		// Shows dependent sections if active.
		$('#thinker_translator_float_settings').on('click', function(e) {
			$('#thinker_translator_settings_page')
				.toggleClass('thinker_translator_float_settings_active')
				.toggleClass('thinker_translator_float_settings_inactive');
		});
		$('#thinker_translator_footer_settings').on('click', function(e) {
			$('#thinker_translator_settings_page')
				.toggleClass('thinker_translator_footer_settings_active')
				.toggleClass('thinker_translator_footer_settings_inactive');
		});
		$('#thinker_translator_hover_settings').change(function(e) {
			$('#thinker_translator_settings_page')
				.toggleClass('thinker_translator_hover_settings_active')
				.toggleClass('thinker_translator_hover_settings_inactive');
		});
		$('#thinker_translator_hover_bg_settings').on('click', function(e) {
			$('#thinker_translator_settings_page')
				.toggleClass('thinker_translator_hover_bg_settings_active')
				.toggleClass('thinker_translator_hover_bg_settings_inactive');
		});

		// Closes the admin Preview.
		$(
			'.thinker_translator_settings_field select,' +
			'.thinker_translator_settings_field input,' +
			'.thinker_translator_settings_field button'
		).on('click', function(e) {
			$('.thinker_translator_preview_open')
				.removeClass('thinker_translator_preview_open')
				.addClass('thinker_translator_preview_close');
		});

		// Clears all fields.
		$('.thinker_translator_settings_form_clear').on('click', function(e) {
			$('.form-table input[type="text"], .form-table input[type="number"]').attr('value', '');
			$('.form-table input[type="checkbox"]').attr('checked', false);
			$('option:selected').removeAttr('selected');
			$('.thinker_translator_footer_settings_active')
				.removeClass('thinker_translator_footer_settings_active')
				.addClass('thinker_translator_footer_settings_inactive');
			$('.thinker_translator_float_settings_active')
				.removeClass('thinker_translator_float_settings_active')
				.addClass('thinker_translator_float_settings_inactive');
			$('.thinker_translator_hover_settings_active')
				.removeClass('thinker_translator_hover_settings_active')
				.addClass('thinker_translator_hover_settings_inactive');
			$('.thinker_translator_hover_bg_settings_active')
				.removeClass('thinker_translator_hover_bg_settings_active')
				.addClass('thinker_translator_hover_bg_settings_inactive');
			$('.thinker_translator_preview_open')
				.removeClass('thinker_translator_preview_open')
				.addClass('thinker_translator_preview_close');
			$('.thinker_translator_hover_settings_disabled')
				.removeClass('thinker_translator_hover_settings_disabled');
		});

		// Shows a Preview Only alert.
		$('.goog-te-combo option').mousedown(function(e) {
			e.preventDefault();
			alert('Preview Only');
		});

		// Disables Icon Method option if Inline Display is active.
		$('#thinker_translator_inline_settings').change(function(e) {
			if ($(this).is(':checked') ) {
				$('#thinker_translator_hover_settings').attr('checked', false);
				$('.thinker_translator_hover_settings_active')
					.removeClass('thinker_translator_hover_settings_active')
					.addClass('thinker_translator_hover_settings_inactive');
				$('#thinker_translator_settings_page')
					.addClass('thinker_translator_hover_settings_disabled');
			} else {
				$('.thinker_translator_hover_settings_disabled')
					.removeClass('thinker_translator_hover_settings_disabled');
			}
		});

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

	});

})( jQuery );
