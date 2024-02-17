( function ( $ ) {
	$( document ).on( 'ready', function () {
		let publishBtn = $( '.hsr-publish-btn' );
		let completedClass = 'completed';
		let gotItBtn = $( '.hsr-got-it-btn' );
		publishBtn.on( 'click', function ( e ) {
			e.preventDefault();
			$( '.hsr-modal' ).addClass( 'open' );
			$( 'body' ).addClass( 'modal-open' );
			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: hostingerContainer.url,
				data: {
					action: 'hostinger_publish_website',
					maintenance: 0,
					nonce: hostingerContainer.nonce,
				},
				success: function ( result ) {
					const previewBtn = $( '.hsr-preview-btn' );
					$( '.hsr-circular' ).addClass( 'hsr-hide' )
					$( '.hsr-success-circular' ).addClass( 'hsr-show' )
					$( '.hsr-publish-modal--footer' ).addClass( 'show' )
					$( '.hsr-publish-modal--body h3' ).text( result.data.title );
					$( '.hsr-publish-modal--body__description' ).text( result.data.description );
					$( '.hsr-publish-btn' ).addClass( 'hsr-preview' )
					previewBtn.addClass( 'hsr-preview' )
					previewBtn.text( result.data.content.btn.text )
					$( '.hsr-onboarding__title' ).text( result.data.content.title );
					$( '.hsr-onboarding__description' ).text( result.data.content.description );
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} )
		} )

		gotItBtn.on( 'click', function ( e ) {
			e.preventDefault();
			const element = $( this );
			const step = $( this ).data( 'step' );
			let remaining_tasks = $( '.hsr-onboarding-steps' ).data( 'remaining-tasks' );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: hostingerContainer.url,
				data: {
					action: 'hostinger_complete_onboarding_step',
					step: step,
					nonce: hostingerContainer.nonce,
				},
				success: function () {
					element.closest( '.hsr-onboarding-step--content' ).slideUp()
					element.parents( '.hsr-onboarding-step' )
						.find( '.hsr-onboarding-step--status' )
						.addClass( completedClass )

					if ( remaining_tasks > 0 ) {
						remaining_tasks = remaining_tasks - 1;
						$( '.hsr-onboarding-steps' ).data( 'remaining-tasks', remaining_tasks )

						if ( remaining_tasks === 0 ) {
							$( '.hsr-publish-btn' ).addClass( completedClass );
						}

					}
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} )
		} )

		$( ".hsr-promotional-banner .hsr-buttons .close-btn" ).click( function () {
			$.ajax( {
				type: 'post',
				url: hostingerContainer.url,
				data: {
					action: 'hostinger_hide_promotional_banner',
					nonce: hostingerContainer.nonce,
				},
				success: function () {
					$('.hsr-banner-container').hide();
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} )
		} );
	} );
} )( jQuery );
