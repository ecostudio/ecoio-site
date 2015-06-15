module.exports = {

	options: {
		args: ['--verbose'],
		recursive: true
	},

	prod: {
		options: {
			src: 'dist/',
			dest: '/data/webcontent/ecostud.io/www/',
			host: 'developer@77.111.97.115'
		}
	}

};
