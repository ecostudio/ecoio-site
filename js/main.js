require(['jquery', 'hero', 'contact', 'menuToggle', 'svgeezy', 'placeholders'],
	function ($, hero, contact, menuToggle, svgeezy) {

	console.log('Welcome to EcoStudio!');

	svgeezy.init();

	hero('.hero', {
		adaptiveHeight: true,
		slide: '.slide',
		media: '.media',
		playButton: '.hero-play',
		prevArrow: '.hero .prev',
		nextArrow: '.hero .next'
	});

	menuToggle('.menu-toggle', '.header-menu', 'open');

	contact('.contact-form');

	$(':header[id]').wrapInner(function () {
		return $('<a>').attr('href', '#' + this.id);
	});

});
