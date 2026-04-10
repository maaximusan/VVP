<?php

if (!function_exists('thegem_extended_blog_render_item')) {
	function thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes = null, $post_id = false, $thegem_highlight_type_creative = null) {
		$slugs = array();

		if ($post_id) {
			if (!isset($settings['query_type']) || $settings['query_type'] == 'post') {
				$slugs = wp_get_object_terms($post_id, array('category'), array('fields' => 'slugs'));
			}

			$thegem_post_data = thegem_get_sanitize_page_title_data(get_the_ID());
			if (get_post_type() == 'thegem_pf_item') {
				$post_item_data = thegem_get_sanitize_pf_item_data(get_the_ID());
				$post_format = $post_item_data['grid_appearance_type'];
				foreach ($post_item_data as $key => $value) {
					if (strpos($key, 'grid_appearance_') !== false) {
						$post_item_data[str_replace('grid_appearance_','', $key)] = $value;
						unset($post_item_data[$key]);
					}
				}
			} else if (get_post_type() == 'post' || get_post_type() == 'page' || get_post_type() == 'product') {
				$post_item_data = thegem_get_sanitize_post_data(get_the_ID());
				$post_format = get_post_format(get_the_ID());
			} else {
				$post_item_data = thegem_get_sanitize_cpt_item_data(get_the_ID());
				$post_format = get_post_format(get_the_ID());
			}

			if ($thegem_highlight_type_creative) {
				$thegem_highlight_type = $thegem_highlight_type_creative;
			} else if (($settings['ignore_highlights'] != 'yes') && !empty($post_item_data['highlight'])) {
				if (!empty($post_item_data['highlight_type'])) {
					$thegem_highlight_type = $post_item_data['highlight_type'];
				} else {
					$thegem_highlight_type = 'squared';
				}
			} else {
				$thegem_highlight_type = 'disabled';
			}

			if ($settings['thegem_elementor_preset'] == 'list' && $post_format == 'quote') {
				$post_format = '';
			}
		} else {
			$portfolio_item_size = true;
			$thegem_post_data = array();
			$post_item_data = array();
			$thegem_highlight_type = 'disabled';
		}

		$thegem_classes = array('portfolio-item');
		$thegem_classes = array_merge($thegem_classes, $slugs);

		$thegem_image_classes = array('image');
		$thegem_caption_classes = array('caption');

		if ($settings['layout'] != 'metro' || isset($portfolio_item_size)) {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical') {
				$thegem_classes = array_merge($thegem_classes, get_thegem_portfolio_render_item_classes($settings, $thegem_highlight_type));
			} else {
				$thegem_classes = array_merge($thegem_classes, $item_classes);
			}
		}

		if ($settings['ignore_highlights'] == 'yes') {
			unset($post_item_data['highlight']);
			unset($post_item_data['highlight_type']);
			unset($post_item_data['highlight_style']);
		}

		if ($settings['layout'] != 'metro') {
			if ($settings['columns_desktop'] == '1x') {
				$thegem_image_classes = array_merge($thegem_image_classes, array('col-sm-5', 'col-xs-12'));
				$thegem_caption_classes = array_merge($thegem_caption_classes, array('col-sm-7', 'col-xs-12'));
			}
		}

		if ($thegem_highlight_type != 'disabled') {
			$thegem_classes[] = 'double-item';
			$thegem_classes[] = 'double-item-' . $thegem_highlight_type;
		}

		$alternative_highlight_style_enabled = isset($post_item_data['highlight']) && $post_item_data['highlight'] && $post_item_data['highlight_style'] == 'alternative' && $settings['caption_position'] == 'hover';
		if ($alternative_highlight_style_enabled) {
			$thegem_classes[] = 'double-item-style-' . $post_item_data['highlight_style'];
			$thegem_classes[] = 'double-item-style-' . $post_item_data['highlight_style'] . '-' . $thegem_highlight_type;

			if ($thegem_highlight_type == 'squared') {
				$thegem_highlight_type = 'vertical';
			} else {
				$thegem_highlight_type = 'disabled';
			}
		}

		if ($thegem_highlight_type != 'disabled') {
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings, $thegem_highlight_type);
		}

		if (isset($settings['loading_animation']) && $settings['loading_animation'] === 'yes') {
			$thegem_classes[] = 'item-animations-not-inited before-start';
		}

		$thegem_self_video = '';

		if ($settings['blog_show_categories'] != 'yes') {
			$thegem_classes[] = 'post-hide-categories';
		}

		if ($settings['blog_show_date'] != 'yes') {
			$thegem_classes[] = 'post-hide-date';
		}

		$thegem_has_post_thumbnail = has_post_thumbnail(get_the_ID());

		$post_excerpt = has_excerpt() ? preg_replace('%&#x[a-fA-F0-9]+;%', '', apply_filters('the_excerpt', get_the_excerpt())) : (!empty($thegem_post_data['title_excerpt']) ? $thegem_post_data['title_excerpt'] : '');

		$has_comments = comments_open() && (!isset($settings['blog_show_comments']) || $settings['blog_show_comments'] == 'yes');

		$has_likes = function_exists('zilla_likes') && (!isset($settings['blog_show_likes']) || $settings['blog_show_likes'] == 'yes');

		if ($settings['thegem_elementor_preset'] != 'default' && $settings['caption_position'] == 'page' && $settings['caption_container_preset'] == 'transparent' && ($has_likes || $has_comments || $settings['social_sharing'] == 'yes')) {
			$thegem_classes[] = 'show-caption-border';
		}

		if ($settings['thegem_elementor_preset'] == 'default' && $settings['caption_position'] == 'page' && $settings['caption_container_preset'] == 'transparent') {
			$thegem_classes[] = 'show-caption-border';
		}

		if (empty($post_excerpt)) {
			$thegem_classes[] = 'post-empty-excerpt';
		}

		if ($settings['blog_show_categories'] == 'yes') {
			foreach ($slugs as $thegem_k => $thegem_slug) {
				if (isset($thegem_terms_set[$thegem_slug])) {
					$thegem_classes[] = 'post-has-sets';
					break;
				}
			}
		}

		if ($settings['blog_show_author'] != 'yes') {
			$thegem_classes[] = 'post-has-author';
		}

		if(!empty($settings['skin_source']) && $settings['skin_source'] === 'builder' && !empty($settings['loop_builder']) && empty($portfolio_item_size)) {
?>
<div <?php post_class($thegem_classes); ?> style="padding: calc(<?= $settings['image_gaps']['size'] ?>px/2)" data-default-sort="<?php echo intval(get_post()->menu_order); ?>" data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="thegem-template-wrapper thegem-template-loop-item thegem-template-<?php echo esc_attr($settings['loop_builder']); ?> thegem-loop-post-<?= esc_attr($post_id);?>">
		<?php
		$thegem_template_type_outer = isset($GLOBALS['thegem_template_type']) ? $GLOBALS['thegem_template_type'] : '';
		$GLOBALS['thegem_template_type'] = 'loop-item';
		$GLOBALS['thegem_loop_item_post'] = $post_id;
		echo thegem_loop_item_styles($post_id, $settings['loop_builder']);
		echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['loop_builder']);
		unset($GLOBALS['thegem_template_type']);
		unset($GLOBALS['thegem_loop_item_post']);
		if (!empty($thegem_template_type_outer)) {
			$GLOBALS['thegem_template_type'] = $thegem_template_type_outer;
		}
		?>
	</div>
	<?php /*<a href="<?php echo get_the_permalink($post_id); ?>">Link</a>*/ ?>
