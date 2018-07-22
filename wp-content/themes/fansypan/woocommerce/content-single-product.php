<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

wp_enqueue_script('vendor-carouFredSel');

if(dh_get_theme_option('single-product-popup','popup') == 'easyzoom'){
	wp_enqueue_script('vendor-easyzoom');
}else{
	wp_enqueue_style('vendor-magnific-popup');
	wp_enqueue_script('vendor-magnific-popup');
}


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$style = dh_get_theme_option('single-product-style','style-1');
$layout = dh_get_theme_option('woo-product-layout','full-width');
?>
<?php if($layout == 'full-width'):?>
<div class="<?php dh_container_class()?>">
	<div class="row">
		<div class="col-md-12 no-min-height">
			<?php
				/**
				 * woocommerce_before_single_product hook
				 *
				 * @hooked wc_print_notices - 10
				 */
				 do_action( 'woocommerce_before_single_product' );
			
				 if ( post_password_required() ) {
				 	echo get_the_password_form();
				 	return;
				 }
			?>		
		</div>
	</div>
</div>
<?php else:?>
	<?php
			/**
			 * woocommerce_before_single_product hook
			 *
			 * @hooked wc_print_notices - 10
			 */
			 do_action( 'woocommerce_before_single_product' );
		
			 if ( post_password_required() ) {
			 	echo get_the_password_form();
			 	return;
			 }
		?>
<?php endif;?>
<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class($style); ?>>
	<?php if($layout == 'full-width'):?>
	<div class="<?php dh_container_class()?>">
	<?php endif;?>
		<div class="row summary-container">
			<div class="col-md-6 <?php echo ($layout == 'full-width' ? 'col-sm-6':'col-sm-12') ?> entry-image">
				<div class="single-product-images">
				<?php
					/**
					 * woocommerce_before_single_product_summary hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
				?>
				</div>
			</div>
			<div class="col-md-6 <?php echo ($layout == 'full-width' ? 'col-sm-6':'col-sm-12') ?> entry-summary">
				<div class="summary">
			
					<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */
						if ( !dh_get_theme_option( 'show-woo-meta', 1 )) {
							remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
							remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
						}
						
						do_action( 'woocommerce_single_product_summary' );
						// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
					?>
			
				</div><!-- .summary -->
				
			</div>
		</div>
	<?php if($layout == 'full-width'):?>
	</div>
	<?php 
	woocommerce_output_product_data_tabs();
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php
					echo do_shortcode( '[wc_quick_buy_link]' );
					echo do_shortcode('[vivafbcomment]');
				?>
			</div>
		</div>
	</div>
	
	<div class="<?php dh_container_class()?>">
	<?php endif;?>
		<div class="row">
			<div class="col-sm-12">
				<?php
					/**
					 * woocommerce_after_single_product_summary hook
					 *
					 * @hooked woocommerce_output_product_data_tabs - 10
					 * @hooked woocommerce_output_related_products - 20
					 */
					do_action( 'woocommerce_after_single_product_summary' );
				?>
			</div>
		</div>
	<?php if($layout == 'full-width'):?>
	</div>
	<?php endif;?>
	<?php 
		// do_action( 'woocommerce_template_single_sharing', 50 );
	?>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</div><!-- #product-<?php the_ID(); ?> -->
<?php do_action( 'woocommerce_after_single_product' ); ?>

