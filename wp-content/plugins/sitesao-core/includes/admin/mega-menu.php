<?php

if(!class_exists('DH_Walker_Nav_Menu_Edit')):
	if(!class_exists('Walker_Nav_Menu_Edit'))
		include_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );

class DH_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit{
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		parent::start_el( $output, $item, $depth, $args, $id );
	
		// Input the option right before Submit Button
		$desc_snipp = '<div class="menu-item-actions description-wide submitbox">';
		$pos = strrpos( $output, $desc_snipp );
		if( $pos !== false ) {
			$output = substr_replace($output, $this->mega_menu_setting( $item, $depth ) . $desc_snipp, $pos, strlen( $desc_snipp ) );
		}
	}
	
	function mega_menu_setting( $item, $depth = 0){
		global $wp_registered_sidebars;
		$html = '<div class="dh-menu-options">';
		$item_id = $item->ID;
		if($depth  == 0){
			ob_start();
			?>
			<p class="field-megamenu-status description description-wide">
				<label for="edit-menu-item-megamenu-status-<?php echo esc_attr($item_id); ?>">
					<input type="checkbox" id="edit-menu-item-megamenu-status-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-status" name="dh-megamenu-status[<?php echo esc_attr($item_id); ?>]" value="yes" <?php checked( $item->dh_megamenu_status, 'yes' ); ?> />
					<strong><?php _e( 'Enable Mega Menu', 'sitesao' ); ?></strong>
				</label>
			</p>
			<p class="field-megamenu field-megamenu-fullwidth description description-wide">
				<label for="edit-menu-item-megamenu-fullwidth-<?php echo esc_attr($item->ID); ?>">
					<input type="checkbox" id="edit-menu-item-megamenu-fullwidth-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-fullwidth" name="dh-megamenu-fullwidth[<?php echo esc_attr($item_id); ?>]" value="yes" <?php checked( $item->dh_megamenu_fullwidth, 'yes' ); ?> />
					<?php _e( 'Full Width Mega Menu', 'sitesao' ); ?>
				</label>
			</p>
			<p class="field-megamenu field-megamenu-columns description description-wide">
				<label for="edit-menu-item-megamenu-columns-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Mega Menu Columns', 'sitesao' ); ?>
					<select id="edit-menu-item-megamenu-columns-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-columns" name="dh-megamenu-columns[<?php echo esc_attr($item_id); ?>]">
						<option value="1" <?php selected( $item->dh_megamenu_columns, '1' ); ?>>1</option>
						<option value="2" <?php selected( $item->dh_megamenu_columns, '2' ); ?>>2</option>
						<option value="3" <?php selected( $item->dh_megamenu_columns, '3' ); ?>>3</option>
						<option value="4" <?php selected( $item->dh_megamenu_columns, '4' ); ?>>4</option>
					</select>
				</label>
			</p>
			<?php
			$html .= ob_get_clean();
		}elseif ($depth == '1'){
			ob_start();
			?>
			<p class="field-megamenu-title description description-wide">
				<label for="edit-menu-item-megamenu-title-<?php echo esc_attr($item_id); ?>">
					<input type="checkbox" id="edit-menu-item-megamenu-title-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-title" name="dh-megamenu-title[<?php echo esc_attr($item_id); ?>]" value="no" <?php checked( $item->dh_megamenu_title, 'no' ); ?> />
					<strong><?php _e( 'Disable Mega Menu Column Title', 'sitesao' ); ?></strong>
				</label>
			</p>
			<p class="field-megamenu-sidebar description description-wide">
				<label for="edit-menu-item-megamenu-widgetarea-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Display Sidebar in Menu', 'sitesao' ); ?>
					<select id="edit-menu-item-megamenu-sidebar-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-sidebar" name="dh-megamenu-sidebar[<?php echo esc_attr($item_id); ?>]">
						<option value="0"><?php _e( 'Select Widget Area...', 'sitesao' ); ?></option>
						<?php
						if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ):
						foreach( $wp_registered_sidebars as $sidebar ):
						?>
						<option value="<?php echo esc_attr($sidebar['id']); ?>" <?php selected( $item->dh_megamenu_sidebar, $sidebar['id'] ); ?>><?php echo esc_html($sidebar['name']); ?></option>
						<?php endforeach; endif; ?>
					</select>
				</label>
			</p>
			<?php
			$html .= ob_get_clean();
		}else{
			ob_start();
			?>
			<p class="field-megamenu-sidebar description description-wide">
				<label for="edit-menu-item-megamenu-widgetarea-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Display Sidebar in Menu', 'sitesao' ); ?>
					<select id="edit-menu-item-megamenu-sidebar-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-megamenu-sidebar" name="dh-megamenu-sidebar[<?php echo esc_attr($item_id); ?>]">
						<option value=""><?php _e( 'Select Widget Area', 'sitesao' ); ?></option>
						<?php
						if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ):
						foreach( $wp_registered_sidebars as $sidebar ):
						?>
						<option value="<?php echo esc_attr($sidebar['id']); ?>" <?php selected( $item->dh_megamenu_sidebar, $sidebar['id'] ); ?>><?php echo esc_html($sidebar['name']); ?></option>
						<?php endforeach; endif; ?>
					</select>
				</label>
			</p>
			<?php
			$html .= ob_get_clean();
		}
		ob_start();
		?>
			<p class="field-menu-icon description description-wide">
				<label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Menu Icon Class (Font Awesome Icon or Elegant Icon.ex: fa fa-home, elegant_icon_house_alt)', 'sitesao' ); ?>
					<input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-icon" name="dh-menu-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->dh_menu_icon); ?>" />
				</label>
			</p>
			<p class="field-menu-visibility description description-wide">
				<label for="edit-menu-item-visibility-<?php echo esc_attr($item_id); ?>">
					<?php _e( 'Visibility', 'sitesao' ); ?>
					<br/>
					<select id="edit-menu-item-menu-visibility-<?php echo esc_attr($item_id); ?>" name="dh-menu-visibility[<?php echo esc_attr($item_id); ?>]">
						<option value="" ><?php _e('All Devices', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility, 'hidden-phone' ) ?> value="hidden-phone"><?php _e('Hidden Phone', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility,'hidden-tablet' ) ?> value="hidden-tablet"><?php _e('Hidden Tablet', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility,'hidden-pc' ) ?> value="hidden-pc"><?php _e('Hidden PC', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility,'visible-phone' ) ?> value="visible-phone"><?php _e('Visible Phone', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility,'visible-tablet' ) ?> value="visible-tablet"><?php _e('Visible Tablet', 'sitesao'); ?></option>
						<option <?php selected( $item->dh_menu_visibility,'visible-pc' ) ?> value="visible-pc"><?php _e('Visible PC', 'sitesao'); ?></option>
					</select>
				</label>
			</p>
		<?php
		$html .=ob_get_clean();
		$html .='</div>';
		return $html;
	}
}
endif;

