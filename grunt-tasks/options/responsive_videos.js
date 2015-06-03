module.exports = {

	gen: {

		options: {
			sizes: [
				{
					name: 'fullhd',
					width: 1920
				}
			]
		},

		files: [{
			expand: true,
			cwd: 'image/',
			src: ['**/*.webm'],
			dest: 'image/'
		}]

	}

};
