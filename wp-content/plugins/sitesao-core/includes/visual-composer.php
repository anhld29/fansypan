<?php
if ( ! class_exists( 'DH_VisualComposer' ) && defined( 'WPB_VC_VERSION' ) ) :
	
	define( 'DHVC_ADD_ITEM_TITLE', __( "Add Item", 'sitesao' ) );
	define( 'DHVC_ITEM_TITLE', __( "Item", 'sitesao' ) );
	define( 'DHVC_MOVE_TITLE', __( 'Move', 'sitesao' ) );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Tabs', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-tabs.php' );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Column', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

	class DHWPBakeryShortcodeContainer extends WPBakeryShortCodesContainer {

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			
			if ( is_file( $user_template ) ) {
				
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class DHWPBakeryShortcode extends WPBakeryShortCode {

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class WPBakeryShortCode_DH_Carousel extends WPBakeryShortCode_VC_Tabs {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			// WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_tab' ) );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_carousel_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' .
				 do_shortcode( '[dh_carousel_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_carousel_item]' ) .
				 '</div>';
		}
	}

	class WPBakeryShortCode_DH_Carousel_Item extends WPBakeryShortCode_VC_Column {

		protected $controls_css_settings = 'tc vc_control-container';

		protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

		public function __construct( $settings ) {
			parent::__construct( $settings );
		}

		public function customAdminBlockParams() {
			return ' id="tab-' . $this->atts['tab_id'] . '"';
		}

		public function mainHtmlBlockParams( $width, $i ) {
			return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] .
				 ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
		}

		public function containerHtmlBlockParams( $width, $i ) {
			return 'class="wpb_column_container vc_container_for_children"';
		}

		public function getColumnControls( $controls, $extended_css = '' ) {
			return $this->getColumnControlsModular( $extended_css );
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}
	}

	class WPBakeryShortCode_DH_Testimonial extends WPBakeryShortCode_DH_Carousel {

		static $filter_added = false;

		public function __construct( $settings ) {
			parent::__construct( $settings );
			if ( ! self::$filter_added ) {
				$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
				self::$filter_added = true;
			}
		}

		protected $predefined_atts = array( 'tab_id' => DHVC_ITEM_TITLE, 'title' => '' );

		public function contentAdmin( $atts, $content = null ) {
			$width = $custom_markup = '';
			$shortcode_attributes = array( 'width' => '1/1' );
			foreach ( $this->settings['params'] as $param ) {
				if ( $param['param_name'] != 'content' ) {
					if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					} elseif ( isset( $param['value'] ) ) {
						$shortcode_attributes[$param['param_name']] = $param['value'];
					}
				} else 
					if ( $param['param_name'] == 'content' && $content == NULL ) {
						$content = $param['value'];
					}
			}
			extract( shortcode_atts( $shortcode_attributes, $atts ) );
			
			// Extract tab titles
			
			preg_match_all( 
				'/dh_testimonial_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
				$content, 
				$matches, 
				PREG_OFFSET_CAPTURE );
			
			$output = '';
			$tab_titles = array();
			
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			$tmp = '';
			if ( count( $tab_titles ) ) {
				$tmp .= '<ul class="clearfix tabs_controls">';
				foreach ( $tab_titles as $tab ) {
					preg_match( 
						'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', 
						$tab[0], 
						$tab_matches, 
						PREG_OFFSET_CAPTURE );
					if ( isset( $tab_matches[1][0] ) ) {
						$tmp .= '<li><a href="#tab-' .
							 ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
							 '">' . $tab_matches[1][0] . '</a></li>';
					}
				}
				$tmp .= '</ul>' . "\n";
			} else {
				$output .= do_shortcode( $content );
			}
			$elem = $this->getElementHolder( $width );
			
			$iner = '';
			foreach ( $this->settings['params'] as $param ) {
				$custom_markup = '';
				$param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
				if ( is_array( $param_value ) ) {
					// Get first element from the array
					reset( $param_value );
					$first_key = key( $param_value );
					$param_value = $param_value[$first_key];
				}
				$iner .= $this->singleParamHtmlHolder( $param, $param_value );
			}
			if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
				if ( $content != '' ) {
					$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
				} else 
					if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
						 $this->settings["default_content_in_template"] != '' ) {
						$custom_markup = str_ireplace( 
							"%content%", 
							$this->settings["default_content_in_template"], 
							$this->settings["custom_markup"] );
					} else {
						$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
					}
				$iner .= do_shortcode( $custom_markup );
			}
			$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
			$output = $elem;
			
			return $output;
		}

		/**
		 * Find html template for shortcode output.
		 */
		protected function findShortcodeTemplate() {
			// Check template path in shortcode's mapping settings
			if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
				return $this->setTemplate( $this->settings['html_template'] );
			}
			// Check template in theme directory
			$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
			if ( is_file( $user_template ) ) {
				return $this->setTemplate( $user_template );
			}
		}

		protected function getFileName() {
			return $this->shortcode;
		}

		public function getTabTemplate() {
			return '<div class="wpb_template">' .
				 do_shortcode( '[dh_testimonial_item title="' . DHVC_ITEM_TITLE . '" tab_id=""][/dh_testimonial_item]' ) .
				 '</div>';
		}
	}

	class WPBakeryShortCode_DH_Testimonial_Item extends DHWPBakeryShortcode {
	}
	
	// Shortcode Container
	class WPBakeryShortCode_DH_Product_Slider extends DHWPBakeryShortcodeContainer {
	}
	// Shortcode
	class WPBakeryShortCode_DH_Button extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Instagram extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Post extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Post_Grid extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Mailchimp extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Box_Feature extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Video extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Counter extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Countdown extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_Client extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Cart extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Layered_Nav_Filters extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Layered_Nav extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Price_Filter extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Categories extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Search extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Tag_Cloud extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Mansory extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_Product_Categories_Grid extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Product_Lookbooks extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Products extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Special_Product extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Products_Grid extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Recent_Reviews extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Recently_Viewed_Products extends DHWPBakeryShortcode {
	}

	class WPBakeryShortCode_DH_WC_Top_Rated_Products extends DHWPBakeryShortcode {
	}

	class DH_VisualComposer {

		public $param_holder = 'div';

		public static $button_styles = array( 'Default' => '', 'Outlined' => 'outline' );

		public function __construct() {
			
			
			add_action( 'init', array( &$this, 'map' ), 20 );
			add_action( 'init', array( &$this, 'add_params' ), 50 );
			if ( is_admin() ) {
				add_action( 'do_meta_boxes', array( &$this, 'remove_vc_teaser_meta_box' ), 1 );
				add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ),100 );
				add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ),100 );
				
				$vc_params_js = DHINC_ASSETS_URL . '/js/vc-params.js';
				vc_add_shortcode_param( 'nullfield', array( &$this, 'nullfield_param' ), $vc_params_js );
				vc_add_shortcode_param( 
					'product_attribute_filter', 
					array( &$this, 'product_attribute_filter_param' ), 
					$vc_params_js );
				vc_add_shortcode_param( 'product_attribute', array( &$this, 'product_attribute_param' ), $vc_params_js );
				vc_add_shortcode_param( 'products_ajax', array( &$this, 'products_ajax_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_brand', array( &$this, 'product_brand_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_lookbook', array( &$this, 'product_lookbook_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_category', array( &$this, 'product_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_datepicker', array( &$this, 'ui_datepicker_param' ) );
				vc_add_shortcode_param( 'post_category', array( &$this, 'post_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_slider', array( &$this, 'ui_slider_param' ) );
				vc_add_shortcode_param( 'dropdown_group', array( &$this, 'dropdown_group_param' ) );
			}
			add_filter( 'vc_autocomplete_dh_wc_product_mansory_category_callback', array(&$this,'productCategoryCategoryAutocompleteSuggester'), 10, 1 ); // Get suggestion(find). Must return an array
			add_filter( 'vc_autocomplete_dh_wc_product_mansory_category_render', array(&$this,'productCategoryCategoryRenderByIdExact'), 10, 1 ); // Render exact category by id. Must return an array (label,value)
			
		}
		
		public function productCategoryCategoryAutocompleteSuggester( $query, $slug = true ) {
			global $wpdb;
			$cat_id = (int) $query;
			$query = trim( $query );
			$post_meta_infos = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT a.term_id AS id, b.name as name, b.slug AS slug
					FROM {$wpdb->term_taxonomy} AS a
					INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
					WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )",
					$cat_id > 0 ? $cat_id : - 1,
					stripslashes( $query ),
					stripslashes( $query ) ),
					ARRAY_A );
		
			$result = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data = array();
					$data['value'] = $slug ? $value['slug'] : $value['id'];
					$data['label'] = __( 'Id', 'luxury-wp' ) . ': ' . $value['id'] .
					( ( strlen( $value['name'] ) > 0 ) ? ' - ' . __( 'Name', 'sitesao' ) . ': ' . $value['name'] : '' ) .
					( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . __( 'Slug', 'sitesao' ) . ': ' . $value['slug'] : '' );
					$result[] = $data;
				}
			}
		
			return $result;
		}
		
		public function productCategoryCategoryRenderByIdExact( $query ) {
			$query = $query['value'];
			$slug = $query;
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			return $this->_productCategoryTermOutput( $term );
		}
		
		protected function _productCategoryTermOutput( $term ) {
			$term_slug = $term->slug;
			$term_title = $term->name;
			$term_id = $term->term_id;
		
			$term_slug_display = '';
			if ( ! empty( $term_slug ) ) {
				$term_slug_display = ' - ' . __( 'Sku', 'sitesao' ) . ': ' . $term_slug;
			}
		
			$term_title_display = '';
			if ( ! empty( $term_title ) ) {
				$term_title_display = ' - ' . __( 'Title', 'sitesao' ) . ': ' . $term_title;
			}
		
			$term_id_display = __( 'Id', 'sitesao' ) . ': ' . $term_id;
		
			$data = array();
			$data['value'] = $term_slug;
			$data['label'] = $term_id_display . $term_title_display . $term_slug_display;
		
			return ! empty( $data ) ? $data : false;
		}

		public function map() {
			$is_wp_version_3_6_more = version_compare( 
				preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), 
				'3.6' ) >= 0;
			vc_map( 
				array( 
					'base' => 'dh_button', 
					'name' => __( 'Button', 'sitesao' ), 
					'description' => __( 'Eye catching button.', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_button', 
					'icon' => 'dh-vc-icon-dh_button', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_menu_anchor', 
					'name' => __( 'Menu Anchor', 'sitesao' ), 
					'description' => __( 'Add a menu anchor points.', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-menu_anchor', 
					'icon' => 'dh-vc-icon-menu_anchor', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_post', 
					'name' => __( 'Post', 'sitesao' ), 
					'description' => __( 'Display post.', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_post', 
					'icon' => 'dh-vc-icon-dh_post', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_post_grid', 
					'name' => __( 'Post Zigzag', 'sitesao' ), 
					'description' => __( 'Display post with 2 styles.', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_post', 
					'icon' => 'dh-vc-icon-dh_post', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			
			vc_map( 
				array( 
					'base' => 'dh_mailchimp', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'name' => __( 'Mailchimp Subscribe', 'sitesao' ), 
					'description' => __( 'Widget Mailchimp Subscribe.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_mailchimp', 
					'icon' => 'dh-vc-icon-dh_mailchimp', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_box_feature', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'name' => __( 'Box Feature', 'sitesao' ), 
					'description' => __( 'Box Feature.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_box_feature', 
					'icon' => 'dh-vc-icon-dh_box_feature', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_instagram', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'name' => __( 'Instagram', 'sitesao' ), 
					'description' => __( 'Instagram.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_instagram', 
					'icon' => 'dh-vc-icon-dh_instagram', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			
			$sliders = get_terms( 'dh_slider' );
			$slider_option = array();
			$slider_option[__( 'None', 'sitesao' )] = '';
			foreach ( (array) $sliders as $slider ) {
				$slider_option[$slider->name] = $slider->slug;
			}
			vc_map( 
				array( 
					'base' => 'dh_slider', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'name' => __( 'DH Slider', 'sitesao' ), 
					'description' => __( 'Display DH Slider.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_slider', 
					'icon' => 'dh-vc-icon-dh_slider', 
					'show_settings_on_create' => true, 
					'params' => array( 
						array( 
							'type' => 'dropdown', 
							'admin_label' => true, 
							'heading' => __( 'Slider', 'sitesao' ), 
							'param_name' => 'slider_slug', 
							'value' => $slider_option, 
							'description' => __( 'Select a slider.', 'sitesao' ) ) ) ) );
			
			vc_map( 
				array( 
					'base' => 'dh_carousel', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'name' => __( 'Carousel Content', 'sitesao' ), 
					'description' => __( 'Animated carousel with carousel', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_carousel', 
					'icon' => 'dh-vc-icon-dh_carousel', 
					'show_settings_on_create' => true, 
					'is_container' => true, 
					'js_view' => 'DHVCCarousel', 
					'params' => array(), 
					"custom_markup" => '
						  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
						  <ul class="tabs_controls">
						  </ul>
						  %content%
						  </div>', 
					'default_content' => '
					  [dh_carousel_item title="' . __( 'Item 1', 'sitesao' ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  [dh_carousel_item title="' . __( 'Item 2', 'sitesao' ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  [dh_carousel_item title="' . __( 'Item 3', 'sitesao' ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_carousel_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Carousel Item', 'sitesao' ), 
					'base' => 'dh_carousel_item', 
					"category" => __( "Sitesao", 'sitesao' ), 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					'params' => array(), 
					'js_view' => 'DHVCCarouselItem' ) );
			vc_map( 
				array( 
					'base' => 'dh_testimonial', 
					'name' => __( 'Testimonial', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'description' => __( 'Animated Testimonial with slider', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_testimonial', 
					'icon' => 'dh-vc-icon-dh_testimonial', 
					'show_settings_on_create' => true, 
					'is_container' => true, 
					'js_view' => 'DHVCTestimonial', 
					'params' => array(), 
					"custom_markup" => '
						  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
						  <ul class="tabs_controls">
						  </ul>
						  %content%
						  </div>', 
					'default_content' => '
					  [dh_testimonial_item title="' . __( 'Item 1', 'sitesao' ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  [dh_testimonial_item title="' . __( 'Item 2', 'sitesao' ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  [dh_testimonial_item title="' . __( 'Item 3', 'sitesao' ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dh_testimonial_item]
					  ' ) );
			vc_map( 
				array( 
					'name' => __( 'Testimonial Item', 'sitesao' ), 
					'base' => 'dh_testimonial_item', 
					'allowed_container_element' => 'vc_row', 
					'is_container' => true, 
					'content_element' => false, 
					"category" => __( "Sitesao", 'sitesao' ), 
					'params' => array(), 
					'js_view' => 'DHVCTestimonialItem' ) );
			vc_map( 
				array( 
					'base' => 'dh_video', 
					'name' => __( 'Video Player', 'sitesao' ), 
					"category" => __( "Sitesao", 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_video', 
					'icon' => 'dh-vc-icon-dh_video', 
					'show_settings_on_create' => true, 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_counter', 
					'name' => __( 'Counter', 'sitesao' ), 
					'description' => __( 'Display Counter.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_counter', 
					'icon' => 'dh-vc-icon-dh_counter', 
					'show_settings_on_create' => true, 
					"category" => __( "Sitesao", 'sitesao' ), 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_countdown', 
					'name' => __( 'Coundown', 'sitesao' ), 
					'description' => __( 'Display Countdown.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_countdown', 
					'icon' => 'dh-vc-icon-dh_countdown', 
					'show_settings_on_create' => true, 
					"category" => __( "Sitesao", 'sitesao' ), 
					'params' => array() ) );
			vc_map( 
				array( 
					'base' => 'dh_client', 
					'name' => __( 'Client', 'sitesao' ), 
					'description' => __( 'Display list clients.', 'sitesao' ), 
					'class' => 'dh-vc-element dh-vc-element-dh_client', 
					'icon' => 'dh-vc-icon-dh_client', 
					'show_settings_on_create' => true, 
					"category" => __( "Sitesao", 'sitesao' ), 
					'params' => array() ) );
			$this->_woocommerce_map();
		}

		protected function _woocommerce_map() {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return;
		
			vc_map(
				array(
					'base' => 'dh_product_slider',
					'name' => __( 'Product Slider', 'sitesao' ),
					'description' => __( 'Animated products with carousel.', 'sitesao' ),
					'as_parent' => array(
						'only' => 'dh_wc_recently_viewed_products,product_category,product_categories,dhwc_product_brands,products,related_products,product_attribute,featured_products,top_rated_products,best_selling_products,sale_products,recent_products' ),
					'content_element' => true,
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'js_view' => 'VcColumnView',
					'show_settings_on_create' => true,
					'params' => array(
						array(
							'save_always'=>true,
							'type' => 'textfield',
							'heading' => __( 'Carousel Title', 'sitesao' ),
							'param_name' => 'title',
							'description' => __(
								'Enter text which will be used as widget title. Leave blank if no title is needed.',
								'sitesao' ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'heading' => __( 'Title color', 'sitesao' ),
							'param_name' => 'title_color',
							'default',
							'dependency' => array( 'element' => "title", 'not_empty' => true ),
							'value' => array(
								__( 'Default', 'sitesao' ) => 'default',
								__( 'Primary', 'sitesao' ) => 'primary',
								__( 'Success', 'sitesao' ) => 'success',
								__( 'Info', 'sitesao' ) => 'info',
								__( 'Warning', 'sitesao' ) => 'warning',
								__( 'Danger', 'sitesao' ) => 'danger' ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'heading' => __( 'Transition', 'sitesao' ),
							'param_name' => 'fx',
							'std' => 'scroll',
							'value' => array(
								'Scroll' => 'scroll',
								'Directscroll' => 'directscroll',
								'Fade' => 'fade',
								'Cross fade' => 'crossfade',
								'Cover' => 'cover',
								'Cover fade' => 'cover-fade',
								'Uncover' => 'cover-fade',
								'Uncover fade' => 'uncover-fade' ),
							'description' => __( 'Indicates which effect to use for the transition.', 'sitesao' ) ),
		
						// array(
						// 'param_name' => 'scroll_item',
						// 'heading' => __( 'The number of items to scroll', 'sitesao' ),
						// 'type' => 'ui_slider',
						// 'holder' => $this->param_holder,
						// 'value' => '1',
						// 'data_min' => '1',
						// 'data_max' => '6',
						// ),
						array(
							'save_always'=>true,
							'param_name' => 'scroll_speed',
							'heading' => __( 'Transition Scroll Speed (ms)', 'sitesao' ),
							'type' => 'ui_slider',
							'value' => '700',
							'data_min' => '100',
							'data_step' => '100',
							'data_max' => '3000' ),
		
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Easing", 'sitesao' ),
							"param_name" => "easing",
							'std' => 'linear',
							"value" => array(
								'linear' => 'linear',
								'swing' => 'swing',
								'easeInQuad' => 'easeInQuad',
								'easeOutQuad' => 'easeOutQuad',
								'easeInOutQuad' => 'easeInOutQuad',
								'easeInCubic' => 'easeInCubic',
								'easeOutCubic' => 'easeOutCubic',
								'easeInOutCubic' => 'easeInOutCubic',
								'easeInQuart' => 'easeInQuart',
								'easeOutQuart' => 'easeOutQuart',
								'easeInOutQuart' => 'easeInOutQuart',
								'easeInQuint' => 'easeInQuint',
								'easeOutQuint' => 'easeOutQuint',
								'easeInOutQuint' => 'easeInOutQuint',
								'easeInExpo' => 'easeInExpo',
								'easeOutExpo' => 'easeOutExpo',
								'easeInOutExpo' => 'easeInOutExpo',
								'easeInSine' => 'easeInSine',
								'easeOutSine' => 'easeOutSine',
								'easeInOutSine' => 'easeInOutSine',
								'easeInCirc' => 'easeInCirc',
								'easeOutCirc' => 'easeOutCirc',
								'easeInOutCirc' => 'easeInOutCirc',
								'easeInElastic' => 'easeInElastic',
								'easeOutElastic' => 'easeOutElastic',
								'easeInOutElastic' => 'easeInOutElastic',
								'easeInBack' => 'easeInBack',
								'easeOutBack' => 'easeOutBack',
								'easeInOutBack' => 'easeInOutBack',
								'easeInBounce' => 'easeInBounce',
								'easeOutBounce' => 'easeOutBounce',
								'easeInOutBounce' => 'easeInOutBounce' ),
							"description" => __(
								"Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these.",
								'sitesao' ) ),
						array(
							'save_always'=>true,
							'type' => 'checkbox',
							'heading' => __( 'Item no Padding ?', 'sitesao' ),
							'param_name' => 'no_padding',
							'description' => __( 'Item No Padding', 'sitesao' ),
							'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ),
						array(
							'save_always'=>true,
							'type' => 'checkbox',
							'heading' => __( 'Autoplay ?', 'sitesao' ),
							'param_name' => 'auto_play',
							'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ),
						array(
							'save_always'=>true,
							'type' => 'checkbox',
							'heading' => __( 'Hide Slide Pagination ?', 'sitesao' ),
							'param_name' => 'hide_pagination',
							'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ),
						array(
							'save_always'=>true,
							'type' => 'checkbox',
							'heading' => __( 'Hide Previous/Next Control ?', 'sitesao' ),
							'param_name' => 'hide_control',
							'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'heading' => __( 'Previous/Next Control Position', 'sitesao' ),
							'param_name' => 'control_position',
							'std' => 'default',
							'dependency' => array( 'element' => "title", 'not_empty' => true ),
							'value' => array(
								__( 'Default', 'sitesao' ) => 'default',
								__( 'Center with Title', 'sitesao' ) => 'center',
								__( 'Right with Title', 'sitesao' ) => 'right' ) ),
						array(
							'save_always'=>true,
							'param_name' => 'visibility',
							'heading' => __( 'Visibility', 'sitesao' ),
							'type' => 'dropdown',
							'std' => 'all',
							'value' => array(
								__( 'All Devices', 'sitesao' ) => "all",
								__( 'Hidden Phone', 'sitesao' ) => "hidden-phone",
								__( 'Hidden Tablet', 'sitesao' ) => "hidden-tablet",
								__( 'Hidden PC', 'sitesao' ) => "hidden-pc",
								__( 'Visible Phone', 'sitesao' ) => "visible-phone",
								__( 'Visible Tablet', 'sitesao' ) => "visible-tablet",
								__( 'Visible PC', 'sitesao' ) => "visible-pc" ) ),
						array(
							'save_always'=>true,
							'param_name' => 'el_class',
							'heading' => __( '(Optional) Extra class name', 'sitesao' ),
							'type' => 'textfield',
							"description" => __(
								"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",
								'sitesao' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Product", 'sitesao' ),
					"base" => "product",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Display a single product.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ) ) ) );
			vc_map(
				array(
					"name" => __( "Special Product", 'sitesao' ),
					"base" => "dh_wc_special_product",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Display a single Special product.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ),
						array(
							'save_always'=>true,
							"type" => "attach_image",
							"heading" => __( "Product Image", 'sitesao' ),
							"param_name" => "product_image",
							"value" => "",
							"description" => __(
								"Select image from media library. If blank will use default Product Image",
								'sitesao' ) ) ) )
		
			);
			vc_map(
				array(
					"name" => __( "Product Page", 'sitesao' ),
					"base" => "product_page",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Show a single product page.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ) ) ) );
			vc_map(
				array(
					"name" => __( "Product Masonry", 'sitesao' ),
					"base" => "dh_wc_product_mansory",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List products with Masonry layout.', 'sitesao' ),
					"params" => array(
						array( 
							'save_always'=>true,
							'type' => 'autocomplete',
							"heading" => __( "Categories", 'sitesao' ), 
							'settings' => array( 'multiple' => true, 'sortable' => true ),
							"param_name" => "category", 
							"admin_label" => true ), 
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"admin_label" => true,
							"value" => 12 ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'param_name' => 'show',
							'heading' => __( 'Show', 'sitesao' ),
							'value' => array(
								__( 'All Products', 'sitesao' ) => '',
								__( 'Featured Products', 'sitesao' ) => 'featured',
								__( 'On-sale Products', 'sitesao' ) => 'onsale' ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'param_name' => 'orderby',
							'heading' => __( 'Order by', 'sitesao' ),
							'std' => 'date',
							'value' => array(
								__( 'Date', 'sitesao' ) => 'date',
								__( 'Price', 'sitesao' ) => 'price',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Sales', 'sitesao' ) => 'sales' ) ),
						array(
							'save_always'=>true,
							'type' => 'dropdown',
							'param_name' => 'order',
							'heading' => _x( 'Order', 'Sorting order', 'sitesao' ),
							'std' => 'asc',
							'value' => array( __( 'ASC', 'sitesao' ) => 'asc', __( 'DESC', 'sitesao' ) => 'desc' ) ),
						array(
							'save_always'=>true,
							'param_name' => 'hide_all_filter',
							'heading' => __( 'Hide All Filter Products', 'sitesao' ),
							'type' => 'checkbox',
							'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
						array(
							'save_always'=>true,
							'param_name' => 'hide_free',
							'heading' => __( 'Hide free products', 'sitesao' ),
							'type' => 'checkbox',
							'value' => array( __( 'Yes,please', 'sitesao'  ) => '1' ) ),
						array(
							'save_always'=>true,
							'param_name' => 'show_hidden',
							'heading' => __( 'Show hidden products', 'sitesao' ),
							'type' => 'checkbox',
							'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Product Category", 'sitesao' ),
					"base" => "product_category",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List products in a category shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "product_category",
							"heading" => __( "Categories", 'sitesao' ),
							"param_name" => "category",
							'save_always' => true,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							'save_always' => true,
							"admin_label" => true,
							"value" => 12 ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							'save_always' => true,
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							'save_always' => true,
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							'class' => 'dhwc-woo-product-page-dropdown',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'save_always' => true,
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ),
		
						array(
							'save_always'=>true,
							"type" => "dropdown",
							'save_always' => true,
							"class" => "",
							"heading" => __( "Query type", 'sitesao' ),
							"param_name" => "operator",
							'std' => 'IN',
							"value" => array(
								__( 'IN', 'sitesao' ) => 'IN',
								__( 'AND', 'sitesao' ) => 'AND',
								__( 'NOT IN', 'sitesao' ) => 'NOT IN' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Product Categories", 'sitesao' ),
					"base" => "product_categories",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List all (or limited) product categories.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "product_category",
							"heading" => __( "Categories", 'sitesao' ),
							"param_name" => "ids",
							'select_field' => 'id',
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Number", 'sitesao' ),
							"param_name" => "number",
							"admin_label" => true,
							'description' => __(
								'You can specify the number of category to show (Leave blank to display all categories).',
								'sitesao' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => '1',
							"heading" => __( "Hide Empty", 'sitesao' ),
							"param_name" => "hide_empty",
							"value" => array( __( 'Yes', 'sitesao' ) => '1', __( 'No', 'sitesao' ) => '0' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Product Categories Grid", 'sitesao' ),
					"base" => "product_categories_grid",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Display categories with grid layout.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "product_category",
							"heading" => __( "Categories", 'sitesao' ),
							"param_name" => "ids",
							'select_field' => 'id',
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => '1',
							"heading" => __( "Grid Style", 'sitesao' ),
							"param_name" => "style",
							'admin_label' => true,
							"value" => array( __( 'Style 1', 'sitesao' ) => '1', __( 'Style 2', 'sitesao' ) => '2' ,__( 'Style 3', 'sitesao' ) => '3' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => '1',
							"heading" => __( "Grid Gutter", 'sitesao' ),
							"param_name" => "gutter",
							"value" => array( __( 'Yes', 'sitesao' ) => '1', __( 'No', 'sitesao' ) => '0' ) ),
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Number", 'sitesao' ),
							"param_name" => "number",
							"admin_label" => true,
							'description' => __(
								'You can specify the number of category to show (Leave blank to display all categories).',
								'sitesao' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Category Order', 'sitesao' ) => 'order',
								__( 'Name', 'sitesao' ) => 'name',
								__( 'Term ID', 'sitesao' ) => 'term_id',
								__( 'Taxonomy Count', 'sitesao' ) => 'count',
								__( 'ID', 'sitesao' ) => 'id', ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => '1',
							"heading" => __( "Hide Empty", 'sitesao' ),
							"param_name" => "hide_empty",
							"value" => array( __( 'Yes', 'sitesao' ) => '1', __( 'No', 'sitesao' ) => '0' ) ) ) ) );
			if ( taxonomy_exists( 'product_lookbook' ) ) {
				vc_map(
					array(
						"name" => __( "Product Lookbooks", 'sitesao' ),
						"base" => "dh_wc_product_lookbooks",
						"category" => __( "WooCommerce", 'sitesao' ),
						"icon" => "dh-vc-icon-dh_woo",
						"class" => "dh-vc-element dh-vc-element-dh_woo",
						'description' => __( 'List all products by lookbooks.', 'sitesao' ),
						"params" => array(
							array(
								"type" => "product_lookbook",
								"heading" => __( "Lookbooks", 'sitesao' ),
								"param_name" => "ids",
								"admin_label" => true ),
							array(
								"type" => "dropdown",
								"heading" => __( "Style", 'sitesao' ),
								"param_name" => "style",
								"std" => 'slider',
								"admin_label" => true,
								"value" => array( __( 'Slider', 'sitesao' ) => 'slider', __( 'Grid', 'sitesao' ) => 'grid' ) ) ) ) );
			}
			if ( taxonomy_exists( 'product_brand' ) ) {
				vc_map(
					array(
						"name" => __( "Product Brands", 'sitesao' ),
						"base" => "dhwc_product_brands",
						"category" => __( "WooCommerce", 'sitesao' ),
						"icon" => "dh-vc-icon-dh_woo",
						"class" => "dh-vc-element dh-vc-element-dh_woo",
						'description' => __( 'List all (or limited) product brands.', 'sitesao' ),
						"params" => array(
							array(
								'save_always'=>true,
								"type" => "product_brand",
								"heading" => __( "Brands", 'sitesao' ),
								"param_name" => "ids",
								"admin_label" => true ),
							array(
								'save_always'=>true,
								"type" => "textfield",
								"heading" => __( "Number", 'sitesao' ),
								"param_name" => "number",
								"admin_label" => true,
								'description' => __(
									'You can specify the number of brand to show (Leave blank to display all brands).',
									'sitesao' ) ),
							array(
								'save_always'=>true,
								"type" => "dropdown",
								"heading" => __( "Columns", 'sitesao' ),
								"param_name" => "columns",
								"std" => 4,
								"admin_label" => true,
								"value" => array( 2, 3, 4, 5, 6 ) ),
							array(
								'save_always'=>true,
								"type" => "dropdown",
								"heading" => __( "Products Ordering", 'sitesao' ),
								"param_name" => "orderby",
								'std' => 'date',
								"value" => array(
									__( 'Publish Date', 'sitesao' ) => 'date',
									__( 'Modified Date', 'sitesao' ) => 'modified',
									__( 'Random', 'sitesao' ) => 'rand',
									__( 'Alphabetic', 'sitesao' ) => 'title',
									__( 'Popularity', 'sitesao' ) => 'popularity',
									__( 'Rate', 'sitesao' ) => 'rating',
									__( 'Price', 'sitesao' ) => 'price' ) ),
							array(
								'save_always'=>true,
								"type" => "dropdown",
								"class" => "",
								'std' => 'ASC',
								"heading" => __( "Ascending or Descending", 'sitesao' ),
								"param_name" => "order",
								"value" => array(
									__( 'Ascending', 'sitesao' ) => 'ASC',
									__( 'Descending', 'sitesao' ) => 'DESC' ) ),
							array(
								'save_always'=>true,
								"type" => "dropdown",
								"class" => "",
								'std' => '1',
								"heading" => __( "Hide Empty", 'sitesao' ),
								"param_name" => "hide_empty",
								"value" => array( __( 'Yes', 'sitesao' ) => '1', __( 'No', 'sitesao' ) => '0' ) ) ) ) );
			}
			vc_map(
				array(
					"name" => __( "Add To Cart", 'sitesao' ),
					"base" => "add_to_cart",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Display a single product price + cart button.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ) ) ) );
			vc_map(
				array(
					"name" => __( "Add To Cart URL", 'sitesao' ),
					"base" => "add_to_cart_url",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Show URL on the add to cart button.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ) ) ) );
			vc_map(
				array(
					"name" => __( "Add To Cart URL", 'sitesao' ),
					"base" => "add_to_cart",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Get the add to cart URL for a product.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select product", 'sitesao' ),
							'single_select' => true,
							'admin_label' => true,
							"param_name" => "id" ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Products", 'sitesao' ),
					"base" => "products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List multiple products shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select products", 'sitesao' ),
							"param_name" => "ids",
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Products Grid", 'sitesao' ),
					"base" => "dh_wc_products_grid",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List multiple products grid shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							'type' => 'textarea_html',
							'holder' => 'div',
							'heading' => __( 'Text Description', 'js_composer' ),
							'param_name' => 'content',
							'value' => __(
								'<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>',
								'js_composer' ) ),
						array(
							'save_always'=>true,
							"type" => "attach_image",
							"heading" => __( "Image Description", 'sitesao' ),
							"param_name" => "image_desc",
							"value" => "",
							"description" => __( "Select image from media library.", 'sitesao' ) ),
						array(
							'save_always'=>true,
							"type" => "products_ajax",
							"heading" => __( "Select products", 'sitesao' ),
							"param_name" => "ids",
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"admin_label" => true,
							"value" => 12 ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Recent Products", 'sitesao' ),
					"base" => "recent_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Recent Products shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"admin_label" => true,
							"value" => 12 ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Sale Products", 'sitesao' ),
					"base" => "sale_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List all products on sale.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Best Selling Products", 'sitesao' ),
					"base" => "best_selling_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List best selling products on sale.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Top Rated Products", 'sitesao' ),
					"base" => "top_rated_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List top rated products on sale.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Featured Products", 'sitesao' ),
					"base" => "featured_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Output featured products.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"class" => "",
							'std' => 'ASC',
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Product Attribute", 'sitesao' ),
					"base" => "product_attribute",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'List products with an attribute shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "product_attribute",
							"heading" => __( "Attribute", 'sitesao' ),
							"param_name" => "attribute",
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "product_attribute_filter",
							"heading" => __( "Filter", 'sitesao' ),
							"param_name" => "filter",
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							'std' => 'date',
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							'std' => 'ASC',
							"class" => "",
							"heading" => __( "Ascending or Descending", 'sitesao' ),
							"param_name" => "order",
							"value" => array(
								__( 'Ascending', 'sitesao' ) => 'ASC',
								__( 'Descending', 'sitesao' ) => 'DESC' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Related products", 'sitesao' ),
					"base" => "related_products",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Output the related products.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Product Per Page", 'sitesao' ),
							"param_name" => "posts_per_page",
							"value" => 12,
							"admin_label" => true ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Columns", 'sitesao' ),
							"param_name" => "columns",
							"std" => 4,
							"admin_label" => true,
							"value" => array( '', 1, 2, 3, 4, 5, 6 ) ),
						array(
							'save_always'=>true,
							"type" => "dropdown",
							"heading" => __( "Products Ordering", 'sitesao' ),
							"param_name" => "orderby",
							"std" => "rand",
							"value" => array(
								__( 'Publish Date', 'sitesao' ) => 'date',
								__( 'Modified Date', 'sitesao' ) => 'modified',
								__( 'Random', 'sitesao' ) => 'rand',
								__( 'Alphabetic', 'sitesao' ) => 'title',
								__( 'Popularity', 'sitesao' ) => 'popularity',
								__( 'Rate', 'sitesao' ) => 'rating',
								__( 'Price', 'sitesao' ) => 'price' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "Shop Messages", 'sitesao' ),
					"base" => "shop_messages",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'show_settings_on_create' => false,
					'description' => __( 'Show messages.', 'sitesao' ),
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map(
				array(
					"name" => __( "Order Tracking", 'sitesao' ),
					"base" => "woocommerce_order_tracking",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'show_settings_on_create' => false,
					'description' => __( 'Order tracking page shortcode.', 'sitesao' ),
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map(
				array(
					"name" => __( "Cart", 'sitesao' ),
					"base" => "woocommerce_cart",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'show_settings_on_create' => false,
					'description' => __( 'Cart page shortcode.', 'sitesao' ),
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
		
			vc_map(
				array(
					"name" => __( "Checkout", 'sitesao' ),
					"base" => "woocommerce_checkout",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'show_settings_on_create' => false,
					'description' => __( 'Checkout page shortcode.', 'sitesao' ),
					"params" => array( array( 'type' => 'nullfield', 'param_name' => 'nullfield' ) ) ) );
			vc_map(
				array(
					"name" => __( "My Account", 'sitesao' ),
					"base" => "woocommerce_my_account",
					"category" => __( "WooCommerce", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'My account shortcode.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							"type" => "textfield",
							"heading" => __( "Number of orders", 'sitesao' ),
							"param_name" => "order_count",
							"admin_label" => true,
							"value" => 12,
							'description' => __(
								'You can specify the number of orders to show (use -1 to display all orders).',
								'sitesao' ) ) ) ) );
		
			// Woocommerce Widgets
			vc_map(
				array(
					"name" => __( "WC Cart", 'sitesao' ),
					"base" => "dh_wc_cart",
					"category" => __( "Woocommerce Widgets", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Woocommerce Widget Cart.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							'type' => 'textfield',
							'heading' => __( 'Widget title', 'sitesao' ),
							'param_name' => 'title',
							'description' => __(
								'What text use as a widget title. Leave blank to use default widget title.',
								'sitesao' ) ),
						array(
							'save_always'=>true,
							'param_name' => 'hide_if_empty',
							'heading' => __( 'Hide if cart is empty', 'sitesao' ),
							'type' => 'checkbox',
							'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
						array(
							'save_always'=>true,
							'type' => 'textfield',
							'heading' => __( 'Extra class name', 'sitesao' ),
							'param_name' => 'el_class',
							'description' => __(
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
								'sitesao' ) ) ) ) );
			vc_map(
				array(
					"name" => __( "WC Layered Nav Filters", 'sitesao' ),
					"base" => "dh_wc_layered_nav_filters",
					"category" => __( "Woocommerce Widgets", 'sitesao' ),
					"icon" => "dh-vc-icon-dh_woo",
					"class" => "dh-vc-element dh-vc-element-dh_woo",
					'description' => __( 'Woocommerce Widget Layered Nav Filters.', 'sitesao' ),
					"params" => array(
						array(
							'save_always'=>true,
							'type' => 'textfield',
							'value' => __( 'Active Filters', 'sitesao' ),
							'heading' => __( 'Widget title', 'sitesao' ),
							'param_name' => 'title',
							'description' => __(
								'What text use as a widget title. Leave blank to use default widget title.',
								'sitesao' ) ),
						array(
							'save_always'=>true,
							'type' => 'textfield',
							'heading' => __( 'Extra class name', 'sitesao' ),
							'param_name' => 'el_class',
							'description' => __(
								'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
								'sitesao' ) ) ) ) );
		
			$attribute_array = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies )
				foreach ( $attribute_taxonomies as $tax )
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) )
						$attribute_array[$tax->attribute_name] = $tax->attribute_name;
		
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Layered Nav", 'sitesao' ),
							"base" => "dh_wc_layered_nav",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Layered Nav.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Filter by', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'attribute',
									'heading' => __( 'Attribute', 'sitesao' ),
									'value' => $attribute_array ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'display_type',
									'std' => 'list',
									'heading' => __( 'Display type', 'sitesao' ),
									'value' => array( __( 'List', 'sitesao' ) => 'list', __( 'Dropdown', 'sitesao' ) => 'dropdown' ) ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'query_type',
									'heading' => __( 'Query type', 'sitesao' ),
									'std' => 'and',
									'value' => array( __( 'AND', 'sitesao' ) => 'and', __( 'OR', 'sitesao' ) => 'or' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Price Filter", 'sitesao' ),
							"base" => "dh_wc_price_filter",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Price Filter.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Filter by price', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Product Categories", 'sitesao' ),
							"base" => "dh_wc_product_categories",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Product Categories.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Product Categories', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'orderby',
									'heading' => __( 'Order by', 'sitesao' ),
									'std' => 'order',
									'value' => array( __( 'Category Order', 'sitesao' ) => 'order', __( 'Name', 'sitesao' ) => 'name' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'dropdown',
									'heading' => __( 'Show as dropdown', 'sitesao' ),
									'type' => 'checkbox',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'count',
									'heading' => __( 'Show post counts', 'sitesao' ),
									'type' => 'checkbox',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'hierarchical',
									'heading' => __( 'Show hierarchy', 'sitesao' ),
									'type' => 'checkbox',
									'std' => '1',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'show_children_only',
									'heading' => __( 'Only show children of the current category', 'sitesao' ),
									'type' => 'checkbox',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
		
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Product Search", 'sitesao' ),
							"base" => "dh_wc_product_search",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Product Search.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Search Products', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Product Tags", 'sitesao' ),
							"base" => "dh_wc_product_tag_cloud",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Product Tags.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Product Tags', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Products", 'sitesao' ),
							"base" => "dh_wc_products",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Products.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Products', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => 5,
									'heading' => __( 'Number of products to show', 'sitesao' ),
									'param_name' => 'number' ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'show',
									'heading' => __( 'Show', 'sitesao' ),
									'value' => array(
										__( 'All Products', 'sitesao' ) => '',
										__( 'Featured Products', 'sitesao' ) => 'featured',
										__( 'On-sale Products', 'sitesao' ) => 'onsale' ) ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'orderby',
									'std' => 'date',
									'heading' => __( 'Order by', 'sitesao' ),
									'value' => array(
										__( 'Date', 'sitesao' ) => 'date',
										__( 'Price', 'sitesao' ) => 'price',
										__( 'Random', 'sitesao' ) => 'rand',
										__( 'Sales', 'sitesao' ) => 'sales' ) ),
								array(
									'save_always'=>true,
									'type' => 'dropdown',
									'param_name' => 'order',
									'std' => 'asc',
									'heading' => _x( 'Order', 'Sorting order', 'sitesao' ),
									'value' => array( __( 'ASC', 'sitesao' ) => 'asc', __( 'DESC', 'sitesao' ) => 'desc' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'hide_free',
									'heading' => __( 'Hide free products', 'sitesao' ),
									'type' => 'checkbox',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'param_name' => 'show_hidden',
									'heading' => __( 'Show hidden products', 'sitesao' ),
									'type' => 'checkbox',
									'value' => array( __( 'Yes,please', 'sitesao' ) => '1' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Recent Reviews", 'sitesao' ),
							"base" => "dh_wc_recent_reviews",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Recent Reviews.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Recent Reviews', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => 5,
									'heading' => __( 'Number of products to show', 'sitesao' ),
									'param_name' => 'number' ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Recently Viewed", 'sitesao' ),
							"base" => "dh_wc_recently_viewed_products",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Recently Viewed.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Recently Viewed Products', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => 5,
									'heading' => __( 'Number of products to show', 'sitesao' ),
									'param_name' => 'number' ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
					vc_map(
						array(
							'save_always'=>true,
							"name" => __( "WC Top Rated Products", 'sitesao' ),
							"base" => "dh_wc_top_rated_products",
							"category" => __( "Woocommerce Widgets", 'sitesao' ),
							"icon" => "dh-vc-icon-dh_woo",
							"class" => "dh-vc-element dh-vc-element-dh_woo",
							'description' => __( 'Woocommerce Widget Top Rated Products.', 'sitesao' ),
							"params" => array(
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => __( 'Top Rated Products', 'sitesao' ),
									'heading' => __( 'Widget title', 'sitesao' ),
									'param_name' => 'title',
									'description' => __(
										'What text use as a widget title. Leave blank to use default widget title.',
										'sitesao' ) ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'value' => 5,
									'heading' => __( 'Number of products to show', 'sitesao' ),
									'param_name' => 'number' ),
								array(
									'save_always'=>true,
									'type' => 'textfield',
									'heading' => __( 'Extra class name', 'sitesao' ),
									'param_name' => 'el_class',
									'description' => __(
										'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.',
										'sitesao' ) ) ) ) );
		}

		public function add_params() {
			vc_add_param( 
				"vc_row", 
				array( 
					"type" => "dropdown", 
					"group" => __( 'Row Type', 'sitesao' ), 
					"class" => "", 
					"heading" => "Type", 
					'std' => 'full_width', 
					"param_name" => "wrap_type", 
					"value" => array( 
						__( "Full Width", 'sitesao' ) => "full_width", 
						__( "In Container", 'sitesao' ) => "in_container" ) ) );
			
			vc_add_param( 
				"vc_row_inner", 
				array( 
					"type" => "dropdown", 
					"group" => __( 'Row Type', 'sitesao' ), 
					"class" => "", 
					"heading" => "Type", 
					"param_name" => "wrap_type", 
					'std' => 'full_width', 
					"value" => array( 
						__( "Full Width", 'sitesao' ) => "full_width", 
						__( "In Container", 'sitesao' ) => "in_container" ) ) );
			
			$params = array( 
				'dh_instagram' => array( 
					array( 
						'param_name' => 'username', 
						'heading' => __( 'Instagram Username', 'sitesao' ), 
						'description' => '', 
						'type' => 'textfield', 
						'admin_label' => true ), 
					array( 
						'param_name' => 'images_number', 
						'heading' => __( 'Number of Images to Show', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '12' ), 
					array( 
						'param_name' => 'refresh_hour', 
						'heading' => __( 'Check for new images on every (hours)', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '5' ) ), 
				'dh_button' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Text', 'sitesao' ), 
						'holder' => 'button', 
						'class' => 'wpb_button', 
						'admin_label' => true, 
						'param_name' => 'title', 
						'value' => __( 'Button', 'sitesao' ), 
						'description' => __( 'Text on the button.', 'sitesao' ) ), 
					array( 
						'type' => 'href', 
						'heading' => __( 'URL (Link)', 'sitesao' ), 
						'param_name' => 'href', 
						'description' => __( 'Button link.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Target', 'sitesao' ), 
						'param_name' => 'target', 
						'std' => '_self', 
						'value' => array( 
							__( 'Same window', 'sitesao' ) => '_self', 
							__( 'New window', 'sitesao' ) => "_blank" ), 
						'dependency' => array( 
							'element' => 'href', 
							'not_empty' => true, 
							'callback' => 'vc_button_param_target_callback' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', 'sitesao' ), 
						"param_holder_class" => 'dh-btn-style-select', 
						'param_name' => 'style', 
						'value' => self::$button_styles, 
						'description' => __( 'Button style.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Size', 'sitesao' ), 
						'param_name' => 'size', 
						'std' => '', 
						'value' => array( 
							__( 'Default', 'sitesao' ) => '', 
							__( 'Large', 'sitesao' ) => 'lg', 
							__( 'Small', 'sitesao' ) => 'sm', 
							__( 'Extra small', 'sitesao' ) => 'xs', 
							__( 'Custom size', 'sitesao' ) => 'custom' ), 
						'description' => __( 'Button size.', 'sitesao' ) ), 
					array( 
						'param_name' => 'font_size', 
						'heading' => __( 'Font Size (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '14', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '50' ), 
					array( 
						'param_name' => 'border_width', 
						'heading' => __( 'Border Width (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '1', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '20' ), 
					array( 
						'param_name' => 'padding_top', 
						'heading' => __( 'Padding Top (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_right', 
						'heading' => __( 'Padding Right (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_bottom', 
						'heading' => __( 'Padding Bottom (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '6', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'param_name' => 'padding_left', 
						'heading' => __( 'Padding Right (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '30', 
						'data_min' => '0', 
						'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ), 
						'data_max' => '100' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Color', 'sitesao' ), 
						'param_name' => 'color', 
						'std' => 'default', 
						'value' => array( 
							__( 'Default', 'sitesao' ) => 'default', 
							__( 'Primary', 'sitesao' ) => 'primary', 
							__( 'Success', 'sitesao' ) => 'success', 
							__( 'Info', 'sitesao' ) => 'info', 
							__( 'Warning', 'sitesao' ) => 'warning', 
							__( 'Danger', 'sitesao' ) => 'danger', 
							__( 'White', 'sitesao' ) => 'white', 
							__( 'Black', 'sitesao' ) => 'black', 
							__( 'Custom', 'sitesao' ) => 'custom' ), 
						'description' => __( 'Button color.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Background Color', 'sitesao' ), 
						'param_name' => 'background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Border Color', 'sitesao' ), 
						'param_name' => 'border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Text Color', 'sitesao' ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Background Color', 'sitesao' ), 
						'param_name' => 'hover_background_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select background color for button when hover.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Border Color', 'sitesao' ), 
						'param_name' => 'hover_border_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select border color for button when hover.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Hover Text Color', 'sitesao' ), 
						'param_name' => 'hover_text_color', 
						'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ), 
						'description' => __( 'Select text color for button when hover.', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Button Full Width', 'sitesao' ), 
						'param_name' => 'block_button', 
						'value' => array( __( 'Yes, please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Button full width of a parent', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Alignment', 'sitesao' ), 
						'param_name' => 'alignment', 
						'std' => 'left', 
						'value' => array( 
							__( 'Left', 'sitesao' ) => 'left', 
							__( 'Center', 'sitesao' ) => 'center', 
							__( 'Right', 'sitesao' ) => 'right' ), 
						'description' => __( 'Button alignment (Not use for Button full width)', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Show Tooltip/Popover', 'sitesao' ), 
						'param_name' => 'tooltip', 
						'value' => array( 
							__( 'No', 'sitesao' ) => '', 
							__( 'Tooltip', 'sitesao' ) => 'tooltip', 
							__( 'Popover', 'sitesao' ) => 'popover' ), 
						'description' => __( 'Display a tooltip or popover with descriptive text.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip position', 'sitesao' ), 
						'param_name' => 'tooltip_position', 
						'std' => 'top', 
						'value' => array( 
							__( 'Top', 'sitesao' ) => 'top', 
							__( 'Bottom', 'sitesao' ) => 'bottom', 
							__( 'Left', 'sitesao' ) => 'left', 
							__( 'Right', 'sitesao' ) => 'right' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose the display position.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Popover Title', 'sitesao' ), 
						'param_name' => 'tooltip_title', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'popover' ) ) ), 
					array( 
						'type' => 'textarea', 
						'heading' => __( 'Tip/Popover Content', 'sitesao' ), 
						'param_name' => 'tooltip_content', 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Tip/Popover trigger', 'sitesao' ), 
						'param_name' => 'tooltip_trigger', 
						'std' => 'hover', 
						'value' => array( __( 'Hover', 'sitesao' ) => 'hover', __( 'Click', 'sitesao' ) => 'click' ), 
						'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ), 
						'description' => __( 'Choose action to trigger the tooltip.', 'sitesao' ) ) ), 
				'dh_menu_anchor' => array( 
					array( 
						'param_name' => 'name', 
						'heading' => __( 'Name Of Menu Anchor', 'sitesao' ), 
						'type' => 'textfield', 
						'admin_label' => true, 
						"description" => __( 
							"This name will be the id you will have to use in your one page menu.", 
							'sitesao' ) ) ), 
				'dh_video' => array( 
					array( 
						'param_name' => 'type', 
						'heading' => __( 'Video Type', 'sitesao' ), 
						'type' => 'dropdown', 
						'admin_label' => true, 
						'std' => 'inline', 
						'value' => array( __( 'Iniline', 'sitesao' ) => 'inline', __( 'Popup', 'sitesao' ) => 'popup' ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Background', 'sitesao' ), 
						'param_name' => 'background', 
						'dependency' => array( 'element' => "type", 'value' => array( 'popup' ) ), 
						'description' => __( 'Video Background.', 'sitesao' ) ), 
					array( 
						'param_name' => 'video_embed', 
						'heading' => __( 'Embedded Code', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '', 
						'description' => __( 
							'Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds" target="_blank">http://codex.wordpress.org/Embeds</a>.', 
							'sitesao' ) ) ), 
				'dh_post' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Layout', 'sitesao' ), 
						'param_name' => 'layout', 
						'std' => 'default', 
						'admin_label' => true, 
						'value' => array( 
							__( 'Default', 'sitesao' ) => 'default', 
							__( 'Masonry', 'sitesao' ) => 'masonry', 
							__( 'Center', 'sitesao' ) => 'center' ), 
						'std' => 'default', 
						'description' => __( 'Select the layout for the blog shortcode.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', 'sitesao' ), 
						'param_name' => 'columns', 
						'std' => 2, 
						'value' => array( 
							__( '2', 'sitesao' ) => '2', 
							__( '3', 'sitesao' ) => '3', 
							__( '4', 'sitesao' ) => '4' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'grid', 'masonry' ) ), 
						'description' => __( 'Select whether to display the layout in 2, 3 or 4 column.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', 'sitesao' ), 
						'param_name' => 'posts_per_page', 
						'value' => 5, 
						'description' => __( 'Select number of posts per page.Set "-1" to display all', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', 'sitesao' ), 
						'param_name' => 'orderby', 
						'std' => 'latest', 
						'value' => array( 
							__( 'Recent First', 'sitesao' ) => 'latest', 
							__( 'Older First', 'sitesao' ) => 'oldest', 
							__( 'Title Alphabet', 'sitesao' ) => 'alphabet', 
							__( 'Title Reversed Alphabet', 'sitesao' ) => 'ralphabet' ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Categories', 'sitesao' ), 
						'param_name' => 'categories', 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', 'sitesao' ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Exclude Categories', 'sitesao' ), 
						'param_name' => 'exclude_categories', 
						'description' => __( 'Select a category to exclude', 'sitesao' ) ), 
					
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Post Title', 'sitesao' ), 
						'param_name' => 'hide_post_title', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide the post title below the featured', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Link Title To Post', 'sitesao' ), 
						'param_name' => 'link_post_title', 
						'std' => 'yes', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes', __( 'No', 'sitesao' ) => 'no' ), 
						'description' => __( 
							'Choose if the title should be a link to the single post page.', 
							'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Thumbnail', 'sitesao' ), 
						'param_name' => 'hide_thumbnail', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide the post featured', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Excerpt', 'sitesao' ), 
						'param_name' => 'hide_excerpt', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry', 'zigzag', 'center' ) ), 
						'description' => __( 'Hide excerpt', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of words in Excerpt', 'sitesao' ), 
						'param_name' => 'excerpt_length', 
						'value' => 30, 
						'dependency' => array( 'element' => 'hide_excerpt', 'is_empty' => true ), 
						'description' => __( 'The number of words', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Date', 'sitesao' ), 
						'param_name' => 'hide_date', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide date in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Timeline Month', 'sitesao' ), 
						'param_name' => 'hide_month', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'dependency' => array( 'element' => "layout", 'value' => array( 'timeline' ) ), 
						'description' => __( 'Hide timeline month', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Comment', 'sitesao' ), 
						'param_name' => 'hide_comment', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide comment in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Category', 'sitesao' ), 
						'param_name' => 'hide_category', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide category in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Author', 'sitesao' ), 
						'param_name' => 'hide_author', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry', 'zigzag', 'center' ) ), 
						'description' => __( 'Hide author in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Read More Link', 'sitesao' ), 
						'param_name' => 'hide_readmore', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry', 'zigzag', 'center' ) ), 
						'description' => __( 'Choose to hide the link', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Show Tags', 'sitesao' ), 
						'param_name' => 'show_tag', 
						'std' => 'no', 
						'value' => array( __( 'No', 'sitesao' ) => 'no', __( 'Yes', 'sitesao' ) => 'yes' ), 
						'dependency' => array( 
							'element' => "layout", 
							'value' => array( 'default', 'medium', 'grid', 'masonry', 'zigzag', 'center' ) ), 
						'description' => __( 'Choose to show the tags', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'std' => 'page_num', 
						'heading' => __( 'Pagination', 'sitesao' ), 
						'param_name' => 'pagination', 
						'value' => array( 
							__( 'Page Number', 'sitesao' ) => 'page_num', 
							__( 'Load More Button', 'sitesao' ) => 'loadmore', 
							__( 'Infinite Scrolling', 'sitesao' ) => 'infinite_scroll', 
							__( 'No', 'sitesao' ) => 'no' ), 
						'description' => __( 'Choose pagination type.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Load More Button Text', 'sitesao' ), 
						'param_name' => 'loadmore_text', 
						'dependency' => array( 'element' => "pagination", 'value' => array( 'loadmore' ) ), 
						'value' => __( 'Load More', 'sitesao' ) ) ), 
				'dh_post_grid' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Layout Style', 'sitesao' ), 
						'param_name' => 'layout_style', 
						'std' => 'list', 
						'value' => array( __( 'List', 'sitesao' ) => 'list', __( 'Grid', 'sitesao' ) => 'grid' ), 
						'description' => __( 'Select style to display the latest posts.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', 'sitesao' ), 
						'param_name' => 'columns', 
						'std' => 2, 
						'value' => array( 
							__( '2', 'sitesao' ) => '2', 
							__( '3', 'sitesao' ) => '3', 
							__( '4', 'sitesao' ) => '4' ), 
						'dependency' => array( 'element' => "layout_style", 'value' => array( 'grid' ) ), 
						'description' => __( 'Select whether to display the layout in 1, 2, 3 or 4 column.', 'sitesao' ) ), 
					
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Posts Per Page', 'sitesao' ), 
						'param_name' => 'posts_per_page', 
						'value' => 12, 
						'description' => __( 'Select number of posts per page.Set "-1" to display all', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Order by', 'sitesao' ), 
						'param_name' => 'orderby', 
						'value' => array( 
							__( 'Recent First', 'sitesao' ) => 'latest', 
							__( 'Older First', 'sitesao' ) => 'oldest', 
							__( 'Title Alphabet', 'sitesao' ) => 'alphabet', 
							__( 'Title Reversed Alphabet', 'sitesao' ) => 'ralphabet' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Pagination', 'sitesao' ), 
						'param_name' => 'hide_pagination', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide pagination of slider', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Date', 'sitesao' ), 
						'param_name' => 'hide_date', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide date in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Author', 'sitesao' ), 
						'param_name' => 'hide_author', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide author in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Comment', 'sitesao' ), 
						'param_name' => 'hide_comment', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide comment in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Category', 'sitesao' ), 
						'param_name' => 'hide_category', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide Category in post meta info', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Excerpt', 'sitesao' ), 
						'param_name' => 'hide_excerpt', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ), 
						'description' => __( 'Hide excerpt', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of words in Excerpt', 'sitesao' ), 
						'param_name' => 'excerpt_length', 
						'value' => 30, 
						'dependency' => array( 'element' => 'hide_excerpt', 'is_empty' => true ), 
						'description' => __( 'The number of words', 'sitesao' ) ), 
					array( 
						'type' => 'post_category', 
						'heading' => __( 'Categories', 'sitesao' ), 
						'param_name' => 'categories', 
						'admin_label' => true, 
						'description' => __( 'Select a category or leave blank for all', 'sitesao' ) ) ), 
				'dh_mailchimp' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', 'sitesao' ), 
						'param_name' => 'title', 
						'description' => __( 
							'Enter text which will be used as widget title. Leave blank if no title is needed.', 
							'sitesao' ) ) ), 
				'dh_slider' => array(), 
				'dh_carousel' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Carousel Title', 'sitesao' ), 
						'param_name' => 'title', 
						'description' => __( 
							'Enter text which will be used as widget title. Leave blank if no title is needed.', 
							'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Transition', 'sitesao' ), 
						'param_name' => 'fx', 
						'std' => 'scroll', 
						'value' => array( 
							'Scroll' => 'scroll', 
							'Directscroll' => 'directscroll', 
							'Fade' => 'fade', 
							'Cross fade' => 'crossfade', 
							'Cover' => 'cover', 
							'Cover fade' => 'cover-fade', 
							'Uncover' => 'cover-fade', 
							'Uncover fade' => 'uncover-fade' ), 
						'description' => __( 'Indicates which effect to use for the transition.', 'sitesao' ) ), 
					array( 
						'param_name' => 'visible', 
						'heading' => __( 'The number of visible items', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '1', 
						'data_min' => '1', 
						'data_max' => '6' ), 
					array( 
						'param_name' => 'scroll_speed', 
						'heading' => __( 'Transition Scroll Speed (ms)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '700', 
						'data_min' => '100', 
						'data_step' => '100', 
						'data_max' => '3000' ), 
					
					array( 
						"type" => "dropdown", 
						"heading" => __( "Easing", 'sitesao' ), 
						"param_name" => "easing", 
						'std' => 'linear', 
						"value" => array( 
							'linear' => 'linear', 
							'swing' => 'swing', 
							'easeInQuad' => 'easeInQuad', 
							'easeOutQuad' => 'easeOutQuad', 
							'easeInOutQuad' => 'easeInOutQuad', 
							'easeInCubic' => 'easeInCubic', 
							'easeOutCubic' => 'easeOutCubic', 
							'easeInOutCubic' => 'easeInOutCubic', 
							'easeInQuart' => 'easeInQuart', 
							'easeOutQuart' => 'easeOutQuart', 
							'easeInOutQuart' => 'easeInOutQuart', 
							'easeInQuint' => 'easeInQuint', 
							'easeOutQuint' => 'easeOutQuint', 
							'easeInOutQuint' => 'easeInOutQuint', 
							'easeInExpo' => 'easeInExpo', 
							'easeOutExpo' => 'easeOutExpo', 
							'easeInOutExpo' => 'easeInOutExpo', 
							'easeInSine' => 'easeInSine', 
							'easeOutSine' => 'easeOutSine', 
							'easeInOutSine' => 'easeInOutSine', 
							'easeInCirc' => 'easeInCirc', 
							'easeOutCirc' => 'easeOutCirc', 
							'easeInOutCirc' => 'easeInOutCirc', 
							'easeInElastic' => 'easeInElastic', 
							'easeOutElastic' => 'easeOutElastic', 
							'easeInOutElastic' => 'easeInOutElastic', 
							'easeInBack' => 'easeInBack', 
							'easeOutBack' => 'easeOutBack', 
							'easeInOutBack' => 'easeInOutBack', 
							'easeInBounce' => 'easeInBounce', 
							'easeOutBounce' => 'easeOutBounce', 
							'easeInOutBounce' => 'easeInOutBounce' ), 
						"description" => __( 
							"Select the animation easing you would like for slide transitions <a href=\"http://jqueryui.com/resources/demos/effect/easing.html\" target=\"_blank\"> Click here </a> to see examples of these.", 
							'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Item no Padding ?', 'sitesao' ), 
						'param_name' => 'no_padding', 
						'description' => __( 'Item No Padding', 'sitesao' ), 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Autoplay ?', 'sitesao' ), 
						'param_name' => 'auto_play', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Slide Pagination ?', 'sitesao' ), 
						'param_name' => 'hide_pagination', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Previous/Next Control ?', 'sitesao' ), 
						'param_name' => 'hide_control', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ) ), 
				'dh_wc_special_product' => array(), 
				'dh_carousel_item' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', 'sitesao' ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', 'sitesao' ) ) ), 
				'dh_testimonial' => array( 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Background Transparent?', 'sitesao' ), 
						'param_name' => 'background_transparent', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Color', 'sitesao' ), 
						'param_name' => 'color', 
						'description' => __( 'Custom color.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Columns', 'sitesao' ), 
						'param_name' => 'columns', 
						'std' => '1', 
						'value' => array( __( '1 Column', 'sitesao' ) => '1', __( '2 Columns', 'sitesao' ) => '2' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', 'sitesao' ), 
						'param_name' => 'style', 
						'std' => 'style-1', 
						'value' => array( 
							__( 'Style 1', 'sitesao' ) => 'style-1', 
							__( 'Style 2', 'sitesao' ) => 'style-2' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Transition', 'sitesao' ), 
						'param_name' => 'fx', 
						'std' => 'scroll', 
						'value' => array( 
							'Scroll' => 'scroll', 
							'Directscroll' => 'directscroll', 
							'Fade' => 'fade', 
							'Cross fade' => 'crossfade', 
							'Cover' => 'cover', 
							'Cover fade' => 'cover-fade', 
							'Uncover' => 'cover-fade', 
							'Uncover fade' => 'uncover-fade' ), 
						'description' => __( 'Indicates which effect to use for the transition.', 'sitesao' ) ) ), 
				'dh_testimonial_item' => array( 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', 'sitesao' ), 
						'param_name' => 'title', 
						'description' => __( 'Item title.', 'sitesao' ) ), 
					array( 
						'type' => 'textarea_safe', 
						'holder' => 'div', 
						'heading' => __( 'Text', 'sitesao' ), 
						'param_name' => 'text', 
						'value' => __( 
							'I am testimonial. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 
							'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Author', 'sitesao' ), 
						'param_name' => 'author', 
						'description' => __( 'Testimonial author.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Company', 'sitesao' ), 
						'param_name' => 'company', 
						'description' => __( 'Author company.', 'sitesao' ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Avatar', 'sitesao' ), 
						'param_name' => 'avatar', 
						'description' => __( 'Avatar author.', 'sitesao' ) ) ), 
				'dh_counter' => array( 
					array( 
						'param_name' => 'speed', 
						'heading' => __( 'Counter Speed', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '2000' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number', 'sitesao' ), 
						'param_name' => 'number', 
						'description' => __( 'Enter the number.', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Format number displayed ?', 'sitesao' ), 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'param_name' => 'format', 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Thousand Separator', 'sitesao' ), 
						'param_name' => 'thousand_sep', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => ',', 
						'description' => __( 'This sets the thousand separator of displayed number.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Decimal Separator', 'sitesao' ), 
						'param_name' => 'decimal_sep', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => '.', 
						'description' => __( 'This sets the decimal separator of displayed number.', 'sitesao' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Number of Decimals', 'sitesao' ), 
						'param_name' => 'num_decimals', 
						'dependency' => array( 'element' => "format", 'not_empty' => true ), 
						'value' => 0, 
						'description' => __( 
							'This sets the number of decimal points shown in displayed number.', 
							'sitesao' ) ), 
					
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Number Color', 'sitesao' ), 
						'param_name' => 'number_color', 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'description' => __( 'Select color for number.', 'sitesao' ) ), 
					array( 
						'param_name' => 'number_font_size', 
						'heading' => __( 'Custom Number Font Size (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '40', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "number", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Units', 'sitesao' ), 
						'param_name' => 'units', 
						'description' => __( 
							'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 
							'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Units Color', 'sitesao' ), 
						'param_name' => 'units_color', 
						'dependency' => array( 'element' => "units", 'not_empty' => true ), 
						'description' => __( 'Select color for number.', 'sitesao' ) ), 
					array( 
						'param_name' => 'units_font_size', 
						'heading' => __( 'Custom Units Font Size (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '30', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "units", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Icon', 'sitesao' ), 
						'param_name' => 'icon', 
						"param_holder_class" => 'dh-font-awesome-select', 
						"value" => dh_font_awesome_options(), 
						'description' => __( 'Button icon.', 'sitesao' ) ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Icon Color', 'sitesao' ), 
						'param_name' => 'icon_color', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'description' => __( 'Select color for icon.', 'sitesao' ) ), 
					array( 
						'param_name' => 'icon_font_size', 
						'heading' => __( 'Custom Icon Size (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '40', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'data_max' => '120' ), 
					array( 
						'type' => 'dropdown', 
						'std' => 'top', 
						'heading' => __( 'Icon Postiton', 'sitesao' ), 
						'param_name' => 'icon_position', 
						'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
						'value' => array( __( 'Top', 'sitesao' ) => 'top', __( 'Left', 'sitesao' ) => 'left' ) ), 
					array( 
						'type' => 'textfield', 
						'heading' => __( 'Title', 'sitesao' ), 
						'param_name' => 'text', 
						'admin_label' => true ), 
					array( 
						'type' => 'colorpicker', 
						'heading' => __( 'Custom Title Color', 'sitesao' ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => "text", 'not_empty' => true ), 
						'description' => __( 'Select color for title.', 'sitesao' ) ), 
					array( 
						'param_name' => 'text_font_size', 
						'heading' => __( 'Custom Title Font Size (px)', 'sitesao' ), 
						'type' => 'ui_slider', 
						'value' => '18', 
						'data_min' => '10', 
						'dependency' => array( 'element' => "text", 'not_empty' => true ), 
						'data_max' => '120' ) ), 
				'dh_countdown' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', 'sitesao' ), 
						'param_name' => 'style', 
						'admin_label' => true, 
						'value' => array( __( 'White', 'sitesao' ) => 'white', __( 'Black', 'sitesao' ) => 'black' ), 
						'description' => __( 'Select style.', 'sitesao' ) ), 
					array( 
						'type' => 'ui_datepicker', 
						'heading' => __( 'Countdown end', 'sitesao' ), 
						'param_name' => 'end', 
						'description' => __( 'Please select day to end.', 'sitesao' ), 
						'value' => '' ) ), 
				'dh_box_feature' => array( 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Style', 'sitesao' ), 
						'param_name' => 'style', 
						'std' => '1', 
						'value' => array( 
							__( 'Style 1', 'sitesao' ) => '1', 
							__( 'Style 2', 'sitesao' ) => "2", 
							__( 'Style 3', 'sitesao' ) => "3", 
							__( 'Style 4', 'sitesao' ) => "4", 
							__( 'Style 5', 'sitesao' ) => "5" ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Content Position', 'sitesao' ), 
						'param_name' => 'content_position', 
						'std' => 'default', 
						'dependency' => array( 'element' => 'style', 'value' => array( '4' ) ), 
						'value' => array( 
							__( 'Default', 'sitesao' ) => 'default', 
							__( 'Top', 'sitesao' ) => "top", 
							__( 'Bottom', 'sitesao' ) => "bottom", 
							__( 'Left', 'sitesao' ) => "left", 
							__( 'Right', 'sitesao' ) => "right", 
							__( 'Full Box', 'sitesao' ) => "full-box" ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Full Box with Primary Soild Background ?', 'sitesao' ), 
						'param_name' => 'primary_background', 
						'dependency' => array( 'element' => 'content_position', 'value' => array( 'full-box' ) ), 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Text color', 'sitesao' ), 
						'param_name' => 'text_color', 
						'dependency' => array( 'element' => 'style', 'value' => array( '5' ) ), 
						'std' => 'white', 
						'value' => array( __( 'White', 'sitesao' ) => "white", __( 'Black', 'sitesao' ) => "black" ) ), 
					array( 
						'type' => 'attach_image', 
						'heading' => __( 'Image Background', 'sitesao' ), 
						'param_name' => 'bg', 
						'description' => __( 'Image Background.', 'sitesao' ) ), 
					array( 
						'type' => 'href', 
						'heading' => __( 'Image URL (Link)', 'sitesao' ), 
						'param_name' => 'href', 
						'description' => __( 'Image Link.', 'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Target', 'sitesao' ), 
						'param_name' => 'target', 
						'std' => '_self', 
						'value' => array( 
							__( 'Same window', 'sitesao' ) => '_self', 
							__( 'New window', 'sitesao' ) => "_blank" ), 
						'dependency' => array( 'element' => 'href', 'not_empty' => true ) ), 
					array( 
						'param_name' => 'link_title', 
						'heading' => __( 'Button Text', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '', 
						'dependency' => array( 'element' => 'style', 'value' => array( '4' ) ), 
						'description' => __( 'Button link text', 'sitesao' ) ), 
					array( 
						'param_name' => 'title', 
						'heading' => __( 'Title', 'sitesao' ), 
						'admin_label' => true, 
						'type' => 'textfield', 
						'value' => '', 
						'description' => __( 'Box Title', 'sitesao' ) ), 
					array( 
						'param_name' => 'sub_title', 
						'heading' => __( 'Sub Title', 'sitesao' ), 
						'type' => 'textfield', 
						'value' => '', 
						'description' => __( 'Box Sub Title', 'sitesao' ) ) ), 
				'dh_client' => array( 
					array( 
						'type' => 'attach_images', 
						'heading' => __( 'Images', 'sitesao' ), 
						'param_name' => 'images', 
						'value' => '', 
						'description' => __( 'Select images from media library.', 'sitesao' ) ), 
					array( 
						'type' => 'exploded_textarea', 
						'heading' => __( 'Custom links', 'sitesao' ), 
						'param_name' => 'custom_links', 
						'description' => __( 
							'Enter links for each image here. Divide links with linebreaks (Enter) . ', 
							'sitesao' ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Display type', 'sitesao' ), 
						'param_name' => 'display', 
						'value' => array( 
							__( 'Slider', 'sitesao' ) => 'slider', 
							__( 'Image grid', 'sitesao' ) => 'grid' ), 
						'description' => __( 'Select display type.', 'sitesao' ) ), 
					array( 
						'type' => 'checkbox', 
						'heading' => __( 'Hide Slide Pagination ?', 'sitesao' ), 
						'param_name' => 'hide_pagination', 
						'dependency' => array( 'element' => 'display', 'value' => array( 'slider' ) ), 
						'value' => array( __( 'Yes,please', 'sitesao' ) => 'yes' ) ), 
					array( 
						'param_name' => 'visible', 
						'heading' => __( 'The number of visible items on a slide or on a grid row', 'sitesao' ), 
						'type' => 'dropdown', 
						'value' => array( 2, 3, 4, 5, 6 ) ), 
					array( 
						'type' => 'dropdown', 
						'heading' => __( 'Image style', 'sitesao' ), 
						'param_name' => 'style', 
						'value' => array( 
							__( 'Normal', 'sitesao' ) => 'normal', 
							__( 'Grayscale and Color on hover', 'sitesao' ) => 'grayscale' ), 
						'description' => __( 'Select image style.', 'sitesao' ) ) ) );
			
			$shortcode_optional_param = array( 
				'dh_wc_special_product', 
				'dh_button', 
				'dh_animation', 
				'dh_post', 
				'dh_post_grid', 
				'dh_instagram', 
				'dh_slider', 
				'dh_carousel', 
				'dh_testimonial', 
				'dh_client', 
				'dh_counter', 
				'dh_countdown' );
			foreach ( $params as $shortcode => $param ) {
				foreach ( $param as $attr ) {
					vc_add_param( $shortcode, $attr );
				}
				if ( in_array( $shortcode, $shortcode_optional_param ) ) {
					foreach ( (array) $this->_get_optional_param() as $optional_param ) {
						vc_add_param( $shortcode, $optional_param );
					}
				}
			}
			
			return;
		}

		public function remove_vc_teaser_meta_box() {
			$post_types = get_post_types( '', 'names' );
			foreach ( $post_types as $post_type ) {
				remove_meta_box( 'vc_teaser', $post_type, 'side' );
			}
			return;
		}

		protected function _get_optional_param() {
			$optional_param = array( 
				array( 
					'param_name' => 'visibility', 
					'heading' => __( 'Visibility', 'sitesao' ), 
					'type' => 'dropdown', 
					'std' => 'all', 
					'value' => array( 
						__( 'All Devices', 'sitesao' ) => "all", 
						__( 'Hidden Phone', 'sitesao' ) => "hidden-phone", 
						__( 'Hidden Tablet', 'sitesao' ) => "hidden-tablet", 
						__( 'Hidden PC', 'sitesao' ) => "hidden-pc", 
						__( 'Visible Phone', 'sitesao' ) => "visible-phone", 
						__( 'Visible Tablet', 'sitesao' ) => "visible-tablet", 
						__( 'Visible PC', 'sitesao' ) => "visible-pc" ) ), 
				array( 
					'param_name' => 'el_class', 
					'heading' => __( '(Optional) Extra class name', 'sitesao' ), 
					'type' => 'textfield', 
					'value' => '', 
					"description" => __( 
						"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
						'sitesao' ) ) );
			return $optional_param;
		}

		public function pricing_table_feature_param( $settings, $value ) {
			$value_64 = base64_decode( $value );
			$value_arr = json_decode( $value_64 );
			if ( empty( $value_arr ) && ! is_array( $value_arr ) ) {
				for ( $i = 0; $i < 2; $i++ ) {
					$option = new stdClass();
					$option->content = '<i class="fa fa-check"></i> I am a feature';
					$value_arr[] = $option;
				}
			}
			$param_line = '';
			$param_line .= '<div class="pricing-table-feature-list clearfix">';
			$param_line .= '<table>';
			$param_line .= '<thead>';
			$param_line .= '<tr>';
			$param_line .= '<td>';
			$param_line .= __( 'Content (<em>Add Arbitrary text or HTML.</em>)', 'sitesao' );
			$param_line .= '</td>';
			$param_line .= '<td>';
			$param_line .= '</td>';
			$param_line .= '</tr>';
			$param_line .= '</thead>';
			$param_line .= '<tbody>';
			if ( is_array( $value_arr ) && ! empty( $value_arr ) ) {
				foreach ( $value_arr as $k => $v ) {
					$param_line .= '<tr>';
					$param_line .= '<td>';
					$param_line .= '<textarea id="content">' . esc_textarea( $v->content ) . '</textarea>';
					$param_line .= '</td>';
					$param_line .= '<td align="left" style="padding:5px;">';
					$param_line .= '<a href="#" class="pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' .
						 __( 'Remove', 'sitesao' ) . '">-</a>';
					$param_line .= '</td>';
					$param_line .= '</tr>';
				}
			}
			$param_line .= '</tbody>';
			$param_line .= '<tfoot>';
			$param_line .= '<tr>';
			$param_line .= '<td colspan="3">';
			$param_line .= '<a href="#" onclick="return pricing_table_feature_add(this);" class="button" title="' .
				 __( 'Add', 'sitesao' ) . '">' . __( 'Add', 'sitesao' ) . '</a>';
			$param_line .= '</td>';
			$param_line .= '</tfoot>';
			$param_line .= '</table>';
			$param_line .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value' .
				 $settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '">';
			$param_line .= '</div>';
			return $param_line;
		}

		public function post_category_param( $settings, $value ) {
			$dependency = function_exists('vc_generate_dependencies_attributes') ?  vc_generate_dependencies_attributes( $settings ) : '';
			$categories = get_categories( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
			
			$class = 'dh-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = array();
			$html[] = '<div class="post_category_param">';
			$html[] = '<select id="' . $settings['param_name'] . '" ' .
				 ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) . ' class="' . $class . '" ' .
				 $dependency . '>';
			$r = array();
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 1;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html[] = dh_walk_category_dropdown_tree( $categories, 0, $r );
			$html[] = '</select>';
			$html[] = '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value dh-chosen-value wpb-textinput" name="' .
				 $settings['param_name'] . '" value="' . $value . '" />';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function dropdown_group_param( $param, $param_value ) {
			$css_option = vc_get_dropdown_option( $param, $param_value );
			$param_line = '';
			$param_line .= '<select name="' . $param['param_name'] .
				 '" class="dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' . $param['param_name'] . ' ' .
				 $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
			foreach ( $param['optgroup'] as $text_opt => $opt ) {
				if ( is_array( $opt ) ) {
					$param_line .= '<optgroup label="' . $text_opt . '">';
					foreach ( $opt as $text_val => $val ) {
						if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
							$text_val = $val;
						}
						$selected = '';
						if ( $param_value !== '' && (string) $val === (string) $param_value ) {
							$selected = ' selected="selected"';
						}
						$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' .
							 htmlspecialchars( $text_val ) . '</option>';
					}
					$param_line .= '</optgroup>';
				} elseif ( is_string( $opt ) ) {
					if ( is_numeric( $text_opt ) && ( is_string( $opt ) || is_numeric( $opt ) ) ) {
						$text_opt = $opt;
					}
					$selected = '';
					if ( $param_value !== '' && (string) $opt === (string) $param_value ) {
						$selected = ' selected="selected"';
					}
					$param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' .
						 htmlspecialchars( $text_opt ) . '</option>';
				}
			}
			$param_line .= '</select>';
			return $param_line;
		}

		public function nullfield_param( $settings, $value ) {
			return '';
		}

		public function product_attribute_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select name= "' . $settings['param_name'] . '" data-placeholder="' .
				 __( 'Select Attibute', 'sitesao' ) .
				 '" class="dh-product-attribute dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$output .= '<option value="' . esc_attr( $name ) . '"' . selected( $value, $name, false ) .
								 '>' . $attr->attribute_name . '</option>';
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			return $output;
		}

		public function product_attribute_filter_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$args = array( 'orderby' => 'name', 'hide_empty' => false );
			$filter_ids = explode( ',', $value );
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple"  data-placeholder="' .
				 __( 'Select Attibute Filter', 'sitesao' ) .
				 '" class="dh-product-attribute-filter dh-chosen-multiple-select dh-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$terms = get_terms( $name, $args );
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$v = $term->slug;
									$output .= '<option data-attr="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) .
										 '"' . selected( in_array( $v, $filter_ids ), true, false ) . '>' .
										 esc_html( $term->name ) . '</option>';
								}
							}
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_brand_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$brands_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$brands = get_terms( 'product_brand', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				 __( 'Select brands', 'sitesao' ) . '" class="dh-chosen-multiple-select dh-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $brands ) ) {
				foreach ( $brands as $brand ) :
					$output .= '<option value="' . esc_attr( $brand->term_id ) . '"' .
						 selected( in_array( $brand->term_id, $brands_slugs ), true, false ) . '>' .
						 esc_html( $brand->name ) . '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_lookbook_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$lookbook_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$lookbooks = get_terms( 'product_lookbook', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				 __( 'Select lookbooks', 'sitesao' ) . '" class="dh-chosen-multiple-select dh-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $lookbooks ) ) {
				foreach ( $lookbooks as $lookbook ) :
					$output .= '<option value="' . esc_attr( $lookbook->term_id ) . '"' .
						 selected( in_array( $lookbook->term_id, $lookbook_slugs ), true, false ) . '>' .
						 esc_html( $lookbook->name ) . '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_category_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$category_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$multiple = isset($settings['multiple']) && $settings['multiple'] == false ? '':' multiple="multiple"';
			$categories = get_terms( 'product_cat', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" '.$multiple.' data-placeholder="' .
				 __( 'Select categories', 'sitesao' ) . '" class="dh-chosen-multiple-select dh-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) :
					$s = isset( $settings['select_field'] ) ? $cat->term_id : $cat->slug;
					$output .= '<option value="' . esc_attr( $s ) . '"' .
						 selected( in_array( $s, $category_slugs ), true, false ) . '>' . esc_html( $cat->name ) .
						 '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function products_ajax_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$product_ids = array();
			if ( ! empty( $value ) )
				$product_ids = array_map( 'absint', explode( ',', $value ) );
			
			$output = '<select data-placeholder="' . __( 'Search for a product...', 'sitesao' ) . '" id= "' .
				 $settings['param_name'] . '" ' . ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) .
				 ' class="dh-chosen-multiple-select dh-chosen-ajax-select-product ' . $settings['param_name'] . ' ' .
				 $settings['type'] . '">';
			if ( isset( $settings['single_select'] ) ) {
				$output .= '<option value=""></option>';
			}
			if ( ! empty( $product_ids ) ) {
				foreach ( $product_ids as $product_id ) {
					$product = get_product( $product_id );
					if ( $product->get_sku() ) {
						$identifier = $product->get_sku();
					} else {
						$identifier = '#' . $product->get_id();
					}
					
					$product_name = sprintf( __( '%s &ndash; %s', 'sitesao' ), $identifier, $product->get_title() );
					
					$output .= '<option value="' . esc_attr( $product_id ) . '" selected="selected">' .
						 esc_html( $product_name ) . '</option>';
				}
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			
			return $output;
		}

		public function ui_datepicker_param( $param, $param_value ) {
			$param_line = '';
			$value = $param_value;
			$value = htmlspecialchars( $value );
			$param_line .= '<input id="' . $param['param_name'] . '" name="' . $param['param_name'] .
				 '" readonly class="wpb_vc_param_value wpb-textinput ' . $param['param_name'] . ' ' . $param['type'] .
				 '" type="text" value="' . $value . '"/>';
			if ( ! defined( 'DH_UISLDER_PARAM' ) ) {
				define( 'DH_UISLDER_PARAM', 1 );
				$param_line .= '<link media="all" type="text/css" href="' . DHINC_ASSETS_URL .
					 '/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css?ver=1.10.0" rel="stylesheet" />';
			}
			$param_line .= '<script>
					jQuery(function() {
					jQuery( "#' . $param['param_name'] . '" ).datepicker({showButtonPanel: true});
					});</script>	
				';
			return $param_line;
		}

		public function ui_slider_param( $settings, $value ) {
			$data_min = ( isset( $settings['data_min'] ) && ! empty( $settings['data_min'] ) ) ? 'data-min="' .
				 absint( $settings['data_min'] ) . '"' : 'data-min="0"';
			$data_max = ( isset( $settings['data_max'] ) && ! empty( $settings['data_max'] ) ) ? 'data-max="' .
				 absint( $settings['data_max'] ) . '"' : 'data-max="100"';
			$data_step = ( isset( $settings['data_step'] ) && ! empty( $settings['data_step'] ) ) ? 'data-step="' .
				 absint( $settings['data_step'] ) . '"' : 'data-step="1"';
			
			return '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '" type="text" value="' . $value . '"/>';
		}

		public function enqueue_scripts() {
			$pricing_table_feature_tmpl = '';
			$pricing_table_feature_tmpl .= '<tr><td><textarea id="content"></textarea></td><td align="left" style="padding:5px;"><a href="#" class="pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' .
				 esc_attr__( 'Remove', 'sitesao' ) . '">-</a></td></tr>';
			wp_enqueue_style( 
				'dh-vc-admin', 
				DHINC_ASSETS_URL . '/css/vc-admin.css', 
				array( 'vendor-font-awesome', 'vendor-elegant-icon', 'vendor-chosen' ), 
				DHINC_VERSION );
			wp_register_script( 
				'dh-vc-custom', 
				DHINC_ASSETS_URL . '/js/vc-custom.js', 
				array( 'jquery', 'jquery-ui-datepicker' ), 
				DHINC_VERSION, 
				true );
			$dhvcL10n = array( 
				'pricing_table_max_item_msg' => __( 'Pricing Table element only support display 5 item', 'sitesao' ), 
				'item_title' => DHVC_ITEM_TITLE, 
				'add_item_title' => DHVC_ADD_ITEM_TITLE, 
				'move_title' => DHVC_MOVE_TITLE, 
				'pricing_table_feature_tmpl' => $pricing_table_feature_tmpl );
			wp_localize_script( 'dh-vc-custom', 'dhvcL10n', $dhvcL10n );
			wp_enqueue_script( 'dh-vc-custom' );
		}
	}
	new DH_VisualComposer();

	function dh_vc_el_increment() {
		static $count = 0;
		$count++;
		return $count;
	}






























endif;