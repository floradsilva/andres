/**
 * Init and resize sliders
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.1
 */

/* global jQuery:false */
/* global TRX_ADDONS_STORAGE:false */

jQuery(document).on('action.init_sliders', trx_addons_init_sliders);
jQuery(document).on('action.init_hidden_elements', trx_addons_init_hidden_sliders);

// Init sliders with engine=swiper
function trx_addons_init_sliders(e, container) {
	"use strict";

	// Create Swiper Controllers
	if (container.find('.sc_slider_controller:not(.inited)').length > 0) {
		container.find('.sc_slider_controller:not(.inited)')
			.each(function () {
				"use strict";
				var controller = jQuery(this).addClass('inited');
				var slider_id = controller.data('slider-id');
				if (!slider_id) return;
				
				var controller_id = controller.attr('id');
				if (controller_id == undefined) {
					controller_id = 'sc_slider_controller_'+Math.random();
					controller_id = controller_id.replace('.', '');
					controller.attr('id', controller_id);
				}

				jQuery('#'+slider_id+' .slider_swiper').attr('data-controller', controller_id);

				var controller_style = controller.data('style');
				var controller_effect = controller.data('effect');
				var controller_interval = controller.data('interval');
				var controller_height = controller.data('height');
				var controller_per_view = controller.data('slides-per-view');
				var controller_space = controller.data('slides-space');
				var controller_controls = controller.data('controls');

				var controller_html = '';
				jQuery('#'+slider_id+' .swiper-slide')
					.each(function (idx) {
						"use strict";
						var slide = jQuery(this);
						var image = slide.data('image');
						var title = slide.data('title');
						var cats = slide.data('cats');
						var date = slide.data('date');
						controller_html += '<div class="swiper-slide"'
												+ ' style="'
														+ (image !== undefined ? 'background-image: url('+image+');' : '')
														+ '"'
												+ '>'
												+ '<div class="sc_slider_controller_info">'
													+ '<span class="sc_slider_controller_info_number">'+(idx < 9 ? '0' : '')+(idx+1)+'</span>'
													+ '<span class="sc_slider_controller_info_title">'+title+'</span>'
												+ '</div>'
											+ '</div>';
					});
				controller.html('<div id="'+controller_id+'_outer"'
									+ ' class="slider_swiper_outer slider_style_controller'
												+ ' slider_outer_' + (controller_controls == 1 ? 'controls slider_outer_controls_side' : 'nocontrols')
												+ ' slider_outer_nopagination'
												+ ' slider_outer_' + (controller_per_view==1 ? 'one' : 'multi')
												+ '"'
									+ '>'
										+ '<div id="'+controller_id+'_swiper"'
											+' class="slider_swiper swiper-slider-container'
													+ ' slider_' + (controller_controls == 1 ? 'controls slider_controls_side' : 'nocontrols')
													+ ' slider_nopagination'
													+ ' slider_notitles'
													+ ' slider_noresize'
													+ ' slider_' + (controller_per_view==1 ? 'one' : 'multi')
													+ '"'
											+ ' data-slides-min-width="100"'
											+ ' data-controlled-slider="'+slider_id+'"'
											+ (controller_effect !== undefined ? ' data-effect="' + controller_effect + '"' : '')
											+ (controller_interval !== undefined ? ' data-interval="' + controller_interval + '"' : '')
											+ (controller_per_view !== undefined ? ' data-slides-per-view="' + controller_per_view + '"' : '')
											+ (controller_space !== undefined ? ' data-slides-space="' + controller_space + '"' : '')
											+ (controller_height !== undefined ? ' style="height:'+controller_height+'"' : '')
										+ '>'
											+ '<div class="swiper-wrapper">'
												+ controller_html
											+ '</div>'
										+ '</div>'
										+ (controller_controls == 1
											? '<div class="slider_controls_wrap"><a class="slider_prev swiper-button-prev" href="#"></a><a class="slider_next swiper-button-next" href="#"></a></div>'
											: ''
											)
									+ '</div>'
				);
			});
	}
				

	// Swiper Slider
	if (container.find('.slider_swiper:not(.inited)').length > 0) {
		container.find('.slider_swiper:not(.inited)')
			.each(function () {
				"use strict";

				// If slider inside the invisible block - exit
				if (jQuery(this).parents('div:hidden,article:hidden').length > 0)
					return;
				
				// Check attr id for slider. If not exists - generate it
				var slider = jQuery(this);
				var id = slider.attr('id');
				if (id == undefined) {
					id = 'swiper_'+Math.random();
					id = id.replace('.', '');
					slider.attr('id', id);
				}
				var cont = slider.parent().hasClass('slider_swiper_outer') ? slider.parent().attr('id', id+'_outer') : slider;
				var cont_id = cont.attr('id');

				// If this slider is controller for the other slider
				var is_controller = slider.parents('.sc_slider_controller').length > 0;
				var controller_id = slider.data('controller');
				
				// Enum all slides
				slider.find('.swiper-slide').each(function(idx) {
					jQuery(this).attr('data-slide-number', idx);
				});

				// Show slider, but make it invisible
				slider.css({
					'display': 'block',
					'opacity': 0
					})
					.addClass(id)
					.addClass('inited')
					.data('settings', {mode: 'horizontal'});		// VC hook

				// Min width of the slides in swiper (used for validate slides_per_view on small screen)
				var smw = slider.data('slides-min-width');
				if (smw == undefined) {
					smw = 180;
					slider.attr('data-slides-min-width', smw);
				}

				// Validate Slides per view on small screen
				var width = slider.width();
				if (width == 0) width = slider.parent().width();
				var spv = slider.data('slides-per-view');
				if (spv == undefined) {
					spv = 1;
					slider.attr('data-slides-per-view', spv);
				}
				if (width / spv < smw) spv = Math.max(1, Math.floor(width / smw));

				// Space between slides
				var space = slider.data('slides-space');
				if (space == undefined) space = 0;
				
				// Autoplay interval
				var interval = slider.data('interval');
				if (interval === undefined) interval = Math.round(5000 * (1 + Math.random()));
				if (isNaN(interval)) interval = 0;
					
				if (TRX_ADDONS_STORAGE['swipers'] === undefined) TRX_ADDONS_STORAGE['swipers'] = {};

				TRX_ADDONS_STORAGE['swipers'][id] = new Swiper('.'+id, {
					calculateHeight: !slider.hasClass('slider_height_fixed'),
					resizeReInit: true,
					autoResize: true,
				    effect: slider.data('effect') ? slider.data('effect') : 'slide',
					pagination: slider.hasClass('slider_pagination') ? '#'+cont_id+' .slider_pagination_wrap' : false,
				    paginationClickable: slider.hasClass('slider_pagination') ? '#'+cont_id+' .slider_pagination_wrap' : false,
				    paginationType: slider.hasClass('slider_pagination') && slider.data('pagination') ? slider.data('pagination') : 'bullets',
			        nextButton: slider.hasClass('slider_controls') ? '#'+cont_id+' .slider_next' : false,
			        prevButton: slider.hasClass('slider_controls') ? '#'+cont_id+' .slider_prev' : false,
			        autoplay: slider.hasClass('slider_noautoplay') || interval==0	? false : parseInt(interval),
        			autoplayDisableOnInteraction: true,
					initialSlide: 0,
					slidesPerView: spv,
					loopedSlides: spv,
					spaceBetween: space,
					speed: 600,
					centeredSlides: false,	//is_controller,
					loop: true,				//!is_controller
					grabCursor: !is_controller,
					slideToClickedSlide: is_controller,
					touchRatio: is_controller ? 0.2 : 1,
					onSlideChangeStart: function (swiper) {
						// Change outside title
						cont.find('.slider_titles_outside_wrap .active').removeClass('active').fadeOut();
						// Update controller or controlled slider
						var controlled_slider = jQuery('#'+slider.data(is_controller ? 'controlled-slider' : 'controller')+' .slider_swiper');
						var controlled_id = controlled_slider.attr('id');
						if (TRX_ADDONS_STORAGE['swipers'][controlled_id] && jQuery('#'+controlled_id).attr('data-busy')!=1) {
							slider.attr('data-busy', 1);
							setTimeout(function() { slider.attr('data-busy', 0); }, 300);
							var slide_number = jQuery(swiper.slides[swiper.activeIndex]).data('slide-number');
							var slide_idx = controlled_slider.find('[data-slide-number="'+slide_number+'"]').index();
							TRX_ADDONS_STORAGE['swipers'][controlled_id].slideTo(slide_idx);
						}
					},
					onSlideChangeEnd: function (swiper) {
						// Change outside title
						var titles = cont.find('.slider_titles_outside_wrap .slide_info');
						if (titles.length==0) return;
						//titles.eq((swiper.activeIndex-1)%titles.length).addClass('active').fadeIn();
						titles.eq(jQuery(swiper.slides[swiper.activeIndex]).data('slide-number')).addClass('active').fadeIn(300);
						// Remove video
						cont.find('.trx_addons_video_player.with_cover.video_play').removeClass('video_play').find('.video_embed').empty();
						// Unlock slider/controller
						slider.attr('data-busy', 0);
					}
				});
				
				slider.attr('data-busy', 1).animate({'opacity':1}, 'fast');
				setTimeout(function() { slider.attr('data-busy', 0); }, 300);
			});
	}
}


