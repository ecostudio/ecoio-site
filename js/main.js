require(['jquery', 'contact', 'menuToggle', 'svgeezy', 'slick', 'placeholders'],
	function ($, contact, menuToggle, svgeezy, slick) {

	console.log('Welcome to EcoStudio!');

	svgeezy.init();

	/*hero('.hero', {
		adaptiveHeight: true,
		slide: '.slide',
		media: '.media',
		playButton: '.hero-play',
		prevArrow: '.hero .prev',
		nextArrow: '.hero .next'
	});*/

	menuToggle('.menu-toggle', '.header-menu', 'open');

	contact('.contact-form');

	$(':header[id]').wrapInner(function () {
		return $('<a>').attr('href', '#' + this.id);
	});

	$('.team-member-icons i').attr('data-title', function () {
		var $this = $(this);
		var title = $this.attr('title');
		return title;
	});

	$('.full-image-list').slick();

});
