<?php

namespace Includes\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

WC()->mailer();
/**
 * This class is used to send mail to customer.
 * Mail includes pdf and the unique link by which customer can approve or reject quote.
 * @static instance Object of class
 * $mailData array Mail data 
 */
class SendQuoteMail extends \WC_Email
{

    private static $instance;
    private $mailData;
    /**
    * Gets Static instance of the Quote Email
    * @param array $mailPostData Data associated with quote mail
    */
    public static function getInstance($mailPostData)
    {
        if (null === static::$instance) {
            static::$instance = new static($mailPostData);
        }

        return static::$instance;
    }
    /**
    * Prepares the content for mail and send the mail.
     * @param array $mailPostData Data associated with quote mail
    */

    public function __construct($mailPostData)
    {
        $this->mailData = $mailPostData;
        add_filter('woocommerce_email_styles', array($this,'addCSS') , 10, 1);
        if ($this->mailData['subject'] == '') {
            $this->mailData['subject'] = __('Quotation', QUOTEUP_TEXT_DOMAIN);
        }

        $subject = wp_kses($this->mailData['subject'], array());
        $subject = stripcslashes($subject);

        $this->email_type = 'text/html';

        $this->heading = __( 'WISDMLABS', QUOTEUP_TEXT_DOMAIN );
        $this->subject = $subject;

        $this->template_html  = 'emails/quote.php';

        // Triggers for this email
        add_action( 'quoteup_send_quote_email', array( $this, 'trigger' ), 15, 1 );

        parent::__construct();

        $this->recipient = filter_var($this->mailData['email'], FILTER_SANITIZE_EMAIL);// E-mail of customer
    }

    /**
    * This returns the CSS for pdf 
    * @return string $css css for pdf
    */
    public function addCSS($css)
    {
        $stylesheet = file_get_contents(QUOTEUP_PLUGIN_DIR.'/css/admin/pdf-generation.css');
        return $css.$stylesheet;
    }

    /**
    * Sends the email with the data to specific recipient.
    */
    public function trigger()
    {
        $this->send($this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $attachments = $this->get_attachments());

        if (file_exists($attachments[0])) {
            unlink($attachments[0]);
        }
    }
    /**
    * Gets the contet in HTML for the mail to be sent.
    * Gets the Products for the enquiry from database.
    * Generates the PDF.
    * Updates History table for changing it to Enquiry sent.
    * @return HTML $message content of email
    */
    public function get_content_html()
    {
        global $wpdb;
        $language = isset($this->mailData['language']) ? $this->mailData['language'] : 'all';
        $enquiry_id = filter_var($this->mailData['enquiry_id'], FILTER_SANITIZE_NUMBER_INT);
        $original_message = wp_kses($this->mailData['message'], array());
        $table_name = getEnquiryDetailsTable();
        $quote_table_name = getQuotationProductsTable();
        $sql2 = $wpdb->prepare("SELECT show_price FROM $quote_table_name WHERE enquiry_id=%d", $enquiry_id);
        $show_pricePDF = $wpdb->get_var($sql2);
        $show_pricePDF = $show_pricePDF == 'yes' ? 1 : 0;
        $PDFData = array(
            'enquiry_id' => $enquiry_id,
            'show-price' => $show_pricePDF,
            'language' => $language,
            'quote_message' => $original_message,
            'source' => 'email',
            );
        $mailQuote = QuoteupGeneratePdf::generatePdf($PDFData);
        $message = $mailQuote;

        //update enquiry details table
        $wpdb->update(
            $table_name,
            array(
                'order_id' => null,
            ),
            array(
                'enquiry_id' => $enquiry_id,
            )
        );

        //update History Table
        global $quoteupManageHistory;
        $quoteupManageHistory->addQuoteHistory($enquiry_id, $original_message, 'Sent');

        if ($this->mailData['source'] == 'ajaxCall') {
            _e('Mail Sent', QUOTEUP_TEXT_DOMAIN);            
        }


        return $message;
    }
    /**
    * Gets the Header for email
    * @return string $header Email header
    */
    public function get_headers()
    {
        return apply_filters('quoteup_quote_mail_header', '' ,$this->mailData);
    }
    /**
    * Gets the generated pdf from the uploads directory to set in the attachments for mail.
    * @return array $attachments 
    */
    public function get_attachments()
    {
        global $wpdb;
        $enquiry_id = filter_var($this->mailData['enquiry_id'], FILTER_SANITIZE_NUMBER_INT);
        $upload_dir = wp_upload_dir();
        $form_data = quoteupSettings();
        $table_name = getEnquiryDetailsTable();
        $sql = $wpdb->prepare("SELECT name,enquiry_hash FROM $table_name WHERE enquiry_id=%d", $enquiry_id);
        $hash = $wpdb->get_row($sql, ARRAY_A);
        //Copy pdf to make its name Quotation.pdf
        if (!file_exists($upload_dir['basedir'].'/QuoteUp_PDF/'.$enquiry_id.'.pdf')) {
            return array();
        }
        $attachments = "";
        if ($form_data['enable_disable_quote_pdf']) {
            copy($upload_dir['basedir'].'/QuoteUp_PDF/'.$enquiry_id.'.pdf', $upload_dir['basedir'].'/QuoteUp_PDF/Quotation '.$hash['name'].'.pdf');
            $attachments = array($upload_dir['basedir'].'/QuoteUp_PDF/Quotation '.$hash['name'].'.pdf');
        }
        return $attachments;
    }
    /**
     * This function is used to send mail to customer.
     */
    public static function sendMailAjaxCallback()
    {
        $mailPostData = $_POST;
        self::sendMail($mailPostData);
        die;
    }
    /**
    * Send the Email 
    * Checks the language set by user.
    * Gets the instance for sending quote mail.
    * @param array $mailPostData Details for mail from the form.
    */
    public static function sendMail($mailPostData)
    {
        $language = isset($mailPostData['language']) ? $mailPostData['language'] : 'all';
        do_action('wdm_before_send_mail', $language);
        $emailObject = self::getInstance($mailPostData);
        do_action('quoteup_send_quote_email', $emailObject);
    }
}
