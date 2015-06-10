(function () {

	requirejs.config({
		paths: {
			objTools: 'lib/objTools',
			underscore: 'lib/underscore',
			jquery: 'lib/jquery',
			slick: 'lib/slick',
			dropzone: 'lib/dropzone-amd-module',
			svgeezy: 'lib/svgeezy'
		},
		shim: {
			svgeezy: {
				exports: 'svgeezy'
			}
		}
	});

})();
