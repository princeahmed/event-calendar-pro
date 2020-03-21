/**
 * Event Calendar Pro Admin
 * webpublisherpro.com
 *
 * Copyright (c) 2018 webpublisherpro
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */

window.Project = (function (window, document, $, undefined) {
	'use strict';
	var app = {

        init: function () {
			//fullcalendar.js config
			$('#calendar').fullCalendar({
				header: {
					center: 'prev,today,next',
					right: false
				},
				height: 'parent'
			});

			$(document).on('click','.fc-day-top', app.loadEvent);
			$('.ecp-upload-img').on('click', app.uploadImage);
			$('.ecp-remove-image').on('click', app.removeImage);

			app.initDatePicker();

		},

		loadEvent: function(){
			var list_container = $('.ecp-widget-event-list');
			var spinner = window.wpecp.asset_url+'/images/icons/spinner.gif';
			list_container.html('<img src="'+spinner+'" style="height: 100px;margin: 0 auto;display: block;">');
			var data = {
				date: $(this).data('date'),
				action: 'ecp_get_event_list'
		};

		if($('.fc-today').length > 0) {
			$('.fc-today').removeClass('fc-today');
		}

		$(this).addClass('fc-today');

		wp.ajax.send('ecp_get_event_list', {
			data: data,
			success: function (res) {
				list_container.replaceWith(res.html);
			},
			error: function () {
				list_container.html('<h3 class="text-center">No Events Found</h3>');
			}
		});

		},

		uploadImage: function (e) {
			e.preventDefault();
			var image = wp.media({
				title: 'Upload Image',
				multiple: false
			}).open()
				.on('select', function () {
					var uploaded_image = image.state().get('selection').first();
					var image_url = uploaded_image.toJSON().url;
					var image_id = uploaded_image.toJSON().id;
					$('.ecp-uploaded-image').attr('src', image_url);
					$('input[name=_thumbnail_id]').val(image_id);
					$('.ecp-remove-image').css('display','inline-block');
					console.log(uploaded_image.toJSON());
				});
		},

		removeImage: function(e) {
			e.preventDefault();
			$('.ecp-uploaded-image').attr('src', '');
			$('input[name=_thumbnail_id]').val('');
			$(this).css('display','none');
		},

		initDatePicker: function () {

			$(document).ready(function ($) {

				$('.ecp-date-calendar').datepicker({
					changeMonth: true,
					changeYear: true,
					dateFormat: 'yy-mm-dd',
					firstDay: 7
				});

				$('.ecp-time-calendar').timepicker({
					scrollbar: true
				});

			});
		}

    };

	$(document).ready(app.init);

	return app;

})(window, document, jQuery);
