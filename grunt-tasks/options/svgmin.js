module.exports = {

	options: {
		plugins: [
			{ removeViewBox: false },
			{ removeUselessStrokeAndFill: false },
			{ removeEmptyAttrs: false }
		]
	},

	dist: {

		files: [{
			expand: true,
			cwd: 'image/',
			src: ['**/*.svg'],
			dest: 'dist/image/'
		}]

	}

};
