<?php
/*
Plugin Name: Sitesao Core
Plugin URI: http://sitesao.com/
Description: Sitesao Core Plugin for WOOW Themes
Version: 1.1.45
Author: Sitesao Team
Author URI: http://sitesao.com/
Text Domain: sitesao
*/
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if(!defined('SITESAO_CORE_VERSION'))
	define('SITESAO_CORE_VERSION', '1.1.45');

if(!defined('SITESAO_CORE_URL'))
	define('SITESAO_CORE_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('SITESAO_CORE_DIR'))
	define('SITESAO_CORE_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

class SitesaoCore {
	public function __construct(){
		add_action('dh_theme_includes', array($this,'includes'));
	}
	
	public function includes(){
		include_once (SITESAO_CORE_DIR.'/includes/init.php');
		//woo coomerce lookbook
		include_once (SITESAO_CORE_DIR . '/lookbook/lookbook.php');
		//woo coomerce brand
		include_once (SITESAO_CORE_DIR . '/dhwc-brand/dhwc-brand.php');
	}
}
new SitesaoCore();