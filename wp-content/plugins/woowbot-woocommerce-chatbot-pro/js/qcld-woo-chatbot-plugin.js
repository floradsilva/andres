/*
 * Project:      WoowBot jQuery Plugin
 * Description:  WoowBot AI based Chatting functionality are handled .
 * Author:       QuantumCloud
 * Version:      1.0
 */
(function($) {

    /*
     * Global variable as object will beused to handle
     * woowbot chatting initialize, tree change transfer,
     * changing tree steps and cookies etc.
     */
    var globalWoow={
        initialize:0,
        settings:{},
        wildCard:0,
        wildcards:'',
        wildcardsHelp:['start','product','catalog','support','order','reset'],
        productStep:'asking',
        orderStep:'welcome',
        supportStep:'welcome',
        hasNameCookie:$.cookie("shopper"),
        shopperUserName:'',
        shopperEmail:'',
        shopperMessage:'',
        emptymsghandler:0,
        repeatQueryEmpty:'',
        woowIsWorking:0,
        ai_step:0,
        df_status_lock:0,
        sbs_search_flag:0,

    };
    /*
     * Woowbot welcome section coverd
     * greeting for new and already visited shopper
     * based the memory after asking thier name.
     */

    var woowWelcome={
        greeting:function () {
			 //Very begining greeting.
            console.log(globalWoow.settings.obj.skip_woo_greetings);
			if(globalWoow.settings.obj.skip_woo_greetings==1){
				if(globalWoow.settings.obj.re_target_handler==0){
				var botJoinMsg="<strong>"+globalWoow.settings.obj.agent+" </strong> "+woowKits.randomMsg(globalWoow.settings.obj.agent_join);
				woowMsg.single(botJoinMsg);
				}

				$.cookie("shopper", globalWoow.settings.obj.shopper_demo_name, { expires : 365 });
				localStorage.setItem('shopper',globalWoow.settings.obj.shopper_demo_name);
				globalWoow.hasNameCookie=globalWoow.settings.obj.shopper_demo_name;
				globalWoow.ai_step=1;
				globalWoow.wildCard=0;
				localStorage.setItem("wildCard",  globalWoow.wildCard);
				localStorage.setItem("aiStep", globalWoow.ai_step);
				
				setTimeout(function(){
					var firstMsg=woowKits.randomMsg(globalWoow.settings.obj.hi_there)+' '+woowKits.randomMsg(globalWoow.settings.obj.welcome)+" <strong>"+globalWoow.settings.obj.host+"!</strong> ";
					var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
					woowMsg.single(firstMsg);
					
					setTimeout(function(){
						
						woowMsg.double_nobg(serviceOffer, globalWoow.wildcards);
					}, globalWoow.settings.preLoadingTime);
					
				}, globalWoow.settings.preLoadingTime);
				
			}else{
			
				if(globalWoow.settings.obj.re_target_handler==0){
					var botJoinMsg="<strong>"+globalWoow.settings.obj.agent+" </strong> "+woowKits.randomMsg(globalWoow.settings.obj.agent_join);
					woowMsg.single(botJoinMsg);
				}
				//Showing greeting for name in cookie or fresh shopper.
				setTimeout(function(){
					var firstMsg=woowKits.randomMsg(globalWoow.settings.obj.hi_there)+' '+woowKits.randomMsg(globalWoow.settings.obj.welcome)+" <strong>"+globalWoow.settings.obj.host+"!</strong> ";
					var secondMsg=woowKits.randomMsg(globalWoow.settings.obj.asking_name);
					woowMsg.double(firstMsg,secondMsg);
				}, globalWoow.settings.preLoadingTime);
        	}
		}
		
		
    };
    //Append the message to the message container based on the requirement.
    var woowMsg={
        single:function (msg) {
            globalWoow.woowIsWorking=1;
            $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
            //Scroll to the last message
            woowKits.scrollTo();
            setTimeout(function(){
                $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').html(msg);
                //If has youtube link then show video
                woowKits.videohandler();
                //scroll to the last message
                woowKits.scrollTo();
                //Enable the editor
                woowKits.enableEditor(woowKits.randomMsg(globalWoow.settings.obj.send_a_msg));
                //keeping in history
                woowKits.woowHistorySave();
            }, globalWoow.settings.preLoadingTime);

        },

        single_nobg:function (msg) {
            globalWoow.woowIsWorking=1;
            $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
            //Scroll to the last message
            woowKits.scrollTo();
            setTimeout(function(){
                $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').parent().addClass('woo-chatbot-msg-flat').html(msg);
                //scroll to the last message
                woowKits.scrollTo();
                //Enable the editor
                woowKits.enableEditor(woowKits.randomMsg(globalWoow.settings.obj.send_a_msg));
                //Keeping the chat history in localStorage
                woowKits.woowHistorySave();
                // disabled editor
                // woowKits.disableEditor('Please choose an option.');
            }, globalWoow.settings.preLoadingTime);
        },

        double:function (fristMsg,secondMsg) {
            globalWoow.woowIsWorking=1;
            $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
            //Scroll to the last message
            woowKits.scrollTo();
            setTimeout(function(){
                $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').html(fristMsg);
                //Second Message with interval
                $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
                //Scroll to the last message
                woowKits.scrollTo();
                setTimeout(function(){
                    $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').html(secondMsg);
                    //Scroll to the last message
                    woowKits.scrollTo();
                    //Enable the editor
                    woowKits.enableEditor(woowKits.randomMsg(globalWoow.settings.obj.send_a_msg));
                    //keeping in history
                    woowKits.woowHistorySave();
                }, globalWoow.settings.preLoadingTime);

            }, globalWoow.settings.preLoadingTime);

        },
        double_nobg:function (fristMsg,secondMsg) {
            globalWoow.woowIsWorking=1;
            $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
            //Scroll to the last message
            woowKits.scrollTo();
            setTimeout(function(){
                $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').html(fristMsg);
                //Second Message with interval
                $(globalWoow.settings.messageContainer).append(woowKits.botPreloader());
                //Scroll to the last message
                woowKits.scrollTo();
                setTimeout(function(){
                    if(globalWoow.wildCard>0){
                        $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').parent().addClass('woo-chatbot-msg-flat').html(secondMsg).append('<span class="qcld-chatbot-wildcard"  data-wildcart="back">' + woowKits.randomMsg(globalWoow.settings.obj.back_to_start) + '</span>');
                    }else{
                        $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').parent().addClass('woo-chatbot-msg-flat').html(secondMsg);
                    }
                    //scroll to the last message
                    woowKits.scrollTo();
                    //Enable the editor
                    if(globalWoow.wildCard==3 && globalWoow.supportStep=='welcome'){
                        woowKits.disableEditor(woowKits.randomMsg(globalWoow.settings.obj.wildcard_support));
                    }else{
                        woowKits.enableEditor(woowKits.randomMsg(globalWoow.settings.obj.send_a_msg));
                    }
                    //keeping in history
                    woowKits.woowHistorySave();
                    // disabled editor
                    // woowKits.disableEditor('Please choose an option.');
                }, globalWoow.settings.preLoadingTime);

            }, globalWoow.settings.preLoadingTime);

        },
        shopper:function (shopperMsg) {
            $(globalWoow.settings.messageContainer).append(woowKits.shopperMsgDom(shopperMsg));
            //scroll to the last message
            woowKits.scrollTo();
            //keeping in history
            woowKits.woowHistorySave();
        },
        shopper_choice:function (shopperChoice) {
            $(globalWoow.settings.messageLastChild).fadeOut(globalWoow.settings.preLoadingTime);
            $(globalWoow.settings.messageContainer).append(woowKits.shopperMsgDom(shopperChoice));
            //scroll to the last message
            woowKits.scrollTo();
            //keeping in history
            woowKits.woowHistorySave();
        }

    };

    //Every tiny tools are implemented  in woowKits as object literal.
    var woowKits={
        enableEditor:function(placeHolder){
            if(globalWoow.settings.editor_handler==0){
                $("#woo-chatbot-editor").attr('disabled',false).focus();
                $("#woo-chatbot-editor").attr('placeholder',placeHolder);
                $("#woo-chatbot-send-message").attr('disabled',false);
            }
        },
        disableEditor:function (placeHolder) {
            if(globalWoow.settings.editor_handler==0){
                $("#woo-chatbot-editor").attr('placeholder',placeHolder);
                $("#woo-chatbot-editor").attr('disabled',true);
                $("#woo-chatbot-send-message").attr('disabled',true);
            }
            //Remove extra pre loader.
            if($('.woo-chatbot-messages-container').find('.woo-chatbot-comment-loader').length>0){
                $('.woo-chatbot-messages-container').find('.woo-chatbot-comment-loader').parent().parent().hide();
            }
        },
        woowHistorySave:function () {
            //setTimeout(function () {
            globalWoow.woowIsWorking=0;
            var woowHistory= $(globalWoow.settings.messageWrapper).html();
            localStorage.setItem("woowHitory", woowHistory);
            //},globalWoow.settings.wildcardsShowTime);
        },

        randomMsg:function(arrMsg){
            var index=Math.floor(Math.random() * arrMsg.length);
            return arrMsg[index];
        },
        ajax:function (data) {
            return jQuery.post(globalWoow.settings.obj.ajax_url, data);

        },
        dailogAIOAction:function(text){
            return  jQuery.ajax({
                type : "POST",
                url :"https://api.dialogflow.com/v1/query?v=20170712",
                contentType : "application/json; charset=utf-8",
                dataType : "json",
                headers : {
                    "Authorization" : "Bearer "+globalWoow.settings.obj.ai_df_token
                },
                data: JSON.stringify( {
                    query: text,
                    //lang : globalWoow.settings.obj.language,
                    lang : 'en-US',
                    sessionId: 'WoowBot_df_2018071'
                } )
            });
        },
        sugestCat:function () {
            var productSuggest=woowKits.randomMsg(globalWoow.settings.obj.product_suggest);
            var data={'action':'qcld_woo_chatbot_category'};
            var result=woowKits.ajax(data);
            result.done(function( response ) {
                woowMsg.double_nobg(productSuggest,response);
                if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                    globalWoow.wildCard=0;
                    globalWoow.ai_step=1;
                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                    localStorage.setItem("aiStep", globalWoow.ai_step);
                }
            });
        },
        subCats:function (parentId) {
            var subCatMsg=woowKits.randomMsg(globalWoow.settings.obj.product_suggest);
            var data={'action':'qcld_woo_chatbot_sub_category','parent_id':parentId};
            var result=woowKits.ajax(data);
            result.done(function( response ) {
                woowMsg.double_nobg(subCatMsg,response);
            });
        },
        suggestEmail:function (emailFor) {
            var sugMsg=woowKits.randomMsg(globalWoow.settings.obj.support_option_again);
            var sugOptions= emailFor+globalWoow.wildcards;
            woowMsg.double_nobg(sugMsg,sugOptions);

        }
        ,
        videohandler:function () {
            $(globalWoow.settings.messageLastChild+' .woo-chatbot-paragraph').html(function(i, html) {
                return html.replace(/(?:https:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g, '<iframe width="250" height="180" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>');
            });
        },
        scrollTo:function () {
            $(globalWoow.settings.botContainer).animate({ scrollTop: $(globalWoow.settings.messageWrapper).prop("scrollHeight")}, 'slow').parent().find('.slimScrollBar').css({'top':$(globalWoow.settings.botContainer).height()+'px'});;
        },
        botPreloader:function () {
            var msgContent='<li class="woo-chatbot-msg">' +
                '<div class="woo-chatbot-avatar">'+
                '<img src="'+globalWoow.settings.obj.agent_image_path+'" alt="">'+
                '</div>'+
                '<div class="woo-chatbot-agent">'+ globalWoow.settings.obj.agent+'</div>'
                +'<div class="woo-chatbot-paragraph"><img class="woo-chatbot-comment-loader" src="'+globalWoow.settings.obj.image_path+'comment.gif" alt="Typing..." /></div></li>';
            return msgContent;
        },
        shopperMsgDom:function (msg) {
            if(globalWoow.hasNameCookie){
                var shopper=globalWoow.hasNameCookie;
            } else{
                var shopper=globalWoow.settings.obj.shopper_demo_name;
            }
            var msgContent='<li class="woo-chat-user-msg">' +
                '<div class="woo-chatbot-avatar">'+
                '<img src="'+globalWoow.settings.obj.image_path+'client.png" alt="">'+
                '</div>'+
                '<div class="woo-chatbot-agent">'+shopper +'</div>'
                +'<div class="woo-chatbot-paragraph">'+msg+'</div></li>';
            return msgContent;
        },
        addToCart:function () { //added by Raju, QC
            var data = {'action':'qcld_woo_chatbot_show_cart'}
            this.ajax(data).done(function (response) {
                //if cart show on message board
                if($('#woo-chatbot-shortcode-template-container').length == 0) {
                    //$(globalWoow.settings.messageWrapper).html(response.html);
                    $('#woo-chatbot-cart-numbers').html(response.items);
                    $('.woo-chatbot-ball-cart-items').html(response.items);
                    //woowKits.disableEditor(woowKits.randomMsg(globalWoow.settings.obj.shopping_cart));
					
                }else{  //Cart show on shortcode
                    $('.woo-chatbot-cart-shortcode-container').html(response.html);

                }
                //Add scroll to the cart shortcode
                if($('#woo-chatbot-shortcode-template-container').length > 0  && $('.chatbot-shortcode-template-02').length==0) {
                    $('.woo-chatbot-cart-body').slimScroll({height: '200px', start: 'bottom'});
                }
            });
        },
        showCart:function () {
            var data = {'action':'qcld_woo_chatbot_show_cart'}
            this.ajax(data).done(function (response) {
                //if cart show on message board
                if($('#woo-chatbot-shortcode-template-container').length == 0) {
                    $(globalWoow.settings.messageWrapper).html(response.html);
                    $('#woo-chatbot-cart-numbers').html(response.items);
                    $('.woo-chatbot-ball-cart-items').html(response.items);
                    woowKits.disableEditor(woowKits.randomMsg(globalWoow.settings.obj.shopping_cart));
                }else{  //Cart show on shortcode
                    $('.woo-chatbot-cart-shortcode-container').html(response.html);

                }
                //Add scroll to the cart shortcode
                if($('#woo-chatbot-shortcode-template-container').length > 0  && $('.chatbot-shortcode-template-02').length==0) {
                    $('.woo-chatbot-cart-body').slimScroll({height: '200px', start: 'bottom'});
                }
            });
        },
        toTitlecase:function (msg) {
            return msg.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        },
        filterStopWords:function(msg){
            var spcialStopWords=",;,/,\\,[,],{,},(,),&,*,.,+ ,?,^,$,=,!,<,>,|,:,-";
            var userMsg="";
            //Removing Special Characts from last position.
            var msgLastChar=msg.slice(-1);
            if(spcialStopWords.indexOf(msgLastChar) >= 0 ){
                userMsg=msg.slice(0, -1);
            }else{
                userMsg=msg;
            }
            var stopWords=globalWoow.settings.obj.stop_words+spcialStopWords;
            var stopWordsArr=stopWords.split(',');
            var msgArr=userMsg.split(' ');
            var filtermsgArr = msgArr.filter(function myCallBack(el){
                return stopWordsArr.indexOf(el.toLowerCase()) < 0;
            });
            filterMsg=filtermsgArr.join(' ');
            return filterMsg;
        },
        htmlTagsScape:function(userString) {
            var tagsToReplace = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;'
            };
            return userString.replace(/[&<>]/g, function(tag) {
                return tagsToReplace[tag] || tag;
            });
        },
        cardResponse:function (title, subtitle, buttons, text, postback) {
            var card = '<div class="woo-chatbot-card-title">' + title + '</div>';
            card += '<div class="woo-chatbot-card-subtitle">' + subtitle + '</div>';
            var index = 0;
            for (index; index<buttons.length; index++) {
                card += '<span type="button" class="woo-chatbot-card-button" card-target="' + buttons[index].postback + '">' + buttons[index].text +  '</span>';
            }
            return card;
        },
        quickRepliesResponse:function (title, replies) {
            var quickRes = '<div class="woo-chatbot-quick-replies-title">' + title + '</div>';

            var index = 0;
            for (index; index<replies.length; index++) {
                quickRes += '<input type="button" class="woo-chatbot-quick-reply"  value="' + replies[index] +  '"/>';
            }
            return quickRes;
        },
        imageResponse:function(imageUrl) {
            if (imageUrl!= "")  {
                var ImgRes = '<img src="' + imageUrl + '"/>';
                return ImgRes;
            }
        },
        woowOpenWindow:function (url, title, w, h) {
            // Fixes dual-screen position                         Most browsers      Firefox
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

            var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = ((width / 2) - (w / 2)) + dualScreenLeft;
            var top = ((height / 2) - (h / 2)) + dualScreenTop;
            var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            // Puts focus on the newWindow
            if (window.focus) {
                newWindow.focus();
            }
        }
    }
    /*
     * Woowbot Trees are basically product,order and support
     * product tree : asking,showing & shopping part will be covered.
     * order tree : showing order list and email to admin option.
     * support tree : List of support query-answer including text & video and email to admin option.
     */
    var woowTree={

        greeting:function (msg) {
            /**
             * When Enable DialogFlow then  or else
             */
            if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                //When intialize 1 and don't have cookies then keep  the name of shooper in in cookie
                if(globalWoow.initialize==1 && !localStorage.getItem('shopper')  && globalWoow.wildCard==0 && globalWoow.ai_step==0 ){
					
					var main_text = msg;
                    msg=woowKits.toTitlecase(msg);
					console.log(msg);
					var dfReturns=woowKits.dailogAIOAction(msg);
					
                    dfReturns.done(function( response ) {
                        console.log(response);
                        if(response.status.code==200){
							var intent = response.result.metadata.intentName;
							
							if(intent=="get name"){
								
								given_name = response.result.parameters.given_name;
								last_name = response.result.parameters.last_name;
								fullname = given_name+' '+last_name;
								if(fullname.length<2){
									fullname = msg
								}
								$.cookie("shopper", fullname, { expires : 365 });
								localStorage.setItem('shopper',fullname);
								globalWoow.hasNameCookie=fullname;
								//Greeting with name and suggesting the wildcard.
								var NameGreeting=woowKits.randomMsg(globalWoow.settings.obj.i_am) +" <strong>"+globalWoow.settings.obj.agent+"</strong>! "+woowKits.randomMsg(globalWoow.settings.obj.name_greeting)+", <strong>"+fullname+"</strong>!";
								var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
								//After completing two steps messaging showing wildcards.
								woowMsg.double(NameGreeting,serviceOffer);
								globalWoow.ai_step=1;
								globalWoow.wildCard=0;
								localStorage.setItem("wildCard",  globalWoow.wildCard);
								localStorage.setItem("aiStep", globalWoow.ai_step);
								
							}else if(intent=='Default Fallback Intent'){
								$.cookie("shopper", globalWoow.settings.obj.shopper_demo_name, { expires : 365 });
								localStorage.setItem('shopper',globalWoow.settings.obj.shopper_demo_name);
								globalWoow.hasNameCookie=globalWoow.settings.obj.shopper_demo_name;
								globalWoow.ai_step=1;
								globalWoow.wildCard=0;
								localStorage.setItem("wildCard",  globalWoow.wildCard);
								localStorage.setItem("aiStep", globalWoow.ai_step);
								woowMsg.single(globalWoow.settings.obj.shopper_call_you+' '+globalWoow.settings.obj.shopper_demo_name);
								setTimeout(function(){
									var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
									woowMsg.single(serviceOffer);
								},globalWoow.settings.preLoadingTime)
								
							}else{
								
								$.cookie("shopper", globalWoow.settings.obj.shopper_demo_name, { expires : 365 });
								localStorage.setItem('shopper',globalWoow.settings.obj.shopper_demo_name);
								globalWoow.hasNameCookie=globalWoow.settings.obj.shopper_demo_name;
								globalWoow.ai_step=1;
								globalWoow.wildCard=0;
								localStorage.setItem("wildCard",  globalWoow.wildCard);
								localStorage.setItem("aiStep", globalWoow.ai_step);
								woowAction.bot(main_text);
							}
						}
					})
					
                }
                //When returning shopper then greeting with name and wildcards.
                else if(localStorage.getItem('shopper')  && globalWoow.wildCard==0 && globalWoow.ai_step==0){
                    //After asking service show the wildcards.
                    var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
                    globalWoow.ai_step=1;
                    globalWoow.wildCard=0;
                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                    localStorage.setItem("aiStep", globalWoow.ai_step);
                    woowMsg.single(serviceOffer);
                }
                //When user asking needs then DialogFlow will given intent after NLP steps.
                else if(globalWoow.wildCard==0 && globalWoow.ai_step==1){
                    var dfReturns=woowKits.dailogAIOAction(msg);
                    dfReturns.done(function( response ) {
                        //console.log('Ai result',JSON.stringify(response));
                        if(response.status.code==200){
                            //For custom intent and step by step search
                            var userIntent=response.result.metadata.intentName;
                            var intentNamesIndex=globalWoow.settings.obj.custom_intent_names.indexOf(userIntent);
                            var intentSysKey=globalWoow.settings.obj.custom_intent_kewords[intentNamesIndex];
                            if(intentSysKey==msg && $.inArray(userIntent, globalWoow.settings.obj.custom_intent_names) !== -1 && globalWoow.settings.obj.custom_search_enable==1){
                                globalWoow.sbs_search_flag=1
                            }
                            if( $.inArray(userIntent, globalWoow.settings.obj.custom_intent_names) !== -1 && globalWoow.settings.obj.custom_search_enable==1 && globalWoow.sbs_search_flag==1){

                                if(response.result.actionIncomplete==true){
                                    var sMgs=response.result.fulfillment.speech;
                                    woowMsg.single(sMgs);
                                }else{
                                    var parameters=response.result.parameters;
                                    var sMgs=response.result.fulfillment.speech;
                                    var data = { 'action':'qcld_woo_chatbot_step_by_step_search','params':parameters};
                                    woowKits.ajax(data).done(function( response ) {
                                        globalWoow.sbs_search_flag=0;
                                        //woowMsg.single(sMgs);
                                        if(response.product_num >0){
                                            woowMsg.double(sMgs,response.html);
                                        }else{
                                            woowMsg.single(woowKits.randomMsg(globalWoow.settings.obj.product_fail));
                                        }

                                    });
                                }
                            }else if(userIntent=='start'){
                                globalWoow.wildCard=0;
                                var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
                                woowMsg.double_nobg(serviceOffer,globalWoow.wildcards);
                            }else if(userIntent=='help'){
                                $(globalWoow.settings.messageWrapper).html(localStorage.getItem("woowHitory"));
                                //Showing help message
                                setTimeout(function () {
                                    woowKits.scrollTo();
                                    var helpWelcome = woowKits.randomMsg(globalWoow.settings.obj.help_welcome);
                                    var helpMsg = woowKits.randomMsg(globalWoow.settings.obj.help_msg);
                                    woowMsg.double(helpWelcome,helpMsg);
                                    //dialogflow
                                    if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                                        globalWoow.wildCard=0;
                                        globalWoow.ai_step=1;
                                        localStorage.setItem("wildCard",  globalWoow.wildCard);
                                        localStorage.setItem("aiStep", globalWoow.ai_step);
                                    }
                                },globalWoow.settings.preLoadingTime);
                            }else if(userIntent=='reset'){
                                var restWarning=globalWoow.settings.obj.reset;
                                var confirmBtn='<span class="qcld-chatbot-reset-btn" reset-data="yes" >'+globalWoow.settings.obj.yes+'</span> <span> '+globalWoow.settings.obj.or+' </span><span class="qcld-chatbot-reset-btn"  reset-data="no">'+globalWoow.settings.obj.no+'</span>';
                                woowMsg.double_nobg(restWarning,confirmBtn);
                            }else if(userIntent=='product' && globalWoow.settings.obj.disable_product_search!=1){
                                var searchQuery=woowKits.filterStopWords(response.result.resolvedQuery);
                                globalWoow.wildCard=1;
                                globalWoow.productStep='search'
                                woowAction.bot(searchQuery);
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("productStep", globalWoow.productStep);
                            }else if(userIntent=='catalog' && globalWoow.settings.obj.disable_catalog!=1){
                                woowAction.bot(globalWoow.settings.obj.sys_key_catalog.toLowerCase());
                            }else if(userIntent=='featured' && globalWoow.settings.obj.disable_featured_product!=1){
                                globalWoow.wildCard=1;
                                globalWoow.productStep='featured'
                                woowAction.bot('from wildcard product');
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("productStep", globalWoow.productStep);
                            }else  if(userIntent=='sale' && globalWoow.settings.obj.disable_sale_product!=1){
                                globalWoow.wildCard=1;
                                globalWoow.productStep='sale'
                                woowAction.bot('from wildcard product');
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("productStep", globalWoow.productStep);
                            }else if(userIntent=='order' && globalWoow.settings.obj.disable_order_status!=1){
                                globalWoow.wildCard=2;
                                globalWoow.orderStep='welcome';
                                woowAction.bot('from wildcard order');
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("orderStep", globalWoow.orderStep);
                            }else if(userIntent=='support'){
                                globalWoow.wildCard=3;
                                globalWoow.supportStep='welcome';
                                woowAction.bot('from wildcard support');
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("supportStep", globalWoow.supportStep);

                            }else if(userIntent=='get name'){
								
								given_name = response.result.parameters.given_name;
								last_name = response.result.parameters.last_name;
								fullname = given_name+' '+last_name;
								
								$.cookie("shopper", fullname, { expires : 365 });
								localStorage.setItem('shopper',fullname);
								globalWoow.hasNameCookie=fullname;
								//Greeting with name and suggesting the wildcard.
								var NameGreeting=woowKits.randomMsg(globalWoow.settings.obj.i_am) +" <strong>"+globalWoow.settings.obj.agent+"</strong>! "+woowKits.randomMsg(globalWoow.settings.obj.name_greeting)+", <strong>"+fullname+"</strong>!";
								var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
								//After completing two steps messaging showing wildcards.
								woowMsg.double(NameGreeting,serviceOffer);
								globalWoow.ai_step=1;
								globalWoow.wildCard=0;
								localStorage.setItem("wildCard",  globalWoow.wildCard);
								localStorage.setItem("aiStep", globalWoow.ai_step);
								
							}
                            else if(response.result.score!=0){ // checking is responding from dialogflow.
                                if(response.result.action==""){
                                    if(response.result.fulfillment.speech!="" && globalWoow.settings.obj.custom_intent_enable==1 && $.inArray(userIntent, globalWoow.settings.obj.custom_intent_names)==-1 ){
                                        //DialogFlow all defualt message will be printed.
                                        var DFMsg=response.result.fulfillment.speech;
                                        woowMsg.single(DFMsg);
                                    }else if(response.result.fulfillment.speech=="" && response.result.fulfillment.hasOwnProperty('messages') && globalWoow.settings.obj.rich_response_enable==1 && globalWoow.settings.obj.custom_intent_enable==1 ){
                                        //DialogFlow all defualt message will be printed.
                                        var DFMsg="";
                                        var messages = response.result.fulfillment.messages;
                                        var numMessages = messages.length;
                                        var index = 0;
                                        for (index; index<numMessages; index++) {
                                            var message = messages[index];
                                            switch (message.type) {
                                                case 0: // For text response
                                                    DFMsg+=message.speech;
                                                    break;
                                                case 1: // For card part
                                                    DFMsg+=woowKits.cardResponse(message.title, message.subtitle, message.buttons, message.text, message.postback);
                                                    break;
                                                case 2: // For quick replies
                                                    DFMsg+=woowKits.quickRepliesResponse(message.title, message.replies);
                                                    break;
                                                case 3: // For image response
                                                    DFMsg+=woowKits.imageResponse(message.imageUrl);
                                                    break;
                                                case 3: // custom payload

                                                    break;
                                                default:
                                            }
                                        }

                                        woowMsg.single(DFMsg);
                                    }else if(globalWoow.settings.obj.disable_product_search!=1){
                                        //Default is considered as product searching in the system if its not smalltalk && no respone message from DF
                                        var searchQuery=woowKits.filterStopWords(response.result.resolvedQuery);
                                        globalWoow.wildCard=1;
                                        globalWoow.productStep='search'
                                        woowAction.bot(searchQuery);
                                        //keeping value in localstorage
                                        localStorage.setItem("wildCard",  globalWoow.wildCard);
                                        localStorage.setItem("productStep", globalWoow.productStep);
                                    }else{
                                        var dfDefaultMsg=globalWoow.settings.obj.df_defualt_reply;
                                        woowMsg.single(dfDefaultMsg);
                                    }
                                } else if(response.result.action!=""){
                                    //Working for smalltalk
                                    var sTalkAction=response.result.action;
                                    var sTalkActionArr=sTalkAction.split('.');
                                    if(sTalkActionArr[0]=='smalltalk'){
                                        var sMgs=response.result.fulfillment.speech;
                                        woowMsg.single(sMgs);
                                    }else{
                                        var searchQuery=woowKits.filterStopWords(response.result.resolvedQuery);
                                        globalWoow.wildCard=1;
                                        globalWoow.productStep='search'
                                        woowAction.bot(searchQuery);
                                        //keeping value in localstorage
                                        localStorage.setItem("wildCard",  globalWoow.wildCard);
                                        localStorage.setItem("productStep", globalWoow.productStep);
                                    }
                                }

                            }else{
                                var searchQuery=woowKits.filterStopWords(response.result.resolvedQuery);
                                globalWoow.wildCard=1;
                                globalWoow.productStep='search'
                                woowAction.bot(searchQuery);
                                //keeping value in localstorage
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("productStep", globalWoow.productStep);
                            }
                        }else{
                            //if bad request or limit cross then
                            globalWoow.df_status_lock=1;
                            var dfDefaultMsg=globalWoow.settings.obj.df_defualt_reply;
                            woowMsg.double_nobg(dfDefaultMsg,globalWoow.wildcards);
                        }
                    }).fail(function (error) {
                        var dfDefaultMsg=globalWoow.settings.obj.df_defualt_reply;
                        woowMsg.double_nobg(dfDefaultMsg,globalWoow.wildcards);
                    });
                }
            }else{
                //When intialize 1 and don't have cookies then keep  the name of shooper in in cookie
                if(globalWoow.initialize==1 && !localStorage.getItem('shopper')  && globalWoow.wildCard==0){
                    msg=woowKits.toTitlecase(msg);
                    $.cookie("shopper", msg, { expires : 365 });
                    localStorage.setItem('shopper',msg);
                    globalWoow.hasNameCookie=msg;
                    //Greeting with name and suggesting the wildcard.
                    var NameGreeting=woowKits.randomMsg(globalWoow.settings.obj.i_am) +" <strong>"+globalWoow.settings.obj.agent+"</strong>! "+woowKits.randomMsg(globalWoow.settings.obj.name_greeting)+", <strong>"+msg+"</strong>!";
                    var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
                    //After completing two steps messaging showing wildcards.
                    woowMsg.double(NameGreeting,serviceOffer);
                    setTimeout(function(){
                        if(globalWoow.wildcards!=""){
                            woowMsg.single_nobg(globalWoow.wildcards);
                        }
                        globalWoow.wildCard=1;
                        globalWoow.productStep='search';
                        localStorage.setItem("wildCard",  globalWoow.wildCard);
                        localStorage.setItem("productStep",  globalWoow.productStep);
                    },parseInt(globalWoow.settings.preLoadingTime*2.1));
                }
                //When returning shopper then greeting with name and wildcards.
                else if(localStorage.getItem('shopper')  && globalWoow.wildCard==0){
                    //After asking service show the wildcards.
                    var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
                    woowMsg.double_nobg(serviceOffer,globalWoow.wildcards);
                    globalWoow.wildCard=1;
                    globalWoow.productStep='search';
                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                    localStorage.setItem("productStep",  globalWoow.productStep);
                }
            }
        },

        product:function (msg) {
            if(globalWoow.wildCard==1 && globalWoow.productStep=='asking'){
                var askingProduct=woowKits.randomMsg(globalWoow.settings.obj.product_asking);
                woowMsg.single(askingProduct);
                globalWoow.productStep='search';
            } else if(globalWoow.wildCard==1 && globalWoow.productStep=='search'){
                var data = {'action':'qcld_woo_chatbot_keyword', 'keyword':msg};
                //Products by string search ajax handler.
                woowKits.ajax(data).done(function( response ) {
                    if(response.product_num==0){
                        var productFail=woowKits.randomMsg(globalWoow.settings.obj.product_fail)+" <strong>"+msg+"</strong>!";
                        //var productSuggest=woowKits.randomMsg(globalWoow.settings.obj.product_suggest);
                        woowMsg.single(productFail);

                        //Suggesting category.
                        setTimeout(function(){
                            woowKits.sugestCat();
                        },parseInt(globalWoow.settings.preLoadingTime*2.1));

                    }else {
                        var productSucces= woowKits.randomMsg(globalWoow.settings.obj.product_success)+" <strong>"+msg+"</strong>!";
                        woowMsg.double_nobg(productSucces,response.html);
                        if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                            globalWoow.wildCard=0;
                            globalWoow.ai_step=1;
                            localStorage.setItem("wildCard",  globalWoow.wildCard);
                            localStorage.setItem("aiStep", globalWoow.ai_step);
                        }else{
                            //Infinite asking to break dead end.
                            if(response.per_page >= response.product_num){
                                setTimeout(function () {
                                    var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                                    woowMsg.single(searchAgain);
                                    //keeping value in localstorage
                                    globalWoow.productStep='search';
                                    localStorage.setItem("productStep",  globalWoow.productStep);
                                },globalWoow.settings.wildcardsShowTime);
                            }
                        }
                    }
                });

            }else if(globalWoow.wildCard==1 && globalWoow.productStep=='category'){
                var msg=msg.split("#");
                var categoryTitle=msg[0];
                var categoryId=msg[1];
                var data = { 'action':'qcld_woo_chatbot_category_products','category':categoryId};
                //Product by category ajax handler.
                woowKits.ajax(data).done(function (response) {
                    if(response.product_num==0){
                        //Since product does not found then show message and suggesting infinity search
                        var productFail = woowKits.randomMsg(globalWoow.settings.obj.product_fail)+" <strong>"+categoryTitle+"</strong>!";
                        var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                        woowMsg.double(productFail,searchAgain);
                        globalWoow.productStep='search';
                        //keeping value in localstorage
                        localStorage.setItem("productStep",  globalWoow.productStep);

                    } else{
                        //Now show chat message to choose the product.
                        var productSuccess = woowKits.randomMsg(globalWoow.settings.obj.product_success)+" <strong>"+categoryTitle+"</strong>!";
                        var products=response.html;
                        woowMsg.double_nobg(productSuccess,products);
                        //Infinite asking to break dead end.
                        if(response.per_page >= response.product_num){
                            setTimeout(function () {
                                var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                                woowMsg.single(searchAgain);
                                globalWoow.productStep='search';
                                //keeping value in localstorage
                                localStorage.setItem("productStep",  globalWoow.productStep);
                            },globalWoow.settings.wildcardsShowTime);
                        }
                    }
                })
            }else if(globalWoow.wildCard==1 && globalWoow.productStep=='featured'){
                var data = {'action':'qcld_woo_chatbot_featured_products'};
                //Products by string search ajax handler.
                woowKits.ajax(data).done(function( response ) {
                    if(response.product_num==0){
                        var productFail=woowKits.randomMsg(globalWoow.settings.obj.product_fail)+" <strong>Featured Products</strong>!";
                        //var productSuggest=woowKits.randomMsg(globalWoow.settings.obj.product_suggest);
                        woowMsg.single(productFail);

                        //Suggesting category.
                        setTimeout(function(){
                            woowKits.sugestCat();
                        },parseInt(globalWoow.settings.preLoadingTime*2.1));

                    }else {
                        var productSucces= woowKits.randomMsg(globalWoow.settings.obj.product_success)+" <strong>Featured Products</strong>!";
                        woowMsg.double_nobg(productSucces,response.html);
                        //Infinite asking to break dead end.
                        if(response.per_page >= response.product_num){
                            setTimeout(function () {
                                var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                                woowMsg.single(searchAgain);
                                //For Dialogflow or else
                                if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                                    globalWoow.wildCard=0;
                                    globalWoow.ai_step=1;
                                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                                    localStorage.setItem("aiStep", globalWoow.ai_step);
                                }else{
                                    //keeping value in localstorage
                                    globalWoow.productStep='search';
                                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                                    localStorage.setItem("productStep",  globalWoow.productStep);
                                }
                            },globalWoow.settings.wildcardsShowTime);
                        }else{
                            //For Dialogflow or else
                            if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                                globalWoow.wildCard=0;
                                globalWoow.ai_step=1;
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("aiStep", globalWoow.ai_step);
                            }
                        }

                    }
                });

            }else if(globalWoow.wildCard==1 && globalWoow.productStep=='sale'){
                var data = {'action':'qcld_woo_chatbot_sale_products'};
                //Products by string search ajax handler.
                woowKits.ajax(data).done(function( response ) {
                    if(response.product_num==0){
                        var productFail=woowKits.randomMsg(globalWoow.settings.obj.product_fail)+'<strong>'+woowKits.randomMsg(globalWoow.settings.obj.sale_products)+'</strong>!';
                        //var productSuggest=woowKits.randomMsg(globalWoow.settings.obj.product_suggest);
                        woowMsg.single(productFail);

                        //Suggesting category.
                        setTimeout(function(){
                            woowKits.sugestCat();
                        },parseInt(globalWoow.settings.preLoadingTime*2.1));

                    }else {
                        var productSucces= woowKits.randomMsg(globalWoow.settings.obj.product_success)+' <strong>'+woowKits.randomMsg(globalWoow.settings.obj.sale_products)+'</strong>!';
                        woowMsg.double_nobg(productSucces,response.html);
                        //Infinite asking to break dead end.
                        if(response.per_page >= response.product_num){
                            setTimeout(function () {
                                var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                                woowMsg.single(searchAgain);
                                //For Dialogflow or else
                                if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                                    globalWoow.wildCard=0;
                                    globalWoow.ai_step=1;
                                    localStorage.setItem("wildCard",  globalWoow.wildCard);
                                    localStorage.setItem("aiStep", globalWoow.ai_step);
                                }else{
                                    //keeping value in localstorage
                                    globalWoow.productStep='search';
                                    localStorage.setItem("productStep",  globalWoow.productStep);
                                }
                            },globalWoow.settings.wildcardsShowTime);
                        }else{
                            //For Dialogflow or else
                            if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                                globalWoow.wildCard=0;
                                globalWoow.ai_step=1;
                                localStorage.setItem("wildCard",  globalWoow.wildCard);
                                localStorage.setItem("aiStep", globalWoow.ai_step);
                            }
                        }

                    }
                });
            }
        },

        order:function (msg) {
            //If user already logged In then
            if(globalWoow.settings.obj.order_login==1){
                var orderWelcome=globalWoow.settings.obj.order_welcome;
                var data = {'action': 'qcld_woo_chatbot_loged_in_user_orders'};
                //Orders for logged in user ajax handler.
                woowKits.ajax(data).done(function (response) {
                    if(response.order_num>0){
                        var orderSucMsg=response.message;
                        var orderSucHtml=response.html;
                        woowMsg.double_nobg(orderSucMsg,orderSucHtml);
                        //Calling the email to admin part
                        setTimeout(function(){
                            var orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.order_email_support)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                        },globalWoow.settings.wildcardsShowTime);
                    }else{
                        var orderFailMsg=response.message;
                        var orderFailHtml=response.html;
                        woowMsg.double(orderFailMsg,orderFailHtml);
                        //Calling the email to admin part
                        setTimeout(function(){
                            var orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.order_email_support)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                        },globalWoow.settings.wildcardsShowTime);
                    }
                });
            }
            //If user is not logged In then
            else{
                if( globalWoow.wildCard==2 && globalWoow.orderStep=='welcome'){
                    var orderWelcome=globalWoow.settings.obj.order_welcome;
                    var userNameAsking=globalWoow.settings.obj.order_username_asking;
                    woowMsg.double(orderWelcome,userNameAsking);
                    //updating the order steps
                    globalWoow.orderStep='user';
                    //keeping value in localstorage
                    localStorage.setItem("orderStep",  globalWoow.orderStep);

                } else if( globalWoow.wildCard==2 && globalWoow.orderStep=='user'){
                    globalWoow.shopperUserName=msg;
                    var data = {'action': 'qcld_woo_chatbot_check_user', 'user_name': globalWoow.shopperUserName };
                    //Username checking ajax handler.
                    woowKits.ajax(data).done(function (response) {
                        if(response.status=='success'){
                            var successMgs=response.message;
                            var sucessHtml=response.html;
                            woowMsg.double(successMgs,sucessHtml);
                            globalWoow.orderStep='password';
                            //keeping value in localstorage
                            localStorage.setItem("orderStep",  globalWoow.orderStep);

                        } else{
                            var failMsg=response.message;
                            woowMsg.single(failMsg);
                            globalWoow.orderStep='user';
                            //keeping value in localstorage
                            localStorage.setItem("orderStep",  globalWoow.orderStep);
                        }
                    });
                }else if( globalWoow.wildCard==2 && globalWoow.orderStep=='password'){
                    var data = {'action': 'qcld_woo_chatbot_login_user','user_name': globalWoow.shopperUserName,'user_pass': msg,'security': globalWoow.settings.obj.order_nonce};
                    //user loginajax handler.
                    woowKits.ajax(data).done(function (response) {
                        if(response.status=='success') {
                            if (response.order_num > 0) {
                                var loginSucMsg=response.message;
                                var orderHtml=response.html;
                                woowMsg.double_nobg(loginSucMsg,orderHtml);
                                //Now keep the user as login in by updating obj
                                globalWoow.settings.obj.order_login=1;
                                //Calling the email to admin part
                                setTimeout(function(){
                                    var orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                                    var orEmailSuggest='<span class="qcld-chatbot-suggest-email">'+woowKits.randomMsg(globalWoow.settings.obj.order_email_support)+'</span>';
                                    woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                                },globalWoow.settings.wildcardsShowTime);

                            } else {
                                var loginFailcMsg=response.message;
                                var orderNoHtml=response.html;
                                woowMsg.double(loginFailcMsg,orderNoHtml);
                                //Now keep the user as login in by updating obj
                                globalWoow.settings.obj.order_login=1;
                                //Calling the email to admin part
                                setTimeout(function(){
                                    var orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                                    var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.order_email_support)+'</span>';
                                    woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                                },globalWoow.settings.wildcardsShowTime);
                            }
                        }else{
                            var loginFail= response.message;
                            woowMsg.single(loginFail);
                            globalWoow.orderStep=='password';
                            //keeping value in localstorage
                            localStorage.setItem("orderStep",  globalWoow.orderStep);
                        }
                    });
                }
            }
        },

        support:function (msg) {
            if(globalWoow.wildCard==3 && globalWoow.supportStep=='welcome'){
                var welcomeMsg= woowKits.randomMsg(globalWoow.settings.obj.support_welcome);
                var orPhoneSuggest = '';
                if(globalWoow.settings.obj.support_query.length>0){
                    var supportsItems = '';
                    var messenger = '';
                    if(globalWoow.settings.obj.enable_messenger==1) {
                        messenger += '<span class="qcld-chatbot-wildcard"  data-wildcart="messenger">'+woowKits.randomMsg(globalWoow.settings.obj.messenger_label)+'</span>';
                    }
                    if(globalWoow.settings.obj.enable_whats==1) {
                        messenger += '<span class="qcld-chatbot-wildcard"  data-wildcart="whatsapp">'+woowKits.randomMsg(globalWoow.settings.obj.whats_label)+'</span>';
                    }
                    if(globalWoow.settings.obj.disable_feedback=='') {
                        messenger+= '<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.feedback_label)+'</span>';
                    }

                    $.each(globalWoow.settings.obj.support_query, function (i, obj) {
                        supportsItems += '<span class="qcld-chatbot-support-items"  data-query-index="' + i + '">' + obj + '</span>';
                    });
                    var orEmailSuggest = '<span class="qcld-chatbot-suggest-email" >' + woowKits.randomMsg(globalWoow.settings.obj.support_email) + '</span>';
                    if(globalWoow.settings.obj.call_sup=="") {
                        orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                    }
                    var queryOrEmail=supportsItems+orEmailSuggest+orPhoneSuggest+messenger;
                }else {
                    if(globalWoow.settings.obj.call_sup=="") {
                        orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                    }
                    var queryOrEmail='<span class="qcld-chatbot-suggest-email" >' + woowKits.randomMsg(globalWoow.settings.obj.support_email) + '</span>'+orPhoneSuggest;

                }

                woowMsg.double_nobg(welcomeMsg,queryOrEmail);
            } else if(globalWoow.wildCard==3 && globalWoow.supportStep=='email'){

                globalWoow.shopperEmail=msg;
                var validate = "";
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                if( re.test(globalWoow.shopperEmail)!=true){
                    validate = validate+woowKits.randomMsg(globalWoow.settings.obj.invalid_email) ;
                }
                if(validate == ""){
                    var askingMsg=woowKits.randomMsg(globalWoow.settings.obj.asking_msg);
                    woowMsg.single(askingMsg);
                    globalWoow.supportStep='message';
                    //keeping value in localstorage
                    localStorage.setItem("supportStep",  globalWoow.supportStep);

                }else{
                    woowMsg.single(validate);
                    globalWoow.supportStep='email';
                    //keeping value in localstorage
                    localStorage.setItem("supportStep",  globalWoow.supportStep);
                }
            }else if(globalWoow.wildCard==3 && globalWoow.supportStep=='message'){

                var data = {'action':'qcld_woo_chatbot_support_email','name':localStorage.getItem('shopper'),'email':globalWoow.shopperEmail,'message':msg};
                woowKits.ajax(data).done(function (response) {
                    var json=$.parseJSON(response);
                    var orPhoneSuggest='';
                    if(json.status=='success'){
                        var sucMsg=json.message;
                        woowMsg.single(sucMsg);
                        //Asking email after showing answer.
                        setTimeout(function(){
                            if(globalWoow.settings.obj.call_sup=="") {
                                orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            }
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.support_email)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                            globalWoow.wildCard=0;
                        },globalWoow.settings.preLoadingTime);
                    }else{
                        var failMsg=json.message;
                        woowMsg.single(failMsg);
                        //Asking email after showing answer.
                        setTimeout(function(){
                            if(globalWoow.settings.obj.call_sup=="") {
                                orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            }
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.support_email)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                            globalWoow.wildCard=0;
                        },globalWoow.settings.preLoadingTime);
                    }
                });

            }else if(globalWoow.wildCard==3 && globalWoow.supportStep=='phone'){
                var data = {'action':'qcld_woo_chatbot_support_phone','name':localStorage.getItem('shopper'),'phone':msg};
                woowKits.ajax(data).done(function (response) {
                    var json=$.parseJSON(response);
                    var orPhoneSuggest='';
                    if(json.status=='success'){
                        var sucMsg=json.message;
                        woowMsg.single(sucMsg);
                        //Asking email after showing answer.
                        setTimeout(function(){
                            if(globalWoow.settings.obj.call_sup=="") {
                                orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            }
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.support_email)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                            globalWoow.wildCard=0;
                        },globalWoow.settings.preLoadingTime);
                    }else{
                        var failMsg=json.message;
                        woowMsg.single(failMsg);
                        //Asking email after showing answer.
                        setTimeout(function(){
                            if(globalWoow.settings.obj.call_sup=="") {
                                orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                            }
                            var orEmailSuggest='<span class="qcld-chatbot-suggest-email" >'+woowKits.randomMsg(globalWoow.settings.obj.support_email)+'</span>';
                            woowKits.suggestEmail(orEmailSuggest+orPhoneSuggest);
                            globalWoow.wildCard=0;
                        },globalWoow.settings.preLoadingTime);
                    }
                });

            }

        }
    };
    /*
     * Woowbot Actions are divided into two part
     * shopper will response after initialize message,
     * then based on shopper activities shopper will act.
     */
    var woowAction={
        bot:function(msg){
            //Disable the Editor
            woowKits.disableEditor(globalWoow.settings.obj.agent+' '+woowKits.randomMsg(globalWoow.settings.obj.is_typing));
            msg=msg.toLowerCase();
            if(globalWoow.wildcardsHelp.indexOf(msg)>-1){
                if(msg==globalWoow.settings.obj.sys_key_help.toLowerCase()){
                    globalWoow.wildCard=0;
                    var serviceOffer=woowKits.randomMsg(globalWoow.settings.obj.wildcard_msg);
                    woowMsg.double_nobg(serviceOffer,globalWoow.wildcards);
                }
                if(msg==globalWoow.settings.obj.sys_key_product.toLowerCase()){
                    globalWoow.wildCard=1;
                    globalWoow.productStep='asking';
                    woowTree.product(msg);
                }
                if(msg==globalWoow.settings.obj.sys_key_catalog.toLowerCase()){
                    globalWoow.wildCard=1;
                    globalWoow.productStep='search';
                    woowKits.sugestCat();
                }

                if(msg==globalWoow.settings.obj.sys_key_order.toLowerCase()){
                    globalWoow.wildCard=2;
                    globalWoow.orderStep='welcome';
                    woowTree.order(msg);
                }
                if(msg==globalWoow.settings.obj.sys_key_support.toLowerCase()){
                    globalWoow.wildCard=3;
                    globalWoow.supportStep='welcome';
                    woowTree.support(msg);
                }
                if(msg==globalWoow.settings.obj.sys_key_reset.toLowerCase()){
                    var restWarning=globalWoow.settings.obj.reset;
                    var confirmBtn='<span class="qcld-chatbot-reset-btn" reset-data="yes" >'+globalWoow.settings.obj.yes+'</span> <span> '+globalWoow.settings.obj.or+' </span><span class="qcld-chatbot-reset-btn"  reset-data="no">'+globalWoow.settings.obj.no+'</span>';
                    woowMsg.double_nobg(restWarning,confirmBtn);
                }

            }else{
                /*
                 *   Greeting part
                 *   bot action
                 */
                if(globalWoow.wildCard==0){
                    woowTree.greeting(msg);
                }

                /*
                 *   Product part
                 *   bot action
                 */
                if(globalWoow.wildCard==1){
                    woowTree.product(msg);
                }

                /*
                 *   order status part
                 *   bot action
                 */
                if(globalWoow.wildCard==2){
                    woowTree.order(msg);
                }

                /*
                 *   support part
                 *   bot action
                 */
                if(globalWoow.wildCard==3){
                    woowTree.support(msg);
                }

            }
        },
        shopper:function (msg) {
            woowMsg.shopper(msg);
            if(globalWoow.wildCard==3) {
                this.bot(msg);
            }else if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.wildCard==0 && globalWoow.ai_step==1 && globalWoow.df_status_lock==0){
                this.bot(msg);
            } else{
                //Filtering the user given messages by stopwords
                var filterMsg=woowKits.filterStopWords(msg);
                //handle empty filterMsg as repeat the message.
                if(filterMsg=="")  {
                    if(globalWoow.emptymsghandler==0){
                        globalWoow.repeatQueryEmpty=woowKits.randomMsg(globalWoow.settings.obj.empty_filter_msg)+ ' "' +$(globalWoow.settings.messageLastBot).text()+'"';
                        globalWoow.emptymsghandler++;
                    }
                    woowMsg.single(globalWoow.repeatQueryEmpty);
                }else {
                    globalWoow.emptymsghandler=0;
                    this.bot(filterMsg);
                }

            }

        }
    };

    /*
     * WoowBot Plugin Creation without selector and
     * woowbot and shoppers all activities will be handled.
     */
    $.woowbot = function(options) {

        //Using plugins defualts values or overwrite by options.
        var settings = $.extend({}, $.woowbot.defaults, options);
        //Updating global settings
        globalWoow.settings=settings;
        //updating the helpkeywords
        globalWoow.wildcardsHelp=[globalWoow.settings.obj.sys_key_help.toLowerCase(),globalWoow.settings.obj.sys_key_product.toLowerCase(),globalWoow.settings.obj.sys_key_catalog.toLowerCase(),globalWoow.settings.obj.sys_key_support.toLowerCase(),globalWoow.settings.obj.sys_key_order.toLowerCase(),globalWoow.settings.obj.sys_key_reset.toLowerCase()]
        //updating wildcards
        globalWoow.wildcards='';
        if(globalWoow.settings.obj.livechat=='1') {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard woobo_live_chat" >'+globalWoow.settings.obj.livechat_button_label+'</span>';
        }
        if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.settings.obj.custom_search_enable==1) {
            $.each(globalWoow.settings.obj.custom_intent_labels, function (i, obj) {
                globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="tag-search" data-intent-key="'+globalWoow.settings.obj.custom_intent_kewords[i]+'" >'+obj+'</span>';
            });
        }

        if(globalWoow.settings.obj.disable_product_search!=1) {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="product">' + woowKits.randomMsg(globalWoow.settings.obj.wildcard_product) + '</span>';
        }
        if(globalWoow.settings.obj.disable_catalog!=1) {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="catalog">' + woowKits.randomMsg(globalWoow.settings.obj.wildcard_catalog) + '</span>';
        }
        if(globalWoow.settings.obj.disable_featured_product!=1){
            globalWoow.wildcards+='<span class="qcld-chatbot-wildcard"  data-wildcart="featured">'+woowKits.randomMsg(globalWoow.settings.obj.featured_products)+'</span>';
        }

        if(globalWoow.settings.obj.disable_sale_product!=1){
            globalWoow.wildcards+='<span class="qcld-chatbot-wildcard"  data-wildcart="sale">'+woowKits.randomMsg(globalWoow.settings.obj.sale_products)+' </span>';
        }
        if(globalWoow.settings.obj.disable_support!=1) {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="support">' + woowKits.randomMsg(globalWoow.settings.obj.wildcard_support) + '</span>';
        }

        if(globalWoow.settings.obj.disable_order_status!=1){
            globalWoow.wildcards+='<span class="qcld-chatbot-wildcard"  data-wildcart="order">'+woowKits.randomMsg(globalWoow.settings.obj.wildcard_order)+'</span>';
        }
        if(globalWoow.settings.obj.enable_messenger==1) {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="messenger">'+woowKits.randomMsg(globalWoow.settings.obj.messenger_label)+'</span>';
        }
        if(globalWoow.settings.obj.enable_whats==1) {
            globalWoow.wildcards += '<span class="qcld-chatbot-wildcard"  data-wildcart="whatsapp">'+woowKits.randomMsg(globalWoow.settings.obj.whats_label)+'</span>';
        }
        if(globalWoow.settings.obj.disable_feedback=='') {
            globalWoow.wildcards += '<span class="qcld-chatbot-suggest-email">'+woowKits.randomMsg(globalWoow.settings.obj.feedback_label)+'</span>';
        }
        if(globalWoow.settings.obj.call_gen=="") {
            globalWoow.wildcards += '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
        }

        //Initialize the woowBot with greeting and if already initialize and given name then return greeting..
        if(localStorage.getItem("woowHitory") && globalWoow.initialize==0 ){
            var woowHistory=localStorage.getItem("woowHitory");
            $(globalWoow.settings.messageWrapper).html(woowHistory);
            //Scroll to the last element.
            woowKits.scrollTo();
            //Now mainting the current stages tokens
            globalWoow.initialize=1;
            if(localStorage.getItem("wildCard")){
                globalWoow.wildCard=localStorage.getItem("wildCard");
            }
            if(localStorage.getItem("productStep")){
                globalWoow.productStep=localStorage.getItem("productStep");
            }
            if(localStorage.getItem("orderStep")){
                globalWoow.orderStep=localStorage.getItem("orderStep");
            }
            if(localStorage.getItem("supportStep")){
                globalWoow.supportStep=localStorage.getItem("supportStep");
            }
            if(localStorage.getItem("aiStep")){
                globalWoow.ai_step=localStorage.getItem("aiStep");
            }
            //update the value for initializing.
            globalWoow.initialize=1;

        } else {
            if(globalWoow.initialize==0 && globalWoow.wildCard==0 && globalWoow.settings.obj.re_target_handler==0){
                woowWelcome.greeting();
                //update the value for initializing.
                globalWoow.initialize=1;
            }else{  // re targeting part .
                setTimeout(function (e) {
                    woowWelcome.greeting();
                },8500);
                globalWoow.initialize=1;
            }
        }
        //When shopper click on send button
        $(document).on('click',settings.sendButton,function (e) {
            var shopperMsg =$.trim($(settings.messageEditor).val());
            if(shopperMsg != ""){
                woowAction.shopper(woowKits.htmlTagsScape(shopperMsg));
                $(settings.messageEditor).val('');
            }
        });

        /*
         * Or when shopper press the ENTER key
         * Then chatting functionality will be started.
         */
        $(document).on('keypress',settings.messageEditor,function (e) {
            if (e.which == 13||e.keyCode==13) {
                e.preventDefault();
                var shopperMsg =$.trim($(settings.messageEditor).val());
                if(shopperMsg != ""){
                    woowAction.shopper(woowKits.htmlTagsScape(shopperMsg));
                    $(settings.messageEditor).val('');
                }
            }
        });
        //Click on the wildcards to select a service
        $(document).on('click','.qcld-chatbot-wildcard',function(){
            var wildcardData=$(this).attr('data-wildcart');
            var shooperChoice=$(this).text();
            woowMsg.shopper_choice(shooperChoice);
            //Wild cards handling for bot.
            if(wildcardData=='product'){
                globalWoow.wildCard=1;
                globalWoow.productStep='asking'
                woowAction.bot('from wildcard product');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("productStep", globalWoow.productStep);
            }
            if(wildcardData=='catalog'){
                woowAction.bot(globalWoow.settings.obj.sys_key_catalog.toLowerCase());
            }
            if(wildcardData=='featured'){
                globalWoow.wildCard=1;
                globalWoow.productStep='featured'
                woowAction.bot('from wildcard product');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("productStep", globalWoow.productStep);
            }
            if(wildcardData=='sale'){
                globalWoow.wildCard=1;
                globalWoow.productStep='sale'
                woowAction.bot('from wildcard product');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("productStep", globalWoow.productStep);
            }
            if(wildcardData=='order'){
                globalWoow.wildCard=2;
                globalWoow.orderStep='welcome';
                woowAction.bot('from wildcard order');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("orderStep", globalWoow.orderStep);
            }
            if(wildcardData=='support'){
                globalWoow.wildCard=3;
                globalWoow.supportStep='welcome';
                woowAction.bot('from wildcard support');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("supportStep", globalWoow.supportStep);

            }
            if(wildcardData=='back'){
                globalWoow.wildCard=0;
                woowAction.bot('start');
                //keeping value in localstorage
                localStorage.setItem("wildCard",  globalWoow.wildCard);
            }

            if(wildcardData=='messenger'){
                var url='https://www.messenger.com/t/'+globalWoow.settings.obj.fb_page_id;
                var win = window.open(url, '_blank');
                win.focus();
            }
            if(wildcardData=='whatsapp'){
                var url='https://api.whatsapp.com/send?phone='+globalWoow.settings.obj.whats_num;
                var win = window.open(url, '_blank');
                win.focus();
            }
            if(wildcardData=='tag-search'){
                woowAction.bot($(this).attr('data-intent-key'));
            }

        });
        //
        $(document).on('click','.qcld-chatbot-product-category',function(){
            var catType=$(this).attr('data-category-type');
            var shopperChoiceCatId=$(this).text()+'#'+$(this).attr('data-category-id');
            var shopperChoiceCategory=$(this).text();
            if(catType=='hasChilds'){
                //Now hide all categories but shopper choice.
                woowMsg.shopper_choice(shopperChoiceCategory);
                //updating the product steps and bringing the product by category.
                woowKits.subCats($(this).attr('data-category-id'));
                globalWoow.productStep='search';
                globalWoow.wildCard=1;
            }else{
                //Now hide all categories but shopper choice.
                woowMsg.shopper_choice(shopperChoiceCategory);
                //updating the product steps and bringing the product by category.
                globalWoow.productStep='category';
                globalWoow.wildCard=1;
                //keeping value in localstorage
                localStorage.setItem("productStep",  globalWoow.productStep);
                woowAction.bot(shopperChoiceCatId);
            }

        });
        //Product Load More features for product search or category products
        $(document).on('click','#woo-chatbot-loadmore',function (e) {
            $('#woo-chatbot-loadmore-loader').html('<img class="woo-chatbot-comment-loader" src="'+globalWoow.settings.obj.image_path+'loadmore.gif" alt="..." />');
            var loadMoreDom=$(this);
            var productOffest=loadMoreDom.attr('data-offset');
            var searchType=loadMoreDom.attr('data-search-type');
            var searchTerm=loadMoreDom.attr('data-search-term');
            var data = { 'action': 'qcld_woo_chatbot_load_more','offset': productOffest,'search_type': searchType,'search_term': searchTerm};
            //Load more ajax handler.
            woowKits.ajax(data).done(function (response) {
                //Change button text
                $('#woo-chatbot-loadmore-loader').html('');
                $('.woo-chatbot-products').append(response.html);
                loadMoreDom.attr('data-search-term',response.search_term);
                woowKits.woowHistorySave();
                loadMoreDom.attr('data-offset',response.offset);
                if(response.product_num <= response.per_page){
                    loadMoreDom.hide();
                    //Now show the user infinite.
                    setTimeout(function () {
                        var searchAgain = woowKits.randomMsg(globalWoow.settings.obj.product_infinite);
                        woowMsg.single(searchAgain);
                        globalWoow.productStep='search';
                        //keeping value in localstorage
                        localStorage.setItem("productStep",  globalWoow.productStep);
                    },globalWoow.settings.wildcardsShowTime);

                }
                //scroll to the last message
                woowKits.scrollTo();
            });
        });
        /*Products details part **/
        if(globalWoow.settings.obj.open_product_detail!=1){
            $(document).on('click','.woo-chatbot-product a',function (e) {
                e.preventDefault();
                $('.woo-chatbot-product-container').addClass('active-chatbot-product-details');
                $('.woo-chatbot-product-reload').addClass('woo-chatbot-product-loading').html('<img class="woo-chatbot-product-loader" src="'+globalWoow.settings.obj.image_path+'comment.gif" alt="Loading..." />');
                var productId=$(this).attr('woo-chatbot-pid');
                var data = { 'action':'qcld_woo_chatbot_product_details', 'woo_chatbot_pid':productId};
                //product details ajax handler.
                woowKits.ajax(data).done(function (response) {
                    console.log(response);
                    $('.woo-chatbot-product-reload').removeClass('woo-chatbot-product-loading').html('');
                    $('#woo-chatbot-product-title').html(response.title);
                    $('#woo-chatbot-product-description').html(response.description);
                    $('#woo-chatbot-product-image').html(response.image);
                    $('#woo-chatbot-product-price').html(response.price);
                    if(globalWoow.settings.obj.hide_add_to_cart==""){
                        $('#woo-chatbot-product-quantity').html(response.quantity);
                        $('#woo-chatbot-product-variable').html(response.variation);
                        $('#woo-chatbot-product-cart-button').html(response.buttton);
                    }

                    //Load gallery magnify
                    setTimeout(function () {
                        $('#woo-chatbot-product-image-large-path').magnificPopup({type:'image'});
                    },1000);

                    //For shortcode handle recenlty view product by ajax as
                    if($('#woo-chatbot-shortcode-template-container').length > 0){
                        var data = {'action':'qcld_woo_chatbot_recently_viewed_products'};
                        woowKits.ajax(data).done(function (response) {
                            $('.woo-chatbot-product-shortcode-container').html(response);
                            $('.chatbot-sidebar .woo-chatbot-products').slimScroll({height: '435px', start: 'top'});
                        });
                    }
                });

            });
        }
        //Image gallery.
        $(document).on('click','.woo-chatbot-product-image-thumbs-path',function (e) {
            e.preventDefault();
            var imagePath=$(this).attr('href');
            $('#woo-chatbot-product-image-large-path').attr('href',imagePath);
            $('#woo-chatbot-product-image-large-src').attr('src',imagePath);
            //handle thumb active one
            $('.woo-chatbot-product-image-thumbs-path').parent().removeClass('woo-chatbot-product-active-image-thumbs');
            $(this).parent().addClass('woo-chatbot-product-active-image-thumbs');
        });
        //Product details close
        $(document).on('click', '.woo-chatbot-product-close', function (e) {
            $('.woo-chatbot-product-container').removeClass('active-chatbot-product-details');
        });
        $(document).on('click', '.woo-chatbot-card-button', function (e) {
            var PostBack=$(this).attr('card-target');
            woowAction.bot(PostBack);
        });
        /*add to cart part **/
        $(document).on("click","#woo-chatbot-add-cart-button",function (e) {
            var pId=$(this).attr('woo-chatbot-product-id');
            var qnty=$("#vPQuantity").val();
            var data = {'action': 'qcld_woo_chatbot_add_to_cart','product_id': pId,'quantity': qnty };
            //add to cart ajax handler.
            woowKits.ajax(data).done(function (response) {
                //Change button text
                if(response=="simple"){
                    //Showing cart.
                    //woowKits.showCart();
					 woowKits.addToCart(); // added by Raju, QC.  no need to show cart
                    //handle the active tab on chat board.
                    $('.woo-chatbot-operation-option').each(function(){
                        if($(this).attr('data-option')=='cart'){
                            $(this).parent().addClass('woo-chatbot-operation-active');
                        }else{
                            $(this).parent().removeClass('woo-chatbot-operation-active');
                        }
                    });
                }
                //Hide the shortcode and chat ui product details.
                $('.woo-chatbot-product-container').removeClass('active-chatbot-product-details');
            });
        });
        //Add to cart operation for variable product.
        $(document).on('click','#woo-chatbot-variation-add-to-cart',function(event) {
            event.preventDefault();

            var pId=$(this).attr('woo-chatbot-product-id');
            var quanity=$('#vPQuantity').val();
            var variation_id=$(this).attr('variation_id');
            var attributes=new Array();
            $.each($("#woo-chatbot-variation-data select"), function(){
                var attribute = $(this).attr('name')+'#'+ $(this).find('option:selected').text();
                attributes.push(attribute);
            });
            var data = {
                'action': 'variable_add_to_cart',
                'p_id': pId,
                'quantity': quanity,
                'variations_id':variation_id,
                'attributes':attributes
            };
            //add to cart ajax handler.
            woowKits.ajax(data).done(function (response) {
                //Change button text
                if(response=="variable"){
                    //Showing cart.
                   // woowKits.showCart();
				   woowKits.addToCart(); // added by Raju, QC.  no need to show cart
                    //handle the active tab on chat board.
                    //handle the active tab on chat board.
                    $('.woo-chatbot-operation-option').each(function(){
                        if($(this).attr('data-option')=='cart'){
                            $(this).parent().addClass('woo-chatbot-operation-active');
                        }else{
                            $(this).parent().removeClass('woo-chatbot-operation-active');
                        }
                    });
                }
                //Hide the shortcode and chat ui product details.
                $('.woo-chatbot-product-container').removeClass('active-chatbot-product-details');
            });
        });
        //Update cart.
        $(document).on("change", ".qcld-woo-chatbot-cart-item-qnty", function () {
            //Update editor only for chat ui
            if($('#woo-chatbot-shortcode-template-container').length == 0) {
                woowKits.disableEditor(woowKits.randomMsg(globalWoow.settings.obj.cart_updating));
            }
            var currentItem=$(this);
            setTimeout(function () {
                var item_key=currentItem.attr('data-cart-item');
                var qnty=currentItem.val();
                var data = {'action': 'qcld_woo_chatbot_update_cart_item_number','cart_item_key':item_key,'qnty':qnty};
                woowKits.ajax(data).done(function () {
                    //Showing cart.
                    woowKits.showCart();
                });
            }, globalWoow.settings.preLoadingTime);
        });
        //remove the cart item from global cart.
        $(document).on("click", ".woo-chatbot-remove-cart-item", function () {
            //Update editor only for chat ui
            if($('#woo-chatbot-shortcode-template-container').length == 0) {
                woowKits.disableEditor(woowKits.randomMsg(globalWoow.settings.obj.cart_removing));
            }
            var item=$(this).attr('data-cart-item');
            var data = {'action': 'qcld_woo_chatbot_cart_item_remove', 'cart_item':item };
            woowKits.ajax(data).done(function () {
                //Showing cart.
                woowKits.showCart();
            })
        });

        /*Support query answering.. **/
        $(document).on('click','.qcld-chatbot-support-items',function (e) {
            var shopperChoose=$(this).text();
            var queryIndex=$(this).attr('data-query-index');
            woowMsg.shopper_choice(shopperChoose);
            //Now answering the query.
            var queryAns=globalWoow.settings.obj.support_ans[queryIndex];
            woowMsg.single(queryAns);
            //Asking email after showing answer.
            var orPhoneSuggest='';
            setTimeout(function(){
                if(globalWoow.settings.obj.call_sup!=1) {
                    orPhoneSuggest = '<span class="qcld-chatbot-suggest-phone" >' + woowKits.randomMsg(globalWoow.settings.obj.support_phone) + '</span>';
                }
                var orEmailSuggest='<span class="qcld-chatbot-suggest-email">'+woowKits.randomMsg(globalWoow.settings.obj.support_email)+'</span>';
                woowKits.suggestEmail(orPhoneSuggest+orEmailSuggest);
            },globalWoow.settings.wildcardsShowTime);
        });
        /*Support Email **/
        $(document).on('click','.qcld-chatbot-suggest-email',function (e) {
            var shopperChoice=$(this).text();
            woowMsg.shopper_choice(shopperChoice);
            //Then ask email address
            if(typeof(globalWoow.hasNameCookie)=='undefined'|| globalWoow.hasNameCookie==''){
                var shopperName=  globalWoow.settings.obj.shopper_demo_name;
            }else{
                var shopperName=globalWoow.hasNameCookie;
            }
            var askEmail=globalWoow.settings.obj.hello+''+shopperName+'! '+ woowKits.randomMsg(globalWoow.settings.obj.asking_email);
            woowMsg.single(askEmail);
            //Now updating the support part as .
            globalWoow.supportStep='email';
            globalWoow.wildCard=3;
            //keeping value in localstorage
            localStorage.setItem("wildCard",  globalWoow.wildCard);
            localStorage.setItem("supportStep",  globalWoow.supportStep);

        });
        /*Support Phone **/
        $(document).on('click','.qcld-chatbot-suggest-phone',function (e) {
            var shopperChoice=$(this).text();
            woowMsg.shopper_choice(shopperChoice);
            //Then ask email address
            if(typeof(globalWoow.hasNameCookie)=='undefined'|| globalWoow.hasNameCookie==''){
                var shopperName=  globalWoow.settings.obj.shopper_demo_name;
            }else{
                var shopperName=globalWoow.hasNameCookie;
            }
            var askEmail=globalWoow.settings.obj.hello+' '+shopperName+'! '+ woowKits.randomMsg(globalWoow.settings.obj.asking_phone);
            woowMsg.single(askEmail);
            //Now updating the support part as .
            globalWoow.supportStep='phone';
            globalWoow.wildCard=3;
            //keeping value in localstorage
            localStorage.setItem("wildCard",  globalWoow.wildCard);
            localStorage.setItem("supportStep",  globalWoow.supportStep);

        });
        /**
         * Live chat
         */
        $(document).on('click','.woobo_live_chat',function (e) {
            e.preventDefault();
            woowKits.woowOpenWindow(globalWoow.settings.obj.livechatlink,'Testing', 400, 600);
        });
        $(document).on('click','#woobot_live_chat_floating_btn',function (e) {
            e.preventDefault();
            woowKits.woowOpenWindow(globalWoow.settings.obj.livechatlink,'Testing', 400, 600);
        });
        //Show chat,cart and recently view products by click event.
        $(document).on('click','.woo-chatbot-operation-option',function (e) {
            e.preventDefault();
            var oppt=$(this).attr('data-option');
            if(oppt=='recent'  && globalWoow.woowIsWorking==0){
                woowKits.disableEditor(globalWoow.settings.obj.sys_key_product);
                var data = {'action':'qcld_woo_chatbot_recently_viewed_products'};
                woowKits.ajax(data).done(function (response) {
                    $(globalWoow.settings.messageWrapper).html(response);
                });
                //First remove woo-chatbot-operation-active class from all selector
                $('.woo-chatbot-operation-option').parent().removeClass('woo-chatbot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('woo-chatbot-operation-active');
            }else if(oppt=='chat' && globalWoow.woowIsWorking==0){
                $(globalWoow.settings.messageWrapper).html(localStorage.getItem("woowHitory"));
                woowKits.scrollTo();
                woowKits.enableEditor(woowKits.randomMsg(globalWoow.settings.obj.send_a_msg));
                //First remove woo-chatbot-operation-active class from all selector
                $('.woo-chatbot-operation-option').parent().removeClass('woo-chatbot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('woo-chatbot-operation-active');
            } else if(oppt=='cart' && globalWoow.woowIsWorking==0){
                woowKits.showCart();
                //First remove woo-chatbot-operation-active class from all selector
                $('.woo-chatbot-operation-option').parent().removeClass('woo-chatbot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('woo-chatbot-operation-active');
            } else if(oppt=='help' && globalWoow.woowIsWorking==0){
                if( $('.woo-chatbot-messages-container').length==0) {
                    //if from other nob then goo to the chat window
                    $(globalWoow.settings.messageWrapper).html(localStorage.getItem("woowHitory"));
                    //Showing help message
                    setTimeout(function () {
                        woowKits.scrollTo();
                        var helpWelcome = woowKits.randomMsg(globalWoow.settings.obj.help_welcome);
                        var helpMsg = woowKits.randomMsg(globalWoow.settings.obj.help_msg);
                        woowMsg.double(helpWelcome,helpMsg);
                        //dialogflow
                        if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                            globalWoow.wildCard=0;
                            globalWoow.ai_step=1;
                            localStorage.setItem("wildCard",  globalWoow.wildCard);
                            localStorage.setItem("aiStep", globalWoow.ai_step);
                        }
                    },globalWoow.settings.preLoadingTime);
                }else{
                    //Showing help message on chat self window.
                    var helpWelcome = woowKits.randomMsg(globalWoow.settings.obj.help_welcome);
                    var helpMsg = woowKits.randomMsg(globalWoow.settings.obj.help_msg);
                    woowMsg.double(helpWelcome,helpMsg);
                    //dialogflow
                    if(globalWoow.settings.obj.ai_df_enable==1 && globalWoow.df_status_lock==0){
                        globalWoow.wildCard=0;
                        globalWoow.ai_step=1;
                        localStorage.setItem("wildCard",  globalWoow.wildCard);
                        localStorage.setItem("aiStep", globalWoow.ai_step);
                    }
                }
                //First remove woo-chatbot-operation-active class from all selector
                $('.woo-chatbot-operation-option').parent().removeClass('woo-chatbot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('woo-chatbot-operation-active');

            } else if(oppt=='support' && globalWoow.woowIsWorking==0){
                if( $('.woo-chatbot-messages-container').length==0) {
                    //if from other nob then goo to the chat window
                    $(globalWoow.settings.messageWrapper).html(localStorage.getItem("woowHitory"));
                    //Showing help message
                    setTimeout(function () {
                        woowKits.scrollTo();
                        globalWoow.wildCard=3;
                        globalWoow.supportStep='welcome';
                        woowTree.support(globalWoow.settings.obj.sys_key_support.toLowerCase());
                    },globalWoow.settings.preLoadingTime);
                }else{
                    //Showing help message on chat self window.
                    globalWoow.wildCard=3;
                    globalWoow.supportStep='welcome';
                    woowTree.support(globalWoow.settings.obj.sys_key_support.toLowerCase());
                }

                //First remove woo-chatbot-operation-active class from all selector
                $('.woo-chatbot-operation-option').parent().removeClass('woo-chatbot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('woo-chatbot-operation-active');
            }
            //show chat wrapper and hide cart-checkout wrapper
            $(globalWoow.settings.messageWrapper).show();
            $('#woo-chatbot-checkout-short-code').hide();
            $('#woo-chatbot-cart-short-code').hide();


        });
        $(document).on('click','.qcld-chatbot-reset-btn',function (e) {
            e.preventDefault();
            var actionType=$(this).attr('reset-data');
            if(actionType=='yes'){
                $('#woo-chatbot-messages-container').html('');
                localStorage.removeItem('shopper');
                globalWoow.wildCard=0;
                globalWoow.ai_step=0;
                globalWoow.sbs_search_flag=0;
                localStorage.setItem("wildCard",  globalWoow.wildCard);
                localStorage.setItem("aiStep", globalWoow.ai_step);
                woowWelcome.greeting();
            } else if(actionType=='no'){
                woowAction.bot(globalWoow.settings.obj.sys_key_help.toLowerCase());
            }
        });
        return this;
    };
    //Deafault value for woowbot.If nothing passes from the work station
    //Then defaults value will be used.
    $.woowbot.defaults={
        obj:{},
        editor_handler:0,
        sendButton:'#woo-chatbot-send-message',
        messageEditor:'#woo-chatbot-editor',
        messageContainer:'#woo-chatbot-messages-container',
        messageWrapper:'.woo-chatbot-messages-wrapper',
        botContainer:'.woo-chatbot-ball-inner',
        messageLastChild:'#woo-chatbot-messages-container li:last',
        messageLastBot:'#woo-chatbot-messages-container .woo-chatbot-msg:last .woo-chatbot-paragraph',
        preLoadingTime:2000,
        wildcardsShowTime:5000,
    }

})(jQuery);