<?php

add_action('init', 'thegem_init_global_page_settings');
function thegem_init_global_page_settings() {
	global $thegem_global_page_settings;
	$thegem_global_page_settings = array(
		'global' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_global'), 'global')),
		'page' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_default'), 'default')),
		'post' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_post'), 'post')),
		'portfolio' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_portfolio'), 'portfolio')),
		'product' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_product'), 'product')),
		'product_category' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_product_categories'), 'product_category')),
		'blog' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_blog'), 'blog')),
		'search' => array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_search'), 'search')),
	);
	foreach(thegem_get_available_po_custom_post_types() as $post_type) {
		$thegem_global_page_settings[$post_type] = array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_'.$post_type), 'cpt'));
		$thegem_global_page_settings[$post_type.'_archive'] = array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_'.$post_type.'_archive'), 'cpt_archive'));
	}
	if(!empty(thegem_get_option('portfolio_category_page_enable'))) {
		$thegem_global_page_settings['thegem_pf_item_archive'] = array_map('stripslashes', thegem_get_sanitize_options_page_data(get_option('thegem_options_page_settings_thegem_pf_item_archive'), 'cpt_archive'));
	}
	$thegem_global_page_settings['global']['header_hide_top_area'] = !thegem_get_option('top_area_show');
	$thegem_global_page_settings['global']['header_hide_top_area_tablet'] = thegem_get_option('top_area_disable_tablet');
	$thegem_global_page_settings['global']['header_hide_top_area_mobile'] = thegem_get_option('top_area_disable_mobile');
	$thegem_global_page_settings['global']['header_source'] = thegem_get_option('header_source');
	$thegem_global_page_settings['global']['header_builder'] = thegem_get_option('header_builder');
	$thegem_global_page_settings['global']['header_builder_sticky_desktop'] = thegem_get_option('header_builder_sticky_desktop');
	$thegem_global_page_settings['global']['header_builder_sticky_mobile'] = thegem_get_option('header_builder_sticky_mobile');
	$thegem_global_page_settings['global']['header_builder_sticky_hide_desktop'] = thegem_get_option('header_builder_sticky_hide_desktop');
	$thegem_global_page_settings['global']['header_builder_sticky_hide_mobile'] = thegem_get_option('header_builder_sticky_hide_mobile');
	$thegem_global_page_settings['global']['header_builder_sticky'] = thegem_get_option('header_builder_sticky');
	$thegem_global_page_settings['global']['header_builder_sticky_opacity'] = thegem_get_option('header_builder_sticky_opacity');
	$thegem_global_page_settings['global']['enable_page_preloader'] = thegem_get_option('preloader');
	$thegem_global_page_settings['global']['main_background_type'] = false;
	$thegem_global_page_settings['global']['effects_hide_footer'] = !thegem_get_option('footer');
	$thegem_global_page_settings['global']['footer_hide_default'] = !thegem_get_option('footer_active');
	$thegem_global_page_settings['global']['footer_hide_widget_area'] = thegem_get_option('footer_widget_area_hide');
	$thegem_global_page_settings['global']['footer_custom_show'] = thegem_get_option('custom_footer_enable');
	$thegem_global_page_settings['global']['footer_custom'] = thegem_get_option('custom_footer');
	$thegem_global_page_settings['global']['breadcrumbs_default_color'] = thegem_get_option('breadcrumbs_default_color');
	$thegem_global_page_settings['global']['breadcrumbs_active_color'] = thegem_get_option('breadcrumbs_active_color');
	$thegem_global_page_settings['global']['breadcrumbs_hover_color'] = thegem_get_option('breadcrumbs_hover_color');
	$thegem_global_page_settings['global']['product_header_separator'] = thegem_get_option('product_header_separator');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs'] = thegem_get_option('page_layout_breadcrumbs');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_default_color'] = thegem_get_option('page_layout_breadcrumbs_default_color');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_active_color'] = thegem_get_option('page_layout_breadcrumbs_active_color');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_hover_color'] = thegem_get_option('page_layout_breadcrumbs_hover_color');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_alignment'] = thegem_get_option('page_layout_breadcrumbs_alignment');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_bottom_spacing'] = thegem_get_option('page_layout_breadcrumbs_bottom_spacing');
	$thegem_global_page_settings['global']['page_layout_breadcrumbs_shop_category'] = thegem_get_option('page_layout_breadcrumbs_shop_category');
	$thegem_global_page_settings['global']['header_builder_light_color'] = thegem_get_option('header_builder_light_color');
	$thegem_global_page_settings['global']['header_builder_light_color_hover'] = thegem_get_option('header_builder_light_color_hover');
	$thegem_global_page_settings['global']['disable_fixed_header'] = thegem_get_option('disable_fixed_header');
	$thegem_global_page_settings['global']['sticky_header_on_mobile'] = thegem_get_option('sticky_header_on_mobile');
}

function thegem_get_post_data($default = array(), $post_data_name = '', $post_id = 0, $type = false) {
	if($type === 'term') {
		$post_data = get_term_meta($post_id, 'thegem_'.$post_data_name.'_data', true);
	} else {
		$post_data = get_post_meta($post_id, 'thegem_'.$post_data_name.'_data', true);
	}
	if($post_data_name == 'page' && is_array($post_data)) {
		if(!isset($post_data['title_show'])) {
			if($type === 'term') {
				update_term_meta($post_id, 'thegem_page_data_old', $post_data);
			} else {
				update_post_meta($post_id, 'thegem_page_data_old', $post_data);
			}
			$post_data = thegeme_migrate_post_page_data($post_data);
		}
		if(!isset($post_data['slideshow_preloader'])) {
			$post_data['slideshow_preloader'] = 1;
		}
		if(!isset($post_data['header_source'])) {
			if(!isset($post_data['effects_hide_header'])) {
				$post_data['effects_hide_header'] = 'enabled';
			} elseif($post_data['effects_hide_header'] === 'default' && isset($post_data['menu_show']) && ($post_data['menu_show'] != 'default' || $post_data['menu_options'] != 'default' || $post_data['header_hide_top_area'] != 'default' || $post_data['top_area_options'] != 'default')) {
				$post_data['effects_hide_header'] = 'enabled';
			}
		}
		if(!isset($post_data['title_font_preset_style'])) {
			if(!empty($post_data['title_xlarge'])) {
				$post_data['title_font_preset_style'] = 'title-xlarge';
			}
		}
		if(!isset($post_data['header_transparent_mobile']) && isset($post_data['header_transparent'])) {
			$post_data['header_transparent_mobile'] = $post_data['header_transparent'];
		}
		if($type === 'term' && metadata_exists('term', $post_id, 'thegem_taxonomy_custom_page_options')) {
			$custom_po = get_term_meta($post_id, 'thegem_taxonomy_custom_page_options', true);
			if(empty($custom_po)) {
				$post_data['effects_hide_header'] = 'default';
				$post_data['title_show'] = 'default';
				$post_data['content_area_options'] = 'default';
				$post_data['sidebar_show'] = 'default';
				$post_data['effects_hide_footer'] = 'default';
				$post_data['footer_hide_widget_area'] = 'default';
				$post_data['footer_hide_default'] = 'default';
				$post_data['footer_custom_show'] = 'default';
				$post_data['enable_page_preloader'] = 'default';
			}
		}
	}
	if($post_data_name == 'post_general_item' && is_array($post_data)) {
		if(!in_array($post_data['show_featured_content'], array('default', 'enabled', 'disabled'), true)) {
			update_post_meta($post_id, 'thegem_post_general_item_data_old', $post_data);
			$post_data = thegeme_migrate_post_general_item_data($post_data);
		}
		/*if(empty($post_data['post_layout_settings']) || !in_array($post_data['post_layout_settings'], array('default', 'custom'), true)) {
			update_post_meta($post_id, 'thegem_post_general_item_data_old', $post_data);
			$post_elements = get_post_meta($post_id, 'thegem_post_elements_data', true);
			if((!empty($post_data['show_featured_content']) && $post_data['show_featured_content'] !== 'default') || (is_array($post_elements) && !empty($post_elements['post_elements']) && $post_elements['post_elements'] !== 'default')) {
				$post_data['post_layout_settings'] = 'custom';
				$post_data['post_layout_source'] = 'default';
			}
		}*/
	}
	if($post_data_name == 'portfolio_item' && is_array($post_data) && function_exists('thegeme_migrate_portfolio_item_data')) {
		if(!isset($post_data['grid_appearance_type'])) {
			update_post_meta($post_id, 'thegem_portfolio_item_data_old', $post_data);
			$post_data = thegeme_migrate_portfolio_item_data($post_data);
		}
	}
	if($post_data_name == 'product_size_guide' && is_array($post_data)) {
		if(!isset($post_data['size_guide'])) {
			update_post_meta($post_id, 'thegem_product_size_guide_data_old', $post_data);
			$post_data = thegeme_migrate_product_size_guide_data($post_data);
		}
	}
	if($post_data_name == 'product_featured' && is_array($post_data)) {
		if(!isset($post_data['highlight'])) {
			update_post_meta($post_id, 'thegem_product_featured_data_old', $post_data);
			$post_data = thegeme_migrate_product_featured_data($post_data);
		}
	}
	if($post_data_name == 'product_page' && (empty($post_data) || !is_array($post_data))) {
		$post_data = get_post_meta($post_id, 'thegem_product_gallery_data', true);
		if(!empty($post_data) && is_array($post_data)) {
			$post_data['product_page_layout'] = 'legacy';
		}
	}
	if($post_data_name == 'blog_archive_page' && is_array($post_data)) {
		if(!isset($post_data['blog_archive_layout_settings']) && isset($post_data['blog_archive_layout_source']) && $post_data['blog_archive_layout_source'] === 'builder') {
			$post_data['blog_archive_layout_settings'] = 'custom';
		}
	}
	if(!is_array($default)) {
		return apply_filters('thegem_get_post_data', array(), $post_id, $post_data_name, $type);
	}
	if(!is_array($post_data)) {
		return apply_filters('thegem_get_post_data', $default, $post_id, $post_data_name, $type);
	}
	return apply_filters('thegem_get_post_data', array_merge($default, $post_data), $post_id, $post_data_name, $type);
}

/* PAGE OPTIONS */

function thegem_get_page_title_background_effect_list() {
	return array(
		'normal'=> __('Normal', 'thegem'),
		'parallax'=> __('Parallax', 'thegem'),
		'ken_burns'=> __('Ken Burns', 'thegem')
	);
}

function thegem_get_page_title_background_ken_burns_direction_list() {
	return array(
		'zoom_in'=> __('Zoom In', 'thegem'),
		'zoom_out'=> __('Zoom Out', 'thegem')
	);
}

function thegem_get_page_scroller_types() {
	return array(
		'basic'=> __('Basic', 'thegem'),
		'advanced'=> __('Advanced', 'thegem')
	);
}

function thegem_fullpage_dots_styles() {
	return array(
		'outline'=> __('Outline dots', 'thegem'),
		'solid'=> __('Solid dots', 'thegem'),
		'solid-small'=> __('Solid dots (small)', 'thegem'),
		'lines'=> __('Lines', 'thegem'),
		'outlined-active'=> __('Outlined active dot', 'thegem'),
	);
}

function thegem_fullpage_scroll_effects() {
	return array(
		'normal'=> __('Normal', 'thegem'),
		'parallax'=> __('Parallax', 'thegem'),
		'fixed_background'=> __('Fixed Backgrounds', 'thegem')
	);
}

function thegem_get_sanitize_product_size_guide_data($post_id = 0, $item_data = array()) {
	$post_item_data = array(
		'size_guide' => 'default',
		'custom_image' => '',
		'custom_text' => '',
	);
	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'product_size_guide', $post_id);
	}

	$post_item_data['size_guide'] = thegem_check_array_value(array('default', 'custom', 'disabled'), $post_item_data['size_guide'], 'default');
	$post_item_data['custom_image'] = esc_url($post_item_data['custom_image']);
	$post_item_data['custom_text'] = esc_html($post_item_data['custom_text']);

	return $post_item_data;
}

function thegeme_migrate_product_size_guide_data($page_data = array()) {
	$page_data['size_guide'] = 'default';
	if(!empty($page_data['disabled'])) {
		$page_data['size_guide'] = 'disabled';
	} elseif(!empty($page_data['custom'])) {
		$page_data['size_guide'] = 'custom';
	}
	return $page_data;
}

function thegem_get_sanitize_product_featured_data($post_id = 0, $item_data = array()) {
	$post_item_data = array(
		'highlight' => '0',
		'highlight_type' => 'squared'
	);
	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'product_featured', $post_id);
	}

	$post_item_data['highlight'] = $post_item_data['highlight'] ? 1 : 0;
	$post_item_data['highlight_type'] = thegem_check_array_value(array('squared', 'horizontal', 'vertical'), $post_item_data['highlight_type'], 'squared');

	return $post_item_data;
}

function thegeme_migrate_product_featured_data($page_data = array()) {
	$page_data['highlight'] = 0;
	if(!empty($page_data['highlight_type']) && $page_data['highlight_type'] != 'disabled') {
		$page_data['highlight'] = 1;
	} else {
		$page_data['highlight_type'] = 'squared';
	}
	return $page_data;
}

add_action('wp_ajax_thegem_icon_list', 'thegem_icon_list_info');
function thegem_icon_list_info() {
	if(!empty($_REQUEST['iconpack']) && in_array($_REQUEST['iconpack'], array('elegant', 'material', 'fontawesome', 'userpack'))) {
		$svg_links = array(
			'elegant' => THEGEM_THEME_URI . '/fonts/elegant/ElegantIcons.svg',
			'material' => THEGEM_THEME_URI . '/fonts/material/materialdesignicons.svg',
			'fontawesome' => THEGEM_THEME_URI . '/fonts/fontawesome/fontawesome-webfont.svg',
			'userpack' => get_stylesheet_directory_uri() . '/fonts/UserPack/UserPack.svg',
		);
		$css_links = array(
			'elegant' => THEGEM_THEME_URI . '/css/icons-elegant.css',
			'material' => THEGEM_THEME_URI . '/css/icons-material.css',
			'fontawesome' => THEGEM_THEME_URI . '/css/icons-fontawesome.css',
			'userpack' => get_stylesheet_directory_uri() . '/css/icons-userpack.css',
		);
		echo '<ul class="icons-list icons-'.esc_attr($_REQUEST['iconpack']).' styled"></ul>';
?>
	<script type="text/javascript">
	(function($) {
		$(function() {
			$.ajax({
				url: '<?php echo esc_url($svg_links[$_REQUEST['iconpack']]); ?>'
			}).done(function(data) {
				var $glyphs = $('glyph', data);
				$('.icons-list').html('');
				$glyphs.each(function() {
					var code = $(this).attr('unicode').charCodeAt(0).toString(16);
					if($(this).attr('d')) {
						$('<li><span class="icon">'+$(this).attr('unicode')+'</span><span class="code">'+code+'</span></li>').appendTo($('.icons-list'));
					}
				});
			});
		});
	})(jQuery);
	</script>
<?php
		exit;
	}
	die(-1);
}

add_action('admin_init', 'thegem_post_types_admin_init');
function thegem_post_types_admin_init() {
	add_post_type_support( 'post', 'page-attributes' );
}

function thegem_order_pre_insert_post($post, \WP_REST_Request $request) {
	$body = $request->get_body();
	if ($body) {
		$body = json_decode($body);
		if (isset($body->menu_order)) {
			$post->menu_order = $body->menu_order;
		}
	}
	return $post;
}
add_filter('rest_pre_insert_post', 'thegem_order_pre_insert_post', 12, 2);

function thegem_order_prepare_post(\WP_REST_Response $response, $post, $request) {
	$response->data['menu_order'] = $post->menu_order;
	return $response;
}
add_filter('rest_prepare_post', 'thegem_order_prepare_post', 12, 3);

function thegem_add_wp_templates_support() {
	$post_types = thegem_get_available_po_custom_post_types();
	foreach ( $post_types as $post_type ) {
		add_filter( "theme_{$post_type}_templates", 'thegem_add_page_templates', 12, 4 );
	}
}
add_action( 'init', 'thegem_add_wp_templates_support' );

