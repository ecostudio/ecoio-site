require(['jquery', 'contact', 'menuToggle', 'slick', 'placeholders'],
	function ($, contact, menuToggle, slick) {

	console.log('Welcome to EcoStudio!');

	menuToggle('.menu-toggle', '.header-menu', 'open');

	contact('.contact-form');

	$(':header[id]').wrapInner(function () {
		return $('<a>').attr('href', window.location.pathname + '#' + this.id);
	});

	$('.team-member-icons i')
		.attr('data-title', function () { return $(this).attr('title'); })
		.removeAttr('title');

	var $sliders = $('.full-image-list').slick();

});
