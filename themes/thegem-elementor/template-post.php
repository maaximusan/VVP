<?php
get_header(); ?>

<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="fullwidth-content">
			<?php
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;
			?>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();