=== WooCommerce Instagram ===
Contributors: woocommerce, themesquad
Tags: woocommerce, instagram, hashtag, product, showcase
Requires at least: 4.1
Tested up to: 5.2
Stable tag: 2.1.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 2.4
WC tested up to: 3.6
Woo: 260061:ecaa2080668997daf396b8f8a50d891a

Connect your Instagram account with WooCommerce. Showcase photos from all over the world, showing visitors how your customers are showcasing your products.
Visit our [product page](https://woocommerce.com/products/woocommerce-instagram/) for more info.

== Minimum Requirements ==

* PHP version 5.2.4 or greater (PHP 7.2 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)

== Installation ==

1. Unzip and upload the plugin’s folder to your /wp-content/plugins/ directory.
2. Activate the extension through the ‘Plugins’ menu in WordPress.
3. Go to WooCommerce > Settings > General to configure the plugin.

== Documentation & support ==

For help setting up and configuring the extension please refer to our [user guide](https://docs.woocommerce.com/document/woocommerce-instagram/).

== Changelog ==

= 2.1.0 May 22, 2019 =
* Feature - Automatically renew the access credentials.
* Tweak - Keep the settings when disconnecting the Instagram account or removing the plugin.
* Tweak - Remove older update notices on plugin activation.
* Tweak - Added URL verification when connecting and disconnecting the Instagram account.
* Tweak - Increased `timeout` parameter for the API requests.
* Tweak - Added compatibility with WP 5.2.
* Fix - Fixed error when passing a callable as argument to the `empty()` function in PHP 5.4 and lower.
* Dev - Moved Instagram Graph API version to v3.3.

= 2.0.1 April 5, 2019 =
* Tweak - Added compatibility with WC 3.6.

= 2.0.0 February 4, 2019 =
* Feature - Use the new Instagram Graph API.
* Feature - Customize the frontend HTML content using WooCommerce template files.
* Feature - New and more intuitive settings page.
* Tweak - Added compatibility with WC 3.5.
* Tweak - Added compatibility with WP 5.0.
* Tweak - Updated Instagram logo.
* Tweak - Check the minimum requirements before initializing the plugin.
* Tweak - Remove the user credentials when uninstalling the plugin.
* Tweak - Optionally remove all the plugin data when uninstalling it.
* Tweak - Optimized the use of the API requests.
* Tweak - Better error handling for the API requests.
* Dev - Log possible errors in the API requests.
* Dev - Rewritten the entire extension.

== Upgrade Notice ==

= 2.1 =
2.1 is a major update. It is important that you make a full site backup and ensure you have installed WC 2.4+ before upgrading.