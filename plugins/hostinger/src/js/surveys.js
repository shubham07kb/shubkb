import * as Survey from 'survey-jquery'

( function ( $ ) {
	$( document ).on( 'ready', function () {
		let type = 'ai_survey';
		let surveyWrapper = $( '.hts-survey-wrapper' );

		if ( surveyWrapper.hasClass( 'hts-woocommerce-csat' ) ) {
			type = 'woo_survey';
		}

		if ( surveyWrapper.hasClass( 'hts-ai-onboarding-csat' ) ) {
			type = 'ai_onboarding_survey';
		}

		if ( surveyWrapper.hasClass( 'hts-affiliate-csat' ) ) {
			type = 'affiliate_plugin_survey';
		}

		// Check if the cookie already exists
		function checkCookie() {
			var match = document.cookie.match(/(?:^|;)\s*HtsSkipSurvey\s*=\s*([^;]*)/);
			var cookieValue = match ? match[1] : "";
			return cookieValue !== undefined && cookieValue !== "";
		}

		$( ".hts-survey-wrapper .close-btn" ).click( function () {
			if ( ! checkCookie() ) {
				var expirationTime = new Date();
				expirationTime.setTime( expirationTime.getTime() + ( 86400 ) ) // 24Hours;
				document.cookie = "HtsSkipSurvey=1; expires=" + expirationTime.toUTCString() + "; path=/";
				surveyWrapper.hide();
			}
		} );

		if ( surveyWrapper.length && ! checkCookie() ) {
			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'hostinger_get_survey',
					nonce: hostingerContainer.nonce,
					type: type
				},
				dataType: 'json',
				success: function ( data ) {
					let questionsCount = $( '#hts-questionsLeft' );
					surveyWrapper.show();
					const survey = new Survey.Model( data );
					survey.focusFirstQuestionAutomatic = false;
					survey.render( "hostinger-feedback-survey" );
					survey.onComplete.add( onSurveyComplete );
					survey.onCurrentPageChanged.add( onPageChanged ); // Add this line
					survey.render( "surveyElement" );

					let answeredQuestions = 0;
					let totalQuestions = survey.getAllQuestions().length;
					if ( totalQuestions >= 2 ) {
						questionsCount.show()
						$( "#hts-allQuestions" ).html( totalQuestions );
					}

					function updateQuestionsLeft () {
						var remaining = answeredQuestions + 1;
						document.getElementById( "hts-currentQuestion" ).textContent = remaining;
					}

					function onPageChanged ( sender, options ) {
						if ( options.isNextPage || options.isPrevPage ) {
							answeredQuestions = survey.currentPageNo;
							updateQuestionsLeft();
						}
					}

					function onSurveyComplete ( sender ) {
						const results = JSON.stringify( sender.data );
						$( '#hts-questionsLeft' ).remove();
						hostinger_submit_survey( results, type );
					}

					updateQuestionsLeft();

				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} );
		}

		function hostinger_submit_survey ( survey_answers, type ) {
			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'hostinger_submit_survey',
					nonce: hostingerContainer.nonce,
					survey_results: survey_answers,
					type: type
				},
				dataType: 'json',
				success: function ( data ) {
					setTimeout(function (  ) {
						var expirationTime = new Date();
						expirationTime.setTime( expirationTime.getTime() + ( 86400 ) ); // 24Hours;
						document.cookie = "HtsSkipSurvey=1; expires=" + expirationTime.toUTCString() + "; path=/";
						surveyWrapper.hide('300');
					},1000);
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} );
		}

	} );

} )( jQuery );
