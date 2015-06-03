define(['jquery'], function () {

	return function (buttonselector, menuSelector, openClass) {

		var $menuToggle = $(buttonselector).on('click', function () {
			$([this, $(menuSelector)]).toggleClass(openClass);
		});

		$('body').on('click', function (e) {
			if ($menuToggle.hasClass(openClass) && (e.target != $menuToggle.get(0))) {
				$menuToggle.trigger('click');
			}
		});

	};

});
