<?php
if(get_option('limit_msg_show')==1){
?>
<style>
ul.woo-chatbot-messages-container > li {
display: none;
}

ul.woo-chatbot-messages-container > li:nth-last-child(-n+3) {
display: block;
}
</style>
<?php } ?>
<div id="woo-chatbot-ball-container" class=" woo-chatbot-<?php echo $qcld_woo_chatbot_theme; ?>">
    <div class="woo-chatbot-container">
        <div class="woo-chatbot-product-container">
            <div class="woo-chatbot-product-details">
                <div class="woo-chatbot-product-image-col">
                    <div id="woo-chatbot-product-image"></div>
                </div>
                <!--woo-chatbot-product-image-col-->
                <div class="woo-chatbot-product-info-col">
                    <div class="woo-chatbot-product-reload"></div>
                    <div id="woo-chatbot-product-title" class="woo-chatbot-product-title"></div>
                    <div id="woo-chatbot-product-price" class="woo-chatbot-product-price"></div>
                    <div id="woo-chatbot-product-description" class="woo-chatbot-product-description"></div>
                    <div id="woo-chatbot-product-quantity" class="woo-chatbot-product-quantity"></div>
                    <div id="woo-chatbot-product-variable" class="woo-chatbot-product-variable"></div>
                    <div id="woo-chatbot-product-cart-button" class="woo-chatbot-product-cart-button"></div>
                </div>
                <!--woo-chatbot-product-info-col-->
                <a href="#" class="woo-chatbot-product-close"></a>
            </div>
            <!--            woo-chatbot-product-details-->
        </div>
        <!--        woo-chatbot-product-container-->
        <div id="woo-chatbot-board-container" class="woo-chatbot-board-container">

            <div class="woo-chatbot-ball-inner woo-chatbot-content">
                <!-- only show on Mobile app -->
                <?php if (isset($template_app) && $template_app == 'yes') { ?>
                    <div class="woo-chatbot-cart-checkout-wrapper">
                        <div id="woo-chatbot-cart-short-code">
                        </div>
                        <div id="woo-chatbot-checkout-short-code">
                        </div>
                    </div>
                <?php } ?>
                <div class="woo-chatbot-messages-wrapper">
                    <ul id="woo-chatbot-messages-container" class="woo-chatbot-messages-container">
                    </ul>
                </div>
            </div>
            <div class="woo-chatbot-footer">
                <div id="woo-chatbot-editor-container" class="woo-chatbot-editor-container">
                    <input id="woo-chatbot-editor" class="woo-chatbot-editor" required
                           placeholder="<?php echo randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_send_a_msg'))); ?>"
                    >
                </div>
                <!--woo-chatbot-editor-container-->
                <div class="woo-chatbot-tab-nav">
                    <ul>
                        <li class="woo-chatbot-operation-active"><a
                                    class="woo-chatbot-operation-option woo-chatbot-tpl-4-chat-trigger"
                                    data-option="chat" href=""><?php echo get_option('qlcd_woo_chatbot_chat')?></a></li>
                        <li><a class="woo-chatbot-operation-option" data-option="help" href=""></a></li>
                        <li><a class="woo-chatbot-operation-option" data-option="support" href=""></a></li>
                        <li><a class="woo-chatbot-operation-option" data-option="recent" href=""></a></li>
                        <li><a class="woo-chatbot-operation-option" data-option="cart" href="">
                                <?php if (get_option('disable_woo_chatbot_cart_item_number') != 1) { ?> <span
                                        id="woo-chatbot-cart-numbers"><?php echo $cart_items_number; ?></span> <?php } ?>
                            </a>
                        </li>

                    </ul>
                </div>
                <!--woo-chatbot-tab-nav-->
            </div>
            <!--woo-chatbot-footer-->
        </div>
        <!--        woo-chatbot-board-container-->
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('body').on('click', function (event) {
            if (jQuery(event.target).hasClass('woo-chatbot-tpl-4-chat-trigger') || jQuery(event.target).hasClass('woo-chatbot-editor') || jQuery(event.target).hasClass('woo-chatbot-msg') || jQuery(event.target).parent().hasClass('woo-chatbot-msg')) {
                if (jQuery(event.target).hasClass('woo-chatbot-tpl-4-chat-trigger')){ event.preventDefault();}
				jQuery(".woo-chatbot-tab-nav").hide();
                jQuery(".slimScrollDiv").show();
				
            } else {
                jQuery(".woo-chatbot-tab-nav").show();
            }
        });

        jQuery('body').on('click', '.woo-chatbot-tab-nav ul li a', function (event) {
            jQuery(".slimScrollDiv").show();
        });


        jQuery(document).on('click', '#woo-chatbot-ball', function () {
            //jQuery('#woo-chatbot-chat-container').css({
            //    'transform': 'translateX(0px)'
            //})
            //$('#woo-chatbot-chat-container').toggle("slide", {direction: "right" }, 1000);
			console.log('clicked');
            if(jQuery(this).hasClass('chat_active')){
                jQuery('#woo-chatbot-chat-container').css({
                        'transform': 'translateX(274px)'
                })
                jQuery(this).removeClass('chat_active');
            }else{
                jQuery('#woo-chatbot-chat-container').css({
                    'transform': 'translateX(0px)'
                })
                jQuery(this).addClass('chat_active');
				
            }
			setTimeout(function(){	woow_fbicon_position();	},1000);
        })



		

        //setTimeout(function(){
            /*jQuery('#woo-chatbot-chat-container').css({
                'transform': 'translateX(0px)'
            });*/
           // jQuery("#woo-chatbot-ball").addClass('chat_active');
			//jQuery("#woo-chatbot-ball").trigger('click');
			//jQuery("#woo-chatbot-ball").trigger('click');
			//jQuery(".slimScrollDiv").show();
        //},3000)
		
		
		
		setTimeout(function () {
			if(jQuery('#woo-chatbot-ball').length>0){
			
				var pos = jQuery('#woo-chatbot-ball').offset();
				
				jQuery('.fb_dialog').css({
					'left': parseInt(parseInt(pos.left) - 30) + 'px',
					'top': parseInt(parseInt(pos.top) + 10) + 'px',
					'visibility': 'visible'
				});
				
			}
		}, 3030);

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

</script>