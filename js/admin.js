jQuery(document).ready(function($) {
	"use strict";
	
	
	// Trigger stuff on change
	var modalIsVisible = false;
	setInterval( function() {
		$ = jQuery;
		if ( modalIsVisible !== $('.media-modal').is(':visible') ) {
			if ( ! modalIsVisible ) {
				$('.setting.gambit-gallery').find('select, input').trigger('change');
			}
			modalIsVisible = $('.media-modal').is(':visible');
		}
	}, 500 );
	
	
	// Hide the size because we won't use it
	// Show gambit gallery settings if enabled
	$('body').on('change', '[data-setting="gambit_gallery"]', function() {
		if ( $(this).val() === 'enabled' ) {
			$('.gallery-settings .setting.size').css('opacity', '.5');
			$('.setting.gambit-gallery').filter(':not(:eq(0))').show();
		} else {
			$('.gallery-settings .setting.size').css('opacity', '1');
			$('.setting.gambit-gallery').filter(':not(:eq(0))').hide();
		}
	});
});