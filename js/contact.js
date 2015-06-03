define(['jquery', 'underscore'], function ($, _) {

	return function (selector) {

		var $formCont = $(selector);
		var $form = $formCont.find('form');

		$form
			.on('submit', function (e) {
				e.preventDefault();

				var $labels = $form.find('label').removeClass('has-error');

				$.post(window.location, $form.serialize(), function(response) {
					if (_(response).isEmpty()) {
						$formCont.addClass('sent-success');
					}
					else {
						_(response).each(function (key) {
							$labels.has('[name="' + key + '"]').addClass('has-error');
						});
					}
				});
			})
			.on('keydown', 'input, textarea', function(e) {
				$(e.target).addClass('touched');
			})
			.on('keyup paste input', 'input, textarea', function (e) {
				$(e.target).filter('.touched').closest('label')
					.toggleClass('has-error', !e.target.validity.valid);
			});

	};

});
