<?php
$output = array();
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$hide_action = !empty($hide_action) ? true : false;
$portfolio_action_link_to = ($portfolio_action_link_to == 'single_url') ? false : true;


$class          = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
switch ($visibility) {
	case 'hidden-phone':
		$class .= ' hidden-xs';
		break;
	case 'hidden-tablet':
		$class .= ' hidden-sm hidden-md';
		break;
	case 'hidden-pc':
		$class .= ' hidden-lg';
		break;
	case 'visible-phone':
		$class .= ' visible-xs-inline';
		break;
	case 'visible-tablet':
		$class .= ' visible-sm-inline visible-md-inline';
		break;
	case 'visible-pc':
		$class .= ' visible-lg-inline';
		break;
}
if($layout == 'masonry'){
	/**
	 * script
	 * {{
	 */
	wp_enqueue_script('vendor-isotope');
}
if($pagination === 'infinite_scroll'){
	/**
	 * script
	 * {{
	 */
	wp_enqueue_script('vendor-infinitescroll');
}

if( is_front_page() || is_home()) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
} else {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
}
$gap_class=' no-gap';
if(!empty($gap)){
	if($gap === 'onepx'){
		$gap_class =' onepx';
	}else{
		$gap_class =' gap';
	}
}
$show_date = empty($hide_date) ? true : false;
$show_comment = empty($hide_comment) ? true : false;
$show_category = empty($hide_category) ? true : false;
$show_author = empty($hide_author)  ? true : false;
$filter_type = empty($hide_sorting) ? 'default'  : $filter_type;

$order = 'DESC';
switch ($orderby) {
	case 'latest':
		$orderby = 'date';
		break;

	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;

	case 'alphabet':
		$orderby = 'title';
		$orderby = 'ASC';
		break;

	case 'ralphabet':
		$orderby = 'title';
		break;

	default:
		$orderby = 'date';
		break;
}

$args = array(
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "portfolio",
	'posts_per_page'  => "-1",
	'paged'			  => $paged
);
if(!empty($posts_per_page))
	$args['posts_per_page'] = $posts_per_page;

if(!empty($categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'portfolio_category',
			'terms'    => explode(',',$categories),
			'field'    => 'slug',
			'operator' => 'IN'
	);
}
if(!empty($exclude_categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'portfolio_category',
			'terms'    => explode(',',$exclude_categories),
			'field'    => 'slug',
			'operator' => 'NOT IN'
	);
}
$r = new WP_Query($args);
if($r->have_posts()):
//reset for wall layout
if($layout == 'wall'):
	$gap_class = '';
	$style = 'wall';
	$hide_action = true;
