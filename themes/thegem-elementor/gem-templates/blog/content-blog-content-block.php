<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
	<div class="container">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">
			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<?php
				if ( have_posts() ) {

					$is_blog = !isset($post_type_name) || $post_type_name == 'post';

					if (!$is_blog) {
						if(!empty($thegem_term_id)) {
							$content_settings = thegem_get_output_page_settings(0, thegem_theme_options_get_page_settings($post_type_name.'_archive'), 'cpt_archive');
						} else {
							$content_settings = $thegem_output_settings;
						}
					}

					if ((is_archive() || is_home()) && ((!$is_blog && $content_settings['archive_layout_type'] == 'grid') || ($is_blog && thegem_get_option('blog_layout_type') == 'grid'))) {

						if ($is_blog) {
							$settings = array(
								'query_type' => 'archive',
								'archive_post_type' => 'any',
								'layout' => thegem_get_option('blog_layout_type_grid'),
								'categories' => array('0'),
								'orderby' => thegem_get_option('blog_layout_sorting_default_orderby') != 'default' ? thegem_get_option('blog_layout_sorting_default_orderby') : '',
								'order' => thegem_get_option('blog_layout_sorting_default_order') != 'default' ? thegem_get_option('blog_layout_sorting_default_order') : '',
								'columns_desktop' => thegem_get_option('blog_layout_columns_desktop'),
								'columns_tablet' => thegem_get_option('blog_layout_columns_tablet'),
								'columns_mobile' => thegem_get_option('blog_layout_columns_mobile'),
								'columns_100' => thegem_get_option('blog_layout_columns_100'),
								'caption_position' => thegem_get_option('blog_layout_caption_position') == 'bellow' ? 'page' : 'hover',
								'thegem_elementor_preset' => thegem_get_option('blog_layout_skin') == 'classic' ? 'default' : 'new',
								'image_gaps' => ['size' => thegem_get_option('blog_layout_gaps_desktop'), 'unit' => 'px'],
								'image_gaps_tablet' => ['size' => thegem_get_option('blog_layout_gaps_tablet')],
								'image_gaps_mobile' => ['size' => thegem_get_option('blog_layout_gaps_mobile')],
								'image_size' => thegem_get_option('blog_layout_image_size'),
								'image_ratio_full' => thegem_get_option('blog_layout_image_ratio_full'),
								'image_ratio_default' => thegem_get_option('blog_layout_image_ratio_default'),
								'icon_hover_show' => thegem_get_option('blog_layout_icon_on_hover') == 1 ? 'yes' : '',
								'blog_show_sorting' => thegem_get_option('blog_layout_sorting') == 1 ? 'yes' : '',
								'image_hover_effect' => str_replace("_", "-", thegem_get_option('blog_layout_hover_effect')),
								'blog_show_featured_image' => thegem_get_option('blog_layout_caption_featured_image') == 1 ? 'yes' : '',
								'blog_show_title' => thegem_get_option('blog_layout_caption_title') == 1 ? 'yes' : '',
								'blog_title_preset' => 'title-'.thegem_get_option('blog_layout_caption_title_preset'),
								'truncate_titles' => thegem_get_option('blog_layout_caption_truncate_titles'),
								'blog_show_description' => thegem_get_option('blog_layout_caption_description') == 1 ? 'yes' : '',
								'truncate_description' => thegem_get_option('blog_layout_caption_truncate_description'),
								'blog_show_date' => thegem_get_option('blog_layout_caption_date') == 1 ? 'yes' : '',
								'blog_show_categories' => thegem_get_option('blog_layout_caption_categories') == 1 ? 'yes' : '',
								'additional_meta_taxonomies' => 'category',
								'additional_meta_click_behavior' => 'archive_link',
								'blog_show_author' => thegem_get_option('blog_layout_caption_author') == 1 ? 'yes' : '',
								'blog_show_author_avatar' => thegem_get_option('blog_layout_caption_author_avatar') == 1 ? 'yes' : '',
								'by_text' => __('By', 'thegem'),
								'blog_show_comments' => thegem_get_option('blog_layout_caption_comments') == 1 ? 'yes' : '',
								'blog_show_likes' => thegem_get_option('blog_layout_caption_likes') == 1 ? 'yes' : '',
								'social_sharing' => thegem_get_option('blog_layout_caption_socials') == 1 ? 'yes' : '',
								'blog_show_readmore_button' => thegem_get_option('blog_layout_caption_read_more') == 1 ? 'yes' : '',
								'blog_readmore_button_text' => thegem_get_option('blog_layout_caption_read_more_text'),
								'caption_container_alignment' => thegem_get_option('blog_layout_caption_content_alignment_desktop'),
								'caption_container_alignment_tablet' => thegem_get_option('blog_layout_caption_content_alignment_tablet'),
								'caption_container_alignment_mobile' => thegem_get_option('blog_layout_caption_content_alignment_mobile'),
								'caption_container_preset' => thegem_get_option('blog_layout_caption_container_preset'),
								'show_bottom_border' => thegem_get_option('blog_layout_caption_bottom_border') == 1 ? 'yes' : '',
								'show_pagination' => thegem_get_option('blog_layout_pagination') == 1 ? 'yes' : '',
								'items_per_page' => thegem_get_option('blog_layout_pagination_items_per_page'),
								'pagination_type' => thegem_get_option('blog_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('blog_layout_pagination_type'),
								'reduce_html_size' => thegem_get_option('blog_layout_pagination_reduce_html') == 1 ? 'yes' : '',
								'items_on_load' => thegem_get_option('blog_layout_pagination_reduce_html_items_count'),
								'more_button_text' => thegem_get_option('blog_layout_load_more_text'),
								'more_icon_pack' => thegem_get_option('blog_layout_load_more_icon_pack'),
								'more_icon_' . thegem_get_option('blog_layout_load_more_icon_pack') => thegem_get_option('blog_layout_load_more_icon'),
								'more_stretch_full_width' => thegem_get_option('blog_layout_load_more_stretch') == 1 ? 'yes' : '',
								'more_show_separator' => (thegem_get_option('blog_layout_load_more_stretch') != 1 && thegem_get_option('blog_layout_load_more_separator') == 1) ? 'yes' : '',
								'load_more_spacing' => thegem_get_option('blog_layout_load_more_spacing_desktop'),
								'load_more_spacing_tablet' => thegem_get_option('blog_layout_load_more_spacing_tablet'),
								'load_more_spacing_mobile' => thegem_get_option('blog_layout_load_more_spacing_mobile'),
								'pagination_more_button_type' => thegem_get_option('blog_layout_load_more_btn_type'),
								'pagination_more_button_size' => thegem_get_option('blog_layout_load_more_btn_size'),
								'loading_animation' => thegem_get_option('blog_layout_loading_animation') == 1 ? 'yes' : '',
								'animation_effect' => thegem_get_option('blog_layout_animation_effect'),
								'ignore_highlights' => thegem_get_option('blog_layout_ignore_highlights') == 1 ? 'yes' : '',
								'skeleton_loader' => thegem_get_option('blog_layout_skeleton_loader') == 1 ? 'yes' : '',
								'ajax_preloader_type' => thegem_get_option('blog_layout_ajax_preloader_type'),
								'fullwidth_section_images' => '',
								'title_weight' => '',
								'skin_source' => thegem_get_option('blog_skin_source'),
								'loop_builder' => thegem_get_option('blog_archive_item_builder_template'),
								'equal_height' => !thegem_get_option('blog_archive_items_equal_height_disabled'),
							);

						} else {

							$settings = array(
								'query_type' => 'archive',
								'archive_post_type' => 'any',
								'layout' => $content_settings['archive_layout_type_grid'],
								'categories' => array('0'),
								'orderby' => $content_settings['archive_layout_sorting_default_orderby'] != 'default' ? $content_settings['archive_layout_sorting_default_orderby'] : '',
								'order' => $content_settings['archive_layout_sorting_default_order'] != 'default' ? $content_settings['archive_layout_sorting_default_order'] : '',
								'columns_desktop' => $content_settings['archive_layout_columns_desktop'],
								'columns_tablet' => $content_settings['archive_layout_columns_tablet'],
								'columns_mobile' => $content_settings['archive_layout_columns_mobile'],
								'columns_100' => $content_settings['archive_layout_columns_100'],
								'caption_position' => $content_settings['archive_layout_caption_position'] == 'bellow' ? 'page' : 'hover',
								'thegem_elementor_preset' => $content_settings['archive_layout_skin'] == 'classic' ? 'default' : 'new',
								'image_gaps' => ['size' => $content_settings['archive_layout_gaps_desktop'], 'unit' => 'px'],
								'image_gaps_tablet' => ['size' => $content_settings['archive_layout_gaps_tablet']],
								'image_gaps_mobile' => ['size' => $content_settings['archive_layout_gaps_mobile']],
								'image_size' => $content_settings['archive_layout_image_size'],
								'image_ratio_full' => $content_settings['archive_layout_image_ratio_full'],
								'image_ratio_default' => $content_settings['archive_layout_image_ratio_default'],
								'icon_hover_show' => $content_settings['archive_layout_icon_on_hover'] == 1 ? 'yes' : '',
								'blog_show_sorting' => $content_settings['archive_layout_sorting'] == 1 ? 'yes' : '',
								'image_hover_effect' => str_replace("_", "-", $content_settings['archive_layout_hover_effect']),
								'blog_show_featured_image' => $content_settings['archive_layout_caption_featured_image'] == 1 ? 'yes' : '',
								'blog_show_title' => $content_settings['archive_layout_caption_title'] == 1 ? 'yes' : '',
								'blog_title_preset' => 'title-'.$content_settings['archive_layout_caption_title_preset'],
								'truncate_titles' => $content_settings['archive_layout_caption_truncate_titles'],
								'blog_show_description' => $content_settings['archive_layout_caption_description'] == 1 ? 'yes' : '',
								'truncate_description' => $content_settings['archive_layout_caption_truncate_description'],
								'blog_show_date' => $content_settings['archive_layout_caption_date'] == 1 ? 'yes' : '',
								'blog_show_categories' => $content_settings['archive_layout_caption_categories'] == 1 ? 'yes' : '',
								'additional_meta_taxonomies' => 'category',
								'additional_meta_click_behavior' => 'archive_link',
								'blog_show_author' => $content_settings['archive_layout_caption_author'] == 1 ? 'yes' : '',
								'blog_show_author_avatar' => $content_settings['archive_layout_caption_author_avatar'] == 1 ? 'yes' : '',
								'by_text' => __('By', 'thegem'),
								'blog_show_comments' => $content_settings['archive_layout_caption_comments'] == 1 ? 'yes' : '',
								'blog_show_likes' => $content_settings['archive_layout_caption_likes'] == 1 ? 'yes' : '',
								'social_sharing' => $content_settings['archive_layout_caption_socials'] == 1 ? 'yes' : '',
								'blog_show_readmore_button' => $content_settings['archive_layout_caption_read_more'] == 1 ? 'yes' : '',
								'blog_readmore_button_text' => $content_settings['archive_layout_caption_read_more_text'],
								'caption_container_alignment' => $content_settings['archive_layout_caption_content_alignment_desktop'],
								'caption_container_alignment_tablet' => $content_settings['archive_layout_caption_content_alignment_tablet'],
								'caption_container_alignment_mobile' => $content_settings['archive_layout_caption_content_alignment_mobile'],
								'caption_container_preset' => $content_settings['archive_layout_caption_container_preset'],
								'show_bottom_border' => $content_settings['archive_layout_caption_bottom_border'] == 1 ? 'yes' : '',
								'show_pagination' => $content_settings['archive_layout_pagination'] == 1 ? 'yes' : '',
								'items_per_page' => $content_settings['archive_layout_pagination_items_per_page'],
								'pagination_type' => $content_settings['archive_layout_pagination_type'] == 'loadmore' ? 'more' : $content_settings['archive_layout_pagination_type'],
								'reduce_html_size' => $content_settings['archive_layout_pagination_reduce_html'] == 1 ? 'yes' : '',
								'items_on_load' => $content_settings['archive_layout_pagination_reduce_html_items_count'],
								'more_button_text' => $content_settings['archive_layout_load_more_text'],
								'more_icon_pack' => $content_settings['archive_layout_load_more_icon_pack'],
								'more_icon_' . $content_settings['archive_layout_load_more_icon_pack'] => $content_settings['archive_layout_load_more_icon'],
								'more_stretch_full_width' => $content_settings['archive_layout_load_more_stretch'] == 1 ? 'yes' : '',
								'more_show_separator' => ($content_settings['archive_layout_load_more_stretch'] != 1 && $content_settings['archive_layout_load_more_separator'] == 1) ? 'yes' : '',
								'load_more_spacing' => $content_settings['archive_layout_load_more_spacing_desktop'],
								'load_more_spacing_tablet' => $content_settings['archive_layout_load_more_spacing_tablet'],
								'load_more_spacing_mobile' => $content_settings['archive_layout_load_more_spacing_mobile'],
								'pagination_more_button_type' => $content_settings['archive_layout_load_more_btn_type'],
								'pagination_more_button_size' => $content_settings['archive_layout_load_more_btn_size'],
								'loading_animation' => $content_settings['archive_layout_loading_animation'] == 1 ? 'yes' : '',
								'animation_effect' => $content_settings['archive_layout_animation_effect'],
								'ignore_highlights' => $content_settings['archive_layout_ignore_highlights'] == 1 ? 'yes' : '',
								'skeleton_loader' => $content_settings['archive_layout_skeleton_loader'] == 1 ? 'yes' : '',
								'ajax_preloader_type' => $content_settings['archive_layout_ajax_preloader_type'],
								'fullwidth_section_images' => '',
								'title_weight' => '',
								'skin_source' => $content_settings['archive_skin_source'],
								'loop_builder' => $content_settings['archive_item_builder_template'],
								'equal_height' => !$content_settings['archive_items_equal_height_disabled'],
							);

						}

						if($settings['skin_source'] === 'builder') {
							$settings['ignore_highlights'] = 'yes';
							$equal_height = !empty($settings['equal_height']) && $settings['layout'] === 'justified';
						}

						$taxonomy_filter = $blog_authors = $date_query = [];

						if (is_author()) {
							$blog_authors = $settings['select_blog_authors'] = array(get_queried_object()->ID);
						} else if (is_category() || is_tag() || is_tax()) {
							$taxonomy_filter[get_queried_object()->taxonomy] = array(get_queried_object()->slug);
							$settings['archive_tax_filter'] = $taxonomy_filter;
						} else if (is_date()) {
							if (!empty(get_query_var('year'))) {
								$date_query['year'] = get_query_var('year');
							}
							if (!empty(get_query_var('monthnum'))) {
								$date_query['month'] = get_query_var('monthnum');
							}
							if (!empty(get_query_var('day'))) {
								$date_query['day'] = get_query_var('day');
							}
							$settings['date_query'] = $date_query;
						}

						if ($settings['blog_show_featured_image'] == '' && $settings['layout'] == 'metro') {
							$settings['layout'] = 'justified';
						}

						if (!empty($settings['image_ratio_default'])) {
							$settings['image_aspect_ratio'] = 'custom';
							$settings['image_ratio_custom'] = $settings['image_ratio_default'];
						}

						wp_enqueue_style('thegem-news-grid');
						wp_enqueue_script('thegem-portfolio-grid-extended');
						$grid_uid = 'blog_grid';
						$grid_uid_url = '';

						$localize = array(
							'data' => $settings,
							'action' => 'blog_grid_extended_load_more',
							'url' => admin_url('admin-ajax.php'),
							'nonce' => wp_create_nonce('portfolio_ajax-nonce')
						);
						wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_'. $grid_uid, $localize );
						$settings['action'] = 'blog_grid_extended_load_more';

						switch ($settings['thegem_elementor_preset']) {
							case 'default':
								if ($settings['caption_position'] == 'hover') {
									$hover_effect = $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'];
									wp_enqueue_style('thegem-news-grid-version-default-hovers-' . $settings['image_hover_effect']);
								} else {
									$hover_effect = $settings['image_hover_effect'];
									wp_enqueue_style('thegem-hovers-' . $settings['image_hover_effect']);
									wp_enqueue_style('thegem-news-grid-hovers');
								}
								break;
							case 'new':
								$hover_effect = $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'];
								wp_enqueue_style('thegem-news-grid-version-new-hovers-' . $settings['image_hover_effect']);
								break;
						}

						if ($settings['loading_animation'] === 'yes') {
							wp_enqueue_style('thegem-animations');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-scroll-monitor');
						}

						if ($settings['pagination_type'] == 'more') {
							wp_enqueue_style('thegem-button');
						} else if ($settings['pagination_type'] == 'scroll') {
							wp_enqueue_script('thegem-scroll-monitor');
						}

						if ($settings['layout'] !== 'justified' || $settings['ignore_highlights'] !== 'yes') {

							if ($settings['layout'] == 'metro') {
								wp_enqueue_script('thegem-isotope-metro');
							} else {
								wp_enqueue_script('thegem-isotope-masonry-custom');
							}
						}

						$page = get_query_var('paged') ?: 1;
						$next_page = 0;

						if ($page !== 1) {
							$settings['reduce_html_size'] = '';
						}
						$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 8;
						if ($settings['reduce_html_size'] == 'yes') {
							$items_on_load = $settings['items_on_load'] ? intval($settings['items_on_load']) : 8;
							if ($items_on_load >= $items_per_page) {
								$settings['reduce_html_size'] = '';
								$items_on_load = $items_per_page;
							}
						} else {
							$items_on_load = $items_per_page;
						}

						$orderby = $settings['orderby'];
						$order = $settings['order'];
						if ($settings['blog_show_sorting'] == 'yes') {
							$query = $_GET;
							$orderby = 'date';
							if (isset($query['orderby']) && $query['orderby'] == 'title') {
								$orderby = 'title';
								$query['orderby'] = 'date';
							} else {
								$query['orderby'] = 'title';
							}
							if (isset($query['order']) && $query['orderby'] == 'asc') {
								$query['order'] = 'asc';
							} else {
								$query['order'] = 'desc';
							}
							$url_result_orderby = add_query_arg($query);

							$query = $_GET;
							$order = 'desc';
							if (isset($query['orderby']) && $query['orderby'] == 'title') {
								$query['orderby'] = 'title';
							} else {
								$query['orderby'] = 'date';
							}
							if (isset($query['order']) && $query['orderby'] == 'asc') {
								$order = 'asc';
								$query['order'] = 'desc';
							} else {
								$query['order'] = 'asc';
							}
							if (isset($_GET['order'])) {
								$order = $_GET['order'];
							}
							$url_result_order = add_query_arg($query);
						}

						$news_grid_loop = get_thegem_extended_blog_posts($post_type_name, $taxonomy_filter, [], [], [], $blog_authors, $page, $items_on_load, $orderby, $order, 0, '', '', '', $date_query);
						if ($settings['reduce_html_size'] == 'yes') {
							$pagination_query = get_thegem_extended_blog_posts($post_type_name, $taxonomy_filter, [], [], [], $blog_authors, $page, $items_per_page, $orderby, $order, 0, '', '', '', $date_query);
						} else {
							$pagination_query = $news_grid_loop;
						}

						$max_page = ceil($news_grid_loop->found_posts / $items_per_page);

						if ($settings['reduce_html_size'] == 'yes') {
							$next_page = $news_grid_loop->found_posts > $items_on_load ? 2 : 0;
						} else {
							$next_page = $max_page > $page ? $page + 1 : 0;
						}

						$item_classes = get_thegem_portfolio_render_item_classes($settings);
						$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings);

						$style = '';

						if (!empty($settings['image_gaps']['size'])) {
							$gaps_size = $settings['image_gaps']['size'];

							$style .= '.portfolio.news-grid.category-grid .portfolio-item {
										padding: calc('.$gaps_size.'px/2) !important;
									}
									.portfolio.news-grid.category-grid .portfolio-row {
										margin: calc(-'.$gaps_size.'px/2);
									}
									.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
										margin: calc(-'.$gaps_size.'px/2) 0;
									}
									.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
										padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
									}
									.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
										padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
									}';
						}

						if (!empty($settings['image_gaps_tablet']['size'])) {
							$gaps_size = $settings['image_gaps_tablet']['size'];

							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid .portfolio-item {
											padding: calc('.$gaps_size.'px/2) !important;
										}
										.portfolio.news-grid.category-grid .portfolio-row {
											margin: calc(-'.$gaps_size.'px/2);
										}
										.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
											margin: calc(-'.$gaps_size.'px/2) 0;
										}
										.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
											padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
										}
										.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
											padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
										}
									}';
						}

						if (!empty($settings['image_gaps_mobile']['size'])) {
							$gaps_size = $settings['image_gaps_mobile']['size'];

							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid .portfolio-item {
											padding: calc('.$gaps_size.'px/2) !important;
										}
										.portfolio.news-grid.category-grid .portfolio-row {
											margin: calc(-'.$gaps_size.'px/2);
										}
										.portfolio.news-grid.category-grid.fullwidth-columns .portfolio-row {
											margin: calc(-'.$gaps_size.'px/2) 0;
										}
										.portfolio.news-grid.category-grid .fullwidth-block:not(.no-paddings) {
											padding-left: '.$gaps_size.'px; padding-right: '.$gaps_size.'px;
										}
										.portfolio.news-grid.category-grid .fullwidth-block .portfolio-row {
											padding-left: calc('.$gaps_size.'px/2); padding-right: calc('.$gaps_size.'px/2);
										}
									}';
						}

						if (!empty($settings['caption_container_alignment'])) {
							$style .= '.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment'].'; }';
						}

						if (!empty($settings['caption_container_alignment_tablet'])) {
							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment_tablet'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment_tablet'].'; }
									}';
						}

						if (!empty($settings['caption_container_alignment_mobile'])) {
							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description { text-align: '.$settings['caption_container_alignment_mobile'].'; }
								 .portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment_mobile'].'; }
									}';
						}

						if ($settings['caption_container_preset'] == 'transparent' && $settings['show_bottom_border'] == 'yes') {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .wrap > .caption {
									border-bottom-width: 1px !important;
								}';
						}

						if (!empty($settings['load_more_spacing'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing'].'px; }';
						}

						if (!empty($settings['load_more_spacing_tablet'])) {
							$style .= '@media (max-width: 991px) {
										.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing_tablet'].'px; }
									}';
						}

						if (!empty($settings['load_more_spacing_mobile'])) {
							$style .= '@media (max-width: 767px) {
										.portfolio.news-grid.category-grid .portfolio-load-more { margin-top: '.$settings['load_more_spacing_mobile'].'px; }
									}';
						}

						if (!empty($settings['truncate_titles'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .caption .title span, 
							.portfolio.news-grid.category-grid .portfolio-item .caption .title a { white-space: initial; max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($settings['truncate_titles']) . '; line-clamp: ' . esc_attr($settings['truncate_titles']) . '; -webkit-box-orient: vertical; }';
						}

						if (!empty($settings['truncate_description'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item .caption .description { max-height: initial; display: -webkit-box; -webkit-line-clamp: ' . esc_attr($settings['truncate_description']) . '; line-clamp: ' . esc_attr($settings['truncate_description']) . '; -webkit-box-orient: vertical; }';
						}

						if (isset($settings['image_size']) && $settings['image_size'] == 'full' && !empty($settings['image_ratio_full'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $settings['image_ratio_full'] . ' !important; height: auto; }';
						}

						if (isset($settings['image_size']) && $settings['image_size'] == 'default' && !empty($settings['image_ratio_default'])) {
							$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $settings['image_ratio_default'] . ' !important; height: auto; }';
						}

						echo '<style>'.$style.'</style>';

						if ($settings['columns_desktop'] == '100%' || (($settings['ignore_highlights'] !== 'yes' || $settings['layout'] !== 'justified') && $settings['skeleton_loader'] !== 'yes')) {
							$spin_class = 'preloader-spin';
							if ($settings['ajax_preloader_type'] == 'minimal') {
								$spin_class = 'preloader-spin-new';
							}
							echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
						} else if ($settings['skeleton_loader'] == 'yes') { ?>
							<div class="preloader save-space">
								<div class="skeleton">
									<div class="skeleton-posts portfolio-row">
										<?php for ($x = 0; $x < $news_grid_loop->post_count; $x++) {
											echo thegem_extended_blog_render_item($settings, $item_classes);
										} ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="portfolio-preloader-wrapper">

							<?php
							$blog_wrap_class = [
								'portfolio portfolio-grid extended-portfolio-grid news-grid category-grid no-padding',
								'portfolio-pagination-' . $settings['pagination_type'],
								'portfolio-style-' . $settings['layout'],
								'background-style-' . $settings['caption_container_preset'],
								'hover-' . $hover_effect,
								'title-on-' . $settings['caption_position'],
								'version-' . $settings['thegem_elementor_preset'],
								($settings['loading_animation'] == 'yes' ? 'loading-animation' : ''),
								($settings['loading_animation'] == 'yes' && $settings['animation_effect'] ? 'item-animation-' . $settings['animation_effect'] : ''),
								($settings['image_gaps']['size'] == 0 ? 'no-gaps' : ''),
								($settings['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-' . $settings['columns_100'] : ''),
								($settings['thegem_elementor_preset'] == 'new' || ($settings['thegem_elementor_preset'] == 'default' && $settings['caption_position'] == 'hover') ? 'hover-' . $settings['thegem_elementor_preset'] . '-' . $settings['image_hover_effect'] : 'hover-' . $settings['image_hover_effect']),
								($settings['caption_position'] == 'hover' ? 'hover-title' : ''),
								($settings['layout'] == 'masonry' && $settings['columns_desktop'] != '1x' ? 'portfolio-items-masonry' : ''),
								($settings['columns_desktop'] != '100%' ? 'columns-' . str_replace("x", "", $settings['columns_desktop']) : ''),
								'columns-tablet-' . str_replace("x", "", $settings['columns_tablet']),
								'columns-mobile-' . str_replace("x", "", $settings['columns_mobile']),
								($settings['layout'] == 'justified' && $settings['ignore_highlights'] == 'yes' ? 'disable-isotope' : ''),
								($settings['blog_show_featured_image'] == '' && $settings['caption_position'] == 'page' ? 'without-image' : ''),
								(($settings['image_size'] == 'full' && empty($settings['image_ratio_full'])) || !in_array($settings['image_size'], ['full', 'default']) ? 'full-image' : ''),
								($settings['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
								($settings['reduce_html_size'] == 'yes' ? 'reduce-size' : ''),
								(!empty($equal_height) ? 'loop-equal-height' : ''),
							];
							?>

							<div class="<?php echo implode(" ", $blog_wrap_class); ?>"
								 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
								 data-current-page="<?php echo esc_attr($page); ?>"
								 data-per-page="<?php echo esc_attr($items_per_page); ?>"
								 data-next-page="<?php echo esc_attr($next_page); ?>"
								 data-pages-count="<?php echo esc_attr($max_page); ?>"
								 data-hover="<?php echo esc_attr($hover_effect); ?>">
								<?php if ($settings['blog_show_sorting'] == 'yes'): ?>
									<div class="portfolio-top-panel<?php if ($settings['columns_desktop'] == '100%'): ?> fullwidth-block<?php endif; ?>">
										<div class="portfolio-top-panel-row">
											<div class="portfolio-top-panel-left"></div>
											<div class="portfolio-top-panel-right">
												<div class="portfolio-sorting title-h6">
													<div class="orderby light">
														<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
														<a href="<?php echo esc_url($url_result_orderby); ?>" class="sorting-switcher <?php echo $orderby == 'title' ? 'right' : ''; ?>" data-current="<?php echo esc_attr($orderby); ?>"></a>
														<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
													</div>
													<div class="portfolio-sorting-sep"></div>
													<div class="order light">
														<label for="" data-value="DESC"><?php _e('Desc', 'thegem') ?></label>
														<a href="<?php echo esc_url($url_result_order); ?>" class="sorting-switcher <?php echo $order == 'asc' ? 'right' : ''; ?>" data-current="<?php echo esc_attr($order); ?>"></a>
														<label for="" data-value="ASC"><?php _e('Asc', 'thegem') ?></label>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="portfolio-row-outer <?php if ($settings['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
									<div class="row portfolio-row">
										<div class="portfolio-set clearfix"
											 data-max-row-height="">
											<?php
											if ($news_grid_loop->have_posts()) {
												while ($news_grid_loop->have_posts()) {
													$news_grid_loop->the_post();
													echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID());
												}
											} ?>
										</div><!-- .portflio-set -->
										<?php if ($settings['columns_desktop'] != '1x'): ?>
											<div class="portfolio-item-size-container">
												<?php echo thegem_extended_blog_render_item($settings, $item_classes); ?>
											</div>
										<?php endif; ?>
									</div><!-- .row-->
									<?php
									if ($settings['show_pagination'] == 'yes') {
										if ($settings['pagination_type'] == 'normal') {
											thegem_pagination($pagination_query);
										} else if ($settings['pagination_type'] == 'more' && $pagination_query->max_num_pages > 1) {

											$separator_enabled = !empty($settings['more_show_separator']) ? true : false;

											// Container
											$classes_container = 'gem-button-container gem-widget-button ';

											if ($separator_enabled) {
												$classes_container .= 'gem-button-position-center gem-button-with-separator ';
											} else {
												if ('yes' === $settings['more_stretch_full_width']) {
													$classes_container .= 'gem-button-position-fullwidth ';
												}
											}

											// Separator
											$classes_separator = 'gem-button-separator gem-button-separator-type-single';

											if (!empty($settings['pagination_more_button_separator_style_active'])) {
												$classes_separator .= esc_attr($settings['pagination_more_button_separator_style_active']);
											} ?>

											<div class="portfolio-load-more">
												<div class="inner">

													<div class="<?php echo esc_attr($classes_container); ?>">
														<?php if ($separator_enabled) { ?>
														<div class="<?php echo esc_attr($classes_separator); ?>">
															<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
																<div class="gem-button-separator-line"></div>
															</div>
															<div class="gem-button-separator-button">
																<?php } ?>

																<button class="load-more-button gem-button gem-button-size-<?php echo $settings['pagination_more_button_size']; ?> gem-button-style-<?php echo $settings['pagination_more_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
																		<span class="gem-inner-wrapper-btn">
																			<?php if (isset($settings['more_icon_pack']) && $settings['more_icon_' . $settings['more_icon_pack']] != '') {
																				echo thegem_build_icon($settings['more_icon_pack'], $settings['more_icon_' . $settings['more_icon_pack']]);
																			} ?>
																			<span class="gem-text-button">
																				<?php echo '<span>' . wp_kses($settings['more_button_text'], 'post') . '</span>'; ?>
																			</span>
																		</span>
																</button>

																<?php if ($separator_enabled) { ?>
															</div>
															<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
																<div class="gem-button-separator-line"></div>
															</div>
														</div>
													<?php } ?>

													</div>
												</div>
											</div>
											<?php
										} else if ($settings['pagination_type'] == 'scroll' && $pagination_query->max_num_pages > 0) { ?>
											<div class="portfolio-scroll-pagination"></div>
										<?php }
									} ?>
								</div><!-- .full-width -->
							</div><!-- .portfolio-->
						</div><!-- .portfolio-preloader-wrapper-->

						<?php

					} else {
						if ($is_blog) {
							$params = array(
								'skin_source' => thegem_get_option('blog_skin_source'),
								'loop_builder' => thegem_get_option('blog_archive_item_builder_template'),
								'gaps_desktop' => thegem_get_option('blog_list_builder_gaps_desktop'),
								'gaps_tablet' => thegem_get_option('blog_list_builder_gaps_tablet'),
							);
						} else {
							$params = array(
								'skin_source' => $content_settings['archive_skin_source'],
								'loop_builder' => $content_settings['archive_item_builder_template'],
								'gaps_desktop' => $content_settings['archive_list_builder_gaps_desktop'],
								'gaps_tablet' => $content_settings['archive_list_builder_gaps_tablet'],
							);
						}
						if(!is_singular()) {
							wp_enqueue_style('thegem-blog');
							wp_enqueue_style('thegem-additional-blog');
							wp_enqueue_style('thegem-blog-timeline-new');
							wp_enqueue_script('thegem-scroll-monitor');
							wp_enqueue_script('thegem-items-animations');
							wp_enqueue_script('thegem-blog');
							wp_enqueue_script('thegem-gallery');
							if(!empty($params['skin_source']) && $params['skin_source'] === 'builder' && !empty($params['loop_builder'])) {
								$params['gaps_desktop'] = intval($params['gaps_desktop']) > 0 || $params['gaps_desktop'] === '0' ? intval($params['gaps_desktop']) : 42;
								$params['gaps_tablet'] = intval($params['gaps_tablet']) > 0 || $params['gaps_tablet'] === '0' ? intval($params['gaps_tablet']) : 42;
								echo '<style type="text/css">';
								echo thegem_generate_css(
									array('rules' => array(array(
										'selector' => '.blog .thegem-template-loop-item.thegem-template-'.esc_attr($params['loop_builder']),
										'styles' => array(
											'margin-bottom' => $params['gaps_desktop'].'px',
										)
									)))
								);
								echo thegem_generate_css(
									array('media' => '(max-width: 1023px)', 'rules' => array(array(
										'selector' => '.blog .thegem-template-loop-item.thegem-template-'.esc_attr($params['loop_builder']),
										'styles' => array(
											'margin-bottom' => $params['gaps_tablet'].'px',
										)
									)))
								);
								echo '</style>';
							}
							echo '<div class="blog blog-style-default">';
						}

						while ( have_posts() ) : the_post();

							if(!empty($params['skin_source']) && $params['skin_source'] === 'builder' && !empty($params['loop_builder'])) {
?>
<div <?php post_class(); ?> data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php echo esc_attr($params['loop_builder']); ?> thegem-loop-post-<?= esc_attr(get_the_id());?>">
		<?php
		$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
		$GLOBALS['thegem_template_type'] = 'loop-item';
		$GLOBALS['thegem_loop_item_post'] = get_the_id();
		echo thegem_loop_item_styles(get_the_id(), $params['loop_builder']);
		echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($params['loop_builder']);
		unset($GLOBALS['thegem_template_type']);
		unset($GLOBALS['thegem_loop_item_post']);
		if (!empty($thegem_template_type_outer)) {
			$GLOBALS['thegem_template_type'] = $thegem_template_type_outer;
		}
		?>
	</div>
</div>
<?php
							} else {
								get_template_part('content', 'blog-item');
							}

						endwhile;

						if(!is_singular()) { thegem_pagination(); echo '</div>'; }
					}
				} else {
					get_template_part( 'content', 'none' );
				}
				?>
			</div>
			<?php
			if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_show'] && !empty($thegem_page_data['sidebar']['sidebar_position'])) {
				echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12'.esc_attr($thegem_sidebar_classes).'" role="complementary">';
				get_sidebar('page');
				echo '</div><!-- .sidebar -->';
			}
			?>
		</div>
	</div><!-- .container -->
</div><!-- .block-content -->
