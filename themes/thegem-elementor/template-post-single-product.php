<?php
get_header(); ?>
<style>
@font-face {
	font-family: 'thegem-elementor';
	src:url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.eot');
	src:url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.eot?#iefix') format('embedded-opentype'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.woff') format('woff'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.ttf') format('truetype'),
		url('<?php echo THEGEM_ELEMENTOR_URL; ?>/assets/icons/fonts/thegem-elementor.svg#thegem-elementor') format('svg');
	font-weight: normal;
	font-style: normal;
}
.template-product-empty-output {
	text-align: center;
	padding: 20px;
	margin: 8px 3px !important;
}
.template-product-empty-output:before {
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
.template-product-empty-output.thegem-te-product-rating:before {
	content: "\e632";
}
.template-product-empty-output.thegem-te-product-reviews:before {
	content: "\e633";
}
.template-product-empty-output.thegem-te-product-navigation:before {
	content: "\e634";
}
.template-product-empty-output.thegem-te-product-price:before {
	content: "\e635";
}
.template-product-empty-output.thegem-te-product-meta-value:before {
	content: "\e636";
}
.template-product-empty-output.thegem-te-product-description:before {
	content: "\e637";
}
.template-product-empty-output.thegem-te-product-extra-description:before {
	content: "\e638";
}
.template-product-empty-output.thegem-te-product-content:before {
	content: "\e639";
}
.template-product-empty-output.thegem-te-product-categories:before {
	content: "\e63a";
}
.template-product-empty-output.thegem-te-product-breadcrumbs:before {
	content: "\e63b";
}
.template-product-empty-output.thegem-te-product-add-to-cart:before {
	content: "\e63c";
}
.template-product-empty-output.thegem-te-product-add-to-wishlist:before {
	content: "\e63d";
}
.template-product-empty-output.thegem-te-product-additional-info:before {
	content: "\e63e";
}
.template-product-empty-output.thegem-te-product-attribute:before {
	content: "\e63f";
}
.template-product-empty-output.thegem-te-product-sharing:before {
	content: "\e640";
}
.template-product-empty-output.thegem-te-product-size_guide:before {
	content: "\e641";
}
.template-product-empty-output.thegem-te-product-sku:before {
	content: "\e642";
}
.template-product-empty-output.thegem-te-product-tags:before {
	content: "\e643";
}
.template-product-empty-output.thegem-te-product-tabs:before {
	content: "\e644";
}
.template-product-empty-output.thegem-te-product-title:before {
	content: "\e645";
}
.template-product-empty-output.thegem-te-product-gallery:before {
	content: "";
}
.template-product-empty-output.thegem-te-product-gallery {
	font-size: 0;
	line-height: 0;
	height: 0;
	width: 100%;
	padding-bottom: 125%;
	background: url('<?php echo THEGEM_THEME_URI; ?>/images/dummy.png') no-repeat 0% 50%;
	background-size: cover;
}
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="<?php echo (defined('WPB_VC_VERSION') && (vc_is_frontend_editor() || vc_is_page_editable()) ? 'container' : 'fullwidth-content'); ?>">
			<div class="thegem-template-wrapper thegem-template-single-product thegem-template-<?php the_ID(); ?> product">
				<?php
					while ( have_posts() ) : the_post();
						$GLOBALS['thegem_template_type'] = 'single-product';
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