
<div class="page-header">
	<h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'woow' ); ?></h2>
</div>
<div class="page-content">
	<?php if ( is_search() ) : ?>

		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'woow' ); ?></p>
		<?php get_search_form(); ?>

	<?php else : ?>

		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'woow' ); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>
