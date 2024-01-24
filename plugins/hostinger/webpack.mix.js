let mix = require('laravel-mix');

mix.setPublicPath('./assets')
	.js('src/js/main.js', 'assets/js')
	.js('src/js/requests.js', 'assets/js')
	.js('src/js/global-scripts.js', 'assets/js')
	.sass('src/css/style.scss', 'assets/css/main.css')
	.sass('src/css/components/coming-soon.scss', 'assets/css/coming-soon.css')
	.sass('src/css/preview/preview.scss', 'assets/css/hts-preview.css')
	.options({
		processCssUrls:false
	})
	.copy('src/images/**/*.{jpg,jpeg,png,gif,svg}', 'assets/images')
	.copy('src/fonts/**/*.{ttf,woff2,woff}', 'assets/fonts');
