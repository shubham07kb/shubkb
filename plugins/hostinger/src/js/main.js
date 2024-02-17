import './actions'
import './videos'

( function ( $ ) {
	$( document ).on( 'ready', function () {
		const openClass = 'open';
		let selectedTab = 'Home';
		const stepsTitle = $( '.hsr-onboarding-step--title' )
		const closeBtn = $( '.hsr-close-btn' );
		const navigationItem = $( '.hsr-list__item' );
		const knowledgeCard = $( '#card-knowledge' );
		const helpCard = $( '#card-help' );

		stepsTitle.on( 'click', function () {
			$( this ).find( '.hsr-onboarding-step--expand' ).toggleClass( openClass );
			$( this ).parent().find( '.hsr-onboarding-step--content' ).slideToggle( 200 );
		} )

		closeBtn.on( 'click', function () {
			$( '.hsr-modal' ).removeClass( 'open' );
			$( 'body' ).removeClass( 'modal-open' );
		} )

		navigationItem.click( function () {
			let clickedItem = $( this );

			$( '.hsr-list__item' ).removeClass( 'hsr-active' );

			clickedItem.addClass( 'hsr-active' );
			selectedTab = clickedItem.data( 'name' );

            $( '.hsr-tab-content' ).hide();
            $( '.hsr-tab-content[data-name="' + selectedTab + '"]' ).show();

			add_admin_menu_class();
		} );

        if( window.location.hash ) {
            let tab = $( '.hsr-onboarding-navbar .hsr-wrapper__list .hsr-list__item[data-name="' + window.location.hash.split('#')[1] + '"]' )

            if(tab.length > 0) {
                $( '.hsr-list__item' ).removeClass( 'hsr-active' );
                tab.addClass( 'hsr-active' );

                $( '.hsr-tab-content' ).hide();
                $( '.hsr-tab-content[data-name="' + window.location.hash.split('#')[1] + '"]' ).show();
            }
        } else {

            $( '.hsr-wrapper__list .hsr-list__item:first-of-type' ).addClass( 'hsr-active' );

        }

		helpCard.click( function () {
			window.open( 'https://hostinger.com/cpanel-login?r=jump-to/new-panel/section/help', '_blank' );
		} );
		knowledgeCard.click( function () {
			window.open( 'https://support.hostinger.com/en/?q=WordPress', '_blank' );
		} );

        $('body').on('click', '.hst-open-affiliate-tab', function(e) {
            e.preventDefault();

            $( 'li.hsr-list__item[data-name="amazon_affiliate"]' ).trigger( 'click' );
        } );

        $('body').on('click', '#hst-connect_affiliate_settings', function(e) {
            e.preventDefault();

            $( 'li.hsr-list__item[data-name="amazon_affiliate"]' ).trigger( 'click' );

            document.dispatchEvent(
                new CustomEvent('scrollToAffiliateSettings', {
                    bubbles: true
                })
            );
        } );

		document.querySelectorAll( '.hsr-playlist-item' ).forEach( function ( item ) {
			const firstItem = document.querySelector( '.hsr-playlist-item:first-child' );
			firstItem.classList.add( 'hsr-active-video' );
			firstItem.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'visible';
			item.addEventListener( 'click', function () {
				document.querySelectorAll( '.hsr-playlist-item.hsr-active-video' ).forEach( function ( selectedItem ) {
					selectedItem.classList.remove( 'hsr-active-video' );
					selectedItem.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'hidden';
				} );
				this.classList.add( 'hsr-active-video' );
				this.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'visible';
			} );
		} );

		function add_admin_menu_class () {
			const hostingerSubMenu = document.querySelectorAll( '#toplevel_page_hostinger .wp-submenu li' );

			if ( hostingerSubMenu ) {
				hostingerSubMenu.forEach( item => {
					item.classList.remove( 'current' );
				} );

                const tabSelectors = document.querySelectorAll( '.hsr-onboarding-navbar .hsr-wrapper__list .hsr-list__item' );

				tabSelectors.forEach( ( item, index ) => {
					if ( item.classList.contains( 'hsr-active' ) ) {
						if ( typeof hostingerSubMenu[ index + 1 ] !== "undefined" ) {
							hostingerSubMenu[ index + 1 ].classList.add( 'current' );
						}
					}
				} );
			}
		}

		add_admin_menu_class();

		// Copy nameservers to clipboard
		$(document).ready(function() {
			$('.hts-nameservers svg').click(function() {
				let textToCopy = $(this).closest('div').find('b').text();
				copyTextToClipboard(textToCopy);
			});
		});

		function copyTextToClipboard(text) {
			let textArea = document.createElement('textarea');
			textArea.value = text;
			document.body.appendChild(textArea);
			textArea.select();
			document.execCommand('copy');
			document.body.removeChild(textArea);
		}

		//Sidebar menu script
		window.addEventListener( 'resize', function () {

			var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
			var navbarWrapper = document.querySelector( '.hsr-wrapper__list' );
			var mobileSidebar = document.querySelector( '.hsr-mobile-sidebar .hsr-wrapper' );
			var hpanelLink = document.querySelector( '.hsr-go-to-hpanel' );


			if ( windowWidth <= 1000 ) {
				if ( navbarWrapper && mobileSidebar ) {
					mobileSidebar.appendChild( navbarWrapper );
					mobileSidebar.appendChild( hpanelLink );
				}
			} else {
				var originalParent = document.querySelector( '.hsr-onboarding-navbar__wrapper' );
				if ( navbarWrapper && originalParent ) {
					originalParent.appendChild( navbarWrapper );
					originalParent.appendChild( hpanelLink );
					document.querySelector( '.hsr-mobile-sidebar' ).classList.remove( 'hsr-active' );
				}
			}
		} );

		window.dispatchEvent( new Event( 'resize' ) );


			var mobileSidebar = document.querySelector( '.hsr-mobile-sidebar' );
			var closeButtons = document.querySelectorAll( '.hsr-close, .hsr-mobile-menu-icon' );

			closeButtons.forEach( function ( button ) {
				button.addEventListener( 'click', function ( event ) {
					mobileSidebar.classList.toggle( 'hsr-active' );
					document.querySelector('body').classList.toggle( 'hsr-sidebar-active' );
					event.stopPropagation();
				} );
			} );

			document.addEventListener( 'click', function ( event ) {
				if ( ! mobileSidebar.contains( event.target ) ) {
					mobileSidebar.classList.remove( 'hsr-active' );
					document.querySelector('body').classList.remove( 'hsr-sidebar-active' );
				}
			} );
		} );



} )( jQuery );
