<?php
if (!function_exists('get_post_type_meta_values')) {
	function get_post_type_meta_values($meta_key = '', $post_type = 'thegem_pf_item', $post_status = 'publish') {
		global $wpdb;

		if (empty($meta_key)) {
			return [];
		}

		$posts = get_posts(
			array(
				'post_type' => $post_type,
				'meta_key' => $meta_key,
				'posts_per_page' => -1,
				'fields' => 'ids',
				'post_status' => $post_status,
				'suppress_filters' => false,
			)
		);
		$posts = implode(',', $posts);
		if ($posts) {
			$meta_values = $wpdb->get_col($wpdb->prepare("
			SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
			WHERE meta_key = %s
			AND post_id IN ($posts)
		", $meta_key));
			return array_unique($meta_values);
		} else {
			return [];
		}
	}
}

if (!function_exists('thegem_print_attributes_list')) {
	function thegem_print_attributes_list($terms, $item, $attribute_name, $attributes_url, $is_child = false, $collapsed = false) {
		if ($is_child) { ?>
			<ul<?php if ($collapsed) { ?> style="display: none" <?php } ?>>
		<?php }
		$keys = array_keys($terms);
		$simple_arr = $keys == array_keys($keys);
		foreach ($terms as $key => $term) {
			$term_slug = isset($term->slug) ? $term->slug : ($simple_arr ? $term : $key);
			$term_title = isset($term->name) ? $term->name : $term;
			if (empty($term_slug) || empty($term_title)) continue;
			if ($item['attribute_type'] == 'taxonomies' && $item['attribute_taxonomies_hierarchy'] == 'yes') {
				$child_terms = get_terms([
					'taxonomy' => $item['attribute_taxonomies'],
					'orderby' => $item['attribute_order_by'],
					'parent' => $term->term_id,
				]);
				if ($item['attribute_taxonomies_collapsible'] == 'yes') {
					$collapsed = true;
					if (isset($attributes_url[$attribute_name])) {
						foreach ($attributes_url[$attribute_name] as $slug) {
							$active_cat_term = get_term_by('slug', $slug, str_replace("tax_","", $attribute_name));
							if ($term->term_id == $active_cat_term->term_id || term_is_ancestor_of($term->term_id, $active_cat_term->term_id, str_replace("tax_","", $attribute_name))) {
								$collapsed = false;
							}
						}
					}
				}
			} ?>
			<li>
				<?php if ($item['attribute_type'] == 'taxonomies' && $item['attribute_taxonomies_click_behavior'] == 'archive_link') { ?>
					<a href="<?php echo get_term_link($term->slug, $item['attribute_taxonomies']); ?>"
					   class="<?php echo isset($attributes_url[$attribute_name]) && in_array($term_slug, $attributes_url[$attribute_name]) ? 'active' : '';
						echo $collapsed ? ' collapsed' : ''; ?>">
						<span class="title"><?php echo esc_html($term_title); ?></span>
					</a>
				<?php } else { ?>
					<a href="#"
					   data-filter-type="<?php echo esc_attr($item['attribute_type']); ?>"
					   data-attr="<?php echo esc_attr($attribute_name); ?>"
					   data-filter="<?php echo esc_attr($term_slug); ?>"
					   class="<?php echo isset($attributes_url[$attribute_name]) && in_array($term_slug, $attributes_url[$attribute_name]) ? 'active' : '';
						echo $collapsed ? ' collapsed' : ''; ?>"
					   rel="nofollow">
						<?php if ($item['attribute_query_type'] == 'or') {
							echo '<span class="check"></span>';
						} ?>
						<span class="title"><?php echo esc_html($term_title); ?></span>
						<?php if (!empty($child_terms) && $item['attribute_taxonomies_collapsible'] == 'yes') { ?>
							<span class="filters-collapsible-arrow"></span>
						<?php } ?>
					</a>
				<?php }

				if (!empty($child_terms)) {
					thegem_print_attributes_list($child_terms, $item, $attribute_name, $attributes_url, true, $collapsed);
				} ?>
			</li>
		<?php }
		if ($is_child) {
			echo '</ul>';
		}
	}
}

ob_start();
$filter_attr = $settings['repeater_attributes'];
$filter_attr_numeric = [];

foreach ($filter_attr as $index => $item) {
	$terms = false;
	$attributes_url = $portfolios_filters_meta_url;
	if ($item['attribute_type'] == 'taxonomies') {
		if (empty($item['attribute_taxonomies']) || !array_key_exists($item['attribute_taxonomies'], $taxonomies_list)) continue;
		if (isset($taxonomy_filter[$item['attribute_taxonomies']])) {
			$terms = $taxonomy_filter[$item['attribute_taxonomies']];
			foreach ($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, $item['attribute_taxonomies'] );
				if (!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$term_args = [
				'taxonomy' => $item['attribute_taxonomies'],
				'orderby' => $item['attribute_order_by'],
			];
			if ($item['attribute_taxonomies_hierarchy'] == 'yes') {
				$term_args['parent'] = 0;
			}
			$terms = get_terms($term_args);
		}
		$attribute_name = 'tax_' . $item['attribute_taxonomies'];
		$attributes_url = $portfolios_filters_tax_url;
	} else {
		if ($item['attribute_type'] == 'details') {
			$attribute_name = $item['attribute_details'];
		} else if ($item['attribute_type'] == 'custom_fields') {
			$attribute_name = $item['attribute_custom_fields'];
		} else {
			$attribute_name = $item['attribute_custom_fields_acf_' . $item['attribute_type']];
			$group_fields = class_exists( 'ACF' ) ? acf_get_fields($item['attribute_type']) : array();
			$found_key = array_search($attribute_name, array_column($group_fields, 'name'));
			$checkbox_field = get_field_object($group_fields[$found_key]['key']);
			if (isset($checkbox_field['choices'])) {
				$terms = $checkbox_field['choices'];
				if ($checkbox_field['type'] == 'checkbox') {
					$attribute_name .= '__check';
				}
			}
			$item['attribute_type'] = 'acf_fields';
		}
		if (empty($attribute_name) || !array_key_exists(str_replace('__check', '', $attribute_name), $meta_list)) continue;
		if (empty($terms)) {
			$terms = get_post_type_meta_values($attribute_name, $post_type);
		}
		$attribute_name = 'meta_' . $attribute_name;
	}
	if (!empty($terms) && !is_wp_error($terms)) {
		$is_dropdown = $settings['filters_style'] !== 'standard' && isset($item['attribute_display_type']) && $item['attribute_display_type'] == 'dropdown';
		if ($item['attribute_type'] != 'taxonomies' && $item['attribute_meta_type'] == 'number') {
			wp_enqueue_script('jquery-touch-punch');
			wp_enqueue_script('jquery-ui-slider');
			$terms = array_map('floatval', $terms);
			$filter_attr_numeric[$attribute_name] = $item; ?>
			<div class="portfolio-filter-item price<?php
				echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
				<?php if ((isset($item['show_title']) && $item['show_title'] == 'yes' && !empty($item['attribute_title'])) || (!$is_dropdown && $settings['filters_style'] == 'standard')) { ?>
					<h4 class="name widget-title">
						<span class="widget-title-by">
							<?php echo esc_html($settings['filter_buttons_hidden_filter_by_text']); ?>
						</span>
						<?php echo esc_html($item['attribute_title']); ?>
						<span class="widget-title-arrow"></span>
					</h4>
				<?php } ?>
				<?php if ($is_dropdown) { ?>
					<div class="dropdown-selector">
						<div class="selector-title">
							<span class="name">
								<?php if (!isset($item['show_title']) || $item['show_title'] !== 'yes') { ?>
									<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
								<?php } ?>
								<span class="slider-amount-value"></span>
							</span>
							<span class="widget-title-arrow"></span>
						</div>
				<?php } ?>
						<div class="portfolio-filter-item-list">
							<div class="price-range-slider" data-title="<?php echo esc_attr($item['attribute_title']); ?>">
								<div class="slider-range"
									 data-attr="<?php echo esc_attr($attribute_name); ?>"
									 data-min="<?php echo esc_attr(min($terms)); ?>"
									 data-max="<?php echo esc_attr(max($terms)); ?>"
									 data-prefix="<?php echo esc_attr($item['attribute_price_format_prefix']); ?>"
									 data-suffix="<?php echo esc_attr($item['attribute_price_format_suffix']); ?>"
									 <?php if ($item['attribute_price_format'] != 'disabled') { ?>data-locale="<?php echo esc_attr($item['attribute_price_format'] == 'wp_locale' ? get_locale() : $item['attribute_price_format_locale']); ?>"<?php }?>></div>
								<div class="slider-amount">
									<span class="slider-amount-text"><?php echo esc_html($item['attribute_title']); ?>: </span>
									<span class="slider-amount-value"></span>
								</div>
							</div>
						</div>
				<?php if ($is_dropdown) { ?>
					</div>
				<?php } ?>
			</div>
		<?php } else {
			$keys = array_keys($terms);
			$simple_arr = $keys == array_keys($keys);
			if ($item['attribute_order_by'] == 'name') {
				if ($simple_arr) {
					sort($terms);
				} else {
					asort($terms);
				}
			} ?>
			<div class="portfolio-filter-item attribute <?php
			echo esc_attr($attribute_name);
			echo strtolower($item['attribute_query_type']) == 'and' ? ' multiple' : ' single';
			echo $is_dropdown ? ' display-type-dropdown open-dropdown-' . $item['attribute_display_dropdown_open'] : ''; ?>">
				<?php if ((isset($item['show_title']) && $item['show_title'] == 'yes' && !empty($item['attribute_title'])) || (!$is_dropdown && $settings['filters_style'] == 'standard')) { ?>
					<h4 class="name widget-title">
						<span class="widget-title-by">
							<?php echo esc_html($settings['filter_buttons_hidden_filter_by_text']); ?>
						</span>
						<?php echo esc_html($item['attribute_title']); ?>
						<span class="widget-title-arrow"></span>
					</h4>
				<?php } ?>
				<?php if ($is_dropdown) { ?>
					<div class="dropdown-selector">
						<div class="selector-title">
							<?php $title = (!isset($item['show_title']) || $item['show_title'] !== 'yes') ? $item['attribute_title'] : str_replace('%ATTR%', $item['attribute_title'], $settings['filters_text_labels_all_text']); ?>
								<span class="name" data-title="<?php echo esc_attr($title); ?>">
								<?php if (!isset($attributes_url[$attribute_name])) { ?>
									<span data-filter="*"><?php echo esc_html($title); ?></span>
								<?php } else {
									foreach ($terms as $key => $term) {
										$term_slug = isset($term->slug) ? $term->slug : ($simple_arr ? $term : $key);
										$term_title = isset($term->name) ? $term->name : $term;
										if (in_array($term_slug, $attributes_url[$attribute_name])) {
											echo '<span data-filter="' . $term_slug . '">' . $term_title . '<span class="separator">, </span></span>';
										}
									}
								} ?>
							</span>
							<span class="widget-title-arrow"></span>
						</div>
				<?php } ?>
						<div class="portfolio-filter-item-list">
					<ul>
						<li>
							<a href="#"
							   data-filter-type="<?php echo esc_attr($item['attribute_type']); ?>"
							   data-attr="<?php echo esc_attr($attribute_name); ?>"
							   data-filter="*"
							   data-title="<?php echo esc_attr($item['attribute_title']); ?>"
							   class="all <?php echo !isset($attributes_url[$attribute_name]) ? 'active' : '' ?>"
							   rel="nofollow">
								<?php if ($item['attribute_query_type'] == 'or') {
									echo '<span class="check"></span>';
								} ?>
								<span class="title"><?php echo $settings['filters_text_labels_all_text']; ?></span>
							</a>
						</li>
						<?php thegem_print_attributes_list($terms, $item, $attribute_name, $attributes_url); ?>
					</ul>
				</div>
				<?php if ($is_dropdown) { ?>
					</div>
				<?php } ?>
			</div>
		<?php }
	}
}

$filters_list = ob_get_clean();
if (!empty($filters_list) || $settings['show_search'] == 'yes') { ?>
<div class="portfolio-filters-list style-<?php echo esc_attr($settings['filters_style']); ?> <?php echo $settings['filters_scroll_top'] == 'yes' ? 'scroll-top' : ''; ?> <?php echo $has_right_panel ? 'has-right-panel' : ''; ?>">
	<div class="portfolio-show-filters-button with-icon">
		<?php echo esc_html($settings['filter_buttons_hidden_show_text']); ?>
		<?php if ($settings['filter_buttons_hidden_show_icon'] == 'yes') { ?>
			<span class="portfolio-show-filters-button-icon"></span>
		<?php } ?>
	</div>

	<div class="portfolio-filters-outer">
		<div class="portfolio-filters-area">
			<div class="portfolio-filters-area-scrollable">
				<h2 class="light"><?php echo esc_html($settings['filter_buttons_hidden_sidebar_title']); ?></h2>
				<div class="widget-area-wrap">
					<div class="portfolio-filters-extended widget-area">
						<?php if ($settings['show_search'] == 'yes') { ?>
						<div class="portfolio-filter-item with-search-filter">
							<form class="portfolio-search-filter<?php
							echo isset($settings['live_search']) && $settings['live_search'] == 'yes' ? ' live-search' : '';
							echo $settings['search_reset_filters'] == 'yes' ? ' reset-filters' : ''; ?>"
								role="search" action="">
								<div class="portfolio-search-filter-form">
									<input type="search"
										   placeholder="<?php echo esc_attr($settings['filters_text_labels_search_text']); ?>"
										   value="<?php echo esc_attr($search_current); ?>"
										   aria-label="<?php esc_attr_e('Search', 'thegem'); ?>">
								</div>
								<div class="portfolio-search-filter-button" role="button" aria-label="<?php esc_attr_e('Search', 'thegem'); ?>"></div>
							</form>
						</div>
						<?php }

						echo $filters_list;

						$preset_path = __DIR__ . '/selected-filters.php';
						if (!empty($preset_path) && file_exists($preset_path)) {
							include($preset_path);
						} ?>
					</div>
				</div>
			</div>
		</div>
		<div class="portfolio-close-filters"></div>
	</div>
</div>
<?php }
