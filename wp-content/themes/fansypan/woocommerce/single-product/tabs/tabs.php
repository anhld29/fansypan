<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version    2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs() 0905032132
 */
add_filter( 'woocommerce_product_tabs', 'helloacm_remove_product_review', 99);
function helloacm_remove_product_review($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
$layout = dh_get_theme_option('woo-product-layout','full-width');

if ( ! empty( $tabs ) ) : ?>
<div class="woocommerce-tab-container woocommerce-tab-<?php echo $layout ?>">
	<?php if($layout == 'full-width'):?>
	<div class="<?php dh_container_class() ?>">
		<div class="row">
			<div class="col-md-12">
	<?php endif;?>
				<div class="tabbable woocommerce-tabs">
					<ul class="nav nav-tabs wc-tabs" role="tablist">
						<?php $i = 0; ?>
						<?php foreach ( $tabs as $key => $tab ) : ?>
							<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo ($i==0 ? 'active':'') ?>">
								<a role="tab" href="#tab-<?php echo esc_attr($key) ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
							</li>
						<?php $i++; ?>
						<?php endforeach; ?>
					</ul>
					<div class="tab-content">
						<?php $i = 0; ?>
						<?php foreach ( $tabs as $key => $tab ) : ?>
				
							<div class="tab-pane wc-tab <?php echo ($i==0 ? 'active':'') ?>" id="tab-<?php echo esc_attr($key) ?>">
								<?php call_user_func( $tab['callback'], $key, $tab ) ?>
							</div>
						<?php $i++; ?>
						<?php endforeach; ?>
					</div>
				</div>
	<?php if($layout == 'full-width'):?>
			</div>
		</div>
	</div>
	<?php endif;?>
</div>
<?php endif; ?>