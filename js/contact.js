define(['jquery', 'underscore', 'dropzone'], function ($, _, Dropzone) {

	var dzTemplate =
		'<div class="dz-preview dz-file-preview">' +
			'<div class="dz-details">' +
				'<span class="dz-filename" data-dz-name></span>' +
				'<span class="dz-size" data-dz-size></span>' +
			'</div>' +
			' <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
			'<div class="dz-success-mark"></div>' +
			'<div class="dz-error-mark"></div>' +
			'<div class="error" data-dz-errormessage></div>' +
			'<button type="button" class="remove" data-dz-remove title="Törlés"></button' +
		'</div>';

	Dropzone.autoDiscover = false;

	return function (selector) {

		var $formCont = $(selector);
		var $form = $formCont.find('form');

		var $dzone = $form.find('.contact-dropzone').show();
		if ($dzone.length) {
			var dz = new Dropzone($dzone.get(0), {
				url: 'fileupload',
				previewTemplate: dzTemplate,
				maxFilesize: 25,
				createImageThumbnails: false,
				maxFiles: 5,
				acceptedFiles: [
					'image/*',
					'application/pdf',
					'application/msword',
					'application/rtf',
					'text/richtext',
					'text/plain',
					'text/xml',
					'application/xml'
				].join(','),
				dictInvalidFileType: 'Nem engedélyezett fájltípus.',
				dictFileTooBig: 'Túl nagy fájl, {{maxFilesize}} MB a maximum.',
				dictResponseError: 'Feltöltési hiba.',
				dictMaxFilesExceeded: 'Több fájlt nem tölthetsz fel.',
				fallback: function () {
					$dzone.hide();
				}
			});

			dz.on('success', function (file, response) {
				$(file.previewElement).data('fileid', response);
			});
		}

		$form
			.on('submit', function (e) {
				e.preventDefault();

				var $labels = $form.find('label').removeClass('has-error');

				var data = $form.serializeArray();
				data.push({
					name: 'referrer',
					value: document.referrer
				},
				{
					name: 'files',
					value: JSON.stringify($form.find('.dropzone .dz-preview').map(function () {
						return $(this).data('fileid');
					}).get())
				});

				$.post(window.location, data, function(response) {
					if (_(response).isEmpty()) {
						$formCont.addClass('sent-success');
						_gaq.push(['_trackEvent', 'ContactForm', 'Submit', 'SendButton']);
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
