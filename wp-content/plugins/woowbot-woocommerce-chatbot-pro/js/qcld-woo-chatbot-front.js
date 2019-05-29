jQuery(function ($) {
    //Global object passed by admin
    var wooChatBotVar = woo_chatbot_obj;
    var LoadWoowBotPlugin = 0;
    var textEditorHandler = 0;
    if (typeof(openingHourIsFn) != 'undefined') {
        var openingHourIs = openingHourIsFn;
    } else {
        var openingHourIs = 0;
    }

    wooChatBotVar.exit_intent_handler = 0;
    wooChatBotVar.scroll_open_handler = 0;
    wooChatBotVar.auto_open_handler = 0;
    wooChatBotVar.re_target_handler = 0;
    $(document).ready(function () {
        //getting window height width
        var widowH = $(window).height();
        var widowW = $(window).width();

        if ($('#woo-chatbot-shortcode-template-container').length == 0 && $('#woo-chatbot-chat-app-shortcode-container').length == 0) {
            //Main woowbot area.
            //show it
            $('#woo-chatbot-ball-wrapper').css({
                'display': 'block',
            });
            //WooChatBot icon  position for mobile
            if (widowW < 667) {
                $('#woo-chatbot-chat-container').css({
                    'right': wooChatBotVar.woo_chatbot_position_x_mobile + 'px',
                    'bottom': wooChatBotVar.woo_chatbot_position_y_mobile + 'px'
                });
            } else {  // for others e.g desktop
                $('#woo-chatbot-chat-container').css({
                    'right': wooChatBotVar.woo_chatbot_position_x + 'px',
                    'bottom': wooChatBotVar.woo_chatbot_position_y + 'px'
                });
            }


            //Facebook Messenger desktop
            setTimeout(function () {
                $('.fb_dialog').css({
                    'right': parseInt(55 + parseInt(wooChatBotVar.woo_chatbot_position_x)) + 'px',
                    'bottom': parseInt(17 + parseInt(wooChatBotVar.woo_chatbot_position_y)) + 'px',
                    'visibility': 'visible'
                });
            }, 3000);

            //Woochatbot icon animation disable or enable
            //Disable WoowBot icon Animation
            if (wooChatBotVar.disable_icon_animation == 1) {
                $('.woo-chatbot-ball').addClass('woo-chatbot-animation-deactive');
            } else {
                $('.woo-chatbot-ball').addClass('woo-chatbot-animation-active');

                //Woochatbot icon animation timing
                //var itemHide = function(){
                //    $('.woo-chatbot-animation-active .woo-chatbot-ball-animation-switch').css({
                //        "opacity": 0,
                //    })
                //};
                var itemHide = function () {
                    $('.woo-chatbot-animation-active .woo-chatbot-ball-animation-switch').fadeOut(1000);
                };
                setTimeout(function () {
                    itemHide()
                }, 1000);

                //Click Animation
                $('.woo-chatbot-animation-active').click(function () {
                    $('.woo-chatbot-animation-active .woo-chatbot-ball-animation-switch').fadeIn(100);
                    setTimeout(function () {
                        itemHide()
                    }, 1000);
                });
            }

            //window resize.
            if (widowW > 767) {
                var ballConH = parseInt(widowH * 0.5);
                $('.woo-chatbot-ball-inner').css({'height': ballConH + 'px'})

                $(window).resize(function () {
                    var widowH = $(window).height();
                    var ballConH = parseInt(widowH * 0.5);
                    $('.woo-chatbot-ball-inner').css({'height': ballConH + 'px'})
                });
            }
            ;
			var botimage = jQuery('#woo-chatbot-ball').find('img').attr('src');
            $(document).on('click', '#woo-chatbot-ball', function (event) {
               // $(".woo-chatbot-mini-mode").siblings("#woo-chatbot-ball").hide();
               
			   //close button
				if($('.active-chat-board').length>0){
					$('#woo-chatbot-ball').removeClass('woobot_chatopen_iconanimation');
					$('#woo-chatbot-ball').addClass('woobot_chatclose_iconanimation');
					$('#woo-chatbot-ball').find('img').attr('src', botimage)		
					$('.woo-chatbot-ball').css('background', '#ffffff');
				}else{
					
					$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
					$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
					$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
					//$('.woo-chatbot-ball').css('background', 'unset');
				}
			    $("#woo-chatbot-board-container").toggleClass('active-chat-board');
                woowbot_board_action();
            });
            //WoowBot proActive start
            //Attention on
            if (wooChatBotVar.enable_meta_title == 1 && wooChatBotVar.meta_label != "") {
                var MetaTitleInterval;
                var orginalTitle = document.title;
                $(document).on("mouseover", 'body', function (e) {
                    document.title = orginalTitle;
                    clearInterval(MetaTitleInterval);
                });
            }
            //Exit Intent
            if (wooChatBotVar.enable_exit_intent == 1) {
                window.addEventListener("mouseout", function (e) {
                    e = e ? e : window.event;

                    // If this is an autocomplete element.
                    if (e.target.tagName.toLowerCase() == "input")
                        return;

                    // Get the current viewport width.
                    var vpWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

                    // If the current mouse X position is within 50px of the right edge
                    // of the viewport, return.
                    if (e.clientX >= (vpWidth - 50))
                        return;

                    // If the current mouse Y position is not within 50px of the top
                    // edge of the viewport, return.
                    if (e.clientY >= 50)
                        return;

                    // Reliable, works on mouse exiting window and
                    // user switching active program
                    var from = e.relatedTarget || e.toElement;
                    if (!from)
                    //if will open once if setup from backend.
                        var exitIntentOpen = true;
                    if ($.cookie('exit_intent') == 'yes' && wooChatBotVar.exit_intent_once == 1) {
                        exitIntentOpen = false;
                    }
                    if ($('.active-chat-board').length == 0 && exitIntentOpen == true) {
                        if (wooChatBotVar.exit_intent_handler == 0) {
                           
						   
						   $('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
							$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
							$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
							//$('.woo-chatbot-ball').css('background', 'unset');
						   
						   
						   
						    $("#woo-chatbot-board-container").addClass('active-chat-board');
							$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
							$("#woo-chatbot-ball").addClass('chat_active');
							setTimeout(function(){	woow_fbicon_position();	},1000);
							
                            wooChatBotVar.exit_intent_handler++;
                            wooChatBotVar.re_target_handler = 1;
                            woowbot_board_action();
                            //Shopper Name
                            if (localStorage.getItem('shopper')) {
                                var shopper = localStorage.getItem('shopper');
                            } else {
                                var shopper = wooChatBotVar.shopper_demo_name;
                            }
                            setTimeout(function () {
                                if (localStorage.getItem("woowHitory")) {
                                    showing_proactive_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.exit_intent_msg);
                                } else {
                                    showing_proactive_double_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.exit_intent_msg)
                                }
                                $.cookie('exit_intent', 'yes');
                                //pro active sound
                                proactive_retargeting_sound();
                                //Window foucus meta title change.
                                window_focus_change_meta_title();
                            }, 1000)
                        }
                    }
                });
            }
            if (wooChatBotVar.enable_scroll_open == 1) {

                $(document).on('scroll', function (event) {
                    var OpenScroll = true;
                    //if will open once if setup from backend.
                    if ($.cookie('scroll_open') == 'yes' && wooChatBotVar.scroll_open_once == 1) {
                        OpenScroll = false;
                    }
                    //it will be open only for single time.
                    if ($('.active-chat-board').length == 0 && OpenScroll == true) {
                        if (wooChatBotVar.scroll_open_handler == 0) {
                            var scrollOpenVal = parseInt(($(document).height() * wooChatBotVar.scroll_open_percent) / 100);
                            if ($(window).scrollTop() + $(window).height() > scrollOpenVal) {
                               
							   
							$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
							$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
							$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
							//$('.woo-chatbot-ball').css('background', 'unset');
							   
							   
							    $("#woo-chatbot-board-container").addClass('active-chat-board');
								$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
								$("#woo-chatbot-ball").addClass('chat_active');
								setTimeout(function(){	woow_fbicon_position();	},1000);
                                wooChatBotVar.scroll_open_handler++;
                                wooChatBotVar.re_target_handler = 2;
                                woowbot_board_action();
                                //Shopper Name
                                if (localStorage.getItem('shopper')) {
                                    var shopper = localStorage.getItem('shopper');
                                } else {
                                    var shopper = wooChatBotVar.shopper_demo_name;
                                }
                                setTimeout(function () {
                                    if (localStorage.getItem("woowHitory")) {
                                        showing_proactive_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.scroll_open_msg);
                                    } else {
                                        showing_proactive_double_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.scroll_open_msg)
                                    }
                                    $.cookie('scroll_open', 'yes');
                                    //pro active sound
                                    proactive_retargeting_sound();
                                    //Window foucus meta title change.
                                    window_focus_change_meta_title();
                                }, 1000)
                            }
                        }
                    }

                });
            }
            if (wooChatBotVar.enable_auto_open == 1) {
                //if will open once if setup from backend.
                var autoOpen = true;
                if ($.cookie('auto_open') == 'yes' && wooChatBotVar.auto_open_once == 1) {
                    autoOpen = false;
                }
                if (wooChatBotVar.auto_open_handler == 0 && autoOpen == true) {
                    setTimeout(function (e) {
                        if ($('.active-chat-board').length == 0) {
                            
							$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
							$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
							$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
							//$('.woo-chatbot-ball').css('background', 'unset');
							
							$("#woo-chatbot-board-container").addClass('active-chat-board');
							$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
							$("#woo-chatbot-ball").addClass('chat_active');
							setTimeout(function(){	woow_fbicon_position();	},1000);
                            wooChatBotVar.auto_open_handler++;
                            wooChatBotVar.re_target_handler = 3;
                            woowbot_board_action();
                            //Shopper Name
                            if (localStorage.getItem('shopper')) {
                                var shopper = localStorage.getItem('shopper');
                            } else {
                                var shopper = wooChatBotVar.shopper_demo_name;
                            }
                            setTimeout(function () {
                                if (localStorage.getItem("woowHitory")) {
                                    showing_proactive_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.auto_open_msg);
                                } else {
                                    showing_proactive_double_msg(wooChatBotVar.ret_greet + ' ' + shopper + ', ' + wooChatBotVar.auto_open_msg)
                                }
                                $.cookie('auto_open', 'yes');
                                //pro active sound
                                proactive_retargeting_sound();
                                //Window foucus meta title change.
                                window_focus_change_meta_title();
                            }, 1000)
                        }
                    }, parseInt(wooChatBotVar.auto_open_time * 1000));
                }
            }
            //Retargeting for Cart to complete checkout.
            if (wooChatBotVar.enable_ret_user_show == 1 && localStorage.getItem("woowHitory") && $.cookie('return_user') != 'yes') {

                $.cookie('return_user', 'yes');
                var data = {'action': 'qcld_woo_chatbot_only_cart'};
                jQuery.post(wooChatBotVar.ajax_url, data, function (response) {
                    if (response.items > 0) {
                        if ($('.active-chat-board').length == 0) {
                            setTimeout(function () {
                                
								
							$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
							$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
							$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
							//$('.woo-chatbot-ball').css('background', 'unset');	
								
								
								$("#woo-chatbot-board-container").addClass('active-chat-board');
								$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
								$("#woo-chatbot-ball").addClass('chat_active');
								setTimeout(function(){	woow_fbicon_position();	},1000);
                                woowbot_board_action();
                                showing_proactive_msg(wooChatBotVar.checkout_msg);
                                setTimeout(function () {
                                    showing_proactive_msg(response.html);
                                    //Window foucus meta title change.
                                    window_focus_change_meta_title();
                                }, 2000);
                            }, 1000);
                        }
                    }
                });
            } else {
                $.cookie('return_user', 'yes');
            }

            if (wooChatBotVar.enable_inactive_time_show == 1 && localStorage.getItem("woowHitory")) {
                var timeoutID;

                function setup() {
                    this.addEventListener("mousemove", resetTimer, false);
                    this.addEventListener("mousedown", resetTimer, false);
                    this.addEventListener("keypress", resetTimer, false);
                    this.addEventListener("DOMMouseScroll", resetTimer, false);
                    this.addEventListener("mousewheel", resetTimer, false);
                    this.addEventListener("touchmove", resetTimer, false);
                    this.addEventListener("MSPointerMove", resetTimer, false);

                    startTimer();
                }

                setup();

                function startTimer() {
                    // wait as set from admin seconds before calling goInactive
                    timeoutID = window.setTimeout(goInactive, parseInt(wooChatBotVar.inactive_time * 1000));
                }

                function resetTimer(e) {
                    window.clearTimeout(timeoutID);

                    goActive();
                }

                function goInactive() {
                    if (wooChatBotVar.ret_inactive_user_once == 1 && $.cookie('return_inactive_user') != 'yes') {
                        $.cookie('return_inactive_user', 'yes');
                        var data = {'action': 'qcld_woo_chatbot_only_cart'};
                        jQuery.post(wooChatBotVar.ajax_url, data, function (response) {
                            if (response.items > 0) {
                                if ($('.active-chat-board').length == 0) {
                                    setTimeout(function () {
                                        
										
									$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
									$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
									$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
									//$('.woo-chatbot-ball').css('background', 'unset');
										
										
										$("#woo-chatbot-board-container").addClass('active-chat-board');
										$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
										$("#woo-chatbot-ball").addClass('chat_active');
										setTimeout(function(){	woow_fbicon_position();	},1000);
                                        woowbot_board_action();
                                        showing_proactive_msg(wooChatBotVar.checkout_msg);
                                        setTimeout(function () {
                                            showing_proactive_msg(response.html);
                                            //Window foucus meta title change.
                                            window_focus_change_meta_title();
                                        }, 2000);
                                    }, 2000);
                                }
                            }
                        });
                    } else {
                        $.cookie('return_inactive_user', 'yes');
                    }
                }

                function goActive() {
                    // do something

                    startTimer();
                }
            }

            //Proactive retargeting sound for auto open. scroll open and
            function proactive_retargeting_sound() {
                if (wooChatBotVar.enable_ret_sound == 1) {
                    var promise = document.querySelector('#woo-chatbot-proactive-audio').play();
                    if (promise !== undefined) {
                        promise.then(function (success) {
                            //success to play
                        }).catch(function (error) {
                            //some error
                            //console.log(error);
                        });
                    }
                }
            }

            //When user will be out of window and news retargetting will be shown. where opening hour, title and meta need to be set.
            function window_focus_change_meta_title() {
                if (wooChatBotVar.enable_meta_title == 1 && wooChatBotVar.meta_label != "" && openingHourIs == 0) {
                    var showInactive = 0;
                    MetaTitleInterval = setInterval(function () {
                        if (showInactive == 0) {
                            document.title = wooChatBotVar.meta_label;
                            showInactive = 1;
                        } else {
                            document.title = orginalTitle;
                            showInactive = 0;
                        }
                    }, 1000);
                }
            }

            //WoowBot proActive end
            function woowbot_board_action() {
                if (widowW <= 767 && wooChatBotVar.mobile_full_screen == 1 && wooChatBotVar.template_name!='mini-mode') {//For mobile
                    if ($('#woo-chatbot-mobile-close').length <= 0) {
                        $('.woo-chatbot-board-container').append('<div id="woo-chatbot-mobile-close">X</div>');
                    }
                    $('.woo-chatbot-ball-inner').slimScroll({
                        height: '100hv',
                        start: 'bottom',
                        wheelStep: 140,
                    }).parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                    $('#woo-chatbot-chat-container').css({'bottom': '0', 'left': '0', 'right': '0'});
                    $('#woo-chatbot-ball').hide();
                    //Maintain inner chat box height
                    var widowH = $(window).height();
                    var headerH = $('.woo-chatbot-header').outerHeight();
                    var footerH = $('.woo-chatbot-footer').outerHeight();
                    var AppContentInner = widowH - footerH - headerH;
                    //alert(footerH);
                    $('.woo-chatbot-ball-inner').css({'height': AppContentInner + 'px'})
                    $(this).hide();
                } else if (widowW <= 767) {
                    $('.woo-chatbot-header').append('<div id="woo-chatbot-desktop-close">X</div>');
                    $('.woo-chatbot-ball-inner').slimScroll({
                        height: '55hv',
                        start: 'bottom',
                        wheelStep: 140,
                    }).parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                }
                else {
                    $('.woo-chatbot-header').append('<div id="woo-chatbot-desktop-close">X</div>');
                    $('.woo-chatbot-ball-inner').slimScroll({
                        height: '55hv',
                        start: 'bottom',
                    }).parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                }


                //Here is the Plugin  to be load only for once.
                if (LoadWoowBotPlugin == 0) {
                    $.woowbot({obj: wooChatBotVar, editor_handler: textEditorHandler});
                    LoadWoowBotPlugin++;
                }
                //If product detials is open then it will be closed.
                $('.woo-chatbot-product-container').removeClass('active-chatbot-product-details');
                //Show and close notification message on ball click
                if ($('.active-chat-board').length != 0) {
                    $('#woo-chatbot-notification-container').removeClass('woo-chatbot-notification-container-sliding');
                    //chatbox will be open and notificaion will be closed
                    $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-disable');
                    //clearInterval(notificationInterval);
                } else {
                    if (!sessionStorage.getItem('wooChatbotNotification')) {
                        $('#woo-chatbot-notification-container').removeClass('woo-chatbot-notification-container-disable');
                        $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-sliding');

                    }
                    /// clearInterval(notificationInterval);
                }
                //Messenger handling.
                if ($('.active-chat-board').length > 0) {
                    $('#woo-chatbot-integration-container').show();
                } else {
                    $('#woo-chatbot-integration-container').hide();
                }
            }

            function showing_proactive_msg(msg) {
                //first open then chatboard
                if (localStorage.getItem("woowHitory") && !$('.woo-chatbot-operation-option[data-option="chat"]').parent().hasClass('woo-chatbot-operation-active')) {
                    $('.woo-chatbot-messages-wrapper').html(localStorage.getItem("woowHitory"));
                    $('.woo-chatbot-operation-option').each(function () {
                        if ($(this).attr('data-option') == 'chat') {
                            $(this).parent().addClass('woo-chatbot-operation-active');
                        } else {
                            $(this).parent().removeClass('woo-chatbot-operation-active');
                        }
                    });
                }
                var msgContent = '<li class="woo-chatbot-msg">' +
                    '<div class="woo-chatbot-avatar">' +
                    '<img src="' + wooChatBotVar.agent_image_path + '" alt="">' +
                    '</div>' +
                    '<div class="woo-chatbot-agent">' + wooChatBotVar.agent + '</div>'
                    + '<div class="woo-chatbot-paragraph"><img class="woo-chatbot-comment-loader" src="' + wooChatBotVar.image_path + 'comment.gif" alt="Typing..." /></div></li>';
                $('#woo-chatbot-messages-container').append(msgContent);
                //Scroll to the last message
                $('.woo-chatbot-ball-inner').animate({scrollTop: $('.woo-chatbot-messages-wrapper').prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                setTimeout(function () {
                    $('#woo-chatbot-messages-container li:last .woo-chatbot-paragraph').html('<div class="woo-chatbot-proactive-msg">' + msg + '</div>').addClass('woo-chatbot-proactive');
                    //scroll to the last message
                    $('.woo-chatbot-ball-inner').animate({scrollTop: $('.woo-chatbot-messages-wrapper').prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                }, 2000);
            }

            function showing_proactive_double_msg(secondMsg) {
                //first open then chatboard
                if (localStorage.getItem("woowHitory")) {
                    $('.woo-chatbot-messages-wrapper').html(localStorage.getItem("woowHitory"));
                    $('.woo-chatbot-operation-option').each(function () {
                        if ($(this).attr('data-option') == 'chat') {
                            $(this).parent().addClass('woo-chatbot-operation-active');
                        } else {
                            $(this).parent().removeClass('woo-chatbot-operation-active');
                        }
                    });
                }
                var fristMsg = "<strong>" + wooChatBotVar.agent + " </strong> " + wooChatBotVar.agent_join[0];
                var msgContent = '<li class="woo-chatbot-msg">' +
                    '<div class="woo-chatbot-avatar">' +
                    '<img src="' + wooChatBotVar.agent_image_path + '" alt="">' +
                    '</div>' +
                    '<div class="woo-chatbot-agent">' + wooChatBotVar.agent + '</div>'
                    + '<div class="woo-chatbot-paragraph"><img class="woo-chatbot-comment-loader" src="' + wooChatBotVar.image_path + 'comment.gif" alt="Typing..." /></div></li>';
                $('#woo-chatbot-messages-container').append(msgContent);
                //Scroll to the last message
                $('.woo-chatbot-ball-inner').animate({scrollTop: $('.woo-chatbot-messages-wrapper').prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});

                setTimeout(function () {
                    $('#woo-chatbot-messages-container li:last .woo-chatbot-paragraph').html(fristMsg);
                    //Second Message with interval
                    $('#woo-chatbot-messages-container').append(msgContent);
                    //Scroll to the last message
                    $('.woo-chatbot-ball-inner').animate({scrollTop: $('.woo-chatbot-messages-wrapper').prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
                    setTimeout(function () {
                        $('#woo-chatbot-messages-container li:last .woo-chatbot-paragraph').html('<div class="woo-chatbot-proactive-msg">' + secondMsg + '</div>').addClass('woo-chatbot-proactive');
                        //Scroll to the last message
                        $('.woo-chatbot-ball-inner').animate({scrollTop: $('.woo-chatbot-messages-wrapper').prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});

                    }, 2000);

                }, 2000);
            }

            $(document).on('click', '#woo-chatbot-mobile-close, #woo-chatbot-desktop-close', function (event) {
                
				if($('.active-chat-board').length>0){
					$('#woo-chatbot-ball').removeClass('woobot_chatopen_iconanimation');
					$('#woo-chatbot-ball').addClass('woobot_chatclose_iconanimation');
					$('#woo-chatbot-ball').find('img').attr('src', botimage)		
					$('.woo-chatbot-ball').css('background', '#ffffff');
				}else{
					$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
					$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
					$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
					//$('.woo-chatbot-ball').css('background', 'unset');
				}
				
				
				$("#woo-chatbot-board-container").toggleClass('active-chat-board');
				
				
                if (sessionStorage.getItem('wooChatbotNotification') && sessionStorage.getItem('wooChatbotNotification') == 'removed') {
                    //if remove on the session.
                    $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-disable');
                } else {
                    $("#woo-chatbot-notification-container").removeClass('woo-chatbot-notification-container-disable').addClass('woo-chatbot-notification-container-sliding');
                }
                // $("#woo-chatbot-notification-container").removeClass('woo-chatbot-notification-container-disable').addClass('woo-chatbot-notification-container-sliding');
                //WooChatBot icon  position for mobile
                if (widowW < 667) {
                    $('#woo-chatbot-chat-container').css({
                        'right': wooChatBotVar.woo_chatbot_position_x_mobile + 'px',
                        'bottom': wooChatBotVar.woo_chatbot_position_y_mobile + 'px'
                    });
                } else {  // for others e.g desktop
                    $('#woo-chatbot-chat-container').css({
                        'right': wooChatBotVar.woo_chatbot_position_x + 'px',
                        'bottom': wooChatBotVar.woo_chatbot_position_y + 'px'
                    });
                }
                $('#woo-chatbot-ball').show();
                //Facebook Messenger.
                if ($('.active-chat-board').length > 0) {
                    $('#woo-chatbot-integration-container').show();
                } else {
                    $('#woo-chatbot-integration-container').hide();
                }
            });


            $("#qcld-woo-chatbot-shortcode-style-css").attr("disabled", "disabled");
            /***
             * Notification Message
             */
            if ($('#woo-chatbot-notification-container').length > 0) {
                if (sessionStorage.getItem('wooChatbotNotification') && sessionStorage.getItem('wooChatbotNotification') == 'removed') {
                    //if remove on the session.
                    $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-disable');
                } else {
                    //Notification comes with slideIn effect
                    $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-sliding');
                    //handling welcome & return user welcome msg.
                    if ($.cookie("shopper")) {
                        var shopper = $.cookie("shopper");
                        var welcomeMsg = wooChatBotVar.welcome_back[0] + ' <strong>' + shopper + '!</strong>';
                    } else {
                        var welcomeMsg = wooChatBotVar.welcome[0] + ' <strong>' + wooChatBotVar.host + '!</strong>';

                    }
                    $('.woo-chatbot-notification-welcome').html(welcomeMsg);
                    //Notifications msgs handling.
                    var notifications = wooChatBotVar.notifications;
                    if (notifications.length > 1) {
                        var totalNotMsg = wooChatBotVar.notifications.length;
                        var notMsgIndex = 0;
                        var intervalTime = parseInt(wooChatBotVar.notification_interval) * 1000;
                        var notificationInterval = setInterval(function (e) {
                            notMsgIndex++;
                            if (totalNotMsg <= notMsgIndex) {
                                notMsgIndex = 0;
                            }
                            //show new notification time after every intervalTime
                            $('.woo-chatbot-notification-message').css({'opacity': 1}).html(notifications[notMsgIndex]);
                        }, intervalTime);
                    }

                    $(".woo-chatbot-notification-close").click(function () {
                        $('#woo-chatbot-notification-container').addClass('woo-chatbot-notification-container-disable');
                        //clearInterval(notificationInterval);
                        sessionStorage.setItem('wooChatbotNotification', 'removed');
                    });
                }
            }
        }
        else if ($('#woo-chatbot-shortcode-template-container').length > 0) { //Page shortcode area.
            $('#woo-chatbot-chat-container').css({'display': 'none'});
            $('#woo-chatbot-ball').hide();
            //Add Scroll to chat ui
            $('.woo-chatbot-ball-inner').slimScroll({
                height: '60hv',
                start: 'bottom'
            }).parent().find('.slimScrollBar').css({'top': $('.woo-chatbot-ball-inner').height() + 'px'});
            //Add scroll to cart part
            var recentViewHeight = $('.woo-chatbot-container').outerHeight();
            if ($('.chatbot-shortcode-template-02').length == 0) {
                $('.woo-chatbot-cart-body').slimScroll({height: '200px', start: 'top'});
                $('.woo-chatbot-widget .woo-chatbot-products').slimScroll({height: '435px', start: 'top'});
            }

            //Remove style of template
            $("#qcld-woo-chatbot-style-css").attr("disabled", "disabled");
            //Here is the Plugin  to be load only for once.
            if (LoadWoowBotPlugin == 0) {
                $.woowbot({obj: wooChatBotVar, editor_handler: textEditorHandler});
                LoadWoowBotPlugin++;
            }


        }
        else if ($('#woo-chatbot-chat-app-shortcode-container').length > 0) {  //App shortcode area.

            textEditorHandler = 1;
            //App UI (ball inner)
            setTimeout(function () {
                var widowH = window.innerHeight;
                //var widowH = $(window).height();
                //var headerH = $('.woo-chatbot-header').outerHeight();
                var footerH = $('.woo-chatbot-footer').outerHeight();

                var AppContentInner = widowH - footerH;
                console.log('Windows : ' + widowH + 'Hieght :' + AppContentInner);
                $('#woo-chatbot-chat-app-shortcode-container .woo-chatbot-ball-inner').css({'height': AppContentInner + 'px'})
            }, 300);
            $(window).resize(function () {
                setTimeout(function () {
                    var widowH = window.innerHeight;
                    //var widowH = $(window).height();
                    //var headerH = $('.woo-chatbot-header').outerHeight();
                    var footerH = $('.woo-chatbot-footer').outerHeight();
                    var AppContentInner = widowH - footerH;
                    //alert(footerH);
                    $('#woo-chatbot-chat-app-shortcode-container .woo-chatbot-ball-inner').css({'height': AppContentInner + 'px'})
                }, 300)
            });

            $('#woo-chatbot-ball').hide();
            //Add Scroll to chat ui
            $("#qcld-woo-chatbot-shortcode-style-css").attr("disabled", "disabled");
            
			$('#woo-chatbot-ball').removeClass('woobot_chatclose_iconanimation');
			$('#woo-chatbot-ball').addClass('woobot_chatopen_iconanimation');
			$('#woo-chatbot-ball').find('img').attr('src', wooChatBotVar.imgurl+'woowbot-close-icon.png');
			//$('.woo-chatbot-ball').css('background', 'unset');	
			
			
			$("#woo-chatbot-board-container").addClass('active-chat-board');
			$('.woo-chatcontainer_mini-mode').css({'transform': 'translateX(0px)'});
			$("#woo-chatbot-ball").addClass('chat_active');
			setTimeout(function(){	woow_fbicon_position();	},1000);
            $('.woo-chatbot-ball-inner').slimScroll({
                height: '55hv',
                start: 'bottom'
            }).parent().find('.slimScrollBar').css({'top': $(window).height() + 'px'});
            if (LoadWoowBotPlugin == 0) {
                $.woowbot({obj: wooChatBotVar, editor_handler: textEditorHandler});
                LoadWoowBotPlugin++;
            }
            //Handling app cart and checkout
            $('#woo-chatbot-cart-short-code').hide();
            $('#woo-chatbot-checkout-short-code').hide();
            $(document).on('click', '.woo-chatbot-cart-link', function (event) {
                $('.woo-chatbot-messages-wrapper').hide();
                $('#woo-chatbot-checkout-short-code').hide();
                $('#woo-chatbot-cart-short-code').show();
                event.preventDefault();
                $('#woo-chatbot-cart-short-code').html('<img class="woo-chatbot-comment-loader" src="' + wooChatBotVar.image_path + 'comment.gif" alt="..." />');
                var data = {'action': 'qcld_woo_chatbot_cart_page'};
                jQuery.post(wooChatBotVar.ajax_url, data, function (response) {
                    $("#woo-chatbot-cart-short-code").html(response);
                });
            });
            $(document).on('click', '.woo-chatbot-checkout-link, .checkout-button', function (event) {
               
			   //added to prevent the rediction when used in woowbot theme
			   if($('#woo-chatbot-chat-app-shortcode-container').hasClass('from_theme')){
					//do nothing
					//return false;
					//exit;
				}else{
					event.preventDefault();
					
					$('.woo-chatbot-messages-wrapper').hide();
					$('#woo-chatbot-cart-short-code').hide();
					$('#woo-chatbot-checkout-short-code').show();
	
	
					$('#woo-chatbot-checkout-short-code').html('<img class="woo-chatbot-comment-loader" src="' + wooChatBotVar.image_path + 'comment.gif" alt="..." />');
					var data = {'action': 'qcld_woo_chatbot_checkout_page'};
					jQuery.post(wooChatBotVar.ajax_url, data, function (response) {
						if (response.status == 'yes') {
							window.location.href = response.html;
						} else {
							$("#woo-chatbot-checkout-short-code").html(response.html);
						}
	
					});
				}
				
            });
            //Preventing url redirect from cart page.
            $(document).on('click', '#woo-chatbot-chat-app-shortcode-container .woocommerce-cart-form a', function (e) {
                e.preventDefault();
            });
        }
        //For variable product configuration
        $(document).on('change', "#woo-chatbot-product-variable select ", function () {
            var variations = JSON.parse($("#woo-chatbot-variation-data").attr('data-product-variation'));
            var item_conditions = [];

            var totalAttr = $("#woo-chatbot-product-variable select").length;
            var i = 1;
            $("#woo-chatbot-product-variable select").each(function (index, element) {
                var myVal = $(this).find('option:selected').val();
                if (myVal != "") {
                    item_conditions.push({
                        'left': 'item["variation_data"]["' + $(this).attr('name') + '"][0]',
                        'right': myVal
                    })
                }
            });
            var newVariation = [];
            for (var a = 0; variations.length > a; a++) {
                var item = variations[a];
                var item_condition = "";
                for (var i = 0; item_conditions.length > i; i++) {

                    if (i > 0) {
                        item_condition += ' && ' + '"' + eval(item_conditions[i].left).toLowerCase() + '"' + '==' + '"' + item_conditions[i].right.toLowerCase() + '"';
                    } else {
                        item_condition += '"' + eval(item_conditions[i].left).toLowerCase() + '"' + '==' + '"' + item_conditions[i].right.toLowerCase() + '"';
                    }
                }
                if (eval(item_condition)) {
                    newVariation[0] = item;
                }
            }
            if (newVariation.length > 0) {
                $('#woo-chatbot-variation-add-to-cart').attr('variation_id', newVariation[0]['variation_id']);
                var priceSets = "";
                if (newVariation[0]['variation_data']['_sale_price'][0] != "") {
                    priceSets += '<strike>' + wooChatBotVar.currency_symbol + newVariation[0]['variation_data']['_regular_price'][0] + '</strike>  <strong>' + wooChatBotVar.currency_symbol + newVariation[0]['variation_data']['_sale_price'][0] + '</strong>'
                } else {
                    priceSets += '<strong>' + wooChatBotVar.currency_symbol + newVariation[0]['variation_data']['_regular_price'][0] + '</strong>';
                }
                $('#woo-chatbot-product-price').html(priceSets);
            }
        });

        if ($('.active-chat-board').length > 0) {
            $('#woo-chatbot-integration-container').show();
        } else {
            $('#woo-chatbot-integration-container').hide();
        }
        //Facebook Messenger Integration
        /* if(wooChatBotVar.enable_messenger == 1){
         $(document).on('click','.fb_dialog',function (e) {
         $('#woo-chatbot-board-container').removeClass('active-chat-board');
         $('.fb_dialog').css({'display': 'inline'});
         setTimeout(function (e) {
         $('.fb-customerchat >span').css({'display': 'inline'});
         $('.fb_dialog').trigger('click'); //.css({'display': 'none'});
         },300);
         $('#woo-chatbot-integration-container').hide();
         });
         }*/
        //skype
        if (wooChatBotVar.enable_skype == 1) {
            $(document).on('click', '.inetegration-skype-btn', function (e) {
                $('#woo-chatbot-board-container').removeClass('active-chat-board');
                // $('.lwc-button-icon').trigger('click');
                $('#woo-chatbot-integration-container').hide();
            });

        }


    });
	
	
	
	function woow_fbicon_position(){
				if(jQuery('.woo-chatcontainer_mini-mode #woo-chatbot-ball').length>0){
					
					var pos = jQuery('#woo-chatbot-ball').offset();
					console.log(pos);
					jQuery('.fb_dialog').css({
						'left': parseInt(parseInt(pos.left) - 30) + 'px',
						'top': parseInt(parseInt(pos.top) + 10) + 'px',
						'visibility': 'visible'
					});
					
				}
			}


});
