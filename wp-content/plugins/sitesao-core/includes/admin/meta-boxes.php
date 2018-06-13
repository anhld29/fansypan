<?php
if (! class_exists ( 'DH_Metaboxes' )) :
	class DH_Metaboxes {
		public function __construct() {
			add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
			add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
			
			//add_action( 'admin_enqueue_scripts', array( &$this, 'assets' ) );
			add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ) );
			
		}
		
		public function add_meta_boxes() {
			global $wp_version;
			
			//Post  Video
			$meta_box = array(
				'id' => 'dh-metabox-product-setting',
				'title' => __('Product Settings', 'sitesao'),
				'description' => '',
				'post_type' => 'product',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'type' => 'heading',
						'heading'=>__('Product Custom Tab','sitesao'),
					),
					array(
						'label' => __('Hide Custom Tab', 'sitesao'),
						'description' => __('Hide custom tab for this product.', 'sitesao'),
						'name' => '_dh_hide_custom_tab',
						'type' => 'checkbox',
					),
					array(
						'label' => __('Custom Tab Content', 'sitesao'),
						'description' => __('Please enter customt tab content.', 'sitesao'),
						'name' => '_dh_custom_tab_content',
						'type' => 'textarea',
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
				
			
			// Post Gallery
			$meta_box = array (
					'id' => 'dh-metabox-post-gallery',
					'title' => __ ( 'Gallery Settings', 'sitesao' ),
					'description' =>'',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Gallery', 'sitesao' ),
									'name' => '_dh_gallery',
									'type' => 'gallery',
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Quote
			$meta_box = array(
					'id' => 'dh-metabox-post-quote',
					'title' =>  __('Quote Settings', 'sitesao'),
					'description' => '',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
									'label' =>  __('Quote Content', 'sitesao'),
									'description' => __('Please type the text for your quote here.', 'sitesao'),
									'name' => '_dh_quote',
									'type' => 'textarea',
							)
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Link
			$meta_box = array(
				'id' => 'dh-metabox-post-link',
				'title' =>  __('Link Settings', 'sitesao'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'label' =>  __('Link URL', 'sitesao'),
						'description' => __('Please input the URL for your link. I.e. http://www.example.com', 'sitesao'),
						'name' => '_dh_link',
						'type' => 'text',
					)
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post  Video
			$meta_box = array(
					'id' => 'dh-metabox-post-video',
					'title' => __('Video Settings', 'sitesao'),
					'description' => '',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array(
							array(
								'type' => 'heading',
								'heading'=>__('Use service video','sitesao'),
							),
							array(
									'label' => __('Embedded Code', 'sitesao'),
									'description' => __('Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'sitesao'),
									'name' => '_dh_video_embed',
									'type' => 'text',
									'hidden'=>true,
							),
							array(
								'type' => 'heading',
								'heading'=>__('Use hosted video','sitesao'),
							),
							array(
									'label' => __('MP4 File URL', 'sitesao'),
									'description' => __('Please enter in the URL to the .m4v video file.', 'sitesao'),
									'name' => '_dh_video_mp4',
									'type' => 'media',
							),
							array(
									'label' => __('OGV/OGG File URL', 'sitesao'),
									'description' => __('Please enter in the URL to the .ogv or .ogg video file.', 'sitesao'),
									'name' => '_dh_video_ogv',
									'type' => 'media',
							),
							array(
									'label' => __('WEBM File URL', 'sitesao'),
									'description' => __('Please enter in the URL to the .webm video file.', 'sitesao'),
									'name' => '_dh_video_webm',
									'type' => 'media',
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			//Product Video
			if(defined('WOOCOMMERCE_VERSION'))
				add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', 'product', $meta_box ['context'], $meta_box ['priority'], $meta_box );
			//Post  Audio
			$meta_box = array(
				'id' => 'dh-metabox-post-audio',
				'title' =>  __('Audio Settings', 'sitesao'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array( 
							'label' => __('MP3 File URL', 'sitesao'),
							'description' => __('Please enter in the URL to the .mp3 file', 'sitesao'),
							'name' => '_dh_audio_mp3',
							'type' => 'media',
					),
					array( 
							'label' => __('OGA File URL', 'sitesao'),
							'description' => __('Please enter in the URL to the .ogg or .oga file', 'sitesao'),
							'name' => '_dh_audio_ogg',
							'type' => 'media',
						)
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			//Post Settings
			$meta_box = array (
					'id' => 'dh-metabox-setting',
					'title' => __ ( 'Post Settings', 'sitesao' ),
					'description' =>'',
					'post_type' => 'post',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Featured', 'sitesao' ),
									'description' => __ ( 'If checked. this post will show in highlighted block shortcode in Visual Composer', 'sitesao' ),
									'name' => 'featured',
									'cbvalue'=>'1',
									'type' => 'checkbox',
							),
							array (
									'label' => __ ( 'Masonry Item Sizing', 'sitesao' ),
									'description' => __ ( 'This will only be used if you choose to display your blog in the masonry/grid format.', 'sitesao' ),
									'name' => '_dh_masonry_size',
									'type' => 'select',
									'options'=>array(
											'normal'=>__('Normal','sitesao'),
											'double'=>__('Double','sitesao')
									)
							),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );

			//Page Settings
			$revsliders = array();
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			if ( is_plugin_active( 'revslider/revslider.php' ) ) {
				global $wpdb;
				$rs = $wpdb->get_results(
						"
	  SELECT id, title, alias
	  FROM " . $wpdb->prefix . "revslider_sliders
	  ORDER BY id ASC LIMIT 999
	  "
				);
				if ( $rs ) {
					foreach ( $rs as $slider ) {
						$revsliders[$slider->alias] = $slider->title;
					}
				} else {
					$revsliders[0] = __( 'No sliders found', 'sitesao' );
				}
			}
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			$menu_options[''] = __('Default Menu...','sitesao');
			foreach ( $menus as $menu ) {
				$menu_options[$menu->term_id] = $menu->name;
			}
			$meta_box = array (
					'id' => 'dh-metabox-page-settings',
					'title' => __ ( 'Page Settings', 'sitesao' ),
					'description' =>'',
					'post_type' => 'page',
					'context' => 'normal',
					'priority' => 'high',
					'fields' => array (
							array (
									'label' => __ ( 'Content Page no Padding', 'sitesao' ),
									'description' => __ ( 'If checked. content of page  with no padding top and padding bottom', 'sitesao' ),
									'name' => '_dh_no_padding',
									'type' => 'checkbox',
							),
							array (
								'label' => __ ( 'Main Navigation Menu', 'sitesao' ),
								'description' => __ ( 'Select which main menu displays on this page.', 'sitesao' ),
								'name' => 'main_menu',
								'type' => 'select',
								'value'=>'',
								'options'=>$menu_options,
							),
							array (
									'label' => __ ( 'Main Sidebar', 'sitesao' ),
									'description' => __ ( 'Select sidebar for page with 2 or 3 colums.', 'sitesao' ),
									'name' => 'main_sidebar',
									'type' => 'widgetised_sidebars',
							),
							array (
									'label' => __ ( 'Header Style', 'sitesao' ),
									'description' => __ ( 'Please select your header style here.', 'sitesao' ),
									'name' => 'header_style',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'center'=>__('Center','sitesao'),
											'classic'=>__('Default','sitesao'),
											'toggle-offcanvas'=>__('Off Canvas','sitesao'),
									)
							),
							array (
									'label' => __ ( 'Topbar', 'sitesao' ),
									'description' => __ ( 'Enable or disable the top bar.', 'sitesao' ),
									'name' => 'show_topbar',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'1'=>__('Show','sitesao'),
											'0'=>__('Hide','sitesao')
									)
							),
							array (
									'label' => __ ( 'Transparent Main Menu', 'sitesao' ),
									'description' => __ ( 'Enable or disable main menu background transparency.', 'sitesao' ),
									'name' => 'menu_transparent',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'1'=>__('On','sitesao'),
											'2'=>__('On and White Transparent','sitesao'),
											'0'=>__('Off','sitesao')
									)
							),
							array (
								'label' => __ ( 'Custom Page Logo', 'sitesao' ),
								'description' => __ ( 'Custom logo for page.', 'sitesao' ),
								'name' => 'page_logo',
								'type' => 'image',
							),
							array (
									'label' => __ ( 'Page Heading', 'sitesao' ),
									'description' => __ ( 'Enable/disable page heading or custom page heading', 'sitesao' ),
									'name' => 'page_heading',
									'type' => 'select',
									'value'=>'default',
									'options'=>array(
											'default'=>__('Default Heading','sitesao'),
											'heading'=>__('Custom Heading','sitesao'),
											'rev'=>__('Use Revolution Slider','sitesao'),
											'0'=>__('Hide','sitesao')
									)
							),
							array (
								'label' => __ ( 'Heading Menu Anchor ', 'sitesao' ),
								'description' => __ ( 'Add menu anchor for heading. You can use in One Page', 'sitesao' ),
								'name' => 'heading_menu_anchor',
								'type' => 'text',
							),
							array (
									'label' => __ ( 'Revolution Slider', 'sitesao' ),
									'description' => __ ( 'Select your Revolution Slider.', 'sitesao' ),
									'name' => 'rev_alias',
									'type' => 'select',
									'options'=>$revsliders,
							),
							array (
									'label' => __ ( 'Page Heading Background Image', 'sitesao' ),
									'description' => __ ( 'Custom heading background image.', 'sitesao' ),
									'name' => 'page_heading_background_image',
									'type' => 'image',
							),
// 							array (
// 								'label' => __ ( 'Sub Heading Title', 'sitesao' ),
// 								'description' => __ ( 'Sub heading title.', 'sitesao' ),
// 								'name' => 'default_sub_heading_title',
// 								'type' => 'text',
// 							),
							array (
									'label' => __ ( 'Page Heading Title', 'sitesao' ),
									'description' => __ ( 'Custom heading title.', 'sitesao' ),
									'name' => 'page_heading_title',
									'type' => 'text',
							),
							array (
									'label' => __ ( 'Page Heading Sub-title', 'sitesao' ),
									'description' => __ ( 'Custom heading sub-title.', 'sitesao' ),
									'name' => 'page_heading_sub_title',
									'type' => 'text',
							),
							array (
								'label' => __ ( 'Button text', 'sitesao' ),
								'description' => __ ( 'Custom heading button text.', 'sitesao' ),
								'name' => 'page_heading_button_text',
								'type' => 'text',
							),
							array (
								'label' => __ ( 'Button URL', 'sitesao' ),
								'description' => __ ( 'Custom heading button URL.', 'sitesao' ),
								'name' => 'page_heading_button_url',
								'type' => 'text',
							),
							array (
									'label' => __ ( 'Footer Newsletter', 'sitesao' ),
									'description' => __ ( 'Do you want show/hide footer newsletter.', 'sitesao' ),
									'name' => 'footer_newsletter',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'1'=>__('Show','sitesao'),
											'0'=>__('Hide','sitesao')
									)
							),
							array (
									'label' => __ ( 'Footer Featured', 'sitesao' ),
									'description' => __ ( 'Do you want show/hide footer featured.', 'sitesao' ),
									'name' => 'footer_featured',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'1'=>__('Show','sitesao'),
											'0'=>__('Hide','sitesao')
									)
							),
							array (
									'label' => __ ( 'Footer Featured Style', 'sitesao' ),
									'description' => __ ( 'Do you want to change footer featured style.', 'sitesao' ),
									'name' => 'footer_featured_style',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'default'=>__('Default','sitesao'),
											'light'=>__('Light','sitesao'),
									)
							),
							array (
									'label' => __ ( 'Footer Widget Area', 'sitesao' ),
									'description' => __ ( 'Do you want use the main footer that contains all the widgets areas.', 'sitesao' ),
									'name' => 'footer_area',
									'type' => 'select',
									'options'=>array(
											'-1'=>__('Global','sitesao'),
											'1'=>__('Show','sitesao'),
											'0'=>__('Hide','sitesao')
									)
							),
							
							// array (
							// 		'label' => __ ( 'Footer', 'sitesao' ),
							// 		'description' => __ ( 'Do you want show/hide footer.', 'sitesao' ),
							// 		'name' => 'footer_info',
							// 		'type' => 'select',
							// 		'options'=>array(
							// 				'-1'=>__('Global','sitesao'),
							// 				'1'=>__('Show','sitesao'),
							// 				'0'=>__('Hide','sitesao')
							// 		)
							// ),
							// array (
							// 	'label' => __ ( 'Footer Menu', 'sitesao' ),
							// 	'description' => __ ( 'Do you want use menu in main footer.', 'sitesao' ),
							// 	'name' => 'footer_menu',
							// 	'type' => 'select',
							// 	'options'=>array(
							// 		'-1'=>__('Global','sitesao'),
							// 		'1'=>__('Show','sitesao'),
							// 		'0'=>__('Hide','sitesao')
							// 	)
							// ),
					)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
				
			
		}
		
		public function add_video_featured_image($att_id){
			$p = get_post($att_id);
			update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
		}
		
		
		public function save_meta_boxes($post_id, $post) {
			// $post_id and $post are required
			if (empty ( $post_id ) || empty ( $post )) {
				return;
			}
			// Dont' save meta boxes for revisions or autosaves
			if (defined ( 'DOING_AUTOSAVE' ) || is_int ( wp_is_post_revision ( $post ) ) || is_int ( wp_is_post_autosave ( $post ) )) {
				return;
			}
			// Check the nonce
			if (empty ( $_POST ['dh_meta_box_nonce'] ) || ! wp_verify_nonce ( $_POST ['dh_meta_box_nonce'], 'dh_meta_box_nonce' )) {
				return;
			}
			
			// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
			if (empty ( $_POST ['post_ID'] ) || $_POST ['post_ID'] != $post_id) {
				return;
			}
			
			// Check user has permission to edit
			if (! current_user_can ( 'edit_post', $post_id )) {
				return;
			}
			if(isset( $_POST['dh_meta'] )){
				$dh_meta = $_POST['dh_meta'];
				if(get_post_format() == 'video' ){
					$_dh_video_embed = $dh_meta['_dh_video_embed'];
					if(dh_is_video_support($_dh_video_embed) && ($_dh_video_embed != dh_get_post_meta('video_embed_hidden'))){
						$videoThumbUrl = dh_get_video_thumb_url($_dh_video_embed);
						if (!empty($videoThumbUrl)) {
							 // add the function above to catch the attachments creation
							add_action('add_attachment',array(&$this,'add_video_featured_image'));
							// load the attachment from the URL
							media_sideload_image($videoThumbUrl, $post_id, $post_id);
							// we have the Image now, and the function above will have fired too setting the thumbnail ID in the process, so lets remove the hook so we don't cause any more trouble
							remove_action('add_attachment',array(&$this,'add_video_featured_image'));
						}
					}
				}
				// Process
				foreach( (array)$_POST['dh_meta'] as $key=>$val ){
					$val = wp_unslash($val);
					if(is_array($val)){
						$option_value = array_filter( array_map( 'sanitize_text_field', (array) $val ) );
						update_post_meta( $post_id, $key, $option_value );
					}else{
						update_post_meta( $post_id, $key, wp_kses_post($val) );
					}
				}
			}
			do_action('dh_metabox_save',$post_id);
		}
		
		public function enqueue_scripts(){
			wp_enqueue_style('dh-meta-box',DHINC_ASSETS_URL.'/css/meta-box.css',null,DHINC_VERSION);
			wp_enqueue_script('dh-meta-box',DHINC_ASSETS_URL.'/js/meta-box.js',array('jquery','jquery-ui-sortable'),DHINC_VERSION,true);
		}
		
	}
	new DH_Metaboxes ();

endif;