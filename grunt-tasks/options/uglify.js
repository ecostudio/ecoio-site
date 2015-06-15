module.exports = {

	options: {
		separator: ';\n'
	},

	dist: {

		files: [{
			expand: true,
			cwd: 'dist/js',
			src: ['**/*.js', '!main.js'],
			dest: 'dist/js'
		}]

	}

};
