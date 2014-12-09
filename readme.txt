=== Securimage-WP-REG ===
Contributors: Jehy
Author URI: http://jehy.ru/articles/web/
Tags: CAPTCHA, spam protection,register
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 0.04

Securimage-WP-REG adds captcha ptotection from Securimage-WP plugin on register pages.


== Description ==

[Securimage-WP-Fixed](http://wordpress.org/plugins/securimage-wp-fixed/) utilizes the powerful CAPTCHA protection of [Securimage Captcha](http://phpcaptcha.org/ "Securimage PHP CAPTCHA") which adds protection to your WordPress comment forms. Unfortunately, it didn't have option to be installed on user register page. Now you can just install Securimage-WP-REG, enable it - and you will have CAPTCHA on register page!    

So, Securimage-WP-REG adds captcha on register pages.

Requirements:

* [Securimage-WP-Fixed](http://wordpress.org/plugins/securimage-wp-fixed/) plugin installed and working. Old version of this plugin you can find [here](https://www.phpcaptcha.org/download/wordpress-plugin/).


####Donate or help?
If you want to ensure the future development and support of this plugin, you can make donation [on this page](http://jehy.ru/articles/donate/) or just write about this plugin in your blog.

== Installation ==

Installation of Securimage-WP-REG is simple.

1. From the `Plugins` menu, select `Add New` and then `Upload`.  Select the .zip file containing Securimage-WP-REG.  Alternatively, you can upload the `securimage-wp-reg` directory to your `/wp-content/plugins` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= What are the requirements? =

[Securimage-WP-Fixed](http://wordpress.org/plugins/securimage-wp-fixed/) plugin needs to be installed and working.

== Screenshots ==

1. Securimage-WP shown on a register form

== Changelog ==

= 0.04 =
* Fixed dependency tracking, refactoring.

= 0.03 =
* Removed HTML fix for securimage-wp plugin because it is now included in securimage-wp-fixed plugin. Please, remove your old plugin and install new if your page is broken.
* Added check for dependent plugin.

= 0.02 =
* Little fixes, release on wordpress.org

= 0.01 =
* Initial release

== Upgrade Notice ==

None yet!
