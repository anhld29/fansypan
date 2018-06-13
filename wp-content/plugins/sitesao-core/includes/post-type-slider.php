<?php
if(!class_exists('DH_PostType_Slider')):
class DH_PostType_Slider {
	public function __construct(){
		add_action ( 'init', array (&$this,'register_post_type' ) );
		add_shortcode('dh_slider', array($this,'shortcode_output'));
		if(is_admin()){
			add_action ( 'admin_enqueue_scripts', array( &$this, 'assets' ) );
			//Post type slide
			add_filter('manage_edit-dh_slide_columns',  array( &$this,'edit_columns'));
			add_action('manage_dh_slide_posts_custom_column', array(&$this,'custom_columns'), 10, 2);
			add_filter( 'pre_get_posts', array( &$this, 'pre_get_posts' ) );
			//Meta box
			add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
			add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
			//Term slider
			add_action('dh_slider_add_form_fields',array(&$this,'add_slider_form_fields'));
			add_action('dh_slider_edit_form_fields',array(&$this,'edit_slider_form_fields'),10,3);
			add_action( 'created_term', array($this,'save_slider_settings'), 10,3 );
			add_action( 'edit_term', array($this,'save_slider_settings'), 10,3 );
			//Ajax Order
			add_action('wp_ajax_dh_slider_update_menu_order', array(&$this,'update_menu_order'));
		}
	}
	
	public function assets(){
		global $current_screen;
		if($current_screen->post_type === 'dh_slide'){
			wp_enqueue_style('dh-postype-slider',DHINC_ASSETS_URL.'/css/posttype-slider.css',null,DHINC_VERSION);
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script('dh-postype-slider',DHINC_ASSETS_URL.'/js/posttype-slider.js',array('jquery','jquery-ui-sortable'),DHINC_VERSION,true);
		}
		return;
	}
	
