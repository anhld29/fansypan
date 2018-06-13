<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce, $woocommerce_loop;
if(function_exists('wc_get_related_products')){
	if(empty($cross_sells)){
		$cart_rand_id = 0;
		if(absint( $woocommerce->cart->cart_contents_count ) && sizeof( $cross_sells ) == 0){
			$cart_products_arr = array();
			foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
				$cart_product = $cart_item['data'];
				$cart_products_arr[] = $cart_product->get_id();
			}
			$cart_rand_id = $cart_products_arr[array_rand($cart_products_arr, 1)];
			$cart_rand_product = wc_get_product($cart_rand_id);
		}
		$cross_sells = wc_get_related_products($cart_rand_product->get_id(),$posts_per_page);
		$cross_sells = array_filter( array_map( 'wc_get_product', $cross_sells ), 'wc_products_array_filter_visible' );
	}
	if ( sizeof( $cross_sells ) == 0 ) return;
	?>
	<div class="cross-sells">
		<h2><?php esc_html_e( 'You may be interested in&hellip;', 'woow' ) ?></h2>
		<ul class="products columns-2">

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
				 	$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		</ul>
	</div>
	<?php
	wp_reset_postdata();
}else{
	$crosssells = WC()->cart->get_cross_sells();
	$crosssells_flag=false;
	$cart_rand_id = 0;
	if(absint( $woocommerce->cart->cart_contents_count ) && sizeof( $crosssells ) == 0 && apply_filters('dh_use_custom_cross_sell', true)){
		$cart_products_arr = array();
		foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
			$cart_product = $cart_item['data'];
			$cart_products_arr[] = $cart_product->get_id();
		}
		$cart_rand_id = $cart_products_arr[array_rand($cart_products_arr, 1)];
		$cart_rand_product = get_product($cart_rand_id);
		$crosssells = $cart_rand_product->get_related();
		$crosssells_flag=true;
	}
	if ( sizeof( $crosssells ) == 0 ) return;
	
	$meta_query = WC()->query->get_meta_query();
	
	$args = array(
		'post_type'           => 'product',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'posts_per_page'      => apply_filters( 'woocommerce_cross_sells_total', $posts_per_page ),
		'orderby'             => $orderby,
		'post__in'            => $crosssells,
		'meta_query'          => $meta_query
	);
	if($crosssells_flag){
		$args['post__not_in'] = array( $cart_rand_id ); 
	}
	
	$products = new WP_Query( $args );
	
	$woocommerce_loop['columns'] = apply_filters( 'woocommerce_cross_sells_columns', $columns );
	
	if ( $products->have_posts() ) : ?>
	
		<div class="cross-sells">
			<h2><?php esc_html_e( 'You may be interested in&hellip;', 'woow' ) ?></h2>
			<ul class="products columns-2">
	
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
	
					<?php wc_get_template_part( 'content', 'product' ); ?>
	
				<?php endwhile; // end of the loop. ?>
	
			</ul>
		</div>
	<?php endif;
	
	wp_reset_postdata();
}