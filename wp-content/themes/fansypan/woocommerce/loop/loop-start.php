<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
?>
<?php 
global $wp_query,$woocommerce_loop;
$columns = isset($woocommerce_loop['columns']) && absint($woocommerce_loop['columns']) > 0 ? ' columns-'.absint($woocommerce_loop['columns']) : ' columns-'.dh_get_theme_option('woo-per-row',3);
$data_columns = isset($woocommerce_loop['columns']) && absint($woocommerce_loop['columns']) > 0 ? absint($woocommerce_loop['columns']) : absint(dh_get_theme_option('woo-per-row',3));

?>
<ul class="products<?php echo esc_attr($columns)?>" data-columns="<?php echo esc_attr($data_columns)?>" <?php echo (is_shop() || is_product_taxonomy() ? ' data-maxpage="'.absint($wp_query->max_num_pages).'"':'')?>>