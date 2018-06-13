<?php
if ( ! class_exists( 'DH_Register' ) ) :

	class DH_Register {

		public function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
		}

		public function init() {
			if ( is_admin() ) {
				$this->register_vendor_assets();
			}else{
				add_action( 'template_redirect', array(&$this,'deregister_assets'));
			}
		}

		public function deregister_assets(){
			wp_deregister_style('dhvc-form-font-awesome');
		}
		
		public function register_vendor_assets() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			
			
			wp_register_script( 'vendor-ace-editor', DHINC_ASSETS_URL. '/vendor/ace/ace.js', array( 'jquery' ), SITESAO_CORE_VERSION, true );

			wp_register_style( 'vendor-chosen', get_template_directory_uri().'/assets/vendor/chosen/chosen.min.css', '1.1.0' );
			wp_register_script( 'vendor-chosen', get_template_directory_uri().'/assets/vendor/chosen/chosen.jquery' . $suffix .'.js', array( 'jquery' ), '1.0.0', true );

			wp_register_style('vendor-font-awesome',DHINC_ASSETS_URL . '/vendor/font-awesome/css/font-awesome' . $suffix . '.css', array(), '4.2.0' );
			wp_register_style('vendor-elegant-icon',DHINC_ASSETS_URL . '/vendor/elegant-icon/css/elegant-icon.css');
			
			wp_register_style('vendor-jquery-ui-bootstrap',DHINC_ASSETS_URL . '/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css', array(), '1.10.0' );
			
			wp_register_script( 'vendor-ace-editor', DHINC_ASSETS_URL. '/vendor/ace/ace.js', array( 'jquery' ), SITESAO_CORE_VERSION, true );
			
			wp_register_style( 'vendor-datetimepicker', DHINC_ASSETS_URL . '/vendor/datetimepicker/jquery.datetimepicker.css', '2.4.0' );
			wp_register_script( 'vendor-datetimepicker', DHINC_ASSETS_URL . '/vendor/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), '2.4.0', true );
			
			wp_register_style( 'vendor-chosen', DHINC_ASSETS_URL . '/vendor/chosen/chosen.min.css', '1.1.0' );
			wp_register_script( 'vendor-chosen', DHINC_ASSETS_URL . '/vendor/chosen/chosen.jquery' . $suffix .'.js', array( 'jquery' ), '1.0.0', true );
			
			wp_register_script( 'vendor-ajax-chosen', DHINC_ASSETS_URL . '/vendor/chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'vendor-chosen' ), '1.0.0', true );
			
			wp_register_script( 'vendor-boostrap', DHINC_ASSETS_URL . '/vendor/bootstrap' . $suffix . '.js', array('jquery','vendor-imagesloaded'), '3.2.0', true );
	
		}
		
	}
	new DH_Register();


endif;