<div class="wrap">
  <h1 style="display:none">
    <?php _e('WoowBot Pro', 'woochatbot'); ?>
  </h1>
</div>
<div class="woo-chatbot-wrap">
<div class="icon32"><br>
</div>
<form action="<?php echo esc_attr($action); ?>" method="POST" id="woo-chatbot-admin-form"
          enctype="multipart/form-data">
  <div class="container form-container">
    <header class="woo-chatbot-admin-header">
      <div class="row">
        <div class="col-sm-6">
          <h2><?php echo __('WoowBot Control Panel', 'woochatbot'); ?></h2>
        </div>
        <div class="col-sm-6 text-right woo-chatbot-version">
          <h3>
            <?php _e('The Professional Version', 'woochatbot'); ?>
          </h3>
          <p><a target="_blank" href="https://www.quantumcloud.com/">
            <?php _e('WoowBot is a project by Web Design Company QuantumCloud', 'woochatbot'); ?>
            </a></p>
        </div>
      </div>
    </header>
    <section class="woo-chatbot-tab-container-inner">
      <div class="woo-chatbot-tabs woo-chatbot-tabs-style-flip">
      <nav>
        <ul>
          <li tab-data="general"><a href="<?php echo esc_attr($action); ?>&tab=general"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-toggle-on"> </i> </span> <span class="woowbot-admin-tab-name">
            <?php _e('GENERAL SETTINGS', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="themes"><a href="<?php echo esc_attr($action); ?>&tab=themes"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-gear faa-spin"></i> </span> <span class="woowbot-admin-tab-name">
            <?php _e('ICONS & THEMES', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="support"><a href="<?php echo esc_attr($action); ?>&tab=support"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-list"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('FAQ Builder', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="notification"><a href="<?php echo esc_attr($action); ?>&tab=notification"> <span class="woowbot-admin-tab-icon"> <i class="fa fa-bell-o"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Notification Builder', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="language"><a href="<?php echo esc_attr($action); ?>&tab=language"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-language"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('LANGUAGE CENTER', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="social"><a href="<?php echo esc_attr($action); ?>&tab=social"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-share"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('INTEGRATION', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="target"><a href="<?php echo esc_attr($action); ?>&tab=target"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-retweet"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Retargeting ', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="hours"><a href="<?php echo esc_attr($action); ?>&tab=hours"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-calendar"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Bot Activity Hour', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="ai"><a href="<?php echo esc_attr($action); ?>&tab=ai"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-500px"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Artificial intelligence', 'woochatbot'); ?>
            </span> </a></li>
          <!--<li tab-data="app"><a href="<?php echo esc_attr($action); ?>&tab=app"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-mobile"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('MOBILE APP', 'woochatbot'); ?>
            </span> </a></li>-->
          <li tab-data="embed"><a href="<?php echo esc_attr($action); ?>&tab=embed"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-mobile"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Embed Code', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="custom"><a href="<?php echo esc_attr($action); ?>&tab=custom"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-code"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Custom CSS', 'woochatbot'); ?>
            </span> </a></li>
          <li tab-data="help"><a href="<?php echo esc_attr($action); ?>&tab=help"> <span
                                            class="woowbot-admin-tab-icon"> <i class="fa fa-code"></i> </span> <span
                                            class="woowbot-admin-tab-name">
            <?php _e('Support', 'woochatbot'); ?>
            </span> </a></li>
        </ul>
      </nav>
      <div class="content-wrap">
        <section id="section-flip-1">
          <div class="top-section"> 
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <?php
                                                $url = get_site_url();
                                                $url = parse_url($url);
                                                $domain = $url['host'];
                                                //$admin_email = "admin@" . $domain;
                                                $admin_email = get_option('admin_email');
                                                ?>
                    <h4 class="qc-opt-title">
                      <?php _e('Emails Will be Sent to', 'woochatbot'); ?>
                    </h4>
                    <input type="text" class="form-control qc-opt-dcs-font"
                                                       name="qlcd_woo_chatbot_admin_email"
                                                       value="<?php echo(get_option('qlcd_woo_chatbot_admin_email') != '' ? get_option('qlcd_woo_chatbot_admin_email') : $admin_email); ?>">
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <h4 class="qc-opt-title">
                    <?php _e('WoowBot Products Search Options', 'woochatbot'); ?>
                  </h4>
                  <select class="form-control" id="woo-chatbot-search-option"
                                                    name="qlcd_woo_chatbot_search_option">
                    <option
                                                        value="standard" <?php if (get_option('qlcd_woo_chatbot_search_option') == 'standard') {
                                                    echo 'selected';
                                                } ?>>
                    <?php _e('Standard Search', 'woochatbot'); ?>
                    </option>
                    <option
                                                        value="advanced" <?php if (get_option('qlcd_woo_chatbot_search_option') == 'advanced') {
                                                    echo 'selected';
                                                } ?>>
                    <?php _e('Advanced Search', 'woochatbot'); ?>
                    </option>
                  </select>
                  <div id="woo-chatbot-standard-search">
                    <p class="qc-opt-title-font">
                      <?php _e('Use Woocommerce standard products search criteria.', 'woochatbot'); ?>
                    </p>
                  </div>
                  <div id="woo-chatbot-advanced-search">
                    <p class="qc-opt-title-font">
                      <?php _e('To Enable Title, Content, Excerpt,Categories,Tag and SKU Search', 'woochatbot'); ?>
                      <strong>
                      <?php _e(' Re-Index Products', 'woochatbot'); ?>
                      </strong> <strong>
                      <?php _e('is required', 'woochatbot'); ?>
                      .</p>
                    <div class="cxsc-settings-blocks">
                      <button type="button" class="btn btn-primary"
                                                            id="qlcd_woo_chatbot_index">
                      <?php _e('Re-Index Products', 'woochatbot'); ?>
                      </button>
                      <p><span id="qlcd_woo_chatbot_index_loader"></span> <span
                                                                id="qlcd_woo_chatbot_index_process"></span> <span
                                                                id="qlcd_woo_chatbot_index_progress"></span></p>
                    </div>
                    <p><strong style="color:red">
                      <?php _e('Note', 'woochatbot'); ?>
                      </strong> :
                      <?php _e(' For large number of products,it may take time to complete indexing.', 'woochatbot'); ?>
                    </p>
                  </div>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable WooWBot', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot" type="checkbox"
                                                   name="disable_woo_chatbot" <?php echo(get_option('disable_woo_chatbot') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot">
                    <?php _e('Disable WoowBot to Load', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable WooWBot on Mobile Device', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot_on_mobile" type="checkbox"
                                                   name="disable_woo_chatbot_on_mobile" <?php echo(get_option('disable_woo_chatbot_on_mobile') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot_on_mobile">
                    <?php _e('Disable WoowBot to Load on Mobile Device', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            
            <div class="row">
    <div class="col-xs-12">
        <h4 class="qc-opt-title"> <?php _e('Skip Greetings and Show Start Menu', 'woochatbot'); ?> </h4>
        <div class="cxsc-settings-blocks">
            <input value="1" id="skip_woo_greetings" type="checkbox"
                   name="skip_woo_greetings" <?php echo(get_option('skip_woo_greetings') == 1 ? 'checked' : ''); ?>>
            <label for="skip_woo_greetings"><?php _e('Skip Greetings and Show Start Menu', 'woochatbot'); ?> </label>
        </div>
    </div>
</div>
            
            
            <div class="row">
    <div class="col-xs-12">
        <h4 class="qc-opt-title"> <?php _e('Show Limited Messages', 'woochatbot'); ?> </h4>
        <div class="cxsc-settings-blocks">
            <input value="1" id="limit_msg_show" type="checkbox"
                   name="limit_msg_show" <?php echo(get_option('limit_msg_show') == 1 ? 'checked' : ''); ?>>
            <label for="limit_msg_show"><?php _e('Show only latest messages (min mode only)', 'woochatbot'); ?> </label>
        </div>
    </div>
</div>
            
            
            
            <div class="row">
              <div class="col-xs-12">
                <h3 class="qc-opt-title">
                  <?php _e('WooWBot Custom Color Options', 'woochatbot'); ?>
                </h3>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="enable_woo_chatbot_custom_color" type="checkbox"
                                                   name="enable_woo_chatbot_custom_color" <?php echo(get_option('enable_woo_chatbot_custom_color') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_custom_color">
                    <?php _e('Enable Custom Colors for WoowBot', 'woochatbot'); ?>
                  </label>
                </div>
                <br>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Text Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_text_color" type="hidden"
                                                       name="woo_chatbot_text_color"
                                                       value="<?php echo(get_option('woo_chatbot_text_color') != '' ? get_option('woo_chatbot_text_color') : '#37424c'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Link Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_link_color" type="hidden"
                                                       name="woo_chatbot_link_color"
                                                       value="<?php echo(get_option('woo_chatbot_link_color') != '' ? get_option('woo_chatbot_link_color') : '#e2cc1f'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Link Hover Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_link_hover_color" type="hidden"
                                                       name="woo_chatbot_link_hover_color"
                                                       value="<?php echo(get_option('woo_chatbot_link_hover_color') != '' ? get_option('woo_chatbot_link_hover_color') : '#734006'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Bot Message  Background Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_bot_msg_bg_color" type="hidden"
                                                       name="woo_chatbot_bot_msg_bg_color"
                                                       value="<?php echo(get_option('woo_chatbot_bot_msg_bg_color') != '' ? get_option('woo_chatbot_bot_msg_bg_color') : '#1f8ceb'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Bot Message Text Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_bot_msg_text_color" type="hidden"
                                                       name="woo_chatbot_bot_msg_text_color"
                                                       value="<?php echo(get_option('woo_chatbot_bot_msg_text_color') != '' ? get_option('woo_chatbot_bot_msg_text_color') : '#ffffff'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('User Message  Background Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_user_msg_bg_color" type="hidden"
                                                       name="woo_chatbot_user_msg_bg_color"
                                                       value="<?php echo(get_option('woo_chatbot_user_msg_bg_color') != '' ? get_option('woo_chatbot_user_msg_bg_color') : '#ffffff'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('User Message Text Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_user_msg_text_color" type="hidden"
                                                       name="woo_chatbot_user_msg_text_color"
                                                       value="<?php echo(get_option('woo_chatbot_user_msg_text_color') != '' ? get_option('woo_chatbot_user_msg_text_color') : '#000000'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Buttons  Background Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_buttons_bg_color" type="hidden"
                                                       name="woo_chatbot_buttons_bg_color"
                                                       value="<?php echo(get_option('woo_chatbot_buttons_bg_color') != '' ? get_option('woo_chatbot_buttons_bg_color') : '#1f8ceb'); ?>"/>
                  </div>
                </div>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <h4 class="qc-opt-title">
                      <?php _e('Buttons Text Color.', 'woochatbot'); ?>
                    </h4>
                    <input id="woo_chatbot_buttons_text_color" type="hidden"
                                                       name="woo_chatbot_buttons_text_color"
                                                       value="<?php echo(get_option('woo_chatbot_buttons_text_color') != '' ? get_option('woo_chatbot_buttons_text_color') : '#ffffff'); ?>"/>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="background: #ededed">
              <div class="col-xs-12">
                <div class="row">
                  <div class="alert alert-info" role="alert"> Intents</div>
                  <div class="col-xs-12">
                    <h3>Predefined Intents </h3>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Product Search', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_product_search"
                                                           type="checkbox"
                                                           name="disable_woo_chatbot_product_search" <?php echo(get_option('disable_woo_chatbot_product_search') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_product_search">
                        <?php _e('Disable Product Search button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Catalog', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_catalog" type="checkbox"
                                                           name="disable_woo_chatbot_catalog" <?php echo(get_option('disable_woo_chatbot_catalog') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_catalog">
                        <?php _e('Disable Catalog button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Featured Products', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_featured_product"
                                                           type="checkbox"
                                                           name="disable_woo_chatbot_featured_product" <?php echo(get_option('disable_woo_chatbot_featured_product') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_featured_product">
                        <?php _e('Disable Featured Products button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Sale Products', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_sale_product"
                                                           type="checkbox"
                                                           name="disable_woo_chatbot_sale_product" <?php echo(get_option('disable_woo_chatbot_sale_product') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_sale_product">
                        <?php _e('Disable Sale Products button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Order Status', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_order_status"
                                                           type="checkbox"
                                                           name="disable_woo_chatbot_order_status" <?php echo(get_option('disable_woo_chatbot_order_status') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_order_status">
                        <?php _e('Disable Order Status button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Support', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_support" type="checkbox"
                                                           name="disable_woo_chatbot_support" <?php echo(get_option('disable_woo_chatbot_support') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_support">
                        <?php _e('Disable Support button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                    <!--<p class="qc-opt-title-font">You can configure support intent settings from  <a class="woo-chatbot-refer-to-language" href="<?php /*echo esc_attr($action); */ ?>&tab=language"><?php /*_e('Language Center', 'woochatbot'); */ ?></a> -> Support</p>--> 
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Call Me', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_call_gen" type="checkbox"
                                                           name="disable_woo_chatbot_call_gen" <?php echo(get_option('disable_woo_chatbot_call_gen') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_call_gen">
                        <?php _e('Disable Call Me button on start', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h4 class="qc-opt-title">
                      <?php _e('Send Feedback', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="disable_woo_chatbot_feedback" type="checkbox"
                                                           name="disable_woo_chatbot_feedback" <?php echo(get_option('disable_woo_chatbot_feedback') == 1 ? 'checked' : ''); ?>>
                      <label for="disable_woo_chatbot_feedback">
                        <?php _e('Disable Send Feedback button on start & support', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <h3>Custom Intents </h3>
                    <p class="qc-opt-title-font">Custom intents allow you to build any type
                      of Intents and Responses (including rich text responses) directly in
                      DialogFlow. When you create custom intents and responses in
                      DialogFlow, WoowBot will display them when user inputs match with
                      the Custom Intents along with the responses you created.</p>
                    <p class="qc-opt-title-font">You can enable and configure custom intent
                      settings from <a class="woo-chatbot-refer-to-ai"
                                                                     href="<?php echo esc_attr($action); ?>&tab=ai">
                      <?php _e('the Artificial intelligence section', 'woochatbot'); ?>
                      </a></p>
                  </div>
                </div>
                <hr>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Sound on Page Load', 'woochatbot'); ?>
                </h4>
                <div class="form-group">
                  <input value="1" id="enable_woo_chatbot_sound_initial" type="checkbox"
                                                   name="enable_woo_chatbot_sound_initial" <?php echo(get_option('enable_woo_chatbot_sound_initial') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_sound_initial">
                    <?php _e('Enable to play sound on initial page load', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable WoowBot Icon Animation', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot_icon_animation" type="checkbox"
                                                   name="disable_woo_chatbot_icon_animation" <?php echo(get_option('disable_woo_chatbot_icon_animation') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot_icon_animation">
                    <?php _e('Disable WoowBot icon border animation', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable Cart Item Number', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot_cart_item_number" type="checkbox"
                                                   name="disable_woo_chatbot_cart_item_number" <?php echo(get_option('disable_woo_chatbot_cart_item_number') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot_cart_item_number">
                    <?php _e('Disable cart item number display', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Open Product Details in Page Instead of Bot Window', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="woo_chatbot_open_product_detail" type="checkbox"
                                                   name="woo_chatbot_open_product_detail" <?php echo(get_option('woo_chatbot_open_product_detail') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_open_product_detail">
                    <?php _e('Enable to display product details page in new tab ', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable WoowBot Opening Notification', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot_notification" type="checkbox"
                                                   name="disable_woo_chatbot_notification" <?php echo(get_option('disable_woo_chatbot_notification') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot_notification">
                    <?php _e('Disable WoowBot Opening notification messages', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Disable WoowBot Opening Notification on Mobile Devices', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="disable_woo_chatbot_notification_mobile"
                                                   type="checkbox"
                                                   name="disable_woo_chatbot_notification_mobile" <?php echo(get_option('disable_woo_chatbot_notification_mobile') == 1 ? 'checked' : ''); ?>>
                  <label for="disable_woo_chatbot_notification_mobile">
                    <?php _e('Disable WoowBot Opening notification messages on mobile devices', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Enable RTL Support for Chat', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="enable_woo_chatbot_rtl" type="checkbox"
                                                   name="enable_woo_chatbot_rtl" <?php echo(get_option('enable_woo_chatbot_rtl') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_rtl">
                    <?php _e('Enable RTL for Chat Window', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('WoowBot Open Full Screen in Mobile', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="enable_woo_chatbot_mobile_full_screen" type="checkbox"
                                                   name="enable_woo_chatbot_mobile_full_screen" <?php echo(get_option('enable_woo_chatbot_mobile_full_screen') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_mobile_full_screen">
                    <?php _e('Enable WoowBot Open Full Screen in Mobile', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <!-- row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Override WoowBot Icon\'s Position', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <?php
                                            $qcld_woo_chatbot_position_x = get_option('woo_chatbot_position_x');
                                            if ((!isset($qcld_woo_chatbot_position_x)) || ($qcld_woo_chatbot_position_x == "")) {
                                                $qcld_woo_chatbot_position_x = __("120", "woo_chatbot");
                                            }
                                            $qcld_woo_chatbot_position_y = get_option('woo_chatbot_position_y');
                                            if ((!isset($qcld_woo_chatbot_position_y)) || ($qcld_woo_chatbot_position_y == "")) {
                                                $qcld_woo_chatbot_position_y = __("120", "woo_chatbot");
                                            } ?>
                  <input type="number" class="qc-opt-dcs-font"
                                                   name="woo_chatbot_position_x"
                                                   id=""
                                                   value="<?php echo($qcld_woo_chatbot_position_x); ?>"
                                                   placeholder="<?php _e('From Right In px', 'woochatbot'); ?>">
                  <span class="qc-opt-dcs-font">
                  <?php _e('From Right In px', 'woochatbot'); ?>
                  </span>
                  <input type="number" class="qc-opt-dcs-font"
                                                   name="woo_chatbot_position_y"
                                                   id=""
                                                   value="<?php echo($qcld_woo_chatbot_position_y); ?>"
                                                   placeholder="<?php _e('From Bottom In Px', 'woochatbot'); ?>">
                  <span class="qc-opt-dcs-font">
                  <?php _e('From Bottom In px', 'woochatbot'); ?>
                  </span></div>
              </div>
              <!--.col-sm-12--> 
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Override WoowBot Icon\'s Position on Mobile Devices', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <?php
                                            $qcld_woo_chatbot_position_x_mobile = get_option('woo_chatbot_position_x_mobile');
                                            if ((!isset($qcld_woo_chatbot_position_x_mobile)) || ($qcld_woo_chatbot_position_x_mobile == "")) {
                                                $qcld_woo_chatbot_position_x_mobile = __("30", "woo_chatbot");
                                            }
                                            $qcld_woo_chatbot_position_y_mobile = get_option('woo_chatbot_position_y_mobile');
                                            if ((!isset($qcld_woo_chatbot_position_y_mobile)) || ($qcld_woo_chatbot_position_y_mobile == "")) {
                                                $qcld_woo_chatbot_position_y_mobile = __("30", "woo_chatbot");
                                            } ?>
                  <input type="number" class="qc-opt-dcs-font"
                                                   name="woo_chatbot_position_x_mobile"
                                                   id=""
                                                   value="<?php echo($qcld_woo_chatbot_position_x_mobile); ?>"
                                                   placeholder="<?php _e('From Right In px', 'woochatbot'); ?>">
                  <span class="qc-opt-dcs-font">
                  <?php _e('From Right In px', 'woochatbot'); ?>
                  </span>
                  <input type="number" class="qc-opt-dcs-font"
                                                   name="woo_chatbot_position_y_mobile"
                                                   id=""
                                                   value="<?php echo($qcld_woo_chatbot_position_y_mobile); ?>"
                                                   placeholder="<?php _e('From Bottom In Px', 'woochatbot'); ?>">
                  <span class="qc-opt-dcs-font">
                  <?php _e('From Bottom In px', 'woochatbot'); ?>
                  </span></div>
              </div>
              <!--.col-sm-12--> 
            </div>
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Number of products to show in search results. ("-1" for all products ).', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <input type="text" class="form-control qc-opt-dcs-font"
                                                       name="qlcd_woo_chatbot_ppp"
                                                       value="<?php echo(get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 6); ?>">
                  </div>
                </div>
                <!--   cxsc-settings-blocks--> 
              </div>
              <!--    col-xs-12--> 
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Display Parent Categories only', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="woo_chatbot_show_parent_category" type="checkbox"
                                                   name="woo_chatbot_show_parent_category" <?php echo(get_option('woo_chatbot_show_parent_category') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_show_parent_category">
                    <?php _e('Enable to display only parent category list.', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <?php
                                    if (get_option('woo_chatbot_show_parent_category') == 1) {
                                        $woo_chatbot_sub_cat_display = "block";
                                    } else {
                                        $woo_chatbot_sub_cat_display = "none";
                                    }
                                    ?>
              <div class="col-xs-12" id="woo_chatbot_sub_cats_container"
                                         style=" display:<?php echo $woo_chatbot_sub_cat_display; ?>">
                <h4 class="qc-opt-title">
                  <?php _e('Display Sub Category after Parent Category', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="woo_chatbot_show_sub_category" type="checkbox"
                                                   name="woo_chatbot_show_sub_category" <?php echo(get_option('woo_chatbot_show_sub_category') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_show_sub_category">
                    <?php _e('Enable to display Sub Category after Parent Category.', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Exclude Out of Stock products in Products Search', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="woo_chatbot_exclude_stock_out_product" type="checkbox"
                                                   name="woo_chatbot_exclude_stock_out_product" <?php echo(get_option('woo_chatbot_exclude_stock_out_product') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_exclude_stock_out_product">
                    <?php _e('Exclude Out of Stock products to display in search results', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Hide Add to Cart button (Catalog Mode)', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="woo_chatbot_hide_product_details_add_to_cart"
                                                   type="checkbox"
                                                   name="woo_chatbot_hide_product_details_add_to_cart" <?php echo(get_option('woo_chatbot_hide_product_details_add_to_cart') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_hide_product_details_add_to_cart">
                    <?php _e('Enable to hide Add to Cart button on product details popup', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Products display orderby', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <select class="form-control" name="qlcd_woo_chatbot_product_orderby">
                      <option value="title" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'title') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby Product Title', 'woochatbot'); ?>
                      </option>
                      <option value="date" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'date') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby Date', 'woochatbot'); ?>
                      </option>
                      <option value="modified" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'modified') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby Modify', 'woochatbot'); ?>
                      </option>
                      <option value="comment_count" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'comment_count') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby Comments Number', 'woochatbot'); ?>
                      </option>
                      <option value="rand" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'rand') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby Random', 'woochatbot'); ?>
                      </option>
                      <option value="none" <?php if (get_option('qlcd_woo_chatbot_product_orderby') == 'none') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Orderby None', 'woochatbot'); ?>
                      </option>
                    </select>
                  </div>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Product display order (ASCENDING or DESCENDING)', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <select class="form-control" name="qlcd_woo_chatbot_product_order">
                      <option value="ASC" <?php if (get_option('qlcd_woo_chatbot_product_order') == 'ASC') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('ASCENDING', 'woochatbot'); ?>
                      </option>
                      <option value="DESC" <?php if (get_option('qlcd_woo_chatbot_product_order') == 'DESC') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('DESCENDING', 'woochatbot'); ?>
                      </option>
                    </select>
                  </div>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Order Status display settings for a customer', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <div class="form-group">
                    <select class="form-control" name="qlcd_woo_chatbot_order_user">
                      <option value="not_login" <?php if (get_option('qlcd_woo_chatbot_order_user') == 'not_login') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Without User Login', 'woochatbot'); ?>
                      </option>
                      <option value="login" <?php if (get_option('qlcd_woo_chatbot_order_user') == 'login') {
                                                        echo 'selected';
                                                    } ?>>
                      <?php _e('Logged In User', 'woochatbot'); ?>
                      </option>
                    </select>
                  </div>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
            <!--                                row-->
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title"><strong>
                  <?php _e('WooWBot', 'woochatbot'); ?>
                  </strong>
                  <?php _e('Loading Control Options', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <div class="row">
                    <div class="col-sm-4 text-right"> <span class="qc-opt-title-font">
                      <?php _e('Show on Home Page', 'woochatbot'); ?>
                      </span></div>
                    <div class="col-sm-8">
                      <label class="radio-inline">
                        <input id="woo-chatbot-show-home-page" type="radio"
                                                               name="woo_chatbot_show_home_page"
                                                               value="on" <?php echo(get_option('woo_chatbot_show_home_page') == 'on' ? 'checked' : ''); ?>>
                        <?php _e('YES', 'woochatbot'); ?>
                      </label>
                      <label class="radio-inline">
                        <input id="woo-chatbot-show-home-page" type="radio"
                                                               name="woo_chatbot_show_home_page"
                                                               value="off" <?php echo(get_option('woo_chatbot_show_home_page') == 'off' ? 'checked' : ''); ?>>
                        <?php _e('NO', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                  <!--  row-->
                  <div class="row">
                    <div class="col-sm-4 text-right"> <span class="qc-opt-title-font">
                      <?php _e('Show on blog posts', 'woochatbot'); ?>
                      </span></div>
                    <div class="col-sm-8">
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-posts" type="radio"
                                                               name="woo_chatbot_show_posts"
                                                               value="on" <?php echo(get_option('woo_chatbot_show_posts') == 'on' ? 'checked' : ''); ?>>
                        <?php _e('YES', 'woochatbot'); ?>
                      </label>
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-posts" type="radio"
                                                               name="woo_chatbot_show_posts"
                                                               value="off" <?php echo(get_option('woo_chatbot_show_posts') == 'off' ? 'checked' : ''); ?>>
                        <?php _e('NO', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                  <!-- row-->
                  <div class="row">
                    <div class="col-md-4 text-right"> <span class="qc-opt-title-font">
                      <?php _e('Show on  pages', 'woochatbot'); ?>
                      </span></div>
                    <div class="col-md-8">
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-pages" type="radio"
                                                               name="woo_chatbot_show_pages"
                                                               value="on" <?php echo(get_option('woo_chatbot_show_pages') == 'on' ? 'checked' : ''); ?>>
                        <?php _e('All Pages', 'woochatbot'); ?>
                      </label>
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-pages" type="radio"
                                                               name="woo_chatbot_show_pages"
                                                               value="off" <?php echo(get_option('woo_chatbot_show_pages') == 'off' ? 'checked' : ''); ?>>
                        <?php _e('Selected Pages Only ', 'woochatbot'); ?>
                      </label>
                      <div id="woo-chatbot-show-pages-list">
                        <ul class="checkbox-list">
                          <?php
                                                            $woo_chatbot_pages = get_pages();
                                                            $woo_chatbot_select_pages = unserialize(get_option('woo_chatbot_show_pages_list'));
                                                            foreach ($woo_chatbot_pages as $woo_chatbot_page) {
                                                                ?>
                          <li>
                            <input
                                                                            id="woo_chatbot_show_page_<?php echo $woo_chatbot_page->ID; ?>"
                                                                            type="checkbox"
                                                                            name="woo_chatbot_show_pages_list[]"
                                                                            value="<?php echo $woo_chatbot_page->ID; ?>" <?php if (!empty($woo_chatbot_select_pages) && in_array($woo_chatbot_page->ID, $woo_chatbot_select_pages) == true) {
                                                                        echo 'checked';
                                                                    } ?> >
                            <label
                                                                            for="woo_chatbot_show_page_<?php echo $woo_chatbot_page->ID; ?>"> <?php echo $woo_chatbot_page->post_title; ?></label>
                          </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!--row-->
                  <div class="row">
                    <div class="col-sm-4 text-right"> <span class="qc-opt-title-font">
                      <?php _e('Show on WooCommerce', 'woochatbot'); ?>
                      </span></div>
                    <div class="col-sm-8">
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-woocommerce" type="radio"
                                                               name="woo_chatbot_show_woocommerce"
                                                               value="on" <?php echo(get_option('woo_chatbot_show_woocommerce') == 'on' ? 'checked' : ''); ?>>
                        <?php _e('YES', 'woochatbot'); ?>
                      </label>
                      <label class="radio-inline">
                        <input class="woo-chatbot-show-woocommerce" type="radio"
                                                               name="woo_chatbot_show_woocommerce"
                                                               value="off" <?php echo(get_option('woo_chatbot_show_woocommerce') == 'off' ? 'checked' : ''); ?>>
                        <?php _e('NO', 'woochatbot'); ?>
                      </label>
                    </div>
                  </div>
                  <!-- row--> 
                </div>
                <!-- cxsc-settings-blocks--> 
              </div>
              <!-- col-xs-12--> 
            </div>
            <!--  row--> 
          </div>
          <!-- top-section--> 
        </section>
        <section id="section-flip-2">
          <div class="top-section">
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Show WoowBot on a Page', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <p class="qc-opt-title-font">
                    <?php _e('Paste the shortcode', 'woochatbot'); ?>
                    <input disabled id="shirtcode-selector" type="text"
                                                       value="[woowbot-page]">
                    <?php _e('on any page to display WoowBot on that page.', 'woochatbot'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('WoowBot Icon', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <ul class="radio-list">
                    <li>
                      <label for="woo_chatbot_icon_0" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-0.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_0" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-0.png' ? 'checked' : ''); ?>
                                                               value="icon-0.png">
                        <?php _e('Icon - 0', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_1" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-1.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_1" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-1.png' ? 'checked' : ''); ?>
                                                               value="icon-1.png">
                        <?php _e('Icon - 1', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_2" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-2.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_2" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-2.png' ? 'checked' : ''); ?>
                                                               value="icon-2.png">
                        <?php _e('Icon - 2', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_3" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-3.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_3" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-3.png' ? 'checked' : ''); ?>
                                                               value="icon-3.png">
                        <?php _e('Icon - 3', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_4" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-4.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_4" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-4.png' ? 'checked' : ''); ?>
                                                               value="icon-4.png">
                        <?php _e('Icon - 4', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_5" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-5.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_5" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-5.png' ? 'checked' : ''); ?>
                                                               value="icon-5.png">
                        <?php _e('Icon - 5', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_6" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-6.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_6" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-6.png' ? 'checked' : ''); ?>
                                                               value="icon-6.png">
                        <?php _e('Icon - 6', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_7" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-7.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_7" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-7.png' ? 'checked' : ''); ?>
                                                               value="icon-7.png">
                        <?php _e('Icon - 7', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_8" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-8.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_8" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-8.png' ? 'checked' : ''); ?>
                                                               value="icon-8.png">
                        <?php _e('Icon - 8', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_9" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-9.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_9" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-9.png' ? 'checked' : ''); ?>
                                                               value="icon-9.png">
                        <?php _e('Icon - 9', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_10" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-10.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_10" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-10.png' ? 'checked' : ''); ?>
                                                               value="icon-10.png">
                        <?php _e('Icon - 10', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_11" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-11.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_11" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-11.png' ? 'checked' : ''); ?>
                                                               value="icon-11.png">
                        <?php _e('Icon - 11', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <label for="woo_chatbot_icon_12" class="qc-opt-dcs-font"><img
                                                                src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>/icon-12.png"
                                                                alt="">
                        <input id="woo_chatbot_icon_12" type="radio"
                                                               name="woo_chatbot_icon" <?php echo(get_option('woo_chatbot_icon') == 'icon-12.png' ? 'checked' : ''); ?>
                                                               value="icon-12.png">
                        <?php _e('Icon - 12', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <?php
                                                    if (get_option('woo_chatbot_custom_icon_path') != "") {
                                                        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_icon_path');
                                                    } else {
                                                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom.png';
                                                    }
                                                    ?>
                      <label for="woo_chatbot_custom_icon_input" class="qc-opt-dcs-font"> <img id="woo_chatbot_custom_icon_src"
                                                             src="<?php echo $woo_chatbot_custom_icon_path; ?>" alt="">
                        <input id="woo_chatbot_custom_icon_input" type="radio"
                                                               name="woo_chatbot_icon"
                                                               value="custom.png" <?php echo(get_option('woo_chatbot_icon') == 'custom.png' ? 'checked' : ''); ?>>
                        <?php _e('Custom Icon', 'woochatbot'); ?>
                      </label>
                    </li>
                  </ul>
                </div>
                <!--  cxsc-settings-blocks--> 
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e(' Upload custom Icon ', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input type="hidden" name="woo_chatbot_custom_icon_path"
                                                   id="woo_chatbot_custom_icon_path"
                                                   value="<?php echo $woo_chatbot_custom_icon_path; ?>"/>
                  <button type="button" class="woo_chatbot_custom_icon_button button">
                  <?php _e('Upload Icon', 'woochatbot'); ?>
                  </button>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
          </div>
          <hr>
          <div class="top-section">
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e(' WoowBot Agent Image', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <ul class="radio-list">
                    <li>
                      <label for="woo_chatbot_agent_image_def" class="qc-opt-dcs-font"> <img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>icon-0.png"
                                                             alt="">
                        <input id="woo_chatbot_agent_image_def" type="radio"
                                                               name="woo_chatbot_agent_image" <?php echo(get_option('woo_chatbot_agent_image') == 'agent-0.png' ? 'checked' : ''); ?>
                                                               value="agent-0.png">
                        <?php _e('Default Agent', 'woochatbot'); ?>
                      </label>
                    </li>
                    <li>
                      <?php
                                                    if (get_option('woo_chatbot_custom_agent_path') != "") {
                                                        $woo_chatbot_custom_agent_path = get_option('woo_chatbot_custom_agent_path');
                                                    } else {
                                                        $woo_chatbot_custom_agent_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
                                                    }
                                                    ?>
                      <label for="woo_chatbot_agent_image_custom" class="qc-opt-dcs-font"> <img id="woo_chatbot_custom_agent_src"
                                                             src="<?php echo $woo_chatbot_custom_agent_path; ?>"
                                                             alt="Agent">
                        <input type="radio" name="woo_chatbot_agent_image"
                                                               id="woo_chatbot_agent_image_custom"
                                                               value="custom-agent.png" <?php echo(get_option('woo_chatbot_agent_image') == 'custom-agent.png' ? 'checked' : ''); ?>>
                        <?php _e('Custom Agent', 'woochatbot'); ?>
                      </label>
                    </li>
                  </ul>
                </div>
                <!--                                        cxsc-settings-blocks--> 
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <h4 class="qc-opt-title">
                  <?php _e('Custom Agent Icon', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input type="hidden" name="woo_chatbot_custom_agent_path"
                                                   id="woo_chatbot_custom_agent_path"
                                                   value="<?php echo $woo_chatbot_custom_agent_path; ?>"/>
                  <button type="button" class="woo_chatbot_custom_agent_button button">
                  <?php _e('Upload Agent Icon', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div id="top-section">
            <div class="row">
              <div class="col-sm-12">
                <h4 class="qc-opt-title">
                  <?php _e('WoowBot Themes', 'woochatbot'); ?>
                </h4>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="qcld_woo_chatbot_theme_box">
                  <label for="qcld_woo_chatbot_theme_0"> <img class="thumbnail theme_prev"
                                                                                        src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>templates/template-00.JPG"
                                                                                        alt="Theme Basic">
                    <input id="qcld_woo_chatbot_theme_0" type="radio"
                                                       name="qcld_woo_chatbot_theme" <?php echo(get_option('qcld_woo_chatbot_theme') == 'template-00' ? 'checked' : ''); ?>
                                                       value="template-00">
                    <?php _e('Theme Basic', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="qcld_woo_chatbot_theme_box">
                  <label for="qcld_woo_chatbot_theme_1"> <img class="thumbnail theme_prev"
                                                                                        src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>templates/template-01.JPG"
                                                                                        alt="Theme one">
                    <input id="qcld_woo_chatbot_theme_1" type="radio"
                                                       name="qcld_woo_chatbot_theme" <?php echo(get_option('qcld_woo_chatbot_theme') == 'template-01' ? 'checked' : ''); ?>
                                                       value="template-01">
                    <?php _e('Theme One', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="qcld_woo_chatbot_theme_box">
                  <label for="qcld_woo_chatbot_theme_2"> <img class="thumbnail theme_prev"
                                                                                        src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>templates/template-02.JPG"
                                                                                        alt="Theme Two">
                    <input id="qcld_woo_chatbot_theme_2" type="radio"
                                                       name="qcld_woo_chatbot_theme"
                                                       value="template-02" <?php echo(get_option('qcld_woo_chatbot_theme') == 'template-02' ? 'checked' : ''); ?>>
                    <?php _e('Theme Two', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="qcld_woo_chatbot_theme_box">
                  <label for="qcld_woo_chatbot_theme_3"> <img class="thumbnail theme_prev"
                                                                                        src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>templates/template-03.JPG"
                                                                                        alt="Theme Three">
                    <input id="qcld_woo_chatbot_theme_3" type="radio"
                                                       name="qcld_woo_chatbot_theme"
                                                       value="template-03" <?php echo(get_option('qcld_woo_chatbot_theme') == 'template-03' ? 'checked' : ''); ?>>
                    <?php _e('Theme Three', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="qcld_woo_chatbot_theme_box">
                  <label for="qcld_woo_chatbot_theme_4"> <img class="thumbnail theme_prev"
                                                                                        src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>templates/mini-mode.jpg"
                                                                                        alt="Theme Three">
                    <input id="qcld_woo_chatbot_theme_4" type="radio"
                                                       name="qcld_woo_chatbot_theme"
                                                       value="mini-mode" <?php echo(get_option('qcld_woo_chatbot_theme') == 'mini-mode' ? 'checked' : ''); ?>>
                    <?php _e('Mini Mode', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <hr>
          
          
          
          
          
          <div id="top-section">
            <div class="row" style="margin:10px 0 10px 0">
              <div class="col-sm-12">
                <h4 class="qc-opt-title">
                  <?php _e('Custom Operation Icons', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="qcld_woo_chatbot_custom_icons" type="checkbox"
                                                   name="qcld_woo_chatbot_custom_icons" <?php echo(get_option('qcld_woo_chatbot_custom_icons') == 1 ? 'checked' : ''); ?>>
                  <label for="qcld_woo_chatbot_custom_icons">
                    <?php _e('Use custom operation icons.', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row " style="margin:10px 0 10px 0">
              <div class="col-xs-2">
                <div class="woow_icon_set align_center">
                  
				  <?php
                                            if (get_option('qcld_woo_chatbot_custom_icon_help') != "") {
                                                $qcld_woo_chatbot_custom_icon_help = get_option('qcld_woo_chatbot_custom_icon_help');
                                            } else {
                                                $qcld_woo_chatbot_custom_icon_help = QCLD_WOOCHATBOT_IMG_URL . '/icon-help.png';
                                            }
                                            ?>
                  <input type="hidden" name="qcld_woo_chatbot_custom_icon_help"
                                                   id="qcld_woo_chatbot_custom_icon_help"
                                                   value="<?php echo $qcld_woo_chatbot_custom_icon_help; ?>"/>
                  Help Icon<br />
<img class="custom_icons" id="qcld_woo_chatbot_custom_icon_help_img" src="<?php echo $qcld_woo_chatbot_custom_icon_help; ?>" />
                  
                  <button type="button" class="qcld_woo_chatbot_custom_icon_help button">
                  <?php _e('Upload', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
              
              
              <div class="col-xs-2">
                <div class="woow_icon_set align_center">
                  <?php
                                            if (get_option('qcld_woo_chatbot_custom_icon_support') != "") {
                                                $qcld_woo_chatbot_custom_icon_support = get_option('qcld_woo_chatbot_custom_icon_support');
                                            } else {
                                                $qcld_woo_chatbot_custom_icon_support = QCLD_WOOCHATBOT_IMG_URL . '/icon-support.png';
                                            }
                                            ?>
                  <input type="hidden" name="qcld_woo_chatbot_custom_icon_support"
                                                   id="qcld_woo_chatbot_custom_icon_support"
                                                   value="<?php echo $qcld_woo_chatbot_custom_icon_support; ?>"/>
                  
                  Support Icon<br />
<img class="custom_icons" id="qcld_woo_chatbot_custom_icon_support_img" src="<?php echo $qcld_woo_chatbot_custom_icon_support; ?>" />
                  <button type="button" class="qcld_woo_chatbot_custom_icon_support button">
                  <?php _e('Upload', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
              
              
              
              
              <div class="col-xs-2">
                <div class="woow_icon_set align_center">
                  <?php
                                            if (get_option('qcld_woo_chatbot_custom_icon_recent') != "") {
                                                $qcld_woo_chatbot_custom_icon_recent = get_option('qcld_woo_chatbot_custom_icon_recent');
                                            } else {
                                                $qcld_woo_chatbot_custom_icon_recent = QCLD_WOOCHATBOT_IMG_URL . '/icon-recent.png';
                                            }
                                            ?>
                  <input type="hidden" name="qcld_woo_chatbot_custom_icon_recent"
                                                   id="qcld_woo_chatbot_custom_icon_recent"
                                                   value="<?php echo $qcld_woo_chatbot_custom_icon_recent; ?>"/>
                  
                  Recent Products<br />
<img class="custom_icons" id="qcld_woo_chatbot_custom_icon_recent_img" src="<?php echo $qcld_woo_chatbot_custom_icon_recent; ?>" />
                  <button type="button" class="qcld_woo_chatbot_custom_icon_recent button">
                  <?php _e('Upload', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
              
              
              <div class="col-xs-2">
                <div class="woow_icon_set align_center">
                  <?php
                                            if (get_option('qcld_woo_chatbot_custom_icon_cart') != "") {
                                                $qcld_woo_chatbot_custom_icon_cart = get_option('qcld_woo_chatbot_custom_icon_cart');
                                            } else {
                                                $qcld_woo_chatbot_custom_icon_cart = QCLD_WOOCHATBOT_IMG_URL . '/icon-cart.png';
                                            }
                                            ?>
                  <input type="hidden" name="qcld_woo_chatbot_custom_icon_cart"
                                                   id="qcld_woo_chatbot_custom_icon_cart"
                                                   value="<?php echo $qcld_woo_chatbot_custom_icon_cart; ?>"/>
                  
                  Cart Icon<br />
<img class="custom_icons" id="qcld_woo_chatbot_custom_icon_cart_img" src="<?php echo $qcld_woo_chatbot_custom_icon_cart; ?>" />
                  <button type="button" class="qcld_woo_chatbot_custom_icon_cart button">
                  <?php _e('Upload', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
              
            <div class="col-xs-2">
            <div class="woow_icon_set align_center">
            <?php
                                        if (get_option('qcld_woo_chatbot_custom_icon_chat') != "") {
                                            $qcld_woo_chatbot_custom_icon_chat = get_option('qcld_woo_chatbot_custom_icon_chat');
                                        } else {
                                            $qcld_woo_chatbot_custom_icon_chat = QCLD_WOOCHATBOT_IMG_URL . '/icon-chat.png';
                                        }
                                        ?>
            <input type="hidden" name="qcld_woo_chatbot_custom_icon_chat"
                                               id="qcld_woo_chatbot_custom_icon_chat"
                                               value="<?php echo $qcld_woo_chatbot_custom_icon_chat; ?>"/>
            Chat Icon<br />
<img class="custom_icons" id="qcld_woo_chatbot_custom_icon_chat_img" src="<?php echo $qcld_woo_chatbot_custom_icon_chat; ?>" />
            <button type="button" class="qcld_woo_chatbot_custom_icon_chat button">
            <?php _e('Upload', 'woochatbot'); ?>
            </button>
            </div>
            </div>

              
              <!--                                    col-xs-6-->
              
            </div>
          </div>
          
          
          
           <hr>
          
          
          
          
          
          <div id="top-section">
            <div class="row">
              <div class="col-sm-12">
                <h4 class="qc-opt-title">
                  <?php _e('Custom Backgroud', 'woochatbot'); ?>
                </h4>
                <div class="cxsc-settings-blocks">
                  <input value="1" id="qcld_woo_chatbot_change_bg" type="checkbox"
                                                   name="qcld_woo_chatbot_change_bg" <?php echo(get_option('qcld_woo_chatbot_change_bg') == 1 ? 'checked' : ''); ?>>
                  <label for="qcld_woo_chatbot_change_bg">
                    <?php _e('Change the WoowBot message board background imge (except mini mode).', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="row qcld-woo-chatbot-board-bg-container" <?php if (get_option('qcld_woo_chatbot_change_bg') != 1) {
                                    echo 'style="display:none"';
                                } ?>>
              <div class="col-xs-6">
                <p class="woo-chatbot-settings-instruction">
                  <?php _e('Upload WoowBot message board background (Ideal image size 376px X 688px).', 'woochatbot'); ?>
                </p>
                <div class="cxsc-settings-blocks">
                  <?php
                                            if (get_option('qcld_woo_chatbot_board_bg_path') != "") {
                                                $qcld_woo_chatbot_board_bg_path = get_option('qcld_woo_chatbot_board_bg_path');
                                            } else {
                                                $qcld_woo_chatbot_board_bg_path = QCLD_WOOCHATBOT_IMG_URL . 'background/background.png';
                                            }
                                            ?>
                  <input type="hidden" name="qcld_woo_chatbot_board_bg_path"
                                                   id="qcld_woo_chatbot_board_bg_path"
                                                   value="<?php echo $qcld_woo_chatbot_board_bg_path; ?>"/>
                  <button type="button" class="qcld_woo_chatbot_board_bg_button button">
                  <?php _e('Upload  WoowBot background.', 'woochatbot'); ?>
                  </button>
                </div>
              </div>
              <!--                                    col-xs-6-->
              <div class="col-xs-6">
                <p class="woo-chatbot-settings-instruction">
                  <?php _e('Custom message board background', 'woochatbot'); ?>
                </p>
                <img id="qcld_woo_chatbot_board_bg_image" style="height:100%;width:100%"
                                             src="<?php echo $qcld_woo_chatbot_board_bg_path; ?>"
                                             alt="Custom Background"></div>
            </div>
          </div>
        </section>
        <section id="section-flip-3">
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <h4 class="qc-opt-title">
                  <?php _e('WoowBot Support Mail', 'woochatbot'); ?>
                </h4>
                <input type="text" class="form-control qc-opt-dcs-font"
                                               name="qlcd_woo_chatbot_email_sub"
                                               value="<?php echo(get_option('qlcd_woo_chatbot_email_sub') != '' ? get_option('qlcd_woo_chatbot_email_sub') : 'Support Email Subject'); ?>">
              </div>
              <div class="form-group">
                <h4 class="qc-opt-title">
                  <?php _e('Your email was sent successfully.Thanks!', 'woochatbot'); ?>
                </h4>
                <input type="text" class="form-control qc-opt-dcs-font"
                                               name="qlcd_woo_chatbot_email_sent"
                                               value="<?php echo(get_option('qlcd_woo_chatbot_email_sent') != '' ? get_option('qlcd_woo_chatbot_email_sent') : 'Your email was sent successfully.Thanks!'); ?>">
              </div>
              <div class="form-group">
                <h4 class="qc-opt-title">
                  <?php _e('Sorry! I could not send your mail! Please contact the webmaster.', 'woochatbot'); ?>
                </h4>
                <input type="text" class="form-control qc-opt-dcs-font"
                                               name="qlcd_woo_chatbot_email_fail"
                                               value="<?php echo(get_option('qlcd_woo_chatbot_email_fail') != '' ? get_option('qlcd_woo_chatbot_email_fail') : 'Sorry! fail to send email'); ?>">
              </div>
              <h4 class="qc-opt-title">
                <?php _e('Disable Call Me (on Support)', 'woochatbot'); ?>
              </h4>
              <div class="cxsc-settings-blocks">
                <input value="1" id="disable_woo_chatbot_call_sup" type="checkbox"
                                               name="disable_woo_chatbot_call_sup" <?php echo(get_option('disable_woo_chatbot_call_sup') == 1 ? 'checked' : ''); ?>>
                <label for="disable_woo_chatbot_call_sup">
                  <?php _e('Disable Call Me button on Support', 'woochatbot'); ?>
                </label>
              </div>
              <br>
              <br>
              <div class="form-group">
                <h4 class="qc-opt-title">
                  <?php _e('Thanks for your phone number. We will call you ASAP!', 'woochatbot'); ?>
                </h4>
                <input type="text" class="form-control qc-opt-dcs-font"
                                               name="qlcd_woo_chatbot_phone_sent"
                                               value="<?php echo(get_option('qlcd_woo_chatbot_phone_sent') != '' ? get_option('qlcd_woo_chatbot_phone_sent') : 'Thanks for your phone number. We will call you ASAP!'); ?>">
              </div>
              <div class="form-group">
                <h4 class="qc-opt-title">
                  <?php _e('Sorry! I could not collect phone number! Please contact the webmaster.', 'woochatbot'); ?>
                </h4>
                <input type="text" class="form-control qc-opt-dcs-font"
                                               name="qlcd_woo_chatbot_phone_fail"
                                               value="<?php echo(get_option('qlcd_woo_chatbot_phone_fail') != '' ? get_option('qlcd_woo_chatbot_phone_fail') : 'Sorry! I could not collect phone number!'); ?>">
              </div>
            </div>
          </div>
          <div class="top-section">
            <h4 class="qc-opt-title">
              <?php _e('Build Support Query and Answers', 'woochatbot'); ?>
            </h4>
            <div class="block-inner ui-sortable" id="woo-chatbot-support-builder">
              <?php
                                    $support_quereis = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('support_query')));
                                    $support_ans = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('support_ans')));
                                    if (count($support_ans) >= 1) {
                                        //foreach (get_option('support_ans') as $support_ans){
                                        $query_ans_counter = 0;
                                        foreach (array_combine($support_quereis, $support_ans) as $query => $ans) {
                                            ?>
              <div class="row"><span class="pull-right"> </span>
                <div class="col-xs-12">
                  <button type="button"
                                                            class="btn btn-danger btn-sm woo-chatbot-remove-support pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <span class="woo-chatbot-support-cross pull-right"> <i
                                                                class="fa fa-crosshairs" aria-hidden="true"></i> </span>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Support query ', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control" name="support_query[]"
                                                               placeholder="<?php _e('Support query ', 'woochatbot'); ?>"
                                                               value="<?php echo $query ?>">
                    <br>
                    <p class="qc-opt-dcs-font">
                      <?php _e('Support answer', 'woochatbot'); ?>
                    </p>
                    <?php wp_editor(html_entity_decode(stripcslashes($ans)), 'support_ans' . '_' . $query_ans_counter, array('textarea_name' =>
                                                            'support_ans[]',
                                                            'textarea_rows' => 20,
                                                            'editor_height' => 100,
                                                            'disabled' => 'disabled',
                                                            'media_buttons' => false,
                                                            'tinymce' => array(
                                                                'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                                        )); ?>
                  </div>
                </div>
              </div>
              <?php
                                            $query_ans_counter++;
                                        }
                                        //}
                                    } else {
                                        ?>
              <div class="row">
                <div class="col-xs-12">
                  <button type="button"
                                                        class="btn btn-danger btn-sm woo-chatbot-remove-support pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <span class="woo-chatbot-support-cross pull-right"> <i
                                                            class="fa fa-crosshairs" aria-hidden="true"></i> </span>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Support query', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control" name="support_query[]"
                                                           placeholder="<?php _e('Support query ', 'woochatbot'); ?>">
                    <br>
                    <p class="qc-opt-dcs-font"><strong>
                      <?php _e('Support answer', 'woochatbot'); ?>
                      </strong></p>
                    <?php wp_editor(html_entity_decode(stripcslashes('')), 'support_ans_0', array('textarea_name' =>
                                                        'support_ans[]',
                                                        'textarea_rows' => 20,
                                                        'editor_height' => 100,
                                                        'disabled' => 'disabled',
                                                        'media_buttons' => false,
                                                        'tinymce' => array(
                                                            'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                                    )); ?>
                  </div>
                  <br>
                </div>
              </div>
              <?php
                                    }
                                    ?>
            </div>
            <div class="row">
              <div class="col-sm-6 text-left"></div>
              <div class="col-sm-6 text-right">
                <button class="btn btn-success btn-sm" type="button"
                                                id="add-more-support-query"><i
                                                    class="fa fa-plus" aria-hidden="true"></i>
                <?php _e('Add More Questions and Answers', 'woochatbot'); ?>
                </button>
              </div>
            </div>
          </div>
        </section>
        <section id="section-flip-4">
          <div class="top-section">
            <div class="notification-block-inner">
              <div class="row">
                <div class="col-xs-12">
                  <div class="cxsc-settings-blocks">
                    <?php $notification_interval = get_option('qlcd_woo_chatbot_notification_interval') != "" ? get_option('qlcd_woo_chatbot_notification_interval') : 5 ?>
                    <h4 class="qc-opt-title">
                      <?php _e('Interval between notifications (in Seconds).', 'woochatbot'); ?>
                    </h4>
                    <input type="text" class="form-control"
                                                       name="qlcd_woo_chatbot_notification_interval"
                                                       value="<?php echo $notification_interval ?>">
                  </div>
                </div>
              </div>
              <!--                                    row-->
              <?php
                                    $notifications = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_notifications')));
                                    if (!empty($notifications)) {
                                        $chatbot_notif_counter = 0;
                                        foreach ($notifications as $notification) {
                                            ?>
              <div class="row">
                <div class="col-xs-12">
                  <button type="button"
                                                            class="btn btn-danger btn-sm woo-chatbot-remove-notification pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <div class="cxsc-settings-blocks">
                    <?php wp_editor(html_entity_decode(stripcslashes($notification)), 'qlcd_woo_chatbot_notifications_' . $chatbot_notif_counter, array('textarea_name' =>
                                                            'qlcd_woo_chatbot_notifications[]',
                                                            'textarea_rows' => 20,
                                                            'editor_height' => 100,
                                                            'disabled' => 'disabled',
                                                            'media_buttons' => false,
                                                            'tinymce' => array(
                                                                'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                                        )); ?>
                  </div>
                </div>
                <!--                                                col-xs-12--> 
              </div>
              <!--                                            row-->
              <?php
                                            $chatbot_notif_counter++;
                                        }
                                        //}
                                    } else {
                                        ?>
              <div class="row">
                <div class="col-xs-12">
                  <button type="button"
                                                        class="btn btn-danger btn-sm woo-chatbot-remove-notification pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <div class="cxsc-settings-blocks">
                    <?php wp_editor(html_entity_decode(stripcslashes('')), 'qlcd_woo_chatbot_notifications_0', array('textarea_name' =>
                                                        'qlcd_woo_chatbot_notifications[]',
                                                        'textarea_rows' => 20,
                                                        'editor_height' => 100,
                                                        'disabled' => 'disabled',
                                                        'media_buttons' => false,
                                                        'tinymce' => array(
                                                            'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                                    )); ?>
                  </div>
                </div>
              </div>
              <?php
                                    }
                                    ?>
            </div>
            <div class="row">
              <div class="col-sm-6 text-left"></div>
              <div class="col-sm-6 text-right">
                <button class="btn btn-success btn-sm" type="button"
                                                id="add-more-notification-message"><i class="fa fa-plus"
                                                                                      aria-hidden="true"></i>
                <?php _e('Add', 'woochatbot'); ?>
                </button>
              </div>
            </div>
            <!--                                row--> 
          </div>
          <!--                            top-section--> 
        </section>
        <section id="section-flip-5">
          <div class="woo-chatbot-language-center-summmery">
            <p>
              <?php _e('Add multiple variations of ChatBot responses for each node. They will be used randomly and give an appearance of more human like conversations.', 'woochatbot'); ?>
            </p>
          </div>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#woo-chatbot-lng-general">
              <?php _e('General', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-lng-product">
              <?php _e('Product Search', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-lng-order">
              <?php _e('Order Status', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-lng-support">
              <?php _e('Support', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-lng-stop-words">
              <?php _e('Stop Words', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-lng-system-keyword">
              <?php _e('System Keywords', 'woochatbot'); ?>
              </a></li>
          </ul>
          <div class="tab-content">
            <div id="woo-chatbot-lng-general" class="tab-pane fade in active">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Your Company or Website Name', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_host"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_host') != '' ? get_option('qlcd_woo_chatbot_host') : 'Our Store'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Agent name', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_agent"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_agent') != '' ? get_option('qlcd_woo_chatbot_agent') : 'Carrie'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Shopper demo name', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_shopper_demo_name"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_shopper_demo_name') != '' ? get_option('qlcd_woo_chatbot_shopper_demo_name') : 'Amigo'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('YES', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_yes"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_yes') != '' ? get_option('qlcd_woo_chatbot_yes') : 'YES'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('NO', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_no"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_no') != '' ? get_option('qlcd_woo_chatbot_no') : 'NO'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('OR', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_or"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_or') != '' ? get_option('qlcd_woo_chatbot_or') : 'OR'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Hello', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_hello"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_hello') != '' ? get_option('qlcd_woo_chatbot_hello') : 'Hello'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Sorry', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sorry"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sorry') != '' ? get_option('qlcd_woo_chatbot_sorry') : 'Sorry'); ?>">
                    </div>
   <div class="form-group">
  <h4 class="qc-opt-title">
    <?php _e('Chat', 'woochatbot'); ?>
  </h4>
  <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_chat"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_chat') != '' ? get_option('qlcd_woo_chatbot_chat') : 'chat'); ?>">
</div>

                    <div class="form-group">
                      <?php
                                                    $agent_join_options = unserialize(get_option('qlcd_woo_chatbot_agent_join'));
                                                    $agent_join_option = 'qlcd_woo_chatbot_agent_join';
                                                    $agent_join_text = __('has joined the conversation', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($agent_join_options, $agent_join_option, $agent_join_text);
                                                    ?>
                    </div>
                  </div>
                  <!--col-xs-12-->
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="text-success">
                      <?php _e(' Message setting for Greetings: ', 'woochatbot'); ?>
                    </h4>
                    <div class="form-group">
                      <?php
                                                    $welcome_to_options = unserialize(get_option('qlcd_woo_chatbot_welcome'));
                                                    $welcome_to_option = 'qlcd_woo_chatbot_welcome';
                                                    $welcome_to_text = __('Welcome to', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($welcome_to_options, $welcome_to_option, $welcome_to_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $welcome_back_options = unserialize(get_option('qlcd_woo_chatbot_welcome_back'));
                                                    $welcome_back_option = 'qlcd_woo_chatbot_welcome_back';
                                                    $welcome_back_text = __('Welcome back', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($welcome_back_options, $welcome_back_option, $welcome_back_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $back_to_start_options = unserialize(get_option('qlcd_woo_chatbot_back_to_start'));
                                                    $back_to_start_option = 'qlcd_woo_chatbot_back_to_start';
                                                    $back_to_start_text = __('Back to Start', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($back_to_start_options, $back_to_start_option, $back_to_start_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $hi_there_options = unserialize(get_option('qlcd_woo_chatbot_hi_there'));
                                                    $hi_there_option = 'qlcd_woo_chatbot_hi_there';
                                                    $hi_there_text = __('Hi There!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($hi_there_options, $hi_there_option, $hi_there_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $asking_name_options = unserialize(get_option('qlcd_woo_chatbot_asking_name'));
                                                    $asking_name_option = 'qlcd_woo_chatbot_asking_name';
                                                    $asking_name_text = __('May I know your name?', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($asking_name_options, $asking_name_option, $asking_name_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $i_am_options = unserialize(get_option('qlcd_woo_chatbot_i_am'));
                                                    $i_am_option = 'qlcd_woo_chatbot_i_am';
                                                    $i_am_text = __('I am', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($i_am_options, $i_am_option, $i_am_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $name_greeting_options = unserialize(get_option('qlcd_woo_chatbot_name_greeting'));
                                                    $name_greeting_option = 'qlcd_woo_chatbot_name_greeting';
                                                    $name_greeting_text = __('Nice to meet you', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($name_greeting_options, $name_greeting_option, $name_greeting_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $wildcard_msg_options = unserialize(get_option('qlcd_woo_chatbot_wildcard_msg'));
                                                    $wildcard_msg_option = 'qlcd_woo_chatbot_wildcard_msg';
                                                    $wildcard_msg_text = __('I am here to find  what you need. What are you looking for?', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($wildcard_msg_options, $wildcard_msg_option, $wildcard_msg_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $empty_filter_msgs = unserialize(get_option('qlcd_woo_chatbot_empty_filter_msg'));
                                                    $empty_filter_msg = 'qlcd_woo_chatbot_empty_filter_msg';
                                                    $empty_filter_msg_text = __('Sorry, I did not understand that', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($empty_filter_msgs, $empty_filter_msg, $empty_filter_msg_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $featured_product_welcome_options = unserialize(get_option('qlcd_woo_chatbot_featured_product_welcome'));
                                                    $featured_product_welcome_option = 'qlcd_woo_chatbot_featured_product_welcome';
                                                    $featured_product_welcome_text = __('I have found following featured products', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($featured_product_welcome_options, $featured_product_welcome_option, $featured_product_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $viewed_product_welcome_options = unserialize(get_option('qlcd_woo_chatbot_viewed_product_welcome'));
                                                    $viewed_product_welcome_option = 'qlcd_woo_chatbot_viewed_product_welcome';
                                                    $viewed_product_welcome_text = __('I have found following recently viewed products', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($viewed_product_welcome_options, $viewed_product_welcome_option, $viewed_product_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $latest_product_welcome_options = unserialize(get_option('qlcd_woo_chatbot_latest_product_welcome'));
                                                    $latest_product_welcome_option = 'qlcd_woo_chatbot_latest_product_welcome';
                                                    $latest_product_welcome_text = __('I have found following latest products', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($latest_product_welcome_options, $latest_product_welcome_option, $latest_product_welcome_text);
                                                    ?>
                    </div>
                    <h4 class="text-success">
                      <?php _e('Message setting for Editor Box ', 'woochatbot'); ?>
                    </h4>
                    <div class="form-group">
                      <?php
                                                    $is_typing_options = unserialize(get_option('qlcd_woo_chatbot_is_typing'));
                                                    $is_typing_option = 'qlcd_woo_chatbot_is_typing';
                                                    $is_typing_text = __('is typing...', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($is_typing_options, $is_typing_option, $is_typing_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $send_a_msg_options = unserialize(get_option('qlcd_woo_chatbot_send_a_msg'));
                                                    $send_a_msg_option = 'qlcd_woo_chatbot_send_a_msg';
                                                    $send_a_msg_text = __('Send a message', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($send_a_msg_options, $send_a_msg_option, $send_a_msg_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $choose_option_options = unserialize(get_option('qlcd_woo_chatbot_choose_option'));
                                                    $choose_option_option = 'qlcd_woo_chatbot_choose_option';
                                                    $choose_option_text = __('Choose an option', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($choose_option_options, $choose_option_option, $choose_option_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $viewed_products_options = unserialize(get_option('qlcd_woo_chatbot_viewed_products'));
                                                    $viewed_products_option = 'qlcd_woo_chatbot_viewed_products';
                                                    $viewed_products_text = __('Recently viewed products', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($viewed_products_options, $viewed_products_option, $viewed_products_text);
                                                    ?>
                    </div>
                    <h4 class="text-success">
                      <?php _e('Message setting for Shopping Cart', 'woochatbot'); ?>
                    </h4>
                    <div class="form-group">
                      <?php
                                                    $shopping_cart_options = unserialize(get_option('qlcd_woo_chatbot_shopping_cart'));
                                                    $shopping_cart_option = 'qlcd_woo_chatbot_shopping_cart';
                                                    $shopping_cart_text = __('Shopping Cart', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($shopping_cart_options, $shopping_cart_option, $shopping_cart_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $add_to_cart_options = unserialize(get_option('qlcd_woo_chatbot_add_to_cart'));
                                                    $add_to_cart_option = 'qlcd_woo_chatbot_add_to_cart';
                                                    $add_to_cart_text = __('Add to Cart', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($add_to_cart_options, $add_to_cart_option, $add_to_cart_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_link_options = unserialize(get_option('qlcd_woo_chatbot_cart_link'));
                                                    $cart_link_option = 'qlcd_woo_chatbot_cart_link';
                                                    $cart_link_text = __('Cart', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_link_options, $cart_link_option, $cart_link_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $checkout_link_options = unserialize(get_option('qlcd_woo_chatbot_checkout_link'));
                                                    $checkout_link_option = 'qlcd_woo_chatbot_checkout_link';
                                                    $checkout_link_text = __('Checkout', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($checkout_link_options, $checkout_link_option, $checkout_link_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_welcome_options = unserialize(get_option('qlcd_woo_chatbot_cart_welcome'));
                                                    $cart_welcome_option = 'qlcd_woo_chatbot_cart_welcome';
                                                    $cart_welcome_text = 'I have found following items from Shopping Cart.';
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_welcome_options, $cart_welcome_option, $cart_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $no_cart_items_options = unserialize(get_option('qlcd_woo_chatbot_no_cart_items'));
                                                    $no_cart_items_option = 'qlcd_woo_chatbot_no_cart_items';
                                                    $no_cart_items_text = __('No items in the cart', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($no_cart_items_options, $no_cart_items_option, $no_cart_items_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_title_options = unserialize(get_option('qlcd_woo_chatbot_cart_title'));
                                                    $cart_title_option = 'qlcd_woo_chatbot_cart_title';
                                                    $cart_title_text = __('Title', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_title_options, $cart_title_option, $cart_title_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_quantity_options = unserialize(get_option('qlcd_woo_chatbot_cart_quantity'));
                                                    $cart_quantity_option = 'qlcd_woo_chatbot_cart_quantity';
                                                    $cart_quantity_text = __('Qty', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_quantity_options, $cart_quantity_option, $cart_quantity_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_price_options = unserialize(get_option('qlcd_woo_chatbot_cart_price'));
                                                    $cart_price_option = 'qlcd_woo_chatbot_cart_price';
                                                    $cart_price_text = __('Price', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_price_options, $cart_price_option, $cart_price_text);
                                                    ?>
                    </div>
                    
                    <div class="form-group">
					<?php
													$cart_total_options = unserialize(get_option('qlcd_woo_chatbot_cart_total'));
													$cart_total_option = 'qlcd_woo_chatbot_cart_total';
													$cart_total_text = __('Total', 'woochatbot');
													$this->qcld_woo_chatbot_dynamic_multi_option($cart_total_options, $cart_total_option, $cart_total_text);
                    ?>
                    </div>
                    
                    <div class="form-group">
                      <?php
                                                    $cart_updating_options = unserialize(get_option('qlcd_woo_chatbot_cart_updating'));
                                                    $cart_updating_option = 'qlcd_woo_chatbot_cart_updating';
                                                    $cart_updating_text = __('Updating cart items ...', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_updating_options, $cart_updating_option, $cart_updating_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $cart_removing_options = unserialize(get_option('qlcd_woo_chatbot_cart_removing'));
                                                    $cart_removing_option = 'qlcd_woo_chatbot_cart_removing';
                                                    $cart_removing_text = __('Removing cart items ...', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($cart_removing_options, $cart_removing_option, $cart_removing_text);
                                                    ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="woo-chatbot-lng-product" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <div class="form-group">
                      <?php
                                                    $wildcard_product_options = unserialize(get_option('qlcd_woo_chatbot_wildcard_product'));
                                                    $wildcard_product_option = 'qlcd_woo_chatbot_wildcard_product';
                                                    $wildcard_product_text = __('Product Search', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($wildcard_product_options, $wildcard_product_option, $wildcard_product_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $wildcard_catalog_options = unserialize(get_option('qlcd_woo_chatbot_wildcard_catalog'));
                                                    $wildcard_catalog_option = 'qlcd_woo_chatbot_wildcard_catalog';
                                                    $wildcard_catalog_text = __('Catalog', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($wildcard_catalog_options, $wildcard_catalog_option, $wildcard_catalog_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $featured_products_options = unserialize(get_option('qlcd_woo_chatbot_featured_products'));
                                                    $featured_products_option = 'qlcd_woo_chatbot_featured_products';
                                                    $featured_products_text = __('Featured Products ', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($featured_products_options, $featured_products_option, $featured_products_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $sale_products_options = unserialize(get_option('qlcd_woo_chatbot_sale_products'));
                                                    $sale_products_option = 'qlcd_woo_chatbot_sale_products';
                                                    $sale_products_text = __('Products on Sale', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($sale_products_options, $sale_products_option, $sale_products_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $product_asking_options = unserialize(get_option('qlcd_woo_chatbot_product_asking'));
                                                    $product_asking_option = 'qlcd_woo_chatbot_product_asking';
                                                    $product_asking_text = __('What are you shopping for?', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($product_asking_options, $product_asking_option, $product_asking_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $product_success_options = unserialize(get_option('qlcd_woo_chatbot_product_success'));
                                                    $product_success_option = 'qlcd_woo_chatbot_product_success';
                                                    $product_success_text = __('Great! We have these products for', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($product_success_options, $product_success_option, $product_success_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $product_fail_options = unserialize(get_option('qlcd_woo_chatbot_product_fail'));
                                                    $product_fail_option = 'qlcd_woo_chatbot_product_fail';
                                                    $product_fail_text = __('Oops! Nothing matches your criteria', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($product_fail_options, $product_fail_option, $product_fail_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $product_suggest_options = unserialize(get_option('qlcd_woo_chatbot_product_suggest'));
                                                    $product_suggest_option = 'qlcd_woo_chatbot_product_suggest';
                                                    $product_suggest_text = __('You can browse our extensive catalog. Just pick a category from below:', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($product_suggest_options, $product_suggest_option, $product_suggest_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $product_infinite_options = unserialize(get_option('qlcd_woo_chatbot_product_infinite'));
                                                    $product_infinite_option = 'qlcd_woo_chatbot_product_infinite';
                                                    $product_infinite_text = __('Too many choices? Let\'s try another search term', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($product_infinite_options, $product_infinite_option, $product_infinite_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $load_more_options = unserialize(get_option('qlcd_woo_chatbot_load_more'));
                                                    $load_more_option = 'qlcd_woo_chatbot_load_more';
                                                    $load_more_text = __('Load More', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($load_more_options, $load_more_option, $load_more_text);
                                                    ?>
                    </div>
                  </div>
                  <!--                                            col-xs-12--> 
                </div>
                <!--                                        row--> 
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-lng-order" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <div class="form-group">
                      <?php
                                                    $wildcard_order_options = unserialize(get_option('qlcd_woo_chatbot_wildcard_order'));
                                                    $wildcard_order_option = 'qlcd_woo_chatbot_wildcard_order';
                                                    $wildcard_order_text = __('Order Status', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($wildcard_order_options, $wildcard_order_option, $wildcard_order_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $order_welcome_options = unserialize(get_option('qlcd_woo_chatbot_order_welcome'));
                                                    $order_welcome_option = 'qlcd_woo_chatbot_order_welcome';
                                                    $order_welcome_text = __('Welcome to Order status section!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($order_welcome_options, $order_welcome_option, $order_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $username_asking_options = unserialize(get_option('qlcd_woo_chatbot_order_username_asking'));
                                                    $username_asking_option = 'qlcd_woo_chatbot_order_username_asking';
                                                    $username_asking_text = __('Please type your username?', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($username_asking_options, $username_asking_option, $username_asking_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $username_not_exist_options = unserialize(get_option('qlcd_woo_chatbot_order_username_not_exist'));
                                                    $username_not_exist_option = 'qlcd_woo_chatbot_order_username_not_exist';
                                                    $username_not_exist_text = __('This username does not exist.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($username_not_exist_options, $username_not_exist_option, $username_not_exist_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $username_thanks_options = unserialize(get_option('qlcd_woo_chatbot_order_username_thanks'));
                                                    $username_thanks_option = 'qlcd_woo_chatbot_order_username_thanks';
                                                    $username_thanks_text = __('Thank you for given username!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($username_thanks_options, $username_thanks_option, $username_thanks_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $username_password_options = unserialize(get_option('qlcd_woo_chatbot_order_username_password'));
                                                    $username_password_option = 'qlcd_woo_chatbot_order_username_password';
                                                    $username_password_text = __('Please type your password', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($username_password_options, $username_password_option, $username_password_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $password_incorrect_options = unserialize(get_option('qlcd_woo_chatbot_order_password_incorrect'));
                                                    $password_incorrect_option = 'qlcd_woo_chatbot_order_password_incorrect';
                                                    $password_incorrect_text = __('Sorry Password is not correct!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($password_incorrect_options, $password_incorrect_option, $password_incorrect_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $order_found_options = unserialize(get_option('qlcd_woo_chatbot_order_found'));
                                                    $order_found_found_option = 'qlcd_woo_chatbot_order_found';
                                                    $order_found_found_text = __('I have found the following orders', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($order_found_options, $order_found_found_option, $order_found_found_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $order_not_found_options = unserialize(get_option('qlcd_woo_chatbot_order_not_found'));
                                                    $order_not_found_option = 'qlcd_woo_chatbot_order_not_found';
                                                    $order_not_found_text = __('I did not find any order by you', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($order_not_found_options, $order_not_found_option, $order_not_found_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $order_email_support_options = unserialize(get_option('qlcd_woo_chatbot_order_email_support'));
                                                    $order_email_support_option = 'qlcd_woo_chatbot_order_email_support';
                                                    $order_email_support_text = __('Email our support center about your order.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($order_email_support_options, $order_email_support_option, $order_email_support_text);
                                                    ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="woo-chatbot-lng-support" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <div class="form-group">
                      <?php
                                                    $wildcard_support_options = unserialize(get_option('qlcd_woo_chatbot_wildcard_support'));
                                                    $wildcard_support_option = 'qlcd_woo_chatbot_wildcard_support';
                                                    $wildcard_support_text = __('Support', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($wildcard_support_options, $wildcard_support_option, $wildcard_support_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $support_welcome_options = unserialize(get_option('qlcd_woo_chatbot_support_welcome'));
                                                    $support_welcome_option = 'qlcd_woo_chatbot_support_welcome';
                                                    $support_welcome_text = __('Welcome to Support Section', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($support_welcome_options, $support_welcome_option, $support_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $support_email_options = unserialize(get_option('qlcd_woo_chatbot_support_email'));
                                                    $support_email_option = 'qlcd_woo_chatbot_support_email';
                                                    $support_email_text = __('Click me if you want to send us a email.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($support_email_options, $support_email_option, $support_email_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $asking_email_options = unserialize(get_option('qlcd_woo_chatbot_asking_email'));
                                                    $asking_email_option = 'qlcd_woo_chatbot_asking_email';
                                                    $asking_email_text = __('Please provide your email address', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($asking_email_options, $asking_email_option, $asking_email_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $invalid_email_options = unserialize(get_option('qlcd_woo_chatbot_invalid_email'));
                                                    $invalid_email_option = 'qlcd_woo_chatbot_invalid_email';
                                                    $invalid_email_text = __('Sorry, Email address is not valid! Please provide a valid email.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($invalid_email_options, $invalid_email_option, $invalid_email_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $asking_msg_options = unserialize(get_option('qlcd_woo_chatbot_asking_msg'));
                                                    $asking_msg_option = 'qlcd_woo_chatbot_asking_msg';
                                                    $asking_msg_text = __('Thank you for email address. Please write your message now.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($asking_msg_options, $asking_msg_option, $asking_msg_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $support_phone_options = unserialize(get_option('qlcd_woo_chatbot_support_phone'));
                                                    $support_phone_option = 'qlcd_woo_chatbot_support_phone';
                                                    $support_phone_text = __('Leave your number. We will call you back!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($support_phone_options, $support_phone_option, $support_phone_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $feedback_label_options = unserialize(get_option('qlcd_woo_chatbot_feedback_label'));
                                                    $feedback_label_option = 'qlcd_woo_chatbot_feedback_label';
                                                    $feedback_label_text = __('Send Feedback!', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($feedback_label_options, $feedback_label_option, $feedback_label_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $asking_phone_options = unserialize(get_option('qlcd_woo_chatbot_asking_phone'));
                                                    $asking_phone_option = 'qlcd_woo_chatbot_asking_phone';
                                                    $asking_phone_text = __('Please provide your Phone number', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($asking_phone_options, $asking_phone_option, $asking_phone_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $thanks_phone_options = unserialize(get_option('qlcd_woo_chatbot_thank_for_phone'));
                                                    $thanks_phone_option = 'qlcd_woo_chatbot_thank_for_phone';
                                                    $thanks_phone_text = __('Thank you for Phone number', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($thanks_phone_options, $thanks_phone_option, $thanks_phone_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $support_option_again_options = unserialize(get_option('qlcd_woo_chatbot_support_option_again'));
                                                    $support_option_again_option = 'qlcd_woo_chatbot_support_option_again';
                                                    $support_option_again_text = __('You may choose option from below.', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($support_option_again_options, $support_option_again_option, $support_option_again_text);
                                                    ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="woo-chatbot-lng-stop-words" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Stop Words Settings Options', 'woochatbot'); ?>
                      </h4>
                      <select class="form-control" id="qlcd_woo_chatbot_stop_words_name"
                                                            name="qlcd_woo_chatbot_stop_words_name">
                        <option value="english" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'english') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('English', 'woochatbot'); ?>
                        </option>
                        <option value="arabic" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'arabic') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Arabic', 'woochatbot'); ?>
                        </option>
                        <option value="bengali" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'bengali') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Bengali', 'woochatbot'); ?>
                        </option>
                        <option value="bulgarian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'bulgarian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Bulgarian', 'woochatbot'); ?>
                        </option>
                        <option value="czech" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'czech') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Czech', 'woochatbot'); ?>
                        </option>
                        <option value="catalan" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'catalan') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Catalan', 'woochatbot'); ?>
                        </option>
                        <option value="danish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'danish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Danish', 'woochatbot'); ?>
                        </option>
                        <option value="dutch" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'dutch') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Dutch', 'woochatbot'); ?>
                        </option>
                        <option value="finnish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'finnish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Finnish', 'woochatbot'); ?>
                        </option>
                        <option value="french" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'french') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('French', 'woochatbot'); ?>
                        </option>
                        <option value="german" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'german') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('German', 'woochatbot'); ?>
                        </option>
                        <option value="hindi" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'hindi') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Hindi', 'woochatbot'); ?>
                        </option>
                        <option value="hungarian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'hungarian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Hungarian', 'woochatbot'); ?>
                        </option>
                        <option value="indonesian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'indonesian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Indonesian', 'woochatbot'); ?>
                        </option>
                        <option value="italian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'italian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Italian', 'woochatbot'); ?>
                        </option>
                        <option value="norwegian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'norwegian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Norwegian', 'woochatbot'); ?>
                        </option>
                        <option value="persian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'persian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Persian', 'woochatbot'); ?>
                        </option>
                        <option value="polish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'polish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Polish', 'woochatbot'); ?>
                        </option>
                        <option value="portuguese" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'portuguese') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Portuguese', 'woochatbot'); ?>
                        </option>
                        <option value="romanian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'ukrainian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Romanian', 'woochatbot'); ?>
                        </option>
                        <option value="russian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'russian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Russian', 'woochatbot'); ?>
                        </option>
                        <option value="slovak" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'slovak') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Slovak', 'woochatbot'); ?>
                        </option>
                        <option value="spanish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'spanish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Spanish', 'woochatbot'); ?>
                        </option>
                        <option value="swedish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'swedish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Swedish', 'woochatbot'); ?>
                        </option>
                        <option value="turkish" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'turkish') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Turkish', 'woochatbot'); ?>
                        </option>
                        <option value="ukrainian" <?php if (get_option('qlcd_woo_chatbot_stop_words_name') == 'ukrainian') {
                                                            echo "selected";
                                                        } ?> >
                        <?php _e('Ukrainian', 'woochatbot'); ?>
                        </option>
                      </select>
                    </div>
                  </div>
                  <!--                                            col-sm-12--> 
                </div>
                <!-- row-->
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Stop Words.', 'woochatbot'); ?>
                      </h4>
                      <?php $englishStopwords = "a,able,about,above,abst,accordance,according,accordingly,across,act,actually,added,adj,affected,affecting,affects,after,afterwards,again,against,ah,all,almost,alone,along,already,also,although,always,am,among,amongst,an,and,announce,another,any,anybody,anyhow,anymore,anyone,anything,anyway,anyways,anywhere,apparently,approximately,are,aren,arent,arise,around,as,aside,ask,asking,at,auth,available,away,awfully,b,back,be,became,because,become,becomes,becoming,been,before,beforehand,begin,beginning,beginnings,begins,behind,being,believe,below,beside,besides,between,beyond,biol,both,brief,briefly,but,by,c,ca,came,can,cannot,can't,cause,causes,certain,certainly,co,com,come,comes,contain,containing,contains,could,couldnt,d,date,did,didn't,different,do,does,doesn't,doing,done,don't,down,downwards,due,during,e,each,ed,edu,effect,eg,eight,eighty,either,else,elsewhere,end,ending,enough,especially,et,et-al,etc,even,ever,every,everybody,everyone,everything,everywhere,ex,except,f,far,few,ff,fifth,first,five,fix,followed,following,follows,for,former,formerly,forth,found,four,from,further,furthermore,g,gave,get,gets,getting,give,given,gives,giving,go,goes,gone,got,gotten,h,had,happens,hardly,has,hasn't,have,haven't,having,he,hed,hence,her,here,hereafter,hereby,herein,heres,hereupon,hers,herself,hes,hi,hid,him,himself,his,hither,home,how,howbeit,however,hundred,i,id,ie,if,i'll,im,immediate,immediately,importance,important,in,inc,indeed,index,information,instead,into,invention,inward,is,isn't,it,itd,it'll,its,itself,i've,j,just,k,keep,keeps,kept,kg,km,know,known,knows,l,largely,last,lately,later,latter,latterly,least,less,lest,let,lets,like,liked,likely,line,little,'ll,look,looking,looks,ltd,m,made,mainly,make,makes,many,may,maybe,me,mean,means,meantime,meanwhile,merely,mg,might,million,miss,ml,more,moreover,most,mostly,mr,mrs,much,mug,must,my,myself,n,na,name,namely,nay,nd,near,nearly,necessarily,necessary,need,needs,neither,never,nevertheless,new,next,nine,ninety,no,nobody,non,none,nonetheless,noone,nor,normally,nos,not,noted,nothing,now,nowhere,o,obtain,obtained,obviously,of,off,often,oh,ok,okay,old,omitted,on,once,one,ones,only,onto,or,ord,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,overall,owing,own,p,page,pages,part,particular,particularly,past,per,perhaps,placed,please,plus,poorly,possible,possibly,potentially,pp,predominantly,present,previously,primarily,probably,promptly,proud,provides,put,q,que,quickly,quite,qv,r,ran,rather,rd,re,readily,really,recent,recently,ref,refs,regarding,regardless,regards,related,relatively,research,respectively,resulted,resulting,results,right,run,s,said,same,saw,say,saying,says,sec,section,see,seeing,seem,seemed,seeming,seems,seen,self,selves,sent,seven,several,shall,she,shed,she'll,shes,should,shouldn't,show,showed,shown,showns,shows,significant,significantly,similar,similarly,since,six,slightly,so,some,somebody,somehow,someone,somethan,something,sometime,sometimes,somewhat,somewhere,soon,sorry,specifically,specified,specify,specifying,still,stop,strongly,sub,substantially,successfully,such,sufficiently,suggest,sup,sure,t,take,taken,taking,tell,tends,th,than,thank,thanks,thanx,that,that'll,thats,that've,the,their,theirs,them,themselves,then,thence,there,thereafter,thereby,thered,therefore,therein,there'll,thereof,therere,theres,thereto,thereupon,there've,these,they,theyd,they'll,theyre,they've,think,this,those,thou,though,thoughh,thousand,throug,through,throughout,thru,thus,til,tip,to,together,too,took,toward,towards,tried,tries,truly,try,trying,ts,twice,two,u,un,under,unfortunately,unless,unlike,unlikely,until,unto,up,upon,ups,us,use,used,useful,usefully,usefulness,uses,using,usually,v,value,various,'ve,very,via,viz,vol,vols,vs,w,want,wants,was,wasnt,way,we,wed,welcome,we'll,went,were,werent,we've,what,whatever,what'll,whats,when,whence,whenever,where,whereafter,whereas,whereby,wherein,wheres,whereupon,wherever,whether,which,while,whim,whither,who,whod,whoever,whole,who'll,whom,whomever,whos,whose,why,widely,willing,wish,with,within,without,wont,words,world,would,wouldnt,www,x,y,yes,yet,you,youd,you'll,your,youre,yours,yourself,yourselves,you've,z,zero";
                                                    ?>
                      <textarea id="qlcd_woo_chatbot_stop_words" cols="85" rows="10"
                                                              name="qlcd_woo_chatbot_stop_words"><?php echo(get_option('qlcd_woo_chatbot_stop_words') != '' ? str_replace('\\', '', get_option('qlcd_woo_chatbot_stop_words')) : $englishStopwords) ?> </textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-lng-system-keyword" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Start Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_help"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_help') != '' ? get_option('qlcd_woo_chatbot_sys_key_help') : 'start'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Product Search Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_product"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_product') != '' ? get_option('qlcd_woo_chatbot_sys_key_product') : 'product'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Product Catalog Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_catalog"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_catalog') != '' ? get_option('qlcd_woo_chatbot_sys_key_catalog') : 'catalog'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title"><strong>
                        <?php _e('Order Status  Keyword', 'woochatbot'); ?>
                        </strong>
                        <?php _e('Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_order"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_order') != '' ? get_option('qlcd_woo_chatbot_sys_key_order') : 'order'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title"><strong>
                        <?php _e('Support', 'woochatbot'); ?>
                        </strong>
                        <?php _e('Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_support"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_support') != '' ? get_option('qlcd_woo_chatbot_sys_key_support') : 'support'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title"><strong>
                        <?php _e('Converstion History Clear', 'woochatbot'); ?>
                        </strong>
                        <?php _e('Keyword', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_sys_key_reset"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_sys_key_reset') != '' ? get_option('qlcd_woo_chatbot_sys_key_reset') : 'reset'); ?>">
                    </div>
                    <div class="form-group">
                      <?php
                                                    $help_welcome_options = unserialize(get_option('qlcd_woo_chatbot_help_welcome'));
                                                    $help_welcome_option = 'qlcd_woo_chatbot_help_welcome';
                                                    $help_welcome_text = 'Welcome to Help Section';
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($help_welcome_options, $help_welcome_option, $help_welcome_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $help_msg_options = unserialize(get_option('qlcd_woo_chatbot_help_msg'));
                                                    $help_msg_option = 'qlcd_woo_chatbot_help_msg';
                                                    $help_msg_text = '<h3>Type and Hit Enter</h3>  1. <b>start</b> Get back to the main menu. <br> 2. <b>prouduct</b> for  product. <br>  3. <b>catalog</b> for  PRODUCT CATEGORIES. <br> 4. <b>order</b> for  ORDER STATUS. <br> 5. <b>support</b> for  SUPPORT. <br> 6. <b>reset</b> To clear chat history and start from the beginning.';
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($help_msg_options, $help_msg_option, $help_msg_text);
                                                    ?>
                    </div>
                    <div class="form-group">
                      <?php
                                                    $reset_options = unserialize(get_option('qlcd_woo_chatbot_reset'));
                                                    $reset_option = 'qlcd_woo_chatbot_reset';
                                                    $reset_text = 'Do you want to clear our chat history and start over?';
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($reset_options, $reset_option, $reset_text);
                                                    ?>
                    </div>
                  </div>
                  <!--                                            col-xs-12--> 
                </div>
                <!--                                        row--> 
              </div>
              <!--                                    top-section--> 
            </div>
            <!--                                woo-chatbot-lng-system-keyword--> 
          </div>
          <!--                            tab-content--> 
        </section>
        <section id="section-flip-6">
          <div class="woo-chatbot-language-center-summmery">
            <p>
              <?php _e('WoowBot integration like Facebook Messenger, WhatApps etc.', 'woochatbot'); ?>
            </p>
          </div>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#woo-chatbot-scl-fb">
              <?php _e('Messenger', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-skype">
              <?php _e('Skype', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-whats">
              <?php _e('WhatsApp', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-viber">
              <?php _e('Viber', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-link">
              <?php _e('Web Link', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-phone">
              <?php _e('Phone', 'woochatbot'); ?>
              </a></li>
            <li><a data-toggle="tab" href="#woo-chatbot-scl-livechat">
              <?php _e('Live Chat', 'woochatbot'); ?>
              </a></li>
          </ul>
          <div class="tab-content">
            <div id="woo-chatbot-scl-fb" class="tab-pane fade in active">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-interaction-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Enable Messenger (if enabled it will show as option during chat and support)', 'woochatbot'); ?>
                    </h4>
                    <p>
                      <?php _e('Create', 'woochatbot'); ?>
                      <a href="https://www.facebook.com/business/help/104002523024878"
                                                       target="_blank">
                      <?php _e('Facebook Page Id', 'woochatbot'); ?>
                      </a>
                      <?php _e('and', 'woochatbot'); ?>
                      <a href="https://developers.facebook.com/docs/apps/register"
                                                       target="_blank">
                      <?php _e('Facebook App ID', 'woochatbot'); ?>
                      </a>.</p>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_messenger" type="checkbox"
                                                           name="enable_woo_chatbot_messenger" <?php echo(get_option('enable_woo_chatbot_messenger') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_messenger">
                        <?php _e('Enable Messenger', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <?php
                                                    $messenger_options = unserialize(get_option('qlcd_woo_chatbot_messenger_label'));
                                                    $messenger_option = 'qlcd_woo_chatbot_messenger_label';
                                                    $messenger_text = __('Chat with Us on Facebook Messenger', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($messenger_options, $messenger_option, $messenger_text);
                                                    ?>
                    </div>
                    <h4 class="qc-opt-title">
                      <?php _e('Show Messenger Icon beside WoowBot Icon', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_messenger_floating_icon"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_messenger_floating_icon" <?php echo(get_option('enable_woo_chatbot_messenger_floating_icon') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_messenger_floating_icon">
                        <?php _e('Enable to display Messenger Icon beside WoowBot Icon', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Facebook App ID', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_fb_app_id"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_fb_app_id') != '' ? get_option('qlcd_woo_chatbot_fb_app_id') : ''); ?>"
                                                           placeholder="<?php _e('Facebook App ID', 'woochatbot'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Facebook Page ID', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_fb_page_id"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_fb_page_id') != '' ? get_option('qlcd_woo_chatbot_fb_page_id') : ''); ?>"
                                                           placeholder="<?php _e('Facebook Page ID', 'woochatbot'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Messenger Color', 'woochatbot'); ?>
                      </h4>
                      <input id="qlcd_woo_chatbot_fb_color" type="hidden"
                                                           name="qlcd_woo_chatbot_fb_color"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_fb_color') != '' ? get_option('qlcd_woo_chatbot_fb_color') : '#0084ff'); ?>"/>
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Logged In Welcome Message', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_fb_in_msg"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_fb_in_msg') != '' ? get_option('qlcd_woo_chatbot_fb_in_msg') : 'Welcome to Our Store!'); ?>"
                                                           placeholder="<?php _e('Facebook logged in welcome message', 'woochatbot'); ?>">
                    </div>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Logged Out Welcome Message', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_fb_out_msg"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_fb_out_msg') != '' ? get_option('qlcd_woo_chatbot_fb_out_msg') : 'You are not logged in'); ?>"
                                                           placeholder="<?php _e('Facebook logged out welcome message', 'woochatbot'); ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="woo-chatbot-scl-skype" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Show Skype Floating Icon on WoowBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_skype_floating_icon"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_skype_floating_icon" <?php echo(get_option('enable_woo_chatbot_skype_floating_icon') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_skype_floating_icon">
                        <?php _e('Enable to display Skype Floating Icon on WoowBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Skype ID', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="enable_woo_chatbot_skype_id"
                                                           value="<?php echo(get_option('enable_woo_chatbot_skype_id') != '' ? get_option('enable_woo_chatbot_skype_id') : ''); ?>"
                                                           placeholder="<?php _e('Skype', 'woochatbot'); ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="woo-chatbot-scl-whats" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Enable WhatsApp (if enabled it will show as option during chat and support)', 'woochatbot'); ?>
                    </h4>
                    <p>
                      <?php _e('Find', 'woochatbot'); ?>
                      <a target="_blank"
                                                       href="https://faq.whatsapp.com/en/android/27585377/?category=5245246">
                      <?php _e('WhatsApp phone number', 'woochatbot'); ?>
                      </a>
                      <?php _e('for settings', 'woochatbot'); ?>
                      .</p>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_whats" type="checkbox"
                                                           name="enable_woo_chatbot_whats" <?php echo(get_option('enable_woo_chatbot_whats') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_whats">
                        <?php _e('Enable WhatsApp', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <?php
                                                    $whatsapp_options = unserialize(get_option('qlcd_woo_chatbot_whats_label'));
                                                    $whatsapp_option = 'qlcd_woo_chatbot_whats_label';
                                                    $whatsapp_text = __('Chat with Us on WhatsApp', 'woochatbot');
                                                    $this->qcld_woo_chatbot_dynamic_multi_option($whatsapp_options, $whatsapp_option, $whatsapp_text);
                                                    ?>
                    </div>
                    <h4 class="qc-opt-title">
                      <?php _e('Show WhatsApp Icon on WoowBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_floating_whats"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_floating_whats" <?php echo(get_option('enable_woo_chatbot_floating_whats') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_floating_whats">
                        <?php _e('Enable to display WhatsApp Floating Icon on WoowBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('WhatsApp Phone Number', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_whats_num"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_whats_num') != '' ? get_option('qlcd_woo_chatbot_whats_num') : ''); ?>"
                                                           placeholder="<?php _e('WhatsApp Phone Number', 'woochatbot'); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-scl-viber" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Show Viber Icon on WoowBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <p>
                      <?php _e('Create', 'woochatbot'); ?>
                      <a href="https://support.viber.com/customer/en/portal/articles/2733413-get-started-with-a-public-account"
                                                       target="_blank">
                      <?php _e('Viber public Account ', 'woochatbot'); ?>
                      </a>
                      <?php _e('for settings', 'woochatbot'); ?>
                      .</p>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_floating_viber"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_floating_viber" <?php echo(get_option('enable_woo_chatbot_floating_viber') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_floating_viber">
                        <?php _e('Enable to display Viber Floating Icon on WoowBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Viber Account', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_viber_acc"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_viber_acc') != '' ? get_option('qlcd_woo_chatbot_viber_acc') : ''); ?>"
                                                           placeholder="<?php _e('Viber Account', 'woochatbot'); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-scl-link" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Show Website Floating Link on WoowBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_floating_link"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_floating_link" <?php echo(get_option('enable_woo_chatbot_floating_link') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_floating_link">
                        <?php _e('Enable to display Website Floating Link on WoowBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Website Url', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_weblink"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_weblink') != '' ? get_option('qlcd_woo_chatbot_weblink') : ''); ?>"
                                                           placeholder="<?php _e('Website Url', 'woochatbot'); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-scl-phone" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Show Phone Icon on WoowBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_floating_phone"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_floating_phone" <?php echo(get_option('enable_woo_chatbot_floating_phone') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_floating_phone">
                        <?php _e('Enable to display Phone Floating Icon on WoowBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Phone Number', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_phone"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_phone') != '' ? get_option('qlcd_woo_chatbot_phone') : ''); ?>"
                                                           placeholder="<?php _e('Phone Number', 'woochatbot'); ?>">
                    </div>
                    <br>
                    <br>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
            <div id="woo-chatbot-scl-livechat" class="tab-pane fade">
              <div class="top-section">
                <div class="row">
                  <div class="col-xs-12" id="woo-chatbot-language-section">
                    <h4 class="qc-opt-title">
                      <?php _e('Show Live Chat Icon on wooBot Message Board Border', 'woochatbot'); ?>
                    </h4>
                    <div class="cxsc-settings-blocks">
                      <input value="1" id="enable_woo_chatbot_floating_livechat"
                                                           type="checkbox"
                                                           name="enable_woo_chatbot_floating_livechat" <?php echo(get_option('enable_woo_chatbot_floating_livechat') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_chatbot_floating_livechat">
                        <?php _e('Enable to display Livechat Floating Icon on wooBot message board border.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Direct Chat Link', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_livechatlink"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_livechatlink') != '' ? get_option('qlcd_woo_chatbot_livechatlink') : ''); ?>"
                                                           placeholder="<?php _e('Direct Chat Link', 'woochatbot'); ?>">
                      <img src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>live-chat.jpg"
                                                         alt=""></div>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Enable Display as 1st Item in Start Menu', 'woochatbot'); ?>
                      </h4>
                      <input value="1" id="enable_woo_custom_intent_livechat_button"
                                                           type="checkbox"
                                                           name="enable_woo_custom_intent_livechat_button" <?php echo(get_option('enable_woo_custom_intent_livechat_button') == 1 ? 'checked' : ''); ?>>
                      <label for="enable_woo_custom_intent_livechat_button">
                        <?php _e('Enable custom intent button for livechat.', 'woochatbot'); ?>
                      </label>
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                      <h4 class="qc-opt-title">
                        <?php _e('Livechat Button Label', 'woochatbot'); ?>
                      </h4>
                      <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_livechat_button_label"
                                                           value="<?php echo(get_option('qlcd_woo_livechat_button_label') != '' ? get_option('qlcd_woo_livechat_button_label') : ''); ?>"
                                                           placeholder="<?php _e('Ex: Live Chat', 'woochatbot'); ?>">
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-xs-12">
                        <h4 class="qc-opt-title">
                          <?php _e(' Upload custom Icon ', 'woochatbot'); ?>
                        </h4>
                        <div class="cxsc-settings-blocks">
                          <input type="hidden" name="woo_custom_icon_livechat"
                                                                   id="woo_custom_icon_livechat"
                                                                   value="<?php echo(get_option('woo_custom_icon_livechat') != '' ? get_option('woo_custom_icon_livechat') : ''); ?>"/>
                          <div id="woo_custom_icon_livechat_src">
                            <?php if (get_option('woo_custom_icon_livechat') != ''): ?>
                            <img src="<?php echo get_option('woo_custom_icon_livechat'); ?>"
                                                                         alt="" width="50" height="50"/>
                            <?php endif; ?>
                          </div>
                          <button type="button"
                                                                    class="woo_custom_icon_livechat button">
                          <?php _e('Upload Icon', 'woochatbot'); ?>
                          </button>
                          <?php if (get_option('woo_custom_icon_livechat') != ''): ?>
                          <button type="button"
                                                                        class="woo_custom_icon_livechat_remove button">
                          <?php _e('Remove Icon', 'woochatbot'); ?>
                          </button>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--                                    top-section--> 
            </div>
          </div>
          <!--                            tab-content--> 
        </section>
        <section id="section-flip-7">
        <div class="woo-chatbot-language-center-summmery">
          <p>
            <?php _e('On Site Retargeting  ', 'woochatbot'); ?>
          </p>
        </div>
        <div class="top-section">
          <div class="row">
            <div class="col-xs-12">
              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group interaction-re-target">
                    <label for="qlcd_woo_chatbot_ret_greet">
                      <?php _e('Hello (When available, we will use user name)', 'woochatbot'); ?>
                    </label>
                    <input type="text" class="form-control qc-opt-dcs-font"
                                                           name="qlcd_woo_chatbot_ret_greet"
                                                           value="<?php echo(get_option('qlcd_woo_chatbot_ret_greet') != '' ? get_option('qlcd_woo_chatbot_ret_greet') : 'Hello'); ?>">
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <p style="margin-top: 35px;">
                      <?php _e('(GREETING + SHOPPER DEMO NAME)', 'woochatbot'); ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="cxsc-settings-blocks">
                <div class="form-group">
                  <h4 class="qc-opt-title">
                    <?php _e('Retargeting Message Container Background Color.', 'woochatbot'); ?>
                  </h4>
                  <input id="woo_chatbot_proactive_bg_color" type="hidden"
                                                       name="woo_chatbot_proactive_bg_color"
                                                       value="<?php echo(get_option('woo_chatbot_proactive_bg_color') != '' ? get_option('woo_chatbot_proactive_bg_color') : '#b9c315ad'); ?>"/>
                </div>
              </div>
              <div class="cxsc-settings-blocks">
                <div class="form-group">
                  <h4 class="qc-opt-title">
                    <?php _e('Retargeting Message Text Color.', 'woochatbot'); ?>
                  </h4>
                  <input id="woo_chatbot_proactive_text_color" type="hidden"
                                                       name="woo_chatbot_proactive_text_color"
                                                       value="<?php echo(get_option('woo_chatbot_proactive_text_color') != '' ? get_option('woo_chatbot_proactive_text_color') : '#c34d15ad'); ?>"/>
                </div>
              </div>
              <div class="cxsc-settings-blocks">
                <h4 class="qc-opt-title">
                  <?php _e('Retargeting Sound', 'woochatbot'); ?>
                </h4>
                <div class="form-group">
                  <input value="1" id="enable_woo_chatbot_ret_sound" type="checkbox"
                                                       name="enable_woo_chatbot_ret_sound" <?php echo(get_option('enable_woo_chatbot_ret_sound') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_ret_sound">
                    <?php _e('Enable to play sound on Exit-Intent, Scroll Opening etc', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <br>
              <div class="cxsc-settings-blocks">
                <h4 class="qc-opt-title">
                  <?php _e('Window Focus Title', 'woochatbot'); ?>
                </h4>
                <div class="form-group">
                  <input value="1" id="enable_woo_chatbot_meta_title" type="checkbox"
                                                       name="enable_woo_chatbot_meta_title" <?php echo(get_option('enable_woo_chatbot_meta_title') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_meta_title">
                    <?php _e('Focus window with a short message appended to page title', 'woochatbot'); ?>
                  </label>
                </div>
                <br>
                <div class="form-group">
                  <label for="qlcd_woo_chatbot_meta_label">
                    <?php _e('Custom Meta Title', 'woochatbot'); ?>
                  </label>
                  <input type="text" class="form-control qc-opt-dcs-font"
                                                       name="qlcd_woo_chatbot_meta_label"
                                                       value="<?php echo(get_option('qlcd_woo_chatbot_meta_label') != '' ? get_option('qlcd_woo_chatbot_meta_label') : '***New Messages'); ?>">
                </div>
              </div>
              <div class="cxsc-settings-blocks">
                <h4 class="qc-opt-title">
                  <?php _e('User Exit Intent', 'woochatbot'); ?>
                  (
                  <?php _e('Show Message when mouse pointer moves out of browser viewport', 'woochatbot'); ?>
                  )</h4>
                <div class="form-group">
                  <input value="1" id="enable_woo_chatbot_exit_intent" type="checkbox"
                                                       name="enable_woo_chatbot_exit_intent" <?php echo(get_option('enable_woo_chatbot_exit_intent') == 1 ? 'checked' : ''); ?>>
                  <label for="enable_woo_chatbot_exit_intent">
                    <?php _e('Enable to show On Exit-Intent Message', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <br>
              <div class="cxsc-settings-blocks">
                <div class="form-group">
                  <input value="1" id="woo_chatbot_exit_intent_once" type="checkbox"
                                                       name="woo_chatbot_exit_intent_once" <?php echo(get_option('woo_chatbot_exit_intent_once') == 1 ? 'checked' : ''); ?>>
                  <label for="woo_chatbot_exit_intent_once">
                    <?php _e('Show only once per visit.', 'woochatbot'); ?>
                  </label>
                </div>
              </div>
              <br>
              <div class="cxsc-settings-blocks" class="woo_chatbot_exit_intent_body">
              <h4 class="qc-opt-title">
                <?php _e('Your Message', 'woochatbot'); ?>
              </h4>
              <?php $exit_intent_settings = array('textarea_name' =>
                                                'woo_chatbot_exit_intent_msg',
                                                'textarea_rows' => 20,
                                                'editor_height' => 100,
                                                'disabled' => 'disabled',
                                                'media_buttons' => false,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                            );
                                            wp_editor(html_entity_decode(stripcslashes(get_option('woo_chatbot_exit_intent_msg'))), 'woo_chatbot_exit_intent_msg', $exit_intent_settings); ?>
            </div>
          </div>
          <div class="col-xs-12"><br>
            <h4 class="qc-opt-title">
              <?php _e('Scroll Down', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="enable_woo_chatbot_scroll_open" type="checkbox"
                                                       name="enable_woo_chatbot_scroll_open" <?php echo(get_option('enable_woo_chatbot_scroll_open') == 1 ? 'checked' : ''); ?>>
                <label for="enable_woo_chatbot_scroll_open">
                  <?php _e('Enable to show message once user scrolls down a page', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <div class="cxsc-settings-blocks"> <span class="qc-opt-dcs-font">
              <?php _e('WoowBot will be shown after scrolling down ', 'woochatbot'); ?>
              </span>
              <input type="number" name="woo_chatbot_scroll_percent"
                                                   value="<?php echo(get_option('woo_chatbot_scroll_percent') != '' ? get_option('woo_chatbot_scroll_percent') : 50); ?>">
              <span class="qc-opt-dcs-font">
              <?php _e('percent', 'woochatbot'); ?>
              </span></div>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="woo_chatbot_scroll_once" type="checkbox"
                                                       name="woo_chatbot_scroll_once" <?php echo(get_option('woo_chatbot_scroll_once') == 1 ? 'checked' : ''); ?>>
                <label for="woo_chatbot_scroll_once">
                  <?php _e('Show only once per visit.', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <br>
            <div class="cxsc-settings-blocks" id="woo_chatbot_scroll_open_body">
              <h4 class="qc-opt-title">
                <?php _e('Your Message', 'woochatbot'); ?>
              </h4>
              <?php $scroll_open_msg_settings = array('textarea_name' =>
                                                'woo_chatbot_scroll_open_msg',
                                                'textarea_rows' => 20,
                                                'editor_height' => 100,
                                                'disabled' => 'disabled',
                                                'media_buttons' => false,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                            );
                                            wp_editor(html_entity_decode(stripcslashes(get_option('woo_chatbot_scroll_open_msg'))), 'woo_chatbot_scroll_open_msg', $scroll_open_msg_settings); ?>
            </div>
            <br>
          </div>
          <div class="col-xs-12">
            <h4 class="qc-opt-title">
              <?php _e('Show Message After "X" Seconds', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="enable_woo_chatbot_auto_open" type="checkbox"
                                                       name="enable_woo_chatbot_auto_open" <?php echo(get_option('enable_woo_chatbot_auto_open') == 1 ? 'checked' : ''); ?>>
                <label for="enable_woo_chatbot_auto_open">
                  <?php _e('Show message after X seconds', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <div class="cxsc-settings-blocks"> <span class="qc-opt-dcs-font">
              <?php _e('WoowBot will be opened automatically after ', 'woochatbot'); ?>
              </span>
              <input type="number" name="woo_chatbot_auto_open_time"
                                                   value="<?php echo(get_option('woo_chatbot_auto_open_time') != '' ? get_option('woo_chatbot_auto_open_time') : 300); ?>">
              <span class="qc-opt-dcs-font">
              <?php _e('seconds', 'woochatbot'); ?>
              </span></div>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="woo_chatbot_auto_open_once" type="checkbox"
                                                       name="woo_chatbot_auto_open_once" <?php echo(get_option('woo_chatbot_auto_open_once') == 1 ? 'checked' : ''); ?>>
                <label for="woo_chatbot_auto_open_once">
                  <?php _e('Show only once per visit.', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <br>
            <div class="cxsc-settings-blocks" id="woo_chatbot_auto_open_body">
              <h4 class="qc-opt-title">
                <?php _e('Your Message', 'woochatbot'); ?>
              </h4>
              <?php $auto_open_msg_settings = array('textarea_name' =>
                                                'woo_chatbot_auto_open_msg',
                                                'textarea_rows' => 20,
                                                'editor_height' => 100,
                                                'disabled' => 'disabled',
                                                'media_buttons' => false,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                            );
                                            wp_editor(html_entity_decode(stripcslashes(get_option('woo_chatbot_auto_open_msg'))), 'woo_chatbot_auto_open_msg', $auto_open_msg_settings); ?>
            </div>
          </div>
          <div class="col-xs-12"><br>
            <h4 class="qc-opt-title">
              <?php _e('Show message to complete checkout (when user has products in the cart)', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="enable_woo_chatbot_ret_user_show" type="checkbox"
                                                       name="enable_woo_chatbot_ret_user_show" <?php echo(get_option('enable_woo_chatbot_ret_user_show') == 1 ? 'checked' : ''); ?>>
                <label for="enable_woo_chatbot_ret_user_show">
                  <?php _e('Show message when the user returns to the site', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <br>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="enable_woo_chatbot_inactive_time_show"
                                                       type="checkbox"
                                                       name="enable_woo_chatbot_inactive_time_show" <?php echo(get_option('enable_woo_chatbot_inactive_time_show') == 1 ? 'checked' : ''); ?>>
                <label for="enable_woo_chatbot_inactive_time_show">
                  <?php _e('Show message when user inactive.', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <div class="cxsc-settings-blocks"> <span class="qc-opt-dcs-font">
              <?php _e('WoowBot will be opened for inactive user after', 'woochatbot'); ?>
              </span>
              <input type="number" name="woo_chatbot_inactive_time"
                                                   value="<?php echo(get_option('woo_chatbot_inactive_time') != '' ? get_option('woo_chatbot_inactive_time') : 10); ?>">
              <span class="qc-opt-dcs-font">
              <?php _e('seconds', 'woochatbot'); ?>
              </span></div>
            <div class="cxsc-settings-blocks">
              <div class="form-group">
                <input value="1" id="woo_chatbot_inactive_once" type="checkbox"
                                                       name="woo_chatbot_inactive_once" <?php echo(get_option('woo_chatbot_inactive_once') == 1 ? 'checked' : ''); ?>>
                <label for="woo_chatbot_inactive_once">
                  <?php _e('Show only once per visit for inactive users.', 'woochatbot'); ?>
                </label>
              </div>
            </div>
            <br>
            <div class="cxsc-settings-blocks" id="woo_chatbot_checkout_open_body">
              <h4 class="qc-opt-title">
                <?php _e('Your Message', 'woochatbot'); ?>
              </h4>
              <?php $checkout_msg_settings = array('textarea_name' =>
                                                'woo_chatbot_checkout_msg',
                                                'textarea_rows' => 20,
                                                'editor_height' => 100,
                                                'disabled' => 'disabled',
                                                'media_buttons' => false,
                                                'tinymce' => array(
                                                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink',)
                                            );
                                            wp_editor(html_entity_decode(stripcslashes(get_option('woo_chatbot_checkout_msg'))), 'woo_chatbot_checkout_msg', $checkout_msg_settings); ?>
            </div>
          </div>
        </div>
      </div>
      <!-- top-section--> 
    </section>
    <section id="section-flip-8">
      <div class="top-section">
        <div class="woo-chatbot-language-center-summmery">
          <p>
            <?php _e('WoowBot will be opened based on the following settings', 'woochatbot'); ?>
          </p>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <h4 class="qc-opt-title">
              <?php _e('Enable Bot Activity Hour', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks" style="padding:0 20px;">
              <input value="1" id="enable_woo_chatbot_opening_hour" type="checkbox"
                                                   name="enable_woo_chatbot_opening_hour" <?php echo(get_option('enable_woo_chatbot_opening_hour') == 1 ? 'checked' : ''); ?>>
              <label for="enable_woo_chatbot_opening_hour">
                <?php _e('If enabled WoowBot will show only during the time schedule you set below. The timezone you set from WordPress general settings will be used.', 'woochatbot'); ?>
              </label>
            </div>
          </div>
        </div>
        <style>
                                    .woo-chatbot-hours-container {
                                        padding: 0px 0 15px 0;
                                        display: flex;
                                        justify-content: space-between;
                                    }

                                    .woo-chatbot-hours {
                                        /*width:75%;*/
                                        display: inline-block;
                                    }

                                    .woo-chatbot-hours input {
                                        display: inline-block;
                                        width: 40%;
                                        padding-right: 10px;
                                        text-align: center;
                                    }

                                    .woo-chatbot-hours-remove {
                                        display: inline-block;
                                        /*width:20%;*/
                                    }
                                </style>
        <div class="row" id="woo-chatbot-hours-wrapper">
          <div class="col-xs-12">
            <h4 class="qc-opt-title">
              <?php _e('WoowBot Bot Activity Hours', 'woochatbot'); ?>
            </h4>
            <?php

                                        if (get_option('woowbot_hours')) {
                                            $woowbot_times = unserialize(get_option('woowbot_hours'));
                                        } else {
                                            $woowbot_times = array();
                                        }
                                        ?>
            <div class="row">
              <div class="col-xs-3">Monday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('monday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="monday"><i class="fa fa-plus"
                                                                                           aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Tuesday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('tuesday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="tuesday"><i class="fa fa-plus"
                                                                                            aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Wednesday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('wednesday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="wednesday"><i class="fa fa-plus"
                                                                                              aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Thursday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('thursday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="thursday"><i class="fa fa-plus"
                                                                                             aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Friday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('friday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="friday"><i class="fa fa-plus"
                                                                                           aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Saturday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('saturday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="saturday"><i class="fa fa-plus"
                                                                                             aria-hidden="true"></i> Add </button>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">Sunday</div>
              <div class="col-xs-4 woo-chatbot-day">
                <?php
                                                $this->woo_chatbot_opening_hours('sunday', $woowbot_times);
                                                ?>
              </div>
              <div class="col-xs-3">
                <button class="btn btn-success btn-sm woo-chatbot-hours-add-btn"
                                                        type="button" data-day="sunday"><i class="fa fa-plus"
                                                                                           aria-hidden="true"></i> Add </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- top-section--> 
    </section>
    <section id="section-flip-9">
      <div class="top-section">
        <div class="woo-chatbot-language-center-summmery">
          <p>
            <?php _e('DialogFlow as Artificial Intelligences Engine for WoowBot', 'woochatbot'); ?>
          </p>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <h4 class="qc-opt-title">
              <?php _e('Enable DialogFlow as AI Engine to Detect Intent', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks">
              <input value="1" id="enable_woo_chatbot_dailogflow" type="checkbox"
                                                   name="enable_woo_chatbot_dailogflow" <?php echo(get_option('enable_woo_chatbot_dailogflow') == 1 ? 'checked' : ''); ?>>
              <label for="enable_woo_chatbot_dailogflow">
                <?php _e('Enable DialogFlow AI Engine to process Natural Language commands from users.', 'woochatbot'); ?>
              </label>
            </div>
          </div>
          <div class="col-xs-12"><br>
            <p>
              <?php _e('You will need an Access Token from DialogFlow. Log in to DialogFlow Console from', 'woochatbot'); ?>
              <a style="text-decoration:underline;color:blue"
                                               href="https://dialogflow.com/" target="_blank">
              <?php _e('Here', 'woochatbot'); ?>
              </a>
              <?php _e('with your gmail account. Create a new agent and copy the Client Access Token from Settings.', 'woochatbot'); ?>
            </p>
            <p><a style="text-decoration:underline;color:blue"
                                              href="<?php echo site_url() ?>/wp-content/plugins/woowbot-woocommerce-chatbot-pro/download/WoowBot.zip"
                                              download>
              <?php _e('Download', 'woochatbot'); ?>
              </a>
              <?php _e('the agent training data and import from DialogFlow->Settings->Export and Import tab. You can add or modify Training phrases under the Intents. But .', 'woochatbot'); ?>
              <b>
              <?php _e('do NOT modify or delete the Intents we have created.', 'woochatbot'); ?>
              </b></p>
          </div>
          <div class="col-xs-12" id="woo-chatbot-dialflow-section">
            <div class="form-group">
              <h4 class="qc-opt-title">
                <?php _e('DialogFlow client Access Token', 'woochatbot'); ?>
              </h4>
              <input type="text" class="form-control qc-opt-dcs-font"
                                                   name="qlcd_woo_chatbot_dialogflow_client_token"
                                                   value="<?php echo(get_option('qlcd_woo_chatbot_dialogflow_client_token') != '' ? get_option('qlcd_woo_chatbot_dialogflow_client_token') : ''); ?>"
                                                   placeholder="<?php _e('DialogFlow Client Access Token', 'woochatbot'); ?>">
            </div>
            <div class="form-group">
              <h4 class="qc-opt-title">
                <?php _e('DialofFlow Defualt reply', 'woochatbot'); ?>
              </h4>
              <input type="text" class="form-control qc-opt-dcs-font"
                                                   name="qlcd_woo_chatbot_dialogflow_defualt_reply"
                                                   value="<?php echo(get_option('qlcd_woo_chatbot_dialogflow_defualt_reply') != '' ? get_option('qlcd_woo_chatbot_dialogflow_defualt_reply') : 'Sorry, I did not understand you. You may browse'); ?>"
                                                   placeholder="<?php _e('DialogFlow defualt reply', 'woochatbot'); ?>">
            </div>
            <div class="form-group">
              <h4 class="qc-opt-title">
                <?php _e('Allow custom intent and reponse from DialogFlow', 'woochatbot'); ?>
              </h4>
              <input value="1" id="enable_woo_chatbot_custom_intent" type="checkbox"
                                                   name="enable_woo_chatbot_custom_intent" <?php echo(get_option('enable_woo_chatbot_custom_intent') == 1 ? 'checked' : ''); ?>>
              <label for="enable_woo_chatbot_custom_intent">
                <?php _e('Enable to get Intents and Responses from the DialogFlow for the custom intents you create.', 'woochatbot'); ?>
              </label>
            </div>
            <br>
            <div class="form-group" style="padding: 15px !important;">
              <h4 class="qc-opt-title">
                <?php _e('Enable rich reponse from DialogFlow using Facebook Messenger', 'woochatbot'); ?>
              </h4>
              <input value="1" id="enable_woo_chatbot_rich_response" type="checkbox"
                                                   name="enable_woo_chatbot_rich_response" <?php echo(get_option('enable_woo_chatbot_rich_response') == 1 ? 'checked' : ''); ?>>
              <label for="enable_woo_chatbot_rich_response">
                <?php _e(' If you enable this option you can create Responses for Facebook messenger for your intents which will support Images, Cards etc.', 'woochatbot'); ?>
              </label>
              <br>
            </div>
            <br>
            <hr>
            <br>
            <div class="form-group" style="padding: 15px !important;">
              <h4 class="qc-opt-title">
                <?php _e('Enable Advanced Step by Step Search', 'woochatbot'); ?>
              </h4>
              <input value="1" id="enable_woo_chatbot_custom_search" type="checkbox"
                                                   name="enable_woo_chatbot_custom_search" <?php echo(get_option('enable_woo_chatbot_custom_search') == 1 ? 'checked' : ''); ?>>
              <label for="enable_woo_chatbot_custom_search">
                <?php _e("You will Need to Create a Custom Question Tree in DialogFlow to Collect Search Criteria from User. Check the documentation's AI section for more details.", 'woochatbot'); ?>
              </label>
            </div>
            <br>
            <br>
            <div class="block-intent-inner" id="block-intent-inner">
              <h4 class="qc-opt-title">
                <?php _e('Custom Intent Name & Label', 'woochatbot'); ?>
              </h4>
              <?php
                                            $intent_names = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('custom_intent_names')));
                                            $intent_labels = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('custom_intent_labels')));
                                            $intent_keywords = $this->qcld_woo_chatbot_str_replace(unserialize(get_option('custom_intent_kewords')));
                                            if (count($intent_names) >= 1) {
                                                $indent_counter = 0;
                                                foreach (array_combine($intent_names, $intent_labels) as $name => $label) {
                                                    ?>
              <div class="row">
                <div class="col-xs-12">
                  <button type="button"
                                                                    class="btn btn-danger btn-sm woo-chatbot-remove-custom-intent pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Label (Will also show as Button Name in the fallback)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                       name="custom_intent_labels[]"
                                                                       placeholder="<?php _e('Intent Label', 'woochatbot'); ?>"
                                                                       value="<?php echo $label ?>">
                  </div>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Name (Must match Exactly as the Intent Name you will create in DialogFlow)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                       name="custom_intent_names[]"
                                                                       placeholder="<?php _e('Intent Name ', 'woochatbot'); ?>"
                                                                       value="<?php echo $name ?>">
                  </div>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Keyword (this is the keyword user must type to get into Step by Step search. You should add a prompt in the greeting to let your users know about the keyword.)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                       name="custom_intent_kewords[]"
                                                                       placeholder="<?php _e('Intent System Keyword ', 'woochatbot'); ?>"
                                                                       value="<?php if (isset($intent_keywords[$indent_counter])) {
                                                                           echo $intent_keywords[$indent_counter];
                                                                       } ?>">
                  </div>
                </div>
              </div>
              <?php
                                                    $indent_counter++;
                                                }
                                                //}
                                            } else {
                                                ?>
              <div class="row">
                <div class="col-xs-12">
                  <button type="button"
                                                                class="btn btn-danger btn-sm woo-chatbot-remove-custom-intent pull-right"> <i class="fa fa-times" aria-hidden="true"></i></button>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Label (Will also show as Button Name in the fallback)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                   name="custom_intent_labels[]"
                                                                   placeholder="<?php _e('Intent Label ', 'woochatbot'); ?>">
                  </div>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Name (Must match Exactly as the Intent Name you will create in DialogFlow)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                   name="custom_intent_names[]"
                                                                   placeholder="<?php _e('Intent Name ', 'woochatbot'); ?>">
                  </div>
                  <div class="cxsc-settings-blocks">
                    <p class="qc-opt-dcs-font">
                      <?php _e('Intent Keyword (this is the keyword user must type to get into Step by Step search. You should add a prompt in the greeting to let your users know about the keyword.)', 'woochatbot'); ?>
                    </p>
                    <input type="text" class="form-control"
                                                                   name="custom_intent_kewords[]"
                                                                   placeholder="<?php _e('Intent System Keyword', 'woochatbot'); ?>">
                  </div>
                </div>
              </div>
              <?php
                                            }
                                            ?>
            </div>
            <div class="row">
              <div class="col-sm-6 text-left"></div>
              <div class="col-sm-6 text-right">
                <button class="btn btn-success btn-sm" type="button"
                                                        id="add-more-custom-intent"><i
                                                            class="fa fa-plus" aria-hidden="true"></i>
                <?php _e('Add More Custom Intent', 'woochatbot'); ?>
                </button>
              </div>
            </div>
            <br>
          </div>
        </div>
      </div>
      <!-- top-section--> 
    </section>
    <!--<section id="section-flip-11">
      <div class="woo-chatbot-language-center-summmery">
        <p>
          <?php _e('Enable the Mobile App feature ONLY if you bought ', 'woochatbot'); ?>
          <a target="_blank"
                                       href="https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/#app">
          <?php _e(' the Mobile App Addon.', 'woochatbot'); ?>
          </a> 
      </div>
      <div class="top-section">
        <div class="row">
          <div class="col-xs-12">
            <h4 class="qc-opt-title">
              <?php _e('Mobile App Pages', 'woochatbot'); ?>
            </h4>
            <div class="cxsc-settings-blocks">
              <input value="1" id="woo_chatbot_app_pages" type="checkbox"
                                                   name="woo_chatbot_app_pages" <?php echo(get_option('woo_chatbot_app_pages') == 1 ? 'checked' : ''); ?>>
              <label for="woo_chatbot_app_pages">
                <?php _e('Create pages for WoowBot Mobile App', 'woochatbot'); ?>
              </label>
            </div>
            <br>
            <p class="qc-opt-title-font">
              <?php _e('Following pages will be created to use in', 'woochatbot'); ?>
              <strong>
              <?php _e('WoowBot', 'woochatbot'); ?>
              </strong>
              <?php _e(' Android or IOS Mobile App', 'woochatbot'); ?>
              .</p>
            <ol>
              <li>
                <?php _e('WoowBot Mobile App', 'woochatbot'); ?>
              </li>
              <li>
                <?php _e('WoowBot App Checkout', 'woochatbot'); ?>
              </li>
              <li>
                <?php _e('WoowBot App Order Thank You', 'woochatbot'); ?>
              </li>
            </ol>
            <p class="qc-opt-title-font">
              <?php _e('Follow the', 'woochatbot'); ?>
              <strong>
              <?php _e('Documentation', 'woochatbot'); ?>
              </strong>
              <?php _e('to build & publish a Mobile Application (Android or IOS ) for your store using any of', 'woochatbot'); ?>
            </p>
            <ol>
              <li>
                <?php _e('WoowBot Ionic FrameWork package', 'woochatbot'); ?>
              </li>
              <li>
                <?php _e('WoowBot PhoneGap package', 'woochatbot'); ?>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </section>-->
    <section id="section-flip-12">
      <div class="top-section">
        <div class="row">
          <div class="col-xs-12">
            <p >
              <?php _e('Use [WoowBot-page] shortcode in order to embed woowbot in a wordpress page.', 'woochatbot'); ?>
            </p>
            <h4 class="qc-opt-dcs">
              <?php _e('Iframe Integration.', 'woochatbot'); ?>
            </h4>
            <span style="font-size:15px; font-weight:bold; color:#F00; display:block">
              <?php _e('[This feature is still experimental.]', 'woochatbot'); ?>
            </span>
            <p>
              <?php _e('Copy the below code & add to any page before closing the body tag. Please note that some features like retargeting will not work on embedded pages.', 'woochatbot'); ?>
            </p>
            <?php
                                        $woo_chatbot_css_url = plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/common-style.css');
                                        $page = get_page_by_title('WoowBot Mobile App');
                                        $woo_chatbot_custom_icon_path = '';
                                        if (get_option('woo_chatbot_icon') == "custom.png") {
                                            $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_icon_path');
                                        } else if (get_option('woo_chatbot_icon') != "custom.png") {
                                            $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_icon');
                                        } else {
                                            $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom.png';
                                        }

                                        ?>
            <textarea
                                                style="width:100%;height:300px;font-size: 14px;"><?php echo htmlentities('<link rel="stylesheet" href="' . $woo_chatbot_css_url . '"/><div id="woo-chatbot-chat-container" class=" " style="right: 50px; bottom: 50px;"><div id="woo-chatbot-ball-container" class="woo-chatbot-template-01" style="display:none;"><div class="woo-chatbot-container"><div style="border: 1px solid #e0e0e0;" id="woo-chatbot-board-container" class="woo-chatbot-board-container active-chat-board"><div class="woo-chatbot-header" style="background: #1f8ceb;color: #fff;border-radius: unset;"><h3>Welcome to Demo Site</h3></div><div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto;">
                                         <div class="woo-chatbot-ball-inner woo-chatbot-content" style="padding:0px !important;height:570px ; overflow: hidden; width: auto;"><iframe style="border:none;" src="' . $page->guid . '" scrolling="no" width="100%" height="570"></iframe></div></div></div></div></div><div id="woo-chatbot-ball" class=""><div class="woo-chatbot-ball woo-chatbot-animation-active"><img src="' . $woo_chatbot_custom_icon_path . '" alt="wooChatIcon"></div></div></div><script>document.getElementById("woo-chatbot-ball").addEventListener("click", displayDate);function displayDate(){var x=document.getElementById("woo-chatbot-ball-container");if (x.style.display==="none"){x.style.display="block";}else{x.style.display="none";}}</script>'); ?>
                                        </textarea>
          </div>
        </div>
        <!--                                row--> 
      </div>
    </section>
    <section id="section-flip-13">
      <div class="top-section">
        <div class="row">
          <div class="col-xs-12">
            <h4 class="qc-opt-dcs">
              <?php _e('You can paste or write your custom css here.', 'woochatbot'); ?>
            </h4>
            <textarea name="woo_chatbot_custom_css"
                                                  class="form-control woo-chatbot-custom-css"
                                                  cols="10"
                                                  rows="16"><?php echo get_option('woo_chatbot_custom_css'); ?></textarea>
          </div>
        </div>
        <!--                                row--> 
      </div>
    </section>
    <section id="section-flip-14">
      <div class="top-section">
        <div class="row">
          <div class="col-xs-12">
            <h4 class="qc-opt-dcs">
              <?php _e('WoowBot WooCommerce Chat Bot Pro support.', 'woochatbot'); ?>
            </h4>
            <?php require_once( 'qc-support-promo-page/class-qc-support-promo-page-html.php' ); ?>
            <!--<iframe src="https://www.quantumcloud.com/documentations/woowbot/doc.html" width="100%" height="800" scrolling="auto"></iframe>-->
          </div>
        </div>
        <!--                                row--> 
      </div>
    </section>
  </div>
  <!-- /content -->
  </div>
  <!-- /woo-chatbot-tabs -->
  <footer class="woo-chatbot-admin-footer">
    <div class="row">
      <div class="text-left col-sm-3 col-sm-offset-3">
        <input type="button" class="btn btn-warning submit-button"
                                   id="qcld-woo-chatbot-reset-option"
                                   value="<?php _e('Reset all options to Default', 'woochatbot'); ?>"/>
      </div>
      <div class="text-right col-sm-6">
        <input type="submit" class="btn btn-primary submit-button" name="submit"
                                   id="submit" value="<?php _e('Save Settings', 'woochatbot'); ?>"/>
      </div>
    </div>
    <!--                    row--> 
  </footer>
  </section>
  </div>
  <?php wp_nonce_field('woo_chatbot'); ?>
</form>
