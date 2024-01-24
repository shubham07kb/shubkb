(function ($) {

	$(document).on('ready', function () {
		function getParams(actionName) {
			return {
				type: 'post',
				url: ajaxurl,
				data: {
					'action': 'hostinger_identify_action',
					'action_name': actionName
				},
				dataType: 'json'
			};
		}

		const onboardingSteps = [
			{
				'event': 'click',
				'element': '#customize-control-custom_logo .button-add-media',
				'action': 'logo_upload',
			},
			{
				'event': 'click',
				'element': '#wp-media-grid .page-title-action, #file-form #plupload-browse-button',
				'action': 'image_upload',
			},
			{   'event': 'input',
				'element': '#_customize-input-blogname',
				'action': 'edit_site_title',
			},
			{   'event': 'click',
				'element': '#menu-settings .wp-has-submenu, #menu-settings.wp-has-submenu .wp-submenu .wp-first-item .wp-first-item',
				'action': 'edit_site_title',
			},
			{   'event': 'click',
				'element': '.hsr-onboarding-step.edit_description .hsr-btn.hsr-primary-btn,.post-type-post .postbox-container #publish, .post-type-post #the-list .row-title, .post-type-post #the-list .row-actions .edit a',
				'action': 'edit_description',
			},
			{   'event': 'click',
				'element': '#wp-admin-bar-new-post,.wp-admin.post-type-post .wrap .page-title-action, #hst-add_post',
				'action': 'add_post',
			},
			{   'event': 'click',
				'element': '#wp-admin-bar-new-page, .wp-admin.post-type-page .wrap .page-title-action, #hst-add_page',
				'action': 'add_page',
			},
			{   'event': 'click',
				'element': '.post-type-product #posts-filter .woocommerce-BlankState-cta.button-primary, .post-type-product .wrap .page-title-action',
				'action': 'add_product',
			},
		];

		onboardingSteps.forEach(step => {
			$(document).on(step.event, step.element, () => {
				$.ajax(getParams(step.action));
			});
		});

	});

})(jQuery);
