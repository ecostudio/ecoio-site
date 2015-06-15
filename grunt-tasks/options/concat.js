module.exports = {

	options: {
		separator: ';\n'
	},

	ie8: {

		src: [
			'js/lib/selectivizr.js',
			'js/lib/html5shiv.js',
			'js/lib/respond.src.js',
			'js/lib/es5-shim.js'
		],

		dest: 'js/ie8/shims.js'

	}

};
