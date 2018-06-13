<header id="header" class="header-container header-type-toggle-offcanvas header-navbar-toggle-offcanvas" itemscope="itemscope" itemtype="<?php echo dh_get_protocol()?>://schema.org/Organization" role="banner">
	<div class="navbar-container">
		<div class="navbar navbar-default <?php if(dh_get_theme_option('sticky-menu',1)):?> navbar-scroll-fixed<?php endif;?>">
			<div class="navbar-default-wrap container">
				<div class="navbar-toggle-right">
					<?php if(dh_get_theme_option('ajaxsearch',1)){ ?>
					<div class="navbar-toggle-search">
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
						</div>
					</div>
					<?php } ?>
					<div class="navcart">
						<div class="navcart-wrap navbar-minicart-topbar">
							<?php 
							if(class_exists('DH_Woocommerce') && defined( 'WOOCOMMERCE_VERSION' ) && dh_get_theme_option( 'woo-cart-nav', 1 )){
								echo '<div class="navbar-minicart navbar-minicart-topbar">'.DH_Woocommerce::instance()->get_minicart().'</div>';
							}
							?>
						</div>
					</div>
					<?php 
					$login_url = wp_login_url();
					$logout_url = wp_logout_url();
					$register_url = wp_registration_url();
					if(defined('WOOCOMMERCE_VERSION')){
						$login_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
						$logout_url = wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );
					}
					?>
					
					<div class="navbar-user">
						<a title="<?php echo esc_attr__('My Account','woow'); ?>" <?php if (apply_filters('dh_user_login_modal', true)){?>data-rel="loginModal"<?php }?> href="<?php echo esc_url($login_url); ?>" class="navbar-user" href="#">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve">
								<g>
									<g>
										<path d="M118.95,92.012c-3.778-3.777-10.1-7.074-20.066-12.059c-5.024-2.512-13.386-6.691-15.413-8.605
											c8.489-10.434,13.416-22.219,13.416-32.535c0-7,0-15.711-3.918-23.48C89.437,8.336,81.544,0,64.002,0
											C46.456,0,38.563,8.336,35.035,15.332c-3.923,7.77-3.923,16.48-3.923,23.48c0,10.32,4.923,22.102,13.417,32.535
											c-2.032,1.918-10.393,6.098-15.417,8.605c-9.963,4.984-16.285,8.281-20.066,12.059c-8.369,8.375-9.002,22.426-9.045,25.16
											c-0.043,2.852,1.059,5.609,3.067,7.648c2,2.031,4.743,3.18,7.595,3.18h106.669c2.86,0,5.596-1.148,7.6-3.18
											c2.004-2.039,3.11-4.797,3.067-7.652C127.956,114.438,127.318,100.387,118.95,92.012z M119.235,119.203
											c-0.508,0.512-1.184,0.797-1.903,0.797H10.663c-0.707,0-1.398-0.289-1.895-0.797c-0.496-0.504-0.777-1.199-0.77-1.91
											c0.023-1.34,0.391-13.305,6.705-19.621c2.915-2.914,9.017-6.074,17.988-10.563c9.576-4.785,14.886-7.637,17.332-9.949l5.399-5.105
											l-4.688-5.758c-7.384-9.07-11.623-19.09-11.623-27.484c0-6.473,0-13.805,3.063-19.875C45.842,11.68,53.179,8,64.002,8
											c10.814,0,18.159,3.68,21.824,10.934c3.063,6.074,3.063,13.406,3.063,19.879c0,8.391-4.235,18.41-11.628,27.484l-4.688,5.762
											l5.4,5.102c2.445,2.309,7.751,5.16,17.331,9.949c8.971,4.484,15.073,7.645,17.988,10.563c5.138,5.137,6.634,14.75,6.704,19.621
											C120.009,118.004,119.731,118.699,119.235,119.203z"/>
									</g>
								</g>
							</svg>
						</a>
						<?php if(defined('YITH_WCWL') || is_user_logged_in()):?>
						<ul  class="dropdown-menu">
							<?php if(defined('YITH_WCWL') && apply_filters('dh_show_wishlist_in_header', true)):?>
	            			<li>
	            				<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url());?>"><i class="fa fa-heart-o"></i> <?php esc_html_e('My Wishlist','woow')?></a>
	            			</li>
	            			<?php endif;?>
							<?php
							if(is_user_logged_in()):
							?>
							<li>
								<a href="<?php echo esc_url($logout_url) ?>"><i class="fa fa-sign-out"></i> <?php esc_html_e('Logout', 'woow'); ?></a>
							</li>
							<?php
							endif;
							?>
						</ul>
						<?php endif;?>
					</div>
				</div>
				<div class="navbar-header">
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
				<div class="navbar-toggle-fixed">
					<button type="button" class="navbar-toggle">
						<span class="sr-only"><?php echo esc_html__('Toggle navigation','woow')?></span>
						<span class="icon-bar bar-top"></span> 
						<span class="icon-bar bar-middle"></span> 
						<span class="icon-bar bar-bottom"></span>
					</button>
				</div>
			</div>
			<div class="header-search-overlay hide">
				<div class="<?php echo dh_container_class()?>">
					<div class="header-search-overlay-wrap">
						<?php echo dh_get_search_form()?>
						<button type="button" class="close">
								<span aria-hidden="true">&times;</span><span class="sr-only"><?php echo esc_html__('Close','woow') ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>