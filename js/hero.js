define(['jquery', 'underscore', 'slick'], function ($, _) {

	var Video = function ($elem) {
		var o = {
			getElem: function () {
				return $elem;
			},
			play: function () {
				if ($elem.length) $elem.get(0).play();
				return this;
			},
			pause: function () {
				if ($elem.length) $elem.get(0).pause();
				return this;
			}
		};

		return o;
	};

	var Slide = function (index, slider) {
		var $elem = _(index).isNumber() ?
			$(slider.$slides.get(index)) :
			$(index);

		return {
			getElem: function () {
				return $elem;
			},
			hideCover: function () {
				$elem.addClass('playing');
				return this;
			},
			showCover: function () {
				$elem.removeClass('playing');
				return this;
			},
			getPreviewVideo: function () {
				return Video($elem.find('video.media.preview'));
			}
		};
	};

	return function (selector, settings) {

		var defaults = {};
		var $hero = $(selector);
		var options = $.extend(defaults, settings);

		var getParentSlide = function (elem) {
			return $(elem).closest(options.slide);
		};

		$hero.slick(options);

		$hero.on('click', options.playButton, function () {
			Slide(getParentSlide(this)).hideCover();
		});

		$hero.on('click', options.media, function () {
			Slide(getParentSlide(this)).showCover();
		});

		$hero.on('beforeChange', function (e, slick, current, next) {
			Slide(next, slick).getPreviewVideo().play();

			if (current !== next) {
				Slide(current, slick).showCover().getPreviewVideo().pause();
			}
		});

	};

});
