( function ( $ ) {
	$( document ).on( 'ready', function () {
			$('#adminmenu #toplevel_page_hostinger li a').click(function () {
				let action = $(this).attr("href").split('#').pop();

				$.ajax( {
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'hostinger_menu_action',
						nonce: hostingerContainer.nonce,
						location: 'side_bar',
						event_action: action
					},
					success: function ( data ) {
					},
					error: function ( xhr, status, error ) {
						console.log( 'AJAX request failed: ' + error );
					}
				} );
			});

		$('.hsr-wrapper__list .hsr-list__item').click(function () {
			let action = $(this).data('name');

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'hostinger_menu_action',
					nonce: hostingerContainer.nonce,
					location: 'home_page',
					event_action: action
				},
				success: function ( data ) {
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} );
		});

		$('.woocommerce-layout__main').on('click', '.woocommerce-profiler-setup-store__button', function () {
			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'hostinger_woocommerce_setup_store',
					nonce: hostingerContainer.nonce,
					event_action: 'wp_admin.woocommerce_onboarding.setup_store'
				},
				success: function ( data ) {
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} );
		});
	} );

} )( jQuery );
