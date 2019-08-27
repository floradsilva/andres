=== Product Enquiry PRO for WooCommerce ===
Contributors: WisdmLabs
Tags: Enquiry for WooCommerce, Product Enquiry WooCommerce, WooCommerce Add-On
Requires at least: 4.4.0
Tested up to: 5.2.2
Woocommerce tested up to : 3.7.0
Stable tag: 6.3.4
License: GPL2

The Product Enquiry Pro for WooCommerce adds a 'Make an enquiry' or a 'Request a Quote' button to every WooCommerce Product Page, allowing a potential customer to make an enquiry or request a quote for one or multiple products. You can view these enquiries and quote requests made right in your dashboard, and filter enquiry and quotation records to analyse product demands.

== Description ==
1. Adds an Enquiry or Request a Quote Button to every WooCommerce Product Page
2. Enquiry and Quote Request Emails are sent to e-Store Owner and the Product Owner
3. Visitor can Choose to Send a Copy of the Enquiry Email or Quote Request to himself
4. Auto-Generate Quote PDF by admin
5. Show/hide price and purchase button for one or multiple products.
6. Style the buttons and Dialog
7. Responsive Enquiry and Quote Request Dialog
8. View Enquiries and Quote Requests right in your Dashboard
9. Filter and Export Enquiry and Quotation Records to Analyse Product Demands
10. Option to Input a potential Customer's Phone Number
11. Localization Ready
12. Remember's Customer's Name and Email id for those who make Regular Enquiries
13. Hooks provided to Customize the Enquiry Form
14. Mutiple Product Enquiries and Quote requests on a Single Page
15. Feature for admin to reply from the WordPress dashboard.

== Installation ==
Important: This plugin is a premium extension for the WooCommerce plugin. You must have the WooCommerce plugin already installed.

Please Note: 

1. Upon purchasing the Product Enquiry Pro (Also known as QuoteUp) for WooCommerce, an email will be sent to the registered email id, with the download link for the plugin and a purchase receipt id. Download the Product Enquiry Pro plugin using the download link.

2. Go to Plugin-> Add New menu in your dashboard and click on the Upload' tab. Choose the 'product-enquiry-pro.zip' file to be uploaded and click on Install Now.

3. After the plugin has installed successfully, click on the Activate Plugin link or activate the Product Enquiry Pro plugin from your Plugins page.

4. A Product Enquiry Pro License sub-menu will be created under Plugins menu in your dashboard. Click on this menu and enter your purchased product's license key. Click on Activate License. If license in valid, an 'Active' status message will be displayed, else 'Not Active' will be displayed.

