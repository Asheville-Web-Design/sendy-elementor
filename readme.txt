=== Integration Sendy for Elementor ===
Contributors: ashevillewebdesign
Tags: sendy, elementor form integration, elementor email marketing, email automation, subscribe forms
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://ashevillewebdesign.com/

== Description ==
Easily connect Elementor Pro forms to Sendy and automatically subscribe users to your email list.

== External Services ==

This plugin integrates Elementor Pro forms with Sendy, a self-hosted email newsletter application.

It sends form submission data (name and email) from Elementor Pro forms to your Sendy installation whenever a form is submitted.

**Data sent:**
- User's name and email address (from the Elementor form fields)
- Sendy List ID (entered in the plugin settings)
- API key (entered in the plugin settings)

**Where is the data sent?**
- Data is sent to the Sendy installation URL you configure in the plugin settings.

**External Service Provider:** [Sendy](https://sendy.co/)  
- [Sendy Terms of Service](https://sendy.co/legal)  
- [Sendy Privacy Policy](https://sendy.co/privacy-policy)

By using this plugin, you agree to Sendy's terms of service and privacy policy.

== Installation ==
1. Upload the `integration-sendy-elementor` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > Integration Sendy for Elementor** to enter your API details.

== Frequently Asked Questions ==
= Do I need Elementor Pro? =
Yes, this plugin works with Elementor Pro because it uses the **Form Widget**.

= Where can I find my Sendy List ID? =
Login to Sendy, go to **View all lists**, and check the **List ID**.

== Changelog ==
= 1.0 =
* Initial release.

== Screenshots ==
1. The settings page where you enter your Sendy API details.
2. Example of an Elementor form using this plugin.

== Upgrade Notice ==
= 1.0 =
First version.
