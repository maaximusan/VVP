<?php
get_header(); ?>
<style>
	@font-face{
		font-family: 'thegem-elementor';
		src: url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.eot');
		src: url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.eot?#iefix') format('embedded-opentype'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.woff') format('woff'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.ttf') format('truetype'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.svg#thegem-elementor') format('svg');
		font-weight: normal;
		font-style: normal;
	}
	.template-post-empty-output{
		text-align: center !important;
		margin: 8px 3px !important;
		padding: 20px;
		justify-content: center !important;
		font-weight: normal !important;
	}
	.template-post-empty-output:before{
		font-family: 'thegem-elementor';
		font-weight: normal;
		font-style: normal;
		font-size: 24px;
		line-height: 1;
		width: 24px;
		text-align: center;
		display: inline-block;
		vertical-align: top;
		margin-right: 5px;
	}
	.template-post-empty-output.thegem-te-post-title:before {
		content: "\e652";
	}
	.template-post-empty-output.thegem-te-post-excerpt:before {
		content: "\e65a";
	}
	.template-post-empty-output.thegem-te-featured-image:before {
		content: "\e655";
	}
	.template-post-empty-output.thegem-te-post-content:before {
		content: "\e657";
	}
	.template-post-empty-output.thegem-te-post-info:before {
		content: "\e659";
	}
	.template-post-empty-output.thegem-te-featured-content:before {
		content: "\e65b";
	}
	.template-post-empty-output.thegem-te-post-tags:before {
		content: "\e65b";
	}
	#main-content .thegem-template-wrapper > .elementor {
		width: 400px;
		max-width: 100%;
		margin: 0 auto;
	}
	.elementor-edit-area-active .elementor-section-wrap:not(:empty) + #elementor-add-new-section {
		display: none;
	}
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="fullwidth-content">
			<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php the_ID(); ?>">
				<?php
				while ( have_posts() ) : the_post();
					$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
					$GLOBALS['thegem_template_type'] = 'loop-item';
					the_content();
					unset($GLOBALS['thegem_template_type']);
					if (!empty($thegem_template_type_outer)) {
						$GLOBALS['thegem_template_type'] = $thegem_template_type_outer;
					}
				endwhile;
				?>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();