5. Upon entering a valid license key, and activating the license, a Product Enquiry menu will be created in your dashboard. Refer to the detailed User Guide (http://wisdmlabs.com/woocommerce-product-enquiry-pro/#user_guide) for additional settings.


== Frequently Asked Questions ==
1. How do I show the telephone number field in the Enquiry Dialog?
A. Go to the Product Enquiry settings in the dashboard. Under ‘Form Options’, check the option 'Enable Telephone Number Field' to show a field to input the telephone number.

2. How do I style the Enquiry Dialog?
A. The ‘Product Enquiry’ settings menu in your dashboard, provides you options to set the colors for the Enquiry Dialog and the Enquiry Button, under 'Styling Options'. You can change the dialog background color, text color, button colors, and even set the product name to a different color.

3. Can I set multiple email ids in the Recipient’s field?
A. The ‘Recipient’s Email’ is set to the admin email id by default. You can replace this id, but you cannot add multiple ids.

4. How can I filter the enquiry records in the Dashboard?
A. In the ‘Enquiry Details’ menu, there is a search box ‘Search’. Enter a value in this box, to filter the enquiry records based on that value. For example, if you want to view the records for your product named PN781, you would have to enter PN781 in the search box.

5. How to export the enquiry records?
A. In the ‘Enquiry Details’ menu, there is an ‘Export As CSV’ button. Use this button to export the enquiry records. By default all records will be exported. To export selective records, filter the records using the ‘Search’ box, or check individual records to be exported, using the checkbox, and then click on the ‘Export As CSV’ button.

6. How can I export records for a particular product?
A. In the ‘Enquiry Details’ menu, enter the product name in the ‘Search’ box and press enter. The records, in the enquiry details table will be filtered for the product name. Now click on the ‘Export As CSV’ button, to export the filtered enquiry records.

7. How can I highlight the Product Name in the Enquiry Dialog?
A. The Product Name text color can be changed from the ‘Product Enquiry’ settings menu. Set a desired color for the product name text, under Product Enquiry-> Styling Options-> Product Name Color.

8. Help! I lost my license key?
A. In case you have misplaced your purchased product license key, kindly go back and retrieve your purchase receipt id from your mailbox. Use this receipt id to make a support request to retrieve your license key.

9. How do I contact you for support?
A. You can direct your support request to us, using the Support form.

10. What will happen if my license expires?
Every purchased license is valid for one year from the date of purchase. During this time you will recieve free updates and support. If the license expires, you will still be able to use PEP, but you will not recieve any support or updates.

11. Do you have a refund policy?
Yes. Refunds will be provided under the following conditions: 
-If PEP does not work with your theme and has integration issues, which we are unable to fix, even after support requests have been made. How to raise a support request.
-Refund is requested within 30 days of the original product purchase date.
-Refund will not be provided, if you do not have a valid reason, to not use the product.

12. Can I disable PEP for specific product?
A. Yes, it is possible to disable for PEP for specific product. This feature is available from 1.3.1 onwards. To do that, please go to the Add/Edit Screen of corresponding product. Search for 'Disable Enquiry for this Product' field. It is above the 'Publish'  or 'Update' button. Setting that dropdown to 'Yes' will prevent PEP from displaying Enquiry form on that product page.

13. I can not see 'Enquiry Form' link on a product. What should I do?
A. This can happen in three scenarios:
     a)  Your license is not activated. 
     b)  PEP has been disabled from settings page of 'Product Enquiry'
     c)  PEP has been disabled for specific product from 'Edit Product' screen.

14. How can I change the Subject and Content of email that goes out to the Admin?
A. Though there is no option in Product Enquiry's settings page to change the email content, PEP allows to do that via coding. One can write a code on the filter 'pep_admin_email_content' and 'pep_admin_email_subject'

15. Is it possible to change the Subject and Content of email that goes to out to the Customer?
A. Yes, it is possible to do that. It can be done using filters: pep_customer_email_subject and pep_customer_email_content

16. I want to add my own styling to the Enquiry or Quote Request button that appears on frontend. How should I do that?
A. On PEP's settings page, we have provided different options, so that you can design the button as per you want. If those options are not sufficient, then you can add your own css in 'Add Custom CSS' box on the settings page.

17. How to disable Product Enquiry forms for products of specific categories?
A. To achieve this, you can add your code on the filter 'pep_before_deciding_position_of_enquiry_form'. Returning false from the associated function with that filter will prevent PEP from showing the button on frontend.
 
18. I am not able to export enquiries/I encounter a white screen when I export enquiries.
A. This could be because WP_DEBUG has been turned on and you are notified with warnings by some other activated plugin or theme.

19. I generated a Quote PDF hours ago. I cannot find it now. What should I do?
A. Clicking the ‘Save and Preview’ button will generate the PDF once again. QuoteUp automatically deletes generated PDFs after an hour of creation, to protect your data in case your server gets compromised and to save space.

20. I feel it takes time to generate PDF. Do you know why so?
A. When ‘Save and Preview’ button is clicked for the first time after installing a plugin, it downloads a font required for your site and then generates a PDF. Time taken to download the font depends upon the size of a font.

21. I had disabled price for all products then to price is visible on products page?
A. when you disable or enable price for all products make sure you check global settings to apply those changes on all products.

== Changelog ==

