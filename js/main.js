require(['jquery', 'contact', 'menuToggle', 'teamcycle', 'slick', 'placeholders'],
	function ($, contact, menuToggle, teamcycle, slick) {

	console.log('Welcome to EcoStudio!');

	menuToggle('.menu-toggle', '.header-menu', 'open');

	contact('.contact-form');

	teamcycle('.team-list .team-member:not(.your-place):not(.placeholder):not(.no-real-img)', 3000);

	$(':header[id]').wrapInner(function () {
		return $('<a>').attr('href', window.location.pathname + '#' + this.id);
	});

	$('.team-member-icons i')
		.attr('data-title', function () { return $(this).attr('title'); })
		.removeAttr('title');

	var $sliders = $('.full-image-list').slick();

});