// Init previously hidden sliders with engine=swiper
function trx_addons_init_hidden_sliders(e, container) {
	"use strict";
	// Init sliders in this container
	trx_addons_init_sliders(e, container);
	// Check slides per view on current window size
	trx_addons_resize_sliders(e, container);
}

// Sliders: Resize
jQuery(document).on('action.resize_trx_addons', trx_addons_resize_sliders);
function trx_addons_resize_sliders(e, container) {
	"use strict";

	if (container === undefined) container = jQuery('body');
	container.find('.slider_swiper.inited').each(function() {
		"use strict";
		
		// If slider in the hidden block - don't resize it
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;

		var id = jQuery(this).attr('id');
		var slider_width = jQuery(this).width();
		var slide = jQuery(this).find('.swiper-slide').eq(0);
		var slide_width = slide.width();
		var slide_height = slide.height();
		var last_width = jQuery(this).data('last-width');
		if (isNaN(last_width)) last_width = 0;
		var ratio = jQuery(this).data('ratio');
		if (ratio===undefined || (''+ratio).indexOf(':')<1) {
			ratio = slide_height > 0 ? slide_width+':'+slide_height : "16:9";
			jQuery(this).attr('data-ratio', ratio);
		}
		ratio = ratio.split(':');
		var ratio_x = !isNaN(ratio[0]) ? Number(ratio[0]) : 16;
		var ratio_y = !isNaN(ratio[1]) ? Number(ratio[1]) : 9;
		
		// Resize slider
		if ( !jQuery(this).hasClass('slider_noresize') || jQuery(this).height()==0 ) {
			jQuery(this).height( Math.floor(slide_width/ratio_x*ratio_y) );
		}

		// Change slides_per_view
		if (TRX_ADDONS_STORAGE['swipers'][id].params.slidesPerView != 'auto') {
			if (last_width==0 || last_width!=slider_width) {
				var smw = jQuery(this).data('slides-min-width');
				var spv = jQuery(this).data('slides-per-view');
				if (slider_width / spv < smw) spv = Math.max(1, Math.floor(slider_width / smw));
				jQuery(this).data('last-width', slider_width);
				if (TRX_ADDONS_STORAGE['swipers'][id].params.slidesPerView != spv) {
					TRX_ADDONS_STORAGE['swipers'][id].params.slidesPerView = spv;
					TRX_ADDONS_STORAGE['swipers'][id].params.loopedSlides = spv;
					//TRX_ADDONS_STORAGE['swipers'][id].reInit();
				}
				TRX_ADDONS_STORAGE['swipers'][id].onResize();
			}
		}
	});
}
