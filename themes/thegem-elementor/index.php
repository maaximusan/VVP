<?php
$thegem_panel_classes = array('panel', 'row');

if(is_active_sidebar('page-sidebar')) {
	$thegem_panel_classes[] = 'panel-sidebar-position-right';
	$thegem_panel_classes[] = 'with-sidebar';
	$thegem_center_classes = 'col-lg-9 col-md-9 col-sm-12';
} else {
	$thegem_center_classes = 'col-xs-12';
}

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	$thegem_no_margins_block = '';
	$post_type_name = 'post';
	if (is_archive() || is_home()) {
		$thegem_term_id = get_queried_object() && !empty(get_queried_object()->term_id) ? get_queried_object()->term_id : 0;
		if(is_post_type_archive() && in_array(get_queried_object()->name, thegem_get_available_po_custom_post_types())) {
			$post_type_name = get_queried_object()->name;
			$thegem_output_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings($post_type_name.'_archive'), 'cpt_archive');
		} else {
			$thegem_output_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings('blog'), 'blog');
		}
		if($thegem_term_id) {
			$tax = get_taxonomy(get_queried_object()->taxonomy);
			if (!empty($tax->object_type) && !empty($tax->object_type[0])) {
				$post_type_name = $tax->object_type[0];
			}
			$thegem_output_settings = thegem_get_output_page_settings($thegem_term_id, array(), 'term');
		}
		$thegem_page_data = array(
			'title' => $thegem_output_settings,
			'effects' => $thegem_output_settings,
			'slideshow' => $thegem_output_settings,
			'sidebar' => $thegem_output_settings
		);

		if($thegem_page_data['effects']['effects_no_bottom_margin']) {
			$thegem_no_margins_block .= ' no-bottom-margin';
		}
		if($thegem_page_data['effects']['effects_no_top_margin']) {
			$thegem_no_margins_block .= ' no-top-margin';
		}

		$thegem_panel_classes = array('panel', 'row');
		$thegem_center_classes = 'panel-center';
		$thegem_sidebar_classes = '';

		if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && $thegem_page_data['sidebar']['sidebar_position']) {
			$thegem_panel_classes[] = 'panel-sidebar-position-'.$thegem_page_data['sidebar']['sidebar_position'];
			$thegem_panel_classes[] = 'with-sidebar';
			$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
			if($thegem_page_data['sidebar']['sidebar_position'] == 'left') {
				$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
				$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
			}
		} else {
			$thegem_center_classes .= ' col-xs-12';
		}
		if($thegem_page_data['sidebar']['sidebar_sticky']) {
			$thegem_panel_classes[] = 'panel-sidebar-sticky';
			wp_enqueue_script('thegem-sticky');
		}
		if($thegem_page_data['title']['title_show'] && $thegem_page_data['title']['title_style'] == 3 && $thegem_page_data['slideshow']['slideshow_type']) {
			thegem_slideshow_block(array('slideshow_type' => $thegem_page_data['slideshow']['slideshow_type'], 'slideshow' => $thegem_page_data['slideshow']['slideshow_slideshow'], 'lslider' => $thegem_page_data['slideshow']['slideshow_layerslider'], 'slider' => $thegem_page_data['slideshow']['slideshow_revslider'], 'preloader' => !empty($thegem_page_data['slideshow']['slideshow_preloader'])));
		}
	}
	echo thegem_page_title();

	if ($post_type_name == 'post') {
		$archive_template_id = thegem_blog_archive_template();
	} else {
		$archive_template_id = thegem_cpt_archive_template();
	}

	if ( $archive_template_id && defined( 'ELEMENTOR_VERSION' ) ) { ?>
		<div class="block-content">
			<div class="fullwidth-content">
				<div class="thegem-template-wrapper thegem-template-blog-archive thegem-template-<?php the_ID(); ?>">
					<?php
					$GLOBALS['thegem_template_type'] = 'blog-archive';
					echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $archive_template_id );
					unset( $GLOBALS['thegem_template_type'] );
					?>
				</div>
			</div><!-- .container -->
		</div><!-- .block-content -->
	<?php } else {
		include(locate_template('gem-templates/blog/content-blog-content-block.php'));
	}

?>

</div><!-- #main-content -->

<?php
get_footer();
