<?php
thegem_enqueue_woocommerce_styles();
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
    .template-checkout-thanks-empty-output{
        text-align: center;
        padding: 20px;
        margin: 8px 3px !important;
    }
    .template-checkout-thanks-empty-output:before{
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
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-order-overview:before {
        content: "\e64e";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-order-details:before {
        content: "\e648";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-cart-totals:before {
        content: "\e649";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-customer-details:before {
        content: "\e651";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-billing-details:before {
        content: "\e64c";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-shipping-details:before {
        content: "\e64d";
    }
    .template-checkout-thanks-empty-output.thegem-te-checkout-thanks-notices:before {
        content: "\e64b";
    }
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="fullwidth-content">
			<div class="thegem-template-wrapper thegem-template-checkout-thanks thegem-template-<?php the_ID(); ?>">
				<?php
					while ( have_posts() ) : the_post();
						$GLOBALS['thegem_template_type'] = 'checkout-thanks';
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
