<div id="woo-chatbot-shortcode-template-container" class="<?php echo $woo_chatbot_enable_rtl;?> woo-chatbot-shortcode-template-container chatbot-shortcode-mini-mode">
    <div class="woo-chatbot-product-container">
        <div class="woo-chatbot-product-details">
            <div class="woo-chatbot-product-image-col">
                <div id="woo-chatbot-product-image"></div>
            </div>
            <!--woo-chatbot-product-image-col-->
            <div class="woo-chatbot-product-info-col">
                <div id="woo-chatbot-product-title" class="woo-chatbot-product-title"></div>
                <div id="woo-chatbot-product-price" class="woo-chatbot-product-price"></div>
                <div id="woo-chatbot-product-description" class="woo-chatbot-product-description"></div>
                <div id="woo-chatbot-product-quantity" class="woo-chatbot-product-quantity"></div>
                <div id="woo-chatbot-product-variable" class="woo-chatbot-product-variable"></div>
                <div id="woo-chatbot-product-cart-button" class="woo-chatbot-product-cart-button"></div>
            </div>
            <!--woo-chatbot-product-info-col-->
            <a class="woo-chatbot-product-close"></a>
        </div>
        <!--            woo-chatbot-product-details-->
    </div>
    <div class="chatbot-shortcode-row">
        <div class="chatbot-sidebar chatbot-left-sidebar">
            <div class="woo-chatbot-widget woo-chatbot-product-shortcode-container">
                <?php echo do_shortcode('[woowbot_products]'); ?>
            </div>
            <!--woo-chatbot-widget-->
        </div>
        <!--woo-chatbot-sidebar-->
        <div class="woo-chatbot-container">
            <div class="woo-chatbot-header">
                <h3> <?php if (get_option('qlcd_woo_chatbot_host') != '') {
                        $welcomes = unserialize(get_option('qlcd_woo_chatbot_welcome'));
                        echo $welcomes[0] . ' ' . get_option('qlcd_woo_chatbot_host');
                    } ?></h3>
            </div>
            <!--woo-chatbot-header-->
            <div class="woo-chatbot-ball-inner  woo-chatbot-content">
                <div class="woo-chatbot-messages-wrapper">
                    <ul id="woo-chatbot-messages-container" class="woo-chatbot-messages-container">
                    </ul>
                </div>
                <!--woo-chatbot-messages-wrapper-->
            </div>
            <!--woo-chatbot-ball-inner-->
            <div class="woo-chatbot-footer">
                <div id="woo-chatbot-editor-area" class="woo-chatbot-editor-area">
                    <input id="woo-chatbot-editor" class="woo-chatbot-editor" required="" placeholder="<?php echo randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_send_a_msg'))); ?>"
                          >
                    <button type="button" id="woo-chatbot-send-message" class="woo-chatbot-button"><?php _e('send', 'woochatbot'); ?></button>
                </div>
                <!--woo-chatbot-editor-container-->
            </div>
            <!--woo-chatbot-footer-->
        </div>
        <!--woo-chatbot-container-->
        <div class="chatbot-sidebar chatbot-right-sidebar">
            <div class="woo-chatbot-widget">
                <div class="chatbot-agent">
                    <?php
                    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
                        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
                    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
                    } else {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
                    }
                    ?>
                    <img src="<?php echo $woo_chatbot_custom_icon_path; ?>" alt="Agent Image">
                    <h3 class="chatbot-agent-name"><?php echo get_option('qlcd_woo_chatbot_agent'); ?></h3>
                </div>
                <!--chatbot-agent-->
            </div>
            <!--woo-chatbot-widget-->
            <div class="woo-chatbot-widget woo-chatbot-cart-shortcode-container">
                <?php echo do_shortcode('[woowbot_cart]'); ?>
            </div>
            <!--woo-chatbot-widget-->
        </div>
        <!--woo-chatbot-sidebar-->
    </div>
    <!--    chatbot-shortcode-row-->
<!--woo-chatbot-ball-container-->