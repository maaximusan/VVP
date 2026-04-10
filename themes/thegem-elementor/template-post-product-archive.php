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
    .template-product-empty-output{
        text-align: center !important;
        margin: 8px 3px !important;
        padding: 20px;
    }
    .template-product-empty-output:before{
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
    .template-product-empty-output.thegem-te-product-archive-title:before{
        content: "\e645";
    }
    .template-product-empty-output.thegem-te-product-archive-description:before{
        content: "\e637";
    }
    .template-product-empty-output.thegem-te-product-archive-breadcrumbs:before{
        content: "\e63b";
    }
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
        <div class="fullwidth-content">
			<div class="thegem-template-wrapper thegem-template-product-archive thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						$GLOBALS['thegem_template_type'] = 'product-archive';
						the_content();
						unset($GLOBALS['thegem_template_type']);
					endwhile;
				?>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->

<?php
get_footer();