<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;
if(empty($product)){
	return '';
}
if(function_exists('wc_get_related_products') && $related_products){
	?>
		<div class="related products">
			<div class="related-title">
				<h3><span><?php esc_html_e( 'We know you will love', 'woow' ); ?></span></h3>
			</div>
			<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				 	$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>
	
		</div>
	<?php
	wp_reset_postdata();
}else{
	$related = $product->get_related( $posts_per_page );

	if ( sizeof( $related ) == 0 ) return;
	
	$args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'no_found_rows'        => 1,
		'posts_per_page'       => $posts_per_page,
		'orderby'              => $orderby,
		'post__in'             => $related,
		'post__not_in'         => array( $product->get_id() )
	) );
	
	$products = new WP_Query( $args );
	
	$woocommerce_loop['columns'] = $columns;
	
	if ( $products->have_posts() ) : ?>
	
		<div class="related products">
			<div class="related-title">
				<h3><span><?php esc_html_e( 'We know you will love', 'woow' ); ?></span></h3>
			</div>
			<?php woocommerce_product_loop_start(); ?>
	
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	
					<?php wc_get_template_part( 'content', 'product' ); ?>
	
				<?php endwhile; // end of the loop. ?>
	
			<?php woocommerce_product_loop_end(); ?>
	
		</div>
	
	<?php endif;
	
	wp_reset_postdata();
}


