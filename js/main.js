require(['jquery', 'hero', 'contact', 'menuToggle'],
	function ($, hero, contact, menuToggle) {

	console.log('Welcome to EcoStudio!');

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

});
