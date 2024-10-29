jQuery(document).ready(function($) {

	jQuery(".mwb_anawoo_excluded_roles").select2();
	jQuery(document).on( "click"  , ".mwb_anawoo_admin_close" , function(){
		const fade = { opacity: 0, transition: 'opacity 0.5s' };
		jQuery(".mwb_anawoo_admin_notice").css(fade).delay(100).slideUp();
	});

});



