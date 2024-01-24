import 'video.js/dist/video-js.css';
import videojs from "!video.js";
import 'videojs-youtube'

document.addEventListener( 'DOMContentLoaded', function () {
	// Initialize the Video.js player
	var playerElement = document.getElementById( 'hts-video-player' );

	if ( playerElement ) {
		var player = videojs( 'hts-video-player', {
			"techOrder": [ "youtube" ],
			"sources": [ {
				"type": "video/youtube",
				"src": "https://www.youtube.com/watch?v=WkbQr5dSGLs&t"
			} ]
		} );


		var playlistItems = document.querySelectorAll( '.hsr-playlist-item' );
		if ( playlistItems ) {
			playlistItems.forEach( function ( item ) {
				item.addEventListener( 'click', function () {
					var videoSrc = this.getAttribute( 'data-video-src' );

					player.src( {
						type: 'video/youtube',
						src: videoSrc
					} );

					player.load();
					player.play();
				} );
			} );
		}
	}
} );
