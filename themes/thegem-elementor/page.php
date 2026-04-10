<?php

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_page_template_id = thegem_page_template();
	while ( have_posts() ) : the_post();
		if($thegem_page_template_id && defined('ELEMENTOR_VERSION')) : ?>
			<?php echo thegem_page_title(); ?>
			<div class="block-content">
				<div class="fullwidth-content">
					<div class="thegem-template-wrapper thegem-template-page thegem-template-<?php echo esc_attr($thegem_page_template_id); ?>">
						<?php
							$GLOBALS['thegem_template_type'] = 'page';
							echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($thegem_page_template_id);
							unset($GLOBALS['thegem_template_type']);
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .block-content -->
		<?php else :
			get_template_part( 'content', 'page' );
		endif;
	endwhile;
?>

</div><!-- #main-content -->

<?php
get_footer();
