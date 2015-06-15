module.exports = {

	dist: {

		files: [
			{
				expand: true,
				src: [
					'js/require_config.js',
					'js/lib/require.js',
					'js/ie8/**',
					'fonts/**',
					'image/**',
					'vendor/**',
					'class/**',
					'templates/**',
					'index.php',
					'favicon.ico',
					'routes.json'
				],
				dest: 'dist/'
			},
			{
				src: '.htaccess_dist',
				dest: 'dist/.htaccess'
			}
		]

	}

};
