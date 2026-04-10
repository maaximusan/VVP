<?php
wp_enqueue_script( 'wc-country-select' );
wp_enqueue_script( 'selectWoo' );
wp_enqueue_style( 'select2' );
thegem_woocommerce_cart_scripts();
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
    .template-checkout-empty-output{
        text-align: center;
        padding: 20px;
        margin: 8px 3px !important;
    }
    .template-checkout-empty-output:before{
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
    .template-checkout-empty-output.thegem-te-checkout-coupon:before {
        content: "\e650";
    }
    .template-checkout-empty-output.thegem-te-checkout-login:before {
        content: "\e651";
    }
    .template-checkout-empty-output.thegem-te-checkout-errors:before {
        content: "\e64b";
    }
</style>
<div id="main-content" class="main-content">
	<div class="block-content">
		<div class="fullwidth-content">
			<div class="woocommerce">
				<form name="checkout" method="post" class="checkout woocommerce-checkout woocommerce-checkout-one-page woocommerce-checkout-one-page-modern woocommerce-checkout-with-template" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
					<div class="thegem-template-wrapper thegem-template-checkout thegem-template-<?php the_ID(); ?>">
						<?php
							while ( have_posts() ) : the_post();
								$GLOBALS['thegem_template_type'] = 'checkout';
								the_content();
								unset($GLOBALS['thegem_template_type']);
							endwhile;
						?>
					</div>
				</form>
			</div>
		</div>
	</div><!-- .block-content -->
</div><!-- #main-content -->
<script type="text/javascript">
(function($) {
	var tb_ship_to_different_address = function() {
		$( 'div.shipping_address' ).hide();
		if ( $( this ).is( ':checked' ) ) {
			$( 'div.shipping_address' ).slideDown();
		}
	}
	$(document).on( 'change', '#ship-to-different-address input', tb_ship_to_different_address );
	$(function() {
		$('select.country_select, select.state_select').select2({width: '100%'});
		$(document.body).find( '#ship-to-different-address input' ).trigger( 'change' );
	});
})(jQuery);
</script>
<?php
get_footer();
