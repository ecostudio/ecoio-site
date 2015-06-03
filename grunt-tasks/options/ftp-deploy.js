var auth = {
	host: 'ecostudio.hu',
	authKey: 'ecostudio'
};

module.exports = {

	full: {

		auth: auth,
		forceVerbose: true,

		src: 'dist/',
		dest: '/'

	},

	quick: {

		auth: auth,
		forceVerbose: true,

		src: 'dist/',
		dest: '/',
		exclusions: ['dist/vendor/**']

	}

};
