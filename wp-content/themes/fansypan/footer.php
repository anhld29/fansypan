	<footer id="footer" class="footer" role="contentinfo">
		<?php if(dh_get_theme_option('footer-featured',1) && dh_get_theme_option('footer-featured-style','default') == 'light'):?>
			<div class="footer-featured <?php echo esc_attr(dh_get_theme_option('footer-featured-style','default'));?>">
				<div class="<?php dh_container_class() ?>">
					<div class="row">
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-1')));?>
						</div>
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-2')));?>
						</div>
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-3')));?>
						</div>
					</div>
				</div>
			</div>
		<?php endif;?>
		
		<?php if(dh_get_theme_option('footer-newsletter',1)):?>
			<div class="footer-newsletter">
				<div class="<?php dh_container_class() ?>">
					<div class="footer-newsletter-wrap">
						<h3 class="footer-newsletter-heading"><?php esc_html_e('NEWSLETTER','woow')?></h3>
						<?php dh_mailchimp_form();?>
					</div>
				</div>
			</div>
		<?php endif;?>

		<?php if(dh_get_theme_option('footer-featured',1) && dh_get_theme_option('footer-featured-style','default') == 'default'):?>
			<div class="footer-featured <?php echo esc_attr(dh_get_theme_option('footer-featured-style','default'));?>">
				<div class="<?php dh_container_class() ?>">
					<div class="row">
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-1')));?>
						</div>
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-2')));?>
						</div>
						<div class="footer-featured-col col-md-4 col-sm-6">
							<?php echo dh_print_string(do_shortcode(dh_get_theme_option('footer-featured-3')));?>
						</div>
					</div>
				</div>
			</div>
		<?php endif;?>

		<?php if(dh_get_theme_option('footer-area',1)):?>
			<div class="footer-widget <?php echo 'footer-'.dh_get_theme_option('footer-area-columns',4).'-widget'?>">
				<div class="<?php dh_container_class() ?>">
					<div class="footer-widget-wrap">
						<div class="row">
							<?php 
							$area_columns = dh_get_theme_option('footer-area-columns',4);
							if($area_columns == '5'):
								?>
								<?php if(is_active_sidebar('sidebar-footer-1')):?>
								<div class="footer-widget-col col-md-2 col-sm-6">
									<?php dynamic_sidebar('sidebar-footer-1')?>
								</div>
								<?php endif;?>
								<?php if(is_active_sidebar('sidebar-footer-2')):?>
								<div class="footer-widget-col col-md-2 col-sm-6">
									<?php dynamic_sidebar('sidebar-footer-2')?>
								</div>
								<?php endif;?>
								<?php if(is_active_sidebar('sidebar-footer-3')):?>
								<div class="footer-widget-col col-md-2 col-sm-6">
									<?php dynamic_sidebar('sidebar-footer-3')?>
								</div>
								<?php endif;?>
								<?php if(is_active_sidebar('sidebar-footer-4')):?>
								<div class="footer-widget-col col-md-2 col-sm-6">
									<?php dynamic_sidebar('sidebar-footer-4')?>
								</div>
								<?php endif;?>
								<?php if(is_active_sidebar('sidebar-footer-5')):?>
								<div class="footer-widget-col col-md-4 col-sm-6">
									<?php dynamic_sidebar('sidebar-footer-5')?>
								</div>
								<?php endif;?>
								<?php
							else:
							$area_class = '';
								if($area_columns == '2'){
									$area_class = 'col-md-6 col-sm-6';
								}elseif ($area_columns == '3'){
									$area_class = 'col-md-3 col-sm-6';
								}elseif ($area_columns == '4'){
									$area_class = 'col-md-3 col-sm-6';
								}
								?>
								<?php for ( $i = 1; $i <= $area_columns ; $i ++ ) :?>
									<?php if(is_active_sidebar('sidebar-footer-'.$i)):?>
									<div class="footer-widget-col <?php if ($i==1) {echo 'col-md-6 col-sm-12'; } else{ echo esc_attr($area_class); } ?>">
										<?php dynamic_sidebar('sidebar-footer-'.$i)?>
									</div>
									<?php endif;?>
								<?php endfor;?>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		<?php endif;?>

		<?php if($footer_info = dh_get_theme_option('footer-info')):?>
			<div class="footer-copyright text-center"><?php echo ($footer_info) ?></div>
    	<?php endif;?>
	</footer>
	
</div>
<?php wp_footer(); ?>
</body>
</html>