</div>
<?php
		} else {
			include(locate_template(array('gem-templates/blog/content-blog-extended-item.php')));
		}
	}
}

if (!function_exists('thegem_extended_blog_render_item_author')) {
	function thegem_extended_blog_render_item_author($settings) {
		if ($settings['blog_show_author'] != 'yes') return;
		$by_text = isset($settings['by_text']) ? $settings['by_text'] : __('By', 'thegem');
		?>

		<div class="author">
			<?php if ($settings['blog_show_author_avatar'] == 'yes'): ?>
				<span class="author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 50, '', get_the_author()) ?></span>
			<?php endif; ?>
			<span class="author-name"><?php printf(esc_html__($by_text . " %s", "thegem"), get_the_author_link()) ?></span>
		</div>
		<?php
	}
}

if (!function_exists('thegem_extended_blog_render_item_meta')) {
	function thegem_extended_blog_render_item_meta($settings, $has_comments, $has_likes, $post_id) {
		global $post;
		if (!$has_comments && !$has_likes && $settings['social_sharing'] != 'yes') return;
		?>

		<div class="grid-post-meta clearfix <?php if (!$has_likes): ?>without-likes<?php endif; ?>">
			<div class="grid-post-meta-inner">
				<?php if ($settings['social_sharing'] == 'yes'): ?>
					<div class="grid-post-share">
						<a href="javascript: void(0);" class="icon share" role="button" aria-label="<?php esc_attr_e('Share', 'thegem'); ?>">
							<?php if (isset($settings['sharing_icon']) && $settings['sharing_icon']['value']) {
								\Elementor\Icons_Manager::render_icon($settings['sharing_icon'], ['aria-hidden' => 'true']);
							} else { ?>
								<i class="default"></i>
							<?php } ?>
						</a>
					</div>
				<?php endif; ?>

				<div class="grid-post-meta-comments-likes">
					<?php if ($has_comments) {
						echo '<span class="comments-link">';
						if (isset($settings['comments_icon']) && $settings['comments_icon']['value']) {
							\Elementor\Icons_Manager::render_icon($settings['comments_icon'], ['aria-hidden' => 'true']);
						} else { ?>
							<i class="default"></i>
						<?php }
						comments_popup_link(0, 1, '%');
						echo '</span>'; ?>
					<?php } ?>

					<?php if ($has_likes) {
						echo '<span class="post-meta-likes">';
						if (isset($settings['likes_icon']) && $settings['likes_icon']['value']) {
							\Elementor\Icons_Manager::render_icon($settings['likes_icon'], ['aria-hidden' => 'true']);
						} else { ?>
							<i class="default"></i>
						<?php }
						zilla_likes();
						echo '</span>';
					} ?>
				</div>

				<?php if ($settings['social_sharing'] == 'yes'): ?>
					<div class="portfolio-sharing-pane"><?php include(locate_template(array('gem-templates/blog/socials-sharing.php'))); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}
}