= 6.3.4 =
* Feature: Added settings in the General tab to enable/ disable Price and Remarks columns in enquiry mail and enquiry cart. #53915
* Feature: Added settings in the Display tab to style and position the enquiry cart icon. #54066
* Feature: Added setting in the General tab to modify the Remarks column name. #53915
* Feature: Added setting to disable bootstrap on frontend. #56071
* Feature: Added setting to manually provide the selector to fetch the variation Id on the single variable product page. #56105
* Feature: Added JS trigger event to extend PEP functionality when enquiry form is submitted successfully. #55880
* Feature: An Enquiry button is enabled only when a variation is selected on Variable Product page. #56277
* Feature: Modified the phone field in the custom form. #56234
* Fix: Product attributes issue in enquiry cart for non-English language. #55912
* Fix: Rating field validation issue on quote creation page. #51667
* Fix: Rating fields preview not visible on form edit page. #57049
* Fix: Enquiry edit page issue after an enquiry is anonymized. #46319
* Fix: Translation issues for enquiry and quote listing on the myaccount page. #46029
* Fix: JS error on mobile view of an enquiry cart when enquiry mail and enquiry cart templates are overridden. #53920
* Fix: Quantity field issue when product price or 'Add to cart' is disabled. #52837
* Fix: Conditional logic issue based on Rating field in the custom form. #50838
* Fix: Depended fields were not getting visible after changing the value of the 'select' field. #54508

= 6.3.3 =
Fix: Error while sending quotation when WooCommerce 3.6.2 is activated.
Fix: Getting date field as 'undefined' in the customer enquiry email.
Fix: Alignment of 'Remarks' field in the customer enquiry email.

= 6.3.2 =
* Feature: Added compatibility with Captcha V3 for PEP forms.
* Fix: Not able to submit enquiry when conditional Login contains metacharacters.
* Fix: Send Quotation Form is always filled with Admin's Info (For Custom Forms).
* Fix: JS error on enquiry edit page.
* Fix: JS error on quote creation page.
* Fix: Order Placed from Quotation shows selected language's product in Dashboard order.
* Fix: Removed conditional logic from Captcha field.
* Fix: Rating field issues in custom form.

= 6.3.1 =
* Feature: Compatibility with caching.
* Feature: Google captcha error message field in the 'form' tab.
* Fix: Variation price not visible on the single product page.
* Fix: Not getting variations in search result while creating a quote.
* Fix: Custom Form Field Icon size larger than field height.
* Fix: Conditional Logic not working for PEP Forms.
* Fix: The Form changed to draft state getting displayed while making an enquiry.
* Fix: Purchasing products, added to the quote list not having price.

= 6.3.0 =
* Feature: Quote history is displayed on the frontend my account page.
* Fix: Solved session related warning messages when using PHP version 7.0+.
* Fix: In admin backend, hover over the customer field on the enquiry edit page to see the entire customer data.
* Fix: JS compatibility of function 'showAlerts' with IE11.
* Fix: Issue with the multiple file upload fields in the enquiry form.
* Fix: When the label of a field is changed while creating the custom form, then reflected label does not appear in the conditional logic drop-down.
* Fix: Checkbox issue on 'Create New Quote' page.
* Fix: Issue when saving the name and email in the browser for custom enquiry forms.
* Fix: 'Enquiry sent' success message issue in case of single product enquiry.
* Fix: 'Date' field issue in case of Single Product Enquiry and Default Form.
* Fix: If 'Terms and Conditions' setting is not enabled, then don't add '[customer@email] accepted the Enquiry terms and conditions.' in the email.
* Fix: Don't verify nonce if 'Disable Nonce' setting is enabled.
* Fix: Translation Issues.

= 6.2.2 =
* Fix: Issue when there are many variations in single variable product.
* Fix: Issue with checkout when WPML is active.

= 6.2.1 =
* Fix: Added privacy policy suggestion text on privacy policy page.
* Fix: Added note on custom form edit page.
* Fix: Solved settings issue for versions below WordPress 4.7

= 6.2.0 =
* Feature: Enquiries data is included in export personal data.
* Feature: Enquiries data is anonymized during remove personal data.
* Feature: Bulk action to remove personal data of one or more enquiry.
* Fix: Variation slug displayed on cart page instead of variation name.
* Fix: Email    Mobile responsive.

= 6.1.0 =
* Feature: Setting to allow only logged in users to make enquiry.
* Feature: Setting to avoid nonce issue.
* Feature: Templates for enquiry cart, enquiry mails and approval rejection page.
* Fix: Prefilled name and email in custom form if user is logged in.
* Fix: Issue with multiple line thank you messgae in custom form.
* Fix: Issue with google analytics in custom form.

= 6.0.6 =
* Fix: Solved the issue with quotation price not applying in some case.

= 6.0.5 =
* Fix: Changed position of modal to solve modal overlay issue

= 6.0.4 =
* Fix: Minor bug fixes related to custom form.

