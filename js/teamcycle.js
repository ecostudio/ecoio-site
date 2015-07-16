define(['jquery', 'underscore'], function ($, _) {

	var cl = 'highlight';

	var isElementInViewport = function (el) {
		var rect = el.getBoundingClientRect();
		return (
			rect.top >= 0 &&
			rect.left >= 0 &&
			rect.bottom <= $(window).height() &&
			rect.right <= $(window).width()
		);
	};

	var highlight = function ($elems) {
		var $prev = $elems.filter('.' + cl).removeClass(cl);
		var $visible = $elems.filter(function () {
			return isElementInViewport(this);
		});
		var which = _.random(0, $visible.length - 1);
		if ($visible.index($prev) !== which) {
			$visible.eq(which).addClass(cl);
		}
	};

	return function (sel, timeout) {
		setInterval(function () { highlight($(sel)); }, timeout);
	};

});
