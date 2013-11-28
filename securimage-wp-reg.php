<?php
/*
Plugin Name: Securimage-WP-REG
Plugin URI: http://jehy.ru/articles/web/
Description: Adds CAPTCHA protection from Securimage-WP plugin to user register form
Author: Jehy
Version: 0.02
Author URI: http://jehy.ru/articles/web/
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
  if (!is_user_logged_in() || !current_user_can('administrator'))
  {
    if (defined('SIWP_DIV_FIXED'))
    {
      add_action('register_form', 'siwp_captcha_html');
      add_action('signup_extra_fields', 'siwp_captcha_html');
    }
    else
    {
      add_action('register_form', 'siwp_captcha_html2');
      add_action('signup_extra_fields', 'siwp_captcha_html2');
    }
    add_action('register_post', 'siwp_check_captcha', 10, 3);
    add_filter('wpmu_validate_user_signup', 'siwp_check_captcha');
  }


  if (!defined('SIWP_DIV_FIXED'))
  {
    if (!is_user_logged_in() || !current_user_can('administrator'))
    {
      remove_action('comment_form', 'siwp_captcha_html');
      add_action('comment_form', 'siwp_captcha_html2');


      function siwp_captcha_html2()
      {
        $show_protected_by = get_option('siwp_show_protected_by', 1);
        $disable_audio = get_option('siwp_disable_audio', 0);
        $flash_bgcol = get_option('siwp_flash_bgcol', '#ffffff');
        $flash_icon = get_option('siwp_flash_icon', siwp_default_flash_icon());
        $position_fix = get_option('siwp_position_fix', 0);
        $refresh_text = get_option('siwp_refresh_text', 'Different Image');
        $use_refresh_text = get_option('siwp_use_refresh_text', 0);
        $imgclass = get_option('siwp_css_clsimg', '');
        $labelclass = get_option('siwp_css_clslabel', '');
        $inputclass = get_option('siwp_css_clsinput', '');
        $imgstyle = get_option('siwp_css_cssimg');
        $labelstyle = get_option('siwp_css_csslabel');
        $inputstyle = get_option('siwp_css_cssinput');
        $expireTime = siwp_get_captcha_expiration();
        $display_sequence = get_option('siwp_display_sequence', 'captcha-input-label');
        $display_sequence = preg_replace('/\s|\(.*?\)/', '', $display_sequence);
        $captchaId = sha1(uniqid($_SERVER['REMOTE_ADDR'] . $_SERVER['REMOTE_PORT']));
        $plugin_url = siwp_get_plugin_url();

        $captcha_html = "<div id=\"siwp_captcha_input\">\n";
        $captcha_html .=
          "<script type=\"text/javascript\">
	<!--
	function siwp_refresh() {
	    // get new captcha id, refresh the image w/ new id, and update form input with new id
		var cid = siwp_genid();
		document.getElementById('input_siwp_captcha_id').value = cid;
		document.getElementById('securimage_captcha_image').src = '{$plugin_url}lib/siwp_captcha.php?id=' + cid;

		// update flash button with new id
		var obj = document.getElementById('siwp_obj');
		obj.setAttribute('data', obj.getAttribute('data').replace(/[a-zA-Z0-9]{40}$/, cid));
		var par = document.getElementById('siwp_param'); // this was a comment...
		par.value = par.value.replace(/[a-zA-Z0-9]{40}$/, cid);

		// replace old flash w/ new one using new id
		var newObj = obj.cloneNode(true);
		obj.parentNode.insertBefore(newObj, obj);
		obj.parentNode.removeChild(obj);
	}
	function siwp_genid() {
	    // generate a random id
		var cid = '', chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		for (var c = 0; c < 40; ++c) { cid += chars.charAt(Math.floor(Math.random() * chars.length)); }
		return cid;
	};
	var siwp_interval = setInterval(siwp_refresh, " . ($expireTime * 1000) . ");
	-->
	</script>
	";

        $sequence = explode('-', $display_sequence);
        foreach ($sequence as $part)
        {
          switch ($part)
          {
            case 'break':
              $captcha_html .= "<br />\n";
              break;

            case 'captcha':
            {
              $captcha_html .= '<div style="float: left">';
              $captcha_html .= '<img id="securimage_captcha_image" src="' .
                siwp_get_captcha_image_url() .
                '?id=' . $captchaId . '" alt="CAPTCHA Image" style="vertical-align: middle;' .
                ($imgstyle != '' ?
                  ' ' . htmlspecialchars($imgstyle) :
                  '') . '" ' .
                ($imgclass != '' ?
                  'class="' . htmlspecialchars($imgclass) . '" ' :
                  '') .
                "/>";

              if ($show_protected_by)
              {
                $captcha_html .= '<br /><a href="http://www.phpcaptcha.org/" ' .
                  'target="_new" style="font-size: 12px; ' .
                  'font-style: italic" class="' .
                  'swip_protected_by">Protected by ' .
                  'Securimage-WP</a>' . "\n";
              }

              #$captcha_html .= "</div>\n"; bad line

              if (!$disable_audio)
              {
                $captcha_html .= '<div style="float: left">';
                $captcha_html .= '<object id="siwp_obj" type="application/x-shockwave-flash"' .
                  ' data="' . siwp_get_plugin_url() .
                  'lib/securimage_play.swf?bgcol=#' . $flash_bgcol .
                  '&amp;icon_file=' . urlencode($flash_icon) .
                  '&amp;audio_file=' . urlencode(siwp_get_plugin_url()) .
                  'lib/siwp_play.php?id=' . $captchaId . '" height="32" width="32">' .
                  "\n" .
                  '<param id="siwp_param" name="movie" value="' . siwp_get_plugin_url() .
                  'lib/securimage_play.swf?bgcol=#' . $flash_bgcol .
                  '&amp;icon_file=' . urlencode($flash_icon) .
                  '&amp;audio_file=' . urlencode(siwp_get_plugin_url()) .
                  'lib/siwp_play.php?id=' . $captchaId . '">' .
                  "\n</object>\n<br />";
              }

              if ($use_refresh_text) $captcha_html .= '[ ';
              $captcha_html .= '<a tabindex="-1" style="border-style: none;"' .
                ' href="#" title="Refresh Image" ' .
                'onclick="siwp_refresh(); return false">' .
                ($use_refresh_text == false ?
                  '<img src="' . siwp_get_plugin_url() .
                    'lib/images/refresh.png" alt="Reload Image"' .
                    ' onclick="this.blur()" style="height: 32px; width: 32px"' .
                    ' align="bottom" />' :
                  $refresh_text
                ) .
                '</a>';
              if ($use_refresh_text) $captcha_html .= ' ]';

              $captcha_html .= '</div><div style="clear: both;"></div>' . "\n";

              break;
            }

            case 'input':
              $captcha_html .= '<input type="hidden" id="input_siwp_captcha_id" name="siwp_captcha_id" value="' . $captchaId . '" />' .
                '<input id="siwp_captcha_value" ' .
                'name="siwp_captcha_value" size="10" ' .
                'maxlength="8" type="text" aria-required="true"' .
                ($inputclass != '' ?
                  ' class="' . htmlspecialchars($inputclass) . '"' :
                  '') .
                ($inputstyle != '' ?
                  ' style="' . htmlspecialchars($inputstyle) . '" ' :
                  '') .
                ' />';

              if (get_current_theme() == 'Twenty Eleven')
              {
                $captcha_html .= '</p>';
              }

              $captcha_html .= "\n";
              break;

            case 'label':
              if (get_current_theme() == 'Twenty Eleven')
              {
                $captcha_html .= '<p class="comment-form-email">';
              }
              $captcha_html .= '<label for="siwp_captcha_value"' .
                ($labelclass != '' ?
                  ' class="' . $labelclass . '"' :
                  '') .
                ($labelstyle != '' ?
                  ' style="' . htmlspecialchars($labelstyle) . '"' :
                  '') .
                '>' .
                'Enter Code <span class="required">*</span>' .
                '</label>' .
                "\n";
              break;
          }
        }

        $captcha_html .= "</div>\n";

        if ($position_fix)
        {
          $captcha_html .=
            "
		<script type=\"text/javascript\">
		<!--
		var commentSubButton = document.getElementById('comment');
	  	var csbParent = commentSubButton.parentNode;
		var captchaDiv = document.getElementById('siwp_captcha_input');
		csbParent.appendChild(captchaDiv, commentSubButton);
		-->
		</script>
		<noscript>
		<style tyle='text/css'>#submit {display: none}</style><br /><input name='submit' type='submit' id='submit-alt' tabindex='6' value='Submit Comment' />
		</noscript>
		";
        }

        echo $captcha_html;
      } // function siwp_captcha_html
    }
  }
}


?>