= 6.0.3 =
* Fix: solved issue with Bootstrap.
* Fix: solved issue with same class name in some cases for custom form.

= 6.0.2 =
* Fix: Solved issue with conditional logic

= 6.0.1 =
* Feature: Support with Google Analytics. Per Product Enquiry tracking
* Fix: Conditional Logic bug resolved
* Fix: Custom Form is now Translation Ready
* Fix: 'Send me a Copy' added to Custom Form
* Fix: Other minor bug fixes
* Fix: All emails from the plugin are now mobile responsive

= 6.0.0 =
* Fix: Solved issues with PHP 7
* Feature: Added a new Form Builder for easy adding, deleting and changing fields 

= 5.0.0 =
* Fix: Restructured Database: Created a new table ‘enquiry_products’ and migrated old details to the new one
* Fix: Made Enquiry Edit Page, Create Quote Page and Enquiry Cart Page Responsive
* Fix: Refactored the Code

= 4.5.0 =
* Feature: Added Google ReCaptcha to the form to prevent spam
* Feature: Added Search Field on the enquiry listing page to quick search a particular client or quote request
* Fix: Hide the filters on enquiry listing page when there are no enquiries of that type under it
* Fix: Display number of new enquiries on enquiry listing page
* Fix: Jump to checkout page when clicked on approve. No need to enter the email address associated with the quote request with the condition that quote is approved by clicking on the link in the email and not the pdf.
* Fix: Made the floating enquiry cart baloon sticky
* Fix: In the email settings, if ‘Mail to Admin’ and/or ‘Mail to Author’ is checked, then allow user to save email setting without any email address in the recipient field
* Fix: Removed telephone number validation to support all kinds of telephone number formats.



= 4.4.0 =
* Feature: Attach Button on form which allows sending files to admin
* Feature: Having a PDF Quote as an attachment in the email sent to customers is now optional. Quotation will be displayed in the email body with link to checkout page.
* Feature: Screen Options added to set the number of items on enquiry listing page.
* Fix: Allow adding a second variation to the enquiry cart after the first one has been added.
* Fix: New and improved enquiry email template
* Fix: Common template for Single and Multiproduct enquiry emails.
* Fix: Create templating of Email Table.
* Fix: Text changes for ease of understanding.
* Fix: Browser page title changed to 'edit enquiry' for ease of switching back to the right tab.
* Fix: Fixed WPML compatibility issues
* Fix: Stored enquiry language as site language even when WPML is not activated to avoid future compatibility issues.
* Fix: Fixed minor loading issuess.
* Fix: 500 Characters Remaining text now translation ready.

= 4.3.1 =
* Feature: Now Compatible with php7+
* Fix: Add to cart issue on WooCommerce 3.0 resolved. Button now being hidden when disabled through bulk actions
* Fix: Font Color on single product enquiry can be changed
* Fix: Quantity now being sent in the enquiry email to the admin
4.4
* Fix: Variable product price now being applied according to price in quotation
* Fix: Decimal numbers will not be accepted in quantity field
* Fix: Subject being sent twice if field left blank, now resolved
* Fix: Variable Product Prices weren't being displayed after WooCommerce 3.0 update, now resolved.
* Fix: Price validation on create quote page. Negative prices will not be accepted now.
* Fix: Quantity Validation in Create Quote Page. Negative values will not be accepted now.
* Fix: Solved all other validation issues.

= 4.3.0 =
* Feature: Create New quote from Dashboard.
* Feature: Allow to create quote of sold individually product with more than 1 product.
* Feature: Added functionality to update existing enquiry and create quote.
* Fix: Compatiblity with woocommerce 3.0

= 4.2.0 =
* Feature: Added Quantity text box in single product enquiry form.
* Feature: Added Shortcode to display enquiry button on any page.
* Feature: Added enquiry support for all types of products (quotation system support available only for simple and variable product types).
* Feature: Added character limit of 500 to the message section.
* Fix: Made enquiry cart page and approval/rejection page responsive.
* Fix: Added date field validations on the front end.
* Fix: Fixed issues with WPML compatiblity.

