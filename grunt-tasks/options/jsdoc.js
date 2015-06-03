module.exports = {

	generate: {

		src: ["./js/"],

		options: {

			destination: "./jsdocs/",

			tags: {

				"allowUnknownTags": true

			},

			templates: {

				cleverLinks: true,

				monospaceLinks: false

			}
		}
	}

};
