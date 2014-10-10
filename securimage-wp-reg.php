<?php
/*
Plugin Name: Securimage-WP-REG
Plugin URI: http://jehy.ru/articles/web/
Description: Adds CAPTCHA protection from Securimage-WP plugin to user register form
Author: Jehy
Version: 0.03
Author URI: http://jehy.ru/articles/web/
Min WP Version: 2.5
Max WP Version: 4.0
*/

/*  Copyright (C) 2013 Jehy

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


#require_once ABSPATH . '/wp-includes/pluggable.php';

add_action('plugins_loaded', 'reload_siwp');

function reload_siwp()
{
    if (!is_user_logged_in() || !current_user_can('administrator')) {
        if (function_exists('siwp_captcha_html')) {
            add_action('register_form', 'siwp_captcha_html');
            add_action('signup_extra_fields', 'siwp_captcha_html');
            add_action('register_post', 'siwp_check_captcha', 10, 3);
            add_filter('wpmu_validate_user_signup', 'siwp_check_captcha');
        } else {
            add_action('admin_init', 'siwp_reg_plugin_deactivate');
            add_action('admin_notices', 'siwp_reg_plugin_admin_notice');

        }
    }
}

function siwp_reg_plugin_deactivate()
{
    deactivate_plugins(plugin_basename(__FILE__));
}

function siwp_reg_plugin_admin_notice()
{
    echo '<div class="updated"><p><strong>Plugin</strong> <a href="http://wordpress.org/plugins/securimage-wp-fixed/">SecureImage-WP-Fixed</a> was not found and plugin SecureImage-WP-REG was <strong>deactivated</strong>.</p></div>';
    if (isset($_GET['activate']))
        unset($_GET['activate']);
}

?>