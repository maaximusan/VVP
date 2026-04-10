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
    .template-portfolio-empty-output{
        text-align: center !important;
        margin: 8px 3px !important;
        padding: 20px;
        justify-content: center !important;
    }
    .template-portfolio-empty-output:before{
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
    .template-portfolio-empty-output.thegem-te-portfolio-title:before {
        content: "\e652";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-excerpt:before {
        content: "\e65a";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-breadcrumbs:before {
        content: "\e654";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-featured-image:before {
        content: "\e655";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-content:before {
        content: "\e657";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-info:before {
        content: "\e659";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-navigation:before {
        content: "\e65b";
    }
    .template-portfolio-empty-output.thegem-te-portfolio-gallery:before {
        content: "\e607";
    }
</style>
<div id="main-content" class="main-content">
    <div class="block-content">
        <div class="fullwidth-content">
            <div class="thegem-template-wrapper thegem-template-portfolio thegem-template-<?php the_ID(); ?>">
                <?php
                while ( have_posts() ) : the_post();
                    $GLOBALS['thegem_template_type'] = 'portfolio';
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
