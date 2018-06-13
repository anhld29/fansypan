<?php
extract(shortcode_atts(array(
	'id'=>'',
	'product_image'=>'',
	'el_class'=>'',
	'visibility'=>'',
),$atts));

$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$el_class .= dh_visibility_class($visibility);

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 1,
	'no_found_rows'  => 1,
	'p'				 =>absint($id),
	'post_status'    => 'publish',
	'meta_query'     => $meta_query
);
$products = new WP_Query( $args );
if($products->have_posts()):
?>
<div class="special-product <?php echo esc_attr($el_class)?>">
	<div class="special-product-wrap">
<?php
	while ($products->have_posts()): $products->the_post();
	global $post,$product;
	?>
	<div class="special-product-image">
		<a href="<?php the_permalink()?>">
		<?php if($image = wp_get_attachment_image(absint($product_image),'shop_single',false,array('title'=>get_the_title()))):?>
		<?php echo $image?>
		<?php elseif (has_post_thumbnail()):?>
			<?php 
			$image_title = esc_attr( get_the_title( ) );
			$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title' => $image_title
			) );
			echo $image
			?>
		<?php else:?>
			<?php echo sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ); ?>
		<?php endif;?>
		</a>
	</div>
	<div class="special-product-info">
		<h3 class="product_title">
			<a href="<?php the_permalink()?>"><?php the_title(); ?></a>
		</h3>
		<div class="info-price">
			<?php woocommerce_template_loop_price(); ?>
		</div>
		<div class="loop-action">
			<div class="loop-add-to-cart">
				<?php woocommerce_template_loop_add_to_cart();?>
			</div>
		</div>
	</div>
	<?php
	endwhile;
?>	
	</div>
</div>
<?php
endif;
