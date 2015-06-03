module.exports = {

	compile: {

		options: {

			mainConfigFile: 'js/require_config.js',
			baseUrl: 'js',
			name: 'main',
			findNestedDependencies: true,
			optimize: 'uglify2',
			out: 'dist/js/main.js'

		}

	}

};
