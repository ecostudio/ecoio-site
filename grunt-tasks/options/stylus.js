module.exports = {

	compile: {

		files: {
			'css/main.css': ['css/slick.css', 'style/index.styl']
		},

		options: {

			compress: false,

			linenos: true,

			use: [
				function () {
					return require('autoprefixer-stylus')({
						browsers: [
							'last 2 versions',
							'ie 8',
							'ie 9'
						]
					});
				}
			],

			import: [
				'../node_modules/rupture/rupture',
				'../node_modules/jeet/stylus/jeet'
			]

		}

	}

};
