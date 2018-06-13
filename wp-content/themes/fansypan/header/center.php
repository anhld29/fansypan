<?php 
global $woocommerce;
$login_url = wp_login_url();
$logout_url = wp_logout_url();
$register_url = wp_registration_url();
if(defined('WOOCOMMERCE_VERSION')){
	$login_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
	$logout_url = wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) );
}
?>
<header id="header" class="header-container page-heading-<?php echo esc_attr($page_heading) ?> header-type-center header-navbar-center<?php if($menu_transparent):?> header-absolute header-transparent<?php if($menu_transparent == '2'){echo ' header-transparent-white';} ?><?php endif?> header-scroll-resize" itemscope="itemscope" itemtype="<?php echo dh_get_protocol()?>://schema.org/Organization" role="banner">
	<?php if(dh_get_theme_option('show-topbar',1)):?>
	<div class="topbar">
		<div class="<?php dh_container_class() ?> topbar-wap">
			<div class="row">
				<div class="col-sm-6 col-left-topbar">
					<div class="left-topbar">
            			<?php
            			
            				$left_topbar_content = dh_get_theme_option('left-topbar-content','info');
	            			if($left_topbar_content === 'info'): 
	            				echo '<div class="topbar-info">';
	            				if(dh_get_theme_option('left-topbar-phone','(123) 456 789'))
	            					echo '<a href="#"><i class="fa fa-phone"></i> '.esc_html(dh_get_theme_option('left-topbar-phone','(123) 456 789')).'</a>';
	            				if(dh_get_theme_option('left-topbar-email','info@domain.com'))
	            					echo '<a href="mailto:'.esc_attr(dh_get_theme_option('left-topbar-email','info@domain.com')).'"><i class="fa fa-envelope-o"></i> '.esc_html(dh_get_theme_option('left-topbar-email','info@domain.com')).'</a>';
	            				if(dh_get_theme_option('left-topbar-skype','skype.name'))
	            					echo '<a href="skype:'.esc_attr(dh_get_theme_option('left-topbar-skype','skype.name')).'?call"><i class="fa fa-skype"></i> '.esc_html(dh_get_theme_option('left-topbar-skype','skype.name')).'</a>';
	            				echo '</div>';
	            			elseif ($left_topbar_content === 'custom'):
	            				echo (dh_get_theme_option('left-topbar-custom-content',''));
	            			endif;
	            			?>
	            			<?php 
	            			if(($left_topbar_content == 'menu_social')):
	            			echo '<div class="topbar-social">';
	            			dh_social(dh_get_theme_option('left-topbar-social',array('facebook','twitter','google-plus','pinterest','rss','instagram')),true);
	            			echo '</div>';
	            			endif;
            			
            			?>
            			
					</div>
				</div>
				<div class="col-sm-6 col-right-topbar">
					<div class="right-topbar">
						<div class="user-login">
            				<ul class="nav top-nav">
            					<?php 
            					if ( has_nav_menu( 'top' ) ) :
            					wp_nav_menu( array(
            						'theme_location'    => 'top',
            						'depth'             => 2,
            						'container'         => false,
            						'items_wrap' 		=> '%3$s',
            						'walker'            => new DH_Walker
            					));
            					endif;
            					if(apply_filters('dh_topbar_account_menu', true)){
            					?>
            					<li class="menu-item<?php if(is_user_logged_in()):?> menu-item-has-children dropdown<?php endif;?>">
            						<a <?php if(!is_user_logged_in()): ?>  <?php if (apply_filters('dh_user_login_modal', true)){?>data-rel="loginModal"<?php }?> <?php endif;?> href="<?php echo esc_url($login_url) ?>"> 
            							<?php if(!is_user_logged_in()): ?><?php esc_html_e('Login','woow')?><?php else:?><?php esc_html_e('Account','woow')?><?php endif;?>
		            					<?php if(is_user_logged_in()):?>
			            					<span class="caret"></span>
			            				<?php endif;?>
		            				</a>
		            				<?php if(is_user_logged_in()):?>
									<ul class="dropdown-menu" role="menu">
										<li class="menu-item">
											<a href="<?php echo esc_url($logout_url) ?>"><i class="fa fa-sign-out"></i> <?php esc_html_e('Logout', 'woow'); ?></a>
										</li>
									</ul>
									<?php endif;?>
            					</li>
            					<?php }?>
            				</ul>
            			</div>

						<?php if(function_exists('icl_get_languages') && apply_filters('dhicl_languages_switcher', true)){?>
							<div class="language-switcher">
								<?php 
								//do_action('icl_language_selector'); 
								$languages = icl_get_languages();
								if( is_array( $languages ) ){
								
									foreach( $languages as $lang_k=>$lang ){
										if( $lang['active'] ){
											$active_lang = $lang;
											unset( $languages[$lang_k] );
										}
									}
										
									// disabled
									if( count( $languages ) ){
										$lang_status = 'enabled';
									} else {
										$lang_status = 'disabled';
									}
								
									echo '<div class="wpml-languages '. $lang_status .'">';
									echo '<a class="active" href="'. $active_lang['url'] .'" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
									echo '<img src="'. $active_lang['country_flag_url'] .'" alt="'. $active_lang['translated_name'] .'"/> '.strtoupper($active_lang['language_code']);
									echo '</a>';
										
									if( count( $languages ) ){
										echo '<ul class="wpml-lang-dropdown dropdown-menu">';
										foreach( $languages as $lang ){
											echo '<li><a href="'. $lang['url'] .'"><img src="'. $lang['country_flag_url'] .'" alt="'. $lang['translated_name'] .'"/> '.strtoupper($lang['language_code']).'</a></li>';
										}
										echo '</ul>';
									}
										
									echo '</div>';
								
								}
								?>
							</div>
						<?php } ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
	<div class="navbar-container">
		<div class="navbar navbar-default <?php if(dh_get_theme_option('sticky-menu',1)):?> navbar-scroll-fixed<?php endif;?>">
			<div class="navbar-default-wrap">
				<div class="<?php dh_container_class() ?>">
					<div class="row">
						<div class="col-md-12 navbar-default-col">
							<div class="navbar-wrap">
								<div class="navbar-header">
									<div class="navbar-header-left">
										<div class="social">
							            	<?php dh_social(dh_get_theme_option('header-left-social',array()),true); ?>
							            </div>
									</div>

									<div class="navbar-header-center">
										<button <?php /*data-target=".primary-navbar-collapse" data-toggle="collapse"*/?> type="button" class="navbar-toggle">
											<span class="sr-only"><?php echo esc_html__('Toggle navigation','woow')?></span>
											<span class="icon-bar bar-top"></span> 
											<span class="icon-bar bar-middle"></span> 
											<span class="icon-bar bar-bottom"></span>
										</button>
										<?php if(dh_get_theme_option('ajaxsearch',1)){ ?>
										<a class="navbar-search-button search-icon-mobile" href="#">
											<svg xml:space="preserve" style="enable-background:new 0 0 612 792;" viewBox="0 0 612 792" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1">
												<g>
													<g>
														<g>
															<path d="M231,104c125.912,0,228,102.759,228,229.5c0,53.034-18.029,101.707-48.051,140.568l191.689,192.953
																c5.566,5.604,8.361,12.928,8.361,20.291c0,7.344-2.795,14.688-8.361,20.291C597.091,713.208,589.798,716,582.5,716
																s-14.593-2.792-20.139-8.396L370.649,514.632C332.043,544.851,283.687,563,231,563C105.088,563,3,460.241,3,333.5
																S105.088,104,231,104z M231,505.625c94.295,0,171-77.208,171-172.125s-76.705-172.125-171-172.125
																c-94.295,0-171,77.208-171,172.125S136.705,505.625,231,505.625z"/>
														</g>
													</g>
												</g>
											</svg>
										</a>
										<?php } ?>
								    	<?php if(defined('WOOCOMMERCE_VERSION') && dh_get_theme_option('woo-cart-mobile',1)):?>
								     	<?php 
								     	$cart_url = wc_get_cart_url();
								     	?>
										<a class="cart-icon-mobile" href="<?php echo esc_url($cart_url) ?>"><i class="elegant_icon_bag_alt"></i><span><?php echo absint($woocommerce->cart->cart_contents_count)?></span></a>
										<?php endif;?>
										<<?php echo apply_filters('dh_logo_tag', 'div')?> class="navbar-brand-title">
											<a class="navbar-brand" itemprop="url" title="<?php esc_attr(bloginfo( 'name' )); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
												<?php if(!empty($logo_url)):?>
													<img class="logo" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_url)?>">
												<?php else:?>
													<?php echo bloginfo( 'name' ) ?>
												<?php endif;?>
												<img class="logo-fixed" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_fixed_url)?>">
												<img class="logo-mobile" alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url($logo_mobile_url)?>">
												<span itemprop="name" class="sr-only sr-only-focusable"><?php bloginfo('name')?></span>
											</a>
										</<?php echo apply_filters('dh_logo_tag', 'div')?>>
									</div>

									<div class="navbar-header-right">
				            			<?php 
											if(dh_get_theme_option('ajaxsearch',1)){
										?>
											<div class="navbar-search">
												<a class="navbar-search-button" href="#">
													<svg xml:space="preserve" style="enable-background:new 0 0 612 792;" viewBox="0 0 612 792" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1">
														<g>
															<g>
																<g>
																	<path d="M231,104c125.912,0,228,102.759,228,229.5c0,53.034-18.029,101.707-48.051,140.568l191.689,192.953
																		c5.566,5.604,8.361,12.928,8.361,20.291c0,7.344-2.795,14.688-8.361,20.291C597.091,713.208,589.798,716,582.5,716
																		s-14.593-2.792-20.139-8.396L370.649,514.632C332.043,544.851,283.687,563,231,563C105.088,563,3,460.241,3,333.5
																		S105.088,104,231,104z M231,505.625c94.295,0,171-77.208,171-172.125s-76.705-172.125-171-172.125
																		c-94.295,0-171,77.208-171,172.125S136.705,505.625,231,505.625z"/>
																</g>
															</g>
														</g>
													</svg>
												</a>
												<div class="search-form-wrap show-popup hide">
												<?php dh_get_search_form(); ?>
												</div>
											</div>
										<?php
										}
										?>
				            			<?php 
											if(class_exists('DH_Woocommerce') && defined( 'WOOCOMMERCE_VERSION' ) && dh_get_theme_option( 'woo-cart-nav', 1 )){
												echo '<div class="navbar-minicart navbar-minicart-topbar">'.DH_Woocommerce::instance()->get_minicart().'</div>';
											}
										?>
										<?php if(defined('YITH_WCWL') && apply_filters('dh_show_wishlist_in_header', true)):?>
				            			<div class="navbar-wishlist">
				            				<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url());?>">
				            					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
													 viewBox="0 0 249.3 249.3" style="enable-background:new 0 0 249.3 249.3;" xml:space="preserve">
												<path d="M249.183,88.644c-0.94-17.571-8.379-34.07-20.947-46.458c-12.756-12.574-29.414-19.499-46.904-19.499
													c-26.191,0-44.735,20.23-54.697,31.099c-0.567,0.618-1.176,1.282-1.771,1.924c-0.319-0.362-0.636-0.723-0.938-1.067
													C114.82,44.27,95.87,22.687,67.972,22.687c-17.49,0-34.147,6.925-46.903,19.499C8.5,54.574,1.061,71.073,0.121,88.644
													c-0.934,17.467,3.507,32.624,14.396,49.146c9.759,14.811,35.173,38.228,53.97,53.78c19.32,15.986,44.767,35.042,56.272,35.042
													c11.686,0,37.043-19.016,56.256-34.968c18.651-15.485,43.925-38.883,53.775-53.86C242.119,126.64,250.379,110.983,249.183,88.644z
													 M222.258,129.542c-7.157,10.885-27.331,30.995-50.201,50.044c-27.269,22.714-43.414,31.666-47.286,32.022
													c-3.866-0.403-20.051-9.445-47.453-32.201c-23.004-19.104-43.208-39.146-50.276-49.871c-9.011-13.672-12.694-26.036-11.943-40.092
													c1.527-28.539,25.246-51.758,52.872-51.758c21.107,0,36.443,17.468,44.683,26.851c4.844,5.518,7.513,8.557,11.999,8.557
													c4.631,0,7.618-3.259,13.04-9.174c8.994-9.813,24.047-26.234,43.639-26.234c27.627,0,51.345,23.219,52.873,51.758
													C234.965,103.659,231.392,115.652,222.258,129.542z"/>
												</svg>
				            				</a>
				            			</div>
				            			<?php endif;?>
									</div>

								</div>


								<nav class="collapse navbar-collapse primary-navbar-collapse" itemtype="<?php echo dh_get_protocol() ?>://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation">
									<?php
									$page_menu = '' ;
									if(is_page() && ($selected_page_menu = dh_get_post_meta('main_menu'))){
										$page_menu = $selected_page_menu;
									}
									if(has_nav_menu('primary') || !empty($page_menu)):
										wp_nav_menu( array(
											'theme_location'    => 'primary',
											'container'         => false,
											'depth'				=> 3,
											'menu'				=> $page_menu,
											'menu_class'        => 'nav navbar-nav primary-nav',
											'walker' 			=> new DH_Mega_Walker
										) );
									else:
										echo '<ul class="nav navbar-nav primary-nav"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . esc_html__( 'No menu assigned!', 'woow' ) . '</a></li></ul>';
									endif;
									?>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="header-search-overlay hide">
				<div class="<?php echo dh_container_class()?>">
					<div class="header-search-overlay-wrap">
						<?php echo dh_get_search_form()?> 
						<button type="button" class="close">
							<span aria-hidden="true" class="fa fa-times"></span><span class="sr-only"><?php echo esc_html__('Close','woow') ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>