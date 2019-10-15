(function ($, elementor) {
	"use strict";

	var colorwayhf = {

		init: function () {

			var widgets = {
				'colorwayhf-countdown-timer.default': colorwayhf.Countdown_Timer,
				'colorwayhf-client-logo.default': colorwayhf.Client_Logo,
				'colorwayhf-testimonial.default': colorwayhf.Testimonial_Slider,
				'colorwayhf-image-comparison.default': colorwayhf.Image_Comparison,
				'colorwayhf-progressbar.default': colorwayhf.Progressbar,
				'colorwayhf-piechart.default': colorwayhf.Piechart,
				'colorwayhf-funfact.default': colorwayhf.Funfact,
				'colorwayhf-gallery.default': colorwayhf.Gallery,
				'colorwayhf-motion-text.default': colorwayhf.MotionText,
				'colorwayhf-timeline.default': colorwayhf.TimeLine,
				'colorwayhf-post-tab.default': colorwayhf.PostTab,
				'colorwayhf-colorwayhf-hotspot.default': colorwayhf.Hotspot,
				'colorwayhf-header-search.default': colorwayhf.Header_Search,
				'colorwayhf-header-offcanvas.default': colorwayhf.Header_Off_Canvas,
			};
			$.each(widgets, function (widget, callback) {
				elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
			});

			elementor.hooks.addAction('frontend/element_ready/global', colorwayhf.GlobalCallback);
		},

		GlobalCallback: function ($scope) {
			new cwStickyHandler({
				$element: $scope
			});
		},

		AnimationFix: function ($scope) {
			function init($scope) {

				new cwStickyHandler({
					$element: $scope
				});

				$scope.find('.colorwayhf-invisible').each(function () {
					var el = $(this);
					var settings = JSON.parse(el.attr('data-settings'));

					var isVisible = colorwayhf.IsElementInView(el, false),
						animationClass = settings._animation,
						animationDelay = settings._animation_delay || 300;

					if (isVisible == true) {
						setTimeout(function () {
							el.removeClass('colorwayhf-invisible').addClass('animated ' + animationClass);
						}, animationDelay);
					}
				});
			}

			init($scope);
			$(window).on('scroll', function () {
				init($scope);
			});
		},

		IsElementInView: function (element, fullyInView) {
			var pageTop = $(window).scrollTop();
			var pageBottom = pageTop + $(window).height();
			var elementTop = element.offset().top;
			var elementBottom = elementTop + element.height();

			if (fullyInView === true) {
				return ((pageTop < elementTop) && (pageBottom > elementBottom));
			} else {
				return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
			}
		},


		Progressbar: function ($scope) {
			var barElement = $scope.find(".single-skill-bar");
			var percentElement = $scope.find(".number-percentage");
			var value = percentElement.attr("data-value");
			var duration = percentElement.attr("data-animation-duration");
			duration = parseInt((duration != '' ? duration : 300), 10);

			barElement.waypoint({
				handler: function () {
					percentElement.animateNumbers(value, true, duration);
					barElement.find('.skill-track').animate({
						width: value + '%'
					}, 3500);
				},
				offset: '100%'
			})
		},
		Funfact: function ($scope) {
			var barElement = $scope.find(".colorwayhf-funfact");
			var percentElement = $scope.find(".number-percentage");
			var value = percentElement.attr("data-value");
			var duration = percentElement.attr("data-animation-duration");
			duration = parseInt((duration != '' ? duration : 300), 10);

			barElement.waypoint({
				handler: function () {
					percentElement.animateNumbers(value, true, duration);
				},
				offset: '100%'
			})
		},
		Countdown_Timer: function ($scope) {

			var $container1 = $scope.find('.colorwayhf-countdown-timer[data-cw-countdown]');
			var $container2 = $scope.find('.colorwayhf-countdown-timer-2[data-cw-countdown]');
			var $container3 = $scope.find('.colorwayhf-countdown-timer-3[data-cw-countdown]');
			var $container4 = $scope.find('.colorwayhf-countdown-timer-4[data-cw-countdown]');
			var $container5 = $scope.find('.colorwayhf-flip-clock');

			$container1.each(function () {
				var $this = $(this),
					finalDate = $(this).data('cw-countdown');
				var hour = $(this).data('date-cw-hour'),
					minute = $(this).data('date-cw-minute'),
					second = $(this).data('date-cw-second'),
					day = $(this).data('date-cw-day'),
					week = $(this).data('date-cw-week'),
					finish_title = $(this).data('finish-title'),
					finish_content = $(this).data('finish-content');

				$this.theFinalCountdown(finalDate, function (event) {
						var $this = $(this).html(event.strftime(' ' +
							'<div class="colorwayhf-timer-container colorwayhf-days"><div class="colorwayhf-inner-container"><div class="colorwayhf-timer-content"><span class="colorwayhf-timer-count">%-D </span><span class="colorwayhf-timer-title">' + day + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-hours"><div class="colorwayhf-inner-container"><div class="colorwayhf-timer-content"><span class="colorwayhf-timer-count">%H </span><span class="colorwayhf-timer-title">' + hour + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-minutes"><div class="colorwayhf-inner-container"><div class="colorwayhf-timer-content"><span class="colorwayhf-timer-count">%M </span><span class="colorwayhf-timer-title">' + minute + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-seconds"><div class="colorwayhf-inner-container"><div class="colorwayhf-timer-content"><span class="colorwayhf-timer-count">%S </span><span class="colorwayhf-timer-title">' + second + '</span></div></div></div>'
						));
					})
					.on('finish.countdown', function () {
						$(this).html(
							finish_title + "<br/>" + finish_content
						);
					});
			});

			$container2.each(function () {
				var $this = $(this),
					finalDate = $(this).data('cw-countdown');
				var hour = $(this).data('date-cw-hour'),
					minute = $(this).data('date-cw-minute'),
					second = $(this).data('date-cw-second'),
					day = $(this).data('date-cw-day'),
					week = $(this).data('date-cw-week'),
					finish_title = $(this).data('finish-title'),
					finish_content = $(this).data('finish-content');

				$this.theFinalCountdown(finalDate, function (event) {

						var $this = $(this).html(event.strftime(' ' +
							'<div class="colorwayhf-timer-container colorwayhf-days"><span class="colorwayhf-timer-count">%-D </span><span class="colorwayhf-timer-title">' + day + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-hours"><span class="colorwayhf-timer-count">%H </span><span class="colorwayhf-timer-title">' + hour + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-minutes"><span class="colorwayhf-timer-count">%M </span><span class="colorwayhf-timer-title">' + minute + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-seconds"><span class="colorwayhf-timer-count">%S </span><span class="colorwayhf-timer-title">' + second + '</span></div>'));
					})
					.on('finish.countdown', function () {
						$(this).html(
							finish_title + "<br/>" + finish_content
						);
					});
			});

			$container3.each(function () {
				var $this = $(this),
					finalDate = $(this).data('cw-countdown');
				var hour = $(this).data('date-cw-hour'),
					minute = $(this).data('date-cw-minute'),
					second = $(this).data('date-cw-second'),
					day = $(this).data('date-cw-day'),
					week = $(this).data('date-cw-week'),
					finish_title = $(this).data('finish-title'),
					finish_content = $(this).data('finish-content');

				$this.theFinalCountdown(finalDate, function (event) {
						var $this = $(this).html(event.strftime(' ' +
							'<div class="colorwayhf-timer-container colorwayhf-days"><div class="colorwayhf-timer-content"><div class="colorwayhf-inner-container"><span class="colorwayhf-timer-count">%-D </span><span class="colorwayhf-timer-title">' + day + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-hours"><div class="colorwayhf-timer-content"><div class="colorwayhf-inner-container"><span class="colorwayhf-timer-count">%H </span><span class="colorwayhf-timer-title">' + hour + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-minutes"><div class="colorwayhf-timer-content"><div class="colorwayhf-inner-container"><span class="colorwayhf-timer-count">%M </span><span class="colorwayhf-timer-title">' + minute + '</span></div></div></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-seconds"><div class="colorwayhf-timer-content"><div class="colorwayhf-inner-container"><span class="colorwayhf-timer-count">%S </span><span class="colorwayhf-timer-title">' + second + '</span></div></div></div>'));

					})
					.on('finish.countdown', function () {
						$(this).html(
							finish_title + "<br/>" + finish_content
						);
					});
			});

			$container4.each(function () {
				var $this = $(this),
					finalDate = $(this).data('cw-countdown');
				var hour = $(this).data('date-cw-hour'),
					minute = $(this).data('date-cw-minute'),
					second = $(this).data('date-cw-second'),
					day = $(this).data('date-cw-day'),
					week = $(this).data('date-cw-week'),
					finish_title = $(this).data('finish-title'),
					finish_content = $(this).data('finish-content');

				$this.theFinalCountdown(finalDate, function (event) {

						var $this = $(this).html(event.strftime(' ' +
							'<div class="colorwayhf-timer-container colorwayhf-days"><span class="colorwayhf-timer-count">%-D </span><span class="colorwayhf-timer-title">' + day + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-hours"><span class="colorwayhf-timer-count">%H </span><span class="colorwayhf-timer-title">' + hour + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-minutes"><span class="colorwayhf-timer-count">%M </span><span class="colorwayhf-timer-title">' + minute + '</span></div>' +
							'<div class="colorwayhf-timer-container colorwayhf-seconds"><span class="colorwayhf-timer-count">%S </span><span class="colorwayhf-timer-title">' + second + '</span></div>'));

					})
					.on('finish.countdown', function () {
						$(this).html(
							finish_title + "<br/>" + finish_content
						);
						$(this).addClass('colorwayhf-coundown-finish');
					});
			});
			$container5.each(function () {
				var hour = $(this).data('date-cw-hour'),
					minute = $(this).data('date-cw-minute'),
					second = $(this).data('date-cw-second'),
					day = $(this).data('date-cw-day'),
					week = $(this).data('date-cw-week'),
					finalDate = $(this).data('cw-countdown'),
					finish_title = $(this).data('finish-title'),
					finish_content = $(this).data('finish-content');

				var labelsData = {
					'colorwayhf-wks': week,
					'colorwayhf-days': day,
					'colorwayhf-hrs': hour,
					'colorwayhf-mins': minute,
					'colorwayhf-secs': second
				};

				var labels = ['colorwayhf-wks', 'colorwayhf-days', 'colorwayhf-hrs', 'colorwayhf-mins', 'colorwayhf-secs'],

					nextYear = (new Date(finalDate)),
					template = _.template('<div class="colorwayhf-time <%= label %>"><span class="colorwayhf-count colorwayhf-curr colorwayhf-top"><%= curr %></span><span class="colorwayhf-count colorwayhf-next colorwayhf-top"><%= next %></span><span class="colorwayhf-count colorwayhf-next colorwayhf-bottom"><%= next %></span><span class="colorwayhf-count colorwayhf-curr colorwayhf-bottom"><%= curr %></span><span class="colorwayhf-label"><%= labelD.length < 6 ? labelD : labelD.substr(0, 3)  %></span></div>'),
					currDate = '00:00:00:00:00',
					nextDate = '00:00:00:00:00',
					parser = /([0-9]{2})/gi,
					$example = $container5;
				// Parse countdown string to an object
				function strfobj(str) {
					var parsed = str.match(parser),
						obj = {};
					labels.forEach(function (label, i) {
						obj[label] = parsed[i]
					});
					return obj;
				}
				// Return the time components that diffs
				function diff(obj1, obj2) {
					var diff = [];
					labels.forEach(function (key) {
						if (obj1[key] !== obj2[key]) {
							diff.push(key);
						}
					});
					return diff;
				}
				// Build the layout
				var initData = strfobj(currDate);
				labels.forEach(function (label, i) {
					$example.append(template({
						curr: initData[label],
						next: initData[label],
						label: label,
						labelD: labelsData[label]
					}));
				});
				// Starts the countdown
				$example.theFinalCountdown(nextYear, function (event) {
						var newDate = event.strftime('%w:%d:%H:%M:%S'),
							data;
						if (newDate !== nextDate) {
							currDate = nextDate;
							nextDate = newDate;
							// Setup the data
							data = {
								'curr': strfobj(currDate),
								'next': strfobj(nextDate)
							};
							// Apply the new values to each node that changed
							diff(data.curr, data.next).forEach(function (label) {
								var selector = '.%s'.replace(/%s/, label),
									$node = $example.find(selector);
								// Update the node
								$node.removeClass('colorwayhf-flip');
								$node.find('.colorwayhf-curr').text(data.curr[label]);
								$node.find('.colorwayhf-next').text(data.next[label]);
								// Wait for a repaint to then flip
								_.delay(function ($node) {
									$node.addClass('colorwayhf-flip');
								}, 50, $node);
							});
						}
					})
					.on('finish.countdown', function () {
						$(this).html(
							finish_title + "<br/>" + finish_content
						);
					});
			});

		},

		Client_Logo: function ($scope) {
			var $log_carosel = $scope.find('.colorwayhf-clients-slider');
			$log_carosel.each(function () {
				// //console.log($(this).data('right_icon'));
				var leftArrow = '<button type="button" class="slick-prev"><i class="icon icon-left-arrow2"></i></button>';

				var rightArrow = '<button type="button" class="slick-next"><i class="icon icon-right-arrow2"></i></button>';

				var slidestoshowtablet = $(this).data('slidestoshowtablet');
				var slidestoscroll_tablet = $(this).data('slidestoscroll_tablet');
				var slidestoshowmobile = $(this).data('slidestoshowmobile');
				var slidestoscroll_mobile = $(this).data('slidestoscroll_mobile');
				var arrow = $(this).data('show_arrow') === 'yes' ? true : false;
				var dot = $(this).data('show_dot') === 'yes' ? true : false;
				var autoPlay = $(this).data('autoplay') === 'yes' ? true : false;
				var centerMode = $(this).data('data-center_mode') === 'yes' ? true : false;

				$(this).not('.slick-initialized').slick({
					slidesToShow: ($(this).data('slidestoshow') !== 'undefined') ? $(this).data('slidestoshow') : 4,
					slidesToScroll: ($(this).data('slidestoscroll') !== 'undefined') ? $(this).data('slidestoscroll') : 4,
					autoplay: ($(this).data('autoplay') !== 'undefined') ? autoPlay : true,
					autoplaySpeed: ($(this).data('speed') !== 'undefined') ? $(this).data('speed') : 1000,
					arrows: ($(this).data('show_arrow') !== 'undefined') ? arrow : true,
					dots: ($(this).data('show_dot') !== 'undefined') ? dot : true,
					pauseOnHover: ($(this).data('pause_on_hover') == 'yes') ? true : false,
					prevArrow: ($(this).data('left_icon') !== 'undefined') ? '<button type="button" class="slick-prev"><i class="' + $(this).data('left_icon') + '"></i></button>' : leftArrow,
					nextArrow: ($(this).data('right_icon') !== 'undefined') ? '<button type="button" class="slick-next"><i class="' + $(this).data('right_icon') + '"></i></button>' : rightArrow,
					rows: ($(this).data('rows') !== 'undefined') ? $(this).data('rows') : 1,
					vertical: ($(this).data('vertical_style') == 'yes') ? true : false,
					infinite: ($(this).data('autoplay') !== 'undefined') ? autoPlay : true,
					responsive: [{
							breakpoint: 1024,
							settings: {
								slidesToShow: slidestoshowtablet,
								slidesToScroll: slidestoscroll_tablet,
							}
						},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: slidestoshowtablet,
								slidesToScroll: slidestoscroll_tablet
							}
						},
						{
							breakpoint: 480,
							settings: {
								arrows: false,
								slidesToShow: slidestoshowmobile,
								slidesToScroll: slidestoscroll_mobile
							}
						}
					]

				});

			});
		},

		Testimonial_Slider: function ($scope) {
			var $testimonial_slider = $scope.find('.colorwayhf-testimonial-slider');
			$testimonial_slider.each(function () {
				var leftArrow = '<button type="button" class="slick-prev"><i class="icon icon-left-arrow2"></i></button>';
				var rightArrow = '<button type="button" class="slick-next"><i class="icon icon-right-arrow2"></i></button>';

				var slidestoshowtablet = $(this).data('slidestoshowtablet');
				var slidestoscroll_tablet = $(this).data('slidestoscroll_tablet');
				var slidestoshowmobile = $(this).data('slidestoshowmobile');
				var slidestoscroll_mobile = $(this).data('slidestoscroll_mobile');
				var arrow = $(this).data('show_arrow') === 'yes' ? true : false;
				var dot = $(this).data('show_dot') === 'yes' ? true : false;
				var autoPlay = $(this).data('autoplay') === 'yes' ? true : false;
				// var centerMode = $(this).data('data-center_mode') === 'yes' ? true : false;


				$(this).not('.slick-initialized').slick({
					slidesToShow: ($(this).data('slidestoshow') !== 'undefined') ? $(this).data('slidestoshow') : 1,
					slidesToScroll: ($(this).data('slidestoscroll') !== 'undefined') ? $(this).data('slidestoscroll') : 1,
					autoplay: ($(this).data('autoplay') !== 'undefined') ? autoPlay : true,
					autoplaySpeed: ($(this).data('speed') !== 'undefined') ? $(this).data('speed') : 1000,
					arrows: ($(this).data('show_arrow') !== 'undefined') ? arrow : true,
					dots: ($(this).data('show_dot') !== 'undefined') ? dot : true,
					pauseOnHover: ($(this).data('pause_on_hover') == 'yes') ? true : false,
					prevArrow: ($(this).data('left_icon') !== 'undefined') ? '<button type="button" class="slick-prev"><i class="' + $(this).data('left_icon') + '"></i></button>' : leftArrow,
					nextArrow: ($(this).data('right_icon') !== 'undefined') ? '<button type="button" class="slick-next"><i class="' + $(this).data('right_icon') + '"></i></button>' : rightArrow,
					// rows: ($(this).data('rows') !== 'undefined') ? $(this).data('rows') : 1,
					vertical: ($(this).data('vertical_style') == 'yes') ? true : false,
					infinite: ($(this).data('autoplay') !== 'undefined') ? autoPlay : true,
					responsive: [{
							breakpoint: 1024,
							settings: {
								slidesToShow: slidestoshowtablet,
								slidesToScroll: slidestoscroll_tablet,
							}
						},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: slidestoshowtablet,
								slidesToScroll: slidestoscroll_tablet
							}
						},
						{
							breakpoint: 480,
							settings: {
								arrows: false,
								slidesToShow: slidestoshowmobile,
								slidesToScroll: slidestoscroll_mobile
							}
						}
					]
				});

			});
		},

		Image_Comparison: function ($scope) {

			var $image_comparison_container = $scope.find('.image-comparison-container');
			var $image_comparison_container_vertical = $scope.find('.image-comparison-container-vertical');


			var $this = $image_comparison_container,
				offset = $this.data('offset'),
				overlay = $this.data('overlay'),
				label_before = $this.data('label_before'),
				label_after = $this.data('label_after'),
				move_with_handle_only = $this.data('move_with_handle_only'),
				move_slider_on_hover = $this.data('move_slider_on_hover'),
				click_to_move = $this.data('click_to_move');



			$image_comparison_container.twentytwenty({
				before_label: label_before, // Set a custom before label
				after_label: label_after, // Set a custom after label
				default_offset_pct: offset, // How much of the before image is visible when the page loads
				no_overlay: overlay, //Do not show the overlay with before and after
				move_slider_on_hover: move_slider_on_hover, // Move slider on mouse hover?
				move_with_handle_only: move_with_handle_only, // Allow a user to swipe anywhere on the image to control slider movement.
				click_to_move: click_to_move // Allow a user to click (or tap) anywhere on the image to move the slider to that location.
			});

			var $this = $image_comparison_container_vertical,
				offset = $this.data('offset'),
				overlay = $this.data('overlay'),
				label_before = $this.data('label_before'),
				label_after = $this.data('label_after'),
				move_slider_on_hover = $this.data('move_slider_on_hover'),
				click_to_move = $this.data('click_to_move');


			$image_comparison_container_vertical.twentytwenty({
				orientation: 'vertical',
				before_label: label_before, // Set a custom before label
				after_label: label_after, // Set a custom after label
				default_offset_pct: offset, // How much of the before image is visible when the page loads
				no_overlay: overlay, //Do not show the overlay with before and after
				move_slider_on_hover: move_slider_on_hover, // Move slider on mouse hover?
				move_with_handle_only: move_with_handle_only, // Allow a user to swipe anywhere on the image to control slider movement.
				click_to_move: click_to_move // Allow a user to click (or tap) anywhere on the image to move the slider to that location.

			});


		},
		Piechart: function ($scope) {
			var colorfulchart = $scope.find('.colorful-chart');

			//console.log(colorfulchart);

			if (colorfulchart.length > 0) {

				colorfulchart.each(function (__, e) {
					var myColors = $(e).data('color');
					var datalineWidth = $(e).data('linewidth');
					var color_type = $(e).data('pie_color_style');
					var gradentColor1 = $(e).data('gradientcolor1');
					var gradentColor2 = $(e).data('gradientcolor2');
					var barbg = $(e).data('barbg');

					var obj;

					if (color_type === 'gradient') {

						obj = {
							gradientChart: true,
							barColor: gradentColor1,
							gradientColor1: gradentColor2,
							gradientColor2: gradentColor1,
							lineWidth: datalineWidth,
							trackColor: barbg,
						};

					} else {
						obj = {
							lineWidth: datalineWidth,
							barColor: myColors,
							trackColor: barbg,
						};
					}

					$(e).myChart(obj);
				})
			}

		},
		Gallery: function ($scope) {
			var $container = $scope.find('.cw_gallery_grid');
			var column = $container.data('gallerycol');
			// console.log((parseInt(column.tablet, 10)));
			if ($container.length > 0) {
				var colWidth = function colWidth() {
						var w = $container.width(),
							columnNum,
							columnWidth = 0;
						if (w > 1024) {
							columnNum = parseInt(column.desktop, 10);
						} else if (w > 768) {
							columnNum = parseInt(column.tablet, 10);
						}
						columnWidth = Math.floor(w / columnNum);
						$container.find('.cw_gallery_grid_item').each(function () {
							var $item = $(this),
								multiplier_w = $item.attr('class').match(/cw_gallery_grid_item-w(\d)/),
								width = multiplier_w ? columnWidth * multiplier_w[1] : columnWidth;
							$item.css({
								width: width,
							});
						});
						return columnWidth;
					},
					isotope = function isotope() {
						$container.isotope({
							resizable: false,
							itemSelector: '.cw_gallery_grid_item',
							masonry: {
								columnWidth: colWidth(),
								gutterWidth: 0
							}
						});
					};
				isotope();
				$(window).on('resize load', isotope);
				var $optionSets = $scope.find('.filter-button-wraper .option-set'),
					$optionLinks = $optionSets.find('a');
				$optionLinks.on('click', function () {
					var $this = $(this);
					var $optionSet = $this.parents('.option-set');
					$optionSet.find('.selected').removeClass('selected');
					$this.addClass('selected');
					// make option object dynamically, i.e. { filter: '.my-filter-class' }
					var options = {},
						key = $optionSet.attr('data-option-key'),
						value = $this.attr('data-option-value');

					// parse 'false' as false boolean
					value = value === 'false' ? false : value;
					options[key] = value;
					if (key === 'layoutMode' && typeof changeLayoutMode === 'function') {
						// changes in layout modes need extra logic
						changeLayoutMode($this, options);
					} else {
						// creativewise, apply new options
						$container.isotope(options);
					}
					return false;
				});
			}
			// tilt
			var tiltContainer = $scope.find('.cw-gallery-portfolio-tilt'),
				glare = $(tiltContainer).data('tilt-glare') === 'yes' ? true : false;
			$(tiltContainer).tilt({
				easing: "cubic-bezier(.03,.98,.52,.99)",
				transition: true,
				glare: glare,
			})
		},
		MotionText: function ($scope) {
			var texts = $scope.find('.cw_char_based .cw_motion_text');
			texts.each(function () {
				var text = $(this);
				for (let i = 0; i < text.length; i++) {
					var $this = text[i];
					var content = $this.innerHTML;
					content = content.trim();
					var str = '';
					var delay = parseInt(text.attr('cw-animation-delay')),
						delayIncrement = delay;

					//console.log(delay);

					for (let l = 0; l < content.length; l++) {
						if (content[l] != '') {
							str += `<span class="cw-letter" style="animation-delay:${delay}ms; -moz-animation-delay:${delay}ms; -webkit-animation-delay:${delay}ms;">${content[l]}</span>`;
							delay += delayIncrement;
						} else {
							str += content[i];
						}
					}
					$this.innerHTML = str;
				}
			});
		},

		TimeLine: function ($scope) {

			colorwayhf.AnimationFix($scope);

			var horizantalTimeline = $scope.find('.horizantal-timeline');

			if (horizantalTimeline.length > 0) {
				horizantalTimeline.find('.content-group').each(function (__, e) {
					$(e).on('mouseenter', function () {
						if ($(e).parents('.single-timeline').hasClass('hover')) {
							$(e).parents('.single-timeline').removeClass('hover')
						} else {
							$(e).parents('.single-timeline').addClass('hover')
							$(e).parents('.single-timeline').nextAll().removeClass('hover')
							$(e).parents('.single-timeline').prevAll().removeClass('hover')
						}
					})
				})
			}
		},

		PostTab: function ($scope) {
			if ($scope.find('.hover--active').length > 0) {
				var event_type = $scope.find('.hover--active').attr('data-post-tab-event');
				$scope.find('.hover--active').tab({
					trigger_event_type: event_type
				});
			}
		},
		Hotspot: function ($scope) {
			if ($scope.find('[data-toggle="tooltip"]').length > 0) {
				var event_type = $scope.find('[data-toggle="tooltip"]');
				event_type.tooltip();
			}
		},
		Header_Search: function ($scope) {
			if ($scope.find('.cw-modal-popup').length > 0) {
				$scope.find('.cw-modal-popup').magnificPopup({
					type: 'inline',
					fixedContentPos: false,
					fixedBgPos: true,
					overflowY: 'auto',
					closeBtnInside: false,
					callbacks: {
						beforeOpen: function() {
							this.st.mainClass = "my-mfp-slide-bottom cw-promo-popup";
						}
					}
				});
			}
		},

		Header_Off_Canvas: function ($scope) {
			if ($scope.find('.cw-sidebar-group').length > 0) {
				$scope.find('.cw_offcanvas-sidebar').on('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					$scope.find('.cw-sidebar-group').addClass('cw_isActive');
				});
				$scope.find('.cw_close-side-widget').on('click', function (e) {
					e.preventDefault();
					$scope.find('.cw-sidebar-group').removeClass('cw_isActive');
				});
				$('body').on('click', function (e) {
					$scope.find('.cw-sidebar-group').removeClass('cw_isActive');
				});
				$scope.find('.cw-sidebar-widget').on('click', function (e) {
					e.stopPropagation();
				});
			}
		}
	};
	$(window).on('elementor/frontend/init', colorwayhf.init);

	function compareVersion(v1, v2) {
		if (typeof v1 !== 'string') return false;
		if (typeof v2 !== 'string') return false;
		v1 = v1.split('.');
		v2 = v2.split('.');
		const k = Math.min(v1.length, v2.length);
		for (let i = 0; i < k; ++ i) {
			v1[i] = parseInt(v1[i], 10);
			v2[i] = parseInt(v2[i], 10);
			if (v1[i] > v2[i]) return 1;
			if (v1[i] < v2[i]) return -1;        
		}
		return v1.length == v2.length ? 0: (v1.length < v2.length ? -1 : 1);
	}

	var ColorwayHFModule = (typeof window.elementorFrontend.version !== 'undefined' && compareVersion(window.elementorFrontend.version, '2.6.0' ) < 0)
							? elementorFrontend.Module
							: elementorModules.frontend.handlers.Base;

	var cwStickyHandler = ColorwayHFModule.extend({

		bindEvents: function bindEvents() {
			elementorFrontend.addListenerOnce(this.getUniqueHandlerID() + 'cw_sticky', 'resize', this.run);
		},

		unbindEvents: function unbindEvents() {
			elementorFrontend.removeListeners(this.getUniqueHandlerID() + 'cw_sticky', 'resize', this.run);
		},

		isActive: function isActive() {
			return undefined !== this.$element.data('cw_sticky');
		},

		activate: function activate() {
			var elementSettings = this.getElementSettings(),
				stickyOptions = {
					to: elementSettings.cw_sticky,
					offset: elementSettings.cw_sticky_offset.size,
					effectsOffset: elementSettings.cw_sticky_effect_offset.size,
					classes: {
						sticky: 'cw-sticky',
						stickyActive: 'cw-sticky--active cw-section--handles-inside',
						stickyEffects: 'cw-sticky--effects',
						spacer: 'cw-sticky__spacer'
					}
				},
				$wpAdminBar = elementorFrontend.getElements('$wpAdminBar');

			if (elementSettings.cw_sticky_parent) {
				stickyOptions.parent = '.cw-widget-wrap';
			}

			if ($wpAdminBar.length && 'top' === elementSettings.cw_sticky && 'fixed' === $wpAdminBar.css('position')) {
				stickyOptions.offset += $wpAdminBar.height();
			}

			this.$element.cw_sticky(stickyOptions);
		},

		deactivate: function deactivate() {
			if (!this.isActive()) {
				return;
			}

			this.$element.cw_sticky('destroy');
		},

		run: function run(refresh) {
			if (!this.getElementSettings('cw_sticky')) {
				this.deactivate();

				return;
			}

			var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				activeDevices = this.getElementSettings('cw_sticky_on');

			if (-1 !== activeDevices.indexOf(currentDeviceMode)) {
				if (true === refresh) {
					this.reactivate();
				} else if (!this.isActive()) {
					this.activate();
				}
			} else {
				this.deactivate();
			}
		},

		reactivate: function reactivate() {
			this.deactivate();

			this.activate();
		},

		onElementChange: function onElementChange(settingKey) {
			if (-1 !== ['cw_sticky', 'cw_sticky_on'].indexOf(settingKey)) {
				this.run(true);
			}

			if (-1 !== ['cw_sticky_offset', 'cw_sticky_effects_offset', 'cw_sticky_parent'].indexOf(settingKey)) {
				this.reactivate();
			}
		},

		onInit: function onInit() {
			ColorwayHFModule.prototype.onInit.apply(this, arguments);
	
			this.run();
		},

		onDestroy: function onDestroy() {
			ColorwayHFModule.prototype.onDestroy.apply(this, arguments);
	
			this.deactivate();
		}
	});
}(jQuery, window.elementorFrontend));