if (!function_exists('blog_grid_extended_more_callback')) {
	function blog_grid_extended_more_callback() {
		$settings = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
		ob_start();
		$response = array('status' => 'success');
		$page = isset($settings['more_page']) ? intval($settings['more_page']) : 1;
		if ($page == 0)
			$page = 1;

		$show_all = $settings['load_more_show_all'] == 'yes' && $page != 1;

		$taxonomy_filter = $meta_filter = $attributes = $manual_selection = $blog_authors = $date_query = [];

		if ($settings['query_type'] == 'post') {

			$post_type = 'post';
			foreach ($settings['source'] as $source) {
				if ($source == 'categories' && !empty($settings['categories'])) {
					$taxonomy_filter['category'] = $settings['categories'];
				} else if ($source == 'tags' && !empty($settings['select_blog_tags'])) {
					$taxonomy_filter['post_tag'] = $settings['select_blog_tags'];
				} else if ($source == 'posts' && !empty($settings['select_blog_posts'])) {
					$manual_selection = $settings['select_blog_posts'];
				} else if ($source == 'authors' && !empty($settings['select_blog_authors'])) {
					$blog_authors = $settings['select_blog_authors'];
				}
			}
			$exclude = $settings['exclude_blog_posts'];

		} else if ($settings['query_type'] == 'related') {

			$post_type = isset($settings['taxonomy_related_post_type']) ? $settings['taxonomy_related_post_type'] : 'any';
			$taxonomy_filter = $settings['related_tax_filter'];
			$exclude = $settings['exclude_posts_manual'];

		} else if ($settings['query_type'] == 'archive') {

			$post_type = $settings['archive_post_type'];
			if (!empty($settings['select_blog_authors'])) {
				$blog_authors = $settings['select_blog_authors'];
			} else if (!empty($settings['archive_tax_filter'])) {
				$taxonomy_filter = $settings['archive_tax_filter'];
			} else if (!empty($settings['date_query'])) {
				$date_query = $settings['date_query'];
			}
			$exclude = $settings['exclude_posts_manual'];

		} else if ($settings['query_type'] == 'manual') {

			$post_type = 'any';
			$manual_selection = $settings['select_posts_manual'];
			$exclude = $settings['exclude_posts_manual'];

		} else {

			$post_type = $settings['query_type'];
			foreach ($settings['source_post_type_' . $post_type] as $source) {
				if ($source == 'all') {

				} else if ($source == 'manual') {
					$manual_selection = $settings['source_post_type_' . $post_type . '_manual'];
				} else {
					$tax_terms = $settings['source_post_type_' . $post_type . '_tax_' . $source];
					if (!empty($tax_terms)) {
						$taxonomy_filter[$source] = $tax_terms;
					}
				}
			}
			$exclude = $settings['source_post_type_' . $post_type . '_exclude'];

		}

		if (!empty($settings['has_categories_filter']) && !empty($settings['categories'])) {
			if ($post_type == 'product') {
				$taxonomy_filter['product_cat'] = $settings['categories'];
			} else {
				$taxonomy_filter['category'] = $settings['categories'];
			}
		}

		if (!empty($settings['has_attributes_filter'])) {
			$attrs = explode(",", $settings['filters_attr']);
			foreach ($attrs as $attr) {
				$values = json_decode($settings['filters_attr_val_' . $attr]);
				if (!empty($values)) {
					if (strpos($attr, "tax_") === 0) {
						$taxonomy_filter[str_replace("tax_","", $attr)] = $values;
					} else if (strpos($attr, "meta_") === 0) {
						$meta_filter[str_replace("meta_", "", $attr)] = $values;
					} else {
						if (empty($values) || in_array('0', $values)) {
							$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
						}
						$taxonomy_filter['pa_' . $attr] = $values;
					}
				}
			}
		}

		$sale_only = $stock_only = null;
		if (isset($params['content_products_status_filter'])) {
			if (in_array('sale', $settings['content_products_status_filter'])) {
				$sale_only = true;
			}
			if (in_array('stock', $settings['content_products_status_filter'])) {
				$stock_only = true;
			}
		}

		if (isset($settings['content_products_price_filter'])) {
			$meta_filter['_price__range'] = $settings['content_products_price_filter'];
		}
		$search = isset($settings['portfolio_search_filter']) && $settings['portfolio_search_filter'] != '' ? $settings['portfolio_search_filter'] : null;

		if(!empty($settings['search_page'])) {
			$post_type = thegem_get_search_post_types_array();
		}
		$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 8;

		$news_grid_loop = get_thegem_extended_blog_posts($post_type, $taxonomy_filter, $meta_filter, $manual_selection, $exclude, $blog_authors, $page, $items_per_page, $settings['orderby'], $settings['order'], $settings['offset'], $settings['ignore_sticky_posts'], $search, $settings['search_by'], $date_query, $show_all, $sale_only, $stock_only);
		$max_page = ceil(($news_grid_loop->found_posts - intval($settings['offset'])) / $items_per_page);
		$next_page = $max_page > $page ? $page + 1 : 0;
		if ($show_all) {
			$next_page = 0;
		}

		if ($news_grid_loop->have_posts()) {

			$item_classes = get_thegem_portfolio_render_item_classes($settings);
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($settings); ?>

			<div data-page="<?php echo $page; ?>" data-next-page="<?php echo $next_page; ?>" data-pages-count="<?php echo esc_attr($max_page); ?>">
				<?php
				if ($settings['layout'] == 'creative') {
					$schemes_list = [
						'6' => [
							'6a' => [
								'count' => 9,
								0 => 'squared',
							],
							'6b' => [
								'count' => 7,
								0 => 'squared',
								1 => 'horizontal',
								6 => 'horizontal',
							],
							'6c' => [
								'count' => 9,
								0 => 'horizontal',
								3 => 'horizontal',
								6 => 'horizontal',
							],
							'6d' => [
								'count' => 9,
								0 => 'horizontal',
								1 => 'horizontal',
								2 => 'horizontal',
							],
							'6e' => [
								'count' => 6,
								0 => 'squared',
								1 => 'squared',
							]
						],
						'5' => [
							'5a' => [
								'count' => 7,
								0 => 'squared',
							],
							'5b' => [
								'count' => 8,
								0 => 'horizontal',
								4 => 'horizontal',
							],
							'5c' => [
								'count' => 6,
								0 => 'horizontal',
								1 => 'horizontal',
								4 => 'horizontal',
								5 => 'horizontal',
							],
							'5d' => [
								'count' => 4,
								0 => 'squared',
								1 => 'vertical',
								2 => 'horizontal',
								3 => 'horizontal',
							]
						],
						'4' => [
							'4a' => [
								'count' => 5,
								0 => 'squared',
							],
							'4b' => [
								'count' => 4,
								0 => 'squared',
								1 => 'horizontal',
							],
							'4c' => [
								'count' => 4,
								0 => 'squared',
								1 => 'vertical',
							],
							'4d' => [
								'count' => 7,
								0 => 'vertical',
							],
							'4e' => [
								'count' => 4,
								0 => 'vertical',
								1 => 'vertical',
								2 => 'horizontal',
								3 => 'horizontal',
							],
							'4f' => [
								'count' => 6,
								0 => 'horizontal',
								5 => 'horizontal',
							]
						],
						'3' => [
							'3a' => [
								'count' => 4,
								0 => 'vertical',
								1 => 'vertical',
							],
							'3b' => [
								'count' => 4,
								1 => 'horizontal',
								2 => 'horizontal',
							],
							'3c' => [
								'count' => 5,
								0 => 'vertical',
							],
							'3d' => [
								'count' => 5,
								0 => 'horizontal',
							],
							'3e' => [
								'count' => 3,
								0 => 'squared',
							],
							'3f' => [
								'count' => 4,
								0 => 'horizontal',
								1 => 'vertical',
							],
							'3g' => [
								'count' => 4,
								0 => 'vertical',
								3 => 'horizontal',
							],
							'3h' => [
								'count' => 5,
								2 => 'vertical',
							]
						],
						'2' => [
							'2a' => [
								'count' => 5,
								0 => 'vertical',
							],
							'2b' => [
								'count' => 5,
								3 => 'vertical',
							],
							'2c' => [
								'count' => 4,
								0 => 'vertical',
								2 => 'vertical',
							],
							'2d' => [
								'count' => 4,
								0 => 'horizontal',
								1 => 'vertical',
							],
							'2e' => [
								'count' => 5,
								0 => 'horizontal',
							],
							'2f' => [
								'count' => 4,
								0 => 'horizontal',
								1 => 'horizontal',
							],
							'2g' => [
								'count' => 5,
								2 => 'horizontal',
							],
							'2h' => [
								'count' => 4,
								0 => 'horizontal',
								3 => 'horizontal',
							],
						]
					];
					$columns = $settings['columns_desktop'] != '100%' ? str_replace("x", "", $settings['columns_desktop']) : $settings['columns_100'];
					$items_sizes = $schemes_list[$columns][$settings['layout_scheme_' . $columns . 'x']];
					$items_count = $items_sizes['count'];
				}

				$i = 0;
				while ($news_grid_loop->have_posts()) {
					$news_grid_loop->the_post();
					$thegem_highlight_type_creative = null;
					if ($settings['layout'] == 'creative') {
						$thegem_highlight_type_creative = 'disabled';
						$item_num = $i % $items_count;
						if (isset($items_sizes[$item_num])) {
							$thegem_highlight_type_creative = $items_sizes[$item_num];
						}
					}
					echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID(), $thegem_highlight_type_creative);
					if ($settings['layout'] == 'creative' && $i == 1) {
						echo thegem_extended_blog_render_item($settings, ['size-item'], $thegem_sizes);
					}
					$i++;
				} ?>
			</div>
		<?php } else { ?>
			<div data-page="1" data-next-page="0" data-pages-count="1">
				<div class="portfolio-item not-found">
					<div class="found-wrap">
						<div class="image-inner empty"></div>
						<div class="msg">
							<?php echo wp_kses($settings['not_found_text'], 'post'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php }

		$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		$response['query'] = $news_grid_loop->query;
		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
	}

	add_action('wp_ajax_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
	add_action('wp_ajax_nopriv_blog_grid_extended_load_more', 'blog_grid_extended_more_callback');
}

if (!function_exists('get_thegem_extended_blog_posts')) {
	function get_thegem_extended_blog_posts($post_type, $taxonomy_filter, $meta_filter, $manual_selection, $exclude, $authors, $page = 1, $ppp = -1, $orderby = '', $order = '', $offset = false, $ignore_sticky_posts = false, $search = null, $search_by = 'content', $date_query = '', $show_all = false, $sale_only = false, $stock_only = false) {

		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => $ppp,
		);

		if ($orderby == 'default') {
			$args['orderby'] = 'menu_order date';
		} else if ($orderby == 'popularity') {
			$args['orderby'] = array('meta_value_num' => 'DESC', 'ID' => 'DESC');
			$args['meta_key'] = 'total_sales';
		} else if ($orderby == 'price') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
		} else if ($orderby == 'rating') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
		} else if (!empty($orderby)) {
			$args['orderby'] = $orderby;
			if (!in_array($orderby, ['date', 'id', 'author', 'title', 'name', 'modified', 'comment_count', 'rand', 'menu_order date'])) {
				if (strpos($orderby, 'num_') === 0) {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = str_replace('num_', '', $orderby);
				} else {
					$args['orderby'] = 'meta_value';
					$args['meta_key'] = $orderby;
				}
			}
		}

		if (!empty($order) && $orderby !== 'default') {
			$args['order'] = $order;
		}

		if (!empty($date_query)) {
			$args['date_query'] = array($date_query);
		}

		$tax_query = $meta_query = [];

		if (!empty($taxonomy_filter)) {
			foreach ($taxonomy_filter as $tax => $tax_arr) {
				if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
					$query_arr = array(
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $tax_arr,
					);
				} else {
					$query_arr = array(
						'taxonomy' => $tax,
						'operator' => 'EXISTS'
					);
				}
				$tax_query[] = $query_arr;
			}
		}

		if (!empty($meta_filter)) {
			foreach ($meta_filter as $meta => $meta_arr) {
				if (!empty($meta_arr)) {
					if (strpos($meta, "__range") > 0) {
						$query_arr = array(
							'key' => str_replace("__range","", $meta),
							'value' => $meta_arr,
							'compare'   => 'BETWEEN',
							'type'   => 'NUMERIC',
						);
					} else if (strpos($meta, "__check") > 0) {
						$check_meta_query = array(
							'relation' => 'OR',
						);
						foreach ($meta_arr as $value) {
							$check_meta_query[] = array(
								'key' => str_replace("__check","", $meta),
								'value' => sprintf('"%s"', $value),
								'compare' => 'LIKE',
							);
						}
						$query_arr = $check_meta_query;
					} else {
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare' => 'IN',
						);
					}
					$meta_query[] = $query_arr;
				}
			}
		}

		if (!empty($search) && $search_by != 'content') {
			$search_meta_query = array(
				'relation' => 'OR',
			);
			foreach ($search_by as $key) {
				$search_meta_query[] = array(
					'key' => $key,
					'value' => $search,
					'compare' => 'LIKE'
				);
			}
			$meta_query[] = $search_meta_query;
		}

		if ($stock_only) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => array('outofstock'),
				'operator' => 'NOT IN'
			);
		}

		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		if (!empty($meta_query)) {
			$args['meta_query'] = $meta_query;
		}

		if ($sale_only) {
			$args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
		}

		if (!empty($manual_selection)) {
			if ($sale_only) {
				$args['post__in'] = array_intersect($args['post__in'], $manual_selection);
			} else {
				$args['post__in'] = $manual_selection;
			}
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
		}

		if (!empty($authors)) {
			$args['author__in'] = $authors;
		}

		if ($ignore_sticky_posts == 'yes') {
			$args['ignore_sticky_posts'] = 1;
		}

		if (!empty($offset) || $show_all) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if ($show_all) {
			$args['posts_per_page'] = 999;
		}

		if (!empty($search) && $search_by == 'content') {
			$args['s'] = $search;
		}

		return new WP_Query($args);
	}
}