	public function shortcode_output($atts, $content = null){
		/**
		 * script
		 * {{
		 */
		wp_enqueue_script('vendor-parallax');
		wp_enqueue_script('vendor-imagesloaded');
		
		extract( shortcode_atts( array(
			'slider_slug' 		=> '',
			'visibility'		=>'',
			'el_class'			=>'',
		), $atts ) );
		if(empty($slider_slug))
			return '';
		
		$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
		switch ($visibility) {
			case 'hidden-phone':
				$el_class .= ' hidden-xs';
				break;
			case 'hidden-tablet':
				$el_class .= ' hidden-sm hidden-md';
				break;
			case 'hidden-pc':
				$el_class .= ' hidden-lg';
				break;
			case 'visible-phone':
				$el_class .= ' visible-xs-inline';
				break;
			case 'visible-tablet':
				$el_class .= ' visible-sm-inline visible-md-inline';
				break;
			case 'visible-pc':
				$el_class .= ' visible-lg-inline';
				break;
		}
		
		$args = array(
			'orderby'         	  => "menu_order",
			'order'           	  => "ASC",
			'posts_per_page'      => -1,
			'post_status'         => 'publish',
			'post_type'			  =>'dh_slide',
		);
		$args['tax_query'][] =  array(
			'taxonomy' => 'dh_slider',
			'terms'    => $slider_slug,
			'field' => 'slug',
			'operator' => 'IN'
		);
		$slides = new WP_Query($args);
		ob_start();
		if($slides->have_posts()):
		$setting_options = get_option('dh_slider_settings');
		$slider = get_term_by('slug', $slider_slug, 'dh_slider');
		if(!$slider || is_wp_error($slider))
			return '';
		$settings = isset($setting_options[$slider->term_id]) ? $setting_options[$slider->term_id] : array();
		$layout = isset($settings['layout']) ? $settings['layout'] : 'fullscreen';
		$width = isset($settings['width']) ? $settings['width'] : '100%';
		$height = isset($settings['height']) ? $settings['height'] : '650';
		$auto_run = isset($settings['auto_run']) ? $settings['auto_run'] : 'yes';
		$duration = isset($settings['duration']) ? $settings['duration'] : '6000';
		$width = $width == '100%' ? $width : $width.'px';
		if($layout == 'fullscreen'){
			$width = '100%';
		}
		$height = $height.'px';
		$indicators = '';
		$id = uniqid('dhslider_');
		
		?>
		<div id="<?php echo esc_attr($id) ?>" data-autorun="<?php echo esc_attr($auto_run) ?>" data-duration="<?php echo esc_attr($duration) ?>" class="carousel slide fade dhslider dhslider-<?php echo esc_attr($layout)?> <?php echo esc_attr($el_class);?>" data-height="<?php echo isset($settings['height']) ? $settings['height'] : '650'; ?>" style="width: <?php echo esc_attr($width) ?>;height: <?php echo esc_attr($height) ?>">
			<div class="dhslider-loader"><div class="fade-loading"><i></i><i></i><i></i><i></i></div></div>
			<div class="carousel-inner dhslider-wrap">
				<?php $i = 0;?>
				<?php while ($slides->have_posts()): $slides->the_post(); global $post;?>
				<?php 
				$indicators .= '<li data-target="#'.$id.'" data-slide-to="'.$i.'"';
				if($i == 0){
					$indicators .= ' class="active"';
				}
				$indicators .= '></li>';
				$bg_type = dh_get_post_meta('bg_type');
				$link_type = dh_get_post_meta('link_type');
				?>
				<div class="item slider-item <?php echo ($i == 0 ?' active':'')  ?>" style="height: <?php echo esc_attr($height);?>">
					<?php if($bg_type == 'image'):?>
					<?php 
					$bg_image = dh_get_post_meta('bg_image');
					if( !empty($bg_image) ) {
						echo '<div class="slide-bg" style="background-image:url('.wp_get_attachment_url($bg_image).')">';
						echo '</div>';
					}
					?>
					<?php 
					elseif ($bg_type =='video'):
						$bg_video_poster = dh_get_post_meta('bg_video_poster');
						$bg_video = '';
						$bg_video_args = array();
						echo '<div class="slide-bg" style="background-image:url('.wp_get_attachment_url($bg_video_poster).')">';
						if ( $bg_video_src_mp4 = dh_get_post_meta('bg_video_mp4') ) {
							$bg_video_args['mp4'] = $bg_video_src_mp4;
						}
						
						if ( $bg_video_src_ogv = dh_get_post_meta('bg_video_ogv')) {
							$bg_video_args['ogv'] = $bg_video_src_ogv;
						}
						
						if ( $bg_video_src_webm = dh_get_post_meta('bg_video_webm')) {
							$bg_video_args['webm'] = $bg_video_src_webm;
						}
						if ( !empty($bg_video_args) ) {
							$attr_strings = array(
								'loop="1"',
								'preload="1"',
								'autoplay="autoplay"',
								'muted="muted"'
							);
							
							if (!empty($bg_video_poster)) {
								$bg_image_path = wp_get_attachment_image_src($bg_video_poster,'dh-full');
								if(!empty($bg_image_path))
									$attr_strings[] = 'poster="' . esc_url($bg_image_path[0]) . '"';
							}
							$bg_video .= sprintf( '<div class="video-embed-wrap"><video %s width="1800" height="700" class="slider-video">', join( ' ', $attr_strings ) );
							
							$source = '<source type="%s" src="%s" />';
							foreach ( $bg_video_args as $video_type=>$video_src ) {
							
								$video_type = wp_check_filetype( $video_src, wp_get_mime_types() );
								$bg_video .= sprintf( $source, $video_type['type'], esc_url( $video_src ) );
							
							}
							$bg_video .= '</video></div>';
						}
						?>
						<?php echo $bg_video?>
						<?php echo '</div>';?>
					<?php endif;?>
					<!-- <div class="slider-overlay"></div> -->
					<?php if($link_type =='link'):?>
					<a class="slide-link" target="_blank" href="<?php echo esc_url(dh_get_post_meta('slide_url'))?>"></a>
					<?php endif;?>
					<div class="slider-caption caption-align-<?php echo dh_get_post_meta('caption_align',get_the_ID(),'center')?>">
						<div class="slider-caption-wrap">
							<?php 
							$top_heading = dh_get_post_meta('top-heading');
							$caption = dh_get_post_meta('caption');
							$heading = dh_get_post_meta('heading');
							$top_heading_color = dh_get_post_meta('top_heading_color');
							$heading_color = dh_get_post_meta('heading_color');
							$caption_color = dh_get_post_meta('caption_color');
							?>
							<?php if(!empty($top_heading)):?>
							<span class="slider-top-caption-text"<?php if(!empty($top_heading_color)):?> style="color:<?php echo esc_attr($top_heading_color)?>"<?php endif;?>><?php echo esc_html($top_heading)?></span>
							<?php endif;?>
							<?php if(!empty($heading)):?>
							<h2 class="slider-heading-text"<?php if(!empty($heading_color)):?> style="color:<?php echo esc_attr($heading_color)?>"<?php endif;?>><?php echo esc_html($heading)?></h2>
							<?php endif;?>
							<?php if(!empty($caption)):?>
							<div class="slider-caption-text"<?php if(!empty($caption_color)):?> style="color:<?php echo esc_attr($caption_color)?>"<?php endif;?>><?php echo dh_print_string($caption)?></div>
							<?php endif;?>
							<?php if($link_type =='button'):?>
								<?php 
								$button_primary_text = dh_get_post_meta('button_primary_text');
								$button_primary_link = dh_get_post_meta('button_primary_link');
								$button_primary_style = dh_get_post_meta('button_primary_style');
								$button_primary_color = dh_get_post_meta('button_primary_color');
								if($button_primary_style =='outlined'){
									$button_primary_color .='-outline';
								}
								$button_secondary_text = dh_get_post_meta('button_secondary_text');
								$button_secondary_link = dh_get_post_meta('button_secondary_link');
								$button_secondary_style = dh_get_post_meta('button_secondary_style');
								$button_secondary_color = dh_get_post_meta('button_secondary_color');
								if($button_secondary_style =='outlined'){
									$button_secondary_color .='-outline';
								}
								?>
								<div class="slider-buttons">
									<?php if(!empty($button_primary_text)):?>
									<a href="<?php echo esc_url($button_primary_link)?>" class="btn btn-lg btn-<?php echo esc_attr($button_primary_color); ?>"><?php echo esc_html($button_primary_text) ?></a>
									<?php endif;?>
									<?php if(!empty($button_secondary_text)):?>
									<a href="<?php echo esc_url($button_secondary_link)?>" class="btn btn-lg btn-<?php echo esc_attr($button_secondary_color)?>"><?php echo esc_html($button_secondary_text) ?></a>
									<?php endif;?>
								</div>
							<?php endif;?>
						</div>
					</div>
				</div>
				<?php $i++;?>
				<?php endwhile;?>
			</div>
			<?php  if($slides->post_count > 1){ ?>
			<ol class="carousel-indicators parallax-content">
				<?php echo $indicators;?>
			</ol>
			<?php } ?>
			<a class="left carousel-control parallax-content" href="<?php echo '#'.$id?>" role="button" data-slide="prev">
				<i class="fa fa-angle-left carousel-icon-prev"></i>
			</a>
			<a class="right carousel-control parallax-content" href="<?php echo '#'.$id?>" role="button" data-slide="next">
				<i class="fa fa-angle-right carousel-icon-next"></i>
			</a>
		</div>
		<?php
		endif;
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
	
	public function add_slider_form_fields(){
		?>
		<div class="form-field">
			<label><?php _e('Slider Layout','sitesao')?></label>
			<select id="slider_layout" name="dh_slider_layout">
				<option selected="selected" value="custom"><?php _e('Custom','sitesao')?></option>
				<option value="fullscreen"><?php _e('Full Screen','sitesao')?></option>
			</select>
		</div>
		<div class="form-field">
			<label><?php _e('Slider Width','sitesao')?></label>
			<input id="slider_width" type="text" size="40" value="960" name="dh_slider_width">
			<p><?php _e('Enter 100% to full width','sitesao')?></p>
		</div>
		<div class="form-field">
			<label><?php _e('Slider Height','sitesao')?></label>
			<input id="slider_height" type="text" size="40" value="650" name="dh_slider_height">
		</div>
		<div class="form-field">
			<label><?php _e('Auto Run','sitesao')?></label>
			<select id="slider_auto_run" name="dh_slider_auto_run">
				<option selected="selected" value="yes"><?php _e('Yes','sitesao')?></option>
				<option value="no"><?php _e('No','sitesao')?></option>
			</select>
		</div>
		<div class="form-field">
			<label><?php _e('Duration','sitesao')?></label>
			<input id="slider_duration" type="text" size="40" value="6000" name="dh_slider_duration">
		</div>
		<script type="text/javascript">
		<!--
		jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			var slider_layout = function(el){
				var el = $(el);
				if(el.val() =='custom'){
					$('#slider_width').closest('.form-field').show();
					$('#slider_height').closest('.form-field').show();
				}else{
					$('#slider_width').closest('.form-field').hide();
					$('#slider_height').closest('.form-field').hide();
				}
			}
			slider_layout($('#slider_layout'));
			$('#slider_layout').on('change',function(){
				slider_layout($(this));
			});
		});
		//-->
		</script>
		
