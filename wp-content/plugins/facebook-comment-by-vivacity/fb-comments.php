<?php
/**
* Plugin Name: Facebook Comments by Vivacity
* Plugin URI: http://www.vivacityinfotech.net
* Description: A simple Facebook Comments plugin for your blog that enables FB User`s to comment on your blog or website.
* Version: 1.4.1
* Author: Vivacity Infotech Pvt. Ltd.
* Author URI: http://www.vivacityinfotech.net
* Author Email: support@vivacityinfotech.net
* Text Domain: facebook-comment-by-vivacity
* Domain Path: /languages
*/
/*Copyright (C) 2010-2010, Alex Moss - alex@peadig.com
Copyright 2014-2016  Vivacity InfoTech Pvt. Ltd.  (email : support@vivacityinfotech.net)
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Alex Moss or pleer nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
if ( ! defined( 'ABSPATH' ) ) exit;

 add_action('init', 'vi_load_viva_transl');
    function vi_load_viva_transl()
   {
       load_plugin_textdomain('facebook-comment-by-vivacity', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
   }
  
if ( is_admin())
	require 'admin-file.php';
else
	require 'user-file.php';
	
// Add link - settings on plugin page
function fb_comment($links) {
  $settings_link = '<a href="options-general.php?page=fbcomment">'. __( "Settings", "facebook-comment-by-vivacity" ).'</a>';
 array_unshift($links, $settings_link);
 return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'fb_comment' );
?>