= 4.1.1 =
* Feature - Added a setting for custom label for 'Enquiry' and 'Quote'.
* Feature - Added a setting for custom label for 'View Enquiry/Quote Cart'.
* Feature - Added a setting for custom label for 'Approve' and 'Reject' button.
* Feature - Added a setting for message to be displayed after quote rejection.
* Feature - Added a setting to add date field with custom date label on the form.
* Fix - Made edit quote page responsive.
* Fix - Changed position of expiration date.
* Fix - Added support for woocommerce 2.4.
* Fix - Added support for variations fetched through ajax.

= 4.1.0 =
* Feature - Added support for all variations of variable products.
* Feature - Provided bulk actions on products listing page for to 'enable/disable enquiry', 'show/hide price' and 'enable/disable add to cart'.
* Fix - CSS issue in some browsers.
* Fix - Made changes in session library to remove conflict with other plugins.

= 4.0.2 =
* Fix - Solved problem with approval/rejection link in PDF.

= 4.0.1 =
* Fix - Added support to variable products same as 3.1.1.

= 4.0 =
* Feature - Added quotation system.
* Feature - Improved quotation details layout (when quotation system is enabled).
* Feature - Receive quote request from customers.
* Feature - Create quotes and send it to your customers right from the dashboard.
* Feature - Auto-generation of quotation PDF.
* Feature - Product specific show or hide price on frontend.
* Fix - Improved the dashboard layout for both enquiry and quotation system.
* Fix - Fixed error with setting woocommerce cart page as enquiry cart.
* Fix - Displaying quote button for products which are displayed using any woocommerce shortcode.
* Fix - Product enquiry pro settings does not take effects on variable products.


= 3.1.1 =
* Feature - Shortcode automatically added on enquiry cart page.
* Feature - Shortcode removed from old enquiry cart page on enquiry cart page change.
* Fix - Problem with adding custom fields on single enquiry form.
* Fix - Problem with adding custom fields on multiple enquiry form.
* Fix - Removed minimum 15 characters message validation on enquiry form.

= 3.1.0 =
* Hover feature added.
* Fix - Enquiry time issue on enquiry details page.
* Fix - Issue when we change products sale price.
* Fix - Issue when changing product image.
* Fix - Message validation on enquiry form.
* Fix - warnings and notices on plugin activation.

= 3.0.1 =
* Issue with plugin update fixed.
* Compatibility with php version 5.3

= 3.0.0 =
* Feature for multi-product enquiry
* Option for admin to reply from the enquiry from dashboard

= 2.2.1 =
Fixed warnings and redirect issues with the last update
Compatible with WordPress 4.3.1 and WooCommerce 2.4.10

= 2.2.0 =
* Enquiry button on shop archive page
* Option to apply global settings for all products

= 2.1.4 =
* Resolved the issue with the Add to cart button being displayed twice
* Feature to select the county for telephone number field validation added
* Compatible with WooCommerce 2.4.6 and WordPress 4.3

= 2.1.3 =
* Feature to make the telephone number field mandatory

= 2.1.2 =
* Updated plugin license code

= 2.1.1 =
* Added feature to redirect user on submitting the enquiry

= 2.1.0 =
* Added feature to enable/disable add to cart button
* Form field accepts the name of the user making the enquiry
* Compatible with WooCommerce 2.3.7

= 2.0.0 = 
* Added filters to allow users to customize enquiry form.
* Added functionality to display additional fields in enquiry table.
* Added German language files.
* Added Dutch language files.

= 1.4.0 =
* Feature to add multiple recipient email addresses.
* Display Enquiry button only when product is out of stock.
* Added Swedish language files.
* Fixed warnings.

= 1.3.1 =
* Added Blog's Name in the Subject of emails that are sent out.
* Many Hooks and Filters have been added. Developers can now add their own hidden field on the form.
* Added SKU Field in the form.
* CSS Box on Settings page to add custom CSS.
* If WPML is enabled, then sends out email to Admin and Author in the language they have set in their profile.
* If WPML is enabled, then sends out email to customers in the language they are viewing the website.

= 1.3.0 =
*Provided checkbox to replace enquiry button with link
*reply-to address changed to senders address

= 1.2.1 =
*Fixed Warnings
*Fixed Enquiry Modal issue for fixed header themes

= 1.2.0 =
*Fixed Enquiry Modal issue 
*Increased Enquiry Message Length

= 1.1 =
*Updated Strings on Product Enquiry Dialog
*Changed text of Post Enquiry message

= 1.0 =
*Plugin Released
