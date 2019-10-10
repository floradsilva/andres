jQuery(document).ready(function () {
// Settings page script actions
jQuery('.wdm-button-color-field').wpColorPicker();
jQuery('#button_text_color').wpColorPicker();
jQuery('#button_border_color').wpColorPicker();
jQuery('#dialog_color').wpColorPicker();
jQuery('#dialog_text_color').wpColorPicker();
jQuery('#dialog_product_color').wpColorPicker();

    // Trigger WooCommerce Tooltips. This is used to trigger tooltips added by function \wc_help_tip
    var tiptip_args = {
        'attribute': 'data-tip',
        'fadeIn': 50,
        'fadeOut': 50,
        'delay': 200
    };
    jQuery('.tips, .help_tip, .woocommerce-help-tip').tipTip(tiptip_args);

    jQuery('#manual_css:checked').live("click", function () {
        jQuery('#Other_Settings').css('display', 'block');
    });

    jQuery('#theme_css:checked').live("click", function () {
        jQuery('#Other_Settings').css('display', 'none');
    });

    jQuery('#default:checked').live("click", function () {
        jQuery('#default_Settings').css('display', 'block');
        jQuery('#custom_Settings').css('display', 'none');
    });

    jQuery('#custom:checked').live("click", function () {
        jQuery('#custom_Settings').css('display', 'block');
        jQuery('#default_Settings').css('display', 'none');
    });

    var cust_email_template = jQuery('#custom_email_template').is(":checked");

    if ( cust_email_template ) {
        jQuery('#wdm_custom_email_template').css('display', 'block');
    }

    jQuery(document).on("click", '#custom_email_template:checked', function () {
        jQuery('#wdm_custom_email_template').css('display', 'block');
    });

    jQuery(document).on("click", '#default_email_template:checked', function () {
        jQuery('#wdm_custom_email_template').css('display', 'none');
    });

//Migration no longer in use
jQuery('#btnMigrate').click(function () {
    jQuery(this).attr('disabled', 'disabled');
    jQuery('.wdm-migrate-txt').animate({
        opacity: 0
    }, 400, function () {
        jQuery(this).css('display', 'none');
    });
    jQuery('.wdm-migrate-loader-wrap').animate({
        opacity: 0
    }, 400, function () {
        jQuery(this).css('display', 'inline-block');
        req_migration = {
            action: 'migrateScript',
            'security': jQuery('#migratenonce').val(),
        };

        jQuery.post(data.ajax_admin_url, req_migration, function ( response ) {
            if ( response == 'SECURITY_ISSUE' ) {
                alert(data.could_not_migrate_enquiries);
                jQuery('.wdm-migrate-txt').animate({
                    opacity: 1
                }, 400, function () {
                 jQuery(this).css('display', 'initial');
                 jQuery('#btnMigrate').removeAttr('disabled');
             });
                jQuery('.wdm-migrate-loader-wrap').animate({
                    opacity: 1
                }, 400, function () {
                  jQuery(this).css('display', 'none');
              });
                return false;
            }
            jQuery('.wdm-migrate-txt').animate({
                opacity: 1
            }, 400, function () {
                jQuery(this).css('display', 'initial');
                jQuery('#btnMigrate').removeAttr('disabled');
            });
            jQuery('.wdm-migrate-loader-wrap').animate({
                opacity: 1
            }, 400, function () {
                jQuery(this).css('display', 'none');
            });
        });
    });
});
    // settings tab display
    jQuery('#tab-container').easytabs();
    $selectedTab = jQuery('#tab-container .etabs li.active').find('.active').closest('a').attr("href");
    $url = jQuery('input[name="_wp_http_referer"]').val();
    if ($url.indexOf("#wdm_") !== -1) {
      $url = $url.substr(0, $url.indexOf("#wdm_"));
  }
  if($selectedTab == '#wdm_other_extensions') {
    jQuery('#wdm_ask_button').hide();
} else {
    jQuery('#wdm_ask_button').show();
}
  // reference to selected settings tab
  jQuery('input[name="_wp_http_referer"]').val($url+$selectedTab)
  jQuery('#tab-container')
  .bind('easytabs:after', function () {

      $selectedTab = jQuery(this).find('.active').closest('a').attr("href");
        // jQuery['input[name="_wp_http_referer"']
        $url = jQuery('input[name="_wp_http_referer"]').val();
        if ($url.indexOf("#wdm_") !== -1) {
            $url = $url.substr(0, $url.indexOf("#wdm_"));
        }
        jQuery('input[name="_wp_http_referer"]').val($url+$selectedTab)

        if($selectedTab == '#wdm_other_extensions') {
            jQuery('#wdm_ask_button').hide();
        } else {
            jQuery('#wdm_ask_button').show();
        }
    });

    // When any checkbox on the settinga page is changed, find out next hidden field and set it to 1 or 0
    jQuery('.wdm_wpi_checkbox').change(function () {
        var nextHiddenField = jQuery(this).next("input[type='hidden']");
        if ( jQuery(this).is(':checked') ) {
         nextHiddenField.val('1');
     } else {
         nextHiddenField.val('0');
     }
 });

    //Show or hide telephone number related fields on page load
    var phNumber = jQuery('#enable_telephone_no_txtbox').is(':checked');
    if ( phNumber ) {
        jQuery('.toggle').show();
    } else {
        jQuery('.toggle').hide()
    }

    //Show or hide telephone number related fields on click
    jQuery('#enable_telephone_no_txtbox').click(function () {
        if ( jQuery(this).is(':checked') ) {
         jQuery('.toggle').show();
     } else {
         jQuery('.toggle').hide()
     }
 });

    //Show or hide date related fields on page load
    var dateField = jQuery('#enable_date_field').is(':checked');
    if ( dateField ) {
        jQuery('.toggle-date').show();
    } else {
        jQuery('.toggle-date').hide()
    }

    //Show or hide date related fields on click
    jQuery('#enable_date_field').click(function () {
        if ( jQuery(this).is(':checked') ) {
         jQuery('.toggle-date').show();
     } else {
         jQuery('.toggle-date').hide()
     }
 });

    //Show or hide attach related fields on page load
    var attachField = jQuery('#enable_attach_field').is(':checked');
    if ( attachField ) {
        jQuery('.toggle-attach').show();
    } else {
        jQuery('.toggle-attach').hide()
    }

    //Show or hide Attach related fields on click
    jQuery('#enable_attach_field').click(function () {
        if ( jQuery(this).is(':checked') ) {
         jQuery('.toggle-attach').show();
     } else {
         jQuery('.toggle-attach').hide()
     }
 });

     //Show or hide captcha related fields on page load
     var attachField = jQuery('#enable_google_captcha').is(':checked');
     if ( attachField ) {
        jQuery('.toggle-captcha').show();
    } else {
        jQuery('.toggle-captcha').hide()
    }

    //Show or hide captcha related fields on click
    jQuery('#enable_google_captcha').click(function () {
        if ( jQuery(this).is(':checked') ) {
         jQuery('.toggle-captcha').show();
     } else {
         jQuery('.toggle-captcha').hide()
     }
 });

    //Show or hide PDF related fields on page load
    var pdfOptions = jQuery('#enable-disable-pdf').is(':checked');
    if ( pdfOptions ) {
        jQuery('.toggle-pdf').show();
    } else {
        jQuery('.toggle-pdf').hide()
    }

    //Show or hide Attach related fields on click
    jQuery('#enable-disable-pdf').click(function () {
        if ( jQuery(this).is(':checked') ) {
           jQuery('.toggle-pdf').show();
       } else {
           jQuery('.toggle-pdf').hide()
       }
   });


    // Enable quotation system checkbox
    var quoteVisibilityOnFirstLoad = jQuery('#quote-enable-disable').is(':checked');
    
    var previousStatus = quoteVisibilityOnFirstLoad;
    //If default value is not set, set it to 'yes'
    if ( quoteVisibilityOnFirstLoad ) {
        quoteVisibilityOnFirstLoad = 'yes';
        jQuery('#quote-settings').hide();
    } else {
        jQuery('#quote-settings').show();
    }

        // Enable quotation system checkbox when changed
        jQuery("#quote-enable-disable").change(function () {
            var newval = jQuery("#quote-enable-disable").is(':checked');
            if ( !newval ) {
                jQuery('#quote-settings').show();
            } else {
                jQuery('#quote-settings').hide();
            }
        });

// Enable multiproduct enquiry checkbox
var val = jQuery("#enable-multiproduct").is(':checked');
if ( val ) {
    jQuery('.quote_cart').show();
} else {
    jQuery('.quote_cart').hide();
}

// Enable multiproduct enquiry checkbox when changed
jQuery("#enable-multiproduct").change(function () {
    var newval = jQuery("#enable-multiproduct").is(':checked');
    if ( newval ) {
        jQuery('.quote_cart').show();
    } else {
        jQuery('.quote_cart').hide();
    }
    manipulateEnquiryMailAndCartOptions();
});


// Enable/ Disable 'Expected Price or Remarks column Label' setting
jQuery("#disable_remarks_col").change(function () {
    manipulateEnquiryMailAndCartOptions();
});

// Enable Specify Icon Color checkbox.
var val = jQuery("#enable-manual-ec-icon-color").is(':checked');
if (val) {
    jQuery('.manual-ec-icon-color-depd').show();
} else {
    jQuery('.manual-ec-icon-color-depd').hide();
}

// Enable Specify Icon Color checkbox when changed.
jQuery("#enable-manual-ec-icon-color").change(function () {
    var newval = jQuery("#enable-manual-ec-icon-color").is(':checked');
    if (newval) {
        jQuery('.manual-ec-icon-color-depd').show();
    } else {
        jQuery('.manual-ec-icon-color-depd').hide();
    }
});

//Enable terms and conditions checkbox
var val = jQuery("#enable_terms_conditions").is(':checked');
if ( val ) {
    jQuery('.enquiry-privacy-policy').show();
} else {
    jQuery('.enquiry-privacy-policy').hide();
}

//Enable terms and conditions checkbox when changed
jQuery("#enable_terms_conditions").change(function () {
    var newval = jQuery("#enable_terms_conditions").is(':checked');
    if ( newval ) {
        jQuery('.enquiry-privacy-policy').show();
    } else {
        jQuery('.enquiry-privacy-policy').hide();
    }
});

//Enable Cookie Consent checkbox
var val = jQuery("#enable_cookie_consent").is(':checked');
if ( val ) {
    jQuery('.cookie-consent-text').show();
} else {
    jQuery('.cookie-consent-text').hide();
}

//Enable Cookie Consent checkbox when changed
jQuery("#enable_cookie_consent").change(function () {
    var newval = jQuery("#enable_cookie_consent").is(':checked');
    if ( newval ) {
        jQuery('.cookie-consent-text').show();
    } else {
        jQuery('.cookie-consent-text').hide();
    }
});

// Product form save chnages posts the data
jQuery("#ask_product_form").submit(function ( e ) {

    error = 0;
    em_regex = /^(\s)*(([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+)((\s)*,(\s)*(([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+)(\s)*(,)*)*(\s)*$/;
    email = jQuery('#wdm_user_email').val();
        // Kept in comments Just for Future reference.
        // if ( email == '' ) {
        //     jQuery('.email_error').text(data.name_req);
        //     error = 1;
        // } else 
        if (email != '' && !em_regex.test(email) ) {
            jQuery('.email_error').text(data.valid_name);
            error = 1;
        } else {
            jQuery('.email_error').text('');
        }
        if ( error == 1 ) {
            return false;
        }

    });

    // jQuery('.anonymize-form-fields-accordion').accordion({
    //     collapsible: true
    // });

    var acc = document.getElementsByClassName("wdm-accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
    // Open first accordion
    acc[0].click();


    function manipulateEnquiryMailAndCartOptions()
    {
        let isMPEEnabled = jQuery("#enable-multiproduct").is(':checked');
        
        if (isMPEEnabled) {
            let isRemarksColEnabled = ! jQuery("#disable_remarks_col").is(':checked');

            jQuery('.enquiry-mail-opt').hide();
            jQuery('.enquiry-mail-cart-cust-opt').show();

            jQuery('.expected_price_remarks_enable_wrapper').show();
            if (isRemarksColEnabled) {
                jQuery('.expected_price_remarks_label_wrapper').show();
                jQuery('.expected_price_remarks_col_phdr_wrapper').show();
            } else {
                jQuery('.expected_price_remarks_label_wrapper').hide();
                jQuery('.expected_price_remarks_col_phdr_wrapper').hide();
            }
        } else {
            jQuery('.enquiry-mail-cart-cust-opt').hide();
            jQuery('.enquiry-mail-opt').show();

            jQuery('.expected_price_remarks_enable_wrapper').hide();
            jQuery('.expected_price_remarks_label_wrapper').hide();
            jQuery('.expected_price_remarks_col_phdr_wrapper').hide();
        }
    }

    manipulateEnquiryMailAndCartOptions();
});

