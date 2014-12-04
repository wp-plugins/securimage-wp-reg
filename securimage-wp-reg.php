<?php
/*
Plugin Name: Securimage-WP-REG
Plugin URI: http://jehy.ru/articles/web/
Description: Adds CAPTCHA protection from <a href="http://wordpress.org/plugins/securimage-wp-fixed/">Securimage-WP-fixed</a> plugin to user register form. <a href="http://wordpress.org/plugins/securimage-wp-fixed/">Securimage-WP-fixed plugin</a> is a required dependency!
Version: 0.04
Author: Jehy
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

class siwp_reg
{

function siwp_reg()
{
  add_action('plugins_loaded', array($this,'reload_siwp'));
}

function reload_siwp()
{
  if (function_exists('siwp_captcha_html') &&(!is_user_logged_in())) 
  {
    add_action('register_form', 'siwp_captcha_html');#add captcha input for simple wordpress
    add_action('register_post', 'siwp_check_captcha', 10, 3);#check captcha for simple wordpress
    
    add_action('signup_extra_fields', 'siwp_captcha_html');#add captcha input for multisite
    add_filter('wpmu_validate_user_signup', 'siwp_check_captcha');#check captcha for wordpress multisite
  }
  elseif(!function_exists('siwp_captcha_html') && current_user_can('manage_options')) 
  {
    add_action('admin_init', array($this,'siwp_reg_plugin_deactivate'));
    add_action('admin_notices', array($this,'siwp_reg_plugin_admin_notice'));
  }
}

function siwp_reg_plugin_deactivate()
{
  deactivate_plugins(plugin_basename(__FILE__));
}

function siwp_reg_plugin_admin_notice()
{
  echo '<div class="error"><p><strong>'.__('Plugin','siwp-reg').'</strong> <a href="http://wordpress.org/plugins/securimage-wp-fixed/">SecureImage-WP-Fixed</a> '.__('was not found and plugin SecureImage-WP-REG was','siwp-reg').' <strong>'.__('deactivated','siwp-reg').'</strong>.</p></div>';
  if (isset($_GET['activate']))
    unset($_GET['activate']);
}
}

new siwp_reg();
?>