		<?php
	}
	
	public function edit_slider_form_fields($term, $taxonomy){
		$setting_options = get_option('dh_slider_settings');
		$settings = isset($setting_options[$term->term_id]) ? $setting_options[$term->term_id] : array();
		$layout = isset($settings['layout']) ? $settings['layout'] : 'custom';
		$width = isset($settings['width']) ? $settings['width'] : '960';
		$height = isset($settings['height']) ? $settings['height'] : '650';
		$auto_run = isset($settings['auto_run']) ? $settings['auto_run'] : 'yes';
		$duration = isset($settings['duration']) ? $settings['duration'] : '6000';
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Slider Layout','sitesao')?></label></th>
			<td>
			<select id="slider_layout" name="dh_slider_layout">
				<option <?php selected($layout,'custom')?> value="custom"><?php _e('Custom','sitesao')?></option>
				<option <?php selected($layout,'fullscreen')?> value="fullscreen"><?php _e('Full Screen','sitesao')?></option>
			</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Slider Width','sitesao')?></label></th>
			<td>
			<input id="slider_width" type="text" size="40" value="<?php echo esc_attr($width)?>" name="dh_slider_width">
			<p><?php _e('Enter 100% to full width','sitesao')?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Slider Height','sitesao')?></label></th>
			<td>
			<input id="slider_height" type="text" size="40" value="<?php echo esc_attr($height)?>" name="dh_slider_height">
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Auto Run','sitesao')?></label></th>
			<td>
			<select id="slider_auto_run" name="dh_slider_auto_run">
				<option <?php selected($auto_run,'yes')?> value="yes"><?php _e('Yes','sitesao')?></option>
				<option <?php selected($auto_run,'no')?> value="no"><?php _e('No','sitesao')?></option>
			</select>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e('Duration','sitesao')?></label></th>
			<td>
			<input id="slider_duration" type="text" size="40" value="<?php echo esc_html($duration) ?>" name="dh_slider_duration">
			</td>
		</tr>
		<script type="text/javascript">
		<!--
		jQuery(document).ready(function($){
			$('#parent').closest('.form-field').hide();
			var slider_layout = function(el){
				var el = $(el);
				if(el.val() =='custom'){
					$('#slider_width').closest('.form-field').show();
					$('#slider_height').closest('.form-field').show();
				}else{
					$('#slider_width').closest('.form-field').hide();
					$('#slider_height').closest('.form-field').hide();
				}
			}
			slider_layout($('#slider_layout'));
			$('#slider_layout').on('change',function(){
				slider_layout($(this));
			});
		});
		//-->
		</script>
		<?php	
	}
	
	public function save_slider_settings($term_id, $tt_id, $taxonomy){
		$fields = array('dh_slider_layout','dh_slider_width','dh_slider_height','dh_slider_auto_run','dh_slider_duration');
		$dh_slider_settings = get_option( 'dh_slider_settings' );
		if ( ! $dh_slider_settings )
			$dh_slider_settings = array();
		$settings = array();
		foreach($fields as $field){
			if(isset($_POST[$field])){
				$key = str_replace('dh_slider_', '', $field);
				$settings[$key] = sanitize_text_field($_POST[$field]);
			}
		}
		$dh_slider_settings[$term_id] = $settings;
		update_option('dh_slider_settings', $dh_slider_settings);
	}
	
	public function pre_get_posts($wp_query){
		if(!defined( 'DOING_AJAX' )){
			if ( isset( $wp_query->query['post_type'] ) ) {
				if ( $wp_query->query['post_type'] == 'dh_slide') {
					$wp_query->set( 'orderby', 'menu_order' );
					$wp_query->set( 'order', 'ASC' );
				}
			}
		}
		return;
	}
	
	public function update_menu_order(){
		global $wpdb;
		parse_str($_POST['order'], $data);
		if ( is_array($data) ) {
			$id_arr = array();
			foreach( $data as $key => $values ) {
				foreach( $values as $position => $id ) {
					$id_arr[] = $id;
				}
			}
			$menu_order_arr = array();
			foreach( $id_arr as $key => $id ) {
				$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = ".$id );
				foreach( $results as $result ) {
					$menu_order_arr[] = $result->menu_order;
				}
			}
			sort($menu_order_arr);
			foreach( $data as $key => $values ) {
				foreach( $values as $position => $id ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[$position] ), array( 'ID' => $id ) );
				}
			}
			echo '1';
			die;
		}
		echo '0';
		die();
	}
	
	public function edit_columns($columns){
		$column_thumbnail = array( 'thumbnail' => __('Thumbnail','sitesao') );
		$column_caption = array( 'caption' => __('Caption','sitesao') );
		$column_slider = array( 'slider' => __('Slider','sitesao') );
		$columns = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
		$columns = array_slice( $columns, 0, 2, true ) + $column_caption + array_slice( $columns, 2, NULL, true );
		$columns = array_slice( $columns, 0, 3, true ) + $column_slider + array_slice( $columns, 3, NULL, true );
		return $columns;
	}
	
	public function custom_columns($columns, $post_id){
		switch ($columns) {
			case 'slider':
				$terms = get_the_terms( $post_id, 'dh_slider' );
				if(!is_wp_error($terms) && !empty($terms)){
					foreach ( $terms as $term ) {
						$link = get_edit_term_link( $term->term_id, 'dh_slider','dh_slide' );
						if ( is_wp_error( $link ) )
							return $link;
						$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
					}
					echo join( ',', $term_links );
				}
			break;
			case 'caption':
				$caption = dh_get_post_meta('caption',$post_id);
				$heading = dh_get_post_meta('heading',$post_id);
				echo '<h2>'.$heading.'</h2><p>'.$caption.'</p>';
			break;
			case 'thumbnail':
				$background_type = dh_get_post_meta('bg_type',$post_id);
				if($background_type == 'image') {
					$thumbnail = dh_get_post_meta('bg_image',$post_id);
					if( !empty($thumbnail) ) {
						echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . wp_get_attachment_url($thumbnail) . '" /></a>';
					} else {
						echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . DHINC_ASSETS_URL . '/images/placeholder.png" /></a>' .
							'<strong><a class="row-title" href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit">'.__('No image added','sitesao').'</a></strong>';
					}
				}
				
				else {
					$thumbnail = dh_get_post_meta('bg_video_poster',$post_id);
					if( !empty($thumbnail) ) {
						echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . wp_get_attachment_url($thumbnail) . '" /></a>';
					} else {
						echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . DHINC_ASSETS_URL . '/images/placeholder.png" /></a>' .
							'<strong><a class="row-title" href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit">'.__('No image added','sitesao').'</a></strong>';
					}
				}
			break;
			default:
				break;
		}
	}
	
	public function add_meta_boxes(){
		//Slide Settings
		$meta_box = array (
			'id' => 'dh-metabox-slide-setting',
			'title' => __ ( 'Slide Settings', 'sitesao' ),
			'description' =>'',
			'post_type' => 'dh_slide',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array (
				array (
					'label' => __ ( 'Background Type', 'sitesao' ),
					'description' => __ ( 'Please select the background type for your slide.', 'sitesao' ),
					'name' => 'bg_type',
					'type' => 'select',
					'options'=>array(
						'image'=>__('Image','sitesao'),
						'video'=>__('Video','sitesao')
					),
					'value'=>'image'
				),
				array(
						'label' => __('Slide Background Image', 'sitesao'),
						'description' => __('Click the "Upload" button to begin uploading your image', 'sitesao'),
						'name' => 'bg_image',
						'type' => 'image',
				),
				array(
						'label' => __('MP4 File URL', 'sitesao'),
						'description' => __('Please enter in the URL to the .m4v video file.', 'sitesao'),
						'name' => 'bg_video_mp4',
						'type' => 'media',
				),
				array(
						'label' => __('OGV/OGG File URL', 'sitesao'),
						'description' => __('Please enter in the URL to the .ogv or .ogg video file.', 'sitesao'),
						'name' => 'bg_video_ogv',
						'type' => 'media',
				),
				array(
						'label' => __('WEBM File URL', 'sitesao'),
						'description' => __('Please enter in the URL to the .webm video file.', 'sitesao'),
						'name' => 'bg_video_webm',
						'type' => 'media',
				),
				array(
						'label' => __('Preview Image', 'sitesao'),
						'description' => __('Image should be at least 680px wide. Click the "Upload" button to begin uploading your image', 'sitesao'),
						'name' => 'bg_video_poster',
						'type' => 'image',
				),
				array(
					'type' => 'hr',
				),
				array(
					'label' =>  __('Caption Align', 'sitesao'),
					'description' => __('Caption align', 'sitesao'),
					'name' => 'caption_align',
					'value'=>'center',
					'type' => 'select',
					'options'=>array(
						'left'=>__('Left','sitesao'),
						'center'=>__('Center','sitesao'),
						'right'=>__('Right','sitesao')
					)
				),
				array(
					'label' =>  __('Top Small Heading', 'sitesao'),
					'description' => __('Please enter in the top small heading for your slide', 'sitesao'),
					'name' => 'top-heading',
					'type' => 'text',
				),
				array(
					'label' =>  __('Small Heading Color', 'sitesao'),
					'name' => 'top_heading_color',
					'type' => 'color',
				),
				array(
					'label' =>  __('Heading', 'sitesao'),
					'description' => __('Please enter in the heading for your slide', 'sitesao'),
					'name' => 'heading',
					'type' => 'text',
				),
				array(
					'label' =>  __('Heading Color', 'sitesao'),
					'name' => 'heading_color',
					'type' => 'color',
				),
				array(
					'label' =>  __('Caption', 'sitesao'),
					'description' => __('Please enter in the caption for your slide', 'sitesao'),
					'name' => 'caption',
					'type' => 'textarea',
				),
				array(
					'label' =>  __('Caption Color', 'sitesao'),
					'name' => 'caption_color',
					'type' => 'color',
				),
				array('type'=>'hr'),
				array (
					'label' => __ ( 'Link Type', 'sitesao' ),
					'description' => __ ( 'Please select the link type for your slide.', 'sitesao' ),
					'name' => 'link_type',
					'type' => 'select',
					'options'=>array(
						'button'=>__('Button Link','sitesao'),
						'link'=>__('Link','sitesao')
					),
					'value'=>'button'
				),
				array(
					'label' =>  __('URL', 'sitesao'),
					'description' => __('Please enter URL', 'sitesao'),
					'name' => 'slide_url',
					'type' => 'text',
				),
				array(
					'label' =>  __('Button #1 Text', 'sitesao'),
					'description' => __('Please enter text for button #1', 'sitesao'),
					'name' => 'button_primary_text',
					'type' => 'text',
				),
				array(
					'label' =>  __('Button #1 Link', 'sitesao'),
					'description' => __('Please enter link for button #1 (remember to include "http://")', 'sitesao'),
					'name' => 'button_primary_link',
					'type' => 'text',
					'value'=>'#'
				),
				array(
					'label' =>  __('Button #1 Style', 'sitesao'),
					'description' => __('Please select style for button #1', 'sitesao'),
					'name' => 'button_primary_style',
					'type' => 'select',
					'options'=>array(
						'solid'=>__('Solid Background','sitesao'),
						'outlined'=>__('Outlined','sitesao')
					),
					'value'=>'solid'
				),
				array(
					'label' =>  __('Button #1 Color', 'sitesao'),
					'description' => __('Please select style for button #1', 'sitesao'),
					'name' => 'button_primary_color',
					'type' => 'select',
					'options'=>array(
						'primary'=>__( 'Primary', 'sitesao' ), 
						'success'=>__( 'Success', 'sitesao' ),
						'info'=>__( 'Info', 'sitesao' ),
						'warning'=>__( 'Warning', 'sitesao' ),
						'danger'=>__( 'Danger', 'sitesao' ), 
						'white'=>__( 'White', 'sitesao' ),
					),
					'value'=>'primary'
				),
				array(
					'label' =>  __('Button #2 Text', 'sitesao'),
					'description' => __('Please enter text for button #2', 'sitesao'),
					'name' => 'button_secondary_text',
					'type' => 'text',
				),
				array(
					'label' =>  __('Button #2 Link', 'sitesao'),
					'description' => __('Please enter link for button #2 (remember to include "http://")', 'sitesao'),
					'name' => 'button_secondary_link',
					'type' => 'text',
					'value'=>'#',
				),
				array(
					'label' =>  __('Button #2 Style', 'sitesao'),
					'description' => __('Please select style for button #2', 'sitesao'),
					'name' => 'button_secondary_style',
					'type' => 'select',
					'options'=>array(
						'solid'=>__('Solid Background','sitesao'),
						'outlined'=>__('Outlined','sitesao')
					),
					'value'=>'outlined'
				),
				array(
					'label' =>  __('Button #2 Color', 'sitesao'),
					'description' => __('Please select style for button #2', 'sitesao'),
					'name' => 'button_secondary_color',
					'type' => 'select',
					'options'=>array(
						'primary'=>__( 'Primary', 'sitesao' ),
						'success'=>__( 'Success', 'sitesao' ),
						'info'=>__( 'Info', 'sitesao' ),
						'warning'=>__( 'Warning', 'sitesao' ),
						'danger'=>__( 'Danger', 'sitesao' ),
						'white'=>__( 'White', 'sitesao' ),
					),
					'value'=>'white'
				),
			)
		);
		add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dh_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
	}
	
	public function save_meta_boxes($post_id, $post){
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
		// Check the post type
		if ($post->post_type != 'dh_slide' ) {
			return;
		}	
		global $wpdb;
		if(empty($post->menu_order)):
			$result = $wpdb->get_var( "SELECT MAX(menu_order) FROM $wpdb->posts WHERE post_type = 'dh_slide'" );
			$wpdb->update( $wpdb->posts, array( 'menu_order' => (int) $result + 1 ), array( 'ID' => $post_id ) );
		endif;
		// Process
		foreach( $_POST['dh_meta'] as $key=>$val ){
			update_post_meta( $post_id, $key, $val );
		}
	}
	
	public function register_post_type() {
		if (post_type_exists ( 'dh_slide' ))
			return;
		
		register_post_type ( "dh_slide", 
			apply_filters ( 'dh_register_post_type_dh_slide', array (
				'labels' => array (
						'name' => __ ( 'DHSlides', 'sitesao' ),
						'singular_name' => __ ( 'DHSlide', 'sitesao' ),
						'menu_name' => _x ( 'DHSlides', 'Admin menu name', 'sitesao' ),
						'add_new' => __ ( 'Add New', 'sitesao' ),
						'add_new_item' => __ ( 'Add New Slide', 'sitesao' ),
						'edit' => __ ( 'Edit', 'sitesao' ),
						'edit_item' => __ ( 'Edit DHSlide', 'sitesao' ),
						'new_item' => __ ( 'New DHSlide', 'sitesao' ),
						'view' => __ ( 'View DHSlide', 'sitesao' ),
						'view_item' => __ ( 'View DHSlide', 'sitesao' ),
						'search_items' => __ ( 'Search DHSlides', 'sitesao' ),
						'not_found' => __ ( 'No DHSlides found', 'sitesao' ),
						'not_found_in_trash' => __ ( 'No DHSlides found in trash', 'sitesao' ),
						'parent' => __ ( 'Parent DHSlide', 'sitesao' ) 
				),
				'singular_label' => __('DHSlide', 'sitesao'),
				'public' => false,
				'show_ui' => true,
				'hierarchical' => false,
				'menu_position' => 10,
				'menu_icon' => 'dashicons-slides',
				'exclude_from_search' => false,
				'supports' => false
		) ) );
		
		register_taxonomy('dh_slider',
			array('dh_slide'),
			array('hierarchical' => true,
			'labels'=>array(
					'name' => __( 'Sliders', 'sitesao'),
					'singular_name' => __( 'Slider', 'sitesao'),
					'search_items' =>  __( 'Search Sliders', 'sitesao'),
					'all_items' => __( 'All Sliders', 'sitesao'),
					'edit_item' => __( 'Edit Slider', 'sitesao'),
					'update_item' => __( 'Update Slider', 'sitesao'),
					'add_new_item' => __( 'Add New Slider', 'sitesao'),
					'new_item_name' => __( 'New Slider', 'sitesao'),
				    'menu_name' => __( 'Sliders', 'sitesao')
				),
			'show_ui' 				=> true,
			'query_var' 			=> false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_tagcloud'         => false,
			'show_admin_column'     => false,
			'public'				=> false,
		));
	}
}
new DH_PostType_Slider();
endif;