function thegem_add_page_templates( $page_templates, $wp_theme, $post ) {
	if ( $post ) {
		$post_id = ! empty( $post->ID ) ? $post->ID : $post;
	}

	$page_templates = [
		'single-fullwidth.php' => _x( 'TheGem Full Width', 'Page Template', 'thegem' ),
	] + $page_templates;

	return $page_templates;
}

function thegem_get_output_page_settings($post_id = 0, $item_data = array(), $type = false) {
	static $cache;
	$cacheKey = serialize([$post_id, $item_data, $type]);

	if (isset($cache[$cacheKey])) {
		return $cache[$cacheKey];
	}

	$output_data = thegem_get_sanitize_admin_page_data($post_id, $item_data, $type);

	if($output_data['effects_hide_header'] == 'default') {
		$output_data['effects_hide_header'] = thegem_get_option_page_setting('effects_hide_header', $output_data['effects_hide_header'], $post_id, $type);
		$output_data['header_source'] = thegem_get_option_page_setting('header_source', $output_data['header_source'], $post_id, $type);
		if($output_data['header_source'] == 'builder') {
			$output_data['header_builder'] = thegem_get_option_page_setting('header_builder', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky_desktop'] = thegem_get_option_page_setting('header_builder_sticky_desktop', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky_mobile'] = thegem_get_option_page_setting('header_builder_sticky_mobile', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky_hide_desktop'] = thegem_get_option_page_setting('header_builder_sticky_hide_desktop', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky_hide_mobile'] = thegem_get_option_page_setting('header_builder_sticky_hide_mobile', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky'] = thegem_get_option_page_setting('header_builder_sticky', $output_data['header_source'], $post_id, $type);
			$output_data['header_builder_sticky_opacity'] = thegem_get_option_page_setting('header_builder_sticky_opacity', $output_data['header_source'], $post_id, $type);
			$output_data['header_transparent'] = thegem_get_option_page_setting('header_transparent', $output_data['header_transparent'], $post_id, $type);
			$output_data['header_transparent_mobile'] = thegem_get_option_page_setting('header_transparent_mobile', $output_data['header_transparent_mobile'], $post_id, $type);
			$output_data['header_opacity'] = thegem_get_option_page_setting('header_opacity', $output_data['header_opacity'], $post_id, $type);
			$output_data['header_menu_logo_light'] = thegem_get_option_page_setting('header_menu_logo_light', $output_data['header_menu_logo_light'], $post_id, $type);
			$output_data['header_builder_light_color'] = thegem_get_option_page_setting('header_builder_light_color', $output_data['header_builder_light_color'], $post_id, $type);
			$output_data['header_builder_light_color_hover'] = thegem_get_option_page_setting('header_builder_light_color_hover', $output_data['header_builder_light_color_hover'], $post_id, $type);
		}
		$output_data['menu_show'] = 'default';
		$output_data['menu_options'] = 'default';
		$output_data['header_hide_top_area'] = 'default';
		$output_data['header_hide_top_area_tablet'] = 'default';
		$output_data['header_hide_top_area_mobile'] = 'default';
		$output_data['top_area_options'] = 'default';
	} elseif($output_data['effects_hide_header'] == 'disabled') {
		$output_data['effects_hide_header'] = 1;
	} else {
		$output_data['effects_hide_header'] = 0;
	}

	if($output_data['header_source'] == 'default') {
		$check_menu_custom = true;
		if($output_data['menu_show'] == 'default') {
			$output_data['menu_show'] = thegem_get_option_page_setting('menu_show', $output_data['menu_show'], $post_id, $type);
		} elseif($output_data['menu_show'] == 'disabled') {
			$output_data['menu_show'] = 0;
			$check_menu_custom = false;
		} else {
			$output_data['menu_show'] = 1;
		}
		if($output_data['menu_show'] && isset($output_data['menu_options']) && $output_data['menu_options'] == 'default') {
			$output_data['header_transparent'] = thegem_get_option_page_setting('header_transparent', $output_data['header_transparent'], $post_id, $type);
			$output_data['header_transparent_mobile'] = thegem_get_option_page_setting('header_transparent_mobile', $output_data['header_transparent_mobile'], $post_id, $type);
			$output_data['header_opacity'] = thegem_get_option_page_setting('header_opacity', $output_data['header_opacity'], $post_id, $type);
			$output_data['header_menu_logo_light'] = thegem_get_option_page_setting('header_menu_logo_light', $output_data['header_menu_logo_light'], $post_id, $type);
			$output_data['disable_fixed_header'] = thegem_get_option_page_setting('disable_fixed_header', $output_data['disable_fixed_header'], $post_id, $type);
			$output_data['sticky_header_on_mobile'] = thegem_get_option_page_setting('sticky_header_on_mobile', $output_data['sticky_header_on_mobile'], $post_id, $type);
		} elseif(isset($output_data['menu_options']) && $output_data['menu_options'] == 'default' && $check_menu_custom) {
			$output_data['header_menu_logo_light'] = thegem_get_option_page_setting('header_menu_logo_light', $output_data['header_menu_logo_light'], $post_id, $type);
			$output_data['disable_fixed_header'] = thegem_get_option_page_setting('disable_fixed_header', $output_data['disable_fixed_header'], $post_id, $type);
			$output_data['sticky_header_on_mobile'] = thegem_get_option_page_setting('sticky_header_on_mobile', $output_data['sticky_header_on_mobile'], $post_id, $type);
		}

		if(!$output_data['menu_show']) {
			$output_data['header_transparent'] = 1;
			$output_data['header_opacity'] = 0;
		}
	}
	if($output_data['header_hide_top_area'] == 'default') {
		$output_data['header_hide_top_area'] = thegem_get_option_page_setting('header_hide_top_area', $output_data['header_hide_top_area'], $post_id, $type);
	} elseif($output_data['header_hide_top_area'] == 'disabled') {
		$output_data['header_hide_top_area'] = 1;
	} else {
		$output_data['header_hide_top_area'] = 0;
	}

	if($output_data['header_hide_top_area_tablet'] == 'default') {
		$output_data['header_hide_top_area_tablet'] = thegem_get_option_page_setting('header_hide_top_area_tablet', $output_data['header_hide_top_area_tablet'], $post_id, $type);
	} elseif($output_data['header_hide_top_area_tablet'] == 'disabled') {
		$output_data['header_hide_top_area_tablet'] = 1;
	} else {
		$output_data['header_hide_top_area_tablet'] = 0;
	}

	if($output_data['header_hide_top_area_mobile'] == 'default') {
		$output_data['header_hide_top_area_mobile'] = thegem_get_option_page_setting('header_hide_top_area_mobile', $output_data['header_hide_top_area_mobile'], $post_id, $type);
	} elseif($output_data['header_hide_top_area_mobile'] == 'disabled') {
		$output_data['header_hide_top_area_mobile'] = 1;
	} else {
		$output_data['header_hide_top_area_mobile'] = 0;
	}

	if(isset($output_data['top_area_options']) && $output_data['top_area_options'] == 'default') {
		$output_data['header_top_area_transparent'] = thegem_get_option_page_setting('header_top_area_transparent', $output_data['header_top_area_transparent'], $post_id, $type);
		$output_data['header_top_area_opacity'] = thegem_get_option_page_setting('header_top_area_opacity', $output_data['header_top_area_opacity'], $post_id, $type);
	}

	if($output_data['title_show'] == 'default') {
		$output_data['product_header_separator'] = thegem_get_option_page_setting('product_header_separator', $output_data['product_header_separator'], $post_id, $type);
		$exclude = array('title_rich_content', 'title_content', 'title_excerpt');
		foreach($output_data as $key => $value) {
			if((strpos($key, 'title_') === 0 || strpos($key, 'breadcrumbs_') === 0) && strpos($key, 'title_icon') === false && !in_array($key, $exclude)) {
				$output_data[$key] = thegem_get_option_page_setting($key, $output_data[$key], $post_id, $type);
			}
		}
	} elseif($output_data['title_show'] == 'disabled') {
		$output_data['title_show'] = 0;
	} else {
		$output_data['title_show'] = 1;
		if($output_data['title_style'] != 2) {
			$exclude = array(
				'title_rich_content', 'title_content', 'title_excerpt',
				'title_font_preset_html', 'title_font_preset_style', 'title_font_preset_weight', 'title_font_preset_transform',
				'title_excerpt_font_preset_html', 'title_excerpt_font_preset_style', 'title_excerpt_font_preset_weight', 'title_excerpt_font_preset_transform'
			);
			foreach($output_data as $key => $value) {
				if((strpos($key, 'title_') === 0 || strpos($key, 'breadcrumbs_') === 0) && strpos($key, 'title_icon') === false && !in_array($key, $exclude) && $value === '' && strpos($key, 'margin') === false) {
					$output_data[$key] = thegem_get_option_page_setting($key, $output_data[$key], $post_id, $type);
				}
			}
		}
	}

	if(isset($output_data['content_area_options']) && $output_data['content_area_options'] == 'default') {
		foreach($output_data as $key => $value) {
			if(strpos($key, 'content_padding_') === 0 || strpos($key, 'main_background_') === 0) {
				$output_data[$key] = thegem_get_option_page_setting($key, $output_data[$key], $post_id, $type);
			}
		}
	}
	if($output_data['sidebar_show'] == 'default') {
		$output_data['sidebar_show'] = thegem_get_option_page_setting('sidebar_show', $output_data['sidebar_show'], $post_id, $type);
		$output_data['sidebar_position'] = thegem_get_option_page_setting('sidebar_position', $output_data['sidebar_position'], $post_id, $type);
		$output_data['sidebar_sticky'] = thegem_get_option_page_setting('sidebar_sticky', $output_data['sidebar_sticky'], $post_id, $type);
	} elseif($output_data['sidebar_show'] == 'disabled') {
		$output_data['sidebar_show'] = 0;
	} else {
		$output_data['sidebar_show'] = 1;
	}

	if($output_data['effects_hide_footer'] == 'default') {
		$output_data['effects_hide_footer'] = thegem_get_option_page_setting('effects_hide_footer', $output_data['effects_hide_footer'], $post_id, $type);
		$output_data['effects_parallax_footer'] = thegem_get_option_page_setting('effects_parallax_footer', $output_data['effects_parallax_footer'], $post_id, $type);
	} elseif($output_data['effects_hide_footer'] == 'disabled') {
		$output_data['effects_hide_footer'] = 1;
	} else {
		$output_data['effects_hide_footer'] = 0;
	}

	if($output_data['footer_hide_default'] == 'default') {
		$output_data['footer_hide_default'] = thegem_get_option_page_setting('footer_hide_default', $output_data['footer_hide_default'], $post_id, $type);
	} elseif($output_data['footer_hide_default'] == 'disabled') {
		$output_data['footer_hide_default'] = 1;
	} else {
		$output_data['footer_hide_default'] = 0;
	}

	if($output_data['footer_hide_widget_area'] == 'default') {
		$output_data['footer_hide_widget_area'] = thegem_get_option_page_setting('footer_hide_widget_area', $output_data['footer_hide_widget_area'], $post_id, $type);
	} elseif($output_data['footer_hide_widget_area'] == 'disabled') {
		$output_data['footer_hide_widget_area'] = 1;
	} else {
		$output_data['footer_hide_widget_area'] = 0;
	}

	if($output_data['footer_custom_show'] == 'default') {
		$output_data['footer_custom_show'] = thegem_get_option_page_setting('footer_custom_show', $output_data['footer_custom_show'], $post_id, $type);
		$output_data['footer_custom'] = thegem_get_option_page_setting('footer_custom', $output_data['footer_custom'], $post_id, $type);
	} elseif($output_data['footer_custom_show'] == 'disabled') {
		$output_data['footer_custom_show'] = 0;
	} else {
		$output_data['footer_custom_show'] = 1;
	}

	if(isset($output_data['enable_page_preloader']) && $output_data['enable_page_preloader'] == 'default') {
		$output_data['enable_page_preloader'] = thegem_get_option_page_setting('enable_page_preloader', $output_data['enable_page_preloader'], $post_id, $type);
	} elseif($output_data['enable_page_preloader'] == 'disabled') {
		$output_data['enable_page_preloader'] = 0;
	} else {
		$output_data['enable_page_preloader'] = 1;
	}

	if(!isset($output_data['effects_page_scroller'])) {
		$output_data['effects_page_scroller'] = 0;
	}
	if(!isset($output_data['effects_one_pager'])) {
		$output_data['effects_one_pager'] = 0;
	}
	if(!isset($output_data['header_custom_menu'])) {
		$output_data['header_custom_menu'] = 0;
	}

	if($output_data['page_layout_breadcrumbs'] == 'default') {
		$output_data['page_layout_breadcrumbs'] = thegem_get_option_page_setting('page_layout_breadcrumbs', $output_data['page_layout_breadcrumbs'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_default_color'] = thegem_get_option_page_setting('page_layout_breadcrumbs_default_color', $output_data['page_layout_breadcrumbs_default_color'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_active_color'] = thegem_get_option_page_setting('page_layout_breadcrumbs_active_color', $output_data['page_layout_breadcrumbs_active_color'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_hover_color'] = thegem_get_option_page_setting('page_layout_breadcrumbs_hover_color', $output_data['page_layout_breadcrumbs_hover_color'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_alignment'] = thegem_get_option_page_setting('page_layout_breadcrumbs_alignment', $output_data['page_layout_breadcrumbs_alignment'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_bottom_spacing'] = thegem_get_option_page_setting('page_layout_breadcrumbs_bottom_spacing', $output_data['page_layout_breadcrumbs_bottom_spacing'], $post_id, $type);
		$output_data['page_layout_breadcrumbs_shop_category'] = thegem_get_option_page_setting('page_layout_breadcrumbs_shop_category', $output_data['page_layout_breadcrumbs_shop_category'], $post_id, $type);
	} elseif($output_data['page_layout_breadcrumbs'] == 'disabled') {
		$output_data['page_layout_breadcrumbs'] = 0;
	} else {
		$output_data['page_layout_breadcrumbs'] = 1;
	}

	/*if(in_array($type, array('blog', 'search', 'product_category')) && thegem_get_option('global_settings_apply_'.$type)) {
		$output_data = array_merge($output_data, $item_data);
	}*/

	$cache[$cacheKey] = $output_data;

	return $output_data;
}

function thegem_get_option_page_setting($key, $value, $post_id, $type='default') {
	global $thegem_global_page_settings;
	static $terms = [];
	static $postTypes = [];

	$defaults = $thegem_global_page_settings;
	$value = isset($defaults['global'][$key]) ? $defaults['global'][$key] : $value;
	if($type === 'blog' || $type === 'term' || $type === 'cpt_archive') {
		if (!isset($terms[$post_id])) {
			$term = get_term($post_id);
			$terms[$post_id] = $term;
		} else {
			$term = $terms[$post_id];
		}
		if($type === 'term' && !is_wp_error($term) && ($term->taxonomy == 'product_cat' || $term->taxonomy == 'product_tag' || $term->taxonomy == 'product_brand')) {
			//huck 2.5level for product title & paddings
			if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'title_show') {
				$value = thegem_get_option('product_title_show');
			}
			if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top') {
				$value = thegem_get_option('product_content_padding_top');
			}
			if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_tablet') {
				$value = thegem_get_option('product_content_padding_top_tablet');
			}
			if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_mobile') {
				$value = thegem_get_option('product_content_padding_top_mobile');
			}
			//huck for sidebar in shop grid
			if (thegem_get_option('product_archive_type') !== 'legacy' && isset($defaults['product_category'][$key]) && ($key == 'sidebar_show' || $key == 'sidebar_position' || $key == 'sidebar_sticky')) {
				$value = $defaults['product_category'][$key];
			}

			$value = isset($defaults['product_category'][$key]) && thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) ? $defaults['product_category'][$key] : $value;
		} else {
			$value = isset($defaults['blog'][$key]) && thegem_get_option('global_settings_apply_blog'.thegem_get_options_group_by_key($key)) ? $defaults['blog'][$key] : $value;
			if($type === 'cpt_archive' && is_post_type_archive() && in_array(get_queried_object()->name, thegem_get_available_po_custom_post_types())) {
				$cpt = get_queried_object()->name;
				if(!empty($defaults[$cpt.'_archive'])) {
					$value = isset($defaults[$cpt.'_archive'][$key]) && !empty($defaults[$cpt.'_archive']['global_settings_apply'.thegem_get_options_group_by_key($key)]) ? $defaults[$cpt.'_archive'][$key] : $value;
				}
			}
			if($type === 'term' && !is_wp_error($term)) {
				$tax = get_taxonomy($term->taxonomy);
				if(!empty($tax->object_type) && !empty($tax->object_type[0]) && in_array($tax->object_type[0] . '_archive', thegem_get_list_po_custom_taxonomies(), true)) {
					$cpt = $tax->object_type[0];
					if(!empty($defaults[$cpt.'_archive'])) {
						$value = isset($defaults[$cpt.'_archive'][$key]) && !empty($defaults[$cpt.'_archive']['global_settings_apply'.thegem_get_options_group_by_key($key)]) ? $defaults[$cpt.'_archive'][$key] : $value;
					}
				}
			}
		}
	} elseif($type === 'product_category') {
		//huck 2.5level for product title & paddings
		if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'title_show') {
			$value = thegem_get_option('product_title_show');
		}
		if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top') {
			$value = thegem_get_option('product_content_padding_top');
		}
		if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_tablet') {
			$value = thegem_get_option('product_content_padding_top_tablet');
		}
		if (!thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_mobile') {
			$value = thegem_get_option('product_content_padding_top_mobile');
		}
		//huck for sidebar in shop grid
		if (thegem_get_option('product_archive_type') !== 'legacy' && isset($defaults['product_category'][$key]) && ($key == 'sidebar_show' || $key == 'sidebar_position' || $key == 'sidebar_sticky')) {
			$value = $defaults['product_category'][$key];
		}

		$value = isset($defaults['product_category'][$key]) && thegem_get_option('global_settings_apply_product_categories'.thegem_get_options_group_by_key($key)) ? $defaults['product_category'][$key] : $value;
	} elseif($type === 'search') {
		$value = isset($defaults['search'][$key]) && thegem_get_option('global_settings_apply_search'.thegem_get_options_group_by_key($key)) ? $defaults['search'][$key] : $value;
	} else {
		if (!isset($postTypes[$post_id])) {
			$postType = get_post_type($post_id);
			$postTypes[$post_id] = $postType;
		} else {
			$postType = $postTypes[$post_id];
		}

		if($postType === 'page' || $type === 'default') {
			$value = isset($defaults['page'][$key]) && thegem_get_option('global_settings_apply_default'.thegem_get_options_group_by_key($key)) ? $defaults['page'][$key] : $value;
		}
		if($postType === 'post' || $type === 'post') {
			$value = isset($defaults['post'][$key]) && thegem_get_option('global_settings_apply_post'.thegem_get_options_group_by_key($key)) ? $defaults['post'][$key] : $value;
		}
		if($postType === 'thegem_pf_item' || $type === 'portfolio') {
			$value = isset($defaults['portfolio'][$key]) && thegem_get_option('global_settings_apply_portfolio'.thegem_get_options_group_by_key($key)) ? $defaults['portfolio'][$key] : $value;
		}
		if(in_array($postType, thegem_get_available_po_custom_post_types(), true)) {
			$value = isset($defaults[$postType][$key]) && !empty($defaults[$postType]['global_settings_apply'.thegem_get_options_group_by_key($key)]) ? $defaults[$postType][$key] : $value;
		}
		if($postType === 'product' || $type === 'product') {
			//huck 2.5level for product title & paddings
			if (!thegem_get_option('global_settings_apply_product'.thegem_get_options_group_by_key($key)) && $key == 'title_show') {
				$value = thegem_get_option('product_title_show');
			}
			if (!thegem_get_option('global_settings_apply_product'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top') {
				$value = thegem_get_option('product_content_padding_top');
			}
			if (!thegem_get_option('global_settings_apply_product'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_tablet') {
				$value = thegem_get_option('product_content_padding_top_tablet');
			}
			if (!thegem_get_option('global_settings_apply_product'.thegem_get_options_group_by_key($key)) && $key == 'content_padding_top_mobile') {
				$value = thegem_get_option('product_content_padding_top_mobile');
			}

			$value = isset($defaults['product'][$key]) && thegem_get_option('global_settings_apply_product'.thegem_get_options_group_by_key($key)) ? $defaults['product'][$key] : $value;
		}
	}
	return $value;
}

function thegeme_migrate_post_page_data($page_data = array()) {
	$old_options = $page_data;
//	ksort($old_options);
	$new_options = array();
	foreach($old_options as $option => $value) {
		switch ($option) {
			case 'title_style':
				if($old_options[$option] == 0 || $old_options[$option] == '') {
					$new_options['title_style'] = 1;
					$new_options['title_show'] = 'disabled';
				} else {
					$new_options['title_style'] = $old_options[$option];
					$new_options['title_show'] = 'enabled';
				}
				break;
			case 'title_alignment':
				$new_options['title_alignment'] = $old_options[$option];
				$new_options['title_breadcrumbs_alignment'] = $old_options[$option];
				break;
			case 'title_background_color':
				if(empty($old_options['title_background_image'])) {
					$new_options['title_background_type'] = 'color';
				}
				$new_options['title_background_color'] = $old_options[$option];
				$new_options['title_background_image_color'] = $old_options[$option];
				break;
			case 'title_background_image':
				if(!empty($old_options[$option])) {
					$new_options['title_background_image'] = $old_options[$option];
					$new_options['title_background_type'] = 'image';
				}
				break;
			case 'title_video_background':
				if(!empty($old_options[$option]) && !empty($old_options['title_video_type'])) {
					$new_options['title_background_type'] = 'video';
				}
				$new_options['title_background_video'] = $old_options[$option];
				break;
			case 'title_video_type':
				if(!empty($old_options[$option])) {
					$new_options['title_background_video_type'] = $old_options[$option];
				}
				break;
			case 'title_video_aspect_ratio':
				if(!empty($old_options[$option])) {
					$new_options['title_background_video_aspect_ratio'] = $old_options[$option];
				}
				break;
			case 'title_video_poster':
				if(!empty($old_options[$option])) {
					$new_options['title_background_video_poster'] = $old_options[$option];
				}
				break;
			case 'title_video_overlay_color':
				if(!empty($old_options[$option])) {
					$new_options['title_background_video_overlay'] = thegem_migrate_update_color($old_options[$option]).str_pad(dechex(ceil(floatval($old_options['title_video_overlay_opacity'])*255)), 2, '0', STR_PAD_LEFT);
				}
				break;
			case 'title_padding_top':
					$new_options['title_padding_top'] = $old_options['title_padding_top'];
					$new_options['title_padding_top_mobile'] = $old_options['title_padding_top'];
					$new_options['title_padding_top_tablet'] = $old_options['title_padding_top'];
				break;
			case 'title_padding_bottom':
					$new_options['title_padding_bottom'] = $old_options['title_padding_bottom'];
					$new_options['title_padding_bottom_mobile'] = $old_options['title_padding_bottom'];
					$new_options['title_padding_bottom_tablet'] = $old_options['title_padding_bottom'];
				break;
			case 'header_hide_top_area':
				if(!empty($old_options[$option])) {
					$new_options['header_hide_top_area'] = 'disabled';
					$new_options['header_hide_top_area_tablet'] = 'disabled';
					$new_options['header_hide_top_area_mobile'] = 'disabled';
				} else {
					$new_options['header_hide_top_area'] = 'default';
					$new_options['header_hide_top_area_tablet'] = 'default';
					$new_options['header_hide_top_area_mobile'] = 'default';
				}
				break;
			case 'footer_hide_default':
				if(!empty($old_options[$option])) {
					$new_options['footer_hide_default'] = 'disabled';
				} else {
					$new_options['footer_hide_default'] = 'default';
				}
				break;
			case 'footer_hide_widget_area':
				if(!empty($old_options[$option])) {
					$new_options['footer_hide_widget_area'] = 'disabled';
				} else {
					$new_options['footer_hide_widget_area'] = 'default';
				}
				break;
			case 'effects_hide_header':
				if(!empty($old_options[$option])) {
					$new_options['effects_hide_header'] = 'disabled';
				} else {
					$new_options['effects_hide_header'] = 'default';
				}
				break;
			case 'effects_hide_footer':
				if(!empty($old_options[$option])) {
					$new_options['effects_hide_footer'] = 'disabled';
				} else {
					$new_options['effects_hide_footer'] = 'default';
				}
				break;
			case 'effects_parallax_footer':
				if(!empty($old_options[$option])) {
					$new_options['effects_hide_footer'] = 'enabled';
				}
				$new_options['effects_parallax_footer'] = $old_options[$option];
				break;
			case 'sidebar_position':
				if(!empty($old_options[$option])) {
					$new_options['sidebar_show'] = 'enabled';
				} else {
					$new_options['sidebar_show'] = 'disabled';
				}
				$new_options['sidebar_position'] = $old_options[$option];
				break;
			case 'slideshow_type':
				if(!empty($old_options[$option])) {
					$new_options['title_style'] = 3;
					$new_options['title_show'] = 'enabled';
				}
				$new_options['slideshow_type'] = $old_options[$option];
				break;
			case 'footer_custom':
				if(!empty($old_options[$option])) {
					$new_options['footer_custom_show'] = 'enabled';
				}
				$new_options['footer_custom'] = $old_options[$option];
				break;
			case 'header_transparent':
			case 'header_menu_logo_light':
				if(!empty($old_options[$option])) {
					if(empty($old_options['effects_hide_header']) || $new_options['effects_hide_header'] !== 'disabled') {
						$new_options['effects_hide_header'] = 'enabled';
					}
					$new_options['menu_options'] = 'custom';
				}
				$new_options[$option] = $old_options[$option];
				break;
			case 'effects_page_scroller':
				if(!empty($old_options[$option])) {
					if(empty($old_options['effects_hide_header']) || $new_options['effects_hide_header'] !== 'disabled') {
						$new_options['effects_hide_header'] = 'enabled';
					}
					$new_options['menu_options'] = 'custom';
					$old_options['header_transparent'] = 1;
					$new_options['header_transparent'] = 1;
				}
				$new_options[$option] = $old_options[$option];
				break;
			case 'header_top_area_transparent':
				if(!empty($old_options[$option])) {
					if(empty($old_options['effects_hide_header']) || $new_options['effects_hide_header'] !== 'disabled') {
						$new_options['effects_hide_header'] = 'enabled';
					}
					$new_options['top_area_options'] = 'custom';
				}
				$new_options[$option] = $old_options[$option];
				break;
			case 'title_background_parallax':
				if(!empty($old_options[$option])) {
					$new_options['title_background_effect'] = 'parallax';
				} else {
					$new_options['title_background_effect'] = 'normal';
				}
				break;
			case 'effects_no_top_margin':
				if(!empty($old_options[$option])) {
					$new_options['content_area_options'] = 'custom';
					$new_options['content_padding_top'] = '0';
				}
				break;
			case 'effects_no_bottom_margin':
				if(!empty($old_options[$option])) {
					$new_options['content_area_options'] = 'custom';
					$new_options['content_padding_bottom'] = '0';
				}
				break;
			case 'title_top_margin':
				if(empty($old_options[$option])) {
					$new_options['title_top_margin'] = '';
				} else {
					$new_options['title_top_margin'] = $old_options[$option];
				}
				break;
			default:
				$new_options[$option] = $old_options[$option];
		}
	}
	$global_settings = thegem_theme_options_get_page_settings('global');
	if($new_options['title_background_type'] == 'color' && empty($new_options['title_background_color']) && !empty($global_settings['title_background_color']) && $new_options['title_style'] != 2) {
		$new_options['title_background_color'] = $global_settings['title_background_color'];
	}
	if(empty($new_options['title_text_color']) && !empty($global_settings['title_text_color']) && $new_options['title_style'] != 2) {
		$new_options['title_text_color'] = $global_settings['title_text_color'];
	}
	if(empty($new_options['title_excerpt_text_color']) && !empty($global_settings['title_excerpt_text_color']) && $new_options['title_style'] != 2) {
		$new_options['title_excerpt_text_color'] = $global_settings['title_excerpt_text_color'];
	}
	if(empty($new_options['title_xlarge'])) {
		$new_options['title_xlarge'] = 0;
	}
	if(!empty($new_options['title_xlarge']) && $new_options['title_style'] == 2) {
		$new_options['title_xlarge_custom_migrate'] = $new_options['title_xlarge'];
	}
	if(empty($new_options['effects_hide_header']) || $new_options['effects_hide_header'] == 'default') {
		$new_options['effects_hide_header'] = 'default';
	} else if($new_options['effects_hide_header'] == 'enabled') {
		$new_options['effects_hide_header'] = 'enabled';
	} else {
		$new_options['effects_hide_header'] = 'disabled';
	}
	if(empty($new_options['effects_hide_footer']) || $new_options['effects_hide_footer'] == 'default') {
		$new_options['effects_hide_footer'] = 'default';
	} else if($new_options['effects_hide_footer'] == 'enabled') {
		$new_options['effects_hide_footer'] = 'enabled';
	} else {
		$new_options['effects_hide_footer'] = 'disabled';
	}
	if(empty($new_options['title_breadcrumbs'])) {
		$new_options['title_breadcrumbs'] = $global_settings['title_breadcrumbs'];
	}
	if(empty($new_options['enable_page_preloader'])) {
		$new_options['enable_page_preloader'] = 'default';
	} else {
		$new_options['enable_page_preloader'] = 'enabled';
	}
	return $new_options;
}

function thegeme_migrate_post_general_item_data($page_data = array()) {
	if(!empty($page_data['show_featured_content'])) {
		$page_data['show_featured_content'] = 'enabled';
	} else {
		$page_data['show_featured_content'] = 'disabled';
	}
	return $page_data;
}

function thegem_get_sanitize_admin_page_data($post_id = 0, $item_data = array(), $type = false) {
	$page_data = apply_filters('thegem_admin_page_data_defaults', array(
		'title_show' => 'default',
		'title_style' => '1',
		'title_template' => '',
		'title_use_page_settings' => 0,
		'title_xlarge' => '',
		'title_rich_content' => '',
		'title_content' => '',
		'title_background_type' => 'color',
		'title_background_image' => thegem_get_option('default_page_title_background_image'),
		'title_background_image_repeat' => '',
		'title_background_position_x' => 'center',
		'title_background_position_y' => 'top',
		'title_background_size' => 'cover',
		'title_background_image_color' => '',
		'title_background_image_overlay' => '',
		'title_background_gradient_type' => 'linear',
		'title_background_gradient_angle' => '0',
		'title_background_gradient_position' => 'center center',
		'title_background_gradient_point1_color' => '',
		'title_background_gradient_point1_position' => '0',
		'title_background_gradient_point2_color' => '',
		'title_background_gradient_point2_position' => '100',
		'title_background_effect' => 'normal',
		'title_background_ken_burns_direction' => '',
		'title_background_ken_burns_transition_speed' => '15000',
		'title_background_video_play_on_mobile' => '',
		'title_background_color' => thegem_get_option('default_page_title_background_color'),
		'title_background_video_type' => '',
		'title_background_video' => '',
		'title_background_video_aspect_ratio' => '',
		'title_background_video_overlay_color' => '',
		'title_background_video_overlay_opacity' => '',
		'title_background_video_poster' => '',
		'title_menu_on_video' => '',
		'title_text_color' => thegem_get_option('default_page_title_text_color'),
		'title_excerpt_text_color' => thegem_get_option('default_page_title_excerpt_text_color'),
		'title_excerpt' => '',
		'title_hide_excerpt' => 0,
		'title_title_width' => thegem_get_option('default_page_title_max_width'),
		'title_excerpt_width' => thegem_get_option('default_page_title_excerpt_width'),
		'title_font_preset_html' => '',
		'title_font_preset_style' => '',
		'title_font_preset_weight' => '',
		'title_font_preset_transform' => '',
		'title_excerpt_font_preset_html' => '',
		'title_excerpt_font_preset_style' => '',
		'title_excerpt_font_preset_weight' => '',
		'title_excerpt_font_preset_transform' => '',
		'title_padding_top' => thegem_get_option('default_page_title_top_padding') ? thegem_get_option('default_page_title_top_padding') : 80,
		'title_padding_top_tablet' => thegem_get_option('default_page_title_top_padding_tablet') ? thegem_get_option('default_page_title_top_padding_tablet') : 80,
		'title_padding_top_mobile' => thegem_get_option('default_page_title_top_padding_mobile') ? thegem_get_option('default_page_title_top_padding_mobile') : 80,
		'title_padding_bottom' => thegem_get_option('default_page_title_bottom_padding') ? thegem_get_option('default_page_title_bottom_padding') : 80,
		'title_padding_bottom_tablet' => thegem_get_option('default_page_title_bottom_padding_tablet') ? thegem_get_option('default_page_title_bottom_padding_tablet') : 80,
		'title_padding_bottom_mobile' => thegem_get_option('default_page_title_bottom_padding_mobile') ? thegem_get_option('default_page_title_bottom_padding_mobile') : 80,
		'title_padding_left' => 0,
		'title_padding_left_tablet' => 0,
		'title_padding_left_mobile' => 0,
		'title_padding_right' => 0,
		'title_padding_right_tablet' => 0,
		'title_padding_right_mobile' => 0,
		'title_top_margin' => thegem_get_option('default_page_title_top_margin'),
		'title_top_margin_tablet' => thegem_get_option('default_page_title_top_margin_tablet'),
		'title_top_margin_mobile' => thegem_get_option('default_page_title_top_margin_mobile'),
		'title_excerpt_top_margin' => thegem_get_option('default_page_title_excerpt_top_margin') ? thegem_get_option('default_page_title_excerpt_top_margin') : 18,
		'title_excerpt_top_margin_tablet' => thegem_get_option('default_page_title_excerpt_top_margin_tablet') ? thegem_get_option('default_page_title_excerpt_top_margin_tablet') : 18,
		'title_excerpt_top_margin_mobile' => thegem_get_option('default_page_title_excerpt_top_margin_mobile') ? thegem_get_option('default_page_title_excerpt_top_margin_mobile') : 18,
		'title_breadcrumbs' => '',
		'title_alignment' => thegem_get_option('default_page_title_alignment'),
		'title_icon_pack' => '',
		'title_icon' => '',
		'title_icon_color' => '',
		'title_icon_color_2' => '',
		'title_icon_background_color' => '',
		'title_icon_shape' => '',
		'title_icon_border_color' => '',
		'title_icon_size' => '',
		'title_icon_style' => '',
		'title_icon_opacity' => '',
		'breadcrumbs_default_color' => '',
		'breadcrumbs_active_color' => '',
		'breadcrumbs_hover_color' => '',
		'title_breadcrumbs_alignment' => '',
		'header_transparent' => '',
		'header_transparent_mobile' => '',
		'header_opacity' => '',
		'header_menu_logo_light' => '',
		'disable_fixed_header' => '',
		'sticky_header_on_mobile' => '',
		'header_hide_top_area' => 'default',
		'header_hide_top_area_tablet' => 'default',
		'header_hide_top_area_mobile' => 'default',
		'menu_show' => 'default',
		'menu_options' => 'default',
		'header_custom_menu' => '',
		'header_top_area_transparent' => '',
		'header_top_area_opacity' => '',
		'top_area_options' => 'default',
		'header_source' => 'default',
		'header_builder' => '',
		'header_builder_sticky_desktop' => false,
		'header_builder_sticky_mobile' => false,
		'header_builder_sticky_hide_desktop' => false,
		'header_builder_sticky_hide_mobile' => '1',
		'header_builder_sticky' => '',
		'header_builder_sticky_opacity' => '',
		'header_builder_light_color' => '',
		'header_builder_light_color_hover' => '',
		'main_background_type' => 'color',
		'main_background_color' => '',
		'main_background_image' => '',
		'main_background_image_repeat' => '',
		'main_background_position_x' => 'center',
		'main_background_position_y' => 'top',
		'main_background_size' => 'cover',
		'main_background_image_color' => '',
		'main_background_image_overlay' => '',
		'main_background_gradient_type' => 'linear',
		'main_background_gradient_angle' => '0',
		'main_background_gradient_position' => 'center center',
		'main_background_gradient_point1_color' => '',
		'main_background_gradient_point1_position' => '0',
		'main_background_gradient_point2_color' => '',
		'main_background_gradient_point2_position' => '100',
		'main_background_pattern' => '',
		'content_padding_top' => '',
		'content_padding_top_tablet' => '',
		'content_padding_top_mobile' => '',
		'content_padding_bottom' => '',
		'content_padding_bottom_tablet' => '',
		'content_padding_bottom_mobile' => '',
		'content_area_options' => 'default',
		'footer_custom_show' => 'default',
		'footer_custom' => '',
		'footer_hide_default' => 'default',
		'footer_hide_widget_area' => 'default',
		'effects_disabled' => false,
		'effects_one_pager' => false,
		'effects_parallax_footer' => false,
		'effects_no_bottom_margin' => false,
		'effects_no_top_margin' => false,
		'redirect_to_subpage' => false,
		'effects_hide_header' => 'default',
		'effects_hide_footer' => 'default',
		'effects_page_scroller' => false,
		'effects_page_scroller_mobile' => false,
		'effects_page_scroller_type' => 'advanced',
		'fullpage_disabled_dots' => false,
		'fullpage_style_dots' => '',
		'fullpage_disabled_tooltips_dots' => false,
		'fullpage_fixed_background' => false,
		'fullpage_enable_continuous' => false,
		'fullpage_disabled_mobile' => false,
		'fullpage_scroll_effect' => 'normal',
		'enable_page_preloader' => 'default',
		'slideshow_type' => '',
		'slideshow_slideshow' => '',
		'slideshow_layerslider' => '',
		'slideshow_revslider' => '',
		'slideshow_preloader' => false,
		'sidebar_show' => 'default',
		'sidebar_position' => '',
		'sidebar_sticky' => '',
		'product_header_separator' => '',
		'page_layout_breadcrumbs' => '',
		'page_layout_breadcrumbs_default_color' => thegem_get_option('page_layout_breadcrumbs_default_color'),
		'page_layout_breadcrumbs_active_color' => thegem_get_option('page_layout_breadcrumbs_active_color'),
		'page_layout_breadcrumbs_hover_color' => thegem_get_option('page_layout_breadcrumbs_hover_color'),
		'page_layout_breadcrumbs_alignment' => thegem_get_option('page_layout_breadcrumbs_alignment'),
		'page_layout_breadcrumbs_bottom_spacing' => thegem_get_option('page_layout_breadcrumbs_bottom_spacing'),
		'page_layout_breadcrumbs_shop_category' => thegem_get_option('page_layout_breadcrumbs_shop_category'),
		'delay_js_execution_desktop' => '0',
		'disable_cache' => '0',

	), $post_id, $item_data, $type);
	foreach($page_data as $key => $value) {
		if($value !== 'default' && $key != 'delay_js_execution_desktop' && $key != 'title_icon') {
			$page_data[$key] = thegem_get_option_page_setting($key, $value, $post_id, $type);
		}
	}
	if(is_array($item_data) && !empty($item_data)) {
		$page_data = array_merge($page_data, $item_data);
	} elseif($post_id != 0) {
		$page_data = thegem_get_post_data($page_data, 'page', $post_id, $type);
	}
	$page_data['title_xlarge'] = $page_data['title_xlarge'] ? 1 : 0;
	$page_data['title_rich_content'] = $page_data['title_rich_content'] ? 1 : 0;
	$page_data['title_show'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['title_show'], 'default');
	$page_data['title_style'] = thegem_check_array_value(array('', '1', '2', '3'), $page_data['title_style'], '1');
	$page_data['title_template'] = strval(intval($page_data['title_template']) >= 0 ? intval($page_data['title_template']) : 0);
	$page_data['title_use_page_settings'] = $page_data['title_use_page_settings'] ? 1 : 0;
	$page_data['title_background_type'] = thegem_check_array_value(array('color', 'image', 'video', 'gradient'), $page_data['title_background_type'], 'color');
	$page_data['title_background_image'] = esc_url($page_data['title_background_image']);
	$page_data['title_background_effect'] = thegem_check_array_value(array_keys(thegem_get_page_title_background_effect_list()), $page_data['title_background_effect'], 'normal');
	$page_data['title_background_ken_burns_direction'] = thegem_check_array_value(array_keys(thegem_get_page_title_background_ken_burns_direction_list()), $page_data['title_background_ken_burns_direction'], 'zoom_in');
	$page_data['title_background_ken_burns_transition_speed'] = intval($page_data['title_background_ken_burns_transition_speed']) >= 0 ? intval($page_data['title_background_ken_burns_transition_speed']) : 0;
	$page_data['title_background_video_play_on_mobile'] = $page_data['title_background_video_play_on_mobile'] ? 1 : 0;
	$page_data['title_background_color'] = sanitize_text_field($page_data['title_background_color']);
	$page_data['title_background_image_color'] = sanitize_text_field($page_data['title_background_image_color']);
	$page_data['title_background_image_overlay'] = sanitize_text_field($page_data['title_background_image_overlay']);
	$page_data['title_background_image_repeat'] = $page_data['title_background_image_repeat'] ? 1 : 0;
	$page_data['title_background_size'] = thegem_check_array_value(array('auto', 'cover', 'contain'), $page_data['title_background_size'], 'cover');
	$page_data['title_background_position_x'] = thegem_check_array_value(array('center', 'left', 'right'), $page_data['title_background_position_x'], 'center');
	$page_data['title_background_position_y'] = thegem_check_array_value(array('center', 'top', 'bottom'), $page_data['title_background_position_y'], 'top');
	$page_data['title_background_gradient_type'] = thegem_check_array_value(array('linear', 'circular'), $page_data['title_background_gradient_type'], 'linear');
	$page_data['title_background_gradient_angle'] = intval($page_data['title_background_gradient_angle']) >= 0 ? intval($page_data['title_background_gradient_angle']) : 0;
	$page_data['title_background_gradient_point1_color'] = sanitize_text_field($page_data['title_background_gradient_point1_color']);
	$page_data['title_background_gradient_point2_color'] = sanitize_text_field($page_data['title_background_gradient_point2_color']);
	$page_data['title_background_gradient_point1_position'] = intval($page_data['title_background_gradient_point1_position']) >= 0 ? intval($page_data['title_background_gradient_point1_position']) : 0;
	$page_data['title_background_gradient_point2_position'] = intval($page_data['title_background_gradient_point2_position']) >= 0 ? intval($page_data['title_background_gradient_point2_position']) : 100;
	$page_data['title_background_video_type'] = thegem_check_array_value(array('', 'youtube', 'vimeo', 'self'), $page_data['title_background_video_type'], '');
	$page_data['title_background_video'] = sanitize_text_field($page_data['title_background_video']);
	$page_data['title_background_video_aspect_ratio'] = sanitize_text_field($page_data['title_background_video_aspect_ratio']);
	$page_data['title_background_video_overlay_color'] = sanitize_text_field($page_data['title_background_video_overlay_color']);
	$page_data['title_background_video_overlay_opacity'] = sanitize_text_field($page_data['title_background_video_overlay_opacity']);
	$page_data['title_background_video_poster'] = esc_url($page_data['title_background_video_poster']);
	$page_data['title_text_color'] = sanitize_text_field($page_data['title_text_color']);
	$page_data['title_excerpt_text_color'] = sanitize_text_field($page_data['title_excerpt_text_color']);
	$page_data['title_excerpt'] = implode("\n", array_map('sanitize_text_field', explode("\n", $page_data['title_excerpt'])));
	$page_data['title_hide_excerpt'] = $page_data['title_hide_excerpt'] ? 1 : 0;
	$page_data['title_title_width'] = intval($page_data['title_title_width']) >= 0 && $page_data['title_title_width'] !== '' ? intval($page_data['title_title_width']) : '';
	$page_data['title_excerpt_width'] = intval($page_data['title_excerpt_width']) >= 0 && $page_data['title_excerpt_width'] !== '' ? intval($page_data['title_excerpt_width']) : '';
	$page_data['title_font_preset_html'] = thegem_check_array_value(array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'), $page_data['title_font_preset_html'], '');
	$page_data['title_font_preset_style'] = thegem_check_array_value(array('title-h1', 'title-h2', 'title-h3', 'title-h4', 'title-h5', 'title-h6', 'title-xlarge', 'styled-subtitle', 'title-main-menu', 'title-body', 'title-tiny-body'), $page_data['title_font_preset_style'], '');
	$page_data['title_font_preset_weight'] = thegem_check_array_value(array('light'), $page_data['title_font_preset_weight'], '');
	$page_data['title_font_preset_transform'] = thegem_check_array_value(array('none', 'capitalize', 'lowercase', 'uppercase'), $page_data['title_font_preset_transform'], '');
	$page_data['title_excerpt_font_preset_html'] = thegem_check_array_value(array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'), $page_data['title_excerpt_font_preset_html'], '');
	$page_data['title_excerpt_font_preset_style'] = thegem_check_array_value(array('title-h1', 'title-h2', 'title-h3', 'title-h4', 'title-h5', 'title-h6', 'title-xlarge', 'styled-subtitle', 'title-main-menu', 'title-body', 'title-tiny-body'), $page_data['title_excerpt_font_preset_style'], '');
	$page_data['title_excerpt_font_preset_weight'] = thegem_check_array_value(array('light'), $page_data['title_excerpt_font_preset_weight'], '');
	$page_data['title_excerpt_font_preset_transform'] = thegem_check_array_value(array('none', 'capitalize', 'lowercase', 'uppercase'), $page_data['title_excerpt_font_preset_transform'], '');
	$page_data['title_top_margin'] = $page_data['title_top_margin'] !== '' ? intval($page_data['title_top_margin']) : '';
	$page_data['title_top_margin_tablet'] = $page_data['title_top_margin_tablet'] !== '' ? intval($page_data['title_top_margin_tablet']) : '';
	$page_data['title_top_margin_mobile'] = $page_data['title_top_margin_mobile'] !== '' ? intval($page_data['title_top_margin_mobile']) : '';
	$page_data['title_excerpt_top_margin'] = $page_data['title_excerpt_top_margin'] !== '' ? intval($page_data['title_excerpt_top_margin']) : '';
	$page_data['title_excerpt_top_margin_tablet'] = $page_data['title_excerpt_top_margin_tablet'] !== '' ? intval($page_data['title_excerpt_top_margin_tablet']) : '';
	$page_data['title_excerpt_top_margin_mobile'] = $page_data['title_excerpt_top_margin_mobile'] !== '' ? intval($page_data['title_excerpt_top_margin_mobile']) : '';
	$page_data['title_breadcrumbs'] = $page_data['title_breadcrumbs'] ? 1 : 0;
	$page_data['title_padding_top'] = intval($page_data['title_padding_top']) >= 0 ? intval($page_data['title_padding_top']) : 0;
	$page_data['title_padding_top_tablet'] = intval($page_data['title_padding_top_tablet']) >= 0 ? intval($page_data['title_padding_top_tablet']) : 0;
	$page_data['title_padding_top_mobile'] = intval($page_data['title_padding_top_mobile']) >= 0 ? intval($page_data['title_padding_top_mobile']) : 0;
	$page_data['title_padding_bottom'] = intval($page_data['title_padding_bottom']) >= 0 ? intval($page_data['title_padding_bottom']) : 0;
	$page_data['title_padding_bottom_tablet'] = intval($page_data['title_padding_bottom_tablet']) >= 0 ? intval($page_data['title_padding_bottom_tablet']) : 0;
	$page_data['title_padding_bottom_mobile'] = intval($page_data['title_padding_bottom_mobile']) >= 0 ? intval($page_data['title_padding_bottom_mobile']) : 0;
	$page_data['title_padding_left'] = intval($page_data['title_padding_left']) >= 0 ? intval($page_data['title_padding_left']) : 0;
	$page_data['title_padding_left_tablet'] = intval($page_data['title_padding_left_tablet']) >= 0 ? intval($page_data['title_padding_left_tablet']) : 0;
	$page_data['title_padding_left_mobile'] = intval($page_data['title_padding_left_mobile']) >= 0 ? intval($page_data['title_padding_left_mobile']) : 0;
	$page_data['title_padding_right'] = intval($page_data['title_padding_right']) >= 0 ? intval($page_data['title_padding_right']) : 0;
	$page_data['title_padding_right_tablet'] = intval($page_data['title_padding_right_tablet']) >= 0 ? intval($page_data['title_padding_right_tablet']) : 0;
	$page_data['title_padding_right_mobile'] = intval($page_data['title_padding_right_mobile']) >= 0 ? intval($page_data['title_padding_right_mobile']) : 0;
	$page_data['title_icon_pack'] = thegem_check_array_value(array('elegant', 'material', 'fontawesome', 'userpack'), $page_data['title_icon_pack'], 'elegant');
	$page_data['title_icon'] = sanitize_text_field($page_data['title_icon']);
	$page_data['title_alignment'] = thegem_check_array_value(array('', 'center', 'left', 'right'), $page_data['title_alignment'], '');
	$page_data['title_icon_color'] = sanitize_text_field($page_data['title_icon_color']);
	$page_data['title_icon_color_2'] = sanitize_text_field($page_data['title_icon_color_2']);
	$page_data['title_icon_background_color'] = sanitize_text_field($page_data['title_icon_background_color']);
	$page_data['title_icon_border_color'] = sanitize_text_field($page_data['title_icon_border_color']);
	$page_data['title_icon_shape'] = thegem_check_array_value(array('circle', 'square', 'romb', 'hexagon'), $page_data['title_icon_shape'], 'circle');
	$page_data['title_icon_size'] = thegem_check_array_value(array('small', 'medium', 'large', 'xlarge'), $page_data['title_icon_size'], 'large');
	$page_data['title_icon_style'] = thegem_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg'), $page_data['title_icon_style'], '');
	$page_data['title_icon_opacity'] = floatval($page_data['title_icon_opacity']) >= 0 && floatval($page_data['title_icon_opacity']) <= 1 ? floatval($page_data['title_icon_opacity']) : 0;
	$page_data['breadcrumbs_default_color'] = sanitize_text_field($page_data['breadcrumbs_default_color']);
	$page_data['breadcrumbs_active_color'] = sanitize_text_field($page_data['breadcrumbs_active_color']);
	$page_data['breadcrumbs_hover_color'] = sanitize_text_field($page_data['breadcrumbs_hover_color']);
	$page_data['title_breadcrumbs_alignment'] = thegem_check_array_value(array('center', 'left', 'right'), $page_data['title_breadcrumbs_alignment'], 'center');
	$page_data['header_transparent'] = $page_data['header_transparent'] ? 1 : 0;
	$page_data['header_transparent_mobile'] = $page_data['header_transparent_mobile'] ? 1 : 0;
	$page_data['header_opacity'] = intval($page_data['header_opacity']) >= 0 && intval($page_data['header_opacity']) <= 100 ? intval($page_data['header_opacity']) : 0;
	$page_data['header_top_area_transparent'] = $page_data['header_top_area_transparent'] ? 1 : 0;
	$page_data['header_top_area_opacity'] = intval($page_data['header_top_area_opacity']) >= 0 && intval($page_data['header_top_area_opacity']) <= 100 ? intval($page_data['header_top_area_opacity']) : 0;
	$page_data['header_menu_logo_light'] = $page_data['header_menu_logo_light'] ? 1 : 0;
	$page_data['disable_fixed_header'] = $page_data['disable_fixed_header'] ? 1 : 0;
	$page_data['sticky_header_on_mobile'] = $page_data['sticky_header_on_mobile'] ? 1 : '';
	$page_data['header_hide_top_area'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['header_hide_top_area'], 'default');
	$page_data['header_hide_top_area_tablet'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['header_hide_top_area_tablet'], 'default');
	$page_data['header_hide_top_area_mobile'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['header_hide_top_area_mobile'], 'default');
	$page_data['menu_show'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['menu_show'], 'default');
	$page_data['menu_options'] = thegem_check_array_value(array('default', 'custom'), $page_data['menu_options'], 'default');
	$page_data['header_custom_menu'] = intval($page_data['header_custom_menu']) >= 0 ? intval($page_data['header_custom_menu']) : 0;
	$page_data['top_area_options'] = thegem_check_array_value(array('default', 'custom'), $page_data['top_area_options'], 'default');
	$page_data['header_source'] = thegem_check_array_value(array('default', 'builder'), $page_data['header_source'], 'default');
	$page_data['header_builder'] = strval(intval($page_data['header_builder']) >= 0 ? intval($page_data['header_builder']) : 0);
	$page_data['header_builder_sticky_desktop'] = $page_data['header_builder_sticky_desktop'] ? 1 : 0;
	$page_data['header_builder_sticky_mobile'] = $page_data['header_builder_sticky_mobile'] ? 1 : 0;
	$page_data['header_builder_sticky_hide_desktop'] = $page_data['header_builder_sticky_hide_desktop'] ? 1 : 0;
	$page_data['header_builder_sticky_hide_mobile'] = $page_data['header_builder_sticky_hide_mobile'] ? 1 : 0;
	$page_data['header_builder_sticky'] = strval(intval($page_data['header_builder_sticky']) >= 0 ? intval($page_data['header_builder_sticky']) : 0);
	$page_data['header_builder_sticky_opacity'] = intval($page_data['header_builder_sticky_opacity']) >= 0 && intval($page_data['header_builder_sticky_opacity']) <= 100 ? intval($page_data['header_builder_sticky_opacity']) : 0;
	$page_data['header_builder_light_color'] = sanitize_text_field($page_data['header_builder_light_color']);
	$page_data['header_builder_light_color_hover'] = sanitize_text_field($page_data['header_builder_light_color_hover']);
	$page_data['content_area_options'] = thegem_check_array_value(array('default', 'custom'), $page_data['content_area_options'], 'default');
	$page_data['content_padding_top'] = intval($page_data['content_padding_top']) >= 0 && $page_data['content_padding_top'] !== '' ? intval($page_data['content_padding_top']) : '';
	$page_data['content_padding_top_tablet'] = intval($page_data['content_padding_top_tablet']) >= 0 && $page_data['content_padding_top_tablet'] !== '' ? intval($page_data['content_padding_top_tablet']) : '';
	$page_data['content_padding_top_mobile'] = intval($page_data['content_padding_top_mobile']) >= 0 && $page_data['content_padding_top_mobile'] !== '' ? intval($page_data['content_padding_top_mobile']) : '';
	$page_data['content_padding_bottom'] = intval($page_data['content_padding_bottom']) >= 0 && $page_data['content_padding_bottom'] !== '' ? intval($page_data['content_padding_bottom']) : '';
	$page_data['content_padding_bottom_tablet'] = intval($page_data['content_padding_bottom_tablet']) >= 0 && $page_data['content_padding_bottom_tablet'] !== '' ? intval($page_data['content_padding_bottom_tablet']) : '';
	$page_data['content_padding_bottom_mobile'] = intval($page_data['content_padding_bottom_mobile']) >= 0 && $page_data['content_padding_bottom_mobile'] !== '' ? intval($page_data['content_padding_bottom_mobile']) : '';
	$page_data['footer_custom_show'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['footer_custom_show'], 'default');
	$page_data['footer_custom'] = strval(intval($page_data['footer_custom']) >= 0 ? intval($page_data['footer_custom']) : 0);
	$page_data['footer_hide_default'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['footer_hide_default'], 'default');
	$page_data['footer_hide_widget_area'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['footer_hide_widget_area'], 'default');
	$page_data['effects_disabled'] = $page_data['effects_disabled'] ? 1 : 0;
	$page_data['effects_one_pager'] = $page_data['effects_one_pager'] ? 1 : 0;
	$page_data['effects_parallax_footer'] = $page_data['effects_parallax_footer'] ? 1 : 0;
	$page_data['effects_no_bottom_margin'] = $page_data['effects_no_bottom_margin'] ? 1 : 0;
	$page_data['effects_no_top_margin'] = $page_data['effects_no_top_margin'] ? 1 : 0;
	$page_data['redirect_to_subpage'] = $page_data['redirect_to_subpage'] ? 1 : 0;
	$page_data['effects_hide_header'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['effects_hide_header'], 'default');
	$page_data['effects_hide_footer'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['effects_hide_footer'], 'default');
	$page_data['effects_page_scroller'] = $page_data['effects_page_scroller'] ? 1 : 0;
	$page_data['effects_page_scroller_mobile'] = $page_data['effects_page_scroller_mobile'] ? 1 : 0;
	if ($page_data['effects_page_scroller'] && empty($page_data['effects_page_scroller_type'])) {
		$page_data['effects_page_scroller_type'] = 'advanced';
	}
	$page_data['effects_page_scroller_type'] = thegem_check_array_value(array_keys(thegem_get_page_scroller_types()), $page_data['effects_page_scroller_type'], 'advanced');
	$page_data['fullpage_disabled_dots'] = $page_data['fullpage_disabled_dots'] ? 1 : 0;
	$page_data['fullpage_style_dots'] = thegem_check_array_value(array_keys(thegem_fullpage_dots_styles()), $page_data['fullpage_style_dots'], 'outline');
	$page_data['fullpage_disabled_tooltips_dots'] = $page_data['fullpage_disabled_tooltips_dots'] ? 1 : 0;
	$page_data['fullpage_enable_continuous'] = $page_data['fullpage_enable_continuous'] ? 1 : 0;
	$page_data['fullpage_disabled_mobile'] = $page_data['fullpage_disabled_mobile'] ? 1 : 0;
	$page_data['fullpage_scroll_effect'] = thegem_check_array_value(array_keys(thegem_fullpage_scroll_effects()), $page_data['fullpage_scroll_effect'], 'normal');
	if (isset($page_data['fullpage_fixed_background']) && $page_data['fullpage_fixed_background'] == 1) {
		$page_data['fullpage_scroll_effect'] = 'fixed_background';
	}
	$page_data['enable_page_preloader'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['enable_page_preloader'], 'default');
	$page_data['slideshow_type'] = thegem_check_array_value(array('', 'NivoSlider', 'LayerSlider', 'revslider'), $page_data['slideshow_type'], '');
	$page_data['slideshow_slideshow'] = sanitize_text_field($page_data['slideshow_slideshow']);
	$page_data['slideshow_layerslider'] = sanitize_text_field($page_data['slideshow_layerslider']);
	$page_data['slideshow_revslider'] = sanitize_text_field($page_data['slideshow_revslider']);
	$page_data['slideshow_preloader'] = $page_data['slideshow_preloader'] ? 1 : 0;
	$page_data['sidebar_show'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['sidebar_show'], 'default');
	$page_data['sidebar_position'] = thegem_check_array_value(array('left', 'right'), $page_data['sidebar_position'], 'left');
	$page_data['sidebar_sticky'] = $page_data['sidebar_sticky'] ? 1 : 0;
	$page_data['product_header_separator'] = $page_data['product_header_separator'] ? 1 : 0;
	$page_data['page_layout_breadcrumbs'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $page_data['page_layout_breadcrumbs'], 'default');
	$page_data['page_layout_breadcrumbs_default_color'] = sanitize_text_field($page_data['page_layout_breadcrumbs_default_color']);
	$page_data['page_layout_breadcrumbs_active_color'] = sanitize_text_field($page_data['page_layout_breadcrumbs_active_color']);
	$page_data['page_layout_breadcrumbs_hover_color'] = sanitize_text_field($page_data['page_layout_breadcrumbs_hover_color']);
	$page_data['page_layout_breadcrumbs_alignment'] = thegem_check_array_value(array('left', 'center', 'right'), $page_data['page_layout_breadcrumbs_alignment'], 'left');
	$page_data['page_layout_breadcrumbs_bottom_spacing'] = sanitize_text_field($page_data['page_layout_breadcrumbs_bottom_spacing']);
	$page_data['page_layout_breadcrumbs_shop_category'] = $page_data['page_layout_breadcrumbs_shop_category'] ? 1 : 0;
	$page_data['delay_js_execution_desktop'] = $page_data['delay_js_execution_desktop'] ? 1 : 0;
	$page_data['disable_cache'] = $page_data['disable_cache'] ? 1 : 0;

	return apply_filters('thegem_admin_page_data', $page_data, $post_id, $item_data, $type);
}

function thegem_get_sanitize_page_title_data($post_id = 0, $item_data = array(), $type = false) {
	$page_data = thegem_get_output_page_settings($post_id, $item_data, $type);
	if(empty($page_data['title_show'])) {
		$page_data['title_style'] = '';
	}
	if($page_data['title_style'] == 3) {
		$page_data['title_style'] = '';
	}
	return $page_data;
}

function thegem_get_sanitize_page_header_data($post_id = 0, $item_data = array(), $type = false) {
	return thegem_get_output_page_settings($post_id, $item_data, $type);
}

function thegem_get_sanitize_page_effects_data($post_id = 0, $item_data = array(), $type = false) {
	return thegem_get_output_page_settings($post_id, $item_data, $type);
}

function thegem_get_sanitize_page_preloader_data($post_id = 0, $item_data = array(), $type = false) {
	return thegem_get_output_page_settings($post_id, $item_data, $type);
}

function thegem_get_sanitize_page_slideshow_data($post_id = 0, $item_data = array(), $type = false) {
	return thegem_get_output_page_settings($post_id, $item_data, $type);
}

function thegem_get_sanitize_page_sidebar_data($post_id = 0, $item_data = array(), $type = false) {
	$page_data = thegem_get_output_page_settings($post_id, $item_data, $type);
	if(empty($page_data['sidebar_show'])) {
		$page_data['sidebar_position'] = '';
	}
	return $page_data;
}

function thegem_get_sanitize_admin_post_data($post_id = 0, $item_data = array()) {
	$post_item_data = apply_filters('thegem_post_data_defaults', array(
		'post_layout_settings' => 'default',
		'post_layout_source' => thegem_get_option('post_layout_source'),
		'post_builder_template' => thegem_get_option('post_builder_template'),
		'show_featured_posts_slider' => 0,
		'show_featured_content' => 'default',
		'video_type' => 'youtube',
		'video' => '',
		'video_aspect_ratio' => '',
		'video_play_on_mobile' => '',
		'video_overlay' => '',
		'video_poster' => '',
		'video_start' => 'open_in_lightbox',
		'quote_text' => '',
		'quote_author' => '',
		'quote_background' => '',
		'quote_author_color' => '',
		'audio' => '',
		'gallery' => 0,
		'gallery_autoscroll' => '',
		'highlight' => 0,
		'highlight_type' => 'squared',
		'highlight_style' => 'default',
		'highlight_title_left_background' => '#00BCD4FF',
		'highlight_title_left_color' => '#FFFFFFFF',
		'highlight_title_right_background' => '#00BCD4FF',
		'highlight_title_right_color' => '#FFFFFFFF',
	), $post_id, $item_data);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'post_general_item', $post_id);
	}
	$post_item_data['post_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['post_layout_settings'], 'default');
	$post_item_data['post_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['post_layout_source'], 'default');
	$post_item_data['post_builder_template'] = strval(intval($post_item_data['post_builder_template']) >= 0 ? intval($post_item_data['post_builder_template']) : 0);
	$post_item_data['show_featured_posts_slider'] = $post_item_data['show_featured_posts_slider'] ? 1 : 0;
	$post_item_data['show_featured_content'] = thegem_check_array_value(array('default', 'enabled', 'disabled'), $post_item_data['show_featured_content'], 'default');
	$post_item_data['video_type'] = thegem_check_array_value(array('youtube', 'vimeo', 'self'), $post_item_data['video_type'], 'youtube');
	$post_item_data['video'] = sanitize_text_field($post_item_data['video']);
	$post_item_data['video_aspect_ratio'] = sanitize_text_field($post_item_data['video_aspect_ratio']);
	$post_item_data['quote_author'] = sanitize_text_field($post_item_data['quote_author']);
	$post_item_data['quote_background'] = sanitize_text_field($post_item_data['quote_background']);
	$post_item_data['quote_author_color'] = sanitize_text_field($post_item_data['quote_author_color']);
	$post_item_data['audio'] = sanitize_text_field($post_item_data['audio']);
	$post_item_data['gallery'] = intval($post_item_data['gallery']);
	$post_item_data['gallery_autoscroll'] = intval($post_item_data['gallery_autoscroll']);
	$post_item_data['highlight'] = $post_item_data['highlight'] ? 1 : 0;
	$post_item_data['highlight_type'] = thegem_check_array_value(array('squared', 'horizontal', 'vertical'), $post_item_data['highlight_type'], 'squared');
	$post_item_data['highlight_style'] = thegem_check_array_value(array('default', 'alternative'), $post_item_data['highlight_style'], 'default');
	$post_item_data['highlight_title_left_background'] = sanitize_text_field($post_item_data['highlight_title_left_background']);
	$post_item_data['highlight_title_left_color'] = sanitize_text_field($post_item_data['highlight_title_left_color']);
	$post_item_data['highlight_title_right_background'] = sanitize_text_field($post_item_data['highlight_title_right_background']);
	$post_item_data['highlight_title_right_color'] = sanitize_text_field($post_item_data['highlight_title_right_color']);

	return $post_item_data;
}

function thegem_get_sanitize_post_data($post_id = 0, $item_data = array()) {
	$output_data = thegem_get_sanitize_admin_post_data($post_id, $item_data);
	if($output_data['post_layout_settings'] == 'default') {
		$output_data['post_layout_source'] = thegem_get_option('post_layout_source');
		$output_data['post_builder_template'] = thegem_get_option('post_builder_template');
	}
	if($output_data['show_featured_content'] == 'default') {
		global $thegem_global_page_settings;
		$output_data['show_featured_content'] = $thegem_global_page_settings['post']['show_featured_content'];
	} elseif($output_data['show_featured_content'] == 'disabled') {
		$output_data['show_featured_content'] = 0;
	} else {
		$output_data['show_featured_content'] = 1;
	}
	return $output_data;
}

function thegem_get_sanitize_admin_post_elements_data($post_id = 0, $item_data = array()) {
	$post_item_data = apply_filters('thegem_post_data_defaults', array(
		'post_elements' => 'default',
		'show_author' => thegem_get_option('show_author'),
		'blog_hide_author' => thegem_get_option('blog_hide_author'),
		'blog_hide_date' => thegem_get_option('blog_hide_date'),
		'blog_hide_date_in_blog_cat' => thegem_get_option('blog_hide_date_in_blog_cat'),
		'blog_hide_categories' => thegem_get_option('blog_hide_categories'),
		'blog_hide_tags' => thegem_get_option('blog_hide_tags'),
		'blog_hide_comments' => thegem_get_option('blog_hide_comments'),
		'blog_hide_likes' => thegem_get_option('blog_hide_likes'),
		'blog_hide_navigation' => thegem_get_option('blog_hide_navigation'),
		'blog_hide_socials' => thegem_get_option('blog_hide_socials'),
		'blog_hide_realted' => thegem_get_option('blog_hide_realted'),
	), $post_id, $item_data);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'post_page_elements', $post_id);
	}

	$post_item_data['post_elements'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['post_elements'], 'default');
	$post_item_data['show_author'] = $post_item_data['show_author'] ? 1 : 0;
	$post_item_data['blog_hide_author'] = $post_item_data['blog_hide_author'] ? 1 : 0;
	$post_item_data['blog_hide_date'] = $post_item_data['blog_hide_date'] ? 1 : 0;
	$post_item_data['blog_hide_date_in_blog_cat'] = $post_item_data['blog_hide_date_in_blog_cat'] ? 1 : 0;
	$post_item_data['blog_hide_categories'] = $post_item_data['blog_hide_categories'] ? 1 : 0;
	$post_item_data['blog_hide_tags'] = $post_item_data['blog_hide_tags'] ? 1 : 0;
	$post_item_data['blog_hide_comments'] = $post_item_data['blog_hide_comments'] ? 1 : 0;
	$post_item_data['blog_hide_likes'] = $post_item_data['blog_hide_likes'] ? 1 : 0;
	$post_item_data['blog_hide_navigation'] = $post_item_data['blog_hide_navigation'] ? 1 : 0;
	$post_item_data['blog_hide_socials'] = $post_item_data['blog_hide_socials'] ? 1 : 0;
	$post_item_data['blog_hide_realted'] = $post_item_data['blog_hide_realted'] ? 1 : 0;

	return $post_item_data;
}

function thegem_get_output_post_elements_data($post_id) {
	$output_data = thegem_get_sanitize_admin_post_elements_data($post_id);
	if($output_data['post_elements'] == 'default') {
		foreach($output_data as $key => $value) {
			$output_data[$key] = thegem_get_option($key);
		}
	}
	return $output_data;
}

function thegem_get_sanitize_product_video_data($item_data) {
	$post_item_data = array(
		'product_video_type' => '',
		'product_video_link' => '',
		'product_video_id' => '',
		'product_video_thumb' => '',
	);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	}

	return $post_item_data;
}

