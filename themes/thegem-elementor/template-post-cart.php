<?php
wp_enqueue_script( 'wc-cart' );
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
    .template-cart-empty-output{
        text-align: center;
        padding: 20px;
        margin: 8px 3px !important;
    }
    .template-cart-empty-output:before{
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
    .template-cart-empty-output.thegem-te-cart-checkout-steps:before{
        content: "\e64a";
    }
    .template-cart-empty-output.thegem-te-cart-checkout-notices:before{
        content: "\e64b";
    }
    .template-cart-empty-output.thegem-te-cart-totals:before{
        content: "\e649";
    }
</style>
<div id="main-content" class="main-content">
    <div class="block-content">
        <div class="fullwidth-content">
            <div class="thegem-template-wrapper woocommerce thegem-template-cart thegem-template-<?php the_ID(); ?>">
                <?php
                while (have_posts()) : the_post();
                    $GLOBALS['thegem_template_type'] = 'cart';
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
