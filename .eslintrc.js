module.exports = {
	root: true,
	extends: [
		'plugin:@wordpress/eslint-plugin/recommended',
		'plugin:eslint-comments/recommended',
	],
	env: {
		browser: true,
	},
	rules: {
		// Add rules.
	},
	globals: {
		// Add global variables here.
		phpvar: 'readonly',
	},
};