function thegem_get_sanitize_product_page_data($post_id = 0, $item_data = array()) {
	$post_item_data = array(
		'product_layout_settings' => 'default',
		'product_layout_source' => thegem_get_option('product_layout_source'),
		'product_builder_template' => thegem_get_option('product_builder_template'),
		'product_gallery' => thegem_get_option('product_gallery'),
		'product_gallery_type' => thegem_get_option('product_gallery_type'),
		'product_gallery_thumb_on_mobile' => thegem_get_option('product_gallery_thumb_on_mobile'),
		'product_gallery_thumb_scroll_by_hover' => thegem_get_option('product_gallery_thumb_scroll_by_hover'),
		'product_gallery_thumb_count' => thegem_get_option('product_gallery_thumb_count'),
		'product_gallery_thumb_position' => thegem_get_option('product_gallery_thumb_position'),
		'product_gallery_column_position' => thegem_get_option('product_gallery_column_position'),
		'product_gallery_column_width' => thegem_get_option('product_gallery_column_width'),
		'product_gallery_show_image' => thegem_get_option('product_gallery_show_image'),
		'product_gallery_image_ratio' => thegem_get_option('product_gallery_image_ratio'),
		'product_gallery_grid_image_size' => thegem_get_option('product_gallery_grid_image_size'),
		'product_gallery_grid_image_ratio' => thegem_get_option('product_gallery_grid_image_ratio'),
		'product_gallery_zoom' => thegem_get_option('product_gallery_zoom'),
		'product_gallery_lightbox' => thegem_get_option('product_gallery_lightbox'),
		'product_gallery_labels' => thegem_get_option('product_gallery_labels'),
		'product_gallery_label_sale' => thegem_get_option('product_gallery_label_sale'),
		'product_gallery_label_new' => thegem_get_option('product_gallery_label_new'),
		'product_gallery_label_out_stock' => thegem_get_option('product_gallery_label_out_stock'),
		'product_gallery_auto_height' => thegem_get_option('product_gallery_auto_height'),
		'product_gallery_elements_color' => thegem_get_option('product_gallery_elements_color'),
		'product_gallery_grid_columns' => thegem_get_option('product_gallery_grid_columns'),
		'product_gallery_grid_gaps' => thegem_get_option('product_gallery_grid_gaps'),
		'product_gallery_grid_gaps_hide' => thegem_get_option('product_gallery_grid_gaps_hide'),
		'product_gallery_grid_top_margin' => thegem_get_option('product_gallery_grid_top_margin'),
		'product_gallery_video_autoplay' => thegem_get_option('product_gallery_video_autoplay'),
		'product_page_layout' => thegem_get_option('product_page_layout'),
		'product_page_layout_style' => thegem_get_option('product_page_layout_style'),
		'product_page_layout_centered' => thegem_get_option('product_page_layout_centered'),
		'product_page_layout_centered_top_margin' => thegem_get_option('product_page_layout_centered_top_margin'),
		'product_page_layout_centered_boxed' => thegem_get_option('product_page_layout_centered_boxed'),
		'product_page_layout_centered_boxed_color' => thegem_get_option('product_page_layout_centered_boxed_color'),
		'product_page_layout_background' => thegem_get_option('product_page_layout_background'),
		'product_page_layout_preset' => thegem_get_option('product_page_layout_preset'),
		'product_page_layout_fullwidth' => thegem_get_option('product_page_layout_fullwidth'),
		'product_page_layout_sticky' => thegem_get_option('product_page_layout_sticky'),
		'product_page_layout_sticky_offset' => thegem_get_option('product_page_layout_sticky_offset'),
		'product_page_skeleton_loader' => thegem_get_option('product_page_skeleton_loader'),
		'product_page_layout_title_area' => thegem_get_option('product_page_layout_title_area'),
		'product_page_ajax_add_to_cart' => thegem_get_option('product_page_ajax_add_to_cart'),
		'product_page_desc_review_source' => thegem_get_option('product_page_desc_review_source'),
		'product_page_desc_review_layout' => thegem_get_option('product_page_desc_review_layout'),
		'product_page_desc_review_layout_tabs_style' => thegem_get_option('product_page_desc_review_layout_tabs_style'),
		'product_page_desc_review_layout_tabs_alignment' => thegem_get_option('product_page_desc_review_layout_tabs_alignment'),
		'product_page_desc_review_layout_acc_position' => thegem_get_option('product_page_desc_review_layout_acc_position'),
		'product_page_desc_review_layout_one_by_one_description_background' => thegem_get_option('product_page_desc_review_layout_one_by_one_description_background'),
		'product_page_desc_review_layout_one_by_one_additional_info_background' => thegem_get_option('product_page_desc_review_layout_one_by_one_additional_info_background'),
		'product_page_desc_review_layout_one_by_one_reviews_background' => thegem_get_option('product_page_desc_review_layout_one_by_one_reviews_background'),
		'product_page_desc_review_description' => thegem_get_option('product_page_desc_review_description'),
		'product_page_desc_review_description_title' => thegem_get_option('product_page_desc_review_description_title'),
		'product_page_desc_review_additional_info' => thegem_get_option('product_page_desc_review_additional_info'),
		'product_page_desc_review_additional_info_title' => thegem_get_option('product_page_desc_review_additional_info_title'),
		'product_page_desc_review_reviews' => thegem_get_option('product_page_desc_review_reviews'),
		'product_page_desc_review_reviews_title' => thegem_get_option('product_page_desc_review_reviews_title'),
		'product_page_button_add_to_cart_text' => thegem_get_option('product_page_button_add_to_cart_text'),
		'product_page_button_add_to_cart_icon_show' => thegem_get_option('product_page_button_add_to_cart_icon_show'),
		'product_page_button_add_to_cart_icon' => thegem_get_option('product_page_button_add_to_cart_icon'),
		'product_page_button_add_to_cart_icon_pack' => thegem_get_option('product_page_button_add_to_cart_icon_pack'),
		'product_page_button_add_to_cart_icon_position' => thegem_get_option('product_page_button_add_to_cart_icon_position'),
		'product_page_button_add_to_cart_border_width' => thegem_get_option('product_page_button_add_to_cart_border_width'),
		'product_page_button_add_to_cart_border_radius' => thegem_get_option('product_page_button_add_to_cart_border_radius'),
		'product_page_button_add_to_cart_color' => thegem_get_option('product_page_button_add_to_cart_color'),
		'product_page_button_add_to_cart_color_hover' => thegem_get_option('product_page_button_add_to_cart_color_hover'),
		'product_page_button_add_to_cart_background' => thegem_get_option('product_page_button_add_to_cart_background'),
		'product_page_button_add_to_cart_background_hover' => thegem_get_option('product_page_button_add_to_cart_background_hover'),
		'product_page_button_add_to_cart_border_color' => thegem_get_option('product_page_button_add_to_cart_border_color'),
		'product_page_button_add_to_cart_border_color_hover' => thegem_get_option('product_page_button_add_to_cart_border_color_hover'),
		'product_page_button_add_to_wishlist_icon' => thegem_get_option('product_page_button_add_to_wishlist_icon'),
		'product_page_button_add_to_wishlist_icon_pack' => thegem_get_option('product_page_button_add_to_wishlist_icon_pack'),
		'product_page_button_add_to_wishlist_color' => thegem_get_option('product_page_button_add_to_wishlist_color'),
		'product_page_button_add_to_wishlist_color_hover' => thegem_get_option('product_page_button_add_to_wishlist_color_hover'),
		'product_page_button_add_to_wishlist_color_filled' => thegem_get_option('product_page_button_add_to_wishlist_color_filled'),
		'product_page_button_added_to_wishlist_icon' => thegem_get_option('product_page_button_added_to_wishlist_icon'),
		'product_page_button_added_to_wishlist_icon_pack' => thegem_get_option('product_page_button_added_to_wishlist_icon_pack'),
		'product_page_button_clear_attributes_text' => thegem_get_option('product_page_button_clear_attributes_text'),
		'product_page_elements_prev_next' => thegem_get_option('product_page_elements_prev_next'),
		'product_page_elements_preview_on_hover' => thegem_get_option('product_page_elements_preview_on_hover'),
		'product_page_elements_back_to_shop' => thegem_get_option('product_page_elements_back_to_shop'),
		'product_page_elements_back_to_shop_link' => thegem_get_option('product_page_elements_back_to_shop_link'),
		'product_page_elements_back_to_shop_link_custom_url' => thegem_get_option('product_page_elements_back_to_shop_link_custom_url'),
		'product_page_elements_title' => thegem_get_option('product_page_elements_title'),
		'product_page_elements_title_html' => thegem_get_option('product_page_elements_title_html'),
		'product_page_elements_attributes' => thegem_get_option('product_page_elements_attributes'),
		'product_page_elements_attributes_data' => thegem_get_option('product_page_elements_attributes_data'),
		'product_page_elements_reviews' => thegem_get_option('product_page_elements_reviews'),
		'product_page_elements_reviews_text' => thegem_get_option('product_page_elements_reviews_text'),
		'product_page_elements_price' => thegem_get_option('product_page_elements_price'),
		'product_page_elements_price_strikethrough' => thegem_get_option('product_page_elements_price_strikethrough'),
		'product_page_elements_description' => thegem_get_option('product_page_elements_description'),
		'product_page_elements_stock_amount' => thegem_get_option('product_page_elements_stock_amount'),
		'product_page_elements_stock_amount_text' => thegem_get_option('product_page_elements_stock_amount_text'),
		'product_page_elements_size_guide' => thegem_get_option('product_page_elements_size_guide'),
		'product_page_elements_sku' => thegem_get_option('product_page_elements_sku'),
		'product_page_elements_sku_title' => thegem_get_option('product_page_elements_sku_title'),
		'product_page_elements_categories' => thegem_get_option('product_page_elements_categories'),
		'product_page_elements_categories_title' => thegem_get_option('product_page_elements_categories_title'),
		'product_page_elements_tags' => thegem_get_option('product_page_elements_tags'),
		'product_page_elements_tags_title' => thegem_get_option('product_page_elements_tags_title'),
		'product_page_elements_share' => thegem_get_option('product_page_elements_share'),
		'product_page_elements_share_title' => thegem_get_option('product_page_elements_share_title'),
		'product_page_elements_share_facebook' => thegem_get_option('product_page_elements_share_facebook'),
		'product_page_elements_share_twitter' => thegem_get_option('product_page_elements_share_twitter'),
		'product_page_elements_share_pinterest' => thegem_get_option('product_page_elements_share_pinterest'),
		'product_page_elements_share_tumblr' => thegem_get_option('product_page_elements_share_tumblr'),
		'product_page_elements_share_linkedin' => thegem_get_option('product_page_elements_share_linkedin'),
		'product_page_elements_share_reddit' => thegem_get_option('product_page_elements_share_reddit'),
		'product_page_elements_upsell' => thegem_get_option('product_page_elements_upsell'),
		'product_page_elements_upsell_title' => thegem_get_option('product_page_elements_upsell_title'),
		'product_page_elements_upsell_title_alignment' => thegem_get_option('product_page_elements_upsell_title_alignment'),
		'product_page_elements_upsell_items' => thegem_get_option('product_page_elements_upsell_items'),
		'product_page_elements_upsell_columns' => thegem_get_option('product_page_elements_upsell_columns'),
		'product_page_elements_upsell_columns_desktop' => thegem_get_option('product_page_elements_upsell_columns_desktop'),
		'product_page_elements_upsell_columns_tablet' => thegem_get_option('product_page_elements_upsell_columns_tablet'),
		'product_page_elements_upsell_columns_mobile' => thegem_get_option('product_page_elements_upsell_columns_mobile'),
		'product_page_elements_upsell_columns_100' => thegem_get_option('product_page_elements_upsell_columns_100'),
		'product_page_elements_related' => thegem_get_option('product_page_elements_related'),
		'product_page_elements_related_title' => thegem_get_option('product_page_elements_related_title'),
		'product_page_elements_related_title_alignment' => thegem_get_option('product_page_elements_related_title_alignment'),
		'product_page_elements_related_items' => thegem_get_option('product_page_elements_related_items'),
		'product_page_elements_related_columns' => thegem_get_option('product_page_elements_related_columns'),
		'product_page_elements_related_columns_desktop' => thegem_get_option('product_page_elements_related_columns_desktop'),
		'product_page_elements_related_columns_tablet' => thegem_get_option('product_page_elements_related_columns_tablet'),
		'product_page_elements_related_columns_mobile' => thegem_get_option('product_page_elements_related_columns_mobile'),
		'product_page_elements_related_columns_100' => thegem_get_option('product_page_elements_related_columns_100'),
		'product_page_additional_tabs' => 'default',
		'product_page_additional_tabs_data' => !empty(thegem_get_option('product_page_additional_tabs')) ? thegem_get_option('product_page_additional_tabs_data') : '',
	);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'product_page', $post_id);
	}

	$post_item_data['product_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['product_layout_settings'], 'default');

	$post_item_data['product_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['product_layout_source'], 'default');
	$post_item_data['product_builder_template'] = strval(intval($post_item_data['product_builder_template']) >= 0 ? intval($post_item_data['product_builder_template']) : 0);

	$post_item_data['product_gallery'] = thegem_check_array_value(array('enabled', 'disabled', 'legacy', 'native'), $post_item_data['product_gallery'], 'enabled');
	$post_item_data['product_gallery_type'] = thegem_check_array_value(array('horizontal', 'vertical', 'dots', 'none', 'grid'), $post_item_data['product_gallery_type'], 'horizontal');
	$post_item_data['product_gallery_thumb_on_mobile'] = $post_item_data['product_gallery_thumb_on_mobile'] ? 1 : 0;
	$post_item_data['product_gallery_thumb_scroll_by_hover'] = $post_item_data['product_gallery_thumb_scroll_by_hover'] ? 1 : 0;
	$post_item_data['product_gallery_thumb_count'] = thegem_check_array_value(array('4', '5', '6', '7', '8'), $post_item_data['product_gallery_thumb_count'], '4');
	$post_item_data['product_gallery_thumb_position'] = thegem_check_array_value(array('left', 'right'), $post_item_data['product_gallery_thumb_position'], 'left');
	$post_item_data['product_gallery_column_position'] = thegem_check_array_value(array('left', 'right'), $post_item_data['product_gallery_column_position'], 'left');
	$post_item_data['product_gallery_column_width'] = sanitize_text_field($post_item_data['product_gallery_column_width']);
	$post_item_data['product_gallery_show_image'] = thegem_check_array_value(array('click', 'hover'), $post_item_data['product_gallery_show_image'], 'hover');
	$post_item_data['product_gallery_image_ratio'] = sanitize_text_field($post_item_data['product_gallery_image_ratio']);
	$post_item_data['product_gallery_grid_image_size'] = thegem_check_array_value(array('default', 'full'), $post_item_data['product_gallery_grid_image_size'], 'default');
	$post_item_data['product_gallery_grid_image_ratio'] = sanitize_text_field($post_item_data['product_gallery_grid_image_ratio']);
	$post_item_data['product_gallery_zoom'] = $post_item_data['product_gallery_zoom'] ? 1 : 0;
	$post_item_data['product_gallery_lightbox'] = $post_item_data['product_gallery_lightbox'] ? 1 : 0;
	$post_item_data['product_gallery_labels'] = $post_item_data['product_gallery_labels'] ? 1 : 0;
	$post_item_data['product_gallery_label_sale'] = $post_item_data['product_gallery_label_sale'] ? 1 : 0;
	$post_item_data['product_gallery_label_new'] = $post_item_data['product_gallery_label_new'] ? 1 : 0;
	$post_item_data['product_gallery_label_out_stock'] = $post_item_data['product_gallery_label_out_stock'] ? 1 : 0;
	$post_item_data['product_gallery_auto_height'] = $post_item_data['product_gallery_auto_height'] ? 1 : 0;
	$post_item_data['product_gallery_elements_color'] = sanitize_text_field($post_item_data['product_gallery_elements_color']);
	$post_item_data['product_gallery_grid_columns'] = thegem_check_array_value(array('1x', '2x', '3x'), $post_item_data['product_gallery_grid_columns'], '1x');
	$post_item_data['product_gallery_grid_gaps'] = sanitize_text_field($post_item_data['product_gallery_grid_gaps']);
	$post_item_data['product_gallery_grid_gaps_hide'] = $post_item_data['product_gallery_grid_gaps_hide'] ? 1 : 0;
	$post_item_data['product_gallery_grid_top_margin'] = sanitize_text_field($post_item_data['product_gallery_grid_top_margin']);
	$post_item_data['product_gallery_video_autoplay'] = $post_item_data['product_gallery_video_autoplay'] ? 1 : 0;

	$post_item_data['product_page_layout'] = thegem_check_array_value(array('default', 'legacy'), $post_item_data['product_page_layout'], 'default');
	$post_item_data['product_page_layout_style'] = sanitize_text_field($post_item_data['product_page_layout_style']);
	$post_item_data['product_page_layout_centered'] = $post_item_data['product_page_layout_centered'] ? 1 : 0;
	$post_item_data['product_page_layout_centered_top_margin'] = sanitize_text_field($post_item_data['product_page_layout_centered_top_margin']);
	$post_item_data['product_page_layout_centered_boxed'] = $post_item_data['product_page_layout_centered_boxed'] ? 1 : 0;
	$post_item_data['product_page_layout_centered_boxed_color'] = sanitize_text_field($post_item_data['product_page_layout_centered_boxed_color']);
	$post_item_data['product_page_layout_background'] = sanitize_text_field($post_item_data['product_page_layout_background']);
	$post_item_data['product_page_layout_preset'] = thegem_check_array_value(array('col-40-60', 'col-50-50', 'col-60-40'), $post_item_data['product_page_layout_preset'], 'col-50-50');
	$post_item_data['product_page_layout_fullwidth'] = $post_item_data['product_page_layout_fullwidth'] ? 1 : 0;
	$post_item_data['product_page_layout_sticky'] = $post_item_data['product_page_layout_sticky'] ? 1 : 0;
	$post_item_data['product_page_layout_sticky_offset'] = sanitize_text_field($post_item_data['product_page_layout_sticky_offset']);
	$post_item_data['product_page_skeleton_loader'] = $post_item_data['product_page_skeleton_loader'] ? 1 : 0;
	$post_item_data['product_page_layout_title_area'] = thegem_check_array_value(array('default', 'disabled'), $post_item_data['product_page_layout_title_area'], 'disabled');
	$post_item_data['product_page_ajax_add_to_cart'] = $post_item_data['product_page_ajax_add_to_cart'] ? 1 : 0;
	$post_item_data['product_page_desc_review_source'] = thegem_check_array_value(array('extra_description', 'page_builder'), $post_item_data['product_page_desc_review_source'], 'extra_description');
	$post_item_data['product_page_desc_review_layout'] = thegem_check_array_value(array('tabs', 'accordion', 'one_by_one'), $post_item_data['product_page_desc_review_layout'], 'tabs');
	$post_item_data['product_page_desc_review_layout_tabs_style'] = thegem_check_array_value(array('horizontal', 'vertical', 'legacy'), $post_item_data['product_page_desc_review_layout_tabs_style'], 'horizontal');
	$post_item_data['product_page_desc_review_layout_tabs_alignment'] = thegem_check_array_value(array('left', 'center', 'right'), $post_item_data['product_page_desc_review_layout_tabs_alignment'], 'left');
	$post_item_data['product_page_desc_review_layout_acc_position'] = thegem_check_array_value(array('next_to_gallery', 'below_gallery'), $post_item_data['product_page_desc_review_layout_acc_position'], 'below_gallery');
	$post_item_data['product_page_desc_review_layout_one_by_one_description_background'] = sanitize_text_field($post_item_data['product_page_desc_review_layout_one_by_one_description_background']);
	$post_item_data['product_page_desc_review_layout_one_by_one_additional_info_background'] = sanitize_text_field($post_item_data['product_page_desc_review_layout_one_by_one_additional_info_background']);
	$post_item_data['product_page_desc_review_layout_one_by_one_reviews_background'] = sanitize_text_field($post_item_data['product_page_desc_review_layout_one_by_one_reviews_background']);
	$post_item_data['product_page_desc_review_description'] = $post_item_data['product_page_desc_review_description'] ? 1 : 0;
	$post_item_data['product_page_desc_review_description_title'] = sanitize_text_field($post_item_data['product_page_desc_review_description_title']);
	$post_item_data['product_page_desc_review_additional_info'] = $post_item_data['product_page_desc_review_additional_info'] ? 1 : 0;
	$post_item_data['product_page_desc_review_additional_info_title'] = sanitize_text_field($post_item_data['product_page_desc_review_additional_info_title']);
	$post_item_data['product_page_desc_review_reviews'] = $post_item_data['product_page_desc_review_reviews'] ? 1 : 0;
	$post_item_data['product_page_desc_review_reviews_title'] = sanitize_text_field($post_item_data['product_page_desc_review_reviews_title']);
	$post_item_data['product_page_button_add_to_cart_text'] = sanitize_text_field($post_item_data['product_page_button_add_to_cart_text']);
	$post_item_data['product_page_button_add_to_cart_icon_show'] = $post_item_data['product_page_button_add_to_cart_icon_show'] ? 1 : 0;
	$post_item_data['product_page_button_add_to_cart_icon'] = sanitize_text_field($post_item_data['product_page_button_add_to_cart_icon']);
	$post_item_data['product_page_button_add_to_cart_icon_pack'] = sanitize_text_field($post_item_data['product_page_button_add_to_cart_icon_pack']);
	$post_item_data['product_page_button_add_to_cart_icon_position'] = thegem_check_array_value(array('left', 'center', 'right'), $post_item_data['product_page_button_add_to_cart_icon_position'], 'left');
	$post_item_data['product_page_button_add_to_wishlist_icon'] = sanitize_text_field($post_item_data['product_page_button_add_to_wishlist_icon']);
	$post_item_data['product_page_button_add_to_wishlist_icon_pack'] = sanitize_text_field($post_item_data['product_page_button_add_to_wishlist_icon_pack']);
	$post_item_data['product_page_button_added_to_wishlist_icon'] = sanitize_text_field($post_item_data['product_page_button_added_to_wishlist_icon']);
	$post_item_data['product_page_button_added_to_wishlist_icon_pack'] = sanitize_text_field($post_item_data['product_page_button_added_to_wishlist_icon_pack']);
	$post_item_data['product_page_button_clear_attributes_text'] = sanitize_text_field($post_item_data['product_page_button_clear_attributes_text']);
	$post_item_data['product_page_elements_prev_next'] = $post_item_data['product_page_elements_prev_next'] ? 1 : 0;
	$post_item_data['product_page_elements_preview_on_hover'] = $post_item_data['product_page_elements_preview_on_hover'] ? 1 : 0;
	$post_item_data['product_page_elements_back_to_shop'] = $post_item_data['product_page_elements_back_to_shop'] ? 1 : 0;
	$post_item_data['product_page_elements_back_to_shop_link'] = thegem_check_array_value(array('main_shop', 'category', 'custom_url'), $post_item_data['product_page_elements_back_to_shop_link'], 'main_shop');
	$post_item_data['product_page_elements_back_to_shop_link_custom_url'] = sanitize_text_field($post_item_data['product_page_elements_back_to_shop_link_custom_url']);
	$post_item_data['product_page_elements_title'] = $post_item_data['product_page_elements_title'] ? 1 : 0;
	$post_item_data['product_page_elements_title_html'] = thegem_check_array_value(array('', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'), $post_item_data['product_page_elements_title_html'], '');
	$post_item_data['product_page_elements_attributes'] = $post_item_data['product_page_elements_attributes'] ? 1 : 0;
	$post_item_data['product_page_elements_attributes_data'] = sanitize_text_field($post_item_data['product_page_elements_attributes_data']);
	$post_item_data['product_page_elements_reviews'] = $post_item_data['product_page_elements_reviews'] ? 1 : 0;
	$post_item_data['product_page_elements_reviews_text'] = sanitize_text_field($post_item_data['product_page_elements_reviews_text']);
	$post_item_data['product_page_elements_price'] = $post_item_data['product_page_elements_price'] ? 1 : 0;
	$post_item_data['product_page_elements_price_strikethrough'] = $post_item_data['product_page_elements_price_strikethrough'] ? 1 : 0;
	$post_item_data['product_page_elements_description'] = $post_item_data['product_page_elements_description'] ? 1 : 0;
	$post_item_data['product_page_elements_stock_amount'] = $post_item_data['product_page_elements_stock_amount'] ? 1 : 0;
	$post_item_data['product_page_elements_stock_amount_text'] = sanitize_text_field($post_item_data['product_page_elements_stock_amount_text']);
	$post_item_data['product_page_elements_size_guide'] = $post_item_data['product_page_elements_size_guide'] ? 1 : 0;
	$post_item_data['product_page_elements_sku'] = $post_item_data['product_page_elements_sku'] ? 1 : 0;
	$post_item_data['product_page_elements_sku_title'] = sanitize_text_field($post_item_data['product_page_elements_sku_title']);
	$post_item_data['product_page_elements_categories'] = $post_item_data['product_page_elements_categories'] ? 1 : 0;
	$post_item_data['product_page_elements_categories_title'] = sanitize_text_field($post_item_data['product_page_elements_categories_title']);
	$post_item_data['product_page_elements_tags'] = $post_item_data['product_page_elements_tags'] ? 1 : 0;
	$post_item_data['product_page_elements_tags_title'] = sanitize_text_field($post_item_data['product_page_elements_tags_title']);
	$post_item_data['product_page_elements_share'] = $post_item_data['product_page_elements_share'] ? 1 : 0;
	$post_item_data['product_page_elements_share_title'] = sanitize_text_field($post_item_data['product_page_elements_share_title']);
	$post_item_data['product_page_elements_share_facebook'] = $post_item_data['product_page_elements_share_facebook'] ? 1 : 0;
	$post_item_data['product_page_elements_share_twitter'] = $post_item_data['product_page_elements_share_twitter'] ? 1 : 0;
	$post_item_data['product_page_elements_share_pinterest'] = $post_item_data['product_page_elements_share_pinterest'] ? 1 : 0;
	$post_item_data['product_page_elements_share_tumblr'] = $post_item_data['product_page_elements_share_tumblr'] ? 1 : 0;
	$post_item_data['product_page_elements_share_linkedin'] = $post_item_data['product_page_elements_share_linkedin'] ? 1 : 0;
	$post_item_data['product_page_elements_share_reddit'] = $post_item_data['product_page_elements_share_reddit'] ? 1 : 0;
	$post_item_data['product_page_elements_upsell'] = $post_item_data['product_page_elements_upsell'] ? 1 : 0;
	$post_item_data['product_page_elements_upsell_title'] = sanitize_text_field($post_item_data['product_page_elements_upsell_title']);
	$post_item_data['product_page_elements_upsell_title_alignment'] = thegem_check_array_value(array('left', 'center', 'right'), $post_item_data['product_page_elements_upsell_title_alignment'], 'left');
	$post_item_data['product_page_elements_upsell_items'] = sanitize_text_field($post_item_data['product_page_elements_upsell_items']);
	$post_item_data['product_page_elements_upsell_columns_desktop'] = thegem_check_array_value(array('2x', '3x', '4x', '5x', '6x', '100%'), $post_item_data['product_page_elements_upsell_columns_desktop'], '4x');
	$post_item_data['product_page_elements_upsell_columns_tablet'] = thegem_check_array_value(array('2x', '3x', '4x'), $post_item_data['product_page_elements_upsell_columns_tablet'], '3x');
	$post_item_data['product_page_elements_upsell_columns_mobile'] = thegem_check_array_value(array('1x', '2x'), $post_item_data['product_page_elements_upsell_columns_mobile'], '2x');
	$post_item_data['product_page_elements_upsell_columns_100'] = thegem_check_array_value(array('4', '5', '6'), $post_item_data['product_page_elements_upsell_columns_100'], '5');
	$post_item_data['product_page_elements_related'] = $post_item_data['product_page_elements_related'] ? 1 : 0;
	$post_item_data['product_page_elements_related_title'] = sanitize_text_field($post_item_data['product_page_elements_related_title']);
	$post_item_data['product_page_elements_related_title_alignment'] = thegem_check_array_value(array('left', 'center', 'right'), $post_item_data['product_page_elements_related_title_alignment'], 'left');
	$post_item_data['product_page_elements_related_items'] = sanitize_text_field($post_item_data['product_page_elements_related_items']);
	$post_item_data['product_page_elements_related_columns_desktop'] = thegem_check_array_value(array('2x', '3x', '4x', '5x', '6x', '100%'), $post_item_data['product_page_elements_related_columns_desktop'], '4x');
	$post_item_data['product_page_elements_related_columns_tablet'] = thegem_check_array_value(array('2x', '3x', '4x'), $post_item_data['product_page_elements_related_columns_tablet'], '3x');
	$post_item_data['product_page_elements_related_columns_mobile'] = thegem_check_array_value(array('1x', '2x'), $post_item_data['product_page_elements_related_columns_mobile'], '2x');
	$post_item_data['product_page_elements_related_columns_100'] = thegem_check_array_value(array('4', '5', '6'), $post_item_data['product_page_elements_related_columns_100'], '5');

	return $post_item_data;
}

function thegem_get_output_product_page_data($post_id) {
	$output_data = thegem_get_sanitize_product_page_data($post_id);
	if($output_data['product_layout_settings'] == 'default') {
		foreach($output_data as $key => $value) {
			$output_data[$key] = thegem_get_option($key);
		}
	}
	return $output_data;
}

//Deprecation product gallery function
function thegem_get_output_product_gallery_data($post_id) {
	$output_data = thegem_get_sanitize_product_page_data($post_id);
	if($output_data['product_layout_settings'] == 'default') {
		foreach($output_data as $key => $value) {
			$output_data[$key] = thegem_get_option($key);
		}
	}
	return $output_data;
}

function thegem_get_sanitize_product_archive_data($term_id = 0, $item_data = array()) {
	$post_item_data = array(
		'product_archive_layout_source' => 'default',
		'product_archive_builder_template' => thegem_get_option('product_builder_template'),
		'products_layout_settings' => 'default',
		'products_layout_source' => 'default',
		'products_builder_template' => 0,
	);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($term_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'product_archive_page', $term_id, 'term');
	}

	$post_item_data['product_archive_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['product_archive_layout_source'], 'default');
	$post_item_data['product_archive_builder_template'] = strval(intval($post_item_data['product_archive_builder_template']) >= 0 ? intval($post_item_data['product_archive_builder_template']) : 0);

	$post_item_data['products_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['products_layout_settings'], 'default');
	$post_item_data['products_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['products_layout_source'], 'default');
	$post_item_data['products_builder_template'] = strval(intval($post_item_data['products_builder_template']) >= 0 ? intval($post_item_data['products_builder_template']) : 0);

	return $post_item_data;
}

function thegem_get_output_product_archive_data($term_id) {
	$output_data = thegem_get_sanitize_product_archive_data($term_id);
	if($output_data['product_archive_layout_source'] == 'default') {
		$output_data['product_archive_layout_source'] = thegem_get_option('product_archive_layout_source');
		$output_data['product_archive_builder_template'] = thegem_get_option('product_archive_builder_template');
	}
	return $output_data;
}

function thegem_get_sanitize_blog_archive_data($term_id = 0, $item_data = array()) {
	$post_item_data = array(
		'blog_archive_layout_settings' => 'default',
		'blog_archive_layout_source' => 'default',
		'blog_archive_builder_template' => thegem_get_option('blog_builder_template'),
	);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($term_id != 0) {
		$post_item_data = thegem_get_post_data($post_item_data, 'blog_archive_page', $term_id, 'term');
	}

	$post_item_data['blog_archive_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['blog_archive_layout_settings'], 'default');
	$post_item_data['blog_archive_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['blog_archive_layout_source'], 'default');
	$post_item_data['blog_archive_builder_template'] = strval(intval($post_item_data['blog_archive_builder_template']) >= 0 ? intval($post_item_data['blog_archive_builder_template']) : 0);

	return $post_item_data;
}

function thegem_get_output_blog_archive_data($term_id) {
	$output_data = thegem_get_sanitize_blog_archive_data($term_id);
	if($output_data['blog_archive_layout_settings'] == 'default') {
		$output_data['blog_archive_layout_source'] = thegem_get_option('blog_archive_layout_source');
		$output_data['blog_archive_builder_template'] = thegem_get_option('blog_archive_builder_template');
	}
	return $output_data;
}

function thegem_get_sanitize_cpt_archive_data($term_id = 0, $item_data = array()) {
	$term_data = array(
		'archive_layout_settings' => 'default',
		'archive_layout_source' => 'default',
		'archive_builder_template' => 0,
		'posts_layout_settings' => 'default',
		'posts_layout_source' => 'default',
		'posts_builder_template' => 0,
	);

	if(is_array($item_data) && !empty($item_data)) {
		$term_data = array_merge($term_data, $item_data);
	} elseif($term_id != 0) {
		$term_page_data = thegem_get_post_data(array(), 'page', $term_id, 'term');
		if(isset($term_page_data['custom_archive_item_data']) && is_array($term_page_data['custom_archive_item_data'])) {
			if (isset($term_page_data['custom_archive_item_data']['layout_source'])) {
				$term_data['archive_layout_source'] = $term_page_data['custom_archive_item_data']['layout_source'];
			}
			if (isset($term_page_data['custom_archive_item_data']['builder_template'])) {
				$term_data['archive_builder_template'] = $term_page_data['custom_archive_item_data']['builder_template'];
			}
			if (isset($term_page_data['custom_archive_item_data']['posts_layout_settings'])) {
				$term_data['posts_layout_settings'] = $term_page_data['custom_archive_item_data']['posts_layout_settings'];
			}
			if (isset($term_page_data['custom_archive_item_data']['posts_layout_source'])) {
				$term_data['posts_layout_source'] = $term_page_data['custom_archive_item_data']['posts_layout_source'];
			}
			if (isset($term_page_data['custom_archive_item_data']['posts_builder_template'])) {
				$term_data['posts_builder_template'] = $term_page_data['custom_archive_item_data']['posts_builder_template'];
			}
		}
	}

	$term_data['archive_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $term_data['archive_layout_settings'], 'default');
	$term_data['archive_layout_source'] = thegem_check_array_value(array('default', 'builder'), $term_data['archive_layout_source'], 'default');
	$term_data['archive_builder_template'] = strval(intval($term_data['archive_builder_template']) >= 0 ? intval($term_data['archive_builder_template']) : 0);

	$term_data['posts_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $term_data['posts_layout_settings'], 'default');
	$term_data['posts_layout_source'] = thegem_check_array_value(array('default', 'builder'), $term_data['posts_layout_source'], 'default');
	$term_data['posts_builder_template'] = strval(intval($term_data['posts_builder_template']) >= 0 ? intval($term_data['posts_builder_template']) : 0);

	return $term_data;
}

function thegem_get_output_cpt_archive_data($term_id, $post_type_name) {
	$output_data = thegem_get_sanitize_cpt_archive_data($term_id);

	if ($output_data['archive_layout_settings'] == 'default') {
		$to_data = array();

		if ($term_id != 0) {
			$term = get_term($term_id);

			if (!is_wp_error($term)) {
				$tax = get_taxonomy($term->taxonomy);
				if (!empty($tax->object_type) && !empty($tax->object_type[0])) {
					$post_type_name = $tax->object_type[0];
				}
			}
		}

		if (!empty($post_type_name) && in_array($post_type_name . '_archive', thegem_get_list_po_custom_taxonomies())) {
			$to_data = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings($post_type_name . '_archive'), 'cpt_archive');
		}

		if (!empty($to_data)) {
			$output_data['archive_layout_source'] = $to_data['archive_layout_source'];
			$output_data['archive_builder_template'] = $to_data['archive_builder_template'];
			$output_data['archive_layout_type'] = $to_data['archive_layout_type'];
		}
	}
	return $output_data;
}

function thegem_get_sanitize_admin_page_item_data($post_id = 0, $item_data = array()) {
	$post_item_data = apply_filters('thegem_page_item_data_defaults', array(
		'page_layout_settings' => 'default',
		'page_layout_source' => 'default',
		'page_builder_template' => 0,
	), $post_id, $item_data);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_page_data = thegem_get_post_data(array(), 'page', $post_id);
		if(isset($post_page_data['page_item_data']) && is_array($post_page_data['page_item_data'])) {
			$post_item_data = array_merge($post_item_data, $post_page_data['page_item_data']);
		}
	}

	$post_item_data['page_layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['page_layout_settings'], 'default');
	$post_item_data['page_layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['page_layout_source'], 'default');
	$post_item_data['page_builder_template'] = strval(intval($post_item_data['page_builder_template']) >= 0 ? intval($post_item_data['page_builder_template']) : 0);

	return $post_item_data;
}

function thegem_get_sanitize_page_item_data($post_id = 0, $item_data = array()) {
	$output_data = thegem_get_sanitize_admin_page_item_data($post_id, $item_data);
	if($output_data['page_layout_settings'] == 'default') {
		$output_data['page_layout_source'] = thegem_get_option('page_layout_source');
		$output_data['page_builder_template'] = thegem_get_option('page_builder_template');
	}

	return $output_data;
}

function thegem_get_sanitize_admin_cpt_item_data($post_id = 0, $item_data = array()) {
	$post_item_data = apply_filters('thegem_cpt_item_data_defaults', array(
		'layout_settings' => 'default',
		'layout_source' => 'default',
		'builder_template' => 0,
	), $post_id, $item_data);

	if(is_array($item_data) && !empty($item_data)) {
		$post_item_data = array_merge($post_item_data, $item_data);
	} elseif($post_id != 0) {
		$post_page_data = thegem_get_post_data(array(), 'page', $post_id);
		if(isset($post_page_data['custom_post_item_data']) && is_array($post_page_data['custom_post_item_data'])) {
			$post_item_data = array_merge($post_item_data, $post_page_data['custom_post_item_data']);
		}
	}

	$post_item_data['layout_settings'] = thegem_check_array_value(array('default', 'custom'), $post_item_data['layout_settings'], 'default');
	$post_item_data['layout_source'] = thegem_check_array_value(array('default', 'builder'), $post_item_data['layout_source'], 'default');
	$post_item_data['builder_template'] = strval(intval($post_item_data['builder_template']) >= 0 ? intval($post_item_data['builder_template']) : 0);

	return $post_item_data;
}

function thegem_get_sanitize_cpt_item_data($post_id = 0, $item_data = array()) {
	$output_data = thegem_get_sanitize_admin_cpt_item_data($post_id, $item_data);

	$cpt = get_post_type($post_id);
	$to_data = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings($cpt), 'default');
	if(is_post_type_viewable($cpt) && $output_data['layout_settings'] == 'default' && !empty($to_data) && $cpt !== 'attachment') {
		$output_data['layout_source'] = $to_data['post_layout_source'];
		$output_data['builder_template'] = $to_data['post_builder_template'];
	}

	return $output_data;
}

function thegem_get_popup_data() {
	$popup_data = get_option('thegem_popups');

	$popup_data_ready = false;
	$thegem_page_id = is_singular() ? get_the_ID() : 0;
	$thegem_shop_page = 0;
	if(is_404() && get_post(thegem_get_option('404_page'))) {
		$thegem_page_id = thegem_get_option('404_page');
	}
	if(is_post_type_archive('product') && function_exists('wc_get_page_id')) {
		$thegem_page_id = wc_get_page_id('shop');
		$thegem_shop_page = 1;
	}
	$thegem_popups_params = get_post_meta($thegem_page_id, 'thegem_popups_data', true);
	if(is_singular()) {
		if(empty($thegem_popups_params) || empty($thegem_popups_params['popups_layout_source']) || $thegem_popups_params['popups_layout_source'] === 'default') {
			if(get_post_type() === 'page') {
				$thegem_popups_params = get_option('thegem_popups_default');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_default');
			} elseif(get_post_type() === 'post') {
				$thegem_popups_params = get_option('thegem_popups_post');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_post');
			} elseif(get_post_type() === 'thegem_pf_item') {
				$thegem_popups_params = get_option('thegem_popups_portfolio');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_portfolio');
			} elseif(get_post_type() === 'product') {
				$thegem_popups_params = get_option('thegem_popups_product');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_product');
			}
		} else {
			$popup_data = array();
			$popup_data_ready = true;
			if($thegem_popups_params['popups_layout_source'] === 'custom' && !empty($thegem_popups_params['thegemPopups'])) {
				$popup_data = $thegem_popups_params['thegemPopups'];
			}
		}
	}

	if((is_archive() || is_home()) && !$thegem_shop_page && !is_post_type_archive('tribe_events')) {
		if(is_tax('product_cat') || is_tax('product_tag') || is_tax('product_brand')) {
			$thegem_popups_params = get_option('thegem_popups_product_categories');
			$thegem_popups_layout_source = thegem_get_option('popups_layout_source_product_categories');
		} else {
			$thegem_popups_params = get_option('thegem_popups_blog');
			$thegem_popups_layout_source = thegem_get_option('popups_layout_source_blog');
		}
	}

	if(is_tax() || is_category() || is_tag()) {
		$thegem_term_id = get_queried_object()->term_id;
		$thegem_popups_params = get_term_meta($thegem_term_id, 'thegem_popups_data', true);
		if(empty($thegem_popups_params) || empty($thegem_popups_params['popups_layout_source']) || $thegem_popups_params['popups_layout_source'] === 'default') {
			if(is_tax('product_cat') || is_tax('product_tag') || is_tax('product_brand')) {
				$thegem_popups_params = get_option('thegem_popups_product_categories');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_product_categories');
			} else {
				$thegem_popups_params = get_option('thegem_popups_blog');
				$thegem_popups_layout_source = thegem_get_option('popups_layout_source_blog');
			}
		} else {
			$popup_data = array();
			$popup_data_ready = true;
			if($thegem_popups_params['popups_layout_source'] === 'custom' && !empty($thegem_popups_params['thegemPopups'])) {
				$popup_data = $thegem_popups_params['thegemPopups'];
			}
		}
	}

	if (is_search()) {
		$thegem_popups_params = get_option('thegem_popups_search');
		$thegem_popups_layout_source = thegem_get_option('popups_layout_source_search');
	}

	if(!$popup_data_ready) {
		if(!empty($thegem_popups_params) && !empty($thegem_popups_layout_source) && $thegem_popups_layout_source !== 'default') {
			$popup_data = array();
			if($thegem_popups_layout_source === 'custom') {
				$popup_data = $thegem_popups_params;
			}
		}
	}

	if(is_array($popup_data) && count($popup_data)) {
		foreach($popup_data as $key => $popup) {
			if(empty($popup['active'])) {
				unset($popup_data[$key]);
			} elseif(isset($popup['triggers'])) {
				$popup_data[$key]['triggers'] = json_decode($popup['triggers'], 1);
			}
		}
	}

	return apply_filters('thegem_popup_data', $popup_data);
}
