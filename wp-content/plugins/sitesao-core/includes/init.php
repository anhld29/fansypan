<?php
if (! class_exists ( 'DH_Init' )) :
	class DH_Init {
		public $version = '1.0.0';
		
		public function __construct() {
			load_plugin_textdomain( 'sitesao' , false,  basename(SITESAO_CORE_DIR).'/languages' );
			$this->_define_constants ();
			$this->_includes ();
			add_action('init', array(&$this,'init'));
			add_action('init', array(&$this,'thirdparty_plugins_init'),1);
		}
		
		public function thirdparty_plugins_init(){
			//Visual Composer
			if ( function_exists( 'vc_set_as_theme' ) ) :
			vc_set_as_theme( true );
			vc_manager()->disableUpdater();
			endif;
			//RevSlider
			if(function_exists('set_revslider_as_theme')){
				if(!defined('REV_SLIDER_AS_THEME'))
					define('REV_SLIDER_AS_THEME',true);
				set_revslider_as_theme();
				update_option('revslider-valid-notice', 'false');
			}
			if(class_exists('RevSliderAdmin') && method_exists('RevSliderAdmin', 'add_plugins_page_notices')){
				remove_action('admin_notices', array('RevSliderAdmin', 'add_plugins_page_notices'));
			}
			
			//Es Grid
			if(function_exists('set_ess_grid_as_theme')){
				if(!defined('ESS_GRID_AS_THEME'))
					define('ESS_GRID_AS_THEME', true);
				set_ess_grid_as_theme();
				update_option('tp_eg_valid-notice', 'false');
			}
		}
		
		public function init(){
			//dh_tooltip shortcode
			add_shortcode('dh_tooltip',array(&$this,'dh_tooltip_shortcode'));
			
		}
		
		private function _define_constants() {
		
			if(!defined('DHINC_VERSION'))
				define( 'DHINC_VERSION', $this->version );
			if(!defined('DHINC_DIR'))
				define( 'DHINC_DIR', dirname ( __FILE__ ) );
			if(!defined('DHINC_URL'))
				define( 'DHINC_URL', untrailingslashit( plugins_url( '/', dirname(__FILE__) ) ) . '/includes' );
			if(!defined('DHINC_ASSETS_URL'))
				define( 'DHINC_ASSETS_URL', untrailingslashit( plugins_url( '/', dirname(__FILE__) ) ) . '/assets' );
		}
		private function _includes() {
			
			// Utils
			include_once (DHINC_DIR . '/functions.php');
			// LESS Helper
			include_once (DHINC_DIR . '/less-helper.php');
			
			// Register
			include_once (DHINC_DIR . '/register.php');
			// Hook
			include_once (DHINC_DIR . '/hook.php');
			// Post type
			include_once (DHINC_DIR . '/post-type-slider.php');
			//Visual Composer
			include_once (DHINC_DIR . '/visual-composer.php');
			
			// Widget
			include_once (DHINC_DIR . '/widget.php');
			// Breadcrumb
			include_once (DHINC_DIR . '/breadcrumb.php');
			//Woocommerce
			include_once (DHINC_DIR . '/woocommerce.php');
			include_once (DHINC_DIR . '/woocommerce_product_variable.php');
			
			if(!class_exists('SMK_Sidebar_Generator'))
				include_once (DHINC_DIR . '/lib/smk-sidebar-generator/smk-sidebar-generator.php');
			
			// Admin
			if (is_admin ()) {
				include_once (DHINC_DIR . '/admin/functions.php');
				include_once (DHINC_DIR . '/admin/admin.php');
			}
			
			//Controller
			include_once (DHINC_DIR . '/controller.php');
		}
		
		
		public function dh_tooltip_shortcode($atts,$content=null){
			$tooltip = '';
			extract(shortcode_atts(array(
				'text'			=>'',
				'url'			=>'#',
				'type'			=>'',
				'position'		=>'',
				'title'			=>'',
				'trigger'		=>'',
			), $atts));
			$data_el = '';
			if(!empty($type)){
				$data_el = ' data-toggle="'.$type.'" data-container="body" data-original-title="'.($type === 'tooltip' ? esc_attr(do_shortcode( shortcode_unautop( $content) )) : esc_attr($title)).'" data-trigger="'.$trigger.'" data-placement="'.$position.'" '.($type === 'popover'?' data-content="'.esc_attr(do_shortcode( shortcode_unautop( $content) )).'"':'').'';
			}
			if(!empty($data_el))
				$tooltip = '<a'.$data_el.' href="'.esc_url($url).'">'.do_shortcode( shortcode_unautop( $text) ).'</a>';
			return $tooltip;
		}
		
	}
	new DH_Init ();
endif;
