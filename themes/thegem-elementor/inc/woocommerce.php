<?php

function thegem_woocommerce_scripts() {
	global $thegem_product_data;

	if(defined( 'WC_PLUGIN_FILE' )) {

		if(is_product() && empty($thegem_product_data)) {
			$thegem_page_id = get_the_ID();
			$thegem_product_data = thegem_get_output_product_page_data($thegem_page_id);
		}

		wp_enqueue_style('thegem-woocommerce-minicart', THEGEM_THEME_URI . '/css/thegem-woocommerce-minicart.css', array(), THEGEM_THEME_VERSION);

		wp_register_style('thegem-woocommerce', THEGEM_THEME_URI . '/css/thegem-woocommerce.css', array(), THEGEM_THEME_VERSION);
		wp_register_style('thegem-woocommerce1', THEGEM_THEME_URI . '/css/thegem-woocommerce1.css', array(), THEGEM_THEME_VERSION);
		wp_register_style('thegem-woocommerce-temp', THEGEM_THEME_URI . '/css/thegem-woocommerce-temp.css', array(), THEGEM_THEME_VERSION);
		wp_register_style('thegem-woocommerce-custom', THEGEM_THEME_URI . '/css/thegem-woocommerce-custom.css', array(), THEGEM_THEME_VERSION);
		wp_register_style('thegem-woocommerce-sidebar-swatches', THEGEM_THEME_URI . '/css/woocommerce-sidebar-swatches.css', array(), THEGEM_THEME_VERSION);

		if (thegem_is_woocommerce_page() || thegem_is_wishlist_page() || (function_exists('thegem_get_template_type') && (thegem_get_template_type(get_the_ID()) === 'single-product' || thegem_get_template_type(get_the_ID()) === 'cart' || thegem_get_template_type(get_the_ID()) === 'checkout'))) {
			thegem_enqueue_woocommerce_styles();
		}
		if ( thegem_is_wishlist_page()) {
			wp_enqueue_style('yith-wcwl-user-main');
		}

		wp_register_script('thegem-checkout', THEGEM_THEME_URI . '/js/thegem-checkout.js', array('jquery'), THEGEM_THEME_VERSION);
		wp_register_script('thegem-product-legacy-gallery', THEGEM_THEME_URI . '/js/thegem-product-legacy-gallery.js', array('jquery', 'jquery-carouFredSel'), THEGEM_THEME_VERSION, true);
		wp_register_script('thegem-woocommerce', THEGEM_THEME_URI . '/js/thegem-woocommerce.js', array('jquery'), THEGEM_THEME_VERSION, true);

		if (is_product() && $thegem_product_data['product_page_layout'] != 'legacy' && $thegem_product_data['product_layout_source'] != 'builder'){
			$productPageScripts = array(
				$thegem_product_data['product_page_layout_sticky'] ? 'thegem-sticky' : 'jquery',
			);

			wp_register_script('thegem-product-page', THEGEM_THEME_URI . '/js/thegem-product-page.js', $productPageScripts, THEGEM_THEME_VERSION, true);
			wp_register_style('thegem-product-page', THEGEM_THEME_URI . '/css/thegem-product-page.css', array('thegem-woocommerce'), THEGEM_THEME_VERSION);

			wp_enqueue_style('thegem-product-page');
			wp_enqueue_script('thegem-product-page');
		}

		wp_register_script('thegem-custom-product', THEGEM_THEME_URI . '/js/thegem-custom-product.js', array('jquery'), THEGEM_THEME_VERSION, true);
		wp_register_style('thegem-custom-product', THEGEM_THEME_URI . '/css/thegem-custom-product.css', array(), THEGEM_THEME_VERSION);
		if (($product_template_id = thegem_single_product_template()) || (function_exists('thegem_get_template_type') && thegem_get_template_type(get_the_ID()) === 'single-product')){
			wp_enqueue_style('thegem-custom-product');
			wp_enqueue_script('thegem-custom-product');
			thegem_enqueue_template_styles($product_template_id);
		}

		if (is_product() && !empty($thegem_product_data['product_page_ajax_add_to_cart'])) {
			wp_enqueue_script( 'wc-add-to-cart' );
		}

		wp_register_script('thegem-quick-view', THEGEM_THEME_URI . '/js/thegem-quick-view.js', array('jquery'), THEGEM_THEME_VERSION, true);
		wp_register_style('thegem-quick-view', THEGEM_THEME_URI . '/css/thegem-quick-view.css', array(), THEGEM_THEME_VERSION);

		wp_register_script('thegem-product-gallery', THEGEM_THEME_URI . '/js/thegem-product-gallery.js', array('jquery', 'owl', 'owl-zoom'), THEGEM_THEME_VERSION, true);
		wp_register_script('thegem-product-gallery-grid', THEGEM_THEME_URI . '/js/thegem-product-gallery-grid.js', array('jquery', 'thegem-sticky', 'owl-zoom'), THEGEM_THEME_VERSION, true);
		wp_register_style('thegem-product-gallery', THEGEM_THEME_URI . '/css/thegem-product-gallery.css', array('owl'), THEGEM_THEME_VERSION);

		if ( is_product() ) {
			wp_enqueue_style('thegem-product-gallery');
		}

		$galleryScripts = array(
			'jquery', 'thegem-woocommerce', thegem_get_option('product_gallery') != 'legacy' ? 'thegem-product-gallery' : 'thegem-product-legacy-gallery',
		);

		wp_register_script('thegem-product-quick-view', THEGEM_THEME_URI . '/js/thegem-product-quick-view.js', $galleryScripts, THEGEM_THEME_VERSION, true);

		wp_localize_script('thegem-woocommerce', 'thegem_woo_data', array(
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => wp_create_nonce('product_quick_view_ajax_security'),
		));
		if(is_woocommerce()) {
			wp_enqueue_script('thegem-woocommerce');

			if (thegem_get_option('products_pagination', 'normal') == 'scroll') {
				wp_enqueue_script('thegem-scroll-monitor');
			}

			if (in_array(thegem_get_option('products_pagination', 'normal'), array('more', 'scroll'))) {
				wp_enqueue_style('thegem-animations');
				wp_enqueue_script('thegem-items-animations');
			}
		}

		if (defined('THEGEM_ELEMENTOR_URL')) {
			wp_register_style('thegem-accordion', THEGEM_ELEMENTOR_URL . '/widgets/accordion/assets/css/thegem-accordion.css', array(), null);
			wp_register_script('thegem-tta', THEGEM_ELEMENTOR_URL . '/widgets/accordion/assets/js/thegem-tta.js', array('jquery'), null, true);
			wp_register_script('thegem-accordion', THEGEM_ELEMENTOR_URL . '/widgets/accordion/assets/js/thegem-accordion.js', array('thegem-tta'), null, true);
			wp_register_style('thegem-tabs', THEGEM_ELEMENTOR_URL . '/widgets/tabs-tours/assets/css/thegem-tabs.css', array('thegem-accordion'), null);
			wp_register_script('thegem-tabs', THEGEM_ELEMENTOR_URL . '/widgets/tabs-tours/assets/js/thegem-tabs.js', array('thegem-accordion'), null, true);
			wp_register_style('thegem-products-categories-styles', THEGEM_ELEMENTOR_URL . '/widgets/products-categories/assets/css/thegem-products-categories.css', array('thegem-woocommerce'), null);
		}

		wp_register_style('thegem-portfolio-products', THEGEM_THEME_URI . '/css/thegem-portfolio-products.css', array('thegem-portfolio', 'thegem-woocommerce'), THEGEM_THEME_VERSION);
		wp_register_style('thegem-portfolio-products-extended', THEGEM_THEME_URI . '/css/thegem-portfolio-products-extended.css', array('thegem-portfolio', 'thegem-woocommerce','thegem-woocommerce-temp', 'thegem-portfolio-filters-list'), THEGEM_THEME_VERSION);

		if (( is_shop() || is_product_taxonomy() || is_product() || is_cart() ) && thegem_get_option('product_archive_layout_source') == 'default' && thegem_get_option('product_archive_type') !== 'legacy') {
			wp_enqueue_style('thegem-portfolio-products-extended');
		}

		if(function_exists('dokan_is_store_page') && dokan_is_store_page() && thegem_get_option('product_archive_type') !== 'legacy') {
			wp_enqueue_style('thegem-portfolio-products-extended');
		}

		if(function_exists('wcfm_is_store_page') && wcfm_is_store_page() && thegem_get_option('product_archive_type') !== 'legacy') {
			wp_enqueue_style('thegem-portfolio-products-extended');
		}

		if(function_exists('dokan_is_store_page') && dokan_is_store_page() && thegem_get_option('product_archive_type') !== 'legacy') {
			wp_enqueue_style('thegem-portfolio-products-extended');
		}

		if(function_exists('wcfm_is_store_page') && wcfm_is_store_page() && thegem_get_option('product_archive_type') !== 'legacy') {
			wp_enqueue_style('thegem-portfolio-products-extended');
		}

		if (is_singular()) {
			$elementor_data = get_post_meta(get_the_ID(), '_elementor_data');

			if ($elementor_data && defined('ELEMENTOR_VERSION')) {
				if (is_array($elementor_data)) {
					$elementor_data = $elementor_data[0];
				}
				$data = json_decode($elementor_data, true);
				if (is_array($data)) {
					$elements = array();
					\Elementor\Plugin::$instance->db->iterate_data( $data, function( $element ) use ( &$elements ) {
						$elements[] = (object)$element;
					} );
					foreach($elements as $widget) {
						if (isset($widget->elType) && $widget->elType == 'widget') {
							if ($widget->widgetType == 'thegem-extended-products-grid') {
								wp_enqueue_style('thegem-portfolio-products-extended');
							} else if ($widget->widgetType == 'thegem-extended-products-carousel') {
								$widgetSettings = $widget->settings;
								$disablePreloader = isset($widgetSettings['disable_preloader']) && $widgetSettings['disable_preloader'] == 'yes';
								if ($disablePreloader) {
									wp_enqueue_style('thegem-products-carousel-styles');
								}
							} else if ($widget->widgetType == 'thegem-products-categories') {
								$widgetSettings = $widget->settings;
								$isCarousel = isset($widgetSettings['layout_type']) && $widgetSettings['layout_type'] == 'carousel';
								$disablePreloader = isset($widgetSettings['disable_preloader']) && $widgetSettings['disable_preloader'] == 'yes';
								if ($isCarousel && $disablePreloader) {
									wp_enqueue_style('thegem-products-categories-styles');
								}
							} else if ($widget->widgetType == 'thegem-products-grid') {
								wp_enqueue_style('thegem-portfolio-products');
							} else if ($widget->widgetType == 'thegem-products-categories') {
								wp_enqueue_style('thegem-products-categories-styles');
							}
						}
					}
				}
			}
		}

		wp_register_script('thegem-isotope-metro', THEGEM_THEME_URI . '/js/isotope_layout_metro.js', array('thegem-isotope-js'), THEGEM_THEME_VERSION, true);
		wp_register_script('thegem-isotope-masonry-custom', THEGEM_THEME_URI . '/js/isotope-masonry-custom.js', array('thegem-isotope-js'), THEGEM_THEME_VERSION, true);
		wp_register_script('thegem-portfolio-grid-extended', THEGEM_THEME_URI . '/js/thegem-portfolio-grid-extended.js', array('jquery'), THEGEM_THEME_VERSION, true);
		wp_register_script( 'thegem-portfolio-grid-extended-inline', '', [], '', true );

		$thegem_cart_template = thegem_cart_template();
		if ( is_cart() && $thegem_cart_template && defined('ELEMENTOR_VERSION')) {
			thegem_enqueue_template_styles($thegem_cart_template);
		}
		$thegem_cart_empty_template = thegem_cart_empty_template();
		if ( is_cart() && $thegem_cart_empty_template && defined('ELEMENTOR_VERSION')) {
			thegem_enqueue_template_styles($thegem_cart_empty_template);
		}
		$thegem_checkout_template = thegem_checkout_template();
		if ( is_checkout() && $thegem_checkout_template && defined('ELEMENTOR_VERSION')) {
			thegem_enqueue_template_styles($thegem_checkout_template);
		}
		$thegem_archive_product_template = thegem_archive_product_template();
		if ( $thegem_archive_product_template && defined('ELEMENTOR_VERSION')) {
			wp_enqueue_style( 'elementor-frontend' );
			thegem_enqueue_template_styles($thegem_archive_product_template);
		}

		if (thegem_get_option('mini_cart_type') === 'sidebar') {
			wp_enqueue_script( 'wc-cart-fragments' );
		}

	}
}
add_action('wp_enqueue_scripts', 'thegem_woocommerce_scripts');

function thegem_enqueue_woocommerce_styles() {
	wp_enqueue_style('thegem-woocommerce');
	wp_enqueue_style('thegem-woocommerce1');
	wp_enqueue_style('thegem-woocommerce-temp');
	wp_enqueue_style('thegem-woocommerce-custom');
}

function thegem_is_woocommerce_page() {
	return function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_product_category() || (is_page() && get_the_ID() == get_option( 'ywraq_page_id' )));
}

function thegem_woocommerce_create_pages($pages) {
	$pages = array(
		'shop' => array(
			'name' => _x( 'shop', 'Page slug', 'woocommerce' ),
			'title' => _x( 'Shop', 'Page title', 'woocommerce' ),
			'content' => '',
		),
		'cart' => array(
			'name' => _x( 'cart', 'Page slug', 'woocommerce' ),
			'title' => _x( 'Cart', 'Page title', 'woocommerce' ),
			'content' => '[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']',
		),
		'checkout' => array(
			'name' => _x( 'checkout', 'Page slug', 'woocommerce' ),
			'title' => _x( 'Checkout', 'Page title', 'woocommerce' ),
			'content' => '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']',
		),
		'myaccount' => array(
			'name' => _x( 'my-account', 'Page slug', 'woocommerce' ),
			'title' => _x( 'My account', 'Page title', 'woocommerce' ),
			'content' => '[' . apply_filters( 'woocommerce_my_account_shortcode_tag', 'woocommerce_my_account' ) . ']',
		),
	);
	return $pages;
}
add_filter('woocommerce_create_pages', 'thegem_woocommerce_create_pages');

function thegem_check_woocommerce_pages() {
	include_once dirname( WC_PLUGIN_FILE ) . '/includes/admin/wc-admin-functions.php';
	if(function_exists('wc_get_page_id')) {
		$cart_page_id = wc_get_page_id('cart');
		$cart_page = get_post($cart_page_id);
		if($cart_page_id && $cart_page) {
			if(strpos($cart_page->post_content, '[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']') === false) {
				delete_option('woocommerce_cart_page_id');
				$qwe = wc_create_page(
					esc_sql( _x( 'cart', 'Page slug', 'woocommerce' ) ),
					'woocommerce_cart_page_id',
					_x( 'Cart', 'Page title', 'woocommerce' ),
					'[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']',
					'',
					'publish'
				);
			}
		}
		$checkout_page_id = wc_get_page_id('checkout');
		$checkout_page = get_post($checkout_page_id);
		if($checkout_page_id && $checkout_page) {
			if(strpos($checkout_page->post_content, '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']') === false) {
				delete_option('woocommerce_checkout_page_id');
				wc_create_page(
					esc_sql( _x( 'checkout', 'Page slug', 'woocommerce' ) ),
					'woocommerce_checkout_page_id',
					_x( 'Checkout', 'Page title', 'woocommerce' ),
					'[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']',
					'',
					'publish'
				);
			}
		}
		update_option('thegem_woocommerce_pages_checked', 1);
	}
}

function thegem_get_cart_count() {
	return empty(WC()->cart) ? 0 : (thegem_get_option('cart_label_count') ? WC()->cart->cart_contents_count : sizeof(WC()->cart->get_cart()));
}

function thegem_is_wishlist_page() {
	return (function_exists('yith_wcwl_is_wishlist') && yith_wcwl_is_wishlist()) || (function_exists('yith_wcwl_is_wishlist_page') && yith_wcwl_is_wishlist_page());
}

if (defined( 'YITH_WCWL') && !function_exists( 'thegem_wishlist_ajax_update_count')) {
	function thegem_wishlist_ajax_update_count() {
		wp_send_json(array(
			'count' => yith_wcwl_count_all_products()
		));
	}
	add_action('wp_ajax_yith_wcwl_update_wishlist_count', 'thegem_wishlist_ajax_update_count');
	add_action('wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'thegem_wishlist_ajax_update_count');
}

function thegem_is_quick_view_default() {
	return (thegem_get_option('product_archive_quick_view') && thegem_get_option('product_page_layout') != 'legacy') || (thegem_get_option('product_quick_view') && thegem_get_option('product_page_layout') != 'legacy');
}

add_action('add_meta_boxes', 'thegem_add_product_settings_boxes');
function thegem_add_product_settings_boxes() {
	add_meta_box('thegem_product_description_meta_box', esc_html__('Product Extra Description', 'thegem'), 'thegem_product_description_settings_box', 'product', 'normal', 'high');
	add_meta_box('thegem_product_video_meta_box', esc_html__('Product Video', 'thegem'), 'thegem_product_video_settings_box', 'product', 'side', 'low');
}

function thegem_product_description_settings_box($post) {
	wp_nonce_field('thegem_product_description_settings_box', 'thegem_product_description_settings_box_nonce');
	$product_description = get_post_meta($post->ID, 'thegem_product_description', true);
?>
<div class="inside">
	<?php wp_editor(htmlspecialchars_decode($product_description), 'thegem_product_description', array(
			'textarea_name' => 'thegem_product_description',
			'quicktags' => array('buttons' => 'em,strong,link'),
			'tinymce' => array(
				'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
				'theme_advanced_buttons2' => '',
			),
			'editor_css' => '<style>#wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}</style>'
		)); ?>
</div>
<?php
}

function thegem_product_hover_settings_box($post) {
	wp_nonce_field('thegem_product_hover_settings_box', 'thegem_product_hover_settings_box_nonce');
	$product_hover = get_post_meta($post->ID, 'thegem_product_disable_hover', true);
?>
<div class="inside">
	<input name="thegem_product_disable_hover" type="checkbox" id="thegem_product_disable_hover" value="1" <?php checked($product_hover, 1); ?> />
	<label for="thegem_product_disable_hover"><?php esc_html_e('Disable hover with alternative product image', 'thegem'); ?></label>
</div>
<?php
}

function thegem_product_video_settings_box($post) {
	wp_nonce_field('thegem_product_video_settings_box', 'thegem_product_video_settings_box_nonce');

	$product_video_data = get_post_meta($post->ID, 'thegem_product_video', true);
	$product_video = thegem_get_sanitize_product_video_data($product_video_data);
	$video_background_types = array('' => __('None', 'thegem'), 'youtube' => __('YouTube', 'thegem'), 'vimeo' => __('Vimeo', 'thegem'), 'self' => __('Self-Hosted Video', 'thegem'));
	?>
	<div class="thegem-product-video">
		<div class="product-video-box visible">
			<label for="thegem_product_video_type"><?php esc_html_e('Video Type', 'thegem'); ?>:</label>
			<?php thegem_print_select_input($video_background_types, esc_attr($product_video['product_video_type']), 'thegem_product_video_type', 'thegem_product_video_type'); ?>
		</div>
		<div id="product-video-self" class="product-video-box">
			<label for="thegem_product_video_link"><?php esc_html_e('Link to video', 'thegem'); ?>:</label>
			<input type="text" name="thegem_product_video_link" id="thegem_product_video_link" value="<?php echo esc_attr($product_video['product_video_link']); ?>" class="video-select"/>
		</div>
		<div id="product-video-id" class="product-video-box">
			<label for="thegem_product_video_id"><?php esc_html_e('Video URL (for YouTube or Vimeo)', 'thegem'); ?>:</label>
			<input type="text" name="thegem_product_video_id" id="thegem_product_video_id" value="<?php echo esc_attr($product_video['product_video_id']); ?>"/>
		</div>
		<div id="product-video-thumb" class="product-video-box">
			<label for="thegem_product_video_thumb"><?php esc_html_e('Video Poster', 'thegem'); ?>:</label>
			<input type="text" name="thegem_product_video_thumb" id="thegem_product_video_thumb" value="<?php echo esc_attr($product_video['product_video_thumb']); ?>" class="picture-select"/>
		</div>
		<div class="product-video-box visible"><a href="#" id="remove-product-video">Remove product video</a></div>
	</div>
	<?php
}

function thegem_save_product_data($post_id) {
	if(!isset($_POST['thegem_product_description_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['thegem_product_description_settings_box_nonce'], 'thegem_product_description_settings_box')) {
		return;
	}

	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && $_POST['post_type'] == 'product') {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}
	if(isset($_POST['thegem_product_description'])) {
		update_post_meta($post_id, 'thegem_product_description', $_POST['thegem_product_description']);
	}

	if(!wp_verify_nonce($_POST['thegem_product_video_settings_box_nonce'], 'thegem_product_video_settings_box')) {
		return;
	}
	$product_video = thegem_get_sanitize_product_video_data( array(
		'product_video_type' => $_POST['thegem_product_video_type'],
		'product_video_link' => $_POST['thegem_product_video_link'],
		'product_video_id' => $_POST['thegem_product_video_id'],
		'product_video_thumb' => $_POST['thegem_product_video_thumb'],
	));
	update_post_meta($post_id, 'thegem_product_video', $product_video);

	//update_post_meta($post_id, 'thegem_product_disable_hover', isset($_POST['thegem_product_disable_hover']));
}
add_action('save_post', 'thegem_save_product_data');

add_filter('woocommerce_enqueue_styles', '__return_false');
add_filter('woocommerce_redirect_single_search_result', '__return_false');

function thegem_sku_search_pre_get_posts($query) {
	if (!empty($query->query['is_products_search']) || (!is_admin() && $query->is_main_query() && $query->is_search() && get_query_var('post_type') == 'product')) {
		if(thegem_get_option('website_search_post_type_product_variation')) {
			$query->set('post_type', ['product', 'product_variation']);
		}
		add_filter('posts_join', 'thegem_sku_search_join');
		add_filter('posts_where', 'thegem_sku_search_where');
		add_filter('posts_groupby', 'thegem_sku_search_groupby');
	}
}
add_action('pre_get_posts', 'thegem_sku_search_pre_get_posts');

function thegem_sku_search_join( $join ){
	global $wpdb;
	$join .= " LEFT JOIN $wpdb->postmeta gm ON (" .
	         $wpdb->posts . ".ID = gm.post_id AND gm.meta_key='_sku')"; // change to your meta key if not woo

	return $join;
}

function thegem_sku_search_where( $where ){
	global $wpdb;
	$where = preg_replace(
		"/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
		"({$wpdb->posts}.post_title LIKE $1) OR ({$wpdb->posts}.ID LIKE $1) OR (gm.meta_value LIKE $1)", $where );
	return $where;
}

function thegem_sku_search_groupby( $groupby ){
	global $wpdb;
	$mygroupby = "{$wpdb->posts}.ID";
	if( preg_match( "/$mygroupby/", $groupby )) {
		return $groupby;
	}
	if( !strlen(trim($groupby))) {
		return $mygroupby;
	}
	return $groupby . ", " . $mygroupby;
}

function thegem_loop_shop_columns($count) {
	$item_data = array(
		'sidebar_position' => '',
	);
	$thegem_page_id = wc_get_page_id('shop');

	$item_data = thegem_get_output_page_settings($thegem_page_id);
	if (!is_singular( 'product' ) && thegem_get_option('product_archive_type') !== 'legacy') {
		$item_data = thegem_get_output_page_settings($thegem_page_id, array(), 'product_category');
	}
	if(is_tax()) {
		if (thegem_get_option('product_archive_type') == 'legacy') {
			$item_data = thegem_get_output_page_settings(0, array(), 'product_category');
		}
		$thegem_term_id = get_queried_object()->term_id;
		$item_data = thegem_get_output_page_settings($thegem_term_id, array(), 'term');
	}

	$sidebar_position = thegem_check_array_value(array('', 'left', 'right'), $item_data['sidebar_position'], '');
	if(is_active_sidebar('shop-sidebar') && $item_data['sidebar_show'] && $sidebar_position) {
		return 3;
	}
	return 4;
}
add_filter('loop_shop_columns', 'thegem_loop_shop_columns');

function thegem_woocommerce_single_product_gallery_labels() {
	global $post, $product;
	$product_gallery_data = thegem_get_output_product_page_data( $product->get_id() );

	$params = array(
		'product_show_new' => $product_gallery_data['product_gallery_labels'] == '1' ? $product_gallery_data['product_gallery_label_new'] : '',
		'product_show_sale' => $product_gallery_data['product_gallery_labels'] == '1' ? $product_gallery_data['product_gallery_label_sale'] : '',
		'product_show_out' => $product_gallery_data['product_gallery_labels'] == '1' ? $product_gallery_data['product_gallery_label_out_stock'] : '',
		'labels_design' => thegem_get_option('product_labels_style'),
		'new_label_text' => thegem_get_option('product_label_new_text'),
		'sale_label_type' => thegem_get_option('product_label_sale_type'),
		'sale_label_prefix' => thegem_get_option('product_label_sale_prefix'),
		'sale_label_suffix' => thegem_get_option('product_label_sale_suffix'),
		'sale_label_text' => thegem_get_option('product_label_sale_text'),
		'out_label_text' => thegem_get_option('product_label_out_of_stock_text'),
	);
?>
<div class="labels-outer">
	<div class="product-labels style-<?php echo esc_attr($params['labels_design']); ?>">
		<?php
		$svg = '';
		if ($params['labels_design'] == 4) {
			$svg = '<svg height="100%" viewBox="0 0 4 19" preserveAspectRatio="none" shape-rendering="geometricPrecision"><polygon points="0,0 0,19 4,0 "/></svg>';
		} ?>
		<?php if ($params['product_show_out'] == '1' && !$product->is_in_stock()) : ?>
			<?php echo apply_filters('thegem_woocommerce_out_of_stock_flash', '<span class="label out-of-stock-label title-h6"><span class="rotate-back"><span class="text">' . $params['out_label_text'] . '</span></span>'.$svg.'</span>', $post, $product); ?>
		<?php endif; ?>
		<?php if ($params['product_show_sale'] == '1' && $product->is_on_sale()) : ?>
			<?php if ($params['sale_label_type'] == 'percentage') {
				$percentage = 0;
				if ($product->get_type() === 'variable') {
					$children = array_filter(array_map('wc_get_product', $product->get_children()), 'wc_products_array_filter_visible_grouped');
					foreach ($children as $child) {
						$regular_price = (float)$child->get_regular_price();
						$sale_price = (float)$child->get_sale_price();
						if (!empty($sale_price)) {
							$new_percentage = round(100 - ($sale_price / $regular_price * 100));
							if ($new_percentage > $percentage) {
								$percentage = $new_percentage;
							}
						}
					}
				} else {
					$regular_price = (float)$product->get_regular_price();
					$sale_price = (float)$product->get_sale_price();
					if (!empty($sale_price) && $sale_price != 0) {
						$percentage = round(100 - ($sale_price / $regular_price * 100));
					}
				}
				$sale_text = '<b>'.$params['sale_label_prefix'] . $percentage . $params['sale_label_suffix'].'</b>';
			} else {
				$sale_text = $params['sale_label_text'];
			} ?>
			<?php echo apply_filters('woocommerce_sale_flash', '<span class="label onsale title-h6"><span class="rotate-back"><span class="text">' . $sale_text . '</span></span>'.$svg.'</span>', $post, $product); ?>
		<?php endif; ?>
		<?php if ($params['product_show_new'] == '1' && thegem_product_need_new_label($product->get_id())) : ?>
			<?php echo apply_filters('thegem_woocommerce_featured_flash', '<span class="label new-label title-h6"><span class="rotate-back"><span class="text">' . $params['new_label_text'] . '</span></span>'.$svg.'</span>', $post, $product); ?>
		<?php endif; ?>
	</div>
</div>
<?php
}

function thegem_woocommerce_single_product_gallery() {
	global $post, $product;
	$product_gallery_data = thegem_get_output_product_page_data($product->get_id());

	if($product_gallery_data['product_gallery'] !== 'legacy') return;

	wp_enqueue_style('thegem-hovers');
	wp_enqueue_script('thegem-product-legacy-gallery');
	$attachments_ids = array();
	if(has_post_thumbnail()) {
		$attachments_ids = array(get_post_thumbnail_id());
	}
	$attachments_ids = array_merge($attachments_ids, $product->get_gallery_image_ids());
	if('variable' === $product->get_type()) {
		foreach($product->get_available_variations() as $variation) {
			if(has_post_thumbnail($variation['variation_id'])) {
				$thumbnail_id = get_post_thumbnail_id($variation['variation_id']);
				if(!in_array($thumbnail_id, $attachments_ids)) {
					$attachments_ids[] = $thumbnail_id;
				}
			}
		}
	}
	if(empty($attachments_ids)) return ;
	$gallery_uid = uniqid();
	$preview_class = '';
	if (defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
		$preview_class = ' gem-gallery-elementor-preview';
	}
	echo '<div class="preloader"><div class="preloader-spin"></div></div>';
	echo '<div class="gem-gallery gem-gallery-hover-default'.$preview_class.'">';
	foreach($attachments_ids as $attachments_id) {
		if(thegem_get_option('woocommerce_activate_images_sizes')) {
			$thumb_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail');
			$preview_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-single');
		} else {
			$thumb_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
			$preview_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_single'));
		}
		$full_image_url = wp_get_attachment_image_src($attachments_id, 'full');
		if(empty($full_image_url)) {
			continue;
		}
		?>
<div class="gem-gallery-item" data-image-id="<?php echo esc_attr($attachments_id); ?>">
	<div class="gem-gallery-item-image">
		<a href="<?php echo esc_url($preview_image_url[0]); ?>" data-fancybox-group="product-gallery-<?php echo esc_attr($gallery_uid); ?>" data-full-image-url="<?php echo esc_url($full_image_url[0]); ?>" data-elementor-open-lightbox="no">
			<svg width="20" height="10"><path d="M 0,10 Q 9,9 10,0 Q 11,9 20,10" /></svg>
			<img src="<?php echo esc_url($thumb_image_url[0]); ?>" alt="<?=thegem_gallery_get_alt_text($attachments_id)?>" class="img-responsive">
		</a>
	</div>
</div>
<?php
	}
	echo '</div>';
}

function thegem_woocommerce_single_product_gallery_alternative() {
	global $post, $product;
	$product_gallery_data = thegem_get_output_product_page_data($product->get_id());
	$product_gallery_data['product_gallery_thumb_count'] = thegem_check_array_value(array('4', '5', '6', '7', '8'), $product_gallery_data['product_gallery_thumb_count'], '4');
	if ($product_gallery_data['product_gallery'] != 'enabled' || $product_gallery_data['product_gallery_type'] == 'grid') return;

	wp_enqueue_style('thegem-product-gallery');
	wp_enqueue_script('thegem-product-gallery');

	$attachments_ids = array();
	if (has_post_thumbnail()) {
		$attachments_ids = array(get_post_thumbnail_id());
	}
	$attachments_ids = array_merge($attachments_ids, $product->get_gallery_image_ids());
	if ('variable' === $product->get_type()) {
		foreach ($product->get_available_variations() as $variation) {
			if (has_post_thumbnail($variation['variation_id'])) {
				$thumbnail_id = get_post_thumbnail_id($variation['variation_id']);
				if (!in_array($thumbnail_id, $attachments_ids)) {
					$attachments_ids[] = $thumbnail_id;
				}
			}
		}
	}
	if (empty($attachments_ids)) {
		return;
	}
	$gallery_uid = uniqid();

	$firstImagePath = wp_get_original_image_path($attachments_ids[0]);
	$isSingleImg = count($attachments_ids) < 2;
	$isSingleImgSkeleton = $isSingleImg ? ' product-gallery-skeleton-single' : '';
	$isSquareImg = '1';
	if ($firstImagePath) {
		$firstImageSize = wp_getimagesize($firstImagePath);
		$skeletonPadding = 100 * $firstImageSize[1] / $firstImageSize[0];
		if ($skeletonPadding > 100) {
			$isSquareImg = '';
		}
	}

	$isVertical = $product_gallery_data['product_gallery_type'] == 'vertical';
	$isVerticalSkeleton = $isVertical ? 'product-gallery-skeleton-vertical' : '';
	$isVerticalSkeleton .= $isSquareImg == '1' ? ' product-gallery-skeleton-vertical-square' : '';
	$aspect_ratio = !empty($product_gallery_data['product_gallery_image_ratio']) ? $product_gallery_data['product_gallery_image_ratio'] : '';
	$aspect_ratio_class = !empty($product_gallery_data['product_gallery_image_ratio']) ? 'image-aspect-ratio' : '';

	?>

	<script>
		function firstImageLoaded() {
			(function ($) {
				var $galleryElement = $('.product-gallery'),
					isVertical = $galleryElement.attr("data-thumb") === 'vertical',
					isTrueCount = $('.product-gallery-slider-item', $galleryElement).length > 1,
					isMobile = $(window).width() < 768 && /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? true : false,
					isDots = $galleryElement.attr("data-thumb") === 'dots';

				if (isVertical && isTrueCount && !isMobile && !isDots) {
					if ($galleryElement.data('square-img')) {
						$galleryElement.css('height', $galleryElement.width() * 0.7411).css('overflow', 'hidden');
					} else {
						$galleryElement.css('height', $galleryElement.width() - 30).css('overflow', 'hidden');
					}

					if ($galleryElement.data("thumb-position") == 'right') {
						$galleryElement.addClass('is-vertical-inited-right');
					} else {
						$galleryElement.addClass('is-vertical-inited');
					}
				}
				$galleryElement.prev('.preloader').remove();
			})(jQuery);
		}
	</script>

	<?php if ($product_gallery_data['product_page_skeleton_loader'] || 1) {
		echo '<div class="preloader skeleton product-gallery-skeleton ' . $isVerticalSkeleton . $isSingleImgSkeleton . '" >';
		echo '<div class="product-gallery-skeleton-image" style="padding-bottom:' . $skeletonPadding . '%"></div>';
		if (!$isSingleImg && ($product_gallery_data['product_gallery_type'] == 'horizontal' || $product_gallery_data['product_gallery_type'] == 'vertical')) {
			echo '<div class="product-gallery-skeleton-thumbs product-gallery-skeleton-thumbs-' . $product_gallery_data['product_gallery_type'] . '"></div>';
		}
		echo '</div>';
	} else {
		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
	} ?>
	<div class="product-gallery <?php echo $product_gallery_data['product_gallery_type'] == 'vertical' ? 'vertical' : ''; ?> <?= $aspect_ratio_class; ?>"
		data-type="<?php echo esc_attr($product_gallery_data['product_gallery_show_image']); ?>"
		data-thumb="<?php echo esc_attr($product_gallery_data['product_gallery_type']); ?>"
		data-thumb-on-mobile="<?php echo esc_attr($product_gallery_data['product_gallery_thumb_on_mobile']); ?>"
		data-thumb-scroll-by-hover="<?php echo esc_attr($product_gallery_data['product_gallery_thumb_scroll_by_hover']); ?>"
		data-thumb-count="<?php echo esc_attr($product_gallery_data['product_gallery_thumb_count']); ?>"
		data-thumb-position="<?php echo esc_attr($product_gallery_data['product_gallery_thumb_position']); ?>"
		data-fancy="<?php echo esc_attr($product_gallery_data['product_gallery_lightbox']); ?>"
		data-zoom="<?php echo esc_attr($product_gallery_data['product_gallery_zoom']); ?>"
		data-colors="<?php echo esc_attr($product_gallery_data['product_gallery_elements_color']); ?>"
		data-auto-height="<?php echo esc_attr($product_gallery_data['product_gallery_auto_height']); ?>"
		data-square-img="<?php echo esc_attr($isSquareImg); ?>">

		<div class="product-gallery-slider-wrap <?php echo $product_gallery_data['product_gallery_lightbox'] ? 'init-fancy' : ''; ?> <?php echo !empty($product_gallery_data['product_gallery_elements_color']) ? 'init-color' : ''; ?>"
			 data-color="<?php echo esc_attr($product_gallery_data['product_gallery_elements_color']); ?>">
			<div class="product-gallery-slider owl-carousel <?php echo $product_gallery_data['product_gallery_type'] == 'dots' ? 'dots' : ''; ?>">
				<?php
				//Images
				foreach ($attachments_ids as $i => $attachments_id) {
					$full_image_url = wp_get_attachment_image_src($attachments_id, 'full');
					if ($full_image_url): ?>
						<div class="product-gallery-slider-item" data-image-id="<?= esc_attr($attachments_id); ?>">
							<div class="product-gallery-image <?= $product_gallery_data['product_gallery_zoom'] ? 'init-zoom' : null ?>">
								<?php if ($product_gallery_data['product_gallery_lightbox']): ?>
									<a href="<?= esc_url($full_image_url[0]); ?>" class="fancy-product-gallery"
									   data-fancybox-group="product-gallery-<?= esc_attr($gallery_uid); ?>"
									   data-fancybox="product-gallery-<?= esc_attr($gallery_uid); ?>"
									   data-full-image-url="<?= esc_url($full_image_url[0]); ?>"
									   data-elementor-open-lightbox="no">
										<div class="image-inner" style="aspect-ratio: <?= $aspect_ratio ?>">
											<img src="<?= esc_url($full_image_url[0]); ?>"
												data-ww="<?php echo esc_url($full_image_url[0]); ?>"
												alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
												class="img-responsive"
												width="<?=$full_image_url[1]?>" height="<?=$full_image_url[2]?>"
												<?php if ($i == 0): ?>onload="firstImageLoaded()"<?php endif; ?>
											/>
										</div>
									</a>
								<?php else: ?>
									<div class="image-inner" style="aspect-ratio: <?= $aspect_ratio ?>">
										<img src="<?= esc_url($full_image_url[0]); ?>"
											data-ww="<?php echo esc_url($full_image_url[0]); ?>"
											alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
											class="img-responsive"
											width="<?=$full_image_url[1]?>" height="<?=$full_image_url[2]?>"
											<?php if ($i == 0): ?>onload="firstImageLoaded()"<?php endif; ?>
										/>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif;
				} ?>

				<!--Video-->
				<?php
				$product_video_data = get_post_meta($post->ID, 'thegem_product_video', true);
				$product_video = thegem_get_sanitize_product_video_data($product_video_data);
				$video_type = $product_video['product_video_type'];
				$video = $product_video['product_video_id'];
				$video_self = $product_video['product_video_link'];
				$poster = $product_video['product_video_thumb'];
				$poster_id = attachment_url_to_postid($poster);

				if (!empty($video) && $video_type == 'youtube') {
					$youtube_id = thegem_parcing_youtube_url($video);
				}

				if (!empty($video) && $video_type == 'vimeo') {
					$vimeo_id = thegem_parcing_vimeo_url($video);
				}

				$link = '';
				if ($video_type == 'youtube' || $video_type == 'vimeo') {
					if ($video_type == 'youtube' && $youtube_id) {
						$link = '//www.youtube.com/embed/' . $youtube_id . '?playlist=' . $youtube_id . '&autoplay=1&mute=1&controls=1&loop=1&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';

						if ($poster) {
							$video_block = '<iframe src="' . esc_url($link) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
						} else {
							$video_block = '<div id="productYoutubeVideo" data-yt-id="' . $youtube_id . '"></div>';
						}
					}
					if ($video_type == 'vimeo' && $vimeo_id) {
						$link = '//player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&controls=1&loop=1&title=0&badge=0&byline=0&autopause=0&autohide=1';

						if ($poster) {
							$video_block = '<iframe src="' . esc_url($link) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
						} else {
							$video_block = '<div id="productVimeoVideo" data-vm-id="' . $vimeo_id . '"></div>';
						}
					}
				} else if ($video_type == 'self') {
					$link = $video_self;
					$video_block = '<video id="productSelfVideo" class="fancybox-video" controls disablePictureInPicture controlsList="nodownload" loop="loop" src="' . $video_self . '" muted="muted"' . ($poster ? ' poster="' . esc_url($poster) . '"' : '') . '></video>';
				}

				if (isset($video_block)) { ?>
					<div class="product-gallery-slider-item <?php if (!$poster || $video_type == 'self'): ?>video-block<?php endif; ?>"
						 data-video-type="<?= $video_type ?>">
						<?php if ($product_gallery_data['product_gallery_lightbox']): ?>
							<a href="<?= $link ?>"
							   class="fancy-product-gallery"
							   data-fancybox-group="product-gallery-<?= esc_attr($gallery_uid); ?>"
							   data-fancybox="product-gallery-<?= esc_attr($gallery_uid); ?>"
							   data-elementor-open-lightbox="no">
								<?php if ($poster && $video_type != 'self'): ?>
									<div class="image-inner" style="aspect-ratio: <?= $aspect_ratio ?>">
										<img src="<?php echo esc_url($poster); ?>"
										     alt="<?= thegem_gallery_get_alt_text($poster_id) ?>" class="img-responsive">
									</div>

									<i class="icon-play <?= $video_type ?>"></i>
								<?php else: ?>
									<?= $video_block ?>
								<?php endif; ?>
							</a>
						<?php else: ?>
							<?= $video_block ?>
						<?php endif; ?>
					</div>
				<?php } ?>
			</div>
			<?php
			//Zoom icon
			if ($product_gallery_data['product_gallery_lightbox']) {
				echo '<div class="product-gallery-fancy"></div>';
			}

			//Labels
			if ($product_gallery_data['product_gallery_labels']) { ?>
				<div class="product-gallery-labels"><?= thegem_woocommerce_single_product_gallery_labels() ?></div>
			<?php }
			?>
		</div>

		<?php if (!$isSingleImg && ($product_gallery_data['product_gallery_type'] == 'horizontal' || $product_gallery_data['product_gallery_type'] == 'vertical')): ?>
			<div class="product-gallery-skeleton-thumbs product-gallery-skeleton-thumbs-<?php echo $product_gallery_data['product_gallery_type'] ?>"></div>
			<div class="product-gallery-thumbs-wrap <?php echo !empty($product_gallery_data['product_gallery_elements_color']) ? 'init-color' : ''; ?>">
				<div class="product-gallery-thumbs owl-carousel">
					<?php
					//Images
					foreach ($attachments_ids as $attachments_id) {
						if (thegem_get_option('woocommerce_activate_images_sizes')) {
							$thumb_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail');
							$thumb_image_url_2x = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-2x');
							$thumb_vertical_image_url = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-vertical');
							$thumb_vertical_image_url_2x = thegem_generate_thumbnail_src($attachments_id, 'thegem-product-thumbnail-vertical-2x');
						} else {
							$thumb_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
							$thumb_image_url_2x = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
							$thumb_vertical_image_url = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_gallery_thumbnail'));
							$thumb_vertical_image_url_2x = wp_get_attachment_image_src($attachments_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_gallery_thumbnail'));
						}
						?>
						<?php if ($thumb_image_url || $thumb_vertical_image_url): ?>
							<div class="product-gallery-thumb-item" data-image-id="<?= esc_attr($attachments_id); ?>">
								<div class="product-gallery-image">
									<img
										<?php if ($product_gallery_data['product_gallery_type'] == 'vertical'): ?>
											src="<?php echo esc_url($thumb_vertical_image_url[0]); ?>"
											<?php if (thegem_get_option('product_gallery_retina_ready')): ?>
												srcset="<?php echo esc_url($thumb_vertical_image_url_2x[0]); ?> 2x"
											<?php endif; ?>
											data-ww="<?php echo esc_url($thumb_vertical_image_url[0]); ?>"
										<?php else: ?>
											src="<?php echo esc_url($thumb_image_url[0]); ?>"
											<?php if (thegem_get_option('product_gallery_retina_ready')): ?>
												srcset="<?php echo esc_url($thumb_image_url_2x[0]); ?> 2x"
											<?php endif; ?>
										<?php endif; ?>
											alt="<?= thegem_gallery_get_alt_text($attachments_id) ?>"
											class="img-responsive"
									>
								</div>
							</div>
						<?php endif;
					} ?>

					<!--Video-->
					<?php

					if (thegem_get_option('woocommerce_activate_images_sizes')) {
						$thumb_video_url = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail');
						$thumb_video_url_2x = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-2x');
						$thumb_vertical_video_url = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-vertical');
						$thumb_vertical_video_url_2x = thegem_generate_thumbnail_src($poster_id, 'thegem-product-thumbnail-vertical-2x');
					} else {
						$thumb_video_url = wp_get_attachment_image_src($poster_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
						$thumb_video_url_2x = wp_get_attachment_image_src($poster_id, apply_filters('single_product_small_thumbnail_size', 'woocommerce_gallery_thumbnail'));
						$thumb_vertical_video_url = wp_get_attachment_image_src($poster_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_single'));
						$thumb_vertical_video_url_2x = wp_get_attachment_image_src($poster_id, apply_filters('single_product_large_thumbnail_size', 'woocommerce_single'));
					}

					if (isset($video_block)) { ?>
						<div class="product-gallery-thumb-item">
							<div class="product-gallery-image">
								<?php if ($poster): ?>
									<img
										<?php if ($product_gallery_data['product_gallery_type'] == 'vertical'): ?>
											src="<?php echo esc_url($thumb_vertical_video_url[0]); ?>"
											<?php if (thegem_get_option('product_gallery_retina_ready')): ?>
												srcset="<?php echo esc_url($thumb_vertical_video_url_2x[0]); ?> 2x"
											<?php endif; ?>
											data-ww="<?php echo esc_url($thumb_vertical_video_url[0]); ?>"
										<?php else: ?>
											src="<?php echo esc_url($thumb_video_url[0]); ?>"
											<?php if (thegem_get_option('product_gallery_retina_ready')): ?>
												srcset="<?php echo esc_url($thumb_video_url_2x[0]); ?> 2x"
											<?php endif; ?>
										<?php endif; ?>
											alt="<?= thegem_gallery_get_alt_text($poster_id) ?>" class="img-responsive"
									>
								<?php else: ?>
									<img src="<?= get_stylesheet_directory_uri() ?>/images/dummy/dummy.png" alt="dummy"
										 class="img-responsive">
								<?php endif; ?>
								<i class="icon-play <?= $video_type ?>"
								   style="color: <?= $poster ? '#ffffff' : '#dfe5e8' ?>"></i>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php
}

function thegem_woocommerce_single_product_gallery_grid() {
	global $post, $product;
	$product_gallery_data = thegem_get_output_product_page_data( $product->get_id() );

	if ( $product_gallery_data['product_gallery'] != 'enabled' || $product_gallery_data['product_gallery_type'] != 'grid' ) return;

	wp_enqueue_style( 'thegem-product-gallery' );
	wp_enqueue_script( 'thegem-product-gallery-grid' );

	//Attachments
	$attachments_ids = array();
	if ( has_post_thumbnail() ) {
		$attachments_ids = array( get_post_thumbnail_id() );
	}
	$attachments_ids = array_merge( $attachments_ids, $product->get_gallery_image_ids() );
	if ( 'variable' === $product->get_type() ) {
		foreach ( $product->get_available_variations() as $variation ) {
			if ( has_post_thumbnail( $variation['variation_id'] ) ) {
				$thumbnail_id = get_post_thumbnail_id( $variation['variation_id'] );
				if ( ! in_array( $thumbnail_id, $attachments_ids ) ) {
					$attachments_ids[] = $thumbnail_id;
				}
			}
		}
	}
	if ( empty( $attachments_ids ) ) {
		return;
	}
	$gallery_uid = uniqid();

	//Params
	$product_page_data = thegem_get_output_page_settings($product->get_id());
	$product_gallery_data = thegem_get_output_product_page_data( $product->get_id() );
	$product_video_data = get_post_meta( $post->ID, 'thegem_product_video', true );
	$product_video = thegem_get_sanitize_product_video_data( $product_video_data );
	$params = [
		'type'                => $product_gallery_data['product_gallery_type'],
		'column'              => $product_gallery_data['product_gallery_grid_columns'],
		'column_width'        => round( 100 / mb_substr( $product_gallery_data['product_gallery_grid_columns'], 0, 1 ), 4 ),
		'gaps'                => round( $product_gallery_data['product_gallery_grid_gaps'] / 2 ),
		'fancy'               => $product_gallery_data['product_gallery_lightbox'],
		'zoom'                => $product_gallery_data['product_gallery_zoom'],
		'color'               => $product_gallery_data['product_gallery_elements_color'],
		'labels'              => $product_gallery_data['product_gallery_labels'],
		'label_sale'          => $product_gallery_data['product_gallery_label_sale'],
		'label_new'           => $product_gallery_data['product_gallery_label_new'],
		'label_out_stock'     => $product_gallery_data['product_gallery_label_out_stock'],
		'is_images_sizes'     => thegem_get_option( 'woocommerce_activate_images_sizes' ),
		'image_size'          => !empty($product_gallery_data['product_gallery_grid_image_size']) ? $product_gallery_data['product_gallery_grid_image_size'] : 'default',
		'aspect_ratio'        => !empty($product_gallery_data['product_gallery_grid_image_ratio']) ? $product_gallery_data['product_gallery_grid_image_ratio'] : '',
		'aspect_ratio_class'  => ($product_gallery_data['product_gallery_grid_image_size'] == 'full' && !empty($product_gallery_data['product_gallery_grid_image_ratio'])) ? 'image-aspect-ratio' : '',
		'video_type'          => $product_video['product_video_type'],
		'video'               => $product_video['product_video_id'],
		'video_autoplay'      => $product_gallery_data['product_gallery_video_autoplay'],
		'video_self'          => $product_video['product_video_link'],
		'video_self_autoplay' => $product_gallery_data['product_gallery_video_autoplay'] ? 'playsinline autoplay' : null,
		'poster'              => $product_video['product_video_thumb'],
		'poster_id'           => attachment_url_to_postid( $product_video['product_video_thumb'] ),
		'content_left_margin' => !wp_is_mobile() && $product_gallery_data['product_gallery_grid_gaps_hide'] ? 42 : 0,
	];

	//Product Gallery Grid
	echo '<div class="product-gallery__grid col-' . $params['column'] . ' '.$params['aspect_ratio_class'].'" data-gallery="' . $params['type'] . '" data-fancy="' . $params['fancy'] . '" data-zoom="' . $params['zoom'] . '" data-color="' . $params['color'] . '" style="overflow: hidden; margin-left: -'.$params['content_left_margin'].'px;">';

	//Labels
	if ( $product_gallery_data['product_gallery_labels'] ) { ?>
		<div class="product-gallery__elements" style="opacity: 0;">
			<div class="product-gallery-labels"><?=thegem_woocommerce_single_product_gallery_labels()?></div>
		</div>
	<?php }

	echo '<div class="product-gallery__grid-wrap" style="margin: '.-$params['gaps'].'px; display: flex; flex-wrap: wrap;">';
	//Images
	foreach ( $attachments_ids as $attachments_id ) {
		if ( $params['is_images_sizes'] && $params['column'] != '1x' && $params['image_size'] == 'default') {
			$thumb_image_url = thegem_generate_thumbnail_src( $attachments_id, 'thegem-product-single' );
			$thumb_image_url_2x = thegem_generate_thumbnail_src( $attachments_id, 'thegem-product-single-2x' );
		} else {
			$thumb_image_url = wp_get_attachment_image_src( $attachments_id, 'full' );
			$thumb_image_url_2x = wp_get_attachment_image_src( $attachments_id, 'full' );
		}
		$full_image_url = wp_get_attachment_image_src( $attachments_id, 'full' );
		?>

		<?php if ( $thumb_image_url || $full_image_url ): ?>
			<div class="product-gallery__grid-item" data-image-id="<?= esc_attr( $attachments_id ); ?>" style="width: <?=$params['column_width']?>%; padding: <?=$params['gaps']?>px;">
				<div class="product-gallery-image <?php if ( $params['zoom'] ): ?>init-zoom<?php endif;?> <?php if ( $params['fancy'] ): ?>init-fancy<?php endif;?>">
					<?php if ( $params['fancy'] ): ?>
						<a href="<?= esc_url( $full_image_url[0] ); ?>" class="fancy-product-gallery"
						   data-fancybox-group="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
						   data-fancybox="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
						   data-full-image-url="<?= esc_url( $full_image_url[0] ); ?>"
						   data-elementor-open-lightbox="no">
							<div class="image-inner" style="aspect-ratio: <?= $params['aspect_ratio'] ?>">
								<i class="product-gallery-fancy" style="opacity: 0;"></i>
								<?php if ( $product_gallery_data['product_page_skeleton_loader'] ): ?>
									<span class="preloader skeleton product-grid-gallery-skeleton"></span>
								<?php endif; ?>
								<img src="<?= esc_url( $thumb_image_url[0] ); ?>"
									<?php if ( thegem_get_option( 'product_gallery_retina_ready' ) ): ?>
										srcset="<?php echo esc_url( $thumb_image_url_2x[0] ); ?> 2x"
									<?php endif; ?>
									 class="img-responsive" style="width: 100%; height: auto;"
									 width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
									 alt="<?= thegem_gallery_get_alt_text( $attachments_id ) ?>"
									 onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
								/>
							</div>
						</a>
					<?php else: ?>
						<div class="image-inner" style="aspect-ratio: <?= $params['aspect_ratio'] ?>">
							<?php if ( $product_gallery_data['product_page_skeleton_loader'] ): ?>
								<span class="preloader skeleton product-grid-gallery-skeleton"></span>
							<?php endif; ?>
							<img src="<?= esc_url( $thumb_image_url[0] ); ?>"
								<?php if ( thegem_get_option( 'product_gallery_retina_ready' ) ): ?>
									srcset="<?php echo esc_url( $thumb_image_url_2x[0] ); ?> 2x"
								<?php endif; ?>
								 class="img-responsive" style="width: 100%; height: auto;"
								 width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
								 alt="<?= thegem_gallery_get_alt_text( $attachments_id ) ?>"
								 onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
							/>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
	}

	//Video
	if ( $params['is_images_sizes'] && $params['column'] != '1x') {
		$thumb_image_url = thegem_generate_thumbnail_src( $params['poster_id'], 'thegem-product-single' );
		$thumb_image_url_2x = thegem_generate_thumbnail_src( $params['poster_id'], 'thegem-product-single-2x' );
	} else {
		$thumb_image_url = wp_get_attachment_image_src( $params['poster_id'], 'full' );
		$thumb_image_url_2x = wp_get_attachment_image_src( $params['poster_id'], 'full' );
	}
	if ( ! empty( $params['video'] ) && $params['video_type'] == 'youtube' ) {
		$youtube_id = thegem_parcing_youtube_url( $params['video'] );
	}
	if ( ! empty( $params['video'] ) && $params['video_type'] == 'vimeo' ) {
		$vimeo_id = thegem_parcing_vimeo_url( $params['video'] );
	}
	$link = '';
	if ( $params['video_type'] == 'youtube' || $params['video_type'] == 'vimeo' ) {
		if ( $params['video_type'] == 'youtube' && $youtube_id ) {
			$link = '//www.youtube.com/embed/' . $youtube_id . '?playlist=' . $youtube_id . '&autoplay=1&mute=1&controls=1&loop=1&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';

			if ( $params['poster'] ) {
				$video_block = '<iframe src="' . esc_url( $link ) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
			} else {
				$video_block = '<div id="productYoutubeVideo" data-yt-id="' . $youtube_id . '"></div>';
			}
		}
		if ( $params['video_type'] == 'vimeo' && $vimeo_id ) {
			$link = '//player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&controls=1&loop=1&title=0&badge=0&byline=0&autopause=0&autohide=1';

			if ( $params['poster'] ) {
				$video_block = '<iframe src="' . esc_url( $link ) . '" frameborder="0" muted="muted" allowfullscreen></iframe>';
			} else {
				$video_block = '<div id="productVimeoVideo" data-vm-id="' . $vimeo_id . '"></div>';
			}
		}
	} else if ( $params['video_type'] == 'self' ) {
		$link = $params['video_self'];
		$video_block = '<video id="productSelfVideo" class="fancybox-video" controls disablePictureInPicture controlsList="nodownload" loop="loop" '.$params['video_self_autoplay'].' src="' . $link . '" muted="muted"' . ( $params['poster'] ? ' poster="' . esc_url( $thumb_image_url[0] ) . '"' : '' ) . '></video>';
	}

	if ( isset( $video_block ) ) { ?>
		<div class="product-gallery__grid-item <?php if ( !$params['poster'] || $params['video_type'] == 'self' ): ?>video-block<?php endif; ?>" data-video-type="<?= $params['video_type'] ?>" data-video-autoplay="<?= $params['video_autoplay'] ?>" data-video-poster="<?= $params['poster_id'] ?>" style="width: <?=$params['column_width']?>%; padding: <?=$params['gaps']?>px; background-color: transparent;">
			<?php if ( $params['fancy'] ): ?>
				<a href="<?= $link ?>"
				   class="fancy-product-gallery"
				   data-fancybox-group="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
				   data-fancybox="product-gallery-<?= esc_attr( $gallery_uid ); ?>"
				   data-elementor-open-lightbox="no">
					<?php if ( $params['poster'] && $params['video_type'] != 'self' ): ?>
						<div class="image-inner" style="aspect-ratio: <?= $params['aspect_ratio'] ?>">
							<i class="icon-play <?= $params['video_type'] ?>"></i>
							<?php if ( $product_gallery_data['product_page_skeleton_loader'] ): ?>
								<span class="preloader skeleton product-grid-gallery-skeleton skeleton--video"></span>
							<?php endif; ?>
							<img src="<?= esc_url( $thumb_image_url[0] ); ?>"
								<?php if ( thegem_get_option( 'product_gallery_retina_ready' ) ): ?>
									srcset="<?php echo esc_url( $thumb_image_url_2x[0] ); ?> 2x"
								<?php endif; ?>
								 class="img-responsive" style="width: 100%; height: auto;"
								 width="<?=$thumb_image_url[1]?>" height="<?=$thumb_image_url[2]?>"
								 alt="<?= thegem_gallery_get_alt_text( $params['poster_id'] ) ?>"
								 onload="if(this.previousElementSibling && this.previousElementSibling.classList.contains('preloader')) { this.previousElementSibling.remove(); }"
							/>
						</div>
					<?php else: ?>
						<?php if ( $product_gallery_data['product_page_skeleton_loader'] ): ?>
							<span class="preloader skeleton product-grid-gallery-skeleton skeleton--video"></span>
						<?php endif; ?>

						<?= $video_block ?>
					<?php endif; ?>
				</a>
			<?php else: ?>
				<?php if ( $product_gallery_data['product_page_skeleton_loader'] ): ?>
					<span class="preloader skeleton product-grid-gallery-skeleton skeleton--video"></span>
				<?php endif; ?>

				<?= $video_block ?>
			<?php endif; ?>
		</div>
	<?php } ?>
	<?php echo '</div></div>';
}

function thegem_woocommerce_single_product_quick_view_gallery() {
	global $post, $product;
	$product_gallery_data = thegem_get_output_product_page_data( $product->get_id() );

	$attachments_ids = array();
	if ( has_post_thumbnail() ) {
		$attachments_ids = array( get_post_thumbnail_id() );
	}
	$attachments_ids = array_merge( $attachments_ids, $product->get_gallery_image_ids() );
	if ( 'variable' === $product->get_type() ) {
		foreach ( $product->get_available_variations() as $variation ) {
			if ( has_post_thumbnail( $variation['variation_id'] ) ) {
				$thumbnail_id = get_post_thumbnail_id( $variation['variation_id'] );
				if ( ! in_array( $thumbnail_id, $attachments_ids ) ) {
					$attachments_ids[] = $thumbnail_id;
				}
			}
		}
	}
	if ( empty( $attachments_ids ) ) {
		return;
	}
	$gallery_uid = uniqid();
	echo '<div class="preloader"><div class="preloader-spin"></div></div>';

	$isLegacy = thegem_get_option( 'product_page_layout' ) == 'legacy';
	$isLegacyGallery = thegem_get_option( 'product_gallery' ) == 'legacy';
	$dataThumb = $isLegacy ? 'dots' : 'none';
	$dataAutoHeight = $isLegacy ? '0' : '1';

	if ( !$isLegacyGallery ) {
		echo '<div class="product-gallery gem-quick-view-gallery" data-thumb="'.$dataThumb.'" data-loop="0" data-auto-height="'.$dataAutoHeight.'" data-colors="#00bcd4">';

		//Labels
		if ( $product_gallery_data['product_gallery_labels'] ) { ?>
			<div class="product-gallery-labels"><?=thegem_woocommerce_single_product_gallery_labels()?></div>
		<?php }

		foreach ( $attachments_ids as $attachments_id ) {
			$preview_image_url = $isLegacy
				? wp_get_attachment_image_src( $attachments_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) )
				: wp_get_attachment_image_src( $attachments_id, 'full' );
			?>
			<?php if ( $preview_image_url ): ?>
				<div class="product-gallery-slider-item" data-image-id="<?= esc_attr( $attachments_id ); ?>">
					<div class="product-gallery-image">
						<img src="<?= esc_url( $preview_image_url[0] ); ?>" alt="<?=thegem_gallery_get_alt_text($attachments_id)?>" class="img-responsive">
					</div>
				</div>
			<?php endif; ?>
			<?php
		}

		$product_video_data = get_post_meta( $post->ID, 'thegem_product_video', true );
		$product_video      = thegem_get_sanitize_product_video_data( $product_video_data );
		$video_type         = $product_video['product_video_type'];
		$video              = $product_video['product_video_id'];
		$video_self         = $product_video['product_video_link'];
		$poster             = $product_video['product_video_thumb'];

		if (!empty($video) && $video_type == 'youtube'){
			$youtube_id = thegem_parcing_youtube_url($video);
		}

		if (!empty($video) && $video_type == 'vimeo') {
			$vimeo_id = thegem_parcing_vimeo_url($video);
		}

		if ( $video_type == 'youtube' || $video_type == 'vimeo' ) {
			if ( $video_type == 'youtube' && $youtube_id ) {
				$video_block = '<div id="productYoutubeVideo" data-yt-id="'.$youtube_id.'"></div>';
			}
			if ( $video_type == 'vimeo' && $vimeo_id ) {
				$video_block = '<div id="productVimeoVideo" data-vm-id="'.$vimeo_id.'"></div>';
			}
		} else if ( $video_type == 'self' ) {
			$video_block = '<video id="productSelfVideo" class="fancybox-video" controls disablePictureInPicture controlsList="nodownload" loop="loop" src="' . $video_self . '" muted="muted"' . ( $poster ? ' poster="' . esc_url( $poster ) . '"' : '' ) . '></video>';
		}

		if ( isset( $video_block ) ) {
			?>
			<div class="product-gallery-slider-item video-block" data-video-type="<?= $video_type ?>"><?= $video_block ?></div>
			<?php
		}

	} else {
		echo '<div class="gem-simple-gallery gem-quick-view-gallery gem-gallery-hover-default responsive">';
		foreach ( $attachments_ids as $attachments_id ) {
			$preview_image_url = wp_get_attachment_image_src( $attachments_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) );
			?>
			<div class="gem-gallery-item">
				<div class="gem-gallery-item-image">
					<img src="<?php echo esc_url( $preview_image_url[0] ); ?>" alt="<?=thegem_gallery_get_alt_text($attachments_id)?>" class="img-responsive">
				</div>
			</div>
			<?php
		}
	}
	echo '</div>';
}

function thegem_woocommerce_single_product_quick_view_details() {
	$addToCartTextColor = thegem_get_option('product_page_button_add_to_cart_color') ? thegem_get_option('product_page_button_add_to_cart_color') : thegem_get_option('button_text_basic_color');
	$addToCartTextColorHover = thegem_get_option('product_page_button_add_to_cart_color_hover') ? thegem_get_option('product_page_button_add_to_cart_color_hover') : thegem_get_option('button_text_hover_color');
	$addToCartBackground = thegem_get_option('product_page_button_add_to_cart_background') ? thegem_get_option('product_page_button_add_to_cart_background') : thegem_get_option('styled_elements_color_1');
	$addToCartBackgroundHover = thegem_get_option('product_page_button_add_to_cart_background_hover') ? thegem_get_option('product_page_button_add_to_cart_background_hover') : thegem_get_option('button_background_hover_color');
	$addToCartBorder = '0';
	$addToCartBorderRadius = '0';
	$permalink = get_permalink( get_the_id() );

	thegem_button( array(
		'tag' => 'a',
		'href' => $permalink,
		'style' => 'flat',
		'size' => 'small',
		'text' => esc_html__('View Details', 'thegem'),
		'position' => 'fullwidth',
		'text_color' => $addToCartTextColor,
		'hover_text_color' => $addToCartTextColorHover,
		'background_color' => $addToCartBackground,
		'hover_background_color' => $addToCartBackgroundHover,
		'border' => $addToCartBorder,
		'corner' => $addToCartBorderRadius,
		'attributes' => array(
			'class' => 'quick-view-details-button',
			'rel' => 'nofollow'
		),
	), 1 );
}

function thegem_woocommerce_single_product_page_content() {
	$template_post_id = get_queried_object_id();
	if(empty($template_post_id) && isset($_REQUEST['editor_post_id'])) {
		$template_post_id = $_REQUEST['editor_post_id'];
	}
	if(get_post_type($template_post_id) === 'thegem_templates' && class_exists('\Elementor\Plugin')) {
		$editor = \Elementor\Plugin::$instance->editor;
		$is_edit_mode = $editor->is_edit_mode();
		$editor->set_edit_mode( false );
		$post_id = get_the_ID();
		$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $post_id );
		if ( $document->is_built_with_elementor() && $template_post_id != $post_id) {
			echo '<div class="product-content entry-content">' . \Elementor\Plugin::$instance->frontend->get_builder_content( $post_id, $is_edit_mode ) . '<div>';
		} else {
			echo '<div class="product-content entry-content">';
			the_content();
			echo '</div>';
		}
		\Elementor\Plugin::$instance->editor->set_edit_mode( $is_edit_mode );
	} elseif(get_the_content() || (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode())) {
?>
<div class="product-content entry-content"><?php the_content(); ?></div>
<?php
	}
}

function thegem_woocommerce_output_related_products_args($args) {
	global $thegem_product_data;
	$args['posts_per_page'] = 6;
	$args['columns'] = 6;
	if(thegem_get_option('product_archive_type') !== 'legacy') {
		$args['posts_per_page'] = $thegem_product_data['product_page_elements_related_items'];
		$args['columns'] = 1;
	}
	return $args;
}
add_filter('woocommerce_output_related_products_args', 'thegem_woocommerce_output_related_products_args');

function thegem_loop_shop_per_page() {
	$pc = !empty($_REQUEST['product_count']) && intval($_REQUEST['product_count']) > 0 ? intval($_REQUEST['product_count']) : 12;
	return $pc;
}
add_filter('loop_shop_per_page', 'thegem_loop_shop_per_page', 15);

function thegem_woocommerce_product_per_page_select() {
	$products_per_page_items = array(12,24,48);
	$pc = !empty($_REQUEST['product_count']) && intval($_REQUEST['product_count']) > 0 ? intval($_REQUEST['product_count']) : 12;
?>
<div class="woocommerce-select-count">
	<select id="products-per-page" name="products_per_page" class="gem-combobox" onchange="window.location.href=jQuery(this).val();">
		<?php foreach($products_per_page_items as $products_per_page_item) : ?>
			<option value="<?php echo esc_url(add_query_arg('product_count', $products_per_page_item)); ?>" <?php selected($pc, $products_per_page_item); ?>><?php printf(esc_html__('Show %d On Page', 'thegem'), $products_per_page_item); ?></option>
		<?php endforeach; ?>
	</select>
</div>
<?php
}

function thegem_woocommerce_before_shop_content() {
	echo '<div class="products-list">';
}
function thegem_woocommerce_after_shop_content() {
	echo '</div><!-- .products-list -->';
}

function thegem_woocommerce_before_shop_loop_start() {
	echo '<div class="before-products-list rounded-corners clearfix">';
}
function thegem_woocommerce_before_shop_loop_end() {
	echo '</div>';
}

function thegem_woocommerce_single_product_navigation() {
	global $thegem_product_data;

	if($thegem_product_data['product_page_layout'] !== 'legacy') return;
?>
<div class="block-navigation">
	<?php if($post = get_previous_post()) : ?>
		<div class="block-product-navigation-prev">
			<?php thegem_button(array(
				'text' => __('Prev', 'thegem'),
				'href' => get_permalink($post->ID),
				'style' => 'outline',
				'size' => 'tiny',
				'position' => 'left',
				'icon' => 'prev',
				'border_color' => thegem_get_option('button_background_basic_color'),
				'text_color' => thegem_get_option('button_background_basic_color'),
				'hover_background_color' => thegem_get_option('button_background_basic_color'),
				'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
			), 1); ?>
		</div>
	<?php endif; ?>
	<?php if($post = get_next_post()) : ?>
		<div class="block-product-navigation-next">
			<?php thegem_button(array(
				'text' => __('Next', 'thegem'),
				'href' => get_permalink($post->ID),
				'style' => 'outline',
				'size' => 'tiny',
				'position' => 'right',
				'icon' => 'next',
				'icon_position' => 'right',
				'border_color' => thegem_get_option('button_background_basic_color'),
				'text_color' => thegem_get_option('button_background_basic_color'),
				'hover_background_color' => thegem_get_option('button_background_basic_color'),
				'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
			), 1); ?>
		</div>
	<?php endif; ?>
</div><!-- .block-product-navigation -->
<?php
}

function thegem_product_quick_view_navigation() {
	global $thegem_product_data;

	if($thegem_product_data['product_page_layout'] !== 'legacy') return;
?>
<div class="product-quick-view-navigation">
	<?php if($post = get_previous_post()) : ?>
		<?php thegem_button(array(
			'style' => 'outline',
			'size' => 'tiny',
			'icon' => 'prev',
			'border_color' => thegem_get_option('button_background_basic_color'),
			'text_color' => thegem_get_option('button_background_basic_color'),
			'hover_background_color' => thegem_get_option('button_background_basic_color'),
			'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
			'attributes' => array(
				'data-product-id' => $post->ID
			)
		), 1); ?>
	<?php endif; ?>
	<?php if($post = get_next_post()) : ?>
		<?php thegem_button(array(
			'style' => 'outline',
			'size' => 'tiny',
			'icon' => 'next',
			'icon_position' => 'right',
			'border_color' => thegem_get_option('button_background_basic_color'),
			'text_color' => thegem_get_option('button_background_basic_color'),
			'hover_background_color' => thegem_get_option('button_background_basic_color'),
			'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
			'attributes' => array(
				'data-product-id' => $post->ID
			)
		), 1); ?>
	<?php endif; ?>
</div>
<?php
}

function thegem_woocommerce_show_product_loop_featured_flash() {
	global $post, $product;
	if(thegem_product_need_new_label($product->get_id())) {
		echo apply_filters('woocommerce_featured_flash', '<span class="new-label title-h6">' . esc_html__( 'New', 'thegem' ) . '</span>', $post, $product);
	}
}

function thegem_woocommerce_show_product_loop_out_of_stock_flash() {
	global $post, $product;
	if(!$product->is_in_stock()) {
		echo apply_filters('woocommerce_out_of_stock_flash', '<span class="out-of-stock-label title-h6">' . wp_kses(__('Out <span class="small">of stock</span>', 'thegem'), array('span' => array('class' => array()))) . '</span>', $post, $product);
	}
}

function thegem_woocommerce_after_shop_loop_item_link() {
	global $post, $product;
	echo '<a href="'.esc_url(get_the_permalink()).'" class="bottom-product-link"></a>';
}

function thegem_woocommerce_after_shop_loop_item_wishlist() {
	global $post, $product;
	if(!thegem_get_option('catalog_view') && defined( 'YITH_WCWL' )) {
		echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
	}
}

function thegem_woocommerce_after_shop_loop_item_linebreak() {
	echo '<div class="product-bottom-linebreak"></div>';
}

function thegem_woocommerce_back_to_shop_button() {
	global $thegem_product_data;

	if($thegem_product_data['product_page_layout'] !== 'legacy') return;

	thegem_button(array(
		'href' => get_permalink(wc_get_page_id('shop')),
		'style' => 'outline',
		'size' => 'tiny',
		'position' => 'right',
		'icon' => 'prev',
		'border_color' => thegem_get_option('button_background_basic_color'),
		'text_color' => thegem_get_option('button_background_basic_color'),
		'hover_background_color' => thegem_get_option('button_background_basic_color'),
		'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
		'extra_class' => 'back-to-shop-button'
	), 1);
}

function thegem_woocommerce_rating_separator() {
	global $thegem_product_data;

	if($thegem_product_data['product_page_layout'] !== 'legacy') return;

	echo '<div class="rating-divider"></div>';
}

function thegem_woocommerce_size_guide() {
	global $product;

	$product_size_guide_data = thegem_get_sanitize_product_size_guide_data($product->get_id());
	$size_guide_image = thegem_get_option('size_guide_image');
	$size_guide_text = thegem_get_option('size_guide_text');
	if($product_size_guide_data['size_guide'] == 'disabled') {
		$size_guide_image = '';
	} elseif($product_size_guide_data['size_guide'] == 'custom' && $product_size_guide_data['custom_image']) {
		$size_guide_image = $product_size_guide_data['custom_image'];
		$size_guide_text = $product_size_guide_data['custom_text'];
	}
?>
	<?php if($size_guide_image) : ?>
		<div class="size-guide"><a href="<?= esc_url($size_guide_image); ?>" class="fancybox"><?= esc_html($size_guide_text); ?></a></div>
	<?php endif; ?>
<?php
}

function thegem_yith_wcwl_add_to_wishlist_params($additional_params, $atts) {
	if(isset($atts['thegem_product_page']) && $atts['thegem_product_page']) {
		$additional_params['thegem_product_page'] = 1;
		$additional_params['container_classes'] .= ' gem-single-wl-button';
	} else {
		$additional_params['container_classes'] .= ' gem-list-wl-button';
	}
	if(isset($atts['thegem_template']) && $atts['thegem_template']) {
		$additional_params['thegem_template'] = 1;
	}
	if(isset($atts['thegem_products_grid']) && $atts['thegem_products_grid']) {
		$additional_params['thegem_products_grid'] = 1;
		$additional_params['container_classes'] .= ' icon';
	}
	return $additional_params;
}
add_filter('yith_wcwl_add_to_wishlist_params', 'thegem_yith_wcwl_add_to_wishlist_params', 10, 2);

function thegem_yith_wcwl_add_to_wishlist_button() {
	if(defined( 'YITH_WCWL' )) {
		echo do_shortcode( '[yith_wcwl_add_to_wishlist thegem_product_page="1"]' );
	}
}

add_filter('yith_wcwl_is_wishlist_responsive', function() { return false; });

add_filter('yith_wcwl_main_style_deps', function() { return array(); });
//add_filter('yith_wcwl_main_script_deps', function() { return array('jquery'); });

function thegem_remove_yith_wcwl_scripts() {
	//wp_dequeue_style('jquery-selectBox');
	wp_dequeue_style('yith-wcwl-font-awesome');
	//wp_dequeue_script('jquery-selectBox');
}
add_action('wp_enqueue_scripts', 'thegem_remove_yith_wcwl_scripts', 15);

function thegem_woocommerce_template_loop_product_hover_thumbnail() {
	global $post, $product;
	$gallery = $product->get_gallery_image_ids();
	$product_hover = get_post_meta($post->ID, 'thegem_product_disable_hover', true);
	$output = '<span class="woo-product-overlay"></span>';
	if(isset($gallery[0]) && !$product_hover) {
		if(thegem_get_option('woocommerce_activate_images_sizes')) {
			$image = thegem_get_thumbnail_image($gallery[0], 'thegem-product-catalog', false, array( 'class' => "attachment woo-product-hover"));
		} else {
			$image = wp_get_attachment_image($gallery[0], 'woocommerce_thumbnail', false, array( 'class' => "attachment woo-product-hover"));
		}
		if(!empty($image)) $output = $image;
	}
	echo $output;
}

function thegem_woocommerce_template_loop_product_quick_view() {
	global $post, $product;

	if(thegem_get_option('product_quick_view') && !is_product()) {
		wp_enqueue_script( 'wc-single-product' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		wp_enqueue_script('thegem-product-quick-view');
		if (!is_product() && thegem_is_quick_view_default()) {
			wp_enqueue_script('thegem-quick-view');
			wp_enqueue_style('thegem-quick-view');
		}
		if(thegem_get_option('product_page_layout') == 'default') {
			if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
			}
			if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
			}
			if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
				wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
			}
		}
		if (thegem_get_option('product_gallery') != 'legacy') {
			wp_enqueue_style('thegem-product-gallery');
		} else {
			wp_enqueue_style('thegem-hovers');
		}
		echo '<span class="quick-view-button title-h6" data-product-id="'.$post->ID.'">'.esc_html__('Quick View', 'thegem').'</span>';
	}
}

function thegem_woocommerce_template_loop_category_title($category) {
	echo '<div class="category-overlay">';
	echo '<h6 class="category-title">'.$category->name.'</h6>';
	echo '<div class="category-overlay-separator"></div>';
	echo '<div class="category-count">'.sprintf(esc_html(_n('%s item', '%s items', $category->count, 'thegem')), $category->count).'</div>';
	echo '</div>';
}

function thegem_woocommerce_dropdown_variation_attribute_options_args($args) {
	global $thegem_product_data;
	$isLegacy = $thegem_product_data['product_page_layout'] == 'legacy';

	$args['class'] = !$isLegacy ? 'thegem-select' : 'gem-combobox';

	return $args;
}

function thegem_woocommerce_review_gravatar_size($size) {
	return '70';
}

function thegem_woocommerce_product_review_comment_form_args($args) {
	if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
		$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

		$cookies = sprintf(
			'<p class="comment-form-cookies-consent col-md-12 col-xs-12">%s %s</p>',
			sprintf(
				'<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%s class="gem-checkbox" />',
				$consent
			),
			sprintf(
				'<label for="wp-comment-cookies-consent">%s</label>',
				__( 'Save my name, email, and website in this browser for the next time I comment.' )
			)
		);

		// Ensure that the passed fields include cookies consent.
		if ( isset( $args['fields'] ) && ! isset( $args['fields']['cookies'] ) ) {
			$args['fields']['cookies'] = $cookies;
		}
	}
	return $args;
}

if (thegem_get_option('product_archive_type') == 'legacy'){
	remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
	remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
	add_action( 'woocommerce_after_shop_loop', 'thegem_woocommerce_after_shop_content', 15);
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 15 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_product_archive_description', 15 );
}

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action('woocommerce_after_shop_loop', 'thegem_woocommerce_product_page_ajax_notification', 55);

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_content', 4);
add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_start', 11);
add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 15);
add_action('woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_product_per_page_select', 30);
add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 40);
add_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_end', 45);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action('woocommerce_shop_loop_item_labels', 'woocommerce_show_product_loop_sale_flash', 5);
add_action('woocommerce_shop_loop_item_labels', 'thegem_woocommerce_show_product_loop_featured_flash', 10);
add_action('woocommerce_shop_loop_item_labels', 'thegem_woocommerce_show_product_loop_out_of_stock_flash', 10);
add_action('woocommerce_shop_loop_item_image', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_shop_loop_item_image', 'thegem_woocommerce_template_loop_product_hover_thumbnail', 15);
add_action('woocommerce_shop_loop_item_image', 'thegem_woocommerce_template_loop_product_quick_view', 40);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

add_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
add_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);
//add_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_linebreak', 17);

add_action('thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery', 5);
add_action('thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery_alternative', 5);
add_action('thegem_woocommerce_single_product_left', 'thegem_woocommerce_single_product_gallery_grid', 5);
if (!thegem_get_option('product_hide_social_sharing')){
	add_action('thegem_woocommerce_single_product_left', 'thegem_socials_sharing', 10);
	add_action('woocommerce_before_single_product_summary', 'thegem_socials_sharing',30);
}
add_action('thegem_woocommerce_single_product_left', 'woocommerce_template_single_meta', 15);
add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_meta', 35);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

add_action('thegem_woocommerce_single_product_quick_view_left', 'thegem_woocommerce_single_product_quick_view_gallery', 5);
if (thegem_is_quick_view_default()) {
	add_action( 'thegem_woocommerce_single_product_quick_view_left', 'thegem_woocommerce_single_product_quick_view_details', 10 );
}

add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_back_to_shop_button', 5);
add_action('woocommerce_single_product_summary', 'thegem_woocommerce_back_to_shop_button', 4);
add_action('woocommerce_single_product_summary', 'thegem_woocommerce_product_page_navigation', 5);
add_action('woocommerce_single_product_summary', 'thegem_woocommerce_product_page_attribute', 6);
add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_title', 10);
add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_rating', 20);
add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_rating_separator', 25);
add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_price', 30);
add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_excerpt', 35);
add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_add_to_cart', 45);
add_action('thegem_woocommerce_single_product_right', 'thegem_woocommerce_size_guide', 50);
add_action('woocommerce_single_product_summary', 'thegem_woocommerce_size_guide', 35);
add_action('woocommerce_after_single_product', 'thegem_woocommerce_product_page_ajax_notification', 55);

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'thegem_woocommerce_dropdown_variation_attribute_options_args', 20 );
add_filter( 'woocommerce_product_description_heading', '__return_false', 20 );
add_filter( 'woocommerce_product_additional_information_heading', '__return_false', 20 );

add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_title', 10);
add_action('thegem_woocommerce_single_product_quick_view_right', 'thegem_woocommerce_product_page_attribute', 15);
add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_rating', 20);
add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_price', 30);
add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_excerpt', 35);
add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_add_to_cart', 45);
add_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_meta', 55);
add_action('thegem_woocommerce_single_product_quick_view_bottom', 'thegem_product_quick_view_navigation', 10);
if (thegem_is_quick_view_default()) {
	add_action('thegem_woocommerce_single_product_quick_view_bottom', 'thegem_woocommerce_product_page_ajax_notification', 15);
}

add_action('thegem_woocommerce_after_add_to_cart_button', 'thegem_yith_wcwl_add_to_wishlist_button');
add_filter('yith_wcwl_show_add_to_wishlist', '__return_false', 20);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('thegem_woocommerce_single_product_bottom', 'woocommerce_output_product_data_tabs', 5);
add_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_navigation', 10);
add_action('thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_page_content', 15);

add_action('thegem_woocommerce_after_single_product', 'woocommerce_output_related_products', 5);

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'thegem_woocommerce_template_loop_category_title', 10 );

add_filter( 'woocommerce_review_gravatar_size', 'thegem_woocommerce_review_gravatar_size', 20 );
add_filter( 'woocommerce_product_review_comment_form_args', 'thegem_woocommerce_product_review_comment_form_args', 20 );

function thegem_woocommerce_product_page_init(){
	global $thegem_product_data;

	if(empty($thegem_product_data)) {
		$thegem_page_id = get_the_ID();
		$thegem_product_data = thegem_get_output_product_page_data($thegem_page_id);
	}

	$params = [
		'isLegacy'                 => $thegem_product_data['product_page_layout'] == 'legacy',
		'isGalleryNative'          => $thegem_product_data['product_gallery'] == 'native',
		'isGalleryGrid'            => $thegem_product_data['product_gallery_type'] == 'grid',
		'isGalleryGridHideGaps'    => $thegem_product_data['product_gallery_grid_gaps_hide'],
		'isAccordion'              => $thegem_product_data['product_page_desc_review_layout'] == 'accordion',
		'isAccordionNextToGallery' => $thegem_product_data['product_page_desc_review_layout_acc_position'] == 'next_to_gallery',
		'isSocialSharing'          => $thegem_product_data['product_page_elements_share'],
		'isDescriptionBuilder'     => $thegem_product_data['product_page_desc_review_source'] == 'page_builder',
		'isMeta'                   => $thegem_product_data['product_page_elements_sku'] ||
		                              $thegem_product_data['product_page_elements_categories'] ||
		                              $thegem_product_data['product_page_elements_tags'],
	];

	if ( ! $params['isLegacy'] ) {
		//Meta and socials to right column
		remove_action( 'thegem_woocommerce_single_product_left', 'thegem_socials_sharing', 10 );
		remove_action( 'thegem_woocommerce_single_product_left', 'woocommerce_template_single_meta', 15 );

		if ( $params['isMeta'] ) {
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}
		if ( $params['isSocialSharing'] ) {
			add_action( 'woocommerce_single_product_summary', 'thegem_socials_sharing', 50 );
		}
		if ( $params['isGalleryNative'] ) {
			remove_action( 'woocommerce_before_single_product_summary', 'thegem_socials_sharing', 30 );
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_meta', 35 );
		}
		if ( $params['isGalleryGrid'] && $params['isGalleryGridHideGaps'] ) {
			add_action( 'woocommerce_single_product_summary', 'thegem_woocommerce_product_page_breadcrumbs', 4 );
		}
		//Accordion to right column
		if ( $params['isAccordion'] && $params['isAccordionNextToGallery'] ) {
			remove_action( 'thegem_woocommerce_single_product_bottom', 'woocommerce_output_product_data_tabs', 5 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 35 );
		}
		if ( $params['isDescriptionBuilder'] ) {
			remove_action( 'thegem_woocommerce_single_product_bottom', 'thegem_woocommerce_single_product_page_content', 15 );
		}
	}
}
add_action( 'woocommerce_before_single_product', 'thegem_woocommerce_product_page_init');

function thegem_woocommerce_product_page_navigation() {
	global $product, $thegem_product_data;

	if ( $thegem_product_data['product_page_layout'] === 'legacy' ) return;

	$isNavigate = $thegem_product_data['product_page_layout'] != 'legacy' && ( $thegem_product_data['product_page_elements_prev_next'] || $thegem_product_data['product_page_elements_back_to_shop'] );

	$back_to_shop_url = 'javascript:void(0);';
	switch ( $thegem_product_data['product_page_elements_back_to_shop_link'] ) {
		case 'main_shop':
			$back_to_shop_url = get_permalink( wc_get_page_id( 'shop' ) );
			break;
		case 'category':
			$terms = get_the_terms( $product->get_id(), 'product_cat' );
			foreach ( $terms as $term ) {
				$product_cat_id   = $term->term_id;
				$back_to_shop_url = get_term_link( $product_cat_id, 'product_cat' );
				break;
			}
			break;
		case 'custom_url':
			$back_to_shop_url = esc_url( $thegem_product_data['product_page_elements_back_to_shop_link_custom_url'] );
			break;
	}
	?>

	<?php if ( $isNavigate ): ?>
		<div class="product-page__nav">
			<ul class="product-page__nav-list">
				<?php if ( ( $post = get_previous_post() ) && $thegem_product_data['product_page_elements_prev_next'] ): ?>
					<li>
						<a class="product-page__nav--prev" href="<?= get_permalink( $post->ID ) ?>">
							<?php if ( $thegem_product_data['product_page_elements_preview_on_hover'] ) : $product = wc_get_product( $post->ID ); ?>
								<div class="product-page__nav-preview-wrap">
									<div class="product-page__nav-preview">
										<div class="nav-preview__image"><?= get_the_post_thumbnail( $post->ID, 'thegem-product-thumbnail' ) ?></div>
										<div class="nav-preview__info">
											<div class="nav-preview__info-title">
												<?= mb_strimwidth( get_the_title( $post->ID ), '0', '20', '...' ) ?>
											</div>
											<div class="nav-preview__info-price"><?= $product->get_price_html() ?></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if ( $thegem_product_data['product_page_elements_back_to_shop'] ): ?>
					<li>
						<a class="product-page__nav--back" href="<?= $back_to_shop_url ?>" aria-label="<?php esc_attr_e('Back to Shop', 'thegem'); ?>"></a>
					</li>
				<?php endif; ?>

				<?php if ( ( $post = get_next_post() ) && $thegem_product_data['product_page_elements_prev_next'] ): ?>
					<li>
						<a class="product-page__nav--next" href="<?= get_permalink( $post->ID ) ?>">
							<?php if ( $thegem_product_data['product_page_elements_preview_on_hover'] ) : $product = wc_get_product( $post->ID ); ?>
								<div class="product-page__nav-preview-wrap">
									<div class="product-page__nav-preview">
										<div class="nav-preview__image"><?= get_the_post_thumbnail( $post->ID, 'thegem-product-thumbnail' ) ?></div>
										<div class="nav-preview__info">
											<div class="nav-preview__info-title">
												<?= mb_strimwidth( get_the_title( $post->ID ), '0', '20', '...' ) ?>
											</div>
											<div class="nav-preview__info-price"><?= $product->get_price_html() ?></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php
	wp_reset_postdata();
}

function thegem_woocommerce_product_page_attribute() {
	global $product, $thegem_product_data;

	if ( $thegem_product_data['product_page_layout'] === 'legacy' ) return;

	$isAttribute = $thegem_product_data['product_page_elements_attributes'];
	$attr = esc_attr($thegem_product_data['product_page_elements_attributes_data']);
	$attrArray = wp_get_post_terms($product->get_id(), 'pa_'.$attr, array('fields' => 'names'));
	?>

	<?php if ($isAttribute && !is_wp_error($attrArray) && !empty($attrArray)): ?>
        <div class="product-page__attribute"><?=implode(", ", $attrArray)?></div>
	<?php endif; ?>

	<?php
}

function thegem_woocommerce_product_page_attribute_clear_text($output) {
	global $thegem_product_data;

	if($thegem_product_data['product_page_layout'] === 'legacy') return $output;

	$text = thegem_get_option('product_page_button_clear_attributes_text') ? thegem_get_option('product_page_button_clear_attributes_text') : esc_html__('Clear selection', 'thegem');

	$output = '<div class="product-page__reset-variations' . (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'thegem_product_quick_view' ? '' : ' hidden') . '">';
	$output .= '<a class="reset_variations" href="#"><i class="reset_variations--icon"></i><span>' . esc_html($text) . '</span></a>';
	$output .= '</div>';
	return $output;
}
add_filter('woocommerce_reset_variations_link' , 'thegem_woocommerce_product_page_attribute_clear_text', 15);

function thegem_woocommerce_product_page_ajax_add_to_cart() {
	$success = wc_notice_count( 'error' ) === 0;
	// Get messages
	ob_start();

	wc_print_notices();

	$notices = ob_get_clean();

	// Get mini cart
	ob_start();

	woocommerce_mini_cart();

	$mini_cart = ob_get_clean();

	// Fragments and mini cart are returned
	$data = array(
		'notices'   => $notices,
		'fragments' => apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		),
		'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
		'success' => $success
	);

	wp_send_json( $data );

	die();
}
add_action('wp_ajax_thegem_ajax_add_to_cart', 'thegem_woocommerce_product_page_ajax_add_to_cart');
add_action('wp_ajax_nopriv_thegem_ajax_add_to_cart', 'thegem_woocommerce_product_page_ajax_add_to_cart');

function thegem_woocommerce_product_page_ajax_add_to_cart_old() {
	ob_start();

	// phpcs:disable WordPress.Security.NonceVerification.Missing
	if ( ! isset( $_POST['product_id'] ) ) {
		return;
	}

	$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
	$product           = wc_get_product( $product_id );
	$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
	$product_status    = get_post_status( $product_id );
	$variation_id      = $_POST['variation_id'];
	$variation         = $_POST['variation'];

	if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation) && 'publish' === $product_status ) {
		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
		}

		WC_AJAX :: get_refreshed_fragments();
	} else {
		// If there was an error adding to the cart, redirect to the product page to show any errors.
		$data = array(
			'error'       => true,
			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
		);

		wp_send_json( $data );
	}
	// phpcs:enable
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'thegem_woocommerce_product_page_ajax_add_to_cart_old');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'thegem_woocommerce_product_page_ajax_add_to_cart_old');

function thegem_woocommerce_product_page_ajax_notification($params = false) {
	global $thegem_product_data;

	if (!$params) {
		if ( ($thegem_product_data && $thegem_product_data['product_page_layout'] === 'legacy') || (!is_singular( 'product' ) && thegem_get_option('product_archive_type') == 'legacy')) return;

		$params = array(
			'stay_visible' => thegem_get_option('product_archive_stay_visible'),
			'added_cart_text' => thegem_get_option('product_archive_added_cart_text'),
			'view_cart_button_text' => thegem_get_option('product_archive_view_cart_button_text'),
			'checkout_button_text' => thegem_get_option('product_archive_checkout_button_text'),
			'added_wishlist_text' => thegem_get_option('product_archive_added_wishlist_text'),
			'view_wishlist_button_text' => thegem_get_option('product_archive_view_wishlist_button_text'),
			'removed_wishlist_text' => thegem_get_option('product_archive_removed_wishlist_text'),
			'mini_cart_type' => thegem_get_option('mini_cart_type'),
		);
	}
	?>
	<div class="thegem-popup-notification-wrap<?php echo (empty($params['loop-product-add-to-cart']) ? '' : ' loop-product-add-to-cart-popup-notification'); ?>"
		<?php if (isset($params['style_uid'])) { ?>id="style-notification-<?php echo esc_attr($params['style_uid']); ?>"
		<?php } else { ?>data-style-uid="to_products"<?php } ?>>
		<?php if (empty($params['mini_cart_type']) || $params['mini_cart_type'] == 'dropdown'): ?>
            <div class="thegem-popup-notification cart" data-timing="<?= esc_attr($params['stay_visible']); ?>">
                <div class="notification-message">
					<?= esc_html($params['added_cart_text']); ?>
                    <span class="buttons">
						<a class="button" href="<?= esc_url(wc_get_cart_url()); ?>"><?= esc_html($params['view_cart_button_text']); ?></a>
						<a class="button" href="<?= esc_url(wc_get_checkout_url()); ?>"><?= esc_html($params['checkout_button_text']); ?></a>
					</span>
                </div>
            </div>
		<?php endif; ?>

		<?php if (defined('YITH_WCWL')): ?>
            <div class="thegem-popup-notification wishlist-add" data-timing="<?= esc_attr($params['stay_visible']); ?>">
                <div class="notification-message">
					<?= esc_html($params['added_wishlist_text']); ?>
                    <span class="buttons">
						<a class="button" href="<?= esc_url(YITH_WCWL()->get_wishlist_url()); ?>"<?php echo (empty($params['view_wishlist_button_text']) ? ' aria-label="' . esc_attr__('Wishlist', 'thegem') . '"' : ''); ?>><?=  esc_html($params['view_wishlist_button_text']); ?></a>
					</span>
                </div>
            </div>
            <div class="thegem-popup-notification wishlist-remove" data-timing="<?php echo esc_attr($params['stay_visible']); ?>">
                <div class="notification-message">
					<?php echo esc_html($params['removed_wishlist_text']); ?>
                </div>
            </div>
		<?php endif; ?>
    </div>
	<?php
}

// Thegem Ajax Notification Sidebar Start
function thegem_woocommerce_ajax_notification_sidebar() {
	$mini_cart_type = thegem_get_option('mini_cart_type');
	if (!defined( 'WC_PLUGIN_FILE' ) || (!empty($mini_cart_type) && $mini_cart_type == 'dropdown') || ((is_checkout() || is_cart()) && class_exists( '\WooCommerce\PayPalCommerce\PluginModule' ))) return;

	ob_start();
	woocommerce_mini_cart();
	$minicart = ob_get_clean();

	$params = [
		'title' => thegem_get_option('mini_cart_sidebar_title'),
		'view_cart_btn' => thegem_get_option('mini_cart_sidebar_view_cart_btn'),
		'infotext' => thegem_get_option('mini_cart_sidebar_infotext'),
	];

	$element_class = implode(' ',
		array(
			empty($params['view_cart_btn']) ? 'hide-cart-btn' : '',
			empty($params['infotext']) ? 'hide-infobox' : '',
		)
	);

	?>
	<div class="thegem-popup-notification-sidebar <?= $element_class ?>">
		<div class="notification-sidebar">
			<div class="notification-sidebar-caption">
				<?php if (!empty($params['title'])): ?>
					<div class="title"><?= esc_html__($params['title']) ?></div>
				<?php endif; ?>
				<div class="close"><?= __('Close', 'thegem') ?></div>
			</div>
			<div class="notification-sidebar-content">
				<div class="widget_shopping_cart_content"><?= $minicart ?></div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'thegem_woocommerce_ajax_notification_sidebar', 100 );

function thegem_notification_sidebar_after_mini_cart() {
	if (!empty(thegem_get_option('mini_cart_type')) && thegem_get_option('mini_cart_type') == 'dropdown') return;

	if(thegem_get_cart_count() == '0') {
		$template_enabled = thegem_get_option('mini_cart_empty_global_section_enabled');
		$template_id = intval(thegem_get_option('mini_cart_empty_global_section'));
		if(!empty($template_enabled) && $template_id > 0 && get_post($template_id) && defined('ELEMENTOR_VERSION')) {
			echo '<div class="minicart-empty-template-placeholder"></div>';
		} else {
			echo '<div class="woocommerce-mini-cart__empty">';
			echo '<div class="woocommerce-mini-cart__empty-icon"></div>';
			echo '<div class="woocommerce-mini-cart__empty-title">'.wp_kses_post(apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) )).'</div>';
			if(thegem_get_option('cart_empty_text')) {
				echo '<div class="woocommerce-mini-cart__empty-subtitle">'.wp_kses_post( nl2br(thegem_get_option('cart_empty_text')) ).'</div>';
			}
			echo '<div class="woocommerce-mini-cart__empty-link"><a class="gem-button gem-button-size-small" href="'.esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ).'">'.esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ).'</a></div>';
			echo '</div>';
		}
	}
}
add_action('woocommerce_after_mini_cart', 'thegem_notification_sidebar_after_mini_cart');

function thegem_notification_sidebar_mini_cart_before_buttons() {
	if (!empty(thegem_get_option('mini_cart_type')) && thegem_get_option('mini_cart_type') == 'dropdown') return;

	if (!empty(thegem_get_option('mini_cart_sidebar_infotext'))){
		echo '<div class="woocommerce-mini-cart__infobox">'.wp_kses_post( nl2br(thegem_get_option('mini_cart_sidebar_infotext')) ).'</div>';
	}
}
add_action('woocommerce_widget_shopping_cart_before_buttons', 'thegem_notification_sidebar_mini_cart_before_buttons');

function thegem_notification_sidebar_update_cart_item() {
	if (!empty(thegem_get_option('mini_cart_type')) && thegem_get_option('mini_cart_type') == 'dropdown') return;

	if ( ( isset( $_GET['item_id'] ) && $_GET['item_id'] ) && ( isset( $_GET['qty'] ) ) ) {
		global $woocommerce;
		if ( $_GET['qty'] ) {
			$woocommerce->cart->set_quantity( $_GET['item_id'], $_GET['qty'] );
		} else {
			$woocommerce->cart->remove_cart_item( $_GET['item_id'] );
		}
	}

	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wp_ajax_thegem_notification_sidebar_update_cart_item', 'thegem_notification_sidebar_update_cart_item' );
add_action( 'wp_ajax_nopriv_thegem_notification_sidebar_update_cart_item', 'thegem_notification_sidebar_update_cart_item' );
// Thegem Ajax Notification Sidebar End

function thegem_woocommerce_product_page_breadcrumbs() {
	global $product;

	$product_page_data = thegem_get_output_page_settings($product->get_id());

	$params = array(
		'bottom_spacing' => $product_page_data['page_layout_breadcrumbs_bottom_spacing'],
		'breadcrumbs' => $product_page_data['page_layout_breadcrumbs'],
		'breadcrumbs_alignment' => $product_page_data['page_layout_breadcrumbs_alignment'],
		'header_transparent' => $product_page_data['header_transparent'],
	);

	if (!is_post_type_archive('product') && $params['breadcrumbs']) { ?>
		<div class="page-breadcrumbs page-breadcrumbs--<?=$params['breadcrumbs_alignment']?>" style="<?php if ($params['header_transparent']) : ?>min-height: 40px; align-items: flex-start;<?php endif;?> <?php if ($params['bottom_spacing']) : ?>margin-bottom: <?=esc_attr($params['bottom_spacing']).'px'?><?php endif; ?>">
			<?= gem_breadcrumbs(true) ?>
		</div>
	<?php }
}

function thegem_cart_menu($items, $args) {
	if(defined( 'WC_PLUGIN_FILE' ) && $args->menu_id == 'primary-menu' && !thegem_get_option('hide_card_icon')) {
		if (thegem_get_option('cart_icon_pack') && thegem_get_option('cart_icon')) {
			wp_enqueue_style('icons-'.thegem_get_option('cart_icon_pack'));
		}

		$count = thegem_get_cart_count();
		ob_start();
		woocommerce_mini_cart();
		$minicart = ob_get_clean();

		$output = '';

		if (!empty(thegem_get_option('mini_cart_type')) && thegem_get_option('mini_cart_type') == 'dropdown') {
			$output = '<li class="menu-item menu-item-cart not-dlmenu"><a href="'.esc_url(get_permalink(wc_get_page_id('cart'))).'" class="minicart-menu-link ' . ($count == 0 ? 'empty' : '') . (thegem_get_option('cart_label_type') == 1 ? ' circle-count' : '') . '" aria-label="' . esc_attr__('Cart', 'thegem') . '">' . '<span class="minicart-item-count">' . $count . '</span>' . '</a><div class="minicart"><div class="widget_shopping_cart_content">'.$minicart.'</div></div></li>';
		}

		if ((!empty(thegem_get_option('mini_cart_type')) && thegem_get_option('mini_cart_type') == 'sidebar') || ((is_checkout() || is_cart()) && class_exists( '\WooCommerce\PayPalCommerce\PluginModule' ))) {
			$output = '<li class="menu-item menu-item-cart not-dlmenu"><a href="'.esc_url(get_permalink(wc_get_page_id('cart'))).'" class="minicart-menu-link ' . ($count == 0 ? 'empty' : '') . (thegem_get_option('cart_label_type') == 1 ? ' circle-count' : '') . '" aria-label="' . esc_attr__('Cart', 'thegem') . '">' . '<span class="minicart-item-count">' . $count . '</span>' . '</a></li>';
		}

		$items .= $output;

	}
	return $items;
}
add_filter('wp_nav_menu_items', 'thegem_cart_menu', 11, 2);

function thegem_woocommerce_placeholder_img($val, $size, $dimensions) {
	return '<span class="product-dummy-wrapper" style="max-width: '.intval($dimensions['width']).'px;"><span class="product-dummy" style="padding-bottom: '.(intval($dimensions['height'])*100/intval($dimensions['width'])).'%;"></span></span>';
}
add_filter('woocommerce_placeholder_img', 'thegem_woocommerce_placeholder_img', 10, 3);

function thegem_cart_short_info() {
	$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
	if($thegem_cart_layout == 'modern') return ;
	echo '<div class="cart-short-info">'.sprintf(wp_kses(__('You Have <span class="items-count">%d Items</span> In Your Cart', 'thegem'), array('span' => array('class' => array()))), WC()->cart->cart_contents_count).'</div>';
}
add_action('woocommerce_before_cart', 'thegem_cart_short_info', 15);
add_action('woocommerce_before_cart', 'woocommerce_breadcrumb', 10);

function thegem_cart_items_html_output() {
	thegem_cart_short_info();
	die(-1);
}
add_action('wp_ajax_thegem_cart_items_html', 'thegem_cart_items_html_output');
add_action('wp_ajax_nopriv_thegem_cart_items_html', 'thegem_cart_items_html_output');

function thegem_wc_add_to_cart_message($message, $products) {
	$titles = array();
	$count  = 0;

	$show_qty = true;

	if ( ! is_array( $products ) ) {
		$products = array( $products => 1 );
		$show_qty = false;
	}

	if ( ! $show_qty ) {
		$products = array_fill_keys( array_keys( $products ), 1 );
	}

	foreach ( $products as $product_id => $qty ) {
		$titles[] = ( $qty > 1 ? absint( $qty ) . ' &times; ' : '' ) . sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), strip_tags( get_the_title( $product_id ) ) );
		$count += $qty;
	}

	$titles = array_filter( $titles );

	$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', $count, 'woocommerce' ), wc_format_list_of_items( $titles ) );

	// Output success messages
	if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
		$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );

		$message = sprintf('<div class="cart-added"><div class="cart-added-text">%s</div><div class="cart-added-button"><a href="%s" class="gem-button button wc-forward">%s</a></div></div>', $added_text, esc_url($return_to), esc_html__('Continue shopping', 'woocommerce'));

	} else {

		$message = sprintf('<div class="cart-added"><div class="cart-added-text">%s</div><div class="cart-added-button"><a href="%s" class="gem-button button wc-forward">%s</a></div></div>', $added_text, esc_url(wc_get_page_permalink( 'cart' )), esc_html__('View cart', 'woocommerce'));

	}

	return $message;
}
add_filter('wc_add_to_cart_message_html', 'thegem_wc_add_to_cart_message', 10, 2);

function thegem_product_add_page_settings_boxes() {
	add_meta_box('thegem_page_title', esc_html__('Page Title', 'thegem'), 'thegem_page_title_settings_box', 'product', 'normal', 'high');
	add_meta_box('thegem_page_sidebar', esc_html__('Page Sidebar', 'thegem'), 'thegem_page_sidebar_settings_box', 'product', 'normal', 'high');
}
if ($thegem_use_old_page_options) {
	add_action('add_meta_boxes', 'thegem_product_add_page_settings_boxes');
}

function thegem_save_product_page_data($post_id) {
	if(
		!isset($_POST['thegem_page_title_settings_box_nonce']) ||
		!isset($_POST['thegem_page_sidebar_settings_box_nonce'])
	) {
		return;
	}
	if(
		!wp_verify_nonce($_POST['thegem_page_title_settings_box_nonce'], 'thegem_page_title_settings_box') ||
		!wp_verify_nonce($_POST['thegem_page_sidebar_settings_box_nonce'], 'thegem_page_sidebar_settings_box')
	) {
		return;
	}

	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && in_array($_POST['post_type'], array('product'))) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['thegem_page_data']) || !is_array($_POST['thegem_page_data'])) {
		return;
	}

	$page_data = array_merge(
		thegem_get_sanitize_page_title_data(0, $_POST['thegem_page_data']),
		thegem_get_sanitize_page_sidebar_data(0, $_POST['thegem_page_data'])
	);
	update_post_meta($post_id, 'thegem_page_data', $page_data);
}
if ($thegem_use_old_page_options) {
	add_action('save_post', 'thegem_save_product_page_data');
}

function thegem_product_tabs_template_section($id) {
	$template_id = intval($id);
	$return_html = '<p>'.esc_html__('Global Section', 'thegem').'</p>';
	if($template_id > 0 && $template = get_post($template_id) && defined('ELEMENTOR_VERSION')) {
		$return_html =\Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
		$return_html = '<div class="thegem-template-wrapper thegem-template-content thegem-template-' . esc_attr($template_id) . '">' . $return_html . '</div>';
	}

	return $return_html;
}

function thegem_product_tabs( $tabs = array() ) {
	global $product, $post, $thegem_product_data;

	$isLegacy = $thegem_product_data['product_page_layout'] == 'legacy';
	$isTabs = $thegem_product_data['product_page_desc_review_layout'] == 'tabs';
	$isAccordion = $thegem_product_data['product_page_desc_review_layout'] == 'accordion';
	$isTabsLegacy = $thegem_product_data['product_page_desc_review_layout_tabs_style'] == 'legacy';
	$isFullWidth = $thegem_product_data['product_page_layout_fullwidth'];
	$isDescriptionBuilder = $thegem_product_data['product_page_desc_review_source'] == 'page_builder';

	// Get Additional Tabs Data
	$additional_tabs = array();
	$product_page_data = get_post_meta( $post->ID, 'thegem_product_page_data', true );
	if (!empty($product_page_data['product_page_additional_tabs'])){
		if ($product_page_data['product_page_additional_tabs'] == 'default' && !empty(thegem_get_option('product_page_additional_tabs'))) {
			$additional_tabs = json_decode(thegem_get_option('product_page_additional_tabs_data'));
		} elseif($product_page_data['product_page_additional_tabs'] == 'custom'){
			$additional_tabs = json_decode($product_page_data['product_page_additional_tabs_data']);
		}
	} else {
		if (!empty(thegem_get_option('product_page_additional_tabs'))){
			$additional_tabs = json_decode(thegem_get_option('product_page_additional_tabs_data'));
		}
	}

	// Description tab - shows product content
	if ( get_post_meta( $post->ID, 'thegem_product_description', true ) ) {
		$tabs['description'] = array(
			'title'    => esc_html__( 'Description', 'woocommerce' ),
			'priority' => 10,
			'callback' => 'woocommerce_product_description_tab'
		);
	} elseif ( isset( $tabs['description'] ) ) {
		unset( $tabs['description'] );
	}

	//Show/Hide and Rename Tabs
	if (!$isLegacy) {

		// Thegem Default Tabs
		$show_description_tab = false;
		$description_tab_callback = '';
		if ( $thegem_product_data['product_page_desc_review_description']) {
			if($isDescriptionBuilder) {
				if(get_the_content() || (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode())) {
					$show_description_tab = true;
					$description_tab_callback = 'thegem_woocommerce_single_product_page_content';
				}
			} elseif(get_post_meta( $post->ID, 'thegem_product_description', true )) {
				$show_description_tab = true;
				$description_tab_callback = 'woocommerce_product_description_tab';
			}
		}
		if ( $show_description_tab ) {
			$tabs['description'] = array(
				'title' => esc_html__( $thegem_product_data['product_page_desc_review_description_title'], 'woocommerce'),
				'priority' => 10,
				'callback' => $description_tab_callback
			);
		} else {
			unset( $tabs['description'] );
		}

		if ( $thegem_product_data['product_page_desc_review_additional_info'] ) {
			$tabs['additional_information'] = array(
				'title'    => esc_html__( $thegem_product_data['product_page_desc_review_additional_info_title'], 'woocommerce'),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			);
		} elseif ( isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		if ( $thegem_product_data['product_page_desc_review_reviews'] )  {
		    $tabs['reviews'] = array(
				'title'    => $product->get_review_count() > 0 ? sprintf(esc_html__( $thegem_product_data['product_page_desc_review_reviews_title'], 'woocommerce' ).' <sup>%d</sup>', $product->get_review_count()) : esc_html__( $thegem_product_data['product_page_desc_review_reviews_title']),
				'priority' => 30,
				'callback' => 'comments_template',
			);
		} elseif ( isset( $tabs['reviews'] )) {
			unset( $tabs['reviews'] );
		}

		if ($thegem_product_data['product_page_desc_review_layout'] == 'accordion' && $thegem_product_data['product_page_desc_review_layout_acc_position'] == 'next_to_gallery' && !$isFullWidth){
			unset( $tabs['reviews'] );
		}

		// Thegem Additional Tabs
		if (!empty($additional_tabs)) {
			foreach ($additional_tabs as $tab) {
				$key = str_replace('_', '-', sanitize_title($tab->title));
				$text_content = ($tab->type == 'text' && !empty($tab->text_content)) ? $tab->text_content : '';
				$section_content = ($tab->type == 'section' && !empty($tab->section_content)) ? thegem_product_tabs_template_section($tab->section_content) : '';
				$priority = !empty($tab->priority) ? intval($tab->priority) : 100;

				if ( !empty($key) )  {
					$tabs[$key] = array(
						'title' => esc_html__($tab->title, 'thegem'),
						'priority' => $priority,
						'type' => 'additional_tab',
						'text_content' => $text_content,
						'section_content' => $section_content
					);
				} elseif ( isset( $tabs[$key] )) {
					unset( $tabs[$key] );
				}
			}
		}

		// Sorting tabs by priority
		uasort($tabs, function ($a, $b) {
			if ($a['priority'] == $b['priority']) {
				return 0;
			}

			return ($a['priority'] < $b['priority']) ? -1 : 1;
		});
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'thegem_product_tabs', 11 );

function thegem_woocommerce_subcategory_thumbnail( $category ) {
	$small_thumbnail_size = apply_filters( 'single_category_small_thumbnail_size', 'woocommerce_thumbnail' );
	$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true);
	$image = '';

	if ( $thumbnail_id ) {
		if(thegem_get_option('woocommerce_activate_images_sizes')) {
			$image = thegem_generate_thumbnail_src( $thumbnail_id, 'thegem-product-catalog' );
		} else {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
		}
		global $thegem_product_categories_images;
		if($thegem_product_categories_images) {
			$image = wp_get_attachment_image_src( $thumbnail_id, 'thegem-custom-product-categories');
		}
	}

	if ( $image ) {
		$image[0] = str_replace( ' ', '%20', $image[0] );
		echo '<img src="' . esc_url( $image[0] ) . '" width="'.esc_attr($image[1]).'" height="'.esc_attr($image[2]).'" alt="' . esc_attr( $category->name ) . '" class="img-responsive" />';
	} else {
		if(thegem_get_option('woocommerce_activate_images_sizes')) {
			echo wc_placeholder_img(array(thegem_get_option('woocommerce_catalog_image_width'), thegem_get_option('woocommerce_catalog_image_height'), 1));
		} else {
			echo wc_placeholder_img($small_thumbnail_size);
		}
	}
}
remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
add_action('woocommerce_before_subcategory_title', 'thegem_woocommerce_subcategory_thumbnail', 10);

add_filter('woocommerce_add_to_cart_fragments', 'gem_woocommerce_header_dropdown_cart_fragment');

function gem_woocommerce_header_dropdown_cart_fragment( $fragments ) {
	$count = thegem_get_cart_count();
	$fragments['a.minicart-menu-link:not(.temp)'] = '<a href="'.esc_url(get_permalink(wc_get_page_id('cart'))).'" class="minicart-menu-link ' . ($count == 0 ? 'empty' : '') . (thegem_get_option('cart_label_type') == 1 ? ' circle-count' : '') . '" aria-label="' . esc_attr__('Cart', 'thegem') . '"><span class="minicart-item-count">'.$count.'</span></a>';
	$fragments['a.minicart-menu-link.temp'] = '<a href="'.esc_url(get_permalink(wc_get_page_id('cart'))).'" class="minicart-menu-link temp ' . ($count == 0 ? 'empty' : '') . (thegem_get_option('cart_label_type') == 1 ? ' circle-count' : '') . '" aria-label="' . esc_attr__('Cart', 'thegem') . '"><span class="minicart-item-count">'.$count.'</span></a>';
	return $fragments;
}

function thegem_single_product_small_thumbnail_size($size) {
	global $thegem_product_categories_images;
	if($thegem_product_categories_images) {
		return 'thegem-custom-product-categories';
	}
	return $size;
}
add_filter( 'single_category_small_thumbnail_size', 'thegem_single_product_small_thumbnail_size' );

function thegem_woocommerce_get_image_size_categories($size) {
	$size = array(
		'width'  => '1170',
		'height' => '1117',
		'crop'   => 1
	);
	return $size;
}
add_filter( 'woocommerce_get_image_size_thegem-custom-product-categories', 'thegem_woocommerce_get_image_size_categories' );

function thegem_woocommerce_account_menu_item_classes($classes, $endpoint) {
	if(in_array('is-active', $classes)) {
		$classes[] = 'current-menu-ancestor';
	}
	return $classes;
}
add_filter('woocommerce_account_menu_item_classes', 'thegem_woocommerce_account_menu_item_classes', 10, 2);

function thegem_product_quick_view_output() {
	$nonce = empty($_REQUEST['ajax_nonce']) ? '' : $_REQUEST['ajax_nonce'];
	$product_id = empty($_REQUEST['product_id']) ? '' : $_REQUEST['product_id'];

	if(!wp_verify_nonce($nonce, 'product_quick_view_ajax_security' )) {
		die(-1);
	}

	$args = array(
		'posts_per_page'	  => 1,
		'post_type'		   => 'product',
		'post_status'		 => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'	   => 1,
	);

	if ( isset( $product_id ) ) {
		$args['p'] = absint( $product_id );
	}

	$single_product = new WP_Query( $args );

	$preselected_id = '0';

	ob_start();

	while ( $single_product->have_posts() ) :
		$single_product->the_post();
		$GLOBALS['thegem_product_data'] = thegem_get_output_product_page_data(get_the_id());
		//$GLOBALS['thegem_product_data']['product_page_layout'] = 'legacy';
		?>

		<div class="single-product" data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>" data-quick-view="<?=thegem_get_option('product_gallery')?>">

			<?php wc_get_template_part( 'content', 'single-product-quick-view' ); ?>

		</div>

	<?php endwhile; // end of the loop.

	wp_reset_postdata();
$time2= time();
	echo '<div class="woocommerce">' . ob_get_clean() . '</div>';

	die(-1);
}
add_action('wp_ajax_thegem_product_quick_view', 'thegem_product_quick_view_output');
add_action('wp_ajax_nopriv_thegem_product_quick_view', 'thegem_product_quick_view_output');

function thegem_catalog_view() {
	if(thegem_get_option('catalog_view', false, false, true)) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_price', 30);
		remove_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_add_to_cart', 45);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
		remove_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_price', 30);
		remove_action('thegem_woocommerce_single_product_quick_view_right', 'woocommerce_template_single_add_to_cart', 45);
		remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

		remove_action('thegem_woocommerce_single_product_left', 'thegem_socials_sharing', 10);
		remove_action('thegem_woocommerce_single_product_left', 'woocommerce_template_single_meta', 15);

		if (!thegem_get_option('product_hide_social_sharing')){
			add_action('thegem_woocommerce_single_product_right', 'thegem_socials_sharing', 65);
		}

		add_action('thegem_woocommerce_single_product_right', 'woocommerce_template_single_meta', 70);

		remove_action('wp_nav_menu_items', 'thegem_cart_menu', 11);

	}
}
add_action('init', 'thegem_catalog_view');
add_action('wp', 'thegem_catalog_view');

function thegem_woocommerce_form_field_args_callback($args, $key, $value) {
	if (stripos($key, 'shipping_') === 0) {
		$args['autofocus'] = false;
	}
	return $args;
}
add_filter('woocommerce_form_field_args', 'thegem_woocommerce_form_field_args_callback', 10, 3);

function thegem_woocommerce_loop_add_to_cart_link($link, $product, $args) {
	if ((strripos($link, 'add_to_cart_button') === false && in_array($product->get_type(), ['simple', 'variable'])) || (defined( 'YITH_YWRAQ_VERSION' ) && get_option( 'ywraq_hide_add_to_cart' ) === 'yes' && !in_array($product->get_type(), ['variable', 'external', 'grouped']))) {
		return '';
	}
	return sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		isset( $args['text'] ) ? $args['text'] : esc_html($product->add_to_cart_text())
	);
}
add_filter('woocommerce_loop_add_to_cart_link', 'thegem_woocommerce_loop_add_to_cart_link', 10, 3);

function thegem_woocommerce_structured_data() {
	if(isset($GLOBALS['woocommerce']) && isset($GLOBALS['woocommerce']->structured_data)) {
		add_action('thegem_woocommerce_single_product_right', array($GLOBALS['woocommerce']->structured_data, 'generate_product_data'), 60);
	}
}
add_action('init', 'thegem_woocommerce_structured_data');

function thegem_single_product_archive_thumbnail_size($size) {
	if(thegem_get_option('woocommerce_activate_images_sizes')) {
		return 'thegem-product-catalog';
	}
	return $size;
}
add_filter( 'single_product_archive_thumbnail_size', 'thegem_single_product_archive_thumbnail_size' );
add_filter( 'subcategory_archive_thumbnail_size', 'thegem_single_product_archive_thumbnail_size' );

function thegem_woocommerce_get_image_size_thumbnail($size) {
	if(thegem_get_option('woocommerce_activate_images_sizes')) {
		return array(
			'width' => thegem_get_option('woocommerce_thumbnail_image_width'),
			'height' => thegem_get_option('woocommerce_thumbnail_image_height'),
			'crop' => 1,
		);
	}
	return $size;
}
add_filter( 'woocommerce_get_image_size_thumbnail', 'thegem_woocommerce_get_image_size_thumbnail' );

function thegem_woocommerce_product_get_image( $image, $product, $size, $attr, $placeholder, $image_o) {
	if(thegem_get_option('woocommerce_activate_images_sizes') && $size == 'thegem-product-catalog') {
		if ( $product->get_image_id() ) {
			$image = thegem_get_thumbnail_image($product->get_image_id(), $size, false, $attr);
		} elseif ( $product->get_parent_id() ) {
			$parent_product = wc_get_product( $product->get_parent_id() );
			$image = $parent_product->get_image( $size, $attr, $placeholder );
		} elseif ( $placeholder ) {
			$image = wc_placeholder_img( $size );
		} else {
			$image = '';
		}
	}
	return $image;
}
add_filter( 'woocommerce_product_get_image', 'thegem_woocommerce_product_get_image', 10, 6);

function thegem_mobile_cart_toggle() {
	if(defined( 'WC_PLUGIN_FILE' ) && thegem_get_option('mobile_cart_position', 'top') == 'top' && !thegem_get_option('hide_card_icon') && !thegem_get_option('catalog_view')) {
		if (thegem_get_option('cart_icon_pack') && thegem_get_option('cart_icon')) {
			wp_enqueue_style('icons-'.thegem_get_option('cart_icon_pack'));
		}
		$count = thegem_get_cart_count();
		echo '<div class="mobile-cart"><a href="'.esc_url(get_permalink(wc_get_page_id('cart'))).'" class="minicart-menu-link temp ' . ($count == 0 ? 'empty' : '') . (thegem_get_option('cart_label_type') == 1 ? ' circle-count' : '') . '" aria-label="' . esc_attr__('Cart', 'thegem') . '">' . '<span class="minicart-item-count">' . $count . '</span>' . '</a></div><div class="mobile-minicart-overlay"></div>';
	}
}
add_action('thegem_header_menu_opposite', 'thegem_mobile_cart_toggle');

function thegem_mobile_cart_before_mini_cart() {
	if(thegem_get_option('mobile_cart_position', 'top') == 'top' && !thegem_get_option('hide_card_icon') && !thegem_get_option('catalog_view')) {
		echo '<div class="mobile-cart-header">';
		echo '<div class="mobile-cart-header-title title-h6">'.esc_html__('Cart', 'woocommerce').'</div>';
		echo '<a class="mobile-cart-header-close" href="#" role="button" aria-label="' . esc_attr__('Close', 'thegem') . '"><span class="cart-close-line-1"></span><span class="cart-close-line-2"></span></a>';
		echo '</div>';
	}
}
add_action('woocommerce_before_mini_cart', 'thegem_mobile_cart_before_mini_cart');

function thegem_mobile_cart_toggle_body_class($classes) {
	if(defined( 'WC_PLUGIN_FILE' ) && thegem_get_option('mobile_cart_position', 'top') == 'top' && !thegem_get_option('hide_card_icon') && !thegem_get_option('catalog_view') && empty($_REQUEST['thegem_header_test'])) {
		$classes[] = 'mobile-cart-position-top';
	}
	return $classes;
}
add_filter('body_class', 'thegem_mobile_cart_toggle_body_class');

add_action('elementor/widget/render_content', function ($content, $widget) {
    if ('woocommerce-product-images' === $widget->get_name()) {
        $thegem_product_data = thegem_get_output_product_page_data(get_the_ID());

        $params = [
            'gallery' => $thegem_product_data['product_gallery'],
            'isGalleryGrid' => $thegem_product_data['product_gallery_type'] == 'grid',
        ];

        if (!$params['isGalleryGrid']) {
            switch ($params['gallery']) {
                case 'legacy':
                    $content = thegem_woocommerce_single_product_gallery();
                    break;
                case 'enabled':
                case 'native':
                    $content = thegem_woocommerce_single_product_gallery_alternative();
                    break;
                case 'disabled':
                    $content = '';
            }
        } elseif (!empty($GLOBALS['thegem_product_page_elementor_template'])) {
            $content = thegem_woocommerce_single_product_gallery_grid();
        } else {
            $content = '<div class="preloader skeleton product-gallery-skeleton"></div>';
        }
    }
    return $content;
}, 10, 2);

add_action('elementor/widget/render_content', function ($content, $widget) {
    if ('woocommerce-product-data-tabs' === $widget->get_name()) {
        if (empty($GLOBALS['thegem_product_page_elementor_template'])) {
            $thegem_product_data = thegem_get_output_product_page_data(get_the_ID());

            $params = [
                'tabs' => $thegem_product_data['product_page_desc_review_layout'],
                'tabs_position' => $thegem_product_data['product_page_desc_review_layout_acc_position'],
            ];

            if ($params['tabs'] == 'one_by_one' || ($params['tabs'] == 'accordion' && $params['tabs_position'] == 'next_to_gallery') ) {
                $content = '<div class="preloader skeleton product-tabs-skeleton"></div>';
            }
        }
    }
    return $content;
}, 10, 2);

add_action( 'elementor/element/woocommerce-product-images/section_product_gallery_style/after_section_end', function( $element, $args ) {
	$element->remove_control('wc_style_warning');
	$element->remove_control('sale_flash');
	$element->remove_control('image_border_border');
	$element->remove_control('image_border_width');
	$element->remove_control('image_border_color');
	$element->remove_control('image_border_radius');
	$element->remove_control('spacing');
	$element->remove_control('heading_thumbs_style');
	$element->remove_control('thumbs_border_border');
	$element->remove_control('thumbs_border_width');
	$element->remove_control('thumbs_border_color');
	$element->remove_control('thumbs_border_radius');
	$element->remove_control('spacing_thumbs');
}, 10, 3 );

function thegem_elementor_templates_product_before_do_single() {
	if(function_exists('is_product') && is_product()) {
		$GLOBALS['thegem_product_page_elementor_template'] = 1;
	}
	if(\Elementor\TemplateLibrary\Source_Local::get_template_type(get_the_ID()) === 'product') {
        wp_enqueue_style('thegem-product-page');
        wp_enqueue_script('thegem-product-page');
		echo '<div class="product-page__wrapper"><div class="single-product-content">';
	}
}
add_action('elementor/theme/before_do_single', 'thegem_elementor_templates_product_before_do_single');

function thegem_elementor_templates_product_after_do_single() {
	if(\Elementor\TemplateLibrary\Source_Local::get_template_type(get_the_ID()) === 'product') {
		echo '</div></div>';
	}
}
add_action('elementor/theme/after_do_single', 'thegem_elementor_templates_product_after_do_single');

function thegem_elementor_templates_before_single_product() {
	if(!empty($GLOBALS['thegem_product_page_elementor_template'])) {
		$thegem_page_id = get_the_ID();
		$GLOBALS['thegem_product_data'] = thegem_get_output_product_page_data($thegem_page_id);
		echo '<div class="product-page__wrapper"><div class="single-product-content">';
	}
}
add_action('woocommerce_before_single_product', 'thegem_elementor_templates_before_single_product', 100);

function thegem_elementor_templates_after_single_product() {
	if(!empty($GLOBALS['thegem_product_page_elementor_template'])) {
		echo '</div></div>';
	}
}
add_action('woocommerce_before_after_product', 'thegem_elementor_templates_after_single_product', 1);

if (!function_exists('thegem_woocommerce_grid_content')) {
	function thegem_woocommerce_grid_content($show_widgets, $thegem_sidebar_sticky) {
		global $post;
		$portfolio_posttemp = $post;

		if (thegem_get_option('product_archive_category_description_position') === 'above' ) {
			do_action('woocommerce_archive_description');
		}
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_content', 4);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_start', 11);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 15);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_product_per_page_select', 30);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 40);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_end', 45);
		do_action('woocommerce_before_shop_loop');

		$is_list = thegem_get_option('product_archive_type') == 'list';

		if ($is_list) {
			$hover_effect = 'list-'.thegem_get_option('product_archive_image_hover_effect_page');
			$caption_position = 'page';
			$preset = 'below-default-cart-button';
		} else if (thegem_get_option('product_archive_preset_type') == 'on_image') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_image');
			$caption_position = 'image';
			$preset = 'image-' . thegem_get_option('product_archive_preset_on_image');
		} else if (thegem_get_option('product_archive_preset_type') == 'below') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_page');
			$caption_position = 'page';
			$preset = 'below-' . thegem_get_option('product_archive_preset_below');
		} else {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_hover');
			$caption_position = 'hover';
			$preset = 'hover-' . thegem_get_option('product_archive_preset_on_hover');
		}

		$queried = get_queried_object();

		$params = array(
			'portfolio_uid' => '',
			'layout_type' => thegem_get_option('product_archive_type'),
			'layout' => $is_list ? 'justified' : thegem_get_option('product_archive_layout'),
			'columns_desktop' => $is_list ? thegem_get_option('product_archive_columns_desktop_list') : thegem_get_option('product_archive_columns_desktop'),
			'columns_tablet' => $is_list ? thegem_get_option('product_archive_columns_tablet_list') : thegem_get_option('product_archive_columns_tablet'),
			'columns_mobile' => $is_list ? '1x' : thegem_get_option('product_archive_columns_mobile'),
			'columns_100' => thegem_get_option('product_archive_columns_100'),
			'image_gaps' => thegem_get_option('product_archive_size_desktop'),
			'image_gaps_tablet' => thegem_get_option('product_archive_size_tablet'),
			'image_gaps_mobile' => thegem_get_option('product_archive_size_mobile'),
			'caption_position' => $caption_position,
			'image_size' => ($is_list || thegem_get_option('product_archive_layout') == 'justified') ? thegem_get_option('product_archive_image_size') : '',
			'image_ratio_full' => thegem_get_option('product_archive_image_ratio_full'),
			'image_ratio_custom' => thegem_get_option('product_archive_image_ratio_custom'),
			'image_aspect_ratio' => thegem_get_option('product_archive_image_aspect_ratio'),
			'quick_view' => thegem_get_option('product_archive_quick_view'),
			'quick_view_text' => thegem_get_option('product_archive_quick_view_text'),
			'orderby' => thegem_get_option('product_archive_orderby'),
			'order' => thegem_get_option('product_archive_order'),
			'product_show_sorting' => thegem_get_option('product_archive_show_sorting'),
			'product_show_categories' => thegem_get_option('product_archive_show_categories_desktop'),
			'product_show_categories_tablet' => thegem_get_option('product_archive_show_categories_tablet'),
			'product_show_categories_mobile' => thegem_get_option('product_archive_show_categories_mobile'),
			'product_show_title' => thegem_get_option('product_archive_show_title'),
			'product_show_description' => $is_list ? thegem_get_option('product_archive_show_description') : '',
			'truncate_title' => thegem_get_option('product_archive_truncate_title'),
			'truncate_description' => thegem_get_option('product_archive_truncate_description'),
			'product_show_price' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_price'),
			'product_show_reviews' => thegem_get_option('product_archive_show_reviews_desktop'),
			'product_show_reviews_tablet' => thegem_get_option('product_archive_show_reviews_tablet'),
			'product_show_reviews_mobile' => thegem_get_option('product_archive_show_reviews_mobile'),
			'product_show_reviews_count' => thegem_get_option('product_archive_show_reviews_count'),
			'attribute_swatches' => thegem_get_option('product_archive_show_swatches_desktop'),
    		'attribute_swatches_tablet' => thegem_get_option('product_archive_show_swatches_tablet'),
    		'attribute_swatches_mobile' => thegem_get_option('product_archive_show_swatches_mobile'),
    		'attribute_swatches_simple' => thegem_get_option('product_archive_show_swatches_simple'),
			'product_show_add_to_cart' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_add_to_cart'),
			'product_show_add_to_cart_mobiles' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_add_to_cart'),
			'add_to_cart_type' => $is_list ? 'button' : thegem_get_option('product_archive_add_to_cart_type'),
			'cart_button_show_icon' => thegem_get_option('product_archive_cart_button_show_icon'),
			'cart_button_text' => thegem_get_option('product_archive_cart_button_text'),
			'cart_button_pack' => thegem_get_option('product_archive_cart_icon_pack'),
			'cart_icon_' . thegem_get_option('product_archive_cart_icon_pack') => thegem_get_option('product_archive_cart_icon'),
			'select_options_button_text' => thegem_get_option('product_archive_select_options_button_text'),
			'select_options_pack' => thegem_get_option('product_archive_select_options_icon_pack'),
			'select_options_icon_' . thegem_get_option('product_archive_select_options_icon_pack') => thegem_get_option('product_archive_select_options_icon'),
			'product_show_wishlist' => thegem_get_option('product_archive_show_wishlist'),
			'add_wishlist_icon_pack' => thegem_get_option('product_archive_add_wishlist_icon_pack'),
			'wishlist_icon_' . thegem_get_option('product_archive_add_wishlist_icon_pack') => thegem_get_option('product_archive_add_wishlist_icon'),
			'added_wishlist_icon_pack' => thegem_get_option('product_archive_added_wishlist_icon_pack'),
			'added_wishlist_icon_' . thegem_get_option('product_archive_added_wishlist_icon_pack') => thegem_get_option('product_archive_added_wishlist_icon'),
			'items_per_page' => thegem_get_option('product_archive_items_per_page_desktop'),
			'items_per_page_tablet' => thegem_get_option('product_archive_items_per_page_tablet'),
			'items_per_page_mobile' => thegem_get_option('product_archive_items_per_page_mobile'),
			'show_pagination' => thegem_get_option('product_archive_show_pagination'),
			'pagination_type' => thegem_get_option('product_archive_pagination_type'),
			'reduce_html_size' => thegem_get_option('product_archive_pagination_reduce_html'),
			'items_on_load' => thegem_get_option('product_archive_pagination_reduce_html_items_count'),
			'pagination_more_button_separator_style' => 'gem-button-separator-type-single',
			'more_button_text' => thegem_get_option('product_archive_more_button_text'),
			'more_icon_pack' => thegem_get_option('product_archive_more_icon_pack'),
			'more_icon_' . thegem_get_option('product_archive_more_icon_pack') => thegem_get_option('product_archive_more_icon'),
			'more_stretch_full_width' => thegem_get_option('product_archive_more_stretch_full_width'),
			'more_show_separator' => thegem_get_option('product_archive_more_show_separator'),
			'not_found_text' => thegem_get_option('product_archive_not_found_text'),
			'loading_animation' => thegem_get_option('product_archive_loading_animation'),
			'animation_effect' => thegem_get_option('product_archive_animation_effect'),
			'ignore_highlights' => $is_list ? '1' : thegem_get_option('product_archive_ignore_highlights'),
			'featured_only' => thegem_get_option('product_archive_featured_only'),
			'sale_only' => thegem_get_option('product_archive_sale_only'),
			'new_only' => thegem_get_option('product_archive_new_only'),
			'stock_only' => thegem_get_option('product_archive_stock_only'),
			'image_hover_effect_image' => thegem_get_option('product_archive_image_hover_effect_image'),
			'image_hover_effect_page' => thegem_get_option('product_archive_image_hover_effect_page'),
			'image_hover_effect_hover' => thegem_get_option('product_archive_image_hover_effect_hover'),
			'image_hover_effect_fallback' => thegem_get_option('product_archive_image_hover_effect_fallback'),
			'caption_container_preset' => thegem_get_option('product_archive_caption_container_preset'),
			'product_separator' => thegem_get_option('product_archive_caption_container_separator'),
			'caption_container_preset_hover' => thegem_get_option('product_archive_caption_container_preset_hover'),
			'caption_container_alignment_hover' => thegem_get_option('product_archive_caption_container_alignment_hover'),
			'buttons_icon_alignment' => thegem_get_option('product_archive_button_icon_alignment'),
			'button_cart_color_normal' => thegem_get_option('product_archive_button_add_to_cart_text_color'),
			'button_cart_color_hover' => thegem_get_option('product_archive_button_add_to_cart_text_color_hover'),
			'button_cart_background_color_normal' => thegem_get_option('product_archive_button_add_to_cart_background_color'),
			'button_cart_background_color_hover' => thegem_get_option('product_archive_button_add_to_cart_background_color_hover'),
			'button_cart_border_color_normal' => thegem_get_option('product_archive_button_add_to_cart_border_color'),
			'button_cart_border_color_hover' => thegem_get_option('product_archive_button_add_to_cart_border_color_hover'),
			'button_options_color_normal' => thegem_get_option('product_archive_button_select_options_text_color'),
			'button_options_color_hover' => thegem_get_option('product_archive_button_select_options_text_color_hover'),
			'button_options_background_color_normal' => thegem_get_option('product_archive_button_select_options_background_color'),
			'button_options_background_color_hover' => thegem_get_option('product_archive_button_select_options_background_color_hover'),
			'button_options_border_color_normal' => thegem_get_option('product_archive_button_select_options_border_color'),
			'button_options_border_color_hover' => thegem_get_option('product_archive_button_select_options_border_color_hover'),
			'product_show_new' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_new') : '',
			'product_show_sale' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_sale') : '',
			'product_show_out' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_out_stock') : '',
			'labels_design' => thegem_get_option('product_labels_style'),
			'new_label_text' => thegem_get_option('product_label_new_text'),
			'sale_label_type' => thegem_get_option('product_label_sale_type'),
			'sale_label_prefix' => thegem_get_option('product_label_sale_prefix'),
			'sale_label_suffix' => thegem_get_option('product_label_sale_suffix'),
			'sale_label_text' => thegem_get_option('product_label_sale_text'),
			'out_label_text' => thegem_get_option('product_label_out_of_stock_text'),
			'filters_scroll_top' => thegem_get_option('product_archive_scroll_to_top'),
			'filter_buttons_standard_alignment' => 'left',
			'filter_buttons_hidden_show_icon' => '1',
			'filter_buttons_hidden_show_text' => thegem_get_option('product_archive_filter_buttons_hidden_show_text'),
			'filters_text_labels_clear_text' => esc_html__('Clear Filters', 'thegem'),
			'filters_style' => thegem_get_option('product_archive_filters_style'),
			'filter_by_search' => thegem_get_option('product_archive_filters_type') == 'normal' ? thegem_get_option('product_archive_filter_by_search') : '',
			'social_sharing' => thegem_get_option('product_archive_social_sharing'),
			'cart_hook' => thegem_get_option('product_archive_cart_hook'),
			'skeleton_loader' => thegem_get_option('product_archive_skeleton_loader'),
			'ajax_preloader_type' => thegem_get_option('product_archive_ajax_preloader_type'),
			'fullwidth_section_images' => thegem_get_option( 'product_archive_used_in_fullwidth_section'),
			'caption_position_list' => thegem_get_option( 'product_archive_caption_position'),
			'caption_container_alignment' => !empty(thegem_get_option( 'product_archive_caption_container_alignment_desktop')) ? thegem_get_option( 'product_archive_caption_container_alignment_desktop') : 'default',
			'caption_layout_list' => thegem_get_option( 'product_archive_caption_layout'),
			'filter_by_status_sale_text' => thegem_get_option('product_archive_filter_by_status_sale_text'),
			'filter_by_status_stock_text' => thegem_get_option('product_archive_filter_by_status_stock_text'),
			'product_show_divider' => thegem_get_option('product_archive_show_divider')
		);

		$normal_filter = false;
		$native_ajax = false;
		if (thegem_get_option('product_archive_filters_type') == 'normal') {
			$normal_filter = true;
			$attrs_arr = [];
			if (thegem_get_option('product_archive_filter_by_attribute') == '1') {
				$attrs = json_decode(thegem_get_option('product_archive_filter_by_attribute_data'));
				foreach ($attrs as $attr) {
					$attrs_arr[] = [
						'attribute_title' => $attr->title,
						'attribute_name' => $attr->attribute,
						'attribute_query_type' => isset($attr->query_type) ? $attr->query_type : 'and',
						'show_title' => isset($attr->attribute_show_title) ? $attr->attribute_show_title : '1',
						'attribute_display_type' => isset($attr->attribute_display_type) ? $attr->attribute_display_type : 'list',
						'attribute_display_dropdown_open' => isset($attr->attribute_display_dropdown_open) ? $attr->attribute_display_dropdown_open : 'hover',
					];
				}
			}
			$params = array_merge($params, array(
				'product_show_filter' => '1',
				'filter_by_categories' => thegem_get_option('product_archive_filter_by_categories'),
				'filter_by_categories_hierarchy' => thegem_get_option('product_archive_filter_by_categories_hierarchy'),
				'filter_by_categories_collapsible' => thegem_get_option('product_archive_filter_by_categories_collapsible'),
				'filter_by_categories_count' => thegem_get_option('product_archive_filter_by_categories_count'),
				'filter_by_categories_title' => thegem_get_option('product_archive_filter_by_categories_title'),
				'filter_by_categories_show_title' => thegem_get_option('product_archive_filter_by_categories_show_title'),
				'filter_by_categories_order_by' => thegem_get_option('product_archive_filter_by_categories_order_by'),
				'filter_by_categories_display_type' => thegem_get_option('product_archive_filter_by_categories_display_type'),
				'filter_by_categories_display_dropdown_open' => thegem_get_option('product_archive_filter_by_categories_display_dropdown_open'),
				'filter_by_categories_order' => thegem_get_option('product_archive_filter_by_categories_order'),
				'filter_by_price' => thegem_get_option('product_archive_filter_by_price'),
				'filter_by_price_title' => thegem_get_option('product_archive_filter_by_price_title'),
				'filter_by_price_show_title' => thegem_get_option('product_archive_filter_by_price_show_title'),
				'filter_by_price_display_type' => thegem_get_option('product_archive_filter_by_price_display_type'),
				'filter_by_price_display_dropdown_open' => thegem_get_option('product_archive_filter_by_price_display_dropdown_open'),
				'filter_by_price_order' => thegem_get_option('product_archive_filter_by_price_order'),
				'filter_by_attribute' => thegem_get_option('product_archive_filter_by_attribute'),
				'repeater_attributes' => $attrs_arr,
				'filter_by_attribute_count' => thegem_get_option('product_archive_filter_by_attribute_count'),
				'filter_by_attribute_hide_null' => thegem_get_option('product_archive_filter_by_attribute_hide_empty'),
				'filter_by_attribute_order' => thegem_get_option('product_archive_filter_by_attribute_order'),
				'filter_by_status' => thegem_get_option('product_archive_filter_by_status'),
				'filter_by_status_title' => thegem_get_option('product_archive_filter_by_status_title'),
				'filter_by_status_show_title' => thegem_get_option('product_archive_filter_by_status_show_title'),
				'filter_by_status_sale' => thegem_get_option('product_archive_filter_by_status_sale'),
				'filter_by_status_stock' => thegem_get_option('product_archive_filter_by_status_stock'),
				'filter_by_status_count' => thegem_get_option('product_archive_filter_by_status_count'),
				'filter_by_status_display_type' => thegem_get_option('product_archive_filter_by_status_display_type'),
				'filter_by_status_display_dropdown_open' => thegem_get_option('product_archive_filter_by_status_display_dropdown_open'),
				'filter_by_status_order' => thegem_get_option('product_archive_filter_by_status_order'),
				'filters_text_labels_all_text' => thegem_get_option('product_archive_filters_text_labels_all_text'),
				'filters_text_labels_clear_text' => thegem_get_option('product_archive_filters_text_labels_clear_text'),
				'filters_text_labels_search_text' => thegem_get_option('product_archive_filters_text_labels_search_text'),
				'filter_buttons_hidden_sidebar_title' => thegem_get_option('product_archive_filter_buttons_hidden_sidebar_title'),
				'filter_buttons_hidden_filter_by_text' => thegem_get_option('product_archive_filter_buttons_hidden_filter_by_text'),
			));
		} else if (thegem_get_option('product_archive_filters_type') == 'native') {
			if (thegem_get_option('product_archive_filters_ajax') == '1') {
				$native_ajax = true;
			}
			$params = array_merge($params, array(
				'product_show_filter' => '1',
				'filters_style' => thegem_get_option('product_archive_filters_style_native'),
				'filter_by_categories' => '',
				'filter_by_attribute' => '',
				'filter_by_price' => '',
				'filter_by_status' => '',
				'filter_by_search' => '',
			));
		} else {
			$params = array_merge($params, array(
				'product_show_filter' => $show_widgets ? '1' : '',
				'filters_style' => 'sidebar',
				'filter_by_price' => '',
			));
		}

		if (is_search() && !isset($_GET['ajax_search']) && thegem_get_option('search_layout_type') == 'grid' && count(thegem_get_search_post_types_array(true)) > 0 && $params['filters_style'] == 'sidebar') {
			$params['filters_style'] = 'hidden';
		}

		if (isset($queried->taxonomy) && !isset($_GET[$queried->taxonomy])) {
			if ($queried->taxonomy == 'product_tag') {
				$params['source'] = array('tag');
				$params['select_products_tags'] = '1';
				$params['content_products_tags'] = array($queried->slug);
			} else {
				$params['select_products_tax'] = $queried->taxonomy;
				$params['content_products_tax'] = array($queried->slug);
			}
		}

		wp_enqueue_style('thegem-portfolio-products-extended');
		wp_enqueue_script('thegem-woocommerce');
		wp_enqueue_script('thegem-portfolio-grid-extended');
		if (!wp_script_is('thegem-portfolio-grid-extended-inline')) {
			wp_enqueue_script('thegem-portfolio-grid-extended-inline');
			wp_add_inline_script( 'thegem-portfolio-grid-extended-inline', "jQuery('.extended-products-grid .yith-icon').each(function () {
					var addIcon = jQuery(this).children('.add-wishlist-icon').clone();
					var addedIcon = jQuery(this).children('.added-wishlist-icon').clone();
					jQuery(this).find('a i').remove();
					jQuery(this).find('a svg').remove();
					jQuery(this).find('.yith-wcwl-add-button a:not(.delete_item)').prepend(addIcon);
					jQuery(this).find('.yith-wcwl-add-button a.delete_item').prepend(addedIcon);
					jQuery(this).find('.yith-wcwl-wishlistexistsbrowse a').prepend(addedIcon);
					jQuery(this).find('a').addClass('icon');
					jQuery(this).find('a.gem-button').removeAttr('class').removeAttr('style').removeAttr('onmouseleave').removeAttr('onmouseenter').addClass('icon');
					jQuery(this).find('.yith-wcwl-wishlistaddedbrowse a').prepend(addedIcon);
				});" );
		}

		if (isset($params['attribute_swatches']) && ($params['attribute_swatches'] == '1' || $params['attribute_swatches_tablet'] == '1' || $params['attribute_swatches_mobile'] == '1')) {
			wp_enqueue_script('wc-add-to-cart-variation');
			$params['repeater_swatches'] = [];
			$attrs = json_decode(thegem_get_option('product_archive_show_swatches_data'));
			foreach ($attrs as $attr) {
				array_push($params['repeater_swatches'], [
					'attribute_name' => isset($attr->attribute) ? $attr->attribute: '',
					'attribute_count' => isset($attr->value_to_show) ? $attr->value_to_show : '-1',
					'attribute_show_name' => isset($attr->attribute_name) ? $attr->attribute_name : '',
				]);
			}
		}

		$grid_uid = $params['portfolio_uid'];
		$grid_uid_url = '';

		$localize = array(
			'data' => $params,
			'action' => 'extended_products_grid_load_more',
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('extended_products_grid_ajax-nonce')
		);
		wp_localize_script('thegem-portfolio-grid-extended', 'thegem_portfolio_ajax_to_products', $localize);

		wp_enqueue_style('thegem-hovers-' . $hover_effect);

		if ($params['pagination_type'] == 'more') {
			wp_enqueue_style('thegem-button');
		} else if ($params['pagination_type'] == 'scroll') {
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['quick_view'] == '1') {
			wp_enqueue_script('wc-single-product');
			wp_enqueue_script('wc-add-to-cart-variation');
			wp_enqueue_script('thegem-product-quick-view');
			if (!is_product() && thegem_is_quick_view_default()) {
				wp_enqueue_script('thegem-quick-view');
				wp_enqueue_style('thegem-quick-view');
			}
			if(thegem_get_option('product_page_layout') == 'default') {
				if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
				}
				if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
				}
				if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
				}
			}
			if (thegem_get_option('product_gallery') != 'legacy') {
				wp_enqueue_style('thegem-product-gallery');
			} else {
				wp_enqueue_style('thegem-hovers');
			}
		}

		if ($params['product_show_filter'] === '1') {
			wp_enqueue_script('jquery-dlmenu');

			if ($params['filter_by_price'] === '1') {
				wp_enqueue_script('wc-jquery-ui-touchpunch');
			}
		}

		if ($params['loading_animation'] === '1') {
			wp_enqueue_style('thegem-animations');
			wp_enqueue_script('thegem-items-animations');
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1') {

			if ($params['layout'] == 'metro') {
				wp_enqueue_script('thegem-isotope-metro');
			} else {
				wp_enqueue_script('thegem-isotope-masonry-custom');
			}
		}

		$categories = ['0'];

		if (isset($params['filter_by_categories']) && $params['filter_by_categories'] == '1') {
			$cat_args = array(
				'hide_empty' => true,
			);

			if (isset($params['filter_by_categories_order_by'])) {
				$cat_args['orderby'] = $params['filter_by_categories_order_by'];

				if ($params['filter_by_categories_order_by'] == 'term_order') {
					$cat_args['orderby'] = 'meta_value_num';
					$cat_args['meta_key'] = 'order';
				}
			}

			if (in_array('0', $categories)) {
				if ($params['filter_by_categories_hierarchy'] == '1') {
					$cat_args['parent'] = 0;
					$terms = get_terms('product_cat', $cat_args);
				} else {
					$terms = get_terms('product_cat', $cat_args);
				}
			} else {
				$cat_args['slug'] = $categories;
				$terms = get_terms('product_cat', $cat_args);
			}
		}

		$filters_tax_url = $taxonomy_filter_current = $filters_meta_url = $meta_filter_current = $filters_attributes_url = [];

		$categories_filter = null;
		if (isset($_GET['category'])) {
			$active_cat = $_GET['category'];
			$taxonomy_filter_current['product_cat'] = [$active_cat];
			$categories_filter = $active_cat;
		} else if (isset($queried->taxonomy) && $queried->taxonomy == 'product_cat') {
			$active_cat = $queried->slug;
			$taxonomy_filter_current['product_cat'] = [$active_cat];
			$categories_filter = $active_cat;
		} else {
			$active_cat = 'all';
			$taxonomy_filter_current['product_cat'] = $categories;
		}

		$attributes = [];
		if (isset($queried->taxonomy) && strpos($queried->taxonomy, 'pa_') !== false) {
			$active_attr = str_replace('pa_', '', $queried->taxonomy);
			$attributes[$active_attr] = [$queried->slug];
		}

		if (!empty($params['source']) && in_array('tag', $params['source'])) {
			$taxonomy_filter_current['product_tag'] = $params['content_products_tags'];
		}
		if (!empty($params['select_products_tax'])) {
			$taxonomy_filter_current[$params['select_products_tax']] = $params['content_products_tax'];
		}

		$taxonomies_list = get_object_taxonomies('product');
		foreach($_GET as $key => $value) {
			if (in_array($key, $taxonomies_list) || strpos($key, 'filter_tax_') === 0) {
				$attr = str_replace('filter_tax_', '', $key);
				if (in_array($attr, $taxonomies_list)) {
					$filters_tax_url['tax_' . $attr] = $taxonomy_filter_current[$attr] = explode(",", $value);
				}
			} else if (strpos($key, 'filter_meta_') === 0) {
				$attr = str_replace('filter_meta_', '', $key);
				$filters_meta_url['meta_' . $attr] = $meta_filter_current[$attr] = explode(",", $value);
			} else if (strpos($key, 'filter_') === 0) {
				$attr = str_replace('filter_', '', $key);
				if (in_array('pa_' . $attr, $taxonomies_list)) {
					$filters_attributes_url[$attr] = explode(",", $value);
				}
			}
		}
		$attributes_filter = array_merge($filters_tax_url, $filters_meta_url, $filters_attributes_url);
		if (empty($attributes_filter)) { $attributes_filter = null; }
		$attributes_current = empty($filters_attributes_url) ? $attributes : $filters_attributes_url;

		$attributes_query_type_url = [];
		$attributes_list = thegem_extended_products_get_woo_attributes();
		foreach ($attributes_list as $name => $attr) {
			if (isset($_GET['query_type_' . $attr])) {
				$attributes_query_type_url[$attr] = $_GET['query_type_' . $attr];
			} else if (!$normal_filter) {
				$attributes_query_type_url[$attr] = 'and';
			}
		}

		$page = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
		$next_page = 0;

		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		}

		if (isset($_GET['ajax_filters']) || $page != 1) {
			$params['reduce_html_size'] = 0;
		}
		$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;
		if ($params['reduce_html_size']) {
			$items_on_load = $params['items_on_load'] ? intval($params['items_on_load']) : 8;
			if ($items_on_load >= $items_per_page) {
				$params['reduce_html_size'] = 0;
				$items_on_load = $items_per_page;
			}
		} else {
			$items_on_load = $items_per_page;
		}

		if (isset($_GET['orderby'])) {
			$orderby = $_GET['orderby'];
			$order = 'desc';
			$sortby = $orderby;
			if ($sortby == 'price') {
				if (!empty($_GET['order'])) {
					$order = $_GET['order'];
				}
				$sortby .= '-' . $order;
			}
		} else {
			$orderby = $params['orderby'];
			$order = $params['order'];
			$sortby = 'default';
		}

		$featured_only = $params['featured_only'] == '1';
		$sale_only = $params['sale_only'] == '1';
		$stock_only = $params['stock_only'] == '1';

		$status_current = null;
		if (isset($_GET['status'])) {
			$status_current = explode(",", $_GET['status']);
			if (in_array('sale', $status_current)) {
				$sale_only = true;
			}
			if (in_array('stock', $status_current)) {
				$stock_only = true;
			}
		}

		$price_current = null;
		if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
			$current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
			$current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;
			$price_current = [$current_min_price, $current_max_price];
		}

		$search_current = null;
		if (isset($_GET['s'])) {
			$search_current = $_GET['s'];
		}

		$product_loop = thegem_extended_products_get_posts($page, $items_on_load, $orderby, $order, $featured_only, $sale_only, $stock_only, false, false, $taxonomy_filter_current, $meta_filter_current, $attributes_current, $price_current, $search_current, $attributes_query_type_url);

		if ($product_loop && $product_loop->have_posts() || $search_current != null || $price_current != null) :

			$max_page = ceil($product_loop->found_posts / $items_per_page);

			if ($params['reduce_html_size']) {
				$next_page = $product_loop->found_posts > $items_on_load ? 2 : 0;
				$next_page_pagination = $max_page > $page ? $page + 1 : 0;
			} else {
				$next_page = $next_page_pagination = $max_page > $page ? $page + 1 : 0;
			}

			$item_classes = get_thegem_portfolio_render_item_classes($params);
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

			$search_only = true;
			if ($params['product_show_filter'] == '1' && (!$normal_filter || (
						($params['filter_by_categories'] == '1' && count($terms) > 0) ||
						$params['filter_by_attribute'] == '1' ||
						$params['filter_by_price'] == '1' ||
						$params['filter_by_status'] == '1'
					))) {
				$search_only = false;
			}

			if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== '1' || $params['layout'] !== 'justified') && $params['skeleton_loader'] !== '1')) {
				$spin_class = 'preloader-spin';
				if ($params['ajax_preloader_type'] == 'minimal') {
					$spin_class = 'preloader-spin-new';
				}
				echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
			} else if ($params['skeleton_loader'] == '1') { ?>
				<div class="preloader save-space shop-skeleton" data-style-uid="to_products">
					<div class="skeleton">
						<?php if ((!$show_widgets && $params['filters_style'] == 'sidebar' && !$search_only) ||
						($show_widgets && $params['filters_style'] != 'hidden')) { ?>
						<div class="with-filter-sidebar">
							<div class="filter-sidebar">
								<div class="widget"></div>
								<div class="widget"></div>
								<div class="widget"></div>
							</div>
							<div class="content">
								<?php }
								if ($params['product_show_sorting'] == '1') { ?>
									<div class="portfolio-top-panel">
										<div class="skeleton-sorting"></div>
									</div>
								<?php } ?>
								<div class="skeleton-posts row portfolio-row">
									<?php for ($x = 0; $x < $product_loop->post_count; $x++) {
										echo thegem_extended_products_render_item($params, $item_classes);
									} ?>
								</div>
								<?php if ((!$show_widgets && $params['filters_style'] == 'sidebar' && !$search_only) ||
								($show_widgets && $params['filters_style'] != 'hidden')) { ?>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			<?php } ?>

			<div class="portfolio-preloader-wrapper">

				<?php
				if ($params['caption_position'] == 'hover') {
					$title_on = 'hover';
				} else {
					$title_on = 'page';
				}

				$portfolio_classes = array(
					'portfolio portfolio-grid extended-portfolio-grid extended-products-grid',
					'to-extended-products main-loop-grid',
					'woocommerce',
					'products',
					'no-padding',
					'portfolio-preset-' . $preset,
					'portfolio-pagination-' . $params['pagination_type'],
					'portfolio-style-' . $params['layout'],
					'background-style-' . $params['caption_container_preset'],
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-container-preset-' . $params['caption_container_preset_hover'] : '',
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-alignment-' . $params['caption_container_alignment_hover'] : '',
					'caption-position-' . $params['caption_position'],
					'hover-' . $hover_effect,
					'title-on-' . $title_on,
					($params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
					($params['layout_type'] == 'list' ? 'list-style disabled-hover caption-position-list-' . $params['caption_position_list'] : ''),
					($params['layout_type'] == 'list' ? 'caption-alignment-list-' . $params['caption_container_alignment'] : ''),
					($params['layout_type'] == 'list' ? 'caption-layout-list-' . $params['caption_layout_list'] : ''),
					($params['loading_animation'] == '1' ? 'loading-animation' : ''),
					($params['loading_animation'] == '1' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
					($params['image_gaps'] == 0 ? 'no-gaps' : ''),
					($params['columns_desktop'] == '100%' || thegem_get_option('product_archive_content_width') === 'fullwidth-nogaps' ? 'fullwidth-columns' : ''),
					($params['columns_desktop'] == '100%' ? 'fullwidth-columns-desktop-' . $params['columns_100'] : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
					($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
					($params['social_sharing'] != '1' ? 'portfolio-disable-socials' : ''),
					($params['layout'] == 'masonry' ? 'portfolio-items-masonry' : ''),
					($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
					('columns-tablet-' . $params['columns_tablet']),
					('columns-mobile-' . $params['columns_mobile']),
					($params['product_separator'] == '1' ? 'item-separator' : ''),
					($params['layout'] == 'justified' && $params['ignore_highlights'] == '1' ? 'disable-isotope' : ''),
					(($params['image_size'] == 'full' && empty($params['image_ratio_full']['size']) || !in_array($params['image_size'], ['full', 'default'])) ? 'full-image' : 'aspect-ratio-custom'),
					($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
					($params['reduce_html_size'] ? 'reduce-size' : ''),
					($params['product_show_divider'] == '1' ? 'with-divider' : ''),
				);
				?>

				<div class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>"
					 data-per-page="<?php echo esc_attr($items_per_page) ?>"
					 data-current-page="<?php echo esc_attr($page) ?>"
					 data-next-page="<?php echo esc_attr($next_page) ?>"
					 data-pages-count="<?php echo esc_attr($max_page) ?>"
					 data-style-uid="to_products"
					 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
					 data-hover="<?php echo esc_attr($hover_effect) ?>"
					 data-portfolio-filter='<?php echo esc_attr($categories_filter) ?>'
					 data-portfolio-filter-attributes='<?php echo esc_attr(json_encode($attributes_filter)) ?>'
					 data-portfolio-filter-status='<?php echo esc_attr(json_encode($status_current)) ?>'
					 data-portfolio-filter-price='<?php echo esc_attr(json_encode($price_current)) ?>'
					 data-portfolio-filter-search='<?php echo esc_attr($search_current) ?>'>
					<?php
					$has_right_panel = $params['product_show_sorting'] == '1' || ($params['product_show_filter'] == '1' && ($params['filter_by_search'] == '1' && ($params['filters_style'] == 'standard' || $search_only))); ?>
					<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%' || thegem_get_option('product_archive_content_width') === 'fullwidth-nogaps'): ?>fullwidth-block no-paddings<?php endif; ?>">
						<input id="shop-page-url" type="hidden" <?php if (get_home_url()."/" == wc_get_page_permalink('shop')) {?>class="is-shop-home"<?php } ?>
							   value="<?php echo (!$normal_filter && isset($queried->taxonomy) && $queried->taxonomy == 'product_cat') ? get_term_link($queried->slug, 'product_cat') : wc_get_page_permalink('shop'); ?>">
						<?php if ((!$show_widgets && $params['filters_style'] == 'sidebar' && !$search_only) ||
						($show_widgets && $params['filters_style'] != 'hidden')) { ?>
						<div class="with-filter-sidebar <?php echo $thegem_sidebar_sticky ? 'sticky-sidebar' : ''; ?>">
							<div class="filter-sidebar <?php echo $params['product_show_sorting'] == '1' ? 'left' : ''; ?>">
								<?php
								if ($normal_filter && $params['filters_style'] != 'standard') {
									include(locate_template(array('gem-templates/products-extended/filters.php')));
								} else {
									if ($show_widgets) { ?>
										<div class="portfolio-filters-list sidebar
										<?php echo $normal_filter ? 'normal hide-mobile hide-tablet' : 'native'; ?>
										style-sidebar <?php echo $params['filters_scroll_top'] == '1' ? 'scroll-top' : ''; ?>
										<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
										<?php echo thegem_get_option('product_archive_remove_attr_counts') == '1' ? 'hide-filter-counts' : ''; ?>
										<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>
										<?php echo !$normal_filter && !empty(thegem_get_option('categories_collapsible')) ? 'categories-widget-collapsible' : ''; ?>">
											<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == '1' ? 'with-icon' : ''; ?>">
												<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
												<?php if ($params['filter_buttons_hidden_show_icon'] == '1') { ?>
													<span class="portfolio-show-filters-button-icon"></span>
												<?php } ?>
											</div>

											<div class="portfolio-filters-outer">
												<div class="portfolio-filters-area">
													<div class="portfolio-filters-area-scrollable">
														<div class="widget-area-wrap">
															<?php get_sidebar('shop'); ?>
														</div>
													</div>
												</div>
												<div class="portfolio-close-filters"></div>
											</div>

										</div>
									<?php }
								} ?>
							</div>
							<div class="content">
								<?php }
								$selected_shown = false;
								if ($params['product_show_filter'] == '1' || $params['product_show_sorting'] == '1'): ?>

									<div class="portfolio-top-panel filter-type-extended <?php echo $params['filters_style'] == 'sidebar' ? 'sidebar-filter' : ''; ?> <?php echo ($params['product_show_sorting'] != '1' && (!$search_only && $params['filters_style'] == 'sidebar')) ? 'selected-only' : ''; ?>">
										<div class="portfolio-top-panel-row">

											<div class="portfolio-top-panel-left">
												<?php if ($params['product_show_filter'] == '1') {
													if ($normal_filter) {
														if ($params['filters_style'] != 'sidebar' && !$search_only) {
															include(locate_template(array('gem-templates/products-extended/filters.php')));
														}
														if ($params['filters_style'] == 'sidebar') {
															$selected_shown = true;
															include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
														}
													} else {
														if ($params['filters_style'] == 'hidden' && is_active_sidebar('shop-sidebar')) { ?>
															<div class="portfolio-filters-list sidebar native style-<?php echo esc_attr($params['filters_style']); ?>
															<?php echo $params['filters_scroll_top'] == '1' ? 'scroll-top' : ''; ?>
															<?php echo $has_right_panel ? 'has-right-panel' : ''; ?>
															<?php echo thegem_get_option('product_archive_remove_attr_counts') == '1' ? 'hide-filter-counts' : ''; ?>
															<?php echo $native_ajax ? 'native-ajax-filters' : ''; ?>
															<?php echo !empty(thegem_get_option('categories_collapsible')) ? 'categories-widget-collapsible' : ''; ?>">

																<div class="portfolio-show-filters-button <?php echo $params['filter_buttons_hidden_show_icon'] == '1' ? 'with-icon' : ''; ?>">
																	<?php echo esc_html($params['filter_buttons_hidden_show_text']); ?>
																	<?php if ($params['filter_buttons_hidden_show_icon'] == '1') { ?>
																		<span class="portfolio-show-filters-button-icon"></span>
																	<?php } ?>
																</div>

																<div class="portfolio-filters-outer">
																	<div class="portfolio-filters-area">
																		<div class="portfolio-filters-area-scrollable">
																			<div class="widget-area-wrap">
																				<?php get_sidebar('shop'); ?>
																			</div>
																		</div>
																	</div>
																	<div class="portfolio-close-filters"></div>
																</div>

															</div>
														<?php }
														if ($params['filters_style'] == 'sidebar' && $native_ajax) {
															$selected_shown = true;
															include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
														}
													}
												} ?>
											</div>

											<?php if ($has_right_panel): ?>
												<div class="portfolio-top-panel-right">
													<?php if ($params['product_show_sorting'] == '1'): ?>
														<div class="portfolio-sorting-select">
															<div class="portfolio-sorting-select-current">
																<div class="portfolio-sorting-select-name">
																	<?php
																	switch ($sortby) {
																		case "date":
																			echo esc_html__('Sort by latest', 'woocommerce');
																			break;
																		case "popularity":
																			echo esc_html__('Sort by popularity', 'woocommerce');
																			break;
																		case "rating":
																			echo esc_html__('Sort by average rating', 'woocommerce');
																			break;
																		case "price-asc":
																			echo esc_html__('Sort by price: low to high', 'woocommerce');
																			break;
																		case "price-desc":
																			echo esc_html__('Sort by price: high to low', 'woocommerce');
																			break;
																		default:
																			echo esc_html__('Default sorting', 'woocommerce');
																	} ?>
																</div>
																<span class="portfolio-sorting-select-current-arrow"></span>
															</div>
															<ul>
																<li class="default <?php echo $sortby == 'default' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="<?php echo esc_attr($params['orderby']) ?>"
																	data-order="<?php echo esc_attr($params['order']) ?>">
																	<?php echo esc_html__('Default sorting', 'woocommerce'); ?>
																</li>
																<li class="<?php echo $sortby == 'date' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="date"
																	data-order="desc">
																	<?php echo esc_html__('Sort by latest', 'woocommerce'); ?>
																</li>
																<li class="<?php echo $sortby == 'popularity' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="popularity"
																	data-order="desc">
																	<?php echo esc_html__('Sort by popularity', 'woocommerce'); ?>
																</li>
																<li class="<?php echo $sortby == 'rating' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="rating"
																	data-order="desc">
																	<?php echo esc_html__('Sort by average rating', 'woocommerce'); ?>
																</li>
																<li class="<?php echo $sortby == 'price-asc' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price"
																	data-order="asc">
																	<?php echo esc_html__('Sort by price: low to high', 'woocommerce'); ?>
																</li>
																<li class="<?php echo $sortby == 'price-desc' ? 'portfolio-sorting-select-current' : ''; ?>"
																	data-orderby="price-desc"
																	data-order="desc">
																	<?php echo esc_html__('Sort by price: high to low', 'woocommerce'); ?>
																</li>
															</ul>
														</div>
													<?php endif; ?>

													<?php if ($params['filter_by_search'] == '1' && ($params['filters_style'] == 'standard' || $search_only)) { ?>
														<span>&nbsp;</span>
														<form class="portfolio-search-filter <?php echo $search_only ? 'mobile-visible' : ''; ?>"
															  role="search" action="">
															<div class="portfolio-search-filter-form">
																<input type="search"
																	   placeholder="<?php echo esc_attr($params['filters_text_labels_search_text']); ?>"
																	   value="<?php echo esc_attr($search_current); ?>"
																	   aria-label="<?php esc_attr_e('Search', 'thegem'); ?>">
															</div>
															<div class="portfolio-search-filter-button" role="button" aria-label="<?php esc_attr_e('Search', 'thegem'); ?>"></div>
														</form>
													<?php } ?>
												</div>
											<?php endif; ?>
										</div>
										<?php if ($params['product_show_filter'] == '1' && ($normal_filter || $native_ajax)) {
											$selected_shown = true;
											include(locate_template(array('gem-templates/products-extended/selected-filters.php')));
										} ?>
									</div>
								<?php endif; ?>
								<?php if (!$selected_shown) { ?>
									<div class="portfolio-top-panel selected-only">
										<?php include(locate_template(array('gem-templates/products-extended/selected-filters.php'))); ?>
									</div>
								<?php } ?>
								<div class="row portfolio-row clearfix">
									<?php $display_type = woocommerce_get_loop_display_mode();

									if ('subcategories' === $display_type || 'both' === $display_type) { ?>
										<div class="portfolio-set sub-categories"
											 data-max-row-height="">

											<?php $parent_id = is_product_category() ? get_queried_object_id() : 0;
											$product_categories = woocommerce_get_product_subcategories($parent_id);

											if ($product_categories) {
												foreach ($product_categories as $category) {
													echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, $category, true);
												}
											} ?>
										</div><!-- .portflio-set -->
									<?php }

									if ('subcategories' !== $display_type) { ?>
										<div class="portfolio-set"
											 data-max-row-height="">

											<?php if ($product_loop->have_posts()) {
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
												remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);
												while ($product_loop->have_posts()) : $product_loop->the_post(); ?>
													<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
												<?php
												endwhile;
												wp_reset_postdata();
											} else { ?>
												<div class="portfolio-item not-found">
													<div class="found-wrap">
														<div class="image-inner empty"></div>
														<div class="msg">
															<?php echo wp_kses($params['not_found_text'], 'post'); ?>
														</div>
													</div>
												</div>
											<?php } ?>
										</div><!-- .portflio-set -->
									<?php } ?>

									<div class="portfolio-item-size-container">
										<?php echo thegem_extended_products_render_item($params, $item_classes); ?>
									</div>
								</div><!-- .row-->
								<?php

								/** Pagination */

								if ('subcategories' !== $display_type && '1' === ($params['show_pagination'])) : ?>
									<?php if ($params['pagination_type'] == 'normal'): ?>
										<div class="portfolio-navigator gem-pagination"<?php if ($max_page < 2) { echo ' style="display:none;"'; } ?>>
											<a href="#" class="prev" aria-label="<?php esc_html_e('Prev', 'thegem'); ?>">
												<i class="default"></i>
											</a>
											<div class="pages"></div>
											<a href="#" class="next" aria-label="<?php esc_html_e('Next', 'thegem'); ?>">
												<i class="default"></i>
											</a>
										</div>
									<?php endif; ?>
									<?php
									if ($params['pagination_type'] == 'more' && $next_page_pagination > 0):

										$separator_enabled = ($params['more_show_separator'] === '1' && $params['more_stretch_full_width'] !== '1') ? true : false;

										// Container
										$classes_container = 'gem-button-container gem-widget-button ';

										if ($separator_enabled) {
											$classes_container .= 'gem-button-position-center gem-button-with-separator ';
										} else {
											if ('1' === $params['more_stretch_full_width']) {
												$classes_container .= 'gem-button-position-fullwidth ';
											}
										}

										// Separator
										$classes_separator = 'gem-button-separator ';

										if (!empty($params['pagination_more_button_separator_style'])) {
											$classes_separator .= $params['pagination_more_button_separator_style'];
										}

										// Link
										$classes_button = "load-more-button gem-button gem-button-text-weight-normal gem-button-size-small gem-button-style-flat";
										?>

										<div class="portfolio-load-more gem-pagination">
											<div class="inner">
												<?php include(locate_template(array('gem-templates/products-extended/more-button.php'))); ?>
											</div>
										</div>
									<?php endif; ?>
									<?php if ($params['pagination_type'] == 'scroll' && $next_page_pagination > 0): ?>
										<div class="portfolio-scroll-pagination gem-pagination"></div>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ((!$show_widgets && $params['filters_style'] == 'sidebar' && !$search_only) ||
								($show_widgets && $params['filters_style'] != 'hidden')) { ?>
							</div>
						</div>
					<?php } ?>
					</div><!-- .full-width -->
				</div><!-- .portfolio-->
			</div><!-- .portfolio-preloader-wrapper-->
			<?php
			remove_action('woocommerce_after_shop_loop', 'thegem_woocommerce_after_shop_content', 15);
			remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);;
			do_action('woocommerce_after_shop_loop');
			if (thegem_get_option('product_archive_category_description_position') !== 'above' ) {
				do_action('woocommerce_archive_description');
			} ?>
		<?php
		else :
			do_action('woocommerce_no_products_found');
		endif;
		$post = $portfolio_posttemp;
	}
}

if (!function_exists('thegem_extended_products_more_callback')) {
	function thegem_extended_products_more_callback() {
		$params = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
		ob_start();
		$response = array('status' => 'success');
		$page = isset($params['more_page']) ? intval($params['more_page']) : 1;
		if ($page == 0)
			$page = 1;
		$featured_only = $params['featured_only'] == 'yes' || $params['featured_only'] == '1';
		$sale_only = $params['sale_only'] == 'yes' || $params['sale_only'] == '1';
		$recently_viewed_only = isset($params['recently_viewed_only']) && ($params['recently_viewed_only'] == 'yes' || $params['recently_viewed_only'] == '1');
		$new_only = isset($params['new_only']) && ($params['new_only'] == 'yes' || $params['new_only'] == '1');

		$taxonomy_filter = $meta_filter = $attributes = [];
		if ((isset($params['source']) && in_array('category', $params['source']) && !empty($params['content_products_cat'])) || !empty($params['has_categories_filter'])) {
			$taxonomy_filter['product_cat'] = explode(",", $params['content_products_cat']);
		} else {
			$taxonomy_filter['product_cat'] = ['0'];
		}
		$post__in = null;
		if ((!empty($params['source']) && in_array('tag', $params['source'])) || !empty($params['has_tags_filter']) || !empty($params['select_products_tags'])) {
			$taxonomy_filter['product_tag'] = is_array($params['content_products_tags']) ? $params['content_products_tags'] : explode(",", $params['content_products_tags']);
		}
		if (!empty($params['select_products_tax'])) {
			$taxonomy_filter[$params['select_products_tax']] = $params['content_products_tax'];
		}
		if (!empty($params['source']) && in_array('products', $params['source'])) {
			$post__in = $params['content_products'];
		}
		if ((isset($params['source']) && in_array('attribute', $params['source']) && !empty($params['content_products_attr'])) || !empty($params['has_attributes_filter'])) {
			$attrs = is_array($params['content_products_attr']) ? $params['content_products_attr'] : explode(",", $params['content_products_attr']);
			foreach ($attrs as $attr) {
				$values = is_array($params['content_products_attr_val_' . $attr]) ? $params['content_products_attr_val_' . $attr] : json_decode($params['content_products_attr_val_' . $attr]);
				if (strpos($attr, "tax_") === 0) {
					$taxonomy_filter[str_replace("tax_","", $attr)] = $values;
				} else if (strpos($attr, "meta_") === 0) {
					$meta_filter[str_replace("meta_", "", $attr)] = $values;
				} else {
					if (empty($values) || in_array('0', $values)) {
						$values = get_terms('pa_' . $attr, array('fields' => 'slugs'));
					}
					$attributes[$attr] = $values;
				}
			}
		}

		$orderby = $params['orderby'];
		$order = $params['order'];

		$stock_only = isset($params['stock_only']) && ($params['stock_only'] == 'yes' || $params['stock_only'] == '1');
		if (isset($params['content_products_status_filter'])) {
			if (in_array('sale', $params['content_products_status_filter'])) {
				$sale_only = true;
			}
			if (in_array('stock', $params['content_products_status_filter'])) {
				$stock_only = true;
			}
			if (in_array('featured', $params['content_products_status_filter'])) {
				$featured_only = true;
			}
			if (in_array('recent', $params['content_products_status_filter'])) {
				$orderby = 'date';
				$order = 'desc';
			}
			if (in_array('sales', $params['content_products_status_filter'])) {
				$orderby = 'popularity';
				$order = 'desc';
			}
		}

		$price = isset($params['content_products_price_filter']) ? $params['content_products_price_filter'] : null;
		$search = isset($params['portfolio_search_filter']) && $params['portfolio_search_filter'] != '' ? $params['portfolio_search_filter'] : null;
		$offset = isset($params['offset']) ? $params['offset'] : null;
		$exclude_products = isset($params['exclude_products']) ? $params['exclude_products'] : null;
		$items_per_page = $params['items_per_page'] ? intval($params['items_per_page']) : 8;

		if (isset($params['content_products_status_filter']) && in_array('sales', $params['content_products_status_filter'])) {
				if(isset($params['content_products_filter_number']) && intval($params['content_products_filter_number']) > 0) {
					$product_filter_loop = thegem_extended_products_get_posts(1, intval($params['content_products_filter_number']), $orderby, $order, $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter, $meta_filter, $attributes, $price, $search, null, $post__in, $offset, $exclude_products);
					$filter_post_ids = wp_list_pluck( $product_filter_loop->posts, 'ID' );
					$post__in = $filter_post_ids;
				}
		}

		$products_grid_loop = thegem_extended_products_get_posts($page, $items_per_page, $orderby, $order, $featured_only, $sale_only, $stock_only, $recently_viewed_only, $new_only, $taxonomy_filter, $meta_filter, $attributes, $price, $search, null, $post__in, $offset, $exclude_products);
		if ((isset($params['filter_by_categories']) && ($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1')) ||
		    (isset($params['filter_by_attribute']) && ($params['filter_by_attribute'] == 'yes' || $params['filter_by_attribute'] == '1')) ||
		    (isset($params['filter_by_status']) && ($params['filter_by_status'] == 'yes' || $params['filter_by_status'] == '1'))) {
			$counts = thegem_extended_products_get_counts($params, $featured_only, $sale_only, $stock_only, $taxonomy_filter['product_cat'], $attributes, $price, $search);
		}
		$max_page = ceil(($products_grid_loop->found_posts - intval($offset)) / $items_per_page);
		if ($max_page > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;

		if ($products_grid_loop->have_posts()):
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
			remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
			remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
			remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);

			$item_classes = get_thegem_portfolio_render_item_classes($params);
			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params); ?>
			<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>"
				 data-pages-count="<?php echo esc_attr($max_page); ?>">
				<?php while ($products_grid_loop->have_posts()) : $products_grid_loop->the_post(); ?>
					<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
				<?php endwhile; ?>
			</div>
		<?php elseif(!$recently_viewed_only): ?>
			<div data-page="1" data-next-page="0" data-pages-count="1">
				<div class="portfolio-item not-found">
					<div class="found-wrap">
						<div class="image-inner empty"></div>
						<div class="msg">
							<?php echo wp_kses($params['not_found_text'], 'post'); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php $response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
		if (isset($counts)) {
			$response['counts'] = $counts;
		}
		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
	}
}
add_action('wp_ajax_extended_products_grid_load_more', 'thegem_extended_products_more_callback');
add_action('wp_ajax_nopriv_extended_products_grid_load_more', 'thegem_extended_products_more_callback');

if (!function_exists('thegem_extended_products_get_posts')) {
	function thegem_extended_products_get_posts($page = 1, $ppp = -1, $orderby = 'menu_order title', $order = 'ASC', $featured_only = false, $sale_only = false, $stock_only = false, $recently_viewed_only = false, $new_only = false, $taxonomy_filter = null, $meta_filter = null, $attributes = null, $price = null, $search = null, $attributes_query_type = null, $post__in = null, $offset = null, $exclude = null) {
		if (!$taxonomy_filter && !$meta_filter && !$attributes && !$post__in) {
			return null;
		}

		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'orderby' => $orderby,
			'order' => $order,
			'posts_per_page' => $ppp,
		);

		if(function_exists('pll_current_language')) {
			$args['lang'] = pll_current_language('slug');
		}

		$tax_query = $meta_query = [];

		$tax_query[] = array(
			'taxonomy' => 'product_visibility',
			'terms' => $search ? array('exclude-from-search') : array('exclude-from-catalog'),
			'field' => 'name',
			'operator' => 'NOT IN',
		);

		if (!empty($taxonomy_filter)) {
			foreach ($taxonomy_filter as $tax => $tax_arr) {
				if (!empty($tax_arr) && !in_array('0', $tax_arr)) {
					$query_arr = array(
						'taxonomy' => $tax,
						'field' => 'slug',
						'terms' => $tax_arr,
					);
					$tax_query[] = $query_arr;
				} elseif(!($search && thegem_get_option('website_search_post_type_product_variation'))) {
					$query_arr = array(
						'taxonomy' => $tax,
						'operator' => 'EXISTS'
					);
					$tax_query[] = $query_arr;
				}
			}
		}

		if (!empty($meta_filter)) {
			foreach ($meta_filter as $meta => $meta_arr) {
				if (!empty($meta_arr)) {
					if (strpos($meta, "__range") > 0) {
						$meta = str_replace("__range","", $meta);
						$query_arr = array(
							'key' => $meta,
							'value' => $meta_arr,
							'compare'   => 'BETWEEN',
							'type'   => 'NUMERIC',
						);
					} else if (strpos($meta, "__check") > 0) {
						$meta = str_replace("__check","", $meta);
						$check_meta_query = array(
							'relation' => 'OR',
						);
						foreach ($meta_arr as $value) {
							$check_meta_query[] = array(
								'key' => $meta,
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

		if (!empty($attributes)) {
			foreach ($attributes as $attr => $attr_arr) {
				if (!empty($attr_arr) && !in_array('0', $attr_arr, true)) {
					$query_arr = array(
						'taxonomy' => 'pa_' . $attr,
						'field' => 'slug',
						'terms' => $attr_arr,
						'include_children' => false,
						'operator' => !empty($attributes_query_type[$attr]) && $attributes_query_type[$attr] === 'and' ? 'AND' : 'IN',
					);
					$tax_query[] = $query_arr;
				}
			}
		}

		if ($featured_only) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => 'featured',
			);
		}

		if ($new_only) {
			if(thegem_get_option('product_new_label_display_method') === 'days') {
				$days = intval(thegem_get_option('product_new_label_display_days'));
				$days = $days > 0 ? $days : 60;
				$args['date_query'] = array(
					'after' => $days.' days ago',
				);
			} else {
				$tax_query[] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',
				);
			}
		}

		if ($stock_only) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => array('outofstock'),
				'operator' => 'NOT IN'
			);
		}

		if ($orderby == 'default') {
			$args['orderby'] = 'menu_order title';
		} else if ($orderby == 'popularity') {
			$args['orderby'] = array('meta_value_num' => 'DESC', 'ID' => 'DESC');
			$args['meta_key'] = 'total_sales';
		} else if ($orderby == 'price' || $orderby == 'price-desc') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_price';
		} else if ($orderby == 'rating') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
		} else if (!in_array($orderby, ['date', 'title', 'rand', 'menu_order ID'])) {
			if (strpos($orderby, 'num_') === 0) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = str_replace('num_', '', $orderby);
			} else {
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = $orderby;
			}
		}

		if ($order == 'default') {
			$args['order'] = 'ASC';
		}

		if ($sale_only) {
			$args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
		}

		if ($price) {
			$meta_query[] = array(
				'key' => '_price',
				'value' => $price,
				'compare' => 'BETWEEN',
				'type' => 'NUMERIC'
			);
		}

		$tax_query = apply_filters( 'woocommerce_product_query_tax_query', $tax_query, new WC_Query );
		if (!empty($tax_query)) {
			$args['tax_query'] = $tax_query;
		}

		$meta_query = apply_filters( 'woocommerce_product_query_meta_query', $meta_query, new WC_Query );
		if (!empty($meta_query)) {
			$args['meta_query'] = $meta_query;
		}

		if ($search) {
			$args['s'] = $search;
			$args['is_products_search'] = 1;
			$args['relevanssi'] = true;
			if(thegem_get_option('website_search_post_type_product_variation')) {
				$args['post_type'] = ['product', 'product_variation'];
			}
		}

		if ($post__in) {
			if ($sale_only) {
				$args['post__in'] = array_intersect($args['post__in'],$post__in);
			} else {
				$args['post__in'] = $post__in;
			}
		}

		if(!empty($recently_viewed_only)) {
			$viewed_products = ! empty( $_COOKIE['thegem_recently_viewed_products'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['thegem_recently_viewed_products'] ) ) : array();
			$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
			$viewed_products = empty($viewed_products) ? array(0) : $viewed_products;
			if(!empty($args['post__in'])) {
				$args['post__in'] = array_intersect($args['post__in'], $viewed_products);
			} else {
				$args['post__in'] = $viewed_products;
			}
		}

		if (!empty($offset)) {
			$args['offset'] = $ppp * ($page - 1) + $offset;
		} else {
			$args['paged'] = $page;
		}

		if (!empty($exclude)) {
			$args['post__not_in'] = $exclude;
		}

		return new WP_Query($args);
	}
}

if (!function_exists('thegem_extended_products_get_counts')) {
	function thegem_extended_products_get_counts($params, $featured_only = false, $sale_only = false, $stock_only = false, $products_cat = null, $attributes = null, $price = null, $search = null, $attributes_query_type = null) {
		global $wpdb;
		$counts = [];

		$cats_tax_query = [];
		if ($products_cat && !in_array('0', $products_cat, true)) {
			$cats_tax_query = array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => $products_cat
			);
		}

		$attributes_tax_query = [];
		if ($attributes) {
			foreach ($attributes as $attr => $attr_arr) {
				if (!empty($attr_arr) && !in_array('0', $attr_arr, true)) {
					$query_arr = array(
						'taxonomy' => 'pa_' . $attr,
						'field' => 'slug',
						'terms' => $attr_arr,
						'include_children' => false,
						'operator' => !empty($attributes_query_type[$attr]) && $attributes_query_type[$attr] === 'and' ? 'AND' : 'IN',
					);
					$attributes_tax_query[] = $query_arr;
				}
			}
		}

		$tax_query = [];

		if ($featured_only) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => 'featured',
			);
		}

		if ($stock_only) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => array('outofstock'),
				'operator' => 'NOT IN'
			);
		}

		$sale_ids_sql = '(' . implode( ',', array_map( 'absint', array_merge(array(0), wc_get_product_ids_on_sale()) ) ) . ')';
		if ($sale_only) {
			$sale_query_sql = " AND {$wpdb->posts}.ID IN $sale_ids_sql";
		} else {
			$sale_query_sql = '';
		}

		if ($price) {
			$meta_query = array(
				array(
					'key' => '_price',
					'value' => $price,
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				),
			);
		} else {
			$meta_query = [];
		}
		$meta_query = new WP_Meta_Query( $meta_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );

		if ($search) {
			$search_query_sql = " AND (({$wpdb->posts}.post_title LIKE '%".$search."%') OR ({$wpdb->posts}.post_excerpt LIKE '%".$search."%') OR ({$wpdb->posts}.post_content LIKE '%".$search."%'))";
		} else {
			$search_query_sql = '';
		}

		if (($params['filter_by_categories'] == 'yes' || $params['filter_by_categories'] == '1')) {
			$filtersListid = get_terms('product_cat', ['fields' => 'ids']);
			if (!is_wp_error($filtersListid) && !empty($filtersListid)) {
				$term_ids_sql   = '(' . implode( ',', array_map( 'absint', $filtersListid ) ) . ')';
				$tax_query_cat = $tax_query;
				if (!empty($attributes_tax_query)) {
					$tax_query_cat[] = $attributes_tax_query;
				}
				$wp_tax_query = new WP_Tax_Query( $tax_query_cat );
				$tax_query_sql = $wp_tax_query->get_sql( $wpdb->posts, 'ID' );
				$query = "FROM {$wpdb->posts}
	
		INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
		INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
		INNER JOIN {$wpdb->terms} AS terms USING( term_id )
		" . $tax_query_sql['join'] . $meta_query_sql['join']."
		
		WHERE {$wpdb->posts}.post_type IN ( 'product' )
		AND {$wpdb->posts}.post_status = 'publish'
		{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND terms.term_id IN $term_ids_sql".
				         $search_query_sql.$sale_query_sql;

				$query_sql = "SELECT COUNT( DISTINCT a.term_count ) AS term_count, a.term_count_id
				FROM ((
					SELECT {$wpdb->posts}.ID as term_count, term_taxonomy.term_id AS term_count_id
					".$query."
				)
				UNION ALL
				(
					SELECT {$wpdb->posts}.ID as term_count, term_taxonomy.parent AS term_count_id
					".$query."
				)) as a
				
				GROUP BY a.term_count_id";


				$query_hash = md5( $query_sql );
				$cache = apply_filters( 'thegem_extended_products_filters_count_maybe_cache', true );
				if ( true === $cache ) {
					$cached_counts = (array) get_transient( 'thegem_extended_products_filters_counts_categories' );
				} else {
					$cached_counts = array();
				}

				if ( ! isset( $cached_counts[ $query_hash ] ) ) {
					$results = $wpdb->get_results( $query_sql, ARRAY_A );
					$counts_cats = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
					$cached_counts[ $query_hash ] = $counts_cats;
					if ( true === $cache ) {
						set_transient( 'thegem_extended_products_filters_counts_categories', $cached_counts, DAY_IN_SECONDS );
					}
				}

				foreach ($filtersListid as $id) {
					$counts[$id] = isset($cached_counts[ $query_hash ][$id]) ? $cached_counts[ $query_hash ][$id] : 0;
				}
			}
		}
		if ($params['filter_by_attribute'] == 'yes' || $params['filter_by_attribute'] == '1') {
			$filter_attr = $params['repeater_attributes'];
			foreach ($filter_attr as $index => $item) {
				if (!empty($item['attribute_name'])) {
					$filtersListid = get_terms('pa_' . $item['attribute_name'], ['fields' => 'ids','hide_empty' => false]);
					if (is_wp_error($filtersListid) || empty($filtersListid)) {
						continue;
					}
					$term_ids_sql = '(' . implode( ',', array_map( 'absint', $filtersListid ) ) . ')';

					$attr_tax_query = [];
					if ($attributes) {
						foreach ($attributes as $attr => $attr_arr) {
							if (!empty($attr_arr) && !in_array('0', $attr_arr, true) && ($attr != $item['attribute_name'] /*|| strtolower($item['attribute_query_type']) == 'or'*/)) {
								$query_arr = array(
									'taxonomy' => 'pa_' . $attr,
									'field' => 'slug',
									'terms' => $attr_arr,
									'include_children' => false,
									'operator' => !empty($attributes_query_type[$attr]) && $attributes_query_type[$attr] === 'and' ? 'AND' : 'IN',
								);
								$attr_tax_query[] = $query_arr;
							}
						}
					}
					$tax_query_attr = $tax_query;
					if (!empty($cats_tax_query)) {
						$tax_query_attr[] = $cats_tax_query;
					}
					if (!empty($attr_tax_query)) {
						$tax_query_attr[] = $attr_tax_query;
					}
					$wp_tax_query = new WP_Tax_Query( $tax_query_attr );
					$tax_query_sql = $wp_tax_query->get_sql( $wpdb->posts, 'ID' );

					$query_sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) AS term_count, term_taxonomy.term_id AS term_count_id

			FROM {$wpdb->posts}
	
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join']."
			
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
				AND terms.term_id IN $term_ids_sql".
					             $search_query_sql.$sale_query_sql.
					             " GROUP BY term_taxonomy.term_id";

					$query_hash = md5( $query_sql );

					$cache = apply_filters( 'thegem_extended_products_filters_count_maybe_cache', true );
					if ( true === $cache ) {
						$cached_counts = (array) get_transient( 'thegem_extended_products_filters_counts_' . sanitize_title( $item['attribute_name'] ) );
					} else {
						$cached_counts = array();
					}

					if ( ! isset( $cached_counts[ $query_hash ] ) ) {
						$results = $wpdb->get_results( $query_sql, ARRAY_A );
						$counts_attrs = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
						$cached_counts[ $query_hash ] = $counts_attrs;
						if ( true === $cache ) {
							set_transient( 'wc_layered_nav_counts_' . sanitize_title( $item['attribute_name'] ), $cached_counts, DAY_IN_SECONDS );
						}
					}
					foreach ($filtersListid as $id) {
						$counts[$id] = isset($cached_counts[ $query_hash ][$id]) ? $cached_counts[ $query_hash ][$id] : 0;
					}
				}
			}
		}

		if ($params['filter_by_status'] == 'yes' || $params['filter_by_status'] == '1') {
			$tax_query_sale = $tax_query;
			if (!empty($cats_tax_query)) {
				$tax_query_sale[] = $cats_tax_query;
			}
			if (!empty($attributes_tax_query)) {
				$tax_query_sale[] = $attributes_tax_query;
			}
			$wp_tax_query = new WP_Tax_Query( $tax_query_sale );
			$tax_query_sql = $wp_tax_query->get_sql( $wpdb->posts, 'ID' );
			if ($params['filter_by_status_sale'] == 'yes' || $params['filter_by_status_sale'] == '1') {
				$query_sql = "SELECT COUNT(*) as count
			FROM {$wpdb->posts}
			" . $tax_query_sql['join'] . $meta_query_sql['join']."
			
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND {$wpdb->posts}.ID IN $sale_ids_sql".
				             $search_query_sql;

				$query_hash = md5( $query_sql );

				$cache = apply_filters( 'thegem_extended_products_filters_count_maybe_cache', true );
				if ( true === $cache ) {
					$cached_counts = (array) get_transient( 'thegem_extended_products_filters_sale' );
				} else {
					$cached_counts = array();
				}

				if ( ! isset( $cached_counts[ $query_hash ] ) ) {
					$results = $wpdb->get_results( $query_sql, ARRAY_A );
					$counts_sale = intval($results[0]['count']);
					$cached_counts[ $query_hash ] = $counts_sale;
					if ( true === $cache ) {
						set_transient( 'thegem_extended_products_filters_sale' , $cached_counts, DAY_IN_SECONDS );
					}
				}
				$counts['sale'] = $cached_counts[ $query_hash ];
			}
			if ($params['filter_by_status_stock'] == 'yes' || $params['filter_by_status_stock'] == '1') {
				$query_sql = "SELECT COUNT(*) as count
			FROM {$wpdb->posts}
			" . $tax_query_sql['join'] . $meta_query_sql['join']."
			
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND {$wpdb->posts}.ID NOT IN (
			SELECT object_id
			FROM {$wpdb->term_relationships} AS term_relationships
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			
			WHERE terms.name = 'outofstock' )".
				             $search_query_sql.$sale_query_sql;

				$query_hash = md5( $query_sql );

				$cache = apply_filters( 'thegem_extended_products_filters_count_maybe_cache', true );
				if ( true === $cache ) {
					$cached_counts = (array) get_transient( 'thegem_extended_products_filters_stock' );
				} else {
					$cached_counts = array();
				}

				if ( ! isset( $cached_counts[ $query_hash ] ) ) {
					$results = $wpdb->get_results( $query_sql, ARRAY_A );
					$counts_stock = intval($results[0]['count']);
					$cached_counts[ $query_hash ] = $counts_stock;
					if ( true === $cache ) {
						set_transient( 'thegem_extended_products_filters_stock' , $cached_counts, DAY_IN_SECONDS );
					}
				}
				$counts['stock'] = $cached_counts[ $query_hash ];
			}
		}

		return $counts;
	}
}

if (!function_exists('thegem_extended_products_render_item')) {
	function thegem_extended_products_render_item($params, $item_classes, $thegem_sizes = null, $post_id = false, $is_cat = false, $is_elementor_widget = false, $is_carousel = false) {
		global $post, $product, $woocommerce_loop;

		if ($is_cat) {
			$category = $post_id;
			$slugs = array();
			$thegem_highlight_type = 'disabled';
		} else if ($post_id) {
			$slugs = wp_get_object_terms($post_id, 'product_cat', array('fields' => 'slugs'));

			$thegem_product_featured_data = thegem_get_sanitize_product_featured_data(get_the_ID());

			if (($params['ignore_highlights'] != 'yes' && $params['ignore_highlights'] != '1' ) && !empty($thegem_product_featured_data['highlight'])) {
				$thegem_highlight_type = $thegem_product_featured_data['highlight_type'];
			} else {
				$thegem_highlight_type = 'disabled';
			}
		} else {
			$slugs = array();
			$product_grid_item_size = true;
			$thegem_highlight_type = 'disabled';
		}

		$thegem_classes = array('portfolio-item', 'product');
		$thegem_classes = array_merge($thegem_classes, $slugs);

		if ($params['layout'] != 'metro' || isset($product_grid_item_size)) {
			if ($thegem_highlight_type != 'disabled' && $thegem_highlight_type != 'vertical') {
				$thegem_classes = array_merge($thegem_classes, get_thegem_portfolio_render_item_classes($params, $thegem_highlight_type));
			} else {
				$thegem_classes = array_merge($thegem_classes, $item_classes);
			}
		}

		if ($thegem_highlight_type != 'disabled') {
			$thegem_classes[] = 'double-item';
			$thegem_classes[] = 'double-item-' . $thegem_highlight_type;

			$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params, $thegem_highlight_type);
		}

		if ($params['loading_animation'] === '1' || $params['loading_animation'] === 'yes') {
			$thegem_classes[] = 'item-animations-not-inited';
		}

		$add_to_cart_class = '';
		$product_bottom_class = '';

		if ($params['product_show_add_to_cart_mobiles'] != '1' && $params['product_show_add_to_cart_mobiles'] != 'yes') {
			$add_to_cart_class = 'hide-tablet hide-mobile';
			if ((!isset($params['product_show_wishlist']) || ($params['product_show_wishlist'] != 'yes' && $params['product_show_wishlist'] != '1')) && $params['social_sharing'] != 'yes' && $params['social_sharing'] != '1') {
				$product_bottom_class = 'empty-mobile';
			}
		}

		if ($is_cat) {
			include(locate_template(array('gem-templates/products-extended/content-product-cat.php')));
		} else {
			include(locate_template(array('gem-templates/products-extended/content-product-grid-item.php')));
		}

	}
}

if (!function_exists('thegem_extended_products_get_product_price_range')) {
	function thegem_extended_products_get_product_price_range($wp_query = false) {
		global $wpdb;

		if ($wp_query) {

			$tax_query = isset($wp_query->tax_query->queries) ? $wp_query->tax_query->queries : array();
			$meta_query = isset($wp_query->query_vars['meta_query']) ? $wp_query->query_vars['meta_query'] : array();

			foreach ($meta_query + $tax_query as $key => $query) {
				if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
					unset($meta_query[$key]);
				}
			}

			$meta_query = new \WP_Meta_Query($meta_query);
			$tax_query = new \WP_Tax_Query($tax_query);

			$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
			$tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');

			$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
			$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " WHERE {$wpdb->posts}.post_type IN ('product')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('_price')
			AND price_meta.meta_value > '' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];


			$search_terms = isset($wp_query->query_vars['search_terms']) ? $wp_query->query_vars['search_terms'] : [];
			if (!empty($search_terms)) {
				$search_sql = array();

				foreach ($search_terms as $term) {
					$include = '-' !== substr($term, 0, 1);

					if ($include) {
						$like_op = 'LIKE';
						$andor_op = 'OR';
					} else {
						$like_op = 'NOT LIKE';
						$andor_op = 'AND';
						$term = substr($term, 1);
					}

					$like = '%' . $wpdb->esc_like($term) . '%';
					$search_sql[] = $wpdb->prepare("(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", $like, $like, $like);
				}

				if (!empty($search_sql) && !is_user_logged_in()) {
					$search_sql[] = "($wpdb->posts.post_password = '')";
				}

				if (!empty($search_sql)) {
					$sql .= " AND " . implode(" AND ", $search_sql);
				}
			}

			$post__in = isset($wp_query->query_vars['post__in']) ? $wp_query->query_vars['post__in'] : [];
			if (!empty($post__in)) {
				$sql .= " AND {$wpdb->posts}.ID IN (" . implode(',', $post__in) . ")";
			}

		} else {
			$sql = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
			$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id ";
			$sql .= " WHERE {$wpdb->posts}.post_type IN ('product')
			AND {$wpdb->posts}.post_status = 'publish'
			AND price_meta.meta_key IN ('_price')
			AND price_meta.meta_value > '' ";
		}

		$prices = $wpdb->get_row($sql);

		return [
			'min' => $prices->min_price,
			'max' => $prices->max_price
		];
	}
}

if (!function_exists('thegem_extended_products_render_styles')) {
	function thegem_extended_products_render_styles($params) {
		$widget_styles = false;
		if (isset($params['style_uid']) && $params['style_uid'] != '') {
			$wrapper = '.extended-products-grid#style-' . $params['style_uid'];
			$wrapper_skeleton = '.preloader#style-preloader-' . $params['style_uid'];
			$wrapper_notification = '.thegem-popup-notification-wrap#style-notification-' . $params['style_uid'];
			$widget_styles = true;
		} else {
			$wrapper = '.portfolio.extended-products-grid.to-extended-products';
			$wrapper_skeleton = '.preloader[data-style-uid="to_products"]';
			$wrapper_notification = '.thegem-popup-notification-wrap[data-style-uid="to_products"]';
		}

		$image_gaps = $params['image_gaps'];
		$image_gaps_tablet = $params['image_gaps_tablet'];
		$image_gaps_mobile = $params['image_gaps_mobile'];

		if ($widget_styles) {
			$style = "<style>";
		} else {
			$style = "";
		};

		$style .= $wrapper . " .portfolio-item:not(.size-item) { padding: calc(" . $image_gaps_mobile . "px/2) !important; }" .
		          $wrapper . " .portfolio-item.size-item { padding: 0 calc(" . $image_gaps_mobile . "px/2) !important; }" .
		          $wrapper . ":not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps_mobile . "px/2); }" .
		          $wrapper . ".item-separator .portfolio-row { margin: 0 calc(-" . $image_gaps_mobile . "px/2); }" .
		          $wrapper . ".fullwidth-columns:not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps_mobile . "px/2) 0; }" .
		          $wrapper . ".fullwidth-columns.item-separator .portfolio-row { margin: 0; }" .
		          $wrapper . " .fullwidth-block:not(.no-paddings) { padding-left: " . $image_gaps_mobile . "px; padding-right: " . $image_gaps_mobile . "px; }" .
		          $wrapper . " .fullwidth-block .portfolio-row { padding-left: calc(" . $image_gaps_mobile . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }" .
		          $wrapper . ":not(.item-separator) .fullwidth-block .portfolio-top-panel { padding-left: " . $image_gaps_mobile . "px; padding-right: " . $image_gaps_mobile . "px; }" .
		          $wrapper . ".item-separator .fullwidth-block .portfolio-top-panel { padding-left: calc(" . $image_gaps_mobile . "px/2); padding-right: calc(" . $image_gaps_mobile . "px/2); }" .
		          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: " . $image_gaps_mobile . "px; }".
		          $wrapper_skeleton . " .portfolio-item { padding: calc(" . $image_gaps_mobile . "px/2); }" .
		          $wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps_mobile . "px/2); }" .
				  $wrapper . ".list-style.with-divider .portfolio-set .portfolio-item .wrap:before { top: calc(-" . $image_gaps_mobile . "px/2); }";

		$style .= "@media (min-width: 768px) { " . $wrapper . " .portfolio-item:not(.size-item) { padding: calc(" . $image_gaps_tablet . "px/2) !important; }" .
		          $wrapper . " .portfolio-item.size-item { padding: 0 calc(" . $image_gaps_tablet . "px/2) !important; }" .
		          $wrapper . ":not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps_tablet . "px/2); }" .
		          $wrapper . ".item-separator .portfolio-row { margin: 0 calc(-" . $image_gaps_tablet . "px/2); }" .
		          $wrapper . ".fullwidth-columns:not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps_tablet . "px/2) 0; }" .
		          $wrapper . ".fullwidth-columns.item-separator .portfolio-row { margin: 0; }" .
		          $wrapper . " .fullwidth-block:not(.no-paddings) { padding-left: " . $image_gaps_tablet . "px; padding-right: " . $image_gaps_tablet . "px; }" .
		          $wrapper . " .fullwidth-block .portfolio-row { padding-left: calc(" . $image_gaps_tablet . "px/2); padding-right: calc(" . $image_gaps_tablet . "px/2); }" .
		          $wrapper . ":not(.item-separator) .fullwidth-block .portfolio-top-panel { padding-left: " . $image_gaps_tablet . "px; padding-right: " . $image_gaps_tablet . "px; }" .
		          $wrapper . ".item-separator .fullwidth-block .portfolio-top-panel { padding-left: calc(" . $image_gaps_tablet . "px/2); padding-right: calc(" . $image_gaps_tablet . "px/2); }" .
		          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: " . $image_gaps_tablet . "px; }".
		          $wrapper_skeleton . " .portfolio-item { padding: calc(" . $image_gaps_tablet . "px/2); }" .
		          $wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps_tablet . "px/2); }" .
				  $wrapper . ".list-style.with-divider .portfolio-set .portfolio-item .wrap:before { top: calc(-" . $image_gaps_tablet . "px/2); } }";

		$style .= "@media (min-width: 992px) { " . $wrapper . " .portfolio-item:not(.size-item) { padding: calc(" . $image_gaps . "px/2) !important; }" .
		          $wrapper . " .portfolio-item.size-item { padding: 0 calc(" . $image_gaps . "px/2) !important; }" .
		          $wrapper . ":not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps . "px/2); }" .
		          $wrapper . ".item-separator .portfolio-row { margin: 0 calc(-" . $image_gaps . "px/2); }" .
		          $wrapper . ".fullwidth-columns:not(.item-separator) .portfolio-row { margin: calc(-" . $image_gaps . "px/2) 0; }" .
		          $wrapper . ".fullwidth-columns.item-separator .portfolio-row { margin: 0; }" .
		          $wrapper . " .fullwidth-block:not(.no-paddings) { padding-left: " . $image_gaps . "px; padding-right: " . $image_gaps . "px; }" .
		          $wrapper . " .fullwidth-block .portfolio-row { padding-left: calc(" . $image_gaps . "px/2); padding-right: calc(" . $image_gaps . "px/2); }" .
		          $wrapper . ":not(.item-separator) .fullwidth-block .portfolio-top-panel { padding-left: " . $image_gaps . "px; padding-right: " . $image_gaps . "px; }" .
		          $wrapper . ".item-separator .fullwidth-block .portfolio-top-panel { padding-left: calc(" . $image_gaps . "px/2); padding-right: calc(" . $image_gaps . "px/2); }" .
		          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: " . $image_gaps . "px; }".
		          $wrapper_skeleton . " .portfolio-item { padding: calc(" . $image_gaps . "px/2); }" .
		          $wrapper_skeleton . " .skeleton-posts.portfolio-row { margin: calc(-" . $image_gaps . "px/2); }" .
				  $wrapper . ".list-style.with-divider .portfolio-set .portfolio-item .wrap:before { top: calc(-" . $image_gaps . "px/2); } }";

		if ((isset($params['columns_desktop']) && $params['columns_desktop'] == '100%') ||
		    (isset($params['fullwidth_section_sorting']) && $params['fullwidth_section_sorting'] == 'yes') ||
		    (!$widget_styles && thegem_get_option('product_archive_content_width') === 'fullwidth-nogaps')) {
			if ($image_gaps_mobile < 21) {
				$style .= $wrapper . " .portfolio-row-outer.fullwidth-block .portfolio-top-panel, " .
				          $wrapper . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }" .
				          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: 21px;}";
			}
			if ($image_gaps_tablet < 21) {
				$style .= "@media (min-width: 768px) { " . $wrapper . " .portfolio-row-outer.fullwidth-block .portfolio-top-panel, " .
				          $wrapper . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }" .
				          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: 21px;}}";
			}
			if ($image_gaps < 21) {
				$style .= "@media (min-width: 992px) { " . $wrapper . " .portfolio-row-outer.fullwidth-block .portfolio-top-panel, " .
				          $wrapper . " .portfolio-item.not-found .found-wrap { padding-left: 21px; padding-right: 21px; }" .
				          $wrapper . ".fullwidth-columns .with-filter-sidebar .filter-sidebar { padding-left: 21px;}}";
			}
		}

		if (isset($params['product_show_categories']) && ($params['product_show_categories'] === '' || $params['product_show_categories'] === '0')) {
			$style .= $wrapper . " .categories { display: none }";
		}

		if (isset($params['product_show_categories_tablet'])) {
			if ($params['product_show_categories_tablet'] === '' || $params['product_show_categories_tablet'] === '0') {
				$style .= "@media (max-width: 991px) { " . $wrapper . " .categories { display: none } }";
			} else {
				$style .= "@media (max-width: 991px) { " . $wrapper . " .categories { display: block } }";
			}
		}

		if (isset($params['product_show_categories_mobile'])) {
			if ($params['product_show_categories_mobile'] === '' || $params['product_show_categories_mobile'] === '0') {
				$style .= "@media (max-width: 767px) { " . $wrapper . " .categories { display: none } }";
			} else {
				$style .= "@media (max-width: 767px) { " . $wrapper . " .categories { display: block } }";
			}
		}

		if (isset($params['product_show_reviews']) && ($params['product_show_reviews'] === '' || $params['product_show_reviews'] === '0')) {
			$style .= $wrapper . " .reviews { display: none }";
		}

		if (isset($params['product_show_reviews_tablet'])) {
			if ($params['product_show_reviews_tablet'] === '' || $params['product_show_reviews_tablet'] === '0') {
				$style .= "@media (max-width: 991px) { " . $wrapper . " .reviews { display: none } }";
			} else {
				$style .= "@media (max-width: 991px) { " . $wrapper . " .reviews { display: block } }";
			}
		}

		if (isset($params['product_show_reviews_mobile'])) {
			if ($params['product_show_reviews_mobile'] === '' || $params['product_show_reviews_mobile'] === '0') {
				$style .= "@media (max-width: 767px) { " . $wrapper . " .reviews { display: none } }";
			} else {
				$style .= "@media (max-width: 767px) { " . $wrapper . " .reviews { display: block } }";
			}
		}

		if (isset($params['product_separator']) && $params['product_separator'] === '1') {
			$style .= $wrapper . ".item-separator .portfolio-item:before," .
			          $wrapper . ".item-separator .portfolio-item:after," .
			          $wrapper . ".item-separator .portfolio-item .item-separator-box:before," .
			          $wrapper . ".item-separator .portfolio-item .item-separator-box:after {
			border-width: " . $params['product_separator_width'] . "px;
			border-color: " . $params['product_separator_color'] . ";}";

			$style .= $wrapper . ".item-separator .portfolio-item .item-separator-box:before," .
			          $wrapper . ".item-separator .portfolio-item .item-separator-box:after {
			width: calc(100% + " . $params['product_separator_width'] . "px);
			left: calc(-" . $params['product_separator_width'] . "px/2);}";

			$style .= $wrapper . ".item-separator .portfolio-item:before," .
			          $wrapper . ".item-separator .portfolio-item:after {
			height: calc(100% + " . $params['product_separator_width'] . "px);
			top: calc(-" . $params['product_separator_width'] . "px/2);}";

			if (intval($params['product_separator_width']) % 2 !== 0 ) {
				$floor = floor(intval($params['product_separator_width']) / 2);
				$ceil = ceil(intval($params['product_separator_width']) / 2);

				$style .= $wrapper . " .portfolio-item:before {
				transform: translateX(-" . $floor . "px) !important;
				top: -" . $floor . "px !important;}";

				$style .= $wrapper . " .portfolio-item:after {
				transform: translateX(" . $ceil . "px) !important;
				top: -" . $floor . "px !important;}";

				$style .= $wrapper . " .portfolio-item .item-separator-box:before {
				transform: translateY(-" . $floor . "px) !important;
				left: -" . $floor . "px !important;}";

				$style .= $wrapper . " .portfolio-item .item-separator-box:after {
				transform: translateY(" . $ceil . "px) !important;
				left: -" . $floor . "px !important;}";
			}
		}

		if (isset($params['image_border_width']) && $params['image_border_width'] != '') {
			if (isset($params['border_caption_container']) && $params['border_caption_container'] === '1') {
				$style .= $wrapper . " .portfolio-item.product .wrap > .image { border-width: " . $params['image_border_width'] . "px; border-style: solid; border-bottom: 0; }";
				$style .= $wrapper . " .portfolio-item.product .wrap > .caption { border-width: " . $params['image_border_width'] . "px; border-style: solid; border-top: 0; }";
			} else {
				$style .= $wrapper . " .portfolio-item.product .wrap > .image { border-width: " . $params['image_border_width'] . "px; border-style: solid; }";
			}

			if (isset($params['image_border_color']) && $params['image_border_color'] != '') {
				$style .= $wrapper . " .portfolio-item.product .wrap > .image, " .
				          $wrapper . " .portfolio-item.product .wrap > .caption { border-color: " . $params['image_border_color'] . "; }";
			}

			if (isset($params['image_border_color_hover']) && $params['image_border_color_hover'] != '') {
				$style .= $wrapper . " .portfolio-item:hover.product .wrap > .image, " .
				          $wrapper . " .portfolio-item:hover.product .wrap > .caption { border-color: " . $params['image_border_color_hover'] . "; }";
			}
		}

		if (isset($params['image_border_radius']) && $params['image_border_radius'] != '') {
			if (isset($params['border_caption_container']) && $params['border_caption_container'] === '1') {
				$style .= $wrapper . " .portfolio-item .wrap { border-radius: " . $params['image_border_radius'] . "px; }";
				$style .= $wrapper . " .portfolio-item .wrap .image," .
						  $wrapper . " .portfolio-item .wrap .image-inner {
			 border-top-left-radius: " . $params['image_border_radius'] . "px;
			 border-top-right-radius: " . $params['image_border_radius'] . "px; }";
				$style .= $wrapper . " .portfolio-item .wrap .caption {
			 border-bottom-left-radius: " . $params['image_border_radius'] . "px;
			 border-bottom-right-radius: " . $params['image_border_radius'] . "px; }";
			} else {
				$style .= $wrapper . " .portfolio-item .image," .
				          $wrapper . " .portfolio-item .image .image-inner," .
				          $wrapper . " .portfolio-item .image .overlay," .
				          $wrapper . " .portfolio-item .image .variations-notification," .
				          $wrapper . ".caption-position-hover .portfolio-item .wrap," .
				          $wrapper . ".caption-position-image .portfolio-item .wrap { border-radius: " . $params['image_border_radius'] . "px }";
				$style .= $wrapper . ".caption-position-page .portfolio-item .wrap {  border-radius: " . $params['image_border_radius'] . "px " . $params['image_border_radius'] . "px 0 0 }";
			}
		}

		if (isset($params['caption_container_preset_hover_background_color']) && $params['caption_container_preset_hover_background_color'] != '') {
			if (isset($params['caption_container_preset_hover']) && $params['caption_container_preset_hover'] == 'solid') {
				$style .= $wrapper . ".caption-container-preset-solid .portfolio-item .image .overlay .links-wrapper .links { background: " . $params['caption_container_preset_hover_background_color'] . " }";
			} else {
				$style .= $wrapper . ".caption-container-preset-light .portfolio-item .image .overlay .links-wrapper .links," .
				          $wrapper . ".caption-container-preset-dark .portfolio-item .image .overlay .links-wrapper .links { background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, " . $params['caption_container_preset_hover_background_color'] . " 100%) }";
			}
		}

		if (isset($params['categories_color_normal']) && $params['categories_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item.product .caption .categories," .
			          $wrapper . " .portfolio-item.product .caption .categories a { color: " . $params['categories_color_normal'] . " }";
		}

		if (isset($params['categories_color_hover']) && $params['categories_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item.product .caption .categories a:hover { color: " . $params['categories_color_hover'] . " }";
		}

		if (isset($params['title_color_normal']) && $params['title_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .caption .title, " . $wrapper . " .portfolio-item .wrap .caption .title a { color: " . $params['title_color_normal'] . " }";
		}

		if (isset($params['title_color_hover']) && $params['title_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .wrap .caption .title," .
			          $wrapper . " .portfolio-item.hover-effect .wrap .caption .title," .
			          $wrapper . " .portfolio-item:hover .wrap .caption .title a," .
			          $wrapper . " .portfolio-item.hover-effect .wrap .caption .title a { color: " . $params['title_color_hover'] . " }";
		}

		if (isset($params['truncate_title']) && intval($params['truncate_title']) > 0) {
			$style .= $wrapper . " .portfolio-item .caption .title span, " . $wrapper . " .portfolio-item .caption .title a { max-height: initial; white-space: initial; display: -webkit-box; -webkit-line-clamp: " . intval($params['truncate_title']) . "; line-clamp: " . intval($params['truncate_title']) . "; -webkit-box-orient: vertical; }";
		}

		if (isset($params['truncate_description']) && $params['truncate_description'] != '') {
			$style .= $wrapper . " .portfolio-item .caption .description .subtitle span { max-height: initial; display: -webkit-box; -webkit-line-clamp: " . $params['truncate_description'] . "; line-clamp: " . $params['truncate_description'] . "; -webkit-box-orient: vertical; }";
		}

		if (isset($params['description_color_normal']) && $params['description_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .caption .description .subtitle, " . $wrapper . " .portfolio-item .wrap .caption .title a { color: " . $params['description_color_normal'] . " }";
		}

		if (isset($params['description_color_hover']) && $params['description_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .wrap .caption .description .subtitle," .
				$wrapper . " .portfolio-item.hover-effect .wrap .caption .description .subtitle { color: " . $params['description_color_hover'] . " }";
		}

		if (isset($params['description_max_width']) && $params['description_max_width'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .description { display: inline-block; max-width: " . $params['description_max_width'] . "px }";
		}

		if (isset($params['price_color_normal']) && $params['price_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item.product .caption .product-price .price { color: " . $params['price_color_normal'] . " }";
		}

		if (isset($params['price_color_hover']) && $params['price_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item.product:hover .caption .product-price .price," .
			          $wrapper . " .portfolio-item.product.hover-effect .caption .product-price .price { color: " . $params['price_color_hover'] . " }";
		}

		if (isset($params['rated_color_normal']) && $params['rated_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .star-rating > span:before { color: " . $params['rated_color_normal'] . " !important }";
		}

		if (isset($params['rated_color_hover']) && $params['rated_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .star-rating > span:before," .
			          $wrapper . " .portfolio-item.hover-effect .star-rating > span:before { color: " . $params['rated_color_hover'] . " !important }";
		}

		if (isset($params['base_color_normal']) && $params['base_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .star-rating:before { color: " . $params['base_color_normal'] . " !important }";
		}

		if (isset($params['base_color_hover']) && $params['base_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .star-rating:before," .
			          $wrapper . " .portfolio-item.hover-effect .star-rating:before { color: " . $params['base_color_hover'] . " !important }";
		}

		if (isset($params['caption_container_alignment']) && $params['caption_container_alignment'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption { text-align: " . $params['caption_container_alignment'] . " }";
			$style .= $wrapper . " .portfolio-item .wrap > .caption .star-rating," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-rating .empty-rating:before," .
			          $wrapper . " .portfolio-item .wrap > .caption .categories," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-bottom { margin-" . $params['caption_container_alignment'] . ": 0 !important }";
		}

		if (isset($params['caption_container_alignment_tablet']) && $params['caption_container_alignment_tablet'] != '') {
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-item .wrap > .caption { text-align: " . $params['caption_container_alignment_tablet'] . " }";
			$style .= $wrapper . " .portfolio-item .wrap > .caption .star-rating," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-rating .empty-rating:before," .
			          $wrapper . " .portfolio-item .wrap > .caption .categories," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-bottom { margin-" . $params['caption_container_alignment_tablet'] . ": 0 }}";
		}

		if (isset($params['caption_container_alignment_mobile']) && $params['caption_container_alignment_mobile'] != '') {
			$style .= "@media (max-width: 767px) { " . $wrapper . " .portfolio-item .wrap > .caption { text-align: " . $params['caption_container_alignment_mobile'] . " }";
			$style .= $wrapper . " .portfolio-item .wrap > .caption .star-rating," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-rating .empty-rating:before," .
			          $wrapper . " .portfolio-item .wrap > .caption .categories," .
			          $wrapper . " .portfolio-item .wrap > .caption .product-bottom { margin-" . $params['caption_container_alignment_mobile'] . ": 0 }}";
		}

		if (isset($params['caption_background']) && $params['caption_background'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption { background-color: " . $params['caption_background'] . " !important }";
		}

		if (isset($params['caption_background_hover']) && $params['caption_background_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .wrap > .caption," .
			          $wrapper . " .portfolio-item.hover-effect .wrap > .caption { background-color: " . $params['caption_background_hover'] . " !important }";
		}

		if (isset($params['spacing_separator_weight']) && $params['spacing_separator_weight'] != '') {
			$style .= $wrapper . " .portfolio-item .product-info .product-rating .empty-rating:before { border-width: " . $params['spacing_separator_weight'] . "px }";
		}

		if (isset($params['spacing_separator_color']) && $params['spacing_separator_color'] != '') {
			$style .= $wrapper . " .portfolio-item .product-info .product-rating .empty-rating:before { border-color: " . $params['spacing_separator_color'] . " }";
		}

		if (isset($params['spacing_separator_color_hover']) && $params['spacing_separator_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item:hover .product-info .product-rating .empty-rating:before," .
			          $wrapper . " .portfolio-item.hover-effect .product-info .product-rating .empty-rating:before { border-color: " . $params['spacing_separator_color_hover'] . " }";
		}

		if (isset($params['icons_color_normal']) && $params['icons_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon { color: " . $params['icons_color_normal'] . " }";
		}

		if (isset($params['icons_color_hover']) && $params['icons_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon:hover { color: " . $params['icons_color_hover'] . " }";
		}

		if (isset($params['icons_background_color_normal']) && $params['icons_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon { background-color: " . $params['icons_background_color_normal'] . " }";
		}

		if (isset($params['icons_background_color_hover']) && $params['icons_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon:hover { background-color: " . $params['icons_background_color_hover'] . " }";
		}

		if (isset($params['icons_border_color_normal']) && $params['icons_border_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon { border-color: " . $params['icons_border_color_normal'] . " }";
		}

		if (isset($params['icons_border_color_hover']) && $params['icons_border_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon:hover { border-color: " . $params['icons_border_color_hover'] . " }";
		}

		if (isset($params['icons_border_width']) && $params['icons_border_width'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption a.icon," .
			          $wrapper . " .portfolio-item .image .overlay .links .portfolio-icons .portfolio-icons-inner a.icon { border-width: " . $params['icons_border_width'] . "px; border-style: solid }";
		}

		if (isset($params['buttons_border_width']) && $params['buttons_border_width'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-width: " . $params['buttons_border_width'] . "px }";
		}

		if (isset($params['buttons_border_width_tablet']) && $params['buttons_border_width_tablet'] != '') {
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-item .wrap .product-bottom.on-page-caption .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-width: " . $params['buttons_border_width_tablet'] . "px }}";
		}

		if (isset($params['buttons_border_width_mobile']) && $params['buttons_border_width_mobile'] != '') {
			$style .= "@media (max-width: 767px) { " . $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-width: " . $params['buttons_border_width_mobile'] . "px }}";
		}

		if (isset($params['buttons_border_radius']) && $params['buttons_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-radius: " . $params['buttons_border_radius'] . "px }";
		}

		if (isset($params['buttons_border_radius_tablet']) && $params['buttons_border_radius_tablet'] != '') {
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-radius: " . $params['buttons_border_radius_tablet'] . "px }}";
		}

		if (isset($params['buttons_border_radius_mobile']) && $params['buttons_border_radius_mobile'] != '') {
			$style .= "@media (max-width: 767px) { " . $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button," .
			          $wrapper_notification . " .thegem-popup-notification .notification-message a.button," .
			          $wrapper . " .portfolio-item.product .actions .button { border-radius: " . $params['buttons_border_radius_mobile'] . "px }}";
		}

		if (isset($params['buttons_icon_alignment']) && $params['buttons_icon_alignment'] != '' ) {
			if ($params['buttons_icon_alignment'] === 'left') {
				$direction = 'row';
			} else {
				$direction = 'row-reverse';
			}
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart.type_button .button { flex-direction: " . $direction . " }";
		}

		if (isset($params['button_cart_color_normal']) && $params['button_cart_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple, " .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple, " .
			          $wrapper . " .portfolio-item.product .actions .button { color: " . $params['button_cart_color_normal'] . " }";
		}

		if (isset($params['button_cart_color_hover']) && $params['button_cart_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple:hover, " .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple:hover, " .
			          $wrapper . " .portfolio-item.product .actions .button:hover { color: " . $params['button_cart_color_hover'] . " }";
		}

		if (isset($params['button_cart_background_color_normal']) && $params['button_cart_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple, " .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple, " .
			          $wrapper . " .portfolio-item.product .actions .button { background-color: " . $params['button_cart_background_color_normal'] . " }";
		}

		if (isset($params['button_cart_background_color_hover']) && $params['button_cart_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple:hover, " .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple:hover, " .
			          $wrapper . " .portfolio-item.product .actions .button:hover { background-color: " . $params['button_cart_background_color_hover'] . " }";
		}

		if (isset($params['button_cart_border_color_normal']) && $params['button_cart_border_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple," .
			          $wrapper . " .portfolio-item.product .actions .button { border-color: " . $params['button_cart_border_color_normal'] . " }";
		}

		if (isset($params['button_cart_border_color_hover']) && $params['button_cart_border_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_simple:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_simple:hover," .
			          $wrapper . " .portfolio-item.product .actions .button:hover { border-color: " . $params['button_cart_border_color_hover'] . " }";
		}

		if (isset($params['button_options_color_normal']) && $params['button_options_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable { color: " . $params['button_options_color_normal'] . " }";
		}

		if (isset($params['button_options_color_hover']) && $params['button_options_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable:hover { color: " . $params['button_options_color_hover'] . " }";
		}

		if (isset($params['button_options_background_color_normal']) && $params['button_options_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable { background-color: " . $params['button_options_background_color_normal'] . " }";
		}

		if (isset($params['button_options_background_color_hover']) && $params['button_options_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable:hover { background-color: " . $params['button_options_background_color_hover'] . " }";
		}

		if (isset($params['button_options_border_color_normal']) && $params['button_options_border_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable { border-color: " . $params['button_options_border_color_normal'] . " }";
		}

		if (isset($params['button_options_border_color_hover']) && $params['button_options_border_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-item .wrap > .caption .product-bottom .cart.type_button .button.product_type_variable:hover," .
			          $wrapper . " .portfolio-item .image .overlay .links .caption .cart .button.product_type_variable:hover { border-color: " . $params['button_options_border_color_hover'] . " }";
		}

		if (isset($params['pagination_spacing']) && $params['pagination_spacing'] != '') {
			$style .= $wrapper . " .portfolio-row + .gem-pagination { margin-top: " . $params['pagination_spacing'] . "px }";
		}

		if (isset($params['pagination_numbers_border_width']) && $params['pagination_numbers_border_width'] != '') {
			$style .= $wrapper . " .gem-pagination a { border-width: " . $params['pagination_numbers_border_width'] . "px }";
		}

		if (isset($params['pagination_numbers_border_radius']) && $params['pagination_numbers_border_radius'] != '') {
			$style .= $wrapper . " .gem-pagination a { border-radius: " . $params['pagination_numbers_border_radius'] . "px }";
		}

		if (isset($params['pagination_numbers_background_color_normal']) && $params['pagination_numbers_background_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination a { background-color: " . $params['pagination_numbers_background_color_normal'] . " }";
		}

		if (isset($params['pagination_numbers_background_color_hover']) && $params['pagination_numbers_background_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination a:hover { background-color: " . $params['pagination_numbers_background_color_hover'] . " }";
		}

		if (isset($params['pagination_numbers_background_color_active']) && $params['pagination_numbers_background_color_active'] != '') {
			$style .= $wrapper . " .gem-pagination a.current { background-color: " . $params['pagination_numbers_background_color_active'] . " }";
		}

		if (isset($params['pagination_numbers_text_color_normal']) && $params['pagination_numbers_text_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination a { color: " . $params['pagination_numbers_text_color_normal'] . " }";
		}

		if (isset($params['pagination_numbers_text_color_hover']) && $params['pagination_numbers_text_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination a:hover { color: " . $params['pagination_numbers_text_color_hover'] . " }";
		}

		if (isset($params['pagination_numbers_text_color_active']) && $params['pagination_numbers_text_color_active'] != '') {
			$style .= $wrapper . " .gem-pagination a.current { color: " . $params['pagination_numbers_text_color_active'] . " }";
		}

		if (isset($params['pagination_numbers_border_color_normal']) && $params['pagination_numbers_border_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination a { border-color: " . $params['pagination_numbers_border_color_normal'] . " }";
		}

		if (isset($params['pagination_numbers_border_color_hover']) && $params['pagination_numbers_border_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination a:hover { border-color: " . $params['pagination_numbers_border_color_hover'] . " }";
		}

		if (isset($params['pagination_numbers_border_color_active']) && $params['pagination_numbers_border_color_active'] != '') {
			$style .= $wrapper . " .gem-pagination a.current { border-color: " . $params['pagination_numbers_border_color_active'] . " }";
		}

		if (isset($params['pagination_arrows_background_color_normal']) && $params['pagination_arrows_background_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination .prev, " .
			          $wrapper . " .gem-pagination .next { background-color: " . $params['pagination_arrows_background_color_normal'] . " }";
		}

		if (isset($params['pagination_arrows_background_color_hover']) && $params['pagination_arrows_background_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination .prev:hover, " .
			          $wrapper . " .gem-pagination .next:hover { background-color: " . $params['pagination_arrows_background_color_hover'] . " }";
		}

		if (isset($params['pagination_arrows_border_color_normal']) && $params['pagination_arrows_border_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination .prev, " .
			          $wrapper . " .gem-pagination .next { border-color: " . $params['pagination_arrows_border_color_normal'] . " }";
		}

		if (isset($params['pagination_arrows_border_color_hover']) && $params['pagination_arrows_border_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination .prev:hover, " .
			          $wrapper . " .gem-pagination .next:hover { border-color: " . $params['pagination_arrows_border_color_hover'] . " }";
		}

		if (isset($params['pagination_arrows_icon_color_normal']) && $params['pagination_arrows_icon_color_normal'] != '') {
			$style .= $wrapper . " .gem-pagination .prev, " .
			          $wrapper . " .gem-pagination .next { color: " . $params['pagination_arrows_icon_color_normal'] . " }";
		}

		if (isset($params['pagination_arrows_icon_color_hover']) && $params['pagination_arrows_icon_color_hover'] != '') {
			$style .= $wrapper . " .gem-pagination .prev:hover, " .
			          $wrapper . " .gem-pagination .next:hover { color: " . $params['pagination_arrows_icon_color_hover'] . " }";
		}

		if (isset($params['pagination_more_spacing']) && $params['pagination_more_spacing'] != '') {
			$style .= $wrapper . " .portfolio-load-more { margin-top: " . $params['pagination_more_spacing'] . "px }";
		}

		if (isset($params['pagination_more_button_no_uppercase']) && $params['pagination_more_button_no_uppercase'] === '1') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { text-transform: initial; }";
		}

		if (isset($params['pagination_more_button_border_radius']) && $params['pagination_more_button_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { border-radius: " . $params['pagination_more_button_border_radius'] . "px }";
		}

		if (isset($params['pagination_more_button_border_width']) && $params['pagination_more_button_border_width'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { border-width: " . $params['pagination_more_button_border_width'] . "px }";
		}

		if (isset($params['pagination_more_button_text_color_normal']) && $params['pagination_more_button_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { color: " . $params['pagination_more_button_text_color_normal'] . " }";
		}

		if (isset($params['pagination_more_button_text_color_hover']) && $params['pagination_more_button_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button:hover { color: " . $params['pagination_more_button_text_color_hover'] . " }";
		}

		if (isset($params['pagination_more_button_bg_color_normal']) && $params['pagination_more_button_bg_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { background-color: " . $params['pagination_more_button_bg_color_normal'] . " }";
		}

		if (isset($params['pagination_more_button_bg_color_hover']) && $params['pagination_more_button_bg_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button:hover { background-color: " . $params['pagination_more_button_bg_color_hover'] . " }";
		}

		if (isset($params['pagination_more_button_border_color_normal']) && $params['pagination_more_button_border_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button { border-color: " . $params['pagination_more_button_border_color_normal'] . " }";
		}

		if (isset($params['pagination_more_button_border_color_hover']) && $params['pagination_more_button_border_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-load-more .gem-button:hover { border-color: " . $params['pagination_more_button_border_color_hover'] . " }";
		}

		if (isset($params['sorting_text_color']) && $params['sorting_text_color'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { color: " . $params['sorting_text_color'] . " }";
		}

		if (isset($params['sorting_border_width']) && $params['sorting_border_width'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select div.portfolio-sorting-select-current { border-width: " . $params['sorting_border_width'] . "px }";
		}

		if (isset($params['sorting_bottom_spacing']) && $params['sorting_bottom_spacing'] != '') {
			$style .= $wrapper . " .portfolio-top-panel { margin-bottom: " . $params['sorting_bottom_spacing'] . "px }";
		}

		if (isset($params['sorting_dropdown_text_color_normal']) && $params['sorting_dropdown_text_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li { color: " . $params['sorting_dropdown_text_color_normal'] . " }";
		}

		if (isset($params['sorting_dropdown_text_color_hover']) && $params['sorting_dropdown_text_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li:hover { color: " . $params['sorting_dropdown_text_color_hover'] . " }";
		}

		if (isset($params['sorting_dropdown_text_color_active']) && $params['sorting_dropdown_text_color_active'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul li.portfolio-sorting-select-current { color: " . $params['sorting_dropdown_text_color_active'] . " }";
		}

		if (isset($params['sorting_dropdown_background_color']) && $params['sorting_dropdown_background_color'] != '') {
			$style .= $wrapper . " .portfolio-sorting-select ul { background-color: " . $params['sorting_dropdown_background_color'] . " }";
		}

		if (isset($params['new_label_background']) && $params['new_label_background'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.new-label { background-color: " . $params['new_label_background'] . " }";
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.new-label:after { border-left-color: " . $params['new_label_background'] . "; border-right-color: " . $params['new_label_background'] . " }";
		}

		if (isset($params['new_label_text_color']) && $params['new_label_text_color'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.new-label { color: " . $params['new_label_text_color'] . " }";
		}

		if (isset($params['sale_label_background']) && $params['sale_label_background'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.onsale { background-color: " . $params['sale_label_background'] . " }";
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.onsale:after { border-left-color: " . $params['sale_label_background'] . "; border-right-color: " . $params['sale_label_background'] . " }";
		}

		if (isset($params['sale_label_text_color']) && $params['sale_label_text_color'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.onsale { color: " . $params['sale_label_text_color'] . " }";
		}

		if (isset($params['out_label_background']) && $params['out_label_background'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.out-of-stock-label { background-color: " . $params['out_label_background'] . " }";
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.out-of-stock-label:after { border-left-color: " . $params['out_label_background'] . "; border-right-color: " . $params['out_label_background'] . " }";
		}

		if (isset($params['out_label_text_color']) && $params['out_label_text_color'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels .label.out-of-stock-label { color: " . $params['out_label_text_color'] . " }";
		}

		if (isset($params['labels_margin_top']) && $params['labels_margin_top'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels { margin-top: " . $params['labels_margin_top'] . "px }";
		}

		if (isset($params['labels_margin_bottom']) && $params['labels_margin_bottom'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels { margin-bottom: " . $params['labels_margin_bottom'] . "px }";
		}

		if (isset($params['labels_margin_left']) && $params['labels_margin_left'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels { margin-left: " . $params['labels_margin_left'] . "px }";
		}

		if (isset($params['labels_margin_right']) && $params['labels_margin_right'] != '') {
			$style .= $wrapper . " .portfolio-item.product .product-labels { margin-right: " . $params['labels_margin_right'] . "px }";
		}

		if (isset($params['filter_buttons_standard_color']) && $params['filter_buttons_standard_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item .name { color: " . $params['filter_buttons_standard_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_border_width']) && $params['filter_buttons_standard_border_width'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard .portfolio-filter-item .name { border-width: " . $params['filter_buttons_standard_border_width'] . "px }";
		}

		if (isset($params['filter_buttons_standard_bottom_spacing']) && $params['filter_buttons_standard_bottom_spacing'] != '') {
			$style .= $wrapper . " .portfolio-top-panel { margin-bottom: " . $params['filter_buttons_standard_bottom_spacing'] . "px }";
		}

		if (isset($params['filter_buttons_standard_dropdown_background_color']) && $params['filter_buttons_standard_dropdown_background_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-standard:not(.single-filter) .portfolio-filter-item .portfolio-filter-item-list { background-color: " . $params['filter_buttons_standard_dropdown_background_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_normal']) && $params['filter_buttons_standard_dropdown_price_range_background_color_normal'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_normal'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_hover']) && $params['filter_buttons_standard_dropdown_price_range_background_color_hover'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount:hover { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_hover'] . " }";
		}

		if (isset($params['filter_buttons_standard_dropdown_price_range_background_color_active']) && $params['filter_buttons_standard_dropdown_price_range_background_color_active'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-filter-item .portfolio-filter-item-list .price-range-slider .slider-amount.active { background-color: " . $params['filter_buttons_standard_dropdown_price_range_background_color_active'] . " }";
		}

		if (isset($params['filter_buttons_sidebar_color']) && $params['filter_buttons_sidebar_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-show-filters-button { color: " . $params['filter_buttons_sidebar_color'] . " }";
		}

		if (isset($params['filter_buttons_sidebar_border_width']) && $params['filter_buttons_sidebar_border_width'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-show-filters-button { border-width: " . $params['filter_buttons_sidebar_border_width'] . "px}";
		}

		if (isset($params['filter_buttons_standard_background']) && $params['filter_buttons_standard_background'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filters-outer .portfolio-filters-area { background-color: " . $params['filter_buttons_standard_background'] . " }";
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filters-outer .portfolio-filters-area," .
			          $wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filters-outer .portfolio-filters-area { background-color: " . $params['filter_buttons_standard_background'] . " }}";
		}

		if (isset($params['filter_buttons_standard_overlay_color']) && $params['filter_buttons_standard_overlay_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list.style-hidden .portfolio-filters-outer { background-color: " . $params['filter_buttons_standard_overlay_color'] . " }";
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filters-list.style-standard .portfolio-filters-outer," .
			          $wrapper . " .portfolio-filters-list.style-sidebar .portfolio-filters-outer { background-color: " . $params['filter_buttons_standard_overlay_color'] . " }}";
		}

		if (isset($params['filter_buttons_standard_close_icon_color']) && $params['filter_buttons_standard_close_icon_color'] != '') {
			$style .= $wrapper . " .portfolio-filters-list .portfolio-close-filters { color: " . $params['filter_buttons_standard_close_icon_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_icon_color']) && $params['filter_buttons_standard_search_icon_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-button," .
			          $wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter .portfolio-search-filter-button { color: " . $params['filter_buttons_standard_search_icon_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_input_border_radius']) && $params['filter_buttons_standard_search_input_border_radius'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
			          $wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { border-radius: " . $params['filter_buttons_standard_search_input_border_radius'] . "px }";
		}

		if (isset($params['filter_buttons_standard_search_input_color']) && $params['filter_buttons_standard_search_input_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
			          $wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { color: " . $params['filter_buttons_standard_search_input_color'] . " }";
		}

		if (isset($params['filter_buttons_standard_search_input_background_color']) && $params['filter_buttons_standard_search_input_background_color'] != '') {
			$style .= $wrapper . " .portfolio-top-panel .portfolio-top-panel-right .portfolio-search-filter .portfolio-search-filter-form input," .
			          $wrapper . " .portfolio-filters-list .portfolio-filters-area .portfolio-search-filter input { background-color: " . $params['filter_buttons_standard_search_input_background_color'] . " }";
		}

		if (isset($params['quick_view_text_color']) && $params['quick_view_text_color'] != '') {
			$style .= $wrapper . " .portfolio-item .quick-view-button { color: " . $params['quick_view_text_color'] . " }";
		}

		if (isset($params['quick_view_background_color']) && $params['quick_view_background_color'] != '') {
			$style .= $wrapper . " .portfolio-item .quick-view-button { background-color: " . $params['quick_view_background_color'] . " }";
		}

		if (isset($params['notification_background_color']) && $params['notification_background_color'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message { background-color: " . $params['notification_background_color'] . " }";
		}

		if (isset($params['notification_text_color']) && $params['notification_text_color'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message { color: " . $params['notification_text_color'] . " }";
		}

		if (isset($params['notification_icon_color']) && $params['notification_icon_color'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message:before { color: " . $params['notification_icon_color'] . " }";
		}

		if (isset($params['button_wishlist_color_normal']) && $params['button_wishlist_color_normal'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button { color: " . $params['button_wishlist_color_normal'] . " }";
		}

		if (isset($params['button_wishlist_color_hover']) && $params['button_wishlist_color_hover'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button:hover { color: " . $params['button_wishlist_color_hover'] . " }";
		}

		if (isset($params['button_wishlist_background_color_normal']) && $params['button_wishlist_background_color_normal'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button { background-color: " . $params['button_wishlist_background_color_normal'] . " }";
		}

		if (isset($params['button_wishlist_background_color_hover']) && $params['button_wishlist_background_color_hover'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button:hover { background-color: " . $params['button_wishlist_background_color_hover'] . " }";
		}

		if (isset($params['button_wishlist_border_color_normal']) && $params['button_wishlist_border_color_normal'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button { border-color: " . $params['button_wishlist_border_color_normal'] . " }";
		}

		if (isset($params['button_wishlist_border_color_hover']) && $params['button_wishlist_border_color_hover'] != '') {
			$style .= $wrapper_notification . " .thegem-popup-notification .notification-message a.button:hover { border-color: " . $params['button_wishlist_border_color_hover'] . " }";
		}

		if (isset($params['image_size']) && $params['image_size'] == 'full' && !empty($params['image_ratio_full'])) {
			$style .= $wrapper . " .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: " . $params['image_ratio_full'] . " !important; height: auto; }";
		}

		if (isset($params['image_size']) && $params['image_size'] == 'default' && $params['image_aspect_ratio'] == 'custom' && !empty($params['image_ratio_custom'])) {
			$style .= $wrapper . " .portfolio-item:not(.custom-ratio, .double-item) .image-inner:not(.empty) { aspect-ratio: " . $params['image_ratio_custom'] . " !important; height: auto; }";
		}

		if (isset($params['image_column_width']) && $params['image_column_width'] != '') {
			$style .= $wrapper . ".list-style .portfolio-set .portfolio-item .wrap > .image { width: " . $params['image_column_width'] . "% }";
		}

		if (isset($params['image_column_width_tablet']) && $params['image_column_width_tablet'] != '') {
			$style .= "@media (max-width: 991px) { " . $wrapper . ".list-style .portfolio-set .portfolio-item .wrap > .image { width: " . $params['image_column_width_tablet'] . "% }}";
		}

		if (isset($params['image_column_width_mobile']) && $params['image_column_width_mobile'] != '') {
			$style .= "@media (max-width: 767px) { " . $wrapper . ".list-style .portfolio-set .portfolio-item .wrap > .image { width: " . $params['image_column_width_mobile'] . "% }}";
		}

		if (isset($params['items_list_max_height']) && $params['items_list_max_height'] !== '') {
			$style .= $wrapper . " .portfolio-filter-item-list { max-height: " . $params['items_list_max_height'] . "px; padding-right: 10px; }";
		}

		if (isset($params['items_list_max_height_tablet']) && $params['items_list_max_height_tablet'] !== '') {
			$style .= "@media (max-width: 991px) { " . $wrapper . " .portfolio-filter-item-list { max-height: " . $params['items_list_max_height_tablet'] . "px; padding-right: 10px; } }";
		}

		if (isset($params['items_list_max_height_mobile']) && $params['items_list_max_height_mobile'] !== '') {
			$style .= "@media (max-width: 767px) { " . $wrapper . " .portfolio-filter-item-list { max-height: " . $params['items_list_max_height_mobile'] . "px; padding-right: 10px; } }";
		}

		if ($widget_styles) {
			$style .= "</style>";
		}

		return $style;

	}
}

if (!function_exists('thegem_extended_products_get_preset_settings')) {
	function thegem_extended_products_get_preset_settings() {
		$presets = array(
			'below-default-cart-button' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'cart_button_show_icon' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '1',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-default-cart-icon' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '1',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-cart-disabled' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '',
				'product_show_categories' => '1',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '22',
				'image_gaps_tablet' => '22',
				'image_gaps_mobile' => '22',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'gray',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-border-cart-icon' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '1',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '12',
				'image_gaps_tablet' => '12',
				'image_gaps_mobile' => '12',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '1',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '1',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'white',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-shadow-hover-01' => array(
				'caption_position' => 'page',
				'social_sharing' => '1',
				'product_show_add_to_cart' => '',
				'product_show_categories' => '1',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-shadow-hover-02' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'cart_button_show_icon' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '30',
				'image_gaps_tablet' => '30',
				'image_gaps_mobile' => '30',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '1',
				'image_border_color' => '',
				'image_border_color_hover' => '#02010100',
				'image_border_radius' => '12',
				'border_caption_container' => '1',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '#99A9B5',
				'icons_background_color_normal' => '#02010100',
				'icons_border_color_normal' => '#99A9B5',
				'icons_color_hover' => '#FFFFFF',
				'icons_background_color_hover' => '#00BCD4',
				'icons_border_color_hover' => '#00BCD4',
				'icons_border_width' => '1',
				'button_cart_color_normal' => '#99A9B5',
				'button_cart_color_hover' => '#FFFFFF',
				'button_cart_background_color_normal' => '#02010100',
				'button_cart_background_color_hover' => '#00BCD4',
				'button_cart_border_color_normal' => '#99A9B5',
				'button_cart_border_color_hover' => '#00BCD4',
				'button_options_color_normal' => '#99A9B5',
				'button_options_color_hover' => '#FFFFFF',
				'button_options_background_color_normal' => '#02010100',
				'button_options_background_color_hover' => '#00BCD4',
				'button_options_border_color_normal' => '#99A9B5',
				'button_options_border_color_hover' => '#00BCD4',
			),
			'below-rounded-images' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '',
				'product_show_categories' => '1',
				'product_show_reviews' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '24',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-rectangle-button-01' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'cart_button_show_icon' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '32',
				'image_gaps_tablet' => '32',
				'image_gaps_mobile' => '32',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => 'left',
				'caption_background_hover' => '',
				'buttons_border_radius' => '0',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-rectangle-button-02' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'cart_button_show_icon' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '16',
				'image_gaps_tablet' => '16',
				'image_gaps_mobile' => '16',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => 'left',
				'caption_background_hover' => '',
				'buttons_border_radius' => '3',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '#DFE5E8',
				'button_options_background_color_hover' => '#00BCD4',
				'button_options_border_color_normal' => '#02010100',
				'button_options_border_color_hover' => '#02010100',
			),
			'below-separator-01' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '4',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '36',
				'image_gaps_tablet' => '36',
				'image_gaps_mobile' => '36',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '1',
				'product_separator_width' => '2',
				'product_separator_color' => thegem_get_option('styled_elements_background_color'),
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '#5F727F',
				'price_color_normal' => '#5F727F',
				'caption_container_preset' => 'gray',
				'caption_container_alignment' => '',
				'caption_background_hover' => '#FFFFFF',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'below-separator-02' => array(
				'caption_position' => 'page',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '1',
				'labels_design' => '6',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '0',
				'image_gaps_tablet' => '0',
				'image_gaps_mobile' => '0',
				'image_hover_effect_page' => 'fade',
				'product_separator' => '1',
				'product_separator_width' => '1',
				'product_separator_color' => '#212227',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'border_caption_container' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset' => 'transparent',
				'caption_container_alignment' => '',
				'caption_background_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '#02010100',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '#00BCD4',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-default-cart-button' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '',
				'cart_button_show_icon' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-default-cart-icon' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-solid-background' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '1',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'solid',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-rounded-corners' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '24',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-shadow-hover-01' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '22',
				'image_gaps_tablet' => '22',
				'image_gaps_mobile' => '22',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-shadow' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '',
				'cart_button_show_icon' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '2',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '10',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => 'right',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-separator-01' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '0',
				'image_gaps_tablet' => '0',
				'image_gaps_mobile' => '0',
				'product_separator' => '1',
				'product_separator_width' => '4',
				'product_separator_color' => '#DFE5E8',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'image-separator-02' => array(
				'caption_position' => 'image',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '',
				'product_show_categories' => '',
				'product_show_reviews' => '1',
				'labels_design' => '5',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '0',
				'image_gaps_tablet' => '0',
				'image_gaps_mobile' => '0',
				'product_separator' => '1',
				'product_separator_width' => '1',
				'product_separator_color' => '#212227',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'solid',
				'image_hover_effect_image' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '#212227',
				'icons_background_color_normal' => '#02010100',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '#212227',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-default' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '4',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'fade',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-rounded-corners' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '1',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '42',
				'image_gaps_tablet' => '42',
				'image_gaps_mobile' => '42',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '24',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'zooming-blur',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-solid-background' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '',
				'labels_design' => '2',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '32',
				'image_gaps_tablet' => '32',
				'image_gaps_mobile' => '32',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'solid',
				'image_hover_effect_hover' => 'fade',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-separator' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '1',
				'labels_design' => '3',
				'sale_label_type' => 'text',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '0',
				'image_gaps_tablet' => '0',
				'image_gaps_mobile' => '0',
				'product_separator' => '1',
				'product_separator_width' => '1',
				'product_separator_color' => '#dfe5e8',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-centered-caption' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'buttons',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '',
				'product_show_reviews' => '1',
				'labels_design' => '1',
				'product_show_new' => '',
				'product_show_sale' => '',
				'product_show_out' => '',
				'image_gaps' => '6',
				'image_gaps_tablet' => '6',
				'image_gaps_mobile' => '6',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'fade',
				'caption_container_alignment_hover' => 'center',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-shadow-hover' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '1',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '26',
				'image_gaps_tablet' => '26',
				'image_gaps_mobile' => '26',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'zooming-blur',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			),
			'hover-gradient-hover' => array(
				'caption_position' => 'hover',
				'social_sharing' => '',
				'product_show_add_to_cart' => '1',
				'add_to_cart_type' => 'icon',
				'product_show_add_to_cart_mobiles' => '1',
				'product_show_categories' => '1',
				'product_show_reviews' => '1',
				'labels_design' => '1',
				'sale_label_type' => 'percentage',
				'product_show_new' => '1',
				'product_show_sale' => '1',
				'product_show_out' => '1',
				'image_gaps' => '16',
				'image_gaps_tablet' => '16',
				'image_gaps_mobile' => '16',
				'product_separator' => '',
				'image_border_width' => '',
				'image_border_color' => '',
				'image_border_radius' => '',
				'title_color_normal' => '',
				'price_color_normal' => '',
				'caption_container_preset_hover' => 'light',
				'image_hover_effect_hover' => 'gradient',
				'caption_container_alignment_hover' => '',
				'buttons_border_radius' => '',
				'icons_color_normal' => '',
				'icons_background_color_normal' => '',
				'icons_border_color_normal' => '',
				'icons_color_hover' => '',
				'icons_background_color_hover' => '',
				'icons_border_color_hover' => '',
				'icons_border_width' => '',
				'button_cart_color_normal' => '',
				'button_cart_color_hover' => '',
				'button_cart_background_color_normal' => '',
				'button_cart_background_color_hover' => '',
				'button_cart_border_color_normal' => '',
				'button_cart_border_color_hover' => '',
				'button_options_color_normal' => '',
				'button_options_color_hover' => '',
				'button_options_background_color_normal' => '',
				'button_options_background_color_hover' => '',
				'button_options_border_color_normal' => '',
				'button_options_border_color_hover' => '',
			)
		);
		return $presets;
	}
}

if (!function_exists('thegem_extended_products_get_preset_settings_callback')) {
	function thegem_extended_products_get_preset_settings_callback() {
		$presets = thegem_extended_products_get_preset_settings();
		$response = array('status' => 'success');
		$response['presets'] = $presets;
		$response = json_encode($response);
		header("Content-Type: application/json");
		echo $response;
		exit;
	}
}
add_action('wp_ajax_extended_products_get_preset_settings', 'thegem_extended_products_get_preset_settings_callback');
add_action('wp_ajax_nopriv_extended_products_get_preset_settings', 'thegem_extended_products_get_preset_settings_callback');

if(!function_exists('thegem_extended_products_get_woo_attributes')) {
	function thegem_extended_products_get_woo_attributes($select = 'attributes', $checkbox = false) {
		if ($checkbox) {
			$attributes = array();
		} else {
			$attributes = array(__('Select attribute', 'thegem') => '');
		}

		if ( class_exists( 'Woocommerce' ) ) {
			$attributes_taxonomy = wc_get_attribute_taxonomies();
			foreach ( $attributes_taxonomy as $attribute ) {
				$attributes[$attribute->attribute_label] = $attribute->attribute_name;
			}
		}

		if($select == 'attributes') {
			return $attributes;
		} elseif($select == 'attribute_val') {
			$terms = $attribute_vall = array();
			foreach ($attributes as $name => $attribute) {
				if(!empty($attribute)) {
					$terms[$name] = get_terms( 'pa_' . $attribute );
				}
			}
			foreach ($terms as $key => $val) {
				foreach ($val as $value) {
					$attribute_vall[$value->name .' in '. $key] = $value->slug;
				}
			}

			return $attribute_vall;
		}
	}
}

/* CART */

function thegem_woocommerce_widget_shopping_cart_button_view_cart() {
	thegem_button(array(
		'tag' => 'a',
		'href' => esc_url(wc_get_cart_url()),
		'text' => esc_html__( 'View cart', 'woocommerce' ),
		'style' => 'flat',
		'size' => 'tiny',
		'text_color' => thegem_get_option('button_text_basic_color'),
		'background_color' => thegem_get_option('styled_elements_color_1'),
		'hover_text_color' => thegem_get_option('styled_elements_color_1'),
		'hover_background_color' => 'transparent',
		'hover_border_color' => thegem_get_option('styled_elements_color_1'),
		'extra_class' => 'mini-cart-view-cart',
	), true);
}

function thegem_woocommerce_widget_shopping_cart_proceed_to_checkout() {
	thegem_button(array(
		'tag' => 'a',
		'href' => esc_url( wc_get_checkout_url() ),
		'text' => esc_html__( 'Checkout', 'woocommerce' ),
		'style' => 'outline',
		'size' => 'tiny',
		'text_color' => thegem_get_option('styled_elements_color_4'),
		'border_color' => thegem_get_option('styled_elements_color_4'),
		'hover_text_color' => thegem_get_option('button_text_basic_color'),
		'hover_background_color' => thegem_get_option('styled_elements_color_4'),
		'hover_border_color' => thegem_get_option('styled_elements_color_4'),
		'extra_class' => 'mini-cart-checkout',
	), true);
}

function thegem_space_after_cart_item_name($cart_item, $cart_item_key) {
	echo '<span class="product-name-space"> </span>';
}

function thegem_woocommerce_before_cart_wrap_start() {
	echo '<div class="woocommerce-before-cart clearfix">';
}

function thegem_woocommerce_before_cart_wrap_end() {
	echo '</div>';
}

function thegem_woocommerce_cart_scripts() {
	wp_enqueue_script('thegem-woocommerce');
}

function thegem_woocommerce_cart_collaterals_start() {
?>
<div class="row">
	<div class="col-md-6 col-sm-12"><?php woocommerce_shipping_calculator(); ?></div>
	<div class="col-md-6 col-sm-12">
<?php
}
function thegem_woocommerce_cart_collaterals_end() {
?>
	</div>
</div>
<?php
}

function thegem_woocommerce_cross_sells_columns($columns) {
	return 4;
}

function thegem_add_cart_body_class($classes) {
	if(in_array('woocommerce-cart', $classes)) {
		$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
		$classes[] = 'woocommerce-cart-layout-'.$thegem_cart_layout;
	}
	if(function_exists('thegem_get_template_type') && thegem_get_template_type(get_the_id()) == 'cart') {
		$classes[] = 'woocommerce-cart-layout-modern';
	}
	return $classes;
}

function thegem_cart_checkout_steps() {
	$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
	$thegem_cart_steps = thegem_get_option('modern_cart_steps', 1);
	$thegem_cart_steps_position = thegem_get_option('modern_cart_steps_position', 'content_area');
	if($thegem_cart_layout == 'modern' && $thegem_cart_steps && $thegem_cart_steps_position == 'content_area') {
?>
<div class="woocommerce-cart-checkout-steps woocommerce-cart-checkout-steps-content">
	<div class="step step-cart title-h6<?php echo (is_cart() ? ' active' : ' light'); ?>"><?php esc_html_e('1. Shopping cart', 'thegem'); ?></div>
	<div class="step step-checkout title-h6<?php echo (is_checkout() && !is_wc_endpoint_url('order-received') ? ' active' : ' light'); ?>"><?php esc_html_e('2. Checkout', 'thegem'); ?></div>
	<div class="step step-order-complete title-h6<?php echo (is_checkout() && is_wc_endpoint_url('order-received') ? ' active' : ' light'); ?>"><?php esc_html_e('3. Order complete', 'thegem'); ?></div>
</div>
<?php
	}
}

function thegem_cart_checkout_title_steps($title) {
	if(defined( 'WC_PLUGIN_FILE' )) {
		if(is_cart() || is_checkout()) {
			$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
			$thegem_cart_steps = thegem_get_option('modern_cart_steps', 1);
			$thegem_cart_steps_position = thegem_get_option('modern_cart_steps_position', 'content_area');
			$admin_page_data = array();
			$page_id = is_cart()? wc_get_page_id('cart') : wc_get_page_id('checkout');
			$admin_page_data = thegem_get_sanitize_admin_page_data($page_id);
			if($admin_page_data['title_show'] != 'default') {
				return $title;
			}
			if($thegem_cart_layout == 'modern' && $thegem_cart_steps && $thegem_cart_steps_position == 'title_area') {
				ob_start();
?>
<div id="page-title" class="page-title-block page-title-alignment-center page-title-style-1 woocommerce-cart-checkout">
	<div class="container"><div class="page-title-inner"><div class="woocommerce-cart-checkout-steps woocommerce-cart-checkout-steps-title">
		<div class="step step-cart title-h2<?php echo (is_cart() ? ' active' : ' light'); ?>"><?php esc_html_e('Shopping cart', 'thegem'); ?></div>
		<div class="step step-checkout title-h2<?php echo (is_checkout() && !is_wc_endpoint_url('order-received') ? ' active' : ' light'); ?>"><?php esc_html_e('Checkout', 'thegem'); ?></div>
		<div class="step step-order-complete title-h2<?php echo (is_checkout() && is_wc_endpoint_url('order-received') ? ' active' : ' light'); ?>"><?php esc_html_e('Order complete', 'thegem'); ?></div>
	</div></div></div>
</div>
<?php
				$title = ob_get_clean();
			}
		}
	}
	return $title;
}

function woocommerce_cart_totals_wrap_start() {
	echo '<div class="cart_totals-inner default-background">';
}

function woocommerce_cart_totals_wrap_end() {
	echo '</div>';
}

function thegem_woocommerce_wrap_cart_item_name($product_name, $cart_item, $cart_item_key) {
	return '<span class="product-title">'.$product_name.'</span>';
}

function thegem_wc_empty_cart_message($text) {
	if(thegem_get_option('cart_layout', 'modern') == 'modern' && !empty(thegem_get_option('cart_empty_title'))) {
		$text = thegem_get_option('cart_empty_title');
	}
	return $text;
}

remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'thegem_woocommerce_widget_shopping_cart_button_view_cart', 10 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'thegem_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
add_action( 'woocommerce_after_cart_item_name', 'thegem_space_after_cart_item_name', 5, 2 );
add_action( 'woocommerce_before_cart', 'thegem_woocommerce_before_cart_wrap_start', 1 );
add_action( 'woocommerce_before_cart', 'thegem_woocommerce_before_cart_wrap_end', 1000 );
add_action( 'woocommerce_before_cart', 'thegem_woocommerce_cart_scripts');
/*add_action( 'woocommerce_cart_collaterals', 'thegem_woocommerce_cart_collaterals_start', 1);
add_action( 'woocommerce_cart_collaterals', 'thegem_woocommerce_cart_collaterals_end', 1000);*/
add_filter('woocommerce_cross_sells_columns', 'thegem_woocommerce_cross_sells_columns');
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 11 );
add_filter('body_class', 'thegem_add_cart_body_class');
add_action('woocommerce_before_cart', 'thegem_cart_checkout_steps');
if(thegem_get_option('cart_layout', 'modern') == 'modern') {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals' );
	add_action( 'woocommerce_before_cart_collaterals', 'woocommerce_cart_totals', 1 );
	add_action( 'woocommerce_before_cart_totals', 'woocommerce_cart_totals_wrap_start', 1 );
	add_action( 'woocommerce_after_cart_totals', 'woocommerce_cart_totals_wrap_end', 100 );
	remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
}
add_filter('woocommerce_cart_item_name', 'thegem_woocommerce_wrap_cart_item_name', 10, 3);
add_filter('thegem_page_title', 'thegem_cart_checkout_title_steps');
add_filter('wc_empty_cart_message', 'thegem_wc_empty_cart_message');


/* CHECKOUT */

function thegem_checkout_get_type() {
	$cart_layout = thegem_get_option('cart_layout', 'modern');
	$checkout_type = thegem_get_option('checkout_type', 'multi-step');
	if($cart_layout == 'modern') {
		return 'one-page-modern';
	}
	return $checkout_type;
}

function thegem_woocommerce_checkout_scripts() {
	wp_enqueue_script('thegem-checkout');
	wp_enqueue_script('thegem-woocommerce');
}

function thegem_woocommerce_checkout_tabs() {
	$thegem_checkout_type = thegem_checkout_get_type();
?>
<?php if ($thegem_checkout_type == 'multi-step'): ?>
	<div class="checkout-steps <?php if(is_user_logged_in()): ?>user-logged<?php endif; ?> clearfix">
		<?php if(is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' )): ?>
			<div class="checkout-step active" data-tab-id="checkout-billing"><?php esc_html_e('1. Billing','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-payment"><?php esc_html_e('2. Payment','thegem'); ?></div>
			<div class="checkout-step disabled" data-tab-id="checkout-confirmation"><?php esc_html_e('3. Confirmation','thegem'); ?></div>
		<?php else: ?>
			<div class="checkout-step active" data-tab-id="checkout-signin"><?php esc_html_e('1. Sign in','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-billing"><?php esc_html_e('2. Billing','thegem'); ?></div>
			<div class="checkout-step" data-tab-id="checkout-payment"><?php esc_html_e('3. Payment','thegem'); ?></div>
			<div class="checkout-step disabled" data-tab-id="checkout-confirmation"><?php esc_html_e('4. Confirmation','thegem'); ?></div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($thegem_checkout_type == 'one-page'): ?>
	<div class="checkout-steps clearfix woocommerce-steps-<?php echo $thegem_checkout_type; ?>">
		<div class="checkout-step disabled before-active"><?php esc_html_e('Shopping cart','thegem'); ?></div>
		<div class="checkout-step disabled active"><?php esc_html_e('Checkout details','thegem'); ?></div>
		<div class="checkout-step disabled"><?php esc_html_e('Order complete','thegem'); ?></div>
	</div>
<?php endif; ?>
<?php
}

function thegem_woocommerce_checkout_nav_buttons() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type != 'multi-step') return ;
?>
<div class="checkout-navigation-buttons">
	<?php
		thegem_button(array(
			'tag' => 'button',
			'text' => esc_html__( 'Previous step', 'thegem' ),
			'style' => 'outline',
			'size' => 'medium',
			'extra_class' => 'checkout-prev-step',
			'attributes' => array(
				'value' => esc_attr__( 'Previous step', 'thegem' ),
				'type' => 'button',
				'class' => 'gem-button-tablet-size-small'
			)
		), true);
	?>
	<?php
		thegem_button(array(
			'tag' => 'button',
			'text' => esc_html__( 'Next step', 'thegem' ),
			'style' => 'outline',
			'size' => 'medium',
			'extra_class' => 'checkout-next-step',
			'attributes' => array(
				'value' => esc_attr__( 'Next step', 'thegem' ),
				'type' => 'button',
				'class' => 'gem-button-tablet-size-small'
			)
		), true);
	?>
</div>
<?php
}

function thegem_woocommerce_customer_details_start() {
	echo '<div class="checkout-contents" data-tab-content-id="checkout-billing">';
}

function thegem_woocommerce_customer_details_end() {
	echo '</div>';
}

function thegem_woocommerce_order_review_start() {
	echo '<div class="checkout-contents" data-tab-content-id="checkout-payment">';
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type == 'one-page-modern') {
		echo '<div class="order-review-inner default-background">';
	};
}

function thegem_woocommerce_order_review_end() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type == 'one-page-modern') {
		echo '</div>';
	};
	echo '</div>';
}

function thegem_woocommerce_checkout_form_steps_script() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type != 'multi-step') return ;
?>
<script>
(function($) {
	function active_checkout_tab($tab, isinit) {
		if ($tab.length == 0 || ($tab.hasClass('active') && !isinit)) {
			return false;
		}

		$tab.parent().find('.checkout-step').removeClass('active before-active');
		$tab.addClass('active');
		$tab.prev('.checkout-step').addClass('before-active');
		var tab_id = $tab.data('tab-id');
		$('.checkout-contents').removeClass('active');
		$('.checkout-contents[data-tab-content-id="' + tab_id + '"]').addClass('active');
		window.location.hash = '#' + tab_id;
	}

	var m = window.location.hash.match(/#checkout\-(.+)/);
	if (m && $('.checkout-steps .checkout-step[data-tab-id="checkout-' + m[1] + '"]').length == 1) {
		active_checkout_tab($('.checkout-steps .checkout-step[data-tab-id="checkout-' + m[1] + '"]'), true);
	} else {
		active_checkout_tab($('.checkout-steps .checkout-step:first'), true);
	}

	$('.checkout-steps .checkout-step').not('.disabled').click(function() {
		active_checkout_tab($(this), false);
	});
})(jQuery);
</script>
<?php
}

function thegem_woocommerce_checkout_registration_buttons() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type != 'multi-step') return ;
	echo '<div class="checkout-registration-buttons">';
	thegem_button(array(
		'tag' => 'button',
		'text' => esc_html__( 'Cancel', 'woocommerce' ),
		'style' => 'outline',
		'size' => 'medium',
		'extra_class' => 'checkout-cancel-create-account-button',
		'attributes' => array(
			'type' => 'button',
		)
	), true);
	thegem_button(array(
		'tag' => 'button',
		'text' => esc_html__( 'Register', 'woocommerce' ),
		'style' => 'outline',
		'size' => 'medium',
		'extra_class' => 'checkout-create-account-button',
		'attributes' => array(
			'type' => 'button',
		)
	), true);
	echo '</div>';
}

function thegem_woocommerce_order_review_table_start() {
	$thegem_checkout_type = thegem_checkout_get_type();
	echo '<div class="gem-table checkout-payment">';
	if ($thegem_checkout_type == 'one-page') {
		$pattern_id = 'pattern-'.time().'-'.rand(0, 100);
		echo '<div class="checkout-order-review-pattern"><svg width="100%" height="27" style="fill: '.thegem_get_option('styled_elements_background_color').';"><defs><pattern id="'.$pattern_id.'" x="10" y="0" width="20" height="28" patternUnits="userSpaceOnUse" ><path d="M20,8V0H0v8c3.314,0,6,2.687,6,6c0,3.313-2.686,6-6,6v8h20v-8c-3.313,0-6-2.687-6-6C14,10.687,16.687,8,20,8z" /></pattern></defs><rect x="0" y="0" width="100%" height="28" style="fill: url(#'.$pattern_id.');" /></svg></div>';
	}
}

function thegem_woocommerce_order_review_table_end() {
	echo '</div>';
}

function thegem_woocommerce_before_checkout_wrapper_start() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type == 'multi-step') return ;
	echo '<div class="checkout-before-checkout-form">';
}

function thegem_woocommerce_before_checkout_wrapper_end() {
	$thegem_checkout_type = thegem_checkout_get_type();
	if ($thegem_checkout_type == 'multi-step') return ;
	echo '</div>';
}

function thegem_add_checkout_body_class($classes) {
	if(in_array('woocommerce-checkout', $classes)) {
		$thegem_checkout_type = thegem_checkout_get_type();
		$classes[] = 'woocommerce-checkout-layout-'.$thegem_checkout_type;
	}
	if(in_array('woocommerce-order-received', $classes)) {
		$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
		$classes[] = 'woocommerce-cart-layout-'.$thegem_cart_layout;
	}
	if(in_array('woocommerce-view-order', $classes)) {
		$thegem_cart_layout = thegem_get_option('cart_layout', 'modern');
		$classes[] = 'woocommerce-cart-layout-'.$thegem_cart_layout;
	}
	return $classes;
}

function thegem_woocommerce_remove_checkout_template_notices() {
	if(is_checkout() && thegem_checkout_template() && defined('ELEMENTOR_VERSION')) {
		remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 4 );
	}
}

add_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_checkout_scripts', 1);
add_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_checkout_tabs', 5);
add_action('woocommerce_before_checkout_form', 'thegem_cart_checkout_steps', 5);
add_action('woocommerce_before_thankyou', 'thegem_cart_checkout_steps', 5);
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', thegem_checkout_get_type() == 'multi-step' ? 9 : 11 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 4 );
add_action( 'woocommerce_before_checkout_form_cart_notices', 'thegem_woocommerce_remove_checkout_template_notices', 1 );
add_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_before_checkout_wrapper_start', 6 );
add_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_before_checkout_wrapper_end', 100 );
add_action( 'woocommerce_checkout_after_customer_details', 'thegem_woocommerce_checkout_nav_buttons', 100);
add_action( 'woocommerce_checkout_before_customer_details', 'thegem_woocommerce_customer_details_start', 1);
add_action( 'woocommerce_checkout_after_customer_details', 'thegem_woocommerce_customer_details_end', 1000);
add_action( 'woocommerce_checkout_before_order_review_heading', 'thegem_woocommerce_order_review_start', 1);
add_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_end', 1000);
add_action( 'woocommerce_after_checkout_form', 'thegem_woocommerce_checkout_form_steps_script');
add_action( 'woocommerce_after_checkout_registration_form', 'thegem_woocommerce_checkout_registration_buttons', 100);
add_action( 'woocommerce_checkout_before_order_review', 'thegem_woocommerce_order_review_table_start', 1);
add_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_table_end', 1000);
add_filter('body_class', 'thegem_add_checkout_body_class');

if (!function_exists('thegem_woocommerce_short_grid_content')) {
	function thegem_woocommerce_short_grid_content($products = array(), $args = array()) {
		global $post;
		$portfolio_posttemp = $post;

		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_content', 4);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_start', 11);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 15);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_product_per_page_select', 30);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 40);
		remove_action('woocommerce_before_shop_loop', 'thegem_woocommerce_before_shop_loop_end', 45);
		do_action('woocommerce_before_shop_loop');

		if (thegem_get_option('product_archive_preset_type') == 'on_image') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_image');
			$caption_position = 'image';
			$preset = 'image-' . thegem_get_option('product_archive_preset_on_image');
		} else if (thegem_get_option('product_archive_preset_type') == 'below') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_page');
			$caption_position = 'page';
			$preset = 'below-' . thegem_get_option('product_archive_preset_below');
		} else {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_hover');
			$caption_position = 'hover';
			$preset = 'hover-' . thegem_get_option('product_archive_preset_on_hover');
		}

		$queried = get_queried_object();

		$params = array_merge( array(
			'portfolio_uid' => '',
			'layout' => thegem_get_option('product_archive_layout'),
			'image_gaps' => thegem_get_option('product_archive_size_desktop'),
			'image_gaps_tablet' => thegem_get_option('product_archive_size_tablet'),
			'image_gaps_mobile' => thegem_get_option('product_archive_size_mobile'),
			'caption_position' => $caption_position,
			'image_size' => thegem_get_option('product_archive_layout') == 'justified' ? thegem_get_option('product_archive_image_size') : '',
			'image_ratio_full' => thegem_get_option('product_archive_image_ratio_full'),
			'image_ratio_custom' => thegem_get_option('product_archive_image_ratio_custom'),
			'image_aspect_ratio' => thegem_get_option('product_archive_image_aspect_ratio'),
			'quick_view' => false,
			'quick_view_text' => thegem_get_option('product_archive_quick_view_text'),
			'orderby' => thegem_get_option('product_archive_orderby'),
			'order' => thegem_get_option('product_archive_order'),
			'product_show_categories' => thegem_get_option('product_archive_show_categories_desktop'),
			'product_show_categories_tablet' => thegem_get_option('product_archive_show_categories_tablet'),
			'product_show_categories_mobile' => thegem_get_option('product_archive_show_categories_mobile'),
			'product_show_title' => thegem_get_option('product_archive_show_title'),
			'product_show_price' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_price'),
			'product_show_reviews' => thegem_get_option('product_archive_show_reviews_desktop'),
			'product_show_reviews_tablet' => thegem_get_option('product_archive_show_reviews_tablet'),
			'product_show_reviews_mobile' => thegem_get_option('product_archive_show_reviews_mobile'),
			'product_show_reviews_count' => thegem_get_option('product_archive_show_reviews_count'),
			'product_show_add_to_cart' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_add_to_cart'),
			'product_show_add_to_cart_mobiles' => thegem_get_option('catalog_view') ? '' : thegem_get_option('product_archive_show_add_to_cart'),
			'add_to_cart_type' => thegem_get_option('product_archive_add_to_cart_type'),
			'cart_button_show_icon' => thegem_get_option('product_archive_cart_button_show_icon'),
			'cart_button_text' => thegem_get_option('product_archive_cart_button_text'),
			'cart_button_pack' => thegem_get_option('product_archive_cart_icon_pack'),
			'cart_icon_' . thegem_get_option('product_archive_cart_icon_pack') => thegem_get_option('product_archive_cart_icon'),
			'select_options_button_text' => thegem_get_option('product_archive_select_options_button_text'),
			'select_options_pack' => thegem_get_option('product_archive_select_options_icon_pack'),
			'select_options_icon_' . thegem_get_option('product_archive_select_options_icon_pack') => thegem_get_option('product_archive_select_options_icon'),
			'product_show_wishlist' => thegem_get_option('product_archive_show_wishlist'),
			'add_wishlist_icon_pack' => thegem_get_option('product_archive_add_wishlist_icon_pack'),
			'wishlist_icon_' . thegem_get_option('product_archive_add_wishlist_icon_pack') => thegem_get_option('product_archive_add_wishlist_icon'),
			'added_wishlist_icon_pack' => thegem_get_option('product_archive_added_wishlist_icon_pack'),
			'added_wishlist_icon_' . thegem_get_option('product_archive_added_wishlist_icon_pack') => thegem_get_option('product_archive_added_wishlist_icon'),
			'loading_animation' => thegem_get_option('product_archive_loading_animation'),
			'animation_effect' => thegem_get_option('product_archive_animation_effect'),
			'ignore_highlights' => thegem_get_option('product_archive_ignore_highlights'),
			'image_hover_effect_image' => thegem_get_option('product_archive_image_hover_effect_image'),
			'image_hover_effect_page' => thegem_get_option('product_archive_image_hover_effect_page'),
			'image_hover_effect_hover' => thegem_get_option('product_archive_image_hover_effect_hover'),
			'image_hover_effect_fallback' => thegem_get_option('product_archive_image_hover_effect_fallback'),
			'caption_container_preset' => thegem_get_option('product_archive_caption_container_preset'),
			'product_separator' => thegem_get_option('product_archive_caption_container_separator'),
			'caption_container_preset_hover' => thegem_get_option('product_archive_caption_container_preset_hover'),
			'caption_container_alignment_hover' => thegem_get_option('product_archive_caption_container_alignment_hover'),
			'buttons_icon_alignment' => thegem_get_option('product_archive_button_icon_alignment'),
			'button_cart_color_normal' => thegem_get_option('product_archive_button_add_to_cart_text_color'),
			'button_cart_color_hover' => thegem_get_option('product_archive_button_add_to_cart_text_color_hover'),
			'button_cart_background_color_normal' => thegem_get_option('product_archive_button_add_to_cart_background_color'),
			'button_cart_background_color_hover' => thegem_get_option('product_archive_button_add_to_cart_background_color_hover'),
			'button_cart_border_color_normal' => thegem_get_option('product_archive_button_add_to_cart_border_color'),
			'button_cart_border_color_hover' => thegem_get_option('product_archive_button_add_to_cart_border_color_hover'),
			'button_options_color_normal' => thegem_get_option('product_archive_button_select_options_text_color'),
			'button_options_color_hover' => thegem_get_option('product_archive_button_select_options_text_color_hover'),
			'button_options_background_color_normal' => thegem_get_option('product_archive_button_select_options_background_color'),
			'button_options_background_color_hover' => thegem_get_option('product_archive_button_select_options_background_color_hover'),
			'button_options_border_color_normal' => thegem_get_option('product_archive_button_select_options_border_color'),
			'button_options_border_color_hover' => thegem_get_option('product_archive_button_select_options_border_color_hover'),
			'product_show_new' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_new') : '',
			'product_show_sale' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_sale') : '',
			'product_show_out' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_out_stock') : '',
			'labels_design' => thegem_get_option('product_labels_style'),
			'new_label_text' => thegem_get_option('product_label_new_text'),
			'sale_label_type' => thegem_get_option('product_label_sale_type'),
			'sale_label_prefix' => thegem_get_option('product_label_sale_prefix'),
			'sale_label_suffix' => thegem_get_option('product_label_sale_suffix'),
			'sale_label_text' => thegem_get_option('product_label_sale_text'),
			'out_label_text' => thegem_get_option('product_label_out_of_stock_text'),
			'social_sharing' => thegem_get_option('product_archive_social_sharing'),
			'cart_hook' => thegem_get_option('product_archive_cart_hook'),
			'skeleton_loader' => thegem_get_option('product_archive_skeleton_loader'),
			'ajax_preloader_type' => thegem_get_option('product_archive_ajax_preloader_type'),
			'fullwidth_section_images' => thegem_get_option( 'product_archive_used_in_fullwidth_section'),
		), $args);

		wp_enqueue_style('thegem-portfolio-products-extended');
		wp_enqueue_script('thegem-woocommerce');
		wp_enqueue_script('thegem-portfolio-grid-extended');
		if (!wp_script_is('thegem-portfolio-grid-extended-inline')) {
			wp_enqueue_script('thegem-portfolio-grid-extended-inline');
			wp_add_inline_script( 'thegem-portfolio-grid-extended-inline', "jQuery('.extended-products-grid .yith-icon').each(function () {
					var addIcon = jQuery(this).children('.add-wishlist-icon').clone();
					var addedIcon = jQuery(this).children('.added-wishlist-icon').clone();
					jQuery(this).find('a i').remove();
					jQuery(this).find('a svg').remove();
					jQuery(this).find('.yith-wcwl-add-button a:not(.delete_item)').prepend(addIcon);
					jQuery(this).find('.yith-wcwl-add-button a.delete_item').prepend(addedIcon);
					jQuery(this).find('.yith-wcwl-wishlistexistsbrowse a').prepend(addedIcon);
					jQuery(this).find('a').addClass('icon');
					jQuery(this).find('a.gem-button').removeAttr('class').removeAttr('style').removeAttr('onmouseleave').removeAttr('onmouseenter').addClass('icon');
					jQuery(this).find('.yith-wcwl-wishlistaddedbrowse a').prepend(addedIcon);
				});" );
		}

		$grid_uid = $params['portfolio_uid'];

		wp_enqueue_style('thegem-hovers-' . $hover_effect);

		if ($params['quick_view'] == '1') {
			wp_enqueue_script('wc-single-product');
			wp_enqueue_script('wc-add-to-cart-variation');
			wp_enqueue_script('thegem-product-quick-view');
			if (!is_product() && thegem_is_quick_view_default()) {
				wp_enqueue_script('thegem-quick-view');
				wp_enqueue_style('thegem-quick-view');
			}
			if(thegem_get_option('product_page_layout') == 'default') {
				if(thegem_get_option('product_page_button_add_to_cart_icon') && thegem_get_option('product_page_button_add_to_cart_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_cart_icon_pack'));
				}
				if(thegem_get_option('product_page_button_add_to_wishlist_icon') && thegem_get_option('product_page_button_add_to_wishlist_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_add_to_wishlist_icon_pack'));
				}
				if(thegem_get_option('product_page_button_added_to_wishlist_icon') && thegem_get_option('product_page_button_added_to_wishlist_icon_pack')) {
					wp_enqueue_style('icons-'.thegem_get_option('product_page_button_added_to_wishlist_icon_pack'));
				}
			}
			if (thegem_get_option('product_gallery') != 'legacy') {
				wp_enqueue_style('thegem-product-gallery');
			} else {
				wp_enqueue_style('thegem-hovers');
			}
		}

		if ($params['loading_animation'] === '1') {
			wp_enqueue_style('thegem-animations');
			wp_enqueue_script('thegem-items-animations');
			wp_enqueue_script('thegem-scroll-monitor');
		}

		if ($params['layout'] !== 'justified' || $params['ignore_highlights'] !== '1') {

			if ($params['layout'] == 'metro') {
				wp_enqueue_script('thegem-isotope-metro');
			} else {
				wp_enqueue_script('thegem-isotope-masonry-custom');
			}
		}

		$items_per_page = count($products);
		$item_classes = get_thegem_portfolio_render_item_classes($params);
		$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

		if ($params['columns_desktop'] == '100%' || (($params['ignore_highlights'] !== '1' || $params['layout'] !== 'justified') && $params['skeleton_loader'] !== '1')) {
			$spin_class = 'preloader-spin';
			if ($params['ajax_preloader_type'] == 'minimal') {
				$spin_class = 'preloader-spin-new';
			}
			echo apply_filters('thegem_portfolio_preloader_html', '<div class="preloader save-space"><div class="' . $spin_class . '"></div></div>');
		} else if ($params['skeleton_loader'] == '1') { ?>
				<div class="preloader save-space shop-skeleton" data-style-uid="to_products">
					<div class="skeleton">
						<div class="skeleton-posts row portfolio-row">
							<?php for ($x = 0; $x < $items_per_page; $x++) {
								echo thegem_extended_products_render_item($params, $item_classes);
							} ?>
						</div>
					</div>
				</div>
			<?php } ?>

			<div class="portfolio-preloader-wrapper">

				<?php
				if ($params['caption_position'] == 'hover') {
					$title_on = 'hover';
				} else {
					$title_on = 'page';
				}

				$portfolio_classes = array(
					'portfolio portfolio-grid extended-portfolio-grid extended-products-grid',
					'to-extended-products',
					'woocommerce',
					'products',
					'no-padding',
					'portfolio-preset-' . $preset,
					'portfolio-style-' . $params['layout'],
					'background-style-' . $params['caption_container_preset'],
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-container-preset-' . $params['caption_container_preset_hover'] : '',
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-alignment-' . $params['caption_container_alignment_hover'] : '',
					'caption-position-' . $params['caption_position'],
					'hover-' . $hover_effect,
					'title-on-' . $title_on,
					($params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
					($params['loading_animation'] == '1' ? 'loading-animation' : ''),
					($params['loading_animation'] == '1' && $params['animation_effect'] ? 'item-animation-' . $params['animation_effect'] : ''),
					($params['image_gaps'] == 0 ? 'no-gaps' : ''),
					($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-desktop-' . $params['columns_100'] : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
					($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
					($params['social_sharing'] != '1' ? 'portfolio-disable-socials' : ''),
					($params['layout'] == 'masonry' ? 'portfolio-items-masonry' : ''),
					($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
					('columns-tablet-' . $params['columns_tablet']),
					('columns-mobile-' . $params['columns_mobile']),
					($params['product_separator'] == '1' ? 'item-separator' : ''),
					($params['layout'] == 'justified' && $params['ignore_highlights'] =='1' ? 'disable-isotope' : ''),
					(($params['image_size'] == 'full' && empty($params['image_ratio_full']['size']) || !in_array($params['image_size'], ['full', 'default'])) ? 'full-image' : 'aspect-ratio-custom'),
					($params['ajax_preloader_type'] == 'minimal' ? 'minimal-preloader' : ''),
				);
				?>

				<div class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>"
					 data-per-page="-1"
					 data-style-uid="to_products"
					 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
					 data-hover="<?php echo esc_attr($hover_effect) ?>">
					<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
						<input id="shop-page-url" type="hidden" <?php if (get_home_url()."/" == wc_get_page_permalink('shop')) {?>class="is-shop-home"<?php } ?>
							   value="<?php echo (isset($queried->taxonomy) && $queried->taxonomy == 'product_cat') ? get_term_link($queried->slug, 'product_cat') : wc_get_page_permalink('shop'); ?>">
								<div class="row portfolio-row clearfix">
										<div class="portfolio-set"
											 data-max-row-height="">

											<?php if (!empty($products)) {
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
												remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);

												$item_classes = get_thegem_portfolio_render_item_classes($params);
												$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);
												foreach ($products as $product_item) : ?>
													<?php
														$post_object = get_post( $product_item->get_id() );
														setup_postdata( $GLOBALS['post'] =& $post_object );
														echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
													<?php
												endforeach;
												wp_reset_postdata();
											} ?>
										</div>
									<div class="portfolio-item-size-container">
										<?php echo thegem_extended_products_render_item($params, $item_classes); ?>
									</div>
								</div><!-- .row-->
					</div><!-- .full-width -->
				</div><!-- .portfolio-->
			</div><!-- .portfolio-preloader-wrapper-->
			<?php
		$post = $portfolio_posttemp;
	}
}

if (!function_exists('thegem_woocommerce_search_grid_content')) {
	function thegem_woocommerce_search_grid_content($products = array()) {

		if (thegem_get_option('product_archive_preset_type') == 'on_image') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_image');
			$caption_position = 'image';
			$preset = 'image-' . thegem_get_option('product_archive_preset_on_image');
		} else if (thegem_get_option('product_archive_preset_type') == 'below') {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_page');
			$caption_position = 'page';
			$preset = 'below-' . thegem_get_option('product_archive_preset_below');
		} else {
			$hover_effect = thegem_get_option('product_archive_image_hover_effect_hover');
			$caption_position = 'hover';
			$preset = 'hover-' . thegem_get_option('product_archive_preset_on_hover');
		}

		$params = array(
			'portfolio_uid' => 'search-products',
			'style_uid' => 'search-products',
			'layout' => thegem_get_option('product_archive_layout'),
			'image_gaps' => '28',
			'image_gaps_tablet' => '24',
			'image_gaps_mobile' => '14',
			'columns_desktop' => '100%',
			'columns_tablet' => '4x',
			'columns_mobile' => '2x',
			'columns_100' => '6',
			'caption_position' => $caption_position,
			'image_size' => thegem_get_option('product_archive_layout') == 'justified' ? thegem_get_option('product_archive_image_size') : '',
			'image_ratio_full' => thegem_get_option('product_archive_image_ratio_full'),
			'image_ratio_custom' => thegem_get_option('product_archive_image_ratio_custom'),
			'image_aspect_ratio' => thegem_get_option('product_archive_image_aspect_ratio'),
			'quick_view' => '',
			'product_show_categories' => '',
			'product_show_categories_tablet' => '',
			'product_show_categories_mobile' => '',
			'product_show_title' => thegem_get_option('product_archive_show_title'),
			'product_show_price' => '1',
			'product_show_reviews' => '',
			'product_show_reviews_tablet' => '',
			'product_show_reviews_mobile' => '',
			'product_show_add_to_cart' => '',
			'product_show_add_to_cart_mobiles' => '',
			'product_show_wishlist' => '',
			'loading_animation' => '',
			'ignore_highlights' => '1',
			'image_hover_effect_image' => thegem_get_option('product_archive_image_hover_effect_image'),
			'image_hover_effect_page' => thegem_get_option('product_archive_image_hover_effect_page'),
			'image_hover_effect_hover' => thegem_get_option('product_archive_image_hover_effect_hover'),
			'image_hover_effect_fallback' => thegem_get_option('product_archive_image_hover_effect_fallback'),
			'caption_container_preset' => thegem_get_option('product_archive_caption_container_preset'),
			'product_separator' => thegem_get_option('product_archive_caption_container_separator'),
			'caption_container_preset_hover' => thegem_get_option('product_archive_caption_container_preset_hover'),
			'caption_container_alignment_hover' => thegem_get_option('product_archive_caption_container_alignment_hover'),
			'product_show_new' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_new') : '',
			'product_show_sale' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_sale') : '',
			'product_show_out' => thegem_get_option('product_archive_labels') == '1' ? thegem_get_option('product_archive_label_out_stock') : '',
			'labels_design' => thegem_get_option('product_labels_style'),
			'new_label_text' => thegem_get_option('product_label_new_text'),
			'sale_label_type' => thegem_get_option('product_label_sale_type'),
			'sale_label_prefix' => thegem_get_option('product_label_sale_prefix'),
			'sale_label_suffix' => thegem_get_option('product_label_sale_suffix'),
			'sale_label_text' => thegem_get_option('product_label_sale_text'),
			'out_label_text' => thegem_get_option('product_label_out_of_stock_text'),
			'social_sharing' => thegem_get_option('product_archive_social_sharing'),
			'cart_hook' => thegem_get_option('product_archive_cart_hook'),
			'skeleton_loader' => '',
		);

		echo thegem_extended_products_render_styles($params);

		$grid_uid = $params['portfolio_uid'];
		?>

			<div class="portfolio-preloader-wrapper">

				<?php
				if ($params['caption_position'] == 'hover') {
					$title_on = 'hover';
				} else {
					$title_on = 'page';
				}

				$portfolio_classes = array(
					'portfolio portfolio-grid extended-portfolio-grid extended-products-grid disable-isotope',
					'woocommerce',
					'products',
					'no-padding',
					'portfolio-preset-' . $preset,
					'portfolio-style-' . $params['layout'],
					'background-style-' . $params['caption_container_preset'],
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-container-preset-' . $params['caption_container_preset_hover'] : '',
					(($params['caption_position'] == 'hover' && ($params['image_hover_effect_hover'] == 'slide' || $params['image_hover_effect_hover'] == 'fade')) || $params['caption_position'] == 'image') ? 'caption-alignment-' . $params['caption_container_alignment_hover'] : '',
					'caption-position-' . $params['caption_position'],
					'hover-' . $hover_effect,
					'title-on-' . $title_on,
					($params['image_size'] == 'default' ? 'aspect-ratio-' . $params['image_aspect_ratio'] : ''),
					($params['image_gaps'] == 0 ? 'no-gaps' : ''),
					($params['columns_desktop'] == '100%' ? 'fullwidth-columns fullwidth-columns-desktop-' . $params['columns_100'] : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'gradient' ? 'hover-gradient-title' : ''),
					($params['caption_position'] == 'image' && $params['image_hover_effect_image'] == 'circular' ? 'hover-circular-title' : ''),
					($params['caption_position'] == 'hover' || $params['caption_position'] == 'image' ? 'hover-title' : ''),
					($params['social_sharing'] != '1' ? 'portfolio-disable-socials' : ''),
					($params['columns_desktop'] != '100%' ? 'columns-desktop-' . $params['columns_desktop'] : 'columns-desktop-' . $params['columns_100']),
					('columns-tablet-' . $params['columns_tablet']),
					('columns-mobile-' . $params['columns_mobile']),
					($params['product_separator'] == '1' ? 'item-separator' : ''),
					(($params['image_size'] == 'full' && empty($params['image_ratio_full']['size']) || !in_array($params['image_size'], ['full', 'default'])) ? 'full-image' : 'aspect-ratio-custom'),
				);
				?>

				<div class="<?php echo esc_attr(implode(' ', $portfolio_classes)) ?>"
					 id="style-search-products"
					 data-per-page="-1"
					 data-portfolio-uid="<?php echo esc_attr($grid_uid) ?>"
					 data-hover="<?php echo esc_attr($hover_effect) ?>">
					<div class="portfolio-row-outer <?php if ($params['columns_desktop'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
								<div class="row portfolio-row clearfix">
										<div class="portfolio-set"
											 data-max-row-height="">

											<?php if (!empty($products)) {
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
												remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
												remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
												remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_link', 15);
												remove_action('woocommerce_after_shop_loop_item', 'thegem_woocommerce_after_shop_loop_item_wishlist', 20);

												$item_classes = get_thegem_portfolio_render_item_classes($params);
												$thegem_sizes = get_thegem_portfolio_render_item_image_sizes($params);

												while ($products->have_posts()) : $products->the_post(); ?>
													<?php echo thegem_extended_products_render_item($params, $item_classes, $thegem_sizes, get_the_ID()); ?>
												<?php endwhile;
												wp_reset_postdata();
											} ?>
										</div>
									<div class="portfolio-item-size-container">
										<?php echo thegem_extended_products_render_item($params, $item_classes); ?>
									</div>
								</div><!-- .row-->
					</div><!-- .full-width -->
				</div><!-- .portfolio-->
			</div><!-- .portfolio-preloader-wrapper-->
			<?php
	}
}

function thegem_woocommerce_widget_swatches( $term_html, $term, $link, $count ) {

	if (thegem_get_option('product_archive_type') !== 'legacy') {
		$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name($term->taxonomy));
		$attribute_type_class = $attribute_data->type == 'color' || $attribute_data->type == 'label' || $attribute_data->type == 'image' ? ' attribute-type-'.$attribute_data->type : '';

		$term_title = $term->name;
		if ($attribute_data->type == 'label') {
			$attribute_label = get_term_meta( $term->term_id, 'thegem_label', true );
			$term_title = !empty($attribute_label) ? $attribute_label : $term_title;
		}

		$term_html = '<a href="'.$link.'" class="' . esc_attr( $attribute_type_class ) . '" rel="nofollow">';
		if($attribute_data->type == 'color') {
			$attribute_color = get_term_meta( $term->term_id, 'thegem_color', true );
			$term_html .= '<span class="color"' . (!empty($attribute_color) ? ' style="background-color: ' . esc_attr($attribute_color).';"' : '') . '></span>';
		}
		if($attribute_data->type == 'image') {
			$attribute_image = wp_get_attachment_image_url(get_term_meta( $term->term_id, 'thegem_image', true ), 'woocommerce_thumbnail');
			$term_html .= '<span class="image"' . (!empty($$attribute_image) ? ' style="background-image: url(' . esc_url($attribute_image).');"' : '') . '></span>';
		}
		$term_html .= '<span class="title">' . esc_html( $term_title ) . '</span>';
		$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );
		$term_html .= '</a>';
	}

	return $term_html;
}
add_filter( 'woocommerce_layered_nav_term_html', 'thegem_woocommerce_widget_swatches', 10, 4 );

function thegem_woocommerce_widget_swatches_title($title, $instance = array(), $id_base = '') {
	if($id_base == 'woocommerce_layered_nav' && $instance['display_type'] == 'list') {
		$attribute_data = wc_get_attribute(wc_attribute_taxonomy_id_by_name('pa_'.$instance['attribute']));
		if ($attribute_data->type == 'label') {
			echo '<div class="attribute-type-label-list"></div>';
		}
	}
	return $title;
}
add_action( 'widget_title', 'thegem_woocommerce_widget_swatches_title', 10, 3 );

function thegem_woocommerce_quantity_buttons() {
	global $product, $thegem_product_data;

	if ( !empty($thegem_product_data) && $thegem_product_data['product_page_layout'] === 'legacy' ) return; ?>

	<script>
		(function($) {
			$('form.cart div.quantity:not(.buttons_added)').addClass('buttons_added').append('<button type="button" class="plus" >+</button>').prepend('<button type="button" class="minus" >-</button>');
		})(jQuery);
	</script>
	<?php
}
add_action( 'woocommerce_after_quantity_input_field', 'thegem_woocommerce_quantity_buttons', 10, 3 );

function thegem_single_product_template() {
	if(!function_exists('thegem_get_template_type') || !is_singular( 'product' )) return false;
	$product_id = get_the_ID();
	$product_data = thegem_get_output_product_page_data($product_id);
	$product_admin_data = thegem_get_sanitize_product_page_data($product_id);
	if($product_admin_data['product_layout_settings'] == 'default') {
		$taxonomies = get_post_taxonomies($product_id);
		if(!empty($taxonomies)) {
			$product_terms = wp_get_object_terms($product_id, $taxonomies);
			if(!empty($product_terms) && is_array($product_terms)) {
				$products_layout_data = false;
				foreach($product_terms as $term) {
					if(!empty($products_layout_data)) continue;
					$term_data = thegem_get_output_product_archive_data($term->term_id, get_post_type($product_id));
					if(isset($term_data['products_layout_settings']) && $term_data['products_layout_settings'] === 'custom' && isset($term_data['products_layout_source'])) {
						$product_data['product_layout_source'] = $term_data['products_layout_source'];
						if(isset($term_data['products_builder_template'])) {
							$product_data['product_builder_template'] = $term_data['products_builder_template'];
						}
						$products_layout_data = true;
					}
				}
			}
		}
	}
	if($product_data['product_layout_source'] !== 'builder') return false;
	$template_id = intval($product_data['product_builder_template']);
	if($template_id < 1) return false;
	$template_id = apply_filters( 'wpml_object_id', $template_id);
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'single-product') {
		return $template_id;
	}
	return false;
}

function thegem_archive_product_template() {
	if(!function_exists('thegem_get_template_type') || !(thegem_get_template_type( get_the_ID() ) === 'product-archive' || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_tax('product_brand') || (function_exists('is_product_taxonomy') && is_product_taxonomy()) || is_post_type_archive( 'product' ))) return false;
	$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : 0;
	$product_archive_data = thegem_get_output_product_archive_data($term_id);
	if($product_archive_data['product_archive_layout_source'] !== 'builder') return false;
	$template_id = intval($product_archive_data['product_archive_builder_template']);
	$template_id = apply_filters( 'wpml_object_id', $template_id, 'thegem_templates', true );
	if($template_id < 1) return false;
	$template = get_post($template_id);
	if($template && thegem_get_template_type($template_id) == 'product-archive') {
		return $template_id;
	}
	return false;
}

function thegem_cart_template() {
	if(!function_exists('thegem_get_template_type') || thegem_get_option('cart_layout_source') != 'builder') return false;
	$template = thegem_get_option('cart_builder_template');
	$template_id = intval($template);
	$template_id = apply_filters( 'wpml_object_id', $template_id, 'thegem_templates', true );
	if(thegem_get_template_type($template_id) == 'cart') {
		return $template_id;
	}
	return false;
}

function thegem_cart_empty_template() {
	if(!function_exists('thegem_get_template_type') || thegem_get_option('cart_empty_layout_source') != 'builder') return false;
	$template = thegem_get_option('cart_empty_builder_template');
	$template_id = intval($template);
	$template_id = apply_filters( 'wpml_object_id', $template_id, 'thegem_templates', true );
	if(thegem_get_template_type($template_id) == 'content') {
		return $template_id;
	}
	return false;
}

function thegem_checkout_template() {
	if(!function_exists('thegem_get_template_type') || thegem_get_option('checkout_layout_source') != 'builder') return false;
	$template = thegem_get_option('checkout_builder_template');
	$template_id = intval($template);
	$template_id = apply_filters( 'wpml_object_id', $template_id, 'thegem_templates', true );
	if(thegem_get_template_type($template_id) == 'checkout') {
		return $template_id;
	}
	return false;
}

function thegem_checkout_thanks_template() {
	if(!function_exists('thegem_get_template_type') || thegem_get_option('checkout_thanks_layout_source') != 'builder') return false;
	$template = thegem_get_option('checkout_thanks_builder_template');
	$template_id = intval($template);
	$template_id = apply_filters( 'wpml_object_id', $template_id, 'thegem_templates', true );
	if(thegem_get_template_type($template_id) == 'checkout-thanks') {
		return $template_id;
	}
	return false;
}

function thegem_template_cart_layout($value) {
	if(thegem_cart_template()) {
		$value = 'modern';
	}
	if(thegem_checkout_template()) {
		$value = 'modern';
	}
	if(function_exists('thegem_get_template_type') && (thegem_get_template_type(get_the_id()) == 'cart' || thegem_get_template_type(get_the_id()) == 'checkout')) {
		$value = 'modern';
	}
	return $value;
}
add_filter('thegem_option_cart_layout', 'thegem_template_cart_layout');

function thegem_page_template_cart_checkout($template, $type, $templates) {
	if (defined('WC_PLUGIN_FILE') && defined('ELEMENTOR_VERSION')) {
		if((is_cart() && thegem_cart_template()) || (is_checkout() && thegem_checkout_template())) {
			$template = locate_template( 'woocommerce/cart-checkout-page-template.php' );
		}
	}
	return $template;
}
add_filter('page_template', 'thegem_page_template_cart_checkout', 10,3);

function thegem_admin_page_data_checkout_thanks( $page_data, $post_id, $item_data, $type) {
	if (defined('WC_PLUGIN_FILE')) {
		if(is_checkout() && is_wc_endpoint_url('order-received')) {
			if(thegem_cart_template()) {
				$page_data['title_show'] = 'disabled';
			}
			if(thegem_checkout_template() && !thegem_checkout_thanks_template()) {
				$page_data['content_area_options'] = 'default';
			}
		}
	}
	return $page_data;
}
add_filter('thegem_admin_page_data', 'thegem_admin_page_data_checkout_thanks', 10, 4);

function thegem_admin_page_data_cart_empty( $page_data, $post_id, $item_data, $type) {
	if (defined('WC_PLUGIN_FILE')) {
		if(is_cart() && thegem_cart_template()) {
			$page_data['content_area_options'] = 'default';
		}
	}
	return $page_data;
}
add_filter('thegem_admin_page_data', 'thegem_admin_page_data_cart_empty', 10, 4);

function thegem_add_button_class_script() {
	if (defined('WC_PLUGIN_FILE')) {
?>
<script type="text/javascript">
var thegem_woo_buttons = document.querySelectorAll('.button');
for (index = 0; index < thegem_woo_buttons.length; index++) {
	if(thegem_woo_buttons[index].tagName.toLowerCase() !== 'p' && thegem_woo_buttons[index].tagName.toLowerCase() !== 'div' && !thegem_woo_buttons[index].closest('.portfolio-item') && !thegem_woo_buttons[index].closest('.thegem-te-loop-product-add-to-cart') && !thegem_woo_buttons[index].closest('.products') && !(thegem_woo_buttons[index].closest('.thegem-popup-notification')) && !(thegem_woo_buttons[index].closest('.woocommerce-MyAccount-content .woocommerce-info'))) {
		thegem_woo_buttons[index].classList.add('gem-button');
		thegem_woo_buttons[index].classList.add('gem-wc-button');
	}
}
</script>
<?php
	}
}
add_action( 'wp_footer', 'thegem_add_button_class_script', 100 );

if(!function_exists('thegem_product_need_new_label')){
function thegem_product_need_new_label($product_id) {
	$need = false;
	$product = wc_get_product($product_id);
	$new_label_method = thegem_get_option('product_new_label_display_method');
	if($new_label_method === 'days') {
		$new_label_days = intval(thegem_get_option('product_new_label_display_days'));
		if($new_label_days > 0 && $product->get_date_created()->getTimestamp() > time() - $new_label_days*24*60*60) {
			$need = true;
		}
	} elseif($product->is_featured()) {
		$need = true;
	}
	return apply_filters('thegem_product_need_new_label', $need, $product_id);
}
}

function thegem_wc_track_product_view() {
	if ( ! is_singular( 'product' ) || apply_filters( 'thegem_wc_track_recently_viewed_product_disabled', false ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['thegem_recently_viewed_products'] ) ) {
		$viewed_products = array();
	} else {
		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['thegem_recently_viewed_products'] ) ) ); // @codingStandardsIgnoreLine.
	}

	array_unshift( $viewed_products, $post->ID );

	if ( count( $viewed_products ) > apply_filters( 'thegem_viewed_products', 15 ) ) {
		$viewed_products = array_pop( $viewed_products );
	}

	if ( is_array( $viewed_products ) ) {
		$viewed_products = implode( '|', $viewed_products );
	}

	setcookie( 'thegem_recently_viewed_products', $viewed_products, 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), false );
}
add_action( 'template_redirect', 'thegem_wc_track_product_view', 20 );

function thegem_checkout_wc_plugin_disable_hooks() {
	if ( defined( 'CFW_VERSION' ) ) {
		remove_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_end', 1000);
		remove_action( 'woocommerce_checkout_before_order_review', 'thegem_woocommerce_order_review_table_start', 1);
		remove_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_table_end', 1000);
	}
}
add_action('init', 'thegem_checkout_wc_plugin_disable_hooks');


// Native gallery

function thegem_add_native_gallery_scripts() {
	wp_enqueue_script( 'zoom' );
	wp_enqueue_script('flexslider');
	wp_enqueue_script( 'photoswipe-ui-default' );
	wp_enqueue_style( 'photoswipe-default-skin' );
	add_action( 'wp_footer', 'thegem_woocommerce_photoswipe' );
}
add_action('woocommerce_product_thumbnails', 'thegem_add_native_gallery_scripts');

function thegem_woocommerce_photoswipe() {
	wc_get_template( 'single-product/photoswipe.php' );
}

function thegem_woocommerce_get_script_data_native_gallery($params, $handle) {
	if($handle === 'wc-single-product') {
		$params['flexslider_enabled'] = true;
		$params['zoom_enabled'] = true;
		$params['photoswipe_enabled'] = true;
	}
	return $params;
}
add_filter('woocommerce_get_script_data', 'thegem_woocommerce_get_script_data_native_gallery', 10, 2);

function thegem_woocommerce_single_product_flexslider_enabled_native_gallery($enabled) {
	$enabled = true;
	return $enabled;
}
add_filter('woocommerce_single_product_flexslider_enabled', 'thegem_woocommerce_single_product_flexslider_enabled_native_gallery');

if(class_exists('YITH_WCWL_Add_To_Wishlist_Button')) {
	class TheGem_Add_To_Wishlist_Button extends YITH_WCWL_Add_To_Wishlist_Button {
		public function set_thegem_product_page( $product_page ) {
			$this->set_prop( 'thegem_product_page', $product_page );
		}

		public function set_thegem_template( $template ) {
			$this->set_prop( 'thegem_template', $template );
		}

		public function set_thegem_products_grid( $products_grid ) {
			$this->set_prop( 'thegem_products_grid', $products_grid );
		}

		public function set_link_classes( $link_classes ) {
			$this->set_prop( 'link_classes', $link_classes );
		}

		protected function get_legacy_php_template_render_data() {
			$product = $this->get_product();

			if ( ! $product ) {
				return false;
			}

			// get wishlist url.
			$wishlist_url = YITH_WCWL()->get_wishlist_url();

			$data = array(
				'label'                     => $this->get_label_for_display(),
				'base_url'                  => yith_wcwl_get_current_url(),
				'wishlist_url'              => $wishlist_url,
				'container_classes'         => $this->get_container_classes(),
				'product_id'                => $product->get_id(),
				'parent_product_id'         => $product->get_parent_id(),
				'product_type'              => $product->get_type(), // TODO: check if needed.
				'link_classes'              => $this->get_button_classes() . (empty($this->data['link_classes']) ? '' : ' ' . $this->data['link_classes']),
				'is_single'                 => $this->get_is_single(),
				'in_default_wishlist'       => $this->is_in_default_wishlist(), // Always true if it exists in the free version
				'exists'                    => $this->is_in_a_wishlist(),
				'found_in_list'             => $this->get_wishlist(),
				'found_item'                => $this->get_wishlist_item(),
				'template_part'             => $this->get_template_kind(),
				'show_view'                 => yith_wcwl_is_single(),
				'browse_wishlist_text'      => $this->get_browse_label(),
				'already_in_wishslist_text' => $this->get_already_in_label(),
				'product_added_text'        => $this->get_added_label(),
				'icon'                      => $this->get_icon_to_display(),
				'available_multi_wishlist'  => false,
				'disable_wishlist'          => false,
				'show_count'                => false, // Always false in free version
				'ajax_loading'              => $this->get_ajax_loading(),
				'loop_position'             => self::get_option( 'loop_position' ),
				'thegem_product_page'       => empty($this->data['thegem_product_page']) ? '' : $this->data['thegem_product_page'],
				'thegem_template'           => empty($this->data['thegem_template']) ? '' : $this->data['thegem_template'],
				'thegem_products_grid'      => empty($this->data['thegem_products_grid']) ? '' : $this->data['thegem_products_grid'],
			);

			/**
			 * APPLY_FILTERS: yith_wcwl_add_to_wishlist_params
			 *
			 * Filter the array with the parameters for the 'Add to wishlist' action.
			 *
			 * @param array $additional_params Wishlist parameters
			 * @param array $data              Array of attributes
			 *
			 * @return array
			 */
			$data = apply_filters( 'yith_wcwl_add_to_wishlist_params', $data, $data );

			// set fragment options.
			$data[ 'fragment_options' ] = YITH_WCWL_Frontend()->format_fragment_options( $data, 'add_to_wishlist' );

			// Updating icon and heading icon HTML
			$data = array_merge( $data, $this->get_icons_html() );

			return $data;
		}
	}
}

function thegem_yith_wcwl_add_to_wishlist_shortcode_output( $output, $tag, $attr, $m ){
	if($tag === 'yith_wcwl_add_to_wishlist' && class_exists('YITH_WCWL_Add_To_Wishlist_Button')) {
		$button = TheGem_Add_To_Wishlist_Button::get($attr);
		$output = $button->render(true);
	}
	return $output;
}
add_filter('do_shortcode_tag', 'thegem_yith_wcwl_add_to_wishlist_shortcode_output', 10, 4);

function thegem_yith_wcwl_ajax_add_return_params( $args ){
	$fragments = isset( $_REQUEST['fragments'] ) ? wc_clean( $_REQUEST['fragments'] ) : false;
	if ( in_array( $args['result'], array( 'exists', 'true' ), true ) ) {
		// search for related fragments.
		if ( ! empty( $fragments ) && ! empty( $args['prod_id'] ) ) {
			foreach ( $fragments as $id => $options ) {
				if ( strpos( $id, 'add-to-wishlist-' . $args['prod_id'] ) ) {
					$fragments[ $id ]['wishlist_url']      = $args['wishlist_url'];
					$fragments[ $id ]['added_to_wishlist'] = 'true' === $args['result'];
				}
			}
		}
	}

	$result = array();
	if ( ! empty( $fragments ) ) {
		foreach ( $fragments as $id => $options ) {
			$id      = sanitize_text_field( $id );
			$options = YITH_WCWL_Frontend()->decode_fragment_options( $options );
			$item    = isset( $options['item'] ) ? $options['item'] : false;

			if ( ! $item ) {
				continue;
			}

			$button = TheGem_Add_To_Wishlist_Button::get($options);
			$output = $button->render(true);
			$result[ $id ] = $output;
		}
	}
	if(!empty($result)) {
		$args['fragments'] = $result;
	}

	return $args;
}
add_filter('yith_wcwl_ajax_add_return_params', 'thegem_yith_wcwl_ajax_add_return_params');

function thegem_yith_wcwl_ajax_delete_item() {
	if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'delete_item' ) ) {
		wp_send_json( array( 'result' => false ) );
	}

	$item_id   = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : false;
	$fragments = isset( $_REQUEST['fragments'] ) ? wc_clean( $_REQUEST['fragments'] ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$return    = array(
		'result' => false,
	);

	if ( $item_id ) {
		$item = YITH_WCWL_Wishlist_Factory::get_wishlist_item( $item_id );

		if ( $item ) {
			$item->delete();

			$result = array();
			if ( ! empty( $fragments ) ) {
				foreach ( $fragments as $id => $options ) {
					$id      = sanitize_text_field( $id );
					$options = YITH_WCWL_Frontend()->decode_fragment_options( $options );
					$item    = isset( $options['item'] ) ? $options['item'] : false;

					if ( ! $item ) {
						continue;
					}

					$button = TheGem_Add_To_Wishlist_Button::get($options);
					$output = $button->render(true);
					$result[ $id ] = $output;
				}
			}

			$return = array(
				'result'    => true,
				'message'   => apply_filters( 'yith_wcwl_product_removed_text', __( 'Product successfully removed.', 'yith-woocommerce-wishlist' ) ),
				'fragments' => array(),
			);

			if(!empty($result)) {
				$return['fragments'] = $result;
			}
		}
	}

	wp_send_json( $return );
}

function thegem_yith_wcwl_ajax_load_fragments() {
	if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'load_fragments' ) ) {
		wp_send_json( array( 'result' => false ) );
	}

	$fragments = isset( $_POST['fragments'] ) ? wc_clean( $_POST['fragments'] ) : false; // phpcs:ignore WordPress.Security

	$result = array();
	if ( ! empty( $fragments ) ) {
		foreach ( $fragments as $id => $options ) {
			$id      = sanitize_text_field( $id );
			$options = YITH_WCWL_Frontend()->decode_fragment_options( $options );
			$item    = isset( $options['item'] ) ? $options['item'] : false;

			if ( ! $item ) {
				continue;
			}

			$button = TheGem_Add_To_Wishlist_Button::get($options);
			$output = $button->render(true);
			$result[ $id ] = $output;
		}
	}

	$return = array(
		'fragments' => array(),
	);

	if(!empty($result)) {
		$return['fragments'] = $result;
	}

	wp_send_json( $return );
}

function thegem_yith_wcwl_ajax_replace_init(){
	if(class_exists('YITH_WCWL_Add_To_Wishlist_Button')) {
		remove_action('wp_ajax_delete_item', array('YITH_WCWL_Ajax_Handler', 'delete_item'));
		remove_action('wp_ajax_nopriv_delete_item', array( 'YITH_WCWL_Ajax_Handler', 'delete_item'));
		add_action('wp_ajax_delete_item', 'thegem_yith_wcwl_ajax_delete_item');
		add_action('wp_ajax_nopriv_delete_item', 'thegem_yith_wcwl_ajax_delete_item');
		remove_action('wp_ajax_load_fragments', array('YITH_WCWL_Ajax_Handler', 'load_fragments'));
		remove_action('wp_ajax_nopriv_load_fragments', array( 'YITH_WCWL_Ajax_Handler', 'load_fragments'));
		add_action('wp_ajax_load_fragments', 'thegem_yith_wcwl_ajax_load_fragments');
		add_action('wp_ajax_nopriv_load_fragments', 'thegem_yith_wcwl_ajax_load_fragments');
	}
}
add_action('init', 'thegem_yith_wcwl_ajax_replace_init');

function thegem_yith_wcwl_rendering_method( $rendering_method, $default, $force_update ) {
	$rendering_method = 'php-templates';
	return $rendering_method;
}
add_filter('yith_wcwl_rendering_method', 'thegem_yith_wcwl_rendering_method', 10, 3);

function thegem_products_attributes_count_cache_init(){
	if(thegem_get_option('products_attributes_count_cache_disable')) {
		add_filter('woocommerce_layered_nav_count_maybe_cache', '__return_false');
	}
	if (class_exists('WooCommerce')) {
		$size = thegem_get_option('products_attributes_count_cache_size');
		if($size !== '' && intval($size) > 0) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			foreach ($attribute_taxonomies as $taxonomy) {
				add_filter('pre_set_transient_wc_layered_nav_counts_pa_' . $taxonomy->attribute_name, 'thegem_products_attributes_count_cache_split', 10, 3);
			}
		}
	}
}
add_action('init', 'thegem_products_attributes_count_cache_init');

function thegem_products_attributes_count_cache_split($value, $expiration, $transient) {
	$size = thegem_get_option('products_attributes_count_cache_size');
	$serialized = maybe_serialize($value);
	$length = strlen($serialized);
	if($length > $size*1024*1024) {
		$reduced_value = array_slice($value, (int)(count($value) / 2));
	}
	return $value;
}

function thegem_mini_cart_global_section_render() {
	$template_id = intval(thegem_get_option('mini_cart_global_section'));
	if($template_id > 0 && get_post($template_id) && defined('ELEMENTOR_VERSION')) {
		$return_html =\Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
		$return_html = '<div class="minicart-template-footer-placeholder" style="display: none;"><div class="thegem-template-wrapper thegem-template-content thegem-template-' . esc_attr($template_id) . '">' . $return_html . '</div></div>';
		echo $return_html;
	}
}

function thegem_mini_cart_empty_global_section_render() {
	$template_id = intval(thegem_get_option('mini_cart_empty_global_section'));
	if($template_id > 0 && get_post($template_id) && defined('ELEMENTOR_VERSION')) {
		$return_html =\Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
		$return_html = '<div class="minicart-empty-template-footer-placeholder" style="display: none;"><div class="thegem-template-wrapper thegem-template-content thegem-template-' . esc_attr($template_id) . '">' . $return_html . '</div></div>';
		echo $return_html;
	}
}

function thegem_mini_cart_global_section_script() {
?>
<script type="text/javascript">
(function ($, window) {
	function thegem_load_minicart_template() {
		var $minicartTemplate = $('.minicart-template-footer-placeholder > .thegem-template-wrapper');
		if($minicartTemplate.length) {
			var $minicartTemplatePlaceholders = $('.minicart-template-placeholder');
			$minicartTemplatePlaceholders.each(function() {
				var $placeholder = $(this);
				var $clone = $minicartTemplate.clone(true, true);
				$placeholder.empty();
				$placeholder.append($clone);
			});
		}
	}

	function thegem_load_minicart_empty_template() {
		var $minicartTemplate = $('.minicart-empty-template-footer-placeholder > .thegem-template-wrapper');
		if($minicartTemplate.length) {
			var $minicartTemplatePlaceholders = $('.minicart-empty-template-placeholder');
			$minicartTemplatePlaceholders.each(function() {
				var $placeholder = $(this);
				var $clone = $minicartTemplate.clone(true, true);
				$placeholder.empty();
				$placeholder.append($clone);
			});
		}
	}

	$(window).on('load', function() {
		thegem_load_minicart_template();
		thegem_load_minicart_empty_template();

		if (typeof wc_cart_fragments_params !== 'undefined' && localStorage.getItem('thegem_wc_cart_fragments_update')) {
			localStorage.removeItem('thegem_wc_cart_fragments_update');
			sessionStorage.removeItem(wc_cart_fragments_params.fragment_name);
			localStorage.removeItem(wc_cart_fragments_params.fragment_name);
			$(document.body).trigger('wc_fragment_refresh');
		}
	});

	$(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function() {
		thegem_load_minicart_template();
		thegem_load_minicart_empty_template();
	});
})(jQuery, window);
</script>
<?php
}

function thegem_mini_cart_template_placeholder_render() {
	echo '<div class="minicart-template-placeholder"></div>';
}

function thegem_mini_cart_contetns_wrap_start() {
	echo '<div class="minicart-contents-wrap">';
}
function thegem_mini_cart_contetns_wrap_end() {
	echo '</div>';
}


function thegem_mini_cart_global_section(){
	$enabled = thegem_get_option('mini_cart_global_section_enabled');
	$position = thegem_check_array_value(array('after-checkout-buttons', 'before-minicart-table', 'after-minicart-table', 'after-cart-totals'), thegem_get_option('mini_cart_global_section_position'), 'before-minicart-table');
	$template_id = intval(thegem_get_option('mini_cart_global_section'));
	$add_inline_script = false;
	if(!empty($enabled) && $template_id > 0 && get_post($template_id) && defined('ELEMENTOR_VERSION')) {
		switch($position) {
			case 'before-minicart-table':
				add_action('thegem_before_mini_cart_contents', 'thegem_mini_cart_template_placeholder_render');
				add_action('thegem_before_mini_cart_contents', 'thegem_mini_cart_contetns_wrap_start', 1);
				add_action('thegem_after_mini_cart_contents', 'thegem_mini_cart_contetns_wrap_end', 100);
				break;
			case 'after-minicart-table':
				add_action('thegem_after_mini_cart_contents', 'thegem_mini_cart_template_placeholder_render');
				add_action('thegem_before_mini_cart_contents', 'thegem_mini_cart_contetns_wrap_start', 1);
				add_action('thegem_after_mini_cart_contents', 'thegem_mini_cart_contetns_wrap_end', 100);
				break;
			case 'after-cart-totals':
				add_action('woocommerce_widget_shopping_cart_before_buttons', 'thegem_mini_cart_template_placeholder_render', 1); break;
			case 'after-checkout-buttons':
				add_action('woocommerce_widget_shopping_cart_after_buttons', 'thegem_mini_cart_template_placeholder_render', 999); break;
		}
		add_action('wp_footer', 'thegem_mini_cart_global_section_render');
		$add_inline_script = true;
	}
	$empty_enabled = thegem_get_option('mini_cart_empty_global_section_enabled');
	$empty_template_id = intval(thegem_get_option('mini_cart_empty_global_section'));
	if(!empty($empty_enabled) && $empty_template_id > 0 && get_post($empty_template_id) && defined('ELEMENTOR_VERSION')) {
		add_action('wp_footer', 'thegem_mini_cart_empty_global_section_render');
		$add_inline_script = true;
	}
	if($add_inline_script) {
		add_action('wp_footer', 'thegem_mini_cart_global_section_script');
	}
}
add_action('init', 'thegem_mini_cart_global_section'); 
