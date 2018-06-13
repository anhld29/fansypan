<form method="GET" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="form">
	<label for="s" class="sr-only"><?php esc_html_e( 'Search', 'woow' ); ?></label>
	<input type="hidden" value="post" name="post_type">
	<input type="search" id="s" name="s" class="form-control" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search something&hellip;', 'woow' ); ?>" />
	<input type="submit" id="searchsubmit" class="hidden" name="submit" value="<?php esc_attr_e( 'Search', 'woow' ); ?>" />
</form>