endif;
?>
<div class="portfolio portfolio-style-<?php echo esc_attr($style) ?> <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($layout === 'masonry') ? ' masonry':'') ?><?php echo esc_attr($gap_class) ?><?php echo esc_attr($class) ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($layout) ?>" <?php echo ($layout === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
	<?php if(($layout === 'masonry') && (empty($hide_filter) || empty($hide_sorting))):?>
	<?php if(!empty($wrap_filter)):?>
	<div class="container">
	<?php endif;?>
		<div class="portfolio-filter">
			<?php if($filter_type != 'center'){?>
			<div class="filter-heaeding">
				<h3><?php echo __('All','woow')?></h3>
			</div>
			<?php } ?>
			<div class="filter-action filter-action-<?php echo esc_attr($filter_type)?> <?php echo (empty($hide_sorting) ? 'sorting':'no-sorting');?>">
				<?php if(empty($hide_sorting)):?>
					<a href="#"  data-sort="date" class="sort-btn current reversed"><?php esc_html_e('Date','woow') ?> <i class="fa fa-sort"></i></a>
					<a href="#"  data-sort="title" class="sort-btn"><?php esc_html_e('Name','woow') ?> <i class="fa fa-sort"></i></a>
				<?php endif;?>
				<?php if(empty($hide_filter)):?>
					<?php if(empty($hide_sorting)):?>
					<a href="#" class="filter-btn"><?php esc_html_e('Filters','woow') ?> <i class="fa fa-bars"></i></a>
					<?php endif;?>
					<ul data-filter-key="filter">
						<li>
							<a class="selected" href="#" data-filter-value= "*"><?php echo __('All','woow') ?></a>
						</li>
						<?php $category_arr = explode(',', $categories); ?>
						<?php $category_arr = array_filter($category_arr)?>
						<?php if(is_array($category_arr) && count($category_arr) > 0):?>
							<?php foreach ($category_arr as $cat):?>
								<?php if($cat):?>
									<?php $category = get_term_by('slug',$cat, 'portfolio_category'); ?>
									<?php if($category): ?>
									<li>
										<a href="#" data-filter-value= ".<?php echo esc_attr($category->slug) ?>"><?php echo esc_html($category->name); ?></a>
									</li>
									<?php endif;?>
								<?php endif;?>
							<?php endforeach;?>
						<?php else:?>
							<?php foreach ((array) get_terms('portfolio_category') as $category):?>
								<?php 
									if ( empty($category->slug ) )
										continue;
								?>
								<li>
									<a href="#" data-filter-value= ".<?php echo esc_attr($category->slug) ?>"><?php echo esc_html($category->name); ?></a>
								</li>
							<?php endforeach;?>
						<?php endif;?>
					</ul>
				<?php endif;?>
			</div>
		</div>
	<?php if(!empty($wrap_filter)):?>
	</div>
	<?php endif;?>
	<?php endif;?>
	<div class="portfolio-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($layout === 'masonry') ? ' masonry-wrap':'') ?> portfolio-layout-<?php echo esc_attr($layout)?><?php if($layout == 'grid' || $layout == 'masonry') echo' row' ?>">
		<?php 
		$post_class = 'portfolio-item';
		$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
		$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
		
			
		if($layout == 'masonry'){
			$post_class .=' masonry-item ';
		}
		?>
		<?php $i = 0;$w=0;?>
		<?php while ($r->have_posts()): $r->the_post(); global $post; $i++;$format = dh_get_post_meta('portfolio_format'); ?>
			<?php 
			//action link
			$view_action = get_the_permalink();
			$target='';
			if($portfolio_action_link_to && ($project_url = dh_get_post_meta('url'))){
				$view_action = $project_url;
				$target = '  target="_blank"';
			}
			
			$post_class_masonry_size =' '.dh_get_post_meta('masonry_size','','normal');
			
			$post_col = '';
			if($layout != 'wall'){
				if($style != 'lily'){
					if(dh_get_post_meta('masonry_size',$post->ID,'normal') === 'double' || dh_get_post_meta('masonry_size',$post->ID,'normal') === 'wide_tall'):
						if($layout == 'grid'):
							$post_col = ' col-md-'.(12/$columns).' col-sm-6';
						elseif ($layout == 'masonry'):
							$post_col = ' col-md-'.((12/$columns) * 2).' col-sm-6';
						endif;
					else :
						$post_col = ' col-md-'.(12/$columns).' col-sm-6';
					endif;
				}else{
					$post_col = ' col-md-'.(12/$columns).' col-sm-6';
				}
			}
			$post_col = apply_filters('dh_portfolio_item_col', $post_col, $columns);
			?>
			<?php 
			$cat_class = array();
			if($layout == 'masonry'){
				if ( is_object_in_taxonomy( $post->post_type, 'portfolio_category' ) ) {
					foreach ( (array) get_the_terms($post->ID,'portfolio_category') as $cat ) {
						if ( empty($cat->slug ) )
							continue;
						$cat_class[] =  sanitize_html_class($cat->slug, $cat->term_id);
					}
				}
			}
			?>
			<?php 
			// Wall layout
			$w++;
			if($layout == 'wall'):
			$wall_open_row=false;
			?>
			<?php if ($w == '4' || $w == '10'): $wall_open_row = true;?>
			<div class="portfolio-wall wall-row">
			<?php endif;?>
			<div class="wall-col <?php 
				
				if($w == '1' || $w == '7'): 
					echo 'col-md-6 col-sm-12 title-in'; 
				elseif ($w=='2' || $w=='3' || $w=='8' || $w=='9'):
					echo 'col-md-6 col-sm-12 title-out';
					
				elseif ($w=='4' || $w=='5' || $w=='6' || $w == '10' || $w == '11' || $w == '12'): 
					echo 'col-sm-4 title-out height-auto'; 
				endif;
				if($w == '4' || $w == '6' || $w == '10' || $w == '12'){
					echo ' wall-bg';
				}
				if($w == '7'){
					echo ' pull-right';
				}
				if ($w !='4' || $w != '10'){
					echo ' portfolio-wall';
				}
				$bgs = array('wall-primary','wall-success','wall-primary');
				$bg = rand(0,2);
				if($w == '3' || $w == '6' || $w == '8' || $w == '12'){
					echo ' '.$bgs[$bg];
				}
				?>">
			<?php
			endif;
			?>
			<article data-title="<?php echo esc_attr(get_the_title())?>" data-date="<?php echo esc_attr(get_the_time('YmdHis'))?>"<?php if($accent_color = dh_get_post_meta('accent_color',$post->ID)):?> data-accent-color="<?php echo esc_attr($accent_color) ?>"<?php endif;?> id="portfolio-<?php the_ID(); ?>" class="<?php echo esc_attr($post_class) ?><?php echo esc_attr($post_class_masonry_size)?><?php echo esc_attr($post_col) ?><?php echo (!empty($cat_class) ? ' '.implode(' ', $cat_class):'')?>">
				<div class="portfolio-item-wrap">
					<?php 
					$entry_featured_class = '';
					if(($layout == 'wall') && ($w=='2' || $w=='3' || $w=='8' || $w=='9')):
						$entry_featured_class = ' col-sm-6';
						if($w == '3' || $w=='9'){
							$entry_featured_class .= ' pull-right';
						}
					endif;
					?>
					<?php $thumb_img = '';?>
					<?php if($layout == 'wall'):?>
						<?php if(has_post_thumbnail(get_the_ID())):?>
						<?php $thumb_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
						<?php endif;?>
					<?php endif;?>
					<div class="portfolio-featured-wrap<?php echo esc_attr($entry_featured_class)?>">
						<?php if($layout == 'wall'):?>
						<a href="<?php echo esc_url($view_action)?>" <?php echo ($target)?>><div class="portfolio-featured" <?php if(!empty($thumb_img)):?> style="background-image: url(<?php echo esc_url($thumb_img)?>)"<?php endif;?>></div></a>
						<?php else:?>
							<?php dh_portfolio_featured('','',true,$hide_action,$layout,$portfolio_action_link_to); ?>
							<?php if($hide_action && $style === 'three'):?>
							<div class="portfolio-overlay"></div>
							<?php endif;?>
						<?php endif;?>
					</div>
					<div class="portfolio-caption<?php if(($layout == 'wall') && ($w=='2' || $w=='3' || $w=='8' || $w=='9')):?> col-sm-6 portfolio-arrow<?php endif;?>">
						<div class="portfolio-caption-wrap">
							<h2 class="portfolio-title">
								<a href="<?php echo esc_url($view_action)?>" <?php echo ($target)?> title="<?php echo esc_attr(get_the_title())?>">
									<?php 
									if($style == 'lily' || $style == 'marley')
										dh_nth_word(get_the_title());
									else 
										the_title();
									?>
								</a>
							</h2>
							<div class="portfolio-meta">
								<?php if($layout == 'wall'):?>
									<span>
									<?php echo get_the_date()?>
									</span>
									<?php echo get_the_term_list( get_the_ID(), 'portfolio_category','',', ','') ?>
									<?php // echo wp_list_categories()?>
								<?php else :?>
									<?php echo get_the_term_list( get_the_ID(), 'portfolio_category','',', ','') ?>
								<?php endif;?>
							</div>
							<?php if($layout == 'wall'):?>
							<a class="readmore-link" href="<?php echo esc_url($view_action)?>" <?php echo ($target)?> ><i class="elegant_arrow_right"></i></a>
							<?php /* if(dh_get_theme_option('show-post-share',1)) dh_share('',dh_get_theme_option('post-fb-share',1),dh_get_theme_option('post-tw-share',1),dh_get_theme_option('post-go-share',1),dh_get_theme_option('post-pi-share',0),dh_get_theme_option('post-li-share',1),true); */?>
							<?php endif;?>
						</div>
					</div>
				</div>
			</article>
			<?php 
			// Wall layout
			if($layout == 'wall'):
			?>
			</div>
				<?php if ($w == '6' || $w == '12'): ?>
				</div>
				<?php elseif ($w > 3 && $w < 6 && $w == $r->post_count):?>
					<?php echo '</div>'?>
				<?php elseif ($w >= 10 && $w < 12 && $w == $r->post_count):?>
					<?php echo '</div>'?>
				<?php
				endif;
				?>
			<?php
			endif;
			?>
			<?php if($w == '12') $w = 0;?>
		<?php endwhile;?>
	</div>
	<?php if($pagination === 'loadmore' && 1 < $r->max_num_pages ):?>
	<div class="loadmore-action">
		<div class="loadmore-loading"><div class="fade-loading"><i></i><i></i><i></i><i></i></div></div>
		<button type="button" class="btn-loadmore"><?php echo esc_html($loadmore_text) ?></button>
	</div>
	<?php endif;?>
	<?php 
	$paginate_args = array();
	if($pagination === 'infinite_scroll' || $pagination === 'loadmore'){
		$paginate_args = array('show_all'=>true);
	}
	?>
	<?php if($pagination != 'no')dh_paginate_links($paginate_args,$r);?>
</div>
<?php
endif;
wp_reset_postdata();
?>