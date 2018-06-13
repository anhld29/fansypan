<?php

if(!class_exists('DH_Admin')):
class DH_Admin {
	public function __construct(){
		
		include_once (dirname( __FILE__ ) . '/meta-boxes.php');
		include_once (dirname( __FILE__ ) . '/mega-menu.php');
		include_once (dirname( __FILE__ ) . '/theme-options.php');
		// Import Demo
		include_once (dirname( __FILE__ ) . '/import-demo.php');
			
		
		add_action( 'admin_init', array(&$this,'admin_init'));
		add_action('admin_enqueue_scripts',array(&$this,'enqueue_scripts'));
	}
	
	
	
	public function admin_init(){
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'mce_external_plugins'));
			add_filter('mce_buttons', array($this, 'mce_buttons'));
		}
	}
	
	public function mce_external_plugins($plugins){
		$plugins['dh_tooltip'] = DHINC_ASSETS_URL. '/js/tooltip_plugin.js';
		return $plugins;
	}
	
	public function mce_buttons($buttons){
		$buttons[] = 'dh_tooltip_button';
		return $buttons;
	}
	
	public function enqueue_scripts(){
		
		wp_enqueue_style('dh-admin',DHINC_ASSETS_URL.'/css/admin.css',false,DHINC_VERSION);
		
		wp_register_script('dh-admin',DHINC_ASSETS_URL.'/js/admin.js',array('jquery'),DHINC_VERSION,true);
		$dhAdminL10n = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'theme_url'=>get_template_directory_uri(),
			'framework_assets_url'=>DHINC_ASSETS_URL,
			'i18n_tooltip_mce_button'=>esc_js(__('Tooltip Shortcode','sitesao')),
			'tooltip_form'=>$this->_tooltip_form()
		);
		wp_localize_script('dh-admin', 'dhAdminL10n', $dhAdminL10n);
		wp_enqueue_script('dh-admin');
	}
	
	protected function _tooltip_form(){
		ob_start();
		?>
		<div class="dh-tooltip-form">
			<div class="dh-tooltip-options">
				<div>
					<label>
						<span><?php _e('Text','sitesao')?></span>
						<input data-id="text" type="text" placeholder="<?php _e('Text','sitesao')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('URL','sitesao')?></span>
						<input data-id="url" type="text" placeholder="<?php _e('http://','sitesao')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Type','sitesao')?></span>
						<select data-id="type">
							<option value="tooltip"><?php _e('Tooltip','sitesao') ?></option>
							<option value="popover"><?php _e('Popover','sitesao') ?></option>
						</select>
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Tip position','sitesao')?></span>
						<select data-id="position">
							<option value="top"><?php _e('Top','sitesao') ?></option>
							<option value="bottom"><?php _e('Bottom','sitesao') ?></option>
							<option value="left"><?php _e('Left','sitesao') ?></option>
							<option value="right"><?php _e('Right','sitesao') ?></option>
						</select>
					</label>
				</div>
				<div style="display: none;">
					<label>
						<span><?php _e('Title','sitesao')?></span>
						<input data-id="title" type="text" placeholder="<?php _e('Title','sitesao')?>">
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Content','sitesao')?></span>
						<textarea data-id="content" placeholder="<?php _e('Content','sitesao')?>"></textarea>
					</label>
				</div>
				<div>
					<label>
						<span><?php _e('Action to trigger','sitesao')?></span>
						<select data-id="trigger">
							<option value="hover"><?php _e('Hover','sitesao') ?></option>
							<option value="click"><?php _e('Click','sitesao') ?></option>
						</select>
					</label>
				</div>
			</div>
			<div class="submitbox">
				<div id="dh-tooltip-cancel">
					<a href="#"><?php _e('Cancel','sitesao')?></a>
				</div>
				<div id="dh-tooltip-update">
					<input type="button" class="button button-primary" value="<?php _e('Add Tooltip','sitesao')?>">
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
new DH_Admin();
endif;