if(!class_exists('DH_MegaMenu_Edit')):
class DH_MegaMenu_Edit {
	public function __construct(){
		 add_filter( 'wp_edit_nav_menu_walker', array( &$this, 'edit_nav_menu_walker' ) );
		 add_action( 'wp_update_nav_menu_item', array( &$this, 'update_nav_menu_item' ), 10, 2);
         add_filter( 'wp_setup_nav_menu_item', array( &$this, 'setup_nav_menu_item' ) );
	}
	
	public function edit_nav_menu_walker(){
		return 'DH_Walker_Nav_Menu_Edit';
	}
	
	public function update_nav_menu_item($menu_id, $menu_item_db_id ){
		$fileds = array('dh-megamenu-status','dh-megamenu-fullwidth','dh-megamenu-columns','dh-megamenu-title','dh-megamenu-sidebar','dh-menu-icon','dh-menu-visibility');
		foreach ($fileds as $filed){
			$value = isset( $_POST[$filed][$menu_item_db_id]) ? $_POST[$filed][$menu_item_db_id] : '';
			$meta_key = '_'.str_replace('-','_', $filed);
			$value = wp_unslash($value);
			if(is_array($value)){
				$option_value = array_filter( array_map( 'sanitize_text_field', (array) $value ) );
				update_post_meta( $menu_item_db_id, $meta_key, $option_value );
			}else{
				update_post_meta( $menu_item_db_id, $meta_key, wp_kses_post($value) );
			}
		}
	}
	
	public function setup_nav_menu_item($menu_item){
		$fileds = array('dh-megamenu-status','dh-megamenu-fullwidth','dh-megamenu-columns','dh-megamenu-title','dh-megamenu-sidebar','dh-menu-icon','dh-menu-visibility');
		foreach ($fileds as $filed){
			$meta_key = str_replace('-','_', $filed);
			$menu_item->$meta_key = get_post_meta($menu_item->ID,'_'.$meta_key,true);
		}
		return $menu_item;
	}
}
new DH_MegaMenu_Edit();
endif;