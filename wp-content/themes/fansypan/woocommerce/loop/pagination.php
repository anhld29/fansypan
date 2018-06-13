<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;
$woo_products_pagination = dh_get_theme_option('woo-products-pagination','page_num');
?>
<nav class="woocommerce-pagination">
  <?php woocommerce_result_count()?>
  <?php
  if ( $wp_query->max_num_pages > 1 )
   	dh_paginate_links();
  ?>
</nav>
<?php if($woo_products_pagination === 'loadmore' && 1 < $wp_query->max_num_pages):?>
<div class="loadmore-action">
	<div class="loadmore-loading"><div class="fade-loading"><i></i><i></i><i></i><i></i></div></div>
	<button type="button" class="btn-loadmore"><?php echo esc_html(dh_get_theme_option('woo-products-loadmore-text','Load More')) ?></button>
</div>
<?php endif;?>