if (!function_exists('thegem_search_grid_content')) {
	function thegem_search_grid_content($mixed_grid = false) {

		if (get_query_var('post_type') != 'any' && !$mixed_grid) {
			$post_types_arr = get_query_var('post_type');
		} else {
			$post_types_arr = thegem_get_search_post_types_array();
		}

		$settings = array(
			'query_type' => get_post_type(),
			'layout' => thegem_get_option('search_layout_type_grid'),
			'categories' => ['0'],
			'search' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
			'post_type' => $post_types_arr,
			'columns_desktop' => thegem_get_option('search_layout_columns_desktop'),
			'columns_tablet' => thegem_get_option('search_layout_columns_tablet'),
			'columns_mobile' => thegem_get_option('search_layout_columns_mobile'),
			'columns_100' => thegem_get_option('search_layout_columns_100'),
			'caption_position' => thegem_get_option('search_layout_caption_position') == 'bellow' ? 'page' : 'hover',
			'thegem_elementor_preset' => thegem_get_option('search_layout_skin') == 'classic' ? 'default' : 'new',
			'image_gaps' => ['size' => thegem_get_option('search_layout_gaps_desktop'), 'unit' => 'px'],
			'image_gaps_tablet' => ['size' => thegem_get_option('search_layout_gaps_tablet')],
			'image_gaps_mobile' => ['size' => thegem_get_option('search_layout_gaps_mobile')],
			'image_size' => thegem_get_option('search_layout_image_size'),
			'image_ratio_full' => thegem_get_option('search_layout_image_ratio_full'),
			'image_ratio_default' => thegem_get_option('search_layout_image_ratio_default'),
			'icon_hover_show' => thegem_get_option('search_layout_icon_on_hover') == 1 ? 'yes' : '',
			'blog_show_sorting' => thegem_get_option('search_layout_sorting') == 1 ? 'yes' : '',
			'post_type_indication' => thegem_get_option('search_layout_post_type_indication') == 1 ? 'yes' : '',
			'image_hover_effect' => str_replace("_", "-", thegem_get_option('search_layout_hover_effect')),
			'blog_show_featured_image' => thegem_get_option('search_layout_caption_featured_image') == 1 ? 'yes' : '',
			'blog_show_title' => thegem_get_option('search_layout_caption_title') == 1 ? 'yes' : '',
			'blog_title_preset' => 'title-'.thegem_get_option('search_layout_caption_title_preset'),
			'blog_show_description' => thegem_get_option('search_layout_caption_description') == 1 ? 'yes' : '',
			'blog_show_date' => thegem_get_option('search_layout_caption_date') == 1 ? 'yes' : '',
			'blog_show_categories' => thegem_get_option('search_layout_caption_categories') == 1 ? 'yes' : '',
			'blog_show_author' => thegem_get_option('search_layout_caption_author') == 1 ? 'yes' : '',
			'blog_show_author_avatar' => thegem_get_option('search_layout_caption_author_avatar') == 1 ? 'yes' : '',
			'blog_show_readmore_button' => '',
			'by_text' => __('By', 'thegem'),
			'blog_show_comments' => '',
			'blog_show_likes' => '',
			'social_sharing' => '',
			'caption_container_alignment' => thegem_get_option('search_layout_caption_content_alignment_desktop'),
			'caption_container_alignment_tablet' => thegem_get_option('search_layout_caption_content_alignment_tablet'),
			'caption_container_alignment_mobile' => thegem_get_option('search_layout_caption_content_alignment_mobile'),
			'caption_container_preset' => thegem_get_option('search_layout_caption_container_preset'),
			'show_bottom_border' => thegem_get_option('search_layout_caption_bottom_border') == 1 ? 'yes' : '',
			'show_pagination' => thegem_get_option('search_layout_pagination') == 1 ? 'yes' : '',
			'items_per_page' => thegem_get_option('search_layout_pagination_items_per_page'),
			'pagination_type' => thegem_get_option('search_layout_pagination_type') == 'loadmore' ? 'more' : thegem_get_option('search_layout_pagination_type'),
			'more_button_text' => thegem_get_option('search_layout_load_more_text'),
			'more_icon_pack' => thegem_get_option('search_layout_load_more_icon_pack'),
			'more_icon_' . thegem_get_option('search_layout_load_more_icon_pack') => thegem_get_option('search_layout_load_more_icon'),
			'more_stretch_full_width' => thegem_get_option('search_layout_load_more_stretch') == 1 ? 'yes' : '',
			'more_show_separator' => (thegem_get_option('search_layout_load_more_stretch') != 1 && thegem_get_option('search_layout_load_more_separator') == 1) ? 'yes' : '',
			'load_more_spacing' => thegem_get_option('search_layout_load_more_spacing_desktop'),
			'load_more_spacing_tablet' => thegem_get_option('search_layout_load_more_spacing_tablet'),
			'load_more_spacing_mobile' => thegem_get_option('search_layout_load_more_spacing_mobile'),
			'pagination_more_button_type' => thegem_get_option('search_layout_load_more_btn_type'),
			'pagination_more_button_size' => thegem_get_option('search_layout_load_more_btn_size'),
			'mixed_grids_per_page' => thegem_get_option('search_layout_mixed_grids_items'),
			'mixed_grids_show_all_button_text' => thegem_get_option('search_layout_mixed_grids_show_all'),
			'mixed_grids_show_all_icon_pack' => thegem_get_option('search_layout_mixed_grids_show_all_icon_pack'),
			'mixed_grids_show_all_icon_' . thegem_get_option('search_layout_mixed_grids_show_all_icon_pack') => thegem_get_option('search_layout_mixed_grids_show_all_icon'),
			'mixed_grids_show_all_stretch_full_width' => thegem_get_option('search_layout_mixed_grids_show_all_stretch') == 1 ? 'yes' : '',
			'mixed_grids_show_all_show_separator' => (thegem_get_option('search_layout_mixed_grids_show_all_stretch') != 1 && thegem_get_option('search_layout_mixed_grids_show_all_separator') == 1) ? 'yes' : '',
			'mixed_grids_show_all_spacing' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_desktop'),
			'mixed_grids_show_all_spacing_tablet' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_tablet'),
			'mixed_grids_show_all_spacing_mobile' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_mobile'),
			'mixed_grids_show_all_button_type' => thegem_get_option('search_layout_mixed_grids_show_all_btn_type'),
			'mixed_grids_show_all_button_size' => thegem_get_option('search_layout_mixed_grids_show_all_btn_size'),
			'loading_animation' => thegem_get_option('search_layout_loading_animation') == 1 ? 'yes' : '',
			'animation_effect' => thegem_get_option('search_layout_animation_effect'),
			'ignore_highlights' => 'yes',
			'skeleton_loader' => thegem_get_option('search_layout_skeleton_loader') == 1 ? 'yes' : '',
			'fullwidth_section_images' => '',
			'title_weight' => '',
			'skin_source' => thegem_get_option('search_skin_source'),
			'loop_builder' => thegem_get_option('search_item_builder_template'),
			'equal_height' => !thegem_get_option('search_items_equal_height_disabled'),
			'search_page' => 1,
		);

		if($settings['skin_source'] === 'builder') {
			if(empty($settings['loop_builder'])) {
				echo '<div class="bordered-box centered-box styled-subtitle">'.esc_html__('Please select loop item template', 'thegem').'</div>';
				return ;
			}
			$settings['ignore_highlights'] = 'yes';
			$hover_effect = '';
			$equal_height = !empty($settings['equal_height']) && $settings['layout'] === 'justified';
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

		$localize = array(
			'data' => $settings,
			'action' => 'search_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_'. $grid_uid, $localize );
		$settings['action'] = 'search_grid_load_more';

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

		if ($settings['pagination_type'] == 'more' || $mixed_grid) {
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

		if ($mixed_grid) {
			$items_per_page = $settings['mixed_grids_per_page'] ? intval($settings['mixed_grids_per_page']) : 12;

			$page = 1;
			if (($key = array_search('product', $post_types_arr)) !== false) {
				unset($post_types_arr[$key]);
			}

			$args = array(
				'post_type' => $post_types_arr,
				'post_status' => 'publish',
				'paged' => $page,
				'posts_per_page' => $items_per_page,
			);

			$args['s'] = $settings['search'];

			$search_grid_loop = new WP_Query( $args );

			if (!$search_grid_loop->have_posts()) {
				return;
			}

			$max_page = $search_grid_loop->max_num_pages;
		} else {
			$items_per_page = $settings['items_per_page'] ? intval($settings['items_per_page']) : 12;

			$page = get_query_var('paged') ?: 1;

			global $wp_query;
			$max_page = $wp_query->max_num_pages;
		}

		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;

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
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
				.portfolio.news-grid .portfolio-item .post-type { text-align: '.$settings['caption_container_alignment'].'; }
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment'].'; }';
		}

		if (!empty($settings['caption_container_alignment_tablet'])) {
			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
				.portfolio.news-grid .portfolio-item .post-type { text-align: '.$settings['caption_container_alignment_tablet'].'; }
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item:hover .image .links .caption .grid-post-meta { justify-content: '.$settings['caption_container_alignment_tablet'].'; }
			}';
		}

		if (!empty($settings['caption_container_alignment_mobile'])) {
			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid.title-on-page .wrap > .caption,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content,
				.portfolio.news-grid.category-grid.title-on-hover .portfolio-item .image .links .caption .slide-content .description,
				.portfolio.news-grid .portfolio-item .post-type { text-align: '.$settings['caption_container_alignment_mobile'].'; }
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

		if (!empty($settings['mixed_grids_show_all_spacing'])) {
			$style .= '.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$settings['mixed_grids_show_all_spacing'].'px; }';
		}

		if (!empty($settings['mixed_grids_show_all_spacing_tablet'])) {
			$style .= '@media (max-width: 991px) {
				.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$settings['mixed_grids_show_all_spacing_tablet'].'px; }
			}';
		}

		if (!empty($settings['mixed_grids_show_all_spacing_mobile'])) {
			$style .= '@media (max-width: 767px) {
				.portfolio.news-grid.category-grid .mixed-show-all { margin-top: '.$settings['mixed_grids_show_all_spacing_mobile'].'px; }
			}';
		}

		if (isset($settings['image_size']) && $settings['image_size'] == 'full' && !empty($settings['image_ratio_full'])) {
			$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $settings['image_ratio_full'] . ' !important; height: auto; }';
		}

		if (isset($settings['image_size']) && $settings['image_size'] == 'default' && !empty($settings['image_ratio_default'])) {
			$style .= '.portfolio.news-grid.category-grid .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: ' . $settings['image_ratio_default'] . ' !important; height: auto; }';
		}

		echo '<style>'.$style.'</style>';

		if ($settings['columns_desktop'] == '100%' || (($settings['ignore_highlights'] !== 'yes' || $settings['layout'] !== 'justified') && $settings['skeleton_loader'] !== 'yes')) {
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="preloader-spin"></div></div>');
		} else if ($settings['skeleton_loader'] == 'yes') { ?>
			<div class="preloader save-space">
				<div class="skeleton">
					<div class="skeleton-posts portfolio-row">
						<?php
						if ($mixed_grid) {
							while ($search_grid_loop->have_posts()) : $search_grid_loop->the_post();
								echo thegem_extended_blog_render_item($settings, $item_classes);
							endwhile;
						} else {
							while (have_posts()) : the_post();
								echo thegem_extended_blog_render_item($settings, $item_classes);
							endwhile;
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
				(!empty($equal_height) ? 'loop-equal-height' : ''),
			];
			?>

			<div class="<?php echo implode(" ", $blog_wrap_class); ?>"
				 data-portfolio-uid="<?php echo esc_attr($grid_uid); ?>"
				 data-current-page="<?php echo esc_attr($page); ?>"
				 data-per-page="<?php echo esc_attr($items_per_page); ?>"
				 data-next-page="<?php echo esc_attr($next_page); ?>"
				 data-pages-count="<?php echo esc_attr($max_page); ?>"
				 data-hover="<?php echo esc_attr($hover_effect); ?>"
				 data-search="<?php echo esc_attr(get_search_query()); ?>"
				 data-post-types="<?php echo esc_attr(json_encode($post_types_arr)); ?>">
				<?php if (!$mixed_grid && $settings['blog_show_sorting'] == 'yes'): ?>
					<div class="portfolio-top-panel<?php if ($settings['columns_desktop'] == '100%'): ?> fullwidth-block<?php endif; ?>">
						<div class="portfolio-top-panel-row">
							<div class="portfolio-top-panel-left"></div>
							<div class="portfolio-top-panel-right">
								<div class="portfolio-sorting title-h6">
									<div class="orderby light">
										<?php
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
										$url_result_order = add_query_arg($query); ?>

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
							if ($mixed_grid) {
								while ($search_grid_loop->have_posts()) : $search_grid_loop->the_post();
									echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID());
								endwhile;
							} else {
								while (have_posts()) : the_post();
									echo thegem_extended_blog_render_item($settings, $item_classes, $thegem_sizes, get_the_ID());
								endwhile;
							} ?>
						</div><!-- .portflio-set -->
						<?php if ($settings['columns_desktop'] != '1x'): ?>
							<div class="portfolio-item-size-container">
								<?php echo thegem_extended_blog_render_item($settings, $item_classes); ?>
							</div>
						<?php endif; ?>
					</div><!-- .row-->
					<?php
					if (!$mixed_grid && $settings['show_pagination'] == 'yes') {
						if ($settings['pagination_type'] == 'normal') {
							thegem_pagination();
						} else if ($settings['pagination_type'] == 'more' && $next_page > 0) {

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
						} else if ($settings['pagination_type'] == 'scroll' && $next_page > 0) { ?>
							<div class="portfolio-scroll-pagination"></div>
						<?php }
					}
					if ($mixed_grid) {
						$separator_enabled = !empty($settings['mixed_grids_show_all_show_separator']) ? true : false;

						// Container
						$classes_container = 'gem-button-container gem-widget-button ';

						if ($separator_enabled) {
							$classes_container .= 'gem-button-position-center gem-button-with-separator ';
						} else {
							if ('yes' === $settings['mixed_grids_show_all_stretch_full_width']) {
								$classes_container .= 'gem-button-position-fullwidth ';
							} else {
								$classes_container .= 'gem-button-position-center ';
							}
						}

						// Separator
						$classes_separator = 'gem-button-separator gem-button-separator-type-single'; ?>

						<div class="mixed-show-all">
							<div class="inner">

								<div class="<?php echo esc_attr($classes_container); ?>">
									<?php if ($separator_enabled) { ?>
									<div class="<?php echo esc_attr($classes_separator); ?>">
										<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
											<div class="gem-button-separator-line"></div>
										</div>
										<div class="gem-button-separator-button">
											<?php } ?>

											<a href="<?php echo home_url(); ?>?s=<?php echo esc_attr($settings['search']); ?>" class="load-more-button gem-button gem-button-size-<?php echo $settings['mixed_grids_show_all_button_size']; ?> gem-button-style-<?php echo $settings['mixed_grids_show_all_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
												<span class="gem-inner-wrapper-btn">
													<?php if (isset($settings['mixed_grids_show_all_icon_pack']) && $settings['mixed_grids_show_all_icon_' . $settings['mixed_grids_show_all_icon_pack']] != '') {
														echo thegem_build_icon($settings['mixed_grids_show_all_icon_pack'], $settings['mixed_grids_show_all_icon_' . $settings['mixed_grids_show_all_icon_pack']]);
													} ?>
													<span class="gem-text-button">
														<?php echo '<span>' . wp_kses($settings['mixed_grids_show_all_button_text'], 'post') . '</span>'; ?>
													</span>
												</span>
											</a>

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
					} ?>
				</div><!-- .full-width -->
			</div><!-- .portfolio-->
		</div><!-- .portfolio-preloader-wrapper-->

		<?php
	}
}

