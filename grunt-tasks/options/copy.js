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
					'data.php',
					'favicon.ico',
					'routes.json',
					'sitemap.xml'
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
