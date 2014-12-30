jQuery(document).ready(function($) {
	"use strict";
	
	
	// Trigger stuff when the modal is visible
	setInterval( function() {
		$ = jQuery;
		if ( $('.media-modal').is(':visible') && $('.gallery-settings').length ) {
			$('[data-setting="gambit_gallery"]').trigger('change');
		}
	}, 500 );
	
	
	// Hide the size because we won't use it
	// Show gambit gallery settings if enabled
	$('body').on('change', '[data-setting="gambit_gallery"]', function() {
		if ( $(this).val() === 'enabled' ) {
			$('.gallery-settings [data-setting="link"]').css('opacity', '.4');
			$('.gallery-settings [data-setting="size"]').css('opacity', '.4');
			$('.gallery-settings .setting.gambit-gallery').filter(':not(:eq(0)):not(:visible)').show();
		} else {
			$('.gallery-settings [data-setting="link"]').css('opacity', '1');
			$('.gallery-settings [data-setting="size"]').css('opacity', '1');
			$('.gallery-settings .setting.gambit-gallery').filter(':not(:eq(0)):visible').hide();
		}
	});
});