if (!function_exists('thegem_blog_archive_template')) {
	function thegem_blog_archive_template() {
		if(!function_exists('thegem_get_template_type') || !((thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_home() || is_category() || is_tag() || is_tax() || is_author() || is_date() || is_post_type_archive( 'post' ))) || (function_exists('is_product_taxonomy') && is_product_taxonomy())) return false;
		$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : 0;
		$blog_archive_data = thegem_get_output_blog_archive_data($term_id);
		if(is_tax()) {
			$term_archive_data = thegem_get_output_cpt_archive_data($term_id, 'post');
			if($term_archive_data['archive_layout_source'] === 'builder') {
				$blog_archive_data['blog_archive_layout_source'] = $term_archive_data['archive_layout_source'];
				$blog_archive_data['blog_archive_builder_template'] = $term_archive_data['archive_builder_template'];
			}
		}
		if($blog_archive_data['blog_archive_layout_source'] != 'builder') return false;
		$template_id = intval($blog_archive_data['blog_archive_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return $template_id;
		}
		return false;
	}
}

if (!function_exists('thegem_cpt_archive_template')) {
	function thegem_cpt_archive_template() {
		if(!function_exists('thegem_get_template_type') || !(thegem_get_template_type( get_the_ID() ) === 'blog-archive' || is_tax() || is_post_type_archive()) || is_search()) return false;
		$term_id = is_tax() ? get_queried_object()->term_id : 0;
		$post_type_name = is_post_type_archive() ? get_queried_object()->name  : 0;
		$cpt_archive_data = thegem_get_output_cpt_archive_data($term_id, $post_type_name);
		if($cpt_archive_data['archive_layout_source'] != 'builder') return false;
		$template_id = intval($cpt_archive_data['archive_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return apply_filters( 'wpml_object_id', $template_id, 'post' );
		}
		return false;
	}
}

if (!function_exists('thegem_search_template')) {
	function thegem_search_template() {
		if(!function_exists('thegem_get_template_type') || !is_search()) return false;
		$search_data = array(
			'search_layout_source' => thegem_get_option('search_layout_source'),
			'search_builder_template' => thegem_get_option('search_builder_template'),
		);

		if($search_data['search_layout_source'] != 'builder') return false;
		$template_id = intval($search_data['search_builder_template']);
		if($template_id < 1) return false;
		$template = get_post($template_id);
		if($template && thegem_get_template_type($template_id) == 'blog-archive') {
			return $template_id;
		}
		return false;
	}
}

function thegem_search_after_products_content() {
	$post_types_arr = thegem_get_search_post_types_array();
	if (($key = array_search('product', $post_types_arr)) !== false) {
		unset($post_types_arr[$key]);
	}
	$mixed_grids_per_page = thegem_get_option('search_layout_mixed_grids_items');
	$items_per_page = $mixed_grids_per_page ? intval($mixed_grids_per_page) : 12;
	$args = array(
		'post_type' => $post_types_arr,
		'post_status' => 'publish',
		'paged' => 1,
		'posts_per_page' => $items_per_page,
		's' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
	);
	$search_grid_loop = new WP_Query( $args );

	$search_template_id = thegem_search_template();
	if ( $search_template_id && defined('ELEMENTOR_VERSION') && $search_grid_loop->have_posts()) { ?>
		<div class="container"><?php echo '<h3 class="light search-grid-title"><span>' . thegem_get_option('search_layout_mixed_grids_title') . '</span></h3>'; ?></div>
		<div class="fullwidth-content">
			<div class="thegem-template-wrapper thegem-template-blog-archive thegem-template-<?php echo esc_attr($search_template_id); ?>">
				<?php
					$GLOBALS['thegem_template_type'] = 'blog-archive';
					echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $search_template_id );
					unset( $GLOBALS['thegem_template_type'] );
				?>
			</div>
		</div><!-- .container -->
	<?php } elseif($search_grid_loop->have_posts()) { ?>

		<div class="container">
			<?php echo '<h3 class="light search-grid-title"><span>' . thegem_get_option('search_layout_mixed_grids_title') . '</span></h3>'; ?>
			<div class="panel row'">
				<div class="panel-center col-xs-12'">
				<?php
					if ( $search_grid_loop->have_posts() ) {

						if (thegem_get_option('search_layout_type') == 'grid') {
							thegem_search_grid_content(true);
						} elseif(thegem_get_option('search_layout_type') == 'list') {

							$params = array(
								'skin_source' => thegem_get_option('search_skin_source'),
								'loop_builder' => thegem_get_option('search_item_builder_template'),
								'gaps_desktop' => thegem_get_option('search_list_builder_gaps_desktop'),
								'gaps_tablet' => thegem_get_option('search_list_builder_gaps_tablet'),
								'search' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
								'mixed_grids_show_all_button_text' => thegem_get_option('search_layout_mixed_grids_show_all'),
								'mixed_grids_show_all_icon_pack' => thegem_get_option('search_layout_mixed_grids_show_all_icon_pack'),
								'mixed_grids_show_all_icon_' . thegem_get_option('search_layout_mixed_grids_show_all_icon_pack') => thegem_get_option('search_layout_mixed_grids_show_all_icon'),
								'mixed_grids_show_all_stretch_full_width' => thegem_get_option('search_layout_mixed_grids_show_all_stretch'),
								'mixed_grids_show_all_show_separator' => (thegem_get_option('search_layout_mixed_grids_show_all_stretch') != 1 && thegem_get_option('search_layout_mixed_grids_show_all_separator') == 1) ? '1' : '',
								'mixed_grids_show_all_spacing' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_desktop'),
								'mixed_grids_show_all_spacing_tablet' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_tablet'),
								'mixed_grids_show_all_spacing_mobile' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_mobile'),
								'mixed_grids_show_all_button_type' => thegem_get_option('search_layout_mixed_grids_show_all_btn_type'),
								'mixed_grids_show_all_button_size' => thegem_get_option('search_layout_mixed_grids_show_all_btn_size'),
							);
							if (!is_singular()) {
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

							while ($search_grid_loop->have_posts()) : $search_grid_loop->the_post();

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

							if (!is_singular()) {
								$separator_enabled = !empty($params['mixed_grids_show_all_show_separator']) ? true : false;

								// Container
								$classes_container = 'gem-button-container gem-widget-button ';

								if ($separator_enabled) {
									$classes_container .= 'gem-button-position-center gem-button-with-separator ';
								} else {
									if ($params['mixed_grids_show_all_stretch_full_width']) {
										$classes_container .= 'gem-button-position-fullwidth ';
									} else {
										$classes_container .= 'gem-button-position-center ';
									}
								}

								// Separator
								$classes_separator = 'gem-button-separator gem-button-separator-type-single'; ?>

								<div class="mixed-show-all">
									<div class="inner">

										<div class="<?php echo esc_attr($classes_container); ?>">
											<?php if ($separator_enabled) { ?>
											<div class="<?php echo esc_attr($classes_separator); ?>">
												<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
													<div class="gem-button-separator-line"></div>
												</div>
												<div class="gem-button-separator-button">
													<?php } ?>

													<a href="<?php echo home_url(); ?>?s=<?php echo esc_attr($params['search']); ?>" class="load-more-button gem-button gem-button-size-<?php echo $params['mixed_grids_show_all_button_size']; ?> gem-button-style-<?php echo $params['mixed_grids_show_all_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
														<span class="gem-inner-wrapper-btn">
															<?php if (isset($params['mixed_grids_show_all_icon_pack']) && $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']] != '') {
																echo thegem_build_icon($params['mixed_grids_show_all_icon_pack'], $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']]);
															} ?>
															<span class="gem-text-button">
																<?php echo '<span>' . wp_kses($params['mixed_grids_show_all_button_text'], 'post') . '</span>'; ?>
															</span>
														</span>
													</a>

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
								echo '</div>';
							}

						} else {
							if(!is_singular()) {
								$blog_style = '3x';
								$params = array(
									'hide_author' => false,
									'hide_date' => true,
									'hide_comments' => true,
									'hide_likes' => true,
									'hide_social_sharing' => true,
									'search' => empty(get_search_query()) ? get_query_var('p') : get_search_query(),
									'mixed_grids_show_all_button_text' => thegem_get_option('search_layout_mixed_grids_show_all'),
									'mixed_grids_show_all_icon_pack' => thegem_get_option('search_layout_mixed_grids_show_all_icon_pack'),
									'mixed_grids_show_all_icon_' . thegem_get_option('search_layout_mixed_grids_show_all_icon_pack') => thegem_get_option('search_layout_mixed_grids_show_all_icon'),
									'mixed_grids_show_all_stretch_full_width' => thegem_get_option('search_layout_mixed_grids_show_all_stretch'),
									'mixed_grids_show_all_show_separator' => (thegem_get_option('search_layout_mixed_grids_show_all_stretch') != 1 && thegem_get_option('search_layout_mixed_grids_show_all_separator') == 1) ? '1' : '',
									'mixed_grids_show_all_spacing' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_desktop'),
									'mixed_grids_show_all_spacing_tablet' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_tablet'),
									'mixed_grids_show_all_spacing_mobile' => thegem_get_option('search_layout_mixed_grids_show_all_spacing_mobile'),
									'mixed_grids_show_all_button_type' => thegem_get_option('search_layout_mixed_grids_show_all_btn_type'),
									'mixed_grids_show_all_button_size' => thegem_get_option('search_layout_mixed_grids_show_all_btn_size'),
								);
								wp_enqueue_style('thegem-blog');
								wp_enqueue_style('thegem-additional-blog');
								wp_enqueue_style('thegem-animations');
								wp_enqueue_script('thegem-blog-isotope');
								echo '<div class="preloader"><div class="preloader-spin"></div></div>';
								echo '<div class="blog blog-style-3x blog-style-masonry">';
							}

							while ( $search_grid_loop->have_posts() ) : $search_grid_loop->the_post();
								include(locate_template(array('gem-templates/blog/content-blog-item-masonry.php', 'content-blog-item.php')));
							endwhile;

							if(!is_singular()) {
								echo '</div>';
								$separator_enabled = !empty($params['mixed_grids_show_all_show_separator']) ? true : false;

								// Container
								$classes_container = 'gem-button-container gem-widget-button ';

								if ($separator_enabled) {
									$classes_container .= 'gem-button-position-center gem-button-with-separator ';
								} else {
									if ($params['mixed_grids_show_all_stretch_full_width']) {
										$classes_container .= 'gem-button-position-fullwidth ';
									} else {
										$classes_container .= 'gem-button-position-center ';
									}
								}

								// Separator
								$classes_separator = 'gem-button-separator gem-button-separator-type-single'; ?>

								<div class="mixed-show-all">
									<div class="inner">

										<div class="<?php echo esc_attr($classes_container); ?>">
											<?php if ($separator_enabled) { ?>
											<div class="<?php echo esc_attr($classes_separator); ?>">
												<div class="<?php echo esc_attr('gem-button-separator-holder') ?>">
													<div class="gem-button-separator-line"></div>
												</div>
												<div class="gem-button-separator-button">
													<?php } ?>

													<a href="<?php echo home_url(); ?>?s=<?php echo esc_attr($params['search']); ?>" class="load-more-button gem-button gem-button-size-<?php echo $params['mixed_grids_show_all_button_size']; ?> gem-button-style-<?php echo $params['mixed_grids_show_all_button_type']; ?> gem-button-icon-position-left gem-button-text-weight-normal">
														<span class="gem-inner-wrapper-btn">
															<?php if (isset($params['mixed_grids_show_all_icon_pack']) && $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']] != '') {
																echo thegem_build_icon($params['mixed_grids_show_all_icon_pack'], $params['mixed_grids_show_all_icon_' . $params['mixed_grids_show_all_icon_pack']]);
															} ?>
															<span class="gem-text-button">
																<?php echo '<span>' . wp_kses($params['mixed_grids_show_all_button_text'], 'post') . '</span>'; ?>
															</span>
														</span>
													</a>

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
							}
						}

					} else {
						get_template_part( 'content', 'none' );
					}
				?>
				</div>
			</div>
		</div><!-- .container -->
<?php }
}
