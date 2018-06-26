<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Script
 */
wp_enqueue_script('vendor-carouFredSel');
wp_enqueue_script( 'wc-add-to-cart-variation' );

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;
$style = dh_get_theme_option('woo-grid-product-style','style-1');
// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if(dh_get_theme_option('woo-lazyload',1))
	$classes[] = 'unveil-image';

if(!dh_get_theme_option('woo-product-border',1))
	$classes[]=' product-no-border ';

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

if (dh_get_theme_option('woo-grid-product-style','style-1') =='style-1')
	$classes[] = 'style-1';
if (dh_get_theme_option('woo-grid-product-style','style-1') =='style-2')
	$classes[] = 'style-2';
if (dh_get_theme_option('woo-grid-product-style','style-1') =='style-3')
	$classes[] = 'style-3';
?>
<li <?php post_class( $classes ); ?>>
	<div class="product-container">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
		<figure>
			<div class="product-wrap">
				<div class="product-images">
					<a href="<?php the_permalink(); ?>">
						<?php if ( !$product->is_in_stock() ) : ?>            
			            	<span class="out_of_stock"><?php esc_html_e( 'Out of stock', 'woow' ); ?></span>            
						<?php endif; ?>
						<?php
							/**
							 * woocommerce_before_shop_loop_item_title hook
							 *
							 * @hooked woocommerce_show_product_loop_sale_flash - 10
							 * @hooked woocommerce_template_loop_product_thumbnail - 10
							 */
							do_action( 'woocommerce_before_shop_loop_item_title' );
						?>
					</a>
					<?php if(dh_get_theme_option('woo-grid-product-style') =='style-1' || dh_get_theme_option('woo-grid-product-style') =='style-3') : ?>
						<div class="loop-action">
							<?php 
								if(class_exists('DH_Woocommerce'))
								DH_Woocommerce::instance()->template_loop_quickview();
							?>
							<div class="loop-add-to-cart">
								<?php woocommerce_template_loop_add_to_cart();?>
							</div>
						</div>
					<?php endif;?>
				</div>
			</div>
			<figcaption>
				<div class="shop-loop-product-info">
					<div class="info-content-wrap">
						<?php if(dh_get_theme_option('woo-grid-product-style') !='style-3') : ?>
							<h3 class="product_title"><a href="<?php the_permalink()?>"><?php the_title(); ?></a></h3>

							<div class="info-price">
								<?php woocommerce_template_loop_price(); ?>
							</div>
						<?php else: ?>
							<h3 class="product_title"><a href="<?php the_permalink()?>"><?php the_title(); ?></a></h3>
							<div class="info-bottom clearfix">
								<div class="info-price">
									<?php woocommerce_template_loop_price(); ?>
								</div>
								<?php if(class_exists('DH_Woocommerce')):?>
									<div class="loop-add-to-wishlist">
										<?php DH_Woocommerce::instance()->template_loop_wishlist()?>
									</div>
								<?php endif;?>
							</div>
						<?php endif;?>
						
						<?php if(dh_get_theme_option('woo-grid-product-style') =='style-2') : ?>
							<div class="loop-action">
								<div class="loop-add-to-cart">
									<?php woocommerce_template_loop_add_to_cart();?>
								</div>
							</div>
						<?php endif;?>
					</div>
					
					<div class="info-excerpt">
						<?php echo wp_trim_words($post->post_excerpt,30)?>
					</div>

				</div>
			</figcaption>
		</figure>
	</div>
</li>