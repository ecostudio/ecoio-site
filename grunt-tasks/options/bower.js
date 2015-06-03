module.exports = {

	install: {

		dest: 'js/lib',

		styl_dest: 'style/lib',

		woff2_dest: 'css/fonts',

		css_dest: 'css',

		options: {

			stripAffix: true,

			keepExpandedHierarchy: false,

			packageSpecific: {
				'slick.js': {
					files: [
						'slick/slick.js',
						'slick/slick.css'
					]
				}
			}

		}
	}

};
