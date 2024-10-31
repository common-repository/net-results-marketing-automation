=== Net-Results Marketing Automation ===
Contributors: Net-Results
Tags: marketing automation, lead management, lead nurturing, lead scoring, drip marketing, email marketing, Net-Results
Requires at least: 2.7
Tested up to: 5.1
Stable tag: 2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables all features of the Net-Results Marketing Automation Platform on your WordPress site or blog in literally seconds.

== Description ==

Leverage Progressive Profiling in your web forms, instantly begin lead scoring every visitor to your WordPress site. Automate marketing list management, lead nurturing and drip email campaigns.
Includes a custom Widget for embedding Net-Results Forms and the automatic setup of Net-Results implementation code (this enables all features of the Net-Results Marketing Automation Platform on your WordPress site or blog).

Net-Results is a next-generation marketing automation platform that makes it easy to leverage Relevance to drive connections, conversions and revenue.

For more information visit:

[Net-Results](http://www.net-results.com/marketing-automation/features/wordpress-marketing-automation-plugin)

== Frequently Asked Questions ==
Visit http://support.net-results.com/index.php/Marketing_Automation_Support_Site

== Installation ==

1. Install the Net-Results Plugin as you would any other WordPress plugin.
2. Activate the Net-Results Plugin through the 'Plugins' menu in WordPress.
3. In WordPress, navigate to Settings -> Net-Results
4. Add your Product Id (available in your Net-Results account at https://apps.net-results.com/app/User/account. Look for the "data-pid" value in the "implementation code". You can also email us at support@Net-Results.com and we'll reply quickly with your Product Id)
5. Authorize your WordPress install to connect to your Net-Results account:
	- Visit https://apps.net-results.com/app/Oauth/edit in another tab or window.
	- Enter "WordPress" or a similar Description
	- **Click Save**
		- Copy your Client ID from this page and paste it into the Client ID field of the WordPress Net-Results Plugin settings page.
		- Copy your Client Secret from this page and paste it into the Client Secret field of the WordPress Net-Results Plugin settings page.
6. Disable any popup blockers in your browser, and in the WordPress Net-Results Plugin settings page, Click the "**Get Access Token**"
7. You'll be brought to the Net-Results application with a prompt to "Authorize" this plugin
8. Click "**Authorize**"
9. You'll be brought back to your WordPress settings menu with the Token automatically populated in the Token field
10. Click the "**Save Settings**" button

== Changelog ==
= 2.0 =
	- September 11, 2014
	- Added Net-Results Forms widget: embed Net-Results forms in any parts of your theme that supports widgets

= 2.1 =
	- May 21, 2015
	- Added support for OAuth v2 secure authentication
	- Removed support for basic authentication via username and password

= 2.2 =
	- March 6, 2019
	- Updated package to fix a deprecated function

= 2.3 =
	- May 23, 2019
	- Update to the Forms widget so forms will render when ad blockers are being used

= 2.4 =
	- August 30, 2019
	- Update to the Net-Results Beacon code

= 2.5 =
	- August 30, 2019
	- version correction

== Screenshots ==

(none)

== Upgrade Notice ==
= 2.1 =
	- Substantial improvement to security: no usersnames/passwords stored in WordPress
	- Improved experience: Changing your password in Net-Results used require you to then update it in WordPress for the plugin to continue functioning. With the upgrade to OAuth v2 authentication this is no longer the case.
