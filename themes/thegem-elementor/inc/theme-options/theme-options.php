<?php

class ThegemThemeOptions {

    const THEGEM_IMPORTER_PLUGIN = 'thegem-importer-elementor/thegem-importer.php';

    function __construct() {
        global $thegem_use_new_theme_options, $thegem_use_new_page_options;
        // hooks for pageoptions
        if ($thegem_use_new_page_options) {
            add_action('add_meta_boxes', array($this,'addPageOptionsMetaboxCallbacks'));
            add_action('save_post', array($this,'savePageOptions'));
            /*add_filter('thegem_page_title_data_defaults', array($this, 'default_page_data'), 10, 4);
            add_filter('thegem_page_header_data_defaults', array($this, 'default_page_data'), 10, 4);
            add_filter('thegem_page_effects_data_defaults', array($this, 'default_page_data'), 10, 4);
            add_filter('thegem_page_preloader_data_defaults', array($this, 'default_page_data'), 10, 4);
            add_filter('thegem_page_sidebar_data_defaults', array($this, 'default_page_data'), 10, 4);*/
            add_action('admin_menu', array($this,'addTaxonomyOptionsMetaboxCallbacks'));
            add_action('edit_term', array($this,'saveTaxonomyOptions'));
        }

        // hooks for themeoptions
        if ($thegem_use_new_theme_options) {
            add_action('admin_menu', array($this,'addAdminMenuItem'),15);
            add_action('admin_enqueue_scripts', array($this,'enqueuePageFiles'));
            add_action('admin_enqueue_scripts', array($this,'remove_codemirror'), 100);
            add_action('wp_ajax_thegem_theme_options_api', array($this,'ajaxApi'));
            add_action('wp_ajax_thegem_theme_options_credentials', array($this,'filesystemCredentials'));
        }
    }

    function addTaxonomyOptionsMetaboxCallbacks() {
        $taxonomies = get_taxonomies(array('show_ui' => true), 'objects');
        foreach ($taxonomies as $taxonomy) {
            if($taxonomy->publicly_queryable) {
                add_action($taxonomy->name . '_edit_form', array($this,'addTaxonomyOptionsMetabox'), 15, 2);
                //add_action($taxonomy->name . '_edit_form_fields','thegem_taxonomy_edit_form_fields', 15);
            }
        }
    }

    function addPageOptionsMetaboxCallbacks() {
        $post_types = array_merge(array('post', 'page', 'thegem_pf_item', 'thegem_news', 'product'), thegem_get_available_po_custom_post_types());
        foreach ($post_types as $post_type) {
            add_meta_box('thegem_page_options', esc_html__('Page Options', 'thegem'), array($this,'addPageOptionsMetabox'), $post_type, 'normal', 'high');
        }
    }

    function getTaxonomyOptions($post) {
        $page_data = array();

/*        $type = 'term';

        if(get_term_meta($post->term_id, 'thegem_page_data', true)) {
            $page_data = thegem_get_sanitize_admin_page_data($post->term_id, array(), $type);
        } else {
            $page_data_default = thegem_theme_options_get_page_settings('blog');
            $page_data = thegem_get_sanitize_admin_page_data($page_data_default);
            if($page_data_default['title_style'] != 2) {
              $page_data['title_template'] = 0;
            }
            if(!$page_data_default['footer_custom_show']) {
              $page_data['footer_custom'] = 0;
            }
        }*/

        $page_data = thegem_get_sanitize_admin_page_data($post->term_id, array(), 'term');
        $popup_data = get_term_meta($post->term_id, 'thegem_popups_data', true);
        if(!empty($popup_data)) {
            $page_data['popups_item_data'] = $popup_data;
        }
        $page_data['product_archive_item_data'] = thegem_get_sanitize_product_archive_data($post->term_id, array(), 'term');
        $page_data['blog_archive_item_data'] = thegem_get_sanitize_blog_archive_data($post->term_id, array(), 'term');

        return $page_data;
    }

    function addTaxonomyOptionsMetabox($tag, $taxonomy) {
		echo '<div class="postbox taxonomy-box" id="thegem_taxonomy_custom_page_options_boxes2">';
		echo '<h3 class="hndle">' . __('Page Options', 'thegem') . '</h3>';
		echo '<div class="inside">';

        $this->renderPageOptions();

        echo '</div>';
		echo '</div>';

		$taxonomy = strpos($taxonomy, 'pa_') !== 0 ? $taxonomy : 'product_attr';

        $appData = array(
            'isTaxonomy' => true,
            'galleries' => $this->getGalleriesList(),
            'sliders' => $this->getSliders(),
            'options' => $this->getTaxonomyOptions($tag, $taxonomy),
            'taxonomy' => $taxonomy,
            'menus' => $this->getMenus(),
            'patternsUrl' => THEGEM_THEME_URI.'/images/backgrounds/patterns/',
            'iconPacks' =>  $this->getIconPacks(),
        );

        $this->addAppData($appData);
    }

    function addPageOptionsMetabox($post, $type = false) {
        $this->renderPageOptions();

        $appData = array(
            'isPage' => true,
            'galleries' => $this->getGalleriesList(),
            'sliders' => $this->getSliders(),
            'postType' => $post->post_type,
            'options' => $this->getPageOptions($post, $type),
            'menus' => $this->getMenus(),
            'patternsUrl' => THEGEM_THEME_URI.'/images/backgrounds/patterns/',
            'iconPacks' =>  $this->getIconPacks(),

        );

        $this->addAppData($appData);
    }

    function addAdminMenuItem() {
        add_menu_page(esc_html__('TheGem','thegem'), esc_html__('TheGem','thegem'), 'edit_theme_options', 'thegem-dashboard-welcome', [$this,'renderDashboardPage'], '', '3.1');
        add_submenu_page('thegem-dashboard-welcome',esc_html__('TheGem Dashboard','thegem'), esc_html__('Dashboard','thegem'), 'edit_theme_options', 'thegem-dashboard-welcome', [$this,'renderDashboardPage'], 10);
        add_submenu_page('thegem-dashboard-welcome',esc_html__('TheGem Theme Options','thegem'), esc_html__('Theme Options','thegem'), 'edit_theme_options', 'thegem-theme-options', [$this,'renderThemeOptions'], 20);
       //add_submenu_page('thegem-theme-options',esc_html__('TheGem Dashboard','thegem'), esc_html__('D - Plugins','thegem'), 'edit_theme_options', 'thegem-dashboard-plugins', [$this,'renderDashboardPage']);
        //add_submenu_page('thegem-theme-options',esc_html__('TheGem Dashboard','thegem'), esc_html__('D - Demo Import','thegem'), 'edit_theme_options', 'thegem-dashboard-demoimport', [$this,'renderDashboardPage']);
        //add_submenu_page('thegem-theme-options',esc_html__('TheGem Dashboard','thegem'), esc_html__('D - Manual & Support','thegem'), 'edit_theme_options', 'thegem-dashboard-manual-and-support', [$this,'renderDashboardPage']);
        add_submenu_page('thegem-dashboard-welcome',esc_html__('TheGem Dashboard','thegem'), esc_html__('System Status','thegem'), 'edit_theme_options', 'thegem-dashboard-system-status', [$this,'renderdashboardPage'], 50);
        add_submenu_page('thegem-dashboard-welcome',esc_html__('TheGem Dashboard','thegem'), esc_html__('Changelog','thegem'), 'edit_theme_options', 'thegem-dashboard-changelog', [$this,'renderDashboardPage'], 70);
        add_submenu_page('thegem-empty-menu', esc_html__('TheGem Importer','thegem'), esc_html__('TheGem Importer','thegem'), 'edit_theme_options', 'thegem-dashboard-importer', [$this,'renderDashboardPage'], 20);
    }

    function renderDashboardPage() {
        echo '<div id="thegem-themeoptions"></div>';
    }

    function renderThemeOptions() {
        echo '<div id="thegem-themeoptions"></div>';
    }

    function renderPageOptions() {
        wp_nonce_field('thegem_page_options_data', 'thegem_page_options_data_nonce');
        echo '<input type="hidden" name="thegem_page_options_data" id="thegem_page_options_data"/><div id="thegem-themeoptions"></div>';
    }

    function getSettings() {
        $settings = get_option('thegem_theme_options_settings');
        if (!$settings) {
            $settings = array (
                'theme' => 'light',
                'background_image_gallery' => array(),
                'colorpicker_favorites' => array('default'=>array())
            );
        }

        return $settings;
    }

    function apiSaveSettings($request) {
        update_option('thegem_theme_options_settings',$request['settings']);
    }

    function saveTaxonomyOptions($term_id) {
        if (!isset($_POST['thegem_page_options_data_nonce']) ||
            !wp_verify_nonce($_POST['thegem_page_options_data_nonce'], 'thegem_page_options_data')) {
            return;
        }


        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if(!current_user_can('edit_term', $term_id)) {
            return;
        }

        $options=json_decode(stripslashes_deep($_POST['thegem_page_options_data']),true);

        if(isset($options['popups_item_data'])) {
            update_term_meta($term_id, 'thegem_popups_data', $options['popups_item_data']);
            unset($options['popups_item_data']);
        }

        $page_data = thegem_get_sanitize_admin_page_data(0, $options);
        $product_archive_item_data = thegem_get_sanitize_product_archive_data(0, $options['product_archive_item_data']);
        $blog_archive_item_data = thegem_get_sanitize_blog_archive_data(0, $options['blog_archive_item_data']);

        delete_term_meta($term_id, 'thegem_taxonomy_custom_page_options');
        update_term_meta($term_id, 'thegem_page_data', $page_data);
        update_term_meta($term_id, 'thegem_product_archive_page_data', $product_archive_item_data);
        update_term_meta($term_id, 'thegem_blog_archive_page_data', $blog_archive_item_data);
    }

    function savePageOptions($post_id) {

        if (!isset($_POST['thegem_page_options_data_nonce']) ||
            !wp_verify_nonce($_POST['thegem_page_options_data_nonce'], 'thegem_page_options_data')) {
            return;
        }


        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if(isset($_POST['post_type']) && in_array($_POST['post_type'], array_merge(array('post', 'page', 'thegem_pf_item', 'thegem_news', 'product'), thegem_get_available_po_custom_post_types()))) {
            if(!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if(!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        $options=json_decode(stripslashes_deep($_POST['thegem_page_options_data']),true);

        $post = get_post($post_id);

        switch ($post->post_type) {
            case 'post':
                update_post_meta($post_id, 'thegem_post_general_item_data', $options['post_item_data']);
                update_post_meta($post_id, 'thegem_show_featured_posts_slider', $options['post_item_data']['show_featured_posts_slider']);
                unset($options['post_item_data']);

                $data = thegem_get_sanitize_admin_post_elements_data($post_id, $options['post_elements_data']);
                update_post_meta($post_id, 'thegem_post_page_elements_data', $data);
                unset($options['post_elements_data']);
                break;

            case 'thegem_pf_item':

                //save grid appearance gif
                if (isset($options['portfolio_item_data']['grid_appearance_gif'])) {
                    $options['portfolio_item_data']['grid_appearance_gif'] = $options['portfolio_item_data']['grid_appearance_gif']['id'];
                }

                $portfolio_item_data = thegem_get_sanitize_pf_item_data(0, $options['portfolio_item_data']);
                update_post_meta($post_id, 'thegem_portfolio_item_data', $portfolio_item_data);
                unset($options['portfolio_item_data']);

                $data = thegem_get_sanitize_pf_item_elements_data($post_id, $options['portfolio_elements_data']);
                update_post_meta($post_id, 'thegem_pf_item_page_elements_data', $data);
                unset($options['portfolio_elements_data']);

                //save meta data
                $portfolio_meta = $portfolio_item_data['meta'];
                if (!empty($portfolio_meta)){
                    foreach ($portfolio_meta as $meta) {
                        if (!empty($meta['value'])){
                            update_post_meta($post_id, $meta['key'], $meta['value']);
                        } else{
                            delete_post_meta($post_id, $meta['key'], $meta['value']);
                        }
                    }
                }

                break;

            case 'product':
                $product_item_data = thegem_get_sanitize_product_page_data(0, $options['product_item_data']);
                $size_guide = thegem_get_sanitize_product_size_guide_data(0, array(
                    'size_guide' => $product_item_data['size_guide'],
                    'custom_image' => $product_item_data['size_guide_image'],
                    'custom_text' => $product_item_data['size_guide_text'],
                ));
                update_post_meta($post_id, 'thegem_product_size_guide_data', $size_guide);
                $highlight = thegem_get_sanitize_product_featured_data(0, array(
                    'highlight' => $product_item_data['highlight'],
                    'highlight_type' => $product_item_data['highlight_type'],
                ));
                update_post_meta($post_id, 'thegem_product_featured_data', $highlight);
                update_post_meta($post_id, 'thegem_product_disable_hover', !empty($product_item_data['thegem_product_disable_hover']));

                $product_page = thegem_get_sanitize_product_page_data(0, array(
                    'product_layout_settings' => $product_item_data['product_layout_settings'],
                    'product_layout_source' => $product_item_data['product_layout_source'],
                    'product_builder_template' => $product_item_data['product_builder_template'],
                    'product_gallery' => $product_item_data['product_gallery'],
                    'product_gallery_type' => $product_item_data['product_gallery_type'],
                    'product_gallery_thumb_on_mobile' => $product_item_data['product_gallery_thumb_on_mobile'],
                    'product_gallery_thumb_scroll_by_hover' => $product_item_data['product_gallery_thumb_scroll_by_hover'],
                    'product_gallery_thumb_count' => $product_item_data['product_gallery_thumb_count'],
                    'product_gallery_thumb_position' => $product_item_data['product_gallery_thumb_position'],
                    'product_gallery_column_position' => $product_item_data['product_gallery_column_position'],
                    'product_gallery_column_width' => $product_item_data['product_gallery_column_width'],
                    'product_gallery_show_image' => $product_item_data['product_gallery_show_image'],
                    'product_gallery_image_ratio' => $product_item_data['product_gallery_image_ratio'],
                    'product_gallery_grid_image_size' => $product_item_data['product_gallery_grid_image_size'],
                    'product_gallery_grid_image_ratio' => $product_item_data['product_gallery_grid_image_ratio'],
                    'product_gallery_zoom' => $product_item_data['product_gallery_zoom'],
                    'product_gallery_lightbox' => $product_item_data['product_gallery_lightbox'],
                    'product_gallery_labels' => $product_item_data['product_gallery_labels'],
                    'product_gallery_label_sale' => $product_item_data['product_gallery_label_sale'],
                    'product_gallery_label_new' => $product_item_data['product_gallery_label_new'],
                    'product_gallery_label_out_stock' => $product_item_data['product_gallery_label_out_stock'],
                    'product_gallery_auto_height' => $product_item_data['product_gallery_auto_height'],
                    'product_gallery_elements_color' => $product_item_data['product_gallery_elements_color'],
                    'product_gallery_grid_columns' => $product_item_data['product_gallery_grid_columns'],
                    'product_gallery_grid_gaps' => $product_item_data['product_gallery_grid_gaps'],
                    'product_gallery_grid_gaps_hide' => $product_item_data['product_gallery_grid_gaps_hide'],
                    'product_gallery_grid_top_margin' => $product_item_data['product_gallery_grid_top_margin'],
                    'product_gallery_video_autoplay' => $product_item_data['product_gallery_video_autoplay'],
                    'product_page_layout' => $product_item_data['product_page_layout'],
                    'product_page_layout_style' => $product_item_data['product_page_layout_style'],
                    'product_page_layout_centered' => $product_item_data['product_page_layout_centered'],
                    'product_page_layout_centered_top_margin' => $product_item_data['product_page_layout_centered_top_margin'],
                    'product_page_layout_centered_boxed' => $product_item_data['product_page_layout_centered_boxed'],
                    'product_page_layout_centered_boxed_color' => $product_item_data['product_page_layout_centered_boxed_color'],
                    'product_page_layout_background' => $product_item_data['product_page_layout_background'],
                    'product_page_layout_preset' => $product_item_data['product_page_layout_preset'],
                    'product_page_layout_fullwidth' => $product_item_data['product_page_layout_fullwidth'],
                    'product_page_layout_sticky' => $product_item_data['product_page_layout_sticky'],
                    'product_page_layout_sticky_offset' => $product_item_data['product_page_layout_sticky_offset'],
                    'product_page_skeleton_loader' => $product_item_data['product_page_skeleton_loader'],
                    'product_page_layout_title_area' => $product_item_data['product_page_layout_title_area'],
                    'product_page_ajax_add_to_cart' => $product_item_data['product_page_ajax_add_to_cart'],
                    'product_page_desc_review_source' => $product_item_data['product_page_desc_review_source'],
                    'product_page_desc_review_layout' => $product_item_data['product_page_desc_review_layout'],
                    'product_page_desc_review_layout_tabs_style' => $product_item_data['product_page_desc_review_layout_tabs_style'],
                    'product_page_desc_review_layout_tabs_alignment' => $product_item_data['product_page_desc_review_layout_tabs_alignment'],
                    'product_page_desc_review_layout_acc_position' => $product_item_data['product_page_desc_review_layout_acc_position'],
                    'product_page_desc_review_layout_one_by_one_description_background' => $product_item_data['product_page_desc_review_layout_one_by_one_description_background'],
                    'product_page_desc_review_layout_one_by_one_additional_info_background' => $product_item_data['product_page_desc_review_layout_one_by_one_additional_info_background'],
                    'product_page_desc_review_layout_one_by_one_reviews_background' => $product_item_data['product_page_desc_review_layout_one_by_one_reviews_background'],
                    'product_page_desc_review_description' => $product_item_data['product_page_desc_review_description'],
                    'product_page_desc_review_description_title' => $product_item_data['product_page_desc_review_description_title'],
                    'product_page_desc_review_additional_info' => $product_item_data['product_page_desc_review_additional_info'],
                    'product_page_desc_review_additional_info_title' => $product_item_data['product_page_desc_review_additional_info_title'],
                    'product_page_desc_review_reviews' => $product_item_data['product_page_desc_review_reviews'],
                    'product_page_desc_review_reviews_title' => $product_item_data['product_page_desc_review_reviews_title'],
                    'product_page_button_add_to_cart_text' => $product_item_data['product_page_button_add_to_cart_text'],
                    'product_page_button_add_to_cart_icon_show' => $product_item_data['product_page_button_add_to_cart_icon_show'],
                    'product_page_button_add_to_cart_icon' => $product_item_data['product_page_button_add_to_cart_icon'],
                    'product_page_button_add_to_cart_icon_pack' => $product_item_data['product_page_button_add_to_cart_icon_pack'],
                    'product_page_button_add_to_cart_icon_position' => $product_item_data['product_page_button_add_to_cart_icon_position'],
                    'product_page_button_add_to_cart_border_width' => $product_item_data['product_page_button_add_to_cart_border_width'],
                    'product_page_button_add_to_cart_border_radius' => $product_item_data['product_page_button_add_to_cart_border_radius'],
                    'product_page_button_add_to_cart_color' => $product_item_data['product_page_button_add_to_cart_color'],
                    'product_page_button_add_to_cart_color_hover' => $product_item_data['product_page_button_add_to_cart_color_hover'],
                    'product_page_button_add_to_cart_background' => $product_item_data['product_page_button_add_to_cart_background'],
                    'product_page_button_add_to_cart_background_hover' => $product_item_data['product_page_button_add_to_cart_background_hover'],
                    'product_page_button_add_to_cart_border_color' => $product_item_data['product_page_button_add_to_cart_border_color'],
                    'product_page_button_add_to_cart_border_color_hover' => $product_item_data['product_page_button_add_to_cart_border_color_hover'],
                    'product_page_button_add_to_wishlist_icon' => $product_item_data['product_page_button_add_to_wishlist_icon'],
                    'product_page_button_add_to_wishlist_icon_pack' => $product_item_data['product_page_button_add_to_wishlist_icon_pack'],
                    'product_page_button_add_to_wishlist_color' => $product_item_data['product_page_button_add_to_wishlist_color'],
                    'product_page_button_add_to_wishlist_color_hover' => $product_item_data['product_page_button_add_to_wishlist_color_hover'],
                    'product_page_button_add_to_wishlist_color_filled' => $product_item_data['product_page_button_add_to_wishlist_color_filled'],
                    'product_page_button_added_to_wishlist_icon' => $product_item_data['product_page_button_added_to_wishlist_icon'],
                    'product_page_button_added_to_wishlist_icon_pack' => $product_item_data['product_page_button_added_to_wishlist_icon_pack'],
                    'product_page_button_clear_attributes_text' => $product_item_data['product_page_button_clear_attributes_text'],
                    'product_page_elements_prev_next' => $product_item_data['product_page_elements_prev_next'],
                    'product_page_elements_preview_on_hover' => $product_item_data['product_page_elements_preview_on_hover'],
                    'product_page_elements_back_to_shop' => $product_item_data['product_page_elements_back_to_shop'],
                    'product_page_elements_back_to_shop_link' => $product_item_data['product_page_elements_back_to_shop_link'],
                    'product_page_elements_back_to_shop_link_custom_url' => $product_item_data['product_page_elements_back_to_shop_link_custom_url'],
                    'product_page_elements_title' => $product_item_data['product_page_elements_title'],
                    'product_page_elements_title_html' => $product_item_data['product_page_elements_title_html'],
                    'product_page_elements_attributes' => $product_item_data['product_page_elements_attributes'],
                    'product_page_elements_attributes_data' => $product_item_data['product_page_elements_attributes_data'],
                    'product_page_elements_reviews' => $product_item_data['product_page_elements_reviews'],
                    'product_page_elements_reviews_text' => $product_item_data['product_page_elements_reviews_text'],
                    'product_page_elements_price' => $product_item_data['product_page_elements_price'],
                    'product_page_elements_price_strikethrough' => $product_item_data['product_page_elements_price_strikethrough'],
                    'product_page_elements_description' => $product_item_data['product_page_elements_description'],
                    'product_page_elements_stock_amount' => $product_item_data['product_page_elements_stock_amount'],
                    'product_page_elements_stock_amount_text' => $product_item_data['product_page_elements_stock_amount_text'],
                    'product_page_elements_size_guide' => $product_item_data['product_page_elements_size_guide'],
                    'product_page_elements_sku' => $product_item_data['product_page_elements_sku'],
                    'product_page_elements_sku_title' => $product_item_data['product_page_elements_sku_title'],
                    'product_page_elements_categories' => $product_item_data['product_page_elements_categories'],
                    'product_page_elements_categories_title' => $product_item_data['product_page_elements_categories_title'],
                    'product_page_elements_tags' => $product_item_data['product_page_elements_tags'],
                    'product_page_elements_tags_title' => $product_item_data['product_page_elements_tags_title'],
                    'product_page_elements_share' => $product_item_data['product_page_elements_share'],
                    'product_page_elements_share_title' => $product_item_data['product_page_elements_share_title'],
                    'product_page_elements_share_facebook' => $product_item_data['product_page_elements_share_facebook'],
                    'product_page_elements_share_twitter' => $product_item_data['product_page_elements_share_twitter'],
                    'product_page_elements_share_pinterest' => $product_item_data['product_page_elements_share_pinterest'],
                    'product_page_elements_share_tumblr' => $product_item_data['product_page_elements_share_tumblr'],
                    'product_page_elements_share_linkedin' => $product_item_data['product_page_elements_share_linkedin'],
                    'product_page_elements_share_reddit' => $product_item_data['product_page_elements_share_reddit'],
                    'product_page_elements_upsell' => $product_item_data['product_page_elements_upsell'],
                    'product_page_elements_upsell_title' => $product_item_data['product_page_elements_upsell_title'],
                    'product_page_elements_upsell_title_alignment' => $product_item_data['product_page_elements_upsell_title_alignment'],
                    'product_page_elements_upsell_items' => $product_item_data['product_page_elements_upsell_items'],
                    'product_page_elements_upsell_columns_desktop' => $product_item_data['product_page_elements_upsell_columns_desktop'],
                    'product_page_elements_upsell_columns_tablet' => $product_item_data['product_page_elements_upsell_columns_tablet'],
                    'product_page_elements_upsell_columns_mobile' => $product_item_data['product_page_elements_upsell_columns_mobile'],
                    'product_page_elements_upsell_columns_100' => $product_item_data['product_page_elements_upsell_columns_100'],
                    'product_page_elements_related' => $product_item_data['product_page_elements_related'],
                    'product_page_elements_related_title' => $product_item_data['product_page_elements_related_title'],
                    'product_page_elements_related_title_alignment' => $product_item_data['product_page_elements_related_title_alignment'],
                    'product_page_elements_related_items' => $product_item_data['product_page_elements_related_items'],
                    'product_page_elements_related_columns_desktop' => $product_item_data['product_page_elements_related_columns_desktop'],
                    'product_page_elements_related_columns_tablet' => $product_item_data['product_page_elements_related_columns_tablet'],
                    'product_page_elements_related_columns_mobile' => $product_item_data['product_page_elements_related_columns_mobile'],
                    'product_page_additional_tabs' => $product_item_data['product_page_additional_tabs'],
                    'product_page_additional_tabs_data' => wp_slash($product_item_data['product_page_additional_tabs_data']),
                ));
                update_post_meta($post_id, 'thegem_product_page_data', $product_page);

                unset($options['product_item_data']);
                break;
        }

        if(isset($options['popups_item_data'])) {
            update_post_meta($post_id, 'thegem_popups_data', $options['popups_item_data']);
            unset($options['popups_item_data']);
        }

        // Save custom fields
        if(isset($options['custom_fields_item_data'])) {
            $custom_fields = $options['custom_fields_item_data'];
            if (!empty($custom_fields)){
                foreach ($custom_fields as $field) {
                    if (!empty($field['value'])){
                        update_post_meta($post_id, $field['key'], $field['value']);
                    } else{
                        delete_post_meta($post_id, $field['key'], $field['value']);
                    }
                }
            }
        }

        $page_data = thegem_get_sanitize_admin_page_data(0, $options);

        update_post_meta($post_id, 'thegem_page_data', $page_data);
    }

    function getBackupsInfo() {
        $backups=get_option('thegem_theme_options_backup2');

        $info=array();
        if ($backups) {
            foreach($backups as $idx=>$backup) {
                array_push($info,array('id'=>$idx,'dt'=>date('Y-m-d H:i', $backup['dt'])));
            }
        }

        return $info;
    }

    function processPageFileUrl($url) {
        return THEGEM_THEME_URI.'/'.$url;
    }

    function apiPurgeThumbnailsCache() {
        $meta_key = 'thegem_image_src_cache';
        delete_post_meta_by_key($meta_key);
        $meta_key = 'thegem_image_regenerated';
        delete_post_meta_by_key($meta_key);

        return array();
    }

    function apiApplyCustomField($request) {
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $request['pt'],
        ));

        foreach ($posts as $post) {
            if (!empty($request['value'])){
                update_post_meta($post->ID, $request['key'], $request['value']);
            } else{
                delete_post_meta($post->ID, $request['key'], $request['value']);
            }
        }

        return $request;
    }

    function apiApplyProjectDetails($request) {
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $request['pt'],
        ));

        foreach ($posts as $post) {
            if (!empty($request['value'])){
                update_post_meta($post->ID, $request['key'], $request['value']);
            } else{
                delete_post_meta($post->ID, $request['key'], $request['value']);
            }
        }

        return $request;
    }

    function apiActivate($request) {
        thegem_activate_elementor_settings();
        delete_option('thegem_activation');
        if(!empty($request['purchaseCode'])) {
            $theme_options = get_option('thegem_theme_options');
            $theme_options['purchase_code'] = $request['purchaseCode'];
            update_option('thegem_theme_options', $theme_options);
            thegem_get_option(false, false, false, true);
            $response_p = wp_remote_get(add_query_arg(array('code' => $request['purchaseCode'], 'info'=>thegem_get_activation_info(), 'site_url' => get_site_url(), 'type' => 'elementor'), esc_url('http://democontent.codex-themes.com/av_validate_code.php')), array('timeout' => 20));

            if(is_wp_error($response_p)) {
                return array('activated' => false, 'error' => __('Some troubles with connecting to TheGem server.', 'thegem'));
            } else {
                $rp_data = json_decode($response_p['body'], true);
                if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
                    update_option('thegem_activation', 1);
                    update_option('thegem_print_google_code', 1);
                    return array('activated' => true);
                } else {
                    return array('activated' => false, 'error' => isset($rp_data['message']) ? $rp_data['message'] : __('The purchase code you have entered is not valid. TheGem has not been activated.', 'thegem'));
                }
            }
        } else {
            return array('activated' => false, 'error' => __('Purchase code is empty. (E01)', 'thegem'));
        }
    }

    function apiBackup() {
        $backups=get_option('thegem_theme_options_backup2');

        if (!$backups) {
            $backups=array();
        }

        array_unshift($backups,array('dt'=>time(),'data'=>$this->getFullOptions()));
        $backups=array_slice($backups,0,5);

        update_option('thegem_theme_options_backup2',$backups);

        return array('backups'=>$this->getBackupsInfo());
    }

    function apiRegenerateCss($request) {
        $cssResult=$this->thegem_generate_custom_css();

        if ($cssResult!==true) {
            return array('status'=>$cssResult);
        };

        return array();
    }

    function apiRegenerateEmptyCss($request) {
        $thegem_theme = wp_get_theme(wp_get_theme()->get('Template'));
        update_option('thegem_generate_empty_css_forced_redirect_done',$thegem_theme->get('Version'));

        $cssResult=$this->thegem_generate_custom_css(_('Custom.css file is missing in your TheGem installation. Custom.css is important for proper functioning of TheGem. Please regenerate it now. All your settings will remain, this action will not affect your setup.'));

        if ($cssResult!==true) {
            return array('status'=>$cssResult);
        };

        return array();
    }

    function apiRestore($request) {
        $backups=get_option('thegem_theme_options_backup2');

        $backup=$backups[$request['id']]['data'];
        if ($backup) {
            $page_options=$backup['page_options'];
            unset($backup['page_options']);

            thegem_check_activation($backup);
            update_option('thegem_theme_options', $backup);
            foreach($page_options as $page=>$options) {
                thegem_theme_options_set_page_settings($page, $options);
            }

            delete_option( 'rewrite_rules' );

            thegem_get_option(false, false, false, true);
            $cssResult=$this->thegem_generate_custom_css();

            if ($cssResult!==true) {
                return array('status'=>$cssResult);
            };
        }

        return array('options'=>$this->getFullOptions());
    }

    function setPopupOptions($page, $options) {
        return array(
            'id' => esc_html($options['id']),
            'active' => esc_html($options['active']),
            'template' => !empty($options['template']) ? esc_html($options['template']) : '',
            'triggers' => !empty($options['triggers']) ? $options['triggers'] : '',
            'show_after_x_page_views' => !empty($options['show_after_x_page_views']) ? $options['show_after_x_page_views'] : '',
            'show_page_views' => !empty($options['show_page_views']) ? esc_html($options['show_page_views']) : '',
            'show_up_to_x_times' => !empty($options['show_up_to_x_times']) ? $options['show_up_to_x_times'] : '',
            'show_popup_count' => !empty($options['show_popup_count']) ? esc_html($options['show_popup_count']) : '',
            'cookie_time' => !empty($options['cookie_time']) ? esc_html($options['cookie_time']) : '',
            'hide_for_logged_in_users' => !empty($options['hide_for_logged_in_users']) ? $options['hide_for_logged_in_users'] : '',
            'show_on_mobile' => !empty($options['show_on_mobile']) ? $options['show_on_mobile'] : '',
            'show_on_tablet' => !empty($options['show_on_tablet']) ? $options['show_on_tablet'] : '',
            'display' => !empty($options['display']) ? $options['display'] : '',
            'images_preloading' => !empty($options['images_preloading']) ? $options['images_preloading'] : '',
        );
    }

    function modifyPopupPostName($page){
        $result = [];
        $post_name = explode('_', $page);
        foreach ($post_name as $name) {
            $result[] = ucfirst($name);
        }
        return implode('', $result);
    }

    function apiSave($request) {
        $theme_options = $request['options'];
        unset($theme_options['page_options']);

        $thegem_theme = wp_get_theme(wp_get_theme()->get('Template'));
        $theme_options['theme_version'] = $thegem_theme->get('Version');

        if(thegem_get_current_language()) {
            $ml_options = thegem_translated_options();
            foreach ($ml_options as $ml_option) {
                $value = thegem_get_option($ml_option, false, true);
                if(!is_array($value)) {
                    if(thegem_get_default_language()) {
                        $value = array(thegem_get_default_language() => $value);
                    }
                }
                $value[thegem_get_current_language()] = $theme_options[$ml_option];
                $theme_options[$ml_option] = $value;
            }
        }

        thegem_check_activation($theme_options);

        // Save Socials
        $customSocials = array();
        foreach($theme_options['customSocials'] as $social) {
            $saveSocial = array(
                'id' => sanitize_title($social['id']),
                'name' => !empty($social['name']) ? esc_html($social['name']) : '',
                'icon_pack' => !empty($social['icon_pack']) ? thegem_check_array_value(array('elegant', 'material', 'fontawesome', 'userpack'), $social['icon_pack'], 'elegant') : 'elegant',
                'icon' => !empty($social['icon']) ? esc_html($social['icon']) : '',
                'rounded_icon' => !empty($social['rounded_icon']) ? esc_html($social['rounded_icon']) : '',
                'squared_icon' => !empty($social['squared_icon']) ? esc_html($social['squared_icon']) : '',
                'color' => !empty($social['color']) ? esc_html($social['color']) : '',
            );

            if (!$saveSocial['id'] && trim($social['name'])!='') {
                $saveSocial['id']='social-'.preg_replace('%[^a-z0-9]%','',strtolower(trim($social['name']))).'-'.time();
            }

            if ($saveSocial['id']) {
                $customSocials[] = $saveSocial;
                $theme_options[$saveSocial['id'].'_active'] = $social['active'];
                $theme_options[$saveSocial['id'].'_link'] = $social['link'];
            }
        }
        unset($theme_options['customSocials']);
        update_option('thegem_additionals_socials', $customSocials);

        // Save Popups
        foreach($request['options']['page_options'] as $page=>$options) {
            $thegemPopupsLvl = array();
            $thegemPopupsPostName = $this->modifyPopupPostName($page);
            if (!empty($theme_options['thegemPopups'.$thegemPopupsPostName])) {
                foreach($theme_options['thegemPopups'.$thegemPopupsPostName] as $popup) {
                    $savePopupLvl = $this->setPopupOptions($page, $popup);

                    if ($savePopupLvl['id']) {
                        $thegemPopupsLvl[] = $savePopupLvl;
                    }
                }
            }
            unset($theme_options['thegemPopups'.$thegemPopupsPostName]);
            update_option('thegem_popups_'.$page, $thegemPopupsLvl);
        }

        $thegemPopups = array();
        foreach($theme_options['thegemPopups'] as $popup) {
            $savePopup = $this->setPopupOptions('', $popup);

            if ($savePopup['id']) {
                $thegemPopups[] = $savePopup;
            }
        }
        unset($theme_options['thegemPopups']);
        unset($theme_options['thegemPopupsPost']);
        unset($theme_options['thegemPopupsDefault']);
        unset($theme_options['thegemPopupsPortfolio']);
        unset($theme_options['thegemPopupsProduct']);
        unset($theme_options['thegemPopupsProductCategories']);
        unset($theme_options['thegemPopupsBlog']);
        unset($theme_options['thegemPopupsSearch']);
        update_option('thegem_popups', $thegemPopups);

        // Save Portfolio Project Details
        $projectDetails = array();
        if (!empty($theme_options['portfolio_project_details_data'])) {
            foreach (json_decode($theme_options['portfolio_project_details_data']) as $pd){
                $pd->key = '_thegem_cf_'.str_replace( '-', '_', sanitize_title($pd->title));
                $projectDetails[] = $pd;
            }
            $theme_options['portfolio_project_details_data'] = json_encode($projectDetails);
        }

        if(!thegem_get_option('team_persons_page_enable', false, true) && !empty($theme_options['team_persons_page_enable'])) {
            $cpt = get_option('elementor_cpt_support') ? get_option('elementor_cpt_support') : array();
            $cpt[] = 'thegem_templates';
            update_option('elementor_cpt_support', $cpt);
            flush_rewrite_rules();
        }

        update_option('thegem_theme_options', $theme_options);

        foreach($request['options']['page_options'] as $page=>$options) {
            thegem_theme_options_set_page_settings($page, $options);
        }
        delete_option( 'rewrite_rules' );

        thegem_get_option(false, false, false, true);
        $cssResult=$this->thegem_generate_custom_css();

        if ($cssResult!==true) {
            return array('status'=>$cssResult);
        };

        return array();
    }

    function applyPageSettings($request) {
        $type = $request['type'];
        $offset = $request['offset'];
        $group = isset($request['group']) ? $request['group'] : false;
        $group_settings = thegem_get_options_by_group($request['group']);
        $group_array = array_fill_keys($group_settings, 0);

        switch ($type) {
            case 'default':
                $typeName = 'Page';
                $offset = thegem_apply_options_page_settings('page', array_intersect_key(thegem_theme_options_get_page_settings('default'), $group_array), $offset, null, $group);
                break;
            case 'post':
                $typeName = 'Post';
                $offset = thegem_apply_options_page_settings('post', array_intersect_key(thegem_theme_options_get_page_settings('post'), $group_array), $offset, null, $group);
                break;
            case 'portfolio':
                $typeName = 'Portfolio';
                $offset = thegem_apply_options_page_settings('thegem_pf_item', array_intersect_key(thegem_theme_options_get_page_settings('portfolio'), $group_array), $offset, null, $group);
                break;
            case 'product':
                $typeName = 'Product';
                $offset = thegem_apply_options_page_settings('product', array_intersect_key(thegem_theme_options_get_page_settings('product'), $group_array), $offset, null, $group);
                break;
            case 'product_layout':
                $typeName = 'Product layout';
                $offset = thegem_apply_options_page_settings('product_layout', array_intersect_key(thegem_theme_options_get_page_settings('product_layout'), $group_array), $offset, null, $group);
                break;
            case 'product_categories':
                $typeName = 'Product categories';
                $offset = thegem_apply_options_page_settings('product_cats', array_intersect_key(thegem_theme_options_get_page_settings('product_categories'), $group_array), $offset, null, $group);
                break;
            case 'blog':
                $typeName = 'Blog categories';
                $offset = thegem_apply_options_page_settings('cats', array_intersect_key(thegem_theme_options_get_page_settings('blog'), $group_array), $offset, null, $group);
                break;
        }

        $data = array ( 'status' => true, 'offset'=> $offset);
        if (!$offset) {
            $data['message'] = __($typeName.' '.'settings applied successfully.', 'thegem');
        }

        return $data;
    }

    function optimizerAllDeactivate($request) {
        deactivate_plugins(['wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php', 'wp-rocket/wp-rocket.php', 'autoptimize/autoptimize.php'], true);
        delete_option('thegem_enabled_wprocket_autoptimize');

        return array('optimizer' => $this->getOptimizerInfo());
    }

    function optimizerWPRocketDeactivate($request) {
        deactivate_plugins(['wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php', 'wp-rocket/wp-rocket.php'], true);

        return array('optimizer' => $this->getOptimizerInfo());
    }

    function optimizerAutoptimizeDeactivate($request) {
        deactivate_plugins(['autoptimize/autoptimize.php'], true);

        return array('optimizer' => $this->getOptimizerInfo());
    }

    function optimizerWPRocketActivate($request) {
        $res = activate_plugin('wp-rocket/wp-rocket.php');

        if ($res !== NULL) {
            return array('message' => __('WP Rocket plugin has not been found in your installation. Please install and activate your own copy of WP Rocket plugin in order to activate caching.', 'thegem'));
        }

        $res = activate_plugin('wp-rocket-disable-google-font-optimization/wp-rocket-disable-google-font-optimization.php');

        if ($res !== NULL) {
            return array('message' => __('WP Rocket disable google font optimization" plugin has not been found. Please reinstall and reactivate this plugin.', 'thegem'));
        }

        return array('optimizer' => $this->getOptimizerInfo());
    }

    function optimizerAutoptimizeActivate($request) {
        $res = activate_plugin('autoptimize/autoptimize.php');
        if ($res !== NULL) {
            return array('message' => __('Activation of Autoptimize plugin failed, please use "Redo one-click optimization" button to reinstall and reactivate this plugin.', 'thegem'));
        }

        return array('optimizer' => $this->getOptimizerInfo());
    }

    function optimizerRestore($request) {
        global $wpdb;

        $data = get_option('thegem_optimizers_backup_settings');

        if ($data['wp_rocket_settings']) {
            update_option('wp_rocket_settings', $data['wp_rocket_settings']);
        }

        if ($data['autoptimize']) {
            $oldAutoptimizeOptions = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'autoptimize_%'" );
            foreach($oldAutoptimizeOptions as $option ) {
                delete_option( $option->option_name );
            }

            foreach($data['autoptimize'] as $optName => $optValue) {
                update_option($optName, $optValue);
            }
        }

        delete_option('thegem_enabled_wprocket_autoptimize');


        return array('optimizer' => $this->getOptimizerInfo());
    }

    function ajaxApi() {

        check_ajax_referer( 'thegem_theme_options_api', 'security' );

        $request = json_decode(file_get_contents('php://input'), true);

        if(current_user_can('edit_theme_options')) {
            switch($request['to_action']) {
                case 'save':
                    $response = $this->apiSave($request);
                    break;
                case 'saveSettings':
                    $response = $this->apiSaveSettings($request);
                    break;
                case 'purgeThumbnailsCache':
                    $response = $this->apiPurgeThumbnailsCache();
                    break;
                case 'backup':
                    $response = $this->apiBackup($request);
                    break;
                case 'restore':
                    $response = $this->apiRestore($request);
                    break;
                case 'regenerateCss':
                    $response = $this->apiRegenerateCss($request);
                    break;
                case 'regenerateEmptyCss':
                    $response = $this->apiRegenerateEmptyCss($request);
                    break;
                case 'activate':
                    $response = $this->apiActivate($request);
                    break;
                case 'applyPageSettings':
                    $response = $this->applyPageSettings($request);
                    break;
                case 'optimizerAllDeactivate':
                    $response = $this->optimizerAllDeactivate($request);
                    break;
                case 'optimizerWPRocketActivate':
                    $response = $this->optimizerWPRocketActivate($request);
                    break;
                case 'optimizerAutoptimizeActivate':
                    $response = $this->optimizerAutoptimizeActivate($request);
                    break;
                case 'optimizerWPRocketDeactivate':
                    $response = $this->optimizerWPRocketDeactivate($request);
                    break;
                case 'optimizerAutoptimizeDeactivate':
                    $response = $this->optimizerAutoptimizeDeactivate($request);
                    break;
                case 'optimizerRestore':
                    $response = $this->optimizerRestore($request);
                    break;
                case 'applyCustomField':
                    $response = $this->apiApplyCustomField($request);
                    break;
                case 'applyProjectDetails':
                    $response = $this->apiApplyProjectDetails($request);
                    break;
                default:
                    $response['status'] = false;
            }
        } else {
            $response['status'] = false;
        }

        if (!isset($response['status'])) {
            $response['status'] = true;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    function getFullOptions() {
        $options = get_option('thegem_theme_options');

        $ml_options = thegem_translated_options();
        foreach ($ml_options as $ml_option) {
            if(isset($options[$ml_option]) && is_array($options[$ml_option])) {
                if(thegem_get_current_language()) {
                    if(isset($options[$ml_option][thegem_get_current_language()])) {
                        $options[$ml_option] = $options[$ml_option][thegem_get_current_language()];
                    } elseif(thegem_get_default_language() && isset($options[$ml_option][thegem_get_default_language()])) {
                        $options[$ml_option] = $options[$ml_option][thegem_get_default_language()];
                    } else {
                        $options[$ml_option] = '';
                    }
                } else {
                    $options[$ml_option] = reset($options[$ml_option]);
                }
            }
        }

        // Get Socials
        $socials = get_option('thegem_additionals_socials');
        if (!$socials) $socials = array();

        $socials = array_values($socials);
        foreach($socials as $k=>$v) {
            $socials[$k]['active']=isset($options[$v['id'].'_active']) && $options[$v['id'].'_active'] ? '1' : '';
            $socials[$k]['link']=isset($options[$v['id'].'_link']) && $options[$v['id'].'_link'] ? $options[$v['id'].'_link'] : '';
            $socials[$k]['key']=$k;
        }
        $options['customSocials'] = $socials;

        // Get Popups
        $post_types = ['global', 'post', 'default', 'portfolio', 'product', 'product_categories', 'blog', 'search'];
        foreach ($post_types as $pt) {
            if ($pt == 'global'){
                $popups = get_option('thegem_popups');
            } else{
                $popups = get_option('thegem_popups_'.$pt);
            }

            if (!$popups) $popups = array();

            $popups = array_values($popups);
            $popupsPostName = $this->modifyPopupPostName($pt);
            foreach ($popups as $k => $v) {
                $popups[$k]['active'] = $v['active'] ? '1' : '';
                $popups[$k]['key'] = $k;
                $popups[$k]['images_preloading'] = !empty($v['images_preloading']) ? $v['images_preloading'] : '';
            }

            if ($pt == 'global'){
                $options['thegemPopups'] = $popups;
            } else{
                $options['thegemPopups'.$popupsPostName] = $popups;
            }
        }

        // Get custom post types & taxonomies settings
        $custom_post_types_taxonomies = array_merge(
            thegem_get_list_po_custom_post_types(),
            thegem_get_list_po_custom_taxonomies()
        );
        $custom_post_types_taxonomies_settings = array();
        foreach ($custom_post_types_taxonomies as $type) {
            $custom_post_types_taxonomies_settings[$type] = thegem_theme_options_get_page_settings($type);
        }

        return array_merge(
            $options,
            array(
                'page_options'=>array_merge(
                    array(
                        'default'=>thegem_theme_options_get_page_settings('default'),
                        'blog'=>thegem_theme_options_get_page_settings('blog'),
                        'search'=>thegem_theme_options_get_page_settings('search'),
                        'global'=>thegem_theme_options_get_page_settings('global'),
                        'post'=>thegem_theme_options_get_page_settings('post'),
                        'portfolio'=>thegem_theme_options_get_page_settings('portfolio'),
                        'product'=>thegem_theme_options_get_page_settings('product'),
                        'product_categories'=>thegem_theme_options_get_page_settings('product_categories'),
                        'testimonials'=>thegem_theme_options_get_page_settings('testimonials'),
                        'team_persons'=>thegem_theme_options_get_page_settings('team_persons'),
                    ),
                    $custom_post_types_taxonomies_settings
                ),
            )
        );
    }

    function getCustomFooters() {
        $data = array(array('value' => '0', 'label' => __('Please select', 'thegem'), 'disabled' => true));

        foreach(thegem_get_footers_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_permalink($k) : null, 'edit' => $k ? add_query_arg(array('post' => $k, 'action' => 'elementor'), admin_url( 'post.php' )) : null);
        }

        return $data;
    }

    function checkPostIsTemplateBuilder($params) {
        global $post, $pagenow;
        $result = false;
        $page_id = get_the_ID();

        if (defined( 'WC_PLUGIN_FILE' ) && $pagenow == 'post.php' && $page_id) {
            switch ($params) {
                case 'cart':
                    $result = (wc_get_page_id('cart') == $page_id) && thegem_cart_template();
                    break;
                case 'checkout':
                    $result = (wc_get_page_id('checkout') == $page_id) && thegem_checkout_template();
                    break;
            }
        }

        return $result;
    }

    function getTemplates($params) {
        $data = array(array('value' => '0', 'label' => __('Please select', 'thegem'), 'disabled' => true));
        $getTemplatesArray = [];

        switch ($params) {
            case 'header':
                $getTemplatesArray = thegem_get_headers_list();
                break;
            case 'product':
                $getTemplatesArray = thegem_get_single_products_list();
                break;
            case 'product_archive':
                $getTemplatesArray = thegem_get_archive_products_list();
                break;
            case 'cart':
                $getTemplatesArray = thegem_get_cart_list();
                break;
            case 'checkout':
                $getTemplatesArray = thegem_get_checkout_list();
                break;
            case 'checkout-thanks':
                $getTemplatesArray = thegem_get_checkout_thanks_list();
                break;
            case 'blog_archive':
                $getTemplatesArray = thegem_get_blog_archive_list();
                break;
            case 'section':
                $getTemplatesArray = thegem_get_sections_list();
                break;
            case 'popups':
                $getTemplatesArray = thegem_get_popups_list();
                break;
            case 'single-post':
                $getTemplatesArray = thegem_get_posts_list();
                break;
            case 'loop-item':
                $getTemplatesArray = thegem_get_loop_items_list();
                break;
            case 'portfolio':
                $getTemplatesArray = thegem_get_portfolio_list();
                break;
        }

        foreach($getTemplatesArray as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_permalink($k) : null, 'edit' => $k ? add_query_arg(array('post' => $k, 'action' => 'elementor'), admin_url( 'post.php' )) : null);
        }

        return $data;
    }

    function getWCAttributes() {
        global $wpdb;
        $data = array(array('value' => '0', 'label' => __('Please select', 'thegem'), 'disabled' => true));

        if (class_exists('WooCommerce')) {
            $table_name = $wpdb->prefix . "woocommerce_attribute_taxonomies";
            $query_results = $wpdb->get_results("SELECT * FROM $table_name order by attribute_label");
            foreach($query_results as $query_result) {
                $data[] = array('value' => $query_result->attribute_name, 'label' => $query_result->attribute_label);
            }
        }

        return $data;
    }

    function getPagesList() {
        $data = array();

        foreach(thegem_get_pages_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => get_permalink($k));
        }

        return $data;
    }

    function getProductsList() {
        $data = array();

        foreach(thegem_get_products_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => get_permalink($k));
        }

        return $data;
    }

    function getBlogArchiveList() {
        $data = array();

        foreach(thegem_get_terms_list_by_taxonomy('category') as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_term_link($k, 'category') : '');
        }

        return $data;
    }

    function getProductArchiveList() {
        $data = array();

        foreach(thegem_get_terms_list_by_taxonomy('product_cat') as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_term_link($k, 'product_cat') : '');
        }

        return $data;
    }

    function getSinglePostsList() {
        $data = array();

        foreach(thegem_get_single_posts_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_permalink($k) : '');
        }

        return $data;
    }

    function getPortfolioList() {
        $data = array();

        foreach(thegem_get_portfolios_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => $k ? get_permalink($k) : '');
        }

        return $data;
    }

    function getParentPagesForPortfolioList() {
        $pages = array( array( 'value' => '' , 'label' => __('Default', 'thegem')));
        $pages_list = get_pages( [
            'sort_order'   => 'DESC',
            'sort_column'  => 'post_date',
            'number'       => 100,
            'post_status'  => 'publish',
        ] );
        foreach ($pages_list as $page) {
            $pages[] = array( 'value' => strval($page->ID), 'label' => $page->post_title . ' (ID = ' . $page->ID . ')');
        }

        return $pages;
    }

    function getPortfolioMeta() {
        global $pagenow;
        $details = thegem_get_option('portfolio_project_details');
        if (empty($details)) return;

        $data = array();
        $details_data = json_decode(thegem_get_option('portfolio_project_details_data'), true);
        if (!empty($details_data)) {
            foreach($details_data as $v) {
                $pm = get_post_meta(get_the_ID(), $v['key'], true);
                $value = !empty($pm) ? $pm : '';

                $data[] = array(
                    'key' => '_thegem_cf_'.str_replace( '-', '_', sanitize_title($v['title'])),
                    'title' => $v['title'],
                    'type' => $v['type'],
                    'value' => $pagenow == 'post-new.php' ? $v['value'] : $value,
                );
            }
        }

        return $data;
    }

    function getCustomTitles() {
        $data = array(array('value' => '0', 'label' => __('Please select', 'thegem'), 'disabled' => true));

        foreach(thegem_get_titles_list() as $k=>$v) {
            $data[] = array( 'value' => strval($k), 'label' => $v, 'preview' => get_permalink($k), 'edit' => $k ? add_query_arg(array('post' => $k, 'action' => 'elementor'), admin_url( 'post.php' )) : null);
        }

        return $data;
    }

    function getGalleriesList() {
        $list = get_posts(array(
            'post_type' => 'thegem_gallery',
            'numberposts' => -1,
            'post_status' => 'any'
        ));

        $galleries = array(array('value' => 0, 'label' => __('Please select', 'thegem'), 'disabled' => true));
        foreach ($list as $gallery) {
        $galleries[] = array( 'value' => $gallery->ID, 'label' => $gallery->post_title . ' (ID = ' . $gallery->ID . ')'/*, 'preview' => get_permalink($gallery->ID)*/);
        }

        return $galleries;
    }

    function getSliders() {
        $data = array(
            'types' => array( array('value' => '', 'label' => __('None', 'thegem'))),
            'slideshows' => array( array('value' => '', 'label' => __('All Slides', 'thegem'))),
            'layersliders' => array(),
            'revsliders' => array(),
        );

        if(thegem_get_option('activate_nivoslider')) {
            $data['types'][] = array ('value' => 'NivoSlider', 'label' => 'NivoSlider');
            $slideshows_terms = get_terms('thegem_slideshows', array('hide_empty' => false));
            foreach($slideshows_terms as $type) {
                $data['slideshows'][] = array ( 'value' => $type->slug, 'label' => $type->name);
            }
        };

        if(defined('LS_PLUGIN_VERSION')) {
            $data['types'][] = array ('value' => 'LayerSlider', 'label' => 'LayerSlider');
            global $wpdb;
            $table_name = $wpdb->prefix . "layerslider";
            $query_results = $wpdb->get_results("SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY id ASC");
            foreach($query_results as $query_result) {
                $data['layersliders'][] = array ( 'value' => $query_result->id, 'label' => $query_result->name);
            }
        }

        if(defined('RS_PLUGIN_PATH')) {
            $data['types'][] = array ('value' => 'revslider', 'label' => 'Revolution Slider');
            $slider = new RevSliderSlider();
            $arrSliders = $slider->get_sliders();
            foreach($arrSliders as $arrSlider) {
                $data['revsliders'][] = array ( 'value' => $arrSlider->get_alias(), 'label' => $arrSlider->get_title());
            }
        }

        return $data;
    }

    function getMenus() {
        $data = array(array('label' => __('Default Menu', 'thegem'), 'value'=> 0));

        $menus = wp_get_nav_menus();
        foreach($menus as $item) {
            $data[] = array('label' => $item->name, 'value' => $item->term_id);
        }

        return $data;
    }

    function getIconPacks() {
        $iconPacks=$this->getIconPacksInfo();
        foreach($iconPacks as $k=>$v) {
            if (!empty($v['fontFaces'])) {
                wp_add_inline_style('thegem-theme-options-0',$v['fontFaces']);
            }

            unset($iconPacks[$k]['fontFaces']);
        }

        return $iconPacks;
    }

    function getCustomPostTypes() {
        $data = array();

        $postTypes = thegem_get_list_po_custom_post_types(false);
        foreach($postTypes as $k => $v) {
            $data[] = array('label' => $v, 'value' => $k, 'formats' => post_type_supports( $k, 'post-formats'));
        }

        return $data;
    }

    function getCustomTaxonomies() {
        $data = array();

        $taxonomies = thegem_get_list_po_custom_taxonomies(false);
        foreach($taxonomies as $k => $v) {
            $data[] = array('label' => $v, 'value' => $k);
        }

        return $data;
    }

    function getContentWidgetsList() {
        $data = array(
            array(
                'name' => 'general',
                'title' => esc_html__('General Elements', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'accordion',
                        'title' => esc_html__('Accordion', 'thegem'),
                    ),
                    array(
                        'name' => 'animated-heading',
                        'title' => esc_html__('Animated Heading', 'thegem'),
                    ),
                    array(
                        'name' => 'styledbutton',
                        'title' => esc_html__('Button', 'thegem'),
                    ),
                    array(
                        'name' => 'cta',
                        'title' => esc_html__('Call To Action', 'thegem'),
                    ),
                    array(
                        'name' => 'clients',
                        'title' => esc_html__('Clients', 'thegem'),
                    ),
                    array(
                        'name' => 'contact-form7',
                        'title' => esc_html__('Contact Form 7', 'thegem'),
                    ),
                    array(
                        'name' => 'countdown',
                        'title' => esc_html__('Countdown', 'thegem'),
                    ),
                    array(
                        'name' => 'counter',
                        'title' => esc_html__('Counter', 'thegem'),
                    ),
                    array(
                        'name' => 'custom-fields',
                        'title' => esc_html__('Custom Fields', 'thegem'),
                    ),
                    array(
                        'name' => 'custom-menu',
                        'title' => esc_html__('Custom Menu', 'thegem'),
                    ),
                    array(
                        'name' => 'extended-filter',
                        'title' => esc_html__('Grid Filter', 'thegem'),
                    ),
                    array(
                        'name' => 'extended-sorting',
                        'title' => esc_html__('Grid Sorting', 'thegem'),
                    ),
                    array(
                        'name' => 'gallery-grid',
                        'title' => esc_html__('Gallery Grid', 'thegem'),
                    ),
                    array(
                        'name' => 'gallery-slider',
                        'title' => esc_html__('Gallery Slider', 'thegem'),
                    ),
                    array(
                        'name' => 'template',
                        'title' => esc_html__('Global Section', 'thegem'),
                    ),
                    array(
                        'name' => 'hotspot',
                        'title' => esc_html__('Hotspot', 'thegem'),
                    ),
                    array(
                        'name' => 'icon',
                        'title' => esc_html__('Icon', 'thegem'),
                    ),
                    array(
                        'name' => 'styledimage',
                        'title' => esc_html__('Image', 'thegem'),
                    ),
                    array(
                        'name' => 'image-compare',
                        'title' => esc_html__('Image Compare', 'thegem'),
                    ),
                    array(
                        'name' => 'infobox',
                        'title' => esc_html__('Infobox', 'thegem'),
                    ),
                    array(
                        'name' => 'mailchimp',
                        'title' => esc_html__('Mailchimp Opt-In', 'thegem'),
                    ),
                    array(
                        'name' => 'pricing-table',
                        'title' => esc_html__('Pricing Table', 'thegem'),
                    ),
                    array(
                        'name' => 'diagram',
                        'title' => esc_html__('Progress Bars', 'thegem'),
                    ),
                    array(
                        'name' => 'progressdonut',
                        'title' => esc_html__('Progress Donut', 'thegem'),
                    ),
                    array(
                        'name' => 'quickfinders',
                        'title' => esc_html__('Quickfinders', 'thegem'),
                    ),
                    array(
                        'name' => 'quoted-text',
                        'title' => esc_html__('Quoted Text', 'thegem'),
                    ),
                    array(
                        'name' => 'searchbar',
                        'title' => esc_html__('Search Bar', 'thegem'),
                    ),
                    array(
                        'name' => 'social-sharing',
                        'title' => esc_html__('Social Sharing', 'thegem'),
                    ),
                    array(
                        'name' => 'tabs',
                        'title' => esc_html__('Tabs & Tours', 'thegem'),
                    ),
                    array(
                        'name' => 'team',
                        'title' => esc_html__('Team', 'thegem'),
                    ),
                    array(
                        'name' => 'testimonials',
                        'title' => esc_html__('Testimonials', 'thegem'),
                    ),
                    array(
                        'name' => 'styled-textbox',
                        'title' => esc_html__('TextBox', 'thegem'),
                    ),
                    array(
                        'name' => 'wp-hook',
                        'title' => esc_html__('WordPress Hook', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'blog',
                'title' => esc_html__('Blog Elements', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'extended-blog-grid',
                        'title' => esc_html__('Posts Extended Grid', 'thegem'),
                    ),
                    array(
                        'name' => 'posts-carousel',
                        'title' => esc_html__('Posts Carousel', 'thegem'),
                    ),
                    array(
                        'name' => 'featured-posts-slider',
                        'title' => esc_html__('Featured Posts Slider', 'thegem'),
                    ),
                    array(
                        'name' => 'blog-grid',
                        'title' => esc_html__('Blog Grid', 'thegem'),
                    ),
                    array(
                        'name' => 'bloglist',
                        'title' => esc_html__('Blog List', 'thegem'),
                    ),
                    array(
                        'name' => 'blogslider',
                        'title' => esc_html__('Blog Slider', 'thegem'),
                    ),
                    array(
                        'name' => 'blogtimeline',
                        'title' => esc_html__('Blog Timeline', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'woocommerce',
                'title' => esc_html__('WooCommerce Elements', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'extended-products-grid',
                        'title' => esc_html__('Products Grid', 'thegem'),
                    ),
                    array(
                        'name' => 'extended-products-carousel',
                        'title' => esc_html__('Products Carousel', 'thegem'),
                    ),
                    array(
                        'name' => 'products-categories',
                        'title' => esc_html__('Product Categories', 'thegem'),
                    ),
                    array(
                        'name' => 'products-compact-grid',
                        'title' => esc_html__('Products Compact Grid/List', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'portfolios',
                'title' => esc_html__('Portfolios Elements', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'portfolio',
                        'title' => esc_html__('Portfolio Grid', 'thegem'),
                    ),
                    array(
                        'name' => 'portfolio-carousel',
                        'title' => esc_html__('Portfolio Carousel', 'thegem'),
                    ),
                    array(
                        'name' => 'portfolio-list',
                        'title' => esc_html__('Portfolio List', 'thegem'),
                    ),
                    array(
                        'name' => 'portfolio-slider',
                        'title' => esc_html__('Portfolio Slider', 'thegem'),
                    ),
                    array(
                        'name' => 'projectinfo',
                        'title' => esc_html__('Project Info', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'header_builder',
                'title' => esc_html__('Header Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-account',
                        'title' => esc_html__('My Account Icon', 'thegem'),
                    ),
                    array(
                        'name' => 'template-cart',
                        'title' => esc_html__('Cart', 'thegem'),
                    ),
                    array(
                        'name' => 'template-countdown',
                        'title' => esc_html__('Countdown', 'thegem'),
                    ),
                    array(
                        'name' => 'template-currency-switcher',
                        'title' => esc_html__('Currency Switcher', 'thegem'),
                    ),
                    array(
                        'name' => 'template-divider',
                        'title' => esc_html__('Divider', 'thegem'),
                    ),
                    array(
                        'name' => 'template-infobox',
                        'title' => esc_html__('Infobox', 'thegem'),
                    ),
                    array(
                        'name' => 'template-language-switcher',
                        'title' => esc_html__('Language Switcher', 'thegem'),
                    ),
                    array(
                        'name' => 'template-logo',
                        'title' => esc_html__('Logo', 'thegem'),
                    ),
                    array(
                        'name' => 'template-menu-secondary',
                        'title' => esc_html__('Secondary Menu', 'thegem'),
                    ),
                    array(
                        'name' => 'template-menu',
                        'title' => esc_html__('Menu', 'thegem'),
                    ),
                    array(
                        'name' => 'template-search-form',
                        'title' => esc_html__('Search Form', 'thegem'),
                    ),
                    array(
                        'name' => 'template-search',
                        'title' => esc_html__('Search Icon', 'thegem'),
                    ),
                    array(
                        'name' => 'template-signin',
                        'title' => esc_html__('Sign In / Sign Out', 'thegem'),
                    ),
                    array(
                        'name' => 'template-wishlist',
                        'title' => esc_html__('Wishlist', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'blog_archive_builder',
                'title' => esc_html__('Archives builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-blog-archive-breadcrumbs',
                        'title' => esc_html__('Archive Breadcrumbs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-blog-archive-description',
                        'title' => esc_html__('Archive Description', 'thegem'),
                    ),
                    array(
                        'name' => 'template-blog-archive-title',
                        'title' => esc_html__('Archive Title', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'cart_builder',
                'title' => esc_html__('Cart Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-cart-table',
                        'title' => esc_html__('Cart Table', 'thegem'),
                    ),
                    array(
                        'name' => 'template-cart-totals',
                        'title' => esc_html__('Cart Totals', 'thegem'),
                    ),
                    array(
                        'name' => 'template-cart-checkout-notices',
                        'title' => esc_html__('WooCommerce Notices', 'thegem'),
                    ),
                    array(
                        'name' => 'template-cart-checkout-steps',
                        'title' => esc_html__('Checkout Steps', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'checkout_builder',
                'title' => esc_html__('Checkout Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-checkout-billing',
                        'title' => esc_html__('Billing Details', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-coupon',
                        'title' => esc_html__('Apply Coupon', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-errors',
                        'title' => esc_html__('Checkout Errors', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-login',
                        'title' => esc_html__('Customer Login', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-order',
                        'title' => esc_html__('Order Review', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-payment',
                        'title' => esc_html__('Payment Methods', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-shipping',
                        'title' => esc_html__('Shipping Details', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'checkout_thanks_builder',
                'title' => esc_html__('Purchase Summary Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-checkout-thanks-billing-details',
                        'title' => esc_html__('Billing Details', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-cart-totals',
                        'title' => esc_html__('Cart Totals', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-customer-details',
                        'title' => esc_html__('Customer Details', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-notices',
                        'title' => esc_html__('WooCommerce Notices', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-order-details',
                        'title' => esc_html__('Order Details', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-order-overview',
                        'title' => esc_html__('Order Overview', 'thegem'),
                    ),
                    array(
                        'name' => 'template-checkout-thanks-shipping-details',
                        'title' => esc_html__('Shipping Details', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'single_post_builder',
                'title' => esc_html__('Single Post/Page Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-portfolio-gallery',
                        'title' => esc_html__('Post Gallery', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-author',
                        'title' => esc_html__('Author Box', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-breadcrumbs',
                        'title' => esc_html__('Post Breadcrumbs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-excerpt',
                        'title' => esc_html__('Post Excerpt', 'thegem'),
                    ),
                    array(
                        'name' => 'template-featured-image',
                        'title' => esc_html__('Featured Image', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-info',
                        'title' => esc_html__('Post Meta', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-navigation',
                        'title' => esc_html__('Post Navigation', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-title',
                        'title' => esc_html__('Post Title', 'thegem'),
                    ),
                    array(
                        'name' => 'template-loop-featured-media',
                        'title' => esc_html__('Featured Media', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-comments',
                        'title' => esc_html__('Post Comments', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-content',
                        'title' => esc_html__('Post Content', 'thegem'),
                    ),
                    array(
                        'name' => 'template-featured-content',
                        'title' => esc_html__('Featured Content', 'thegem'),
                    ),
                    array(
                        'name' => 'template-post-tags',
                        'title' => esc_html__('Post Tags', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'portfolio_builder',
                'title' => esc_html__('Portfolio Page Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-portfolio-breadcrumbs',
                        'title' => esc_html__('Breadcrumbs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-content',
                        'title' => esc_html__('Content', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-excerpt',
                        'title' => esc_html__('Excerpt/Short Description', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-featured-image',
                        'title' => esc_html__('Featured Image', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-info',
                        'title' => esc_html__('Project Meta/Details', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-navigation',
                        'title' => esc_html__('Prev/Next Navigation', 'thegem'),
                    ),
                    array(
                        'name' => 'template-portfolio-title',
                        'title' => esc_html__('Title', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'single_product_builder',
                'title' => esc_html__('Single Product Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-product-add-to-cart',
                        'title' => esc_html__('Product Add to Cart', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-add-to-wishlist',
                        'title' => esc_html__('Product Add to Wishlist', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-additional-info',
                        'title' => esc_html__('Product Additional Info', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-attribute',
                        'title' => esc_html__('Product Attributes', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-breadcrumbs',
                        'title' => esc_html__('Product Breadcrumbs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-categories',
                        'title' => esc_html__('Product Categories', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-content',
                        'title' => esc_html__('Product Content', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-description',
                        'title' => esc_html__('Product Short Description', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-extra-description',
                        'title' => esc_html__('Product Extra Description', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-gallery',
                        'title' => esc_html__('Product Gallery', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-meta-value',
                        'title' => esc_html__('Product Meta Value', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-navigation',
                        'title' => esc_html__('Product Navigation', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-price',
                        'title' => esc_html__('Product Price', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-rating',
                        'title' => esc_html__('Product Rating', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-reviews',
                        'title' => esc_html__('Product Reviews', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-sharing',
                        'title' => esc_html__('Product Sharing', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-size-guide',
                        'title' => esc_html__('Product Size Guide', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-sku',
                        'title' => esc_html__('Product SKU', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-tabs',
                        'title' => esc_html__('Product Tabs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-tags',
                        'title' => esc_html__('Product Tags', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-title',
                        'title' => esc_html__('Product Title', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'product_archive_builder',
                'title' => esc_html__('Product Archives Builder', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'template-product-archive-breadcrumbs',
                        'title' => esc_html__('Archive Breadcrumbs', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-archive-description',
                        'title' => esc_html__('Archive Description', 'thegem'),
                    ),
                    array(
                        'name' => 'template-product-archive-title',
                        'title' => esc_html__('Archive Title', 'thegem'),
                    ),
                ),
            ),
            array(
                'name' => 'deprecated',
                'title' => esc_html__('Deprecated Elements', 'thegem'),
                'widgetsList' => array(
                    array(
                        'name' => 'products-grid',
                        'title' => esc_html__('Product Classic Grid (Legacy)', 'thegem'),
                    ),
                    array(
                        'name' => 'products-slider',
                        'title' => esc_html__('Products Slider (Legacy)', 'thegem'),
                    ),
                ),
            ),
        );
        if(!defined('WC_PLUGIN_FILE')) {
            foreach($data as $key => $group) {
                if(in_array($group['name'], array('woocommerce', 'cart_builder', 'checkout_builder', 'checkout_thanks_builder', 'single_product_builder', 'product_archive_builder', 'deprecated'))) {
                    unset($data[$key]);
                }
            }
            $data = array_values($data);
        }
        return $data;
    }

    function cutSchemeFromUrl($url) {
        return preg_replace('%^[^:]+://%', '//', $url);
    }

    function get_gdpr_theme_fonts() {
        $gdpr_theme_fonts = get_option('thegem_gdpr_theme_fonts');
        if (empty($gdpr_theme_fonts['value'])) return;

        return $gdpr_theme_fonts['value'];
    }

    function addAppData($specificData) {

        wp_enqueue_media();
        wp_enqueue_editor();

        $content=file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'index.html');

        preg_match_all('%<link[^>]*?([^"=]*\.css)%',$content,$m);
        foreach(array_unique($m[1]) as $idx=>$styleFile) {
            wp_enqueue_style("thegem-theme-options-$idx",$this->processPageFileUrl($styleFile));
        }

        wp_enqueue_style('icons-elegant', THEGEM_THEME_URI . '/css/icons-elegant.css', array(), THEGEM_THEME_VERSION);
        wp_enqueue_style('icons-material', THEGEM_THEME_URI . '/css/icons-material.css', array(), THEGEM_THEME_VERSION);
        wp_enqueue_style('icons-fontawesome', THEGEM_THEME_URI . '/css/icons-fontawesome.css', array(), THEGEM_THEME_VERSION);

        preg_match_all('%<script[^>]*?([^"=]*\.js)%',$content,$m);
        foreach($m[1] as $idx=>$scriptFile) {
            wp_enqueue_script("thegem-theme-options-$idx",$this->processPageFileUrl($scriptFile),array(),false,true);
        }

        $thegem_theme = wp_get_theme(wp_get_theme()->get('Template'));
        $newTemplateNonce = wp_create_nonce( 'thegem_templates_new' );
        $lang = thegem_get_current_language() ? '&lang='.thegem_get_current_language() : '';
        $upload_dir = wp_upload_dir();

        $appData=array_merge( array(
            'version' => $thegem_theme->get('Version'),
            'appUrl' => THEGEM_THEME_URI.'/inc/theme-options/dist/',
            'apiUrl' => admin_url( 'admin-ajax.php' ).'?action=thegem_theme_options_api&security='.wp_create_nonce( 'thegem_theme_options_api' ).$lang,
            'styleEditorCssUrl' => set_url_scheme($upload_dir['baseurl'] . '/thegem/css/style-editor.css'),
            'adminUrl' => admin_url(),
            'homeUrl' => $this->cutSchemeFromUrl(trailingslashit(get_home_url())),
            'i18n' => array(
                'locale' => get_bloginfo('language'),
                'messages' => require('messages.php')
            ),
            'optimizer' => $this->getOptimizerInfo(),
            'gdpr_extras_google_fonts' => $this->get_gdpr_theme_fonts(),
            'WCAttributes' => $this->getWCAttributes(),
            'customPostTypes' => $this->getCustomPostTypes(),
            'customTaxonomies' => $this->getCustomTaxonomies(),
            'contentWidgetsList' => $this->getContentWidgetsList(),
            'wysiwygFormats' => $this->getWysiwygFormats(),
            'settings' => $this->getSettings(),
            'presetsUrl' => THEGEM_THEME_URI.'/images/backgrounds/presets/',
            'isCartPageBuilder' => $this->checkPostIsTemplateBuilder('cart'),
            'isCheckoutPageBuilder' => $this->checkPostIsTemplateBuilder('checkout'),
            'headerBuilderTemplates' => $this->getTemplates('header'),
            'headerBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=header'),
            'headerBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=header#open-modal-import'),
            'customTitles' => $this->getCustomTitles(),
            'titleTemplateCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=title'),
            'titleTemplateImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=title#open-modal-import'),
            'customFooters' => $this->getCustomFooters(),
            'footerTemplateCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=footer'),
            'footerTemplateImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=footer#open-modal-import'),
            'productBuilderTemplates' => $this->getTemplates('product'),
            'productBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=single-product'),
            'productBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=single-product#open-modal-import'),
            'productBuilderPreviewProductsList' => $this->getProductsList(),
            'productArchiveBuilderTemplates' => $this->getTemplates('product_archive'),
            'productArchiveBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=product-archive'),
            'productArchiveBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=product-archive#open-modal-import'),
            'productArchiveBuilderPreviewList' => $this->getProductArchiveList(),
            'cartBuilderTemplates' => $this->getTemplates('cart'),
            'cartBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=cart'),
            'cartBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=cart#open-modal-import'),
            'checkoutBuilderTemplates' => $this->getTemplates('checkout'),
            'checkoutBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=checkout'),
            'checkoutBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=checkout#open-modal-import'),
            'checkoutThanksBuilderTemplates' => $this->getTemplates('checkout-thanks'),
            'checkoutThanksBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=checkout-thanks'),
            'checkoutThanksBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=checkout-thanks#open-modal-import'),
            'blogArchiveBuilderTemplates' => $this->getTemplates('blog_archive'),
            'blogArchiveBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=blog-archive'),
            'blogArchiveBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=blog-archive#open-modal-import'),
            'blogArchiveBuilderPreviewList' => $this->getBlogArchiveList(),
            'postBuilderTemplates' => $this->getTemplates('single-post'),
            'postBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=single-post'),
            'postBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=single-post#open-modal-import'),
            'postBuilderPreviewList' => $this->getSinglePostsList(),
            'loopBuilderTemplates' => $this->getTemplates('loop-item'),
            'loopBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=loop-item'),
            'loopBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=loop-item#open-modal-import'),
            'sectionBuilderTemplates' => $this->getTemplates('section'),
            'sectionBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=content'),
            'sectionBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=content#open-modal-import'),
            'mobileMenuSectionBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=mobile-menu#open-modal-import'),
            'popupsTemplates' => $this->getTemplates('popups'),
            'popupsCreateUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=popup#open-modal-proceed'),
            'popupsImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=popup#open-modal-import'),
            'portfolioBuilderTemplates' => $this->getTemplates('portfolio'),
            'portfolioBuilderCreateUrl' => admin_url('edit.php?post_type=thegem_templates&action=thegem_templates_new&_wpnonce='.$newTemplateNonce.'&template_type=portfolio'),
            'portfolioBuilderImportUrl' => admin_url('edit.php?post_type=thegem_templates&templates_type=portfolio#open-modal-import'),
            'portfolioBuilderPreviews' => $this->getPortfolioList(),
            'wooSettingsProductsGeneralUrl' => admin_url('admin.php?page=wc-settings&tab=products'),
            'is_rtl' => is_rtl(),
        ), $specificData);

        wp_localize_script("thegem-theme-options-0", 'theme_options_app_data', $appData);
    }

    function getWysiwygFormats() {
        $settings = thegem_init_editor(array());
        return json_decode($settings['style_formats'], true);
    }

    function getCustomFieldsFromPostTypes($post_type) {
        global $pagenow;
        switch ($post_type) {
            case 'page':
                $post_type = 'default';
                break;
            case 'thegem_pf_item':
                $post_type = 'portfolio';
                break;
        }

        $pt_data = thegem_theme_options_get_page_settings($post_type);
        $custom_fields = !empty($pt_data['custom_fields']) ? $pt_data['custom_fields'] : null;
        $custom_fields_data = !empty($pt_data['custom_fields_data']) ? json_decode($pt_data['custom_fields_data'], true) : null;
        $data = array();

        if (empty($custom_fields)) return;

        if (!empty($custom_fields_data)) {
            foreach($custom_fields_data as $field) {
                $pm = get_post_meta(get_the_ID(), $field['key'], true);
                $value = !empty($pm) ? $pm : '';

                $data[] = array(
                    'key' => $field['key'],
                    'title' => $field['title'],
                    'type' => $field['type'],
                    'value' => $pagenow == 'post-new.php' ? $field['value'] : $value,
                );
            }
        }

        return $data;
    }

    function getPageOptions($post, $type) {
        $page_data = array();
        /*if($type === 'term') {
            if(get_term_meta($post->term_id, 'thegem_page_data', true)) {
                $page_data = thegem_get_sanitize_admin_page_data($post->term_id, array(), $type);
            } else {
                $page_data = thegem_get_sanitize_admin_page_data(0, thegem_theme_options_get_page_settings('blog'), 'blog');
            }
        } elseif(in_array($type, array('default', 'blog', 'search'))) {
            $page_data = thegem_get_sanitize_admin_page_data(0, thegem_theme_options_get_page_settings($type), $type);
        } else {
            if(get_post_meta($post->ID, 'thegem_page_data', true)) {
                $page_data = thegem_get_sanitize_admin_page_data($post->ID);
            } else {
                $page_data_defaut = thegem_theme_options_get_page_settings('default');
                $page_data =thegem_get_sanitize_admin_page_data(0, $page_data_defaut, 'default');
                if($page_data_defaut['title_style'] != 2) {
                  $page_data['title_template'] = 0;
                }
                if(!$page_data_defaut['footer_custom_show']) {
                  $page_data['footer_custom'] = 0;
                }
            }
        }*/
        $page_data = thegem_get_sanitize_admin_page_data($post->ID);
        $popup_data = get_post_meta($post->ID, 'thegem_popups_data', true);
        if(!empty($popup_data)) {
            $page_data['popups_item_data'] = $popup_data;
        }

        // Get custom fields
        if (empty($page_data['custom_fields_item_data'])){
            $page_data['custom_fields_item_data'] = $this->getCustomFieldsFromPostTypes($post->post_type);
        } else{
            $meta_data = array();
            if (!empty($this->getCustomFieldsFromPostTypes($post->post_type))) {
                foreach ($this->getCustomFieldsFromPostTypes($post->post_type) as $item) {
                    $meta_data[] = array(
                        'key' => $item['key'],
                        'title' => $item['title'],
                        'type' => $item['type'],
                        'value' => get_post_meta($post->ID, $item['key'], true)
                    );
                }
            }

            if (!empty($meta_data)){
                $page_data['custom_fields_item_data'] = $meta_data;
            } else{
                $page_data['custom_fields_item_data'] = $this->getCustomFieldsFromPostTypes($post->post_type);
            }
        }

        switch ($post->post_type) {
            case 'post':
                $page_data['post_item_data'] = thegem_get_sanitize_admin_post_data($post->ID);
                $page_data['post_elements_data'] = thegem_get_sanitize_admin_post_elements_data($post->ID);
                break;

            case 'thegem_pf_item':
                $portfolio_item_data = thegem_get_sanitize_pf_item_data($post->ID);
                if (empty($portfolio_item_data['types'])) {
                    $portfolio_item_data['types'] = array(0 => array('link' => '', 'link_target' => '_self', 'type' => 'self-link'));
                }

                //get grid appearance gif
                if (!empty($portfolio_item_data['grid_appearance_gif'])) {
                    $portfolio_item_data['grid_appearance_gif'] = array(
                        'id' => $portfolio_item_data['grid_appearance_gif'],
                        'url' => wp_get_attachment_image_src($portfolio_item_data['grid_appearance_gif'], 'full')[0]
                    );
                } else{
                    $portfolio_item_data['grid_appearance_gif'] = null;
                }

                //get meta data
                if (empty($portfolio_item_data['meta']) || empty(thegem_get_option('portfolio_project_details'))){
                    $portfolio_item_data['meta'] = $this->getPortfolioMeta();
                } else{
                    $meta_data = array();
                    foreach ($this->getPortfolioMeta() as $item) {
                        $meta_data[] = array(
                            'key' => '_thegem_cf_'.str_replace( '-', '_', sanitize_title($item['title'])),
                            'title' => $item['title'],
                            'type' => $item['type'],
                            'value' => get_post_meta($post->ID, $item['key'], true)
                        );
                    }

                    if (!empty($meta_data)){
                        $portfolio_item_data['meta'] = $meta_data;
                    } else{
                        $portfolio_item_data['meta'] = $this->getPortfolioMeta();
                    }
                }

                $page_data['portfolio_item_data'] = $portfolio_item_data;

                $page_data['portfolio_elements_data'] = thegem_get_sanitize_pf_item_elements_data($post->ID);
                break;

            case 'product':
                $size_guide = thegem_get_sanitize_product_size_guide_data($post->ID);
                $highlight = thegem_get_sanitize_product_featured_data($post->ID);
                $hover = !empty(get_post_meta($post->ID, 'thegem_product_disable_hover', true));
                $product_page = thegem_get_sanitize_product_page_data($post->ID);

                $page_data['product_item_data'] = array(
                    'thegem_product_disable_hover' => $hover ? 1 : 0,
                    'highlight' => $highlight['highlight'],
                    'highlight_type' => $highlight['highlight_type'],
                    'size_guide' => $size_guide['size_guide'],
                    'size_guide_image' => $size_guide['custom_image'],
                    'size_guide_text' => $size_guide['custom_text'],
                    'product_layout_settings' => $product_page['product_layout_settings'],
                    'product_layout_source' => $product_page['product_layout_source'],
                    'product_builder_template' => $product_page['product_builder_template'],
                    'product_gallery' => $product_page['product_gallery'],
                    'product_gallery_type' => $product_page['product_gallery_type'],
                    'product_gallery_thumb_on_mobile' => $product_page['product_gallery_thumb_on_mobile'],
                    'product_gallery_thumb_scroll_by_hover' => $product_page['product_gallery_thumb_scroll_by_hover'],
                    'product_gallery_thumb_count' => $product_page['product_gallery_thumb_count'],
                    'product_gallery_thumb_position' => $product_page['product_gallery_thumb_position'],
                    'product_gallery_column_position' => $product_page['product_gallery_column_position'],
                    'product_gallery_column_width' => $product_page['product_gallery_column_width'],
                    'product_gallery_show_image' => $product_page['product_gallery_show_image'],
                    'product_gallery_image_ratio' => $product_page['product_gallery_image_ratio'],
                    'product_gallery_grid_image_size' => $product_page['product_gallery_grid_image_size'],
                    'product_gallery_grid_image_ratio' => $product_page['product_gallery_grid_image_ratio'],
                    'product_gallery_zoom' => $product_page['product_gallery_zoom'],
                    'product_gallery_lightbox' => $product_page['product_gallery_lightbox'],
                    'product_gallery_labels' => $product_page['product_gallery_labels'],
                    'product_gallery_label_sale' => $product_page['product_gallery_label_sale'],
                    'product_gallery_label_new' => $product_page['product_gallery_label_new'],
                    'product_gallery_label_out_stock' => $product_page['product_gallery_label_out_stock'],
                    'product_gallery_auto_height' => $product_page['product_gallery_auto_height'],
                    'product_gallery_elements_color' => $product_page['product_gallery_elements_color'],
                    'product_gallery_grid_columns' => $product_page['product_gallery_grid_columns'],
                    'product_gallery_grid_gaps' => $product_page['product_gallery_grid_gaps'],
                    'product_gallery_grid_gaps_hide' => $product_page['product_gallery_grid_gaps_hide'],
                    'product_gallery_grid_top_margin' => $product_page['product_gallery_grid_top_margin'],
                    'product_gallery_video_autoplay' => $product_page['product_gallery_video_autoplay'],
                    'product_page_layout' => $product_page['product_page_layout'],
                    'product_page_layout_style' => $product_page['product_page_layout_style'],
                    'product_page_layout_centered' => $product_page['product_page_layout_centered'],
                    'product_page_layout_centered_top_margin' => $product_page['product_page_layout_centered_top_margin'],
                    'product_page_layout_centered_boxed' => $product_page['product_page_layout_centered_boxed'],
                    'product_page_layout_centered_boxed_color' => $product_page['product_page_layout_centered_boxed_color'],
                    'product_page_layout_background' => $product_page['product_page_layout_background'],
                    'product_page_layout_preset' => $product_page['product_page_layout_preset'],
                    'product_page_layout_fullwidth' => $product_page['product_page_layout_fullwidth'],
                    'product_page_layout_sticky' => $product_page['product_page_layout_sticky'],
                    'product_page_layout_sticky_offset' => $product_page['product_page_layout_sticky_offset'],
                    'product_page_skeleton_loader' => $product_page['product_page_skeleton_loader'],
                    'product_page_layout_title_area' => $product_page['product_page_layout_title_area'],
                    'product_page_ajax_add_to_cart' => $product_page['product_page_ajax_add_to_cart'],
                    'product_page_desc_review_source' => $product_page['product_page_desc_review_source'],
                    'product_page_desc_review_layout' => $product_page['product_page_desc_review_layout'],
                    'product_page_desc_review_layout_tabs_style' => $product_page['product_page_desc_review_layout_tabs_style'],
                    'product_page_desc_review_layout_tabs_alignment' => $product_page['product_page_desc_review_layout_tabs_alignment'],
                    'product_page_desc_review_layout_acc_position' => $product_page['product_page_desc_review_layout_acc_position'],
                    'product_page_desc_review_layout_one_by_one_description_background' => $product_page['product_page_desc_review_layout_one_by_one_description_background'],
                    'product_page_desc_review_layout_one_by_one_additional_info_background' => $product_page['product_page_desc_review_layout_one_by_one_additional_info_background'],
                    'product_page_desc_review_layout_one_by_one_reviews_background' => $product_page['product_page_desc_review_layout_one_by_one_reviews_background'],
                    'product_page_desc_review_description' => $product_page['product_page_desc_review_description'],
                    'product_page_desc_review_description_title' => $product_page['product_page_desc_review_description_title'],
                    'product_page_desc_review_additional_info' => $product_page['product_page_desc_review_additional_info'],
                    'product_page_desc_review_additional_info_title' => $product_page['product_page_desc_review_additional_info_title'],
                    'product_page_desc_review_reviews' => $product_page['product_page_desc_review_reviews'],
                    'product_page_desc_review_reviews_title' => $product_page['product_page_desc_review_reviews_title'],
                    'product_page_button_add_to_cart_text' => $product_page['product_page_button_add_to_cart_text'],
                    'product_page_button_add_to_cart_icon_show' => $product_page['product_page_button_add_to_cart_icon_show'],
                    'product_page_button_add_to_cart_icon' => $product_page['product_page_button_add_to_cart_icon'],
                    'product_page_button_add_to_cart_icon_pack' => $product_page['product_page_button_add_to_cart_icon_pack'],
                    'product_page_button_add_to_cart_icon_position' => $product_page['product_page_button_add_to_cart_icon_position'],
                    'product_page_button_add_to_cart_border_width' => $product_page['product_page_button_add_to_cart_border_width'],
                    'product_page_button_add_to_cart_border_radius' => $product_page['product_page_button_add_to_cart_border_radius'],
                    'product_page_button_add_to_cart_color' => $product_page['product_page_button_add_to_cart_color'],
                    'product_page_button_add_to_cart_color_hover' => $product_page['product_page_button_add_to_cart_color_hover'],
                    'product_page_button_add_to_cart_background' => $product_page['product_page_button_add_to_cart_background'],
                    'product_page_button_add_to_cart_background_hover' => $product_page['product_page_button_add_to_cart_background_hover'],
                    'product_page_button_add_to_cart_border_color' => $product_page['product_page_button_add_to_cart_border_color'],
                    'product_page_button_add_to_cart_border_color_hover' => $product_page['product_page_button_add_to_cart_border_color_hover'],
                    'product_page_button_add_to_wishlist_icon' => $product_page['product_page_button_add_to_wishlist_icon'],
                    'product_page_button_add_to_wishlist_icon_pack' => $product_page['product_page_button_add_to_wishlist_icon_pack'],
                    'product_page_button_add_to_wishlist_color' => $product_page['product_page_button_add_to_wishlist_color'],
                    'product_page_button_add_to_wishlist_color_hover' => $product_page['product_page_button_add_to_wishlist_color_hover'],
                    'product_page_button_add_to_wishlist_color_filled' => $product_page['product_page_button_add_to_wishlist_color_filled'],
                    'product_page_button_added_to_wishlist_icon' => $product_page['product_page_button_added_to_wishlist_icon'],
                    'product_page_button_added_to_wishlist_icon_pack' => $product_page['product_page_button_added_to_wishlist_icon_pack'],
                    'product_page_button_clear_attributes_text' => $product_page['product_page_button_clear_attributes_text'],
                    'product_page_elements_prev_next' => $product_page['product_page_elements_prev_next'],
                    'product_page_elements_preview_on_hover' => $product_page['product_page_elements_preview_on_hover'],
                    'product_page_elements_back_to_shop' => $product_page['product_page_elements_back_to_shop'],
                    'product_page_elements_back_to_shop_link' => $product_page['product_page_elements_back_to_shop_link'],
                    'product_page_elements_back_to_shop_link_custom_url' => $product_page['product_page_elements_back_to_shop_link_custom_url'],
                    'product_page_elements_title' => $product_page['product_page_elements_title'],
                    'product_page_elements_title_html' => $product_page['product_page_elements_title_html'],
                    'product_page_elements_attributes' => $product_page['product_page_elements_attributes'],
                    'product_page_elements_attributes_data' => $product_page['product_page_elements_attributes_data'],
                    'product_page_elements_reviews' => $product_page['product_page_elements_reviews'],
                    'product_page_elements_reviews_text' => $product_page['product_page_elements_reviews_text'],
                    'product_page_elements_price' => $product_page['product_page_elements_price'],
                    'product_page_elements_price_strikethrough' => $product_page['product_page_elements_price_strikethrough'],
                    'product_page_elements_description' => $product_page['product_page_elements_description'],
                    'product_page_elements_stock_amount' => $product_page['product_page_elements_stock_amount'],
                    'product_page_elements_stock_amount_text' => $product_page['product_page_elements_stock_amount_text'],
                    'product_page_elements_size_guide' => $product_page['product_page_elements_size_guide'],
                    'product_page_elements_sku' => $product_page['product_page_elements_sku'],
                    'product_page_elements_sku_title' => $product_page['product_page_elements_sku_title'],
                    'product_page_elements_categories' => $product_page['product_page_elements_categories'],
                    'product_page_elements_categories_title' => $product_page['product_page_elements_categories_title'],
                    'product_page_elements_tags' => $product_page['product_page_elements_tags'],
                    'product_page_elements_tags_title' => $product_page['product_page_elements_tags_title'],
                    'product_page_elements_share' => $product_page['product_page_elements_share'],
                    'product_page_elements_share_title' => $product_page['product_page_elements_share_title'],
                    'product_page_elements_share_facebook' => $product_page['product_page_elements_share_facebook'],
                    'product_page_elements_share_twitter' => $product_page['product_page_elements_share_twitter'],
                    'product_page_elements_share_pinterest' => $product_page['product_page_elements_share_pinterest'],
                    'product_page_elements_share_tumblr' => $product_page['product_page_elements_share_tumblr'],
                    'product_page_elements_share_linkedin' => $product_page['product_page_elements_share_linkedin'],
                    'product_page_elements_share_reddit' => $product_page['product_page_elements_share_reddit'],
                    'product_page_elements_upsell' => $product_page['product_page_elements_upsell'],
                    'product_page_elements_upsell_title' => $product_page['product_page_elements_upsell_title'],
                    'product_page_elements_upsell_title_alignment' => $product_page['product_page_elements_upsell_title_alignment'],
                    'product_page_elements_upsell_items' => $product_page['product_page_elements_upsell_items'],
                    'product_page_elements_upsell_columns_desktop' => $product_page['product_page_elements_upsell_columns_desktop'],
                    'product_page_elements_upsell_columns_tablet' => $product_page['product_page_elements_upsell_columns_tablet'],
                    'product_page_elements_upsell_columns_mobile' => $product_page['product_page_elements_upsell_columns_mobile'],
                    'product_page_elements_upsell_columns_100' => $product_page['product_page_elements_upsell_columns_100'],
                    'product_page_elements_related' => $product_page['product_page_elements_related'],
                    'product_page_elements_related_title' => $product_page['product_page_elements_related_title'],
                    'product_page_elements_related_title_alignment' => $product_page['product_page_elements_related_title_alignment'],
                    'product_page_elements_related_items' => $product_page['product_page_elements_related_items'],
                    'product_page_elements_related_columns_desktop' => $product_page['product_page_elements_related_columns_desktop'],
                    'product_page_elements_related_columns_tablet' => $product_page['product_page_elements_related_columns_tablet'],
                    'product_page_elements_related_columns_mobile' => $product_page['product_page_elements_related_columns_mobile'],
                    'product_page_elements_related_columns_100' => $product_page['product_page_elements_related_columns_100'],
                    'product_page_additional_tabs' => $product_page['product_page_additional_tabs'],
                    'product_page_additional_tabs_data' => is_array($product_page['product_page_additional_tabs_data']) ? json_encode($product_page['product_page_additional_tabs_data']) : $product_page['product_page_additional_tabs_data'],
                );

                break;
        }

        return $page_data;
    }

    function getPurchaseCode() {
        $code = thegem_get_option('purchase_code');

        for ($i = 18; $i < strlen($code); $i++) {
            if ($code[$i] != '-') {
                $code[$i] = '*';
            }
        }

        return $code;
    }

    function getSelfHostedFonts() {
        $data = thegem_additionals_fonts();

        $fonts = array();

        foreach($data as $fontData) {
            $fonts[]= $fontData['font_name'];
        };

        return implode(',', $fonts);
    }

    function getOptimizerInfo() {
        $data = array();
        $data['activated'] = get_option('thegem_enabled_wprocket_autoptimize') && defined( 'WP_ROCKET_VERSION' );
        if (!$data['activated']) {
            $data['wprocket_active'] = defined( 'WP_ROCKET_VERSION' );
            $data['show_confirm_on_activate'] = is_plugin_active('wp-rocket/wp-rocket.php') || is_plugin_active('autoptimize/autoptimize.php');
        } else {
            $error = get_option('thegem_optimizer_error');
            if ($error != '') {
                $data['error'] = get_option('thegem_optimizer_error');
            }

            $data['wprocket_active'] = is_plugin_active('wp-rocket/wp-rocket.php');
        }

        $data['wpsupercache_active'] = get_option('thegem_enabled_wpsupercache_autoptimize') && function_exists('wpsc_init');
        $data['autoptimize_active'] = is_plugin_active('autoptimize/autoptimize.php');

		if(get_option('thegem_wpsupercache_activated')) {
			$data['wpsupercache_activated'] = 1;
			delete_option('thegem_wpsupercache_activated');
		}
		if(get_option('thegem_wpsupercache_error')) {
			$data['wpsupercache_error'] = get_option('thegem_wpsupercache_error');
			delete_option('thegem_wpsupercache_error');
		}

        $data['delay_js_execution'] = thegem_get_option('delay_js_execution');

        return $data;
    }

    function remove_codemirror($hook) {
        if ($hook == 'thegem_page_thegem-theme-options') {
            wp_dequeue_script('code-editor');
        }
    }

    function enqueuePageFiles($hook) {
        if (strpos($hook, '_page_thegem-theme-options') !== false) {
            $appData = array(
                'isTheme' => true,
                'defaultOptions' => thegem_color_skin_defaults(),
                'options' => $this->getFullOptions(),
                'backups' => $this->getBackupsInfo(),
                'optimizer' => $this->getOptimizerInfo(),
                'fonts' => $this->getFontsInfo(),
                'selfhostedFonts' => $this->getSelfHostedFonts(),
                'pages' => $this->getPagesList(),
                'parentPagesForPortfolioList' => $this->getParentPagesForPortfolioList(),
                'patternsUrl' => THEGEM_THEME_URI.'/images/backgrounds/patterns/',
                'iconPacks' => $this->getIconPacks(),
                'isWooCommerce' => defined( 'WC_PLUGIN_FILE' ) ? true : false,
                'isWishlist' => defined( 'YITH_WCWL' ) ? true : false,
                'presetsUrl' => THEGEM_THEME_URI.'/images/backgrounds/presets/',
            );

            $this->addAppData($appData);
        }

        if (preg_match('%^thegem_page_(thegem-.+|install-required-plugins)$%', $hook, $matches) || in_array($hook, array(
            'toplevel_page_thegem-dashboard-welcome','admin_page_thegem-importer','admin_page_thegem-dashboard-importer'))) {

            switch ($hook) {
                case 'admin_page_thegem-importer':
                    $dashboardPage = 'thegem-importer';
                    break;
                case 'admin_page_thegem-dashboard-importer':
                    $dashboardPage = 'thegem-dashboard-importer';
                    break;
                case 'toplevel_page_thegem-dashboard-welcome':
                    $dashboardPage = 'thegem-dashboard-welcome';
                    break;
                default:
                    $dashboardPage = $matches[1];
                    break;
            }

            switch($dashboardPage) {
                case 'thegem-dashboard-welcome':
                    $pageData = array(
                        'systemStatus' => $this->getSystemStatus(),
                        'purchaseCode' => $this->getPurchaseCode(),
                        'activated' => get_option('thegem_activation'),
                        'buyUrl' => 'https://themeforest.net/checkout/from_item/16061685?license=regular',
                        'licenseManagerUrl' => 'https://license-manager.codex-themes.com/',
                        'blocksUrl' => 'https://docs.codex-themes.com/category/554-thegem-blocks',
                        'supportUrl' => 'http://codexthemes.ticksy.com/',
                        'documentationUrl' => 'https://docs.codex-themes.com/',
                    );
                    break;

                case 'install-required-plugins':
                    $pageData = array(
                    );
                    break;

                case 'thegem-dashboard-importer':
                    $installed_plugins = get_plugins();
                    $importer_installed = array_key_exists( static::THEGEM_IMPORTER_PLUGIN, $installed_plugins ) || in_array( static::THEGEM_IMPORTER_PLUGIN, $installed_plugins, true );

                    $action = $importer_installed ? 'activate' : 'install';

                    $url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'page' => 'install-required-plugins',
                                'plugin' => preg_replace('%/.*%', '', static::THEGEM_IMPORTER_PLUGIN),
                                'tgmpa-'.$action => $action.'-plugin'
                            ),
                            admin_url( 'admin.php' )
                        ),
                        'tgmpa-' . $action,
                        'tgmpa-nonce'
                    );

                    $pageData = array(
                        'pluginUrl' => $url,
                        'isPluginInstalled' => $importer_installed
                    );
                    break;

                case 'thegem-importer':
                    $pageData = array(
                    );
                    break;

                case 'thegem-dashboard-manual-and-support':
                    $pageData = array(
                    );
                    break;

                case 'thegem-dashboard-system-status':
                    $pageData = array(
                        'systemStatus' => $this->getSystemStatus(),
                    );
                    break;

                case 'thegem-dashboard-changelog':
                    $pageData = array(
                    );
                    break;
            }

            if (isset($pageData)) {
                $appData = array_merge($pageData, array(
                    'isDashboard' => true,
                    'dashboardPage' => $dashboardPage,
                    'activated' => get_option('thegem_activation'),
                    'themeUpdate' => $this->getThemeUpdateInfo(),
                    'pluginElementsUpdate' => $this->getPluginElementsUpdateInfo(),
                    'importPage' => is_plugin_active(static::THEGEM_IMPORTER_PLUGIN) ? 'thegem-importer':'thegem-dashboard-importer'
                ));

                $this->addAppData($appData);
            }
        }
    }

    function parseFontPack($filename,$regexp,$data) {
        $css = file_get_contents(get_template_directory() . $filename);

        preg_match_all('%@font-face[^}]*}%',$css,$matches);
        $fontFaces=implode("\n",$matches[0]);
        $data['fontFaces']=str_replace('..',THEGEM_THEME_URI,$fontFaces);

        preg_match_all($regexp, $css, $matches);
        $data['icons'] = array();
        foreach($matches[1] as $k=>$v) {
            $data['icons'][$matches[2][$k]]=$v;
        }

        return $data;
    }

    private function unitToInt($s) {
        return (int)preg_replace_callback('/(\-?\d+)(.)/', function ($m) {
            return $m[1] * pow(1024, strpos('BKMG', $m[2]));
        }, strtoupper($s));
    }

    function getPluginElementsUpdateInfo() {
        $plugins = get_site_transient('update_plugins');

        if ( isset($plugins->response) && is_array($plugins->response) ) {
            $plugins_ids = array_keys( $plugins->response );
            $plugin_file = 'thegem-elements-elementor/thegem-elements-elementor.php';
            if(in_array($plugin_file, $plugins_ids)) {
                $plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).$plugin_file);
                $plugin_update = $plugins->response[$plugin_file];

                return array(
                    'updateUrl' => esc_url(admin_url('update-core.php')),
                    'currentVersion' => $plugin_data['Version'],
                    'newVersion' => $plugin_update->new_version
                );
            } else {
                if (!is_plugin_active('thegem-elements-elementor/thegem-elements-elementor.php')) {
                    return false;
                }

                $plugin_data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR).'thegem-elements-elementor/thegem-elements-elementor.php');

                return array(
                    'currentVersion' => $plugin_data['Version'],
                );
            }
        }
        return false;
    }

    function getThemeUpdateInfo() {
        if ( !current_user_can('update_themes' ) )
            return false;
		$themes_update = get_site_transient('update_themes');

        if ( isset($themes_update->response['thegem']) ) {
            $update = $themes_update->response['thegem'];
            $theme = wp_prepare_themes_for_js( array( wp_get_theme('thegem') ) );
            if(isset($theme[0]) && isset($theme[0]['hasUpdate']) && $theme[0]['hasUpdate']) {
                return array(
                    'detailsUrl' => add_query_arg(array(), $update['url']),
                    'updateUrl' => admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( 'thegem' ) ."&_wpnonce=". wp_create_nonce('upgrade-theme_thegem') ),
                    'version' => $update['new_version']
                );
            }
        }

        return false;
    }

    function formatBytes($bytes, $precision = 2) {
        $unit = array("B", "KB", "MB", "GB");
        $exp = floor(log($bytes, 1024)) | 0;
        return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
    }

    function getApiServerConnection() {
        $ok = get_transient('thegem_theme_options_server_connection');

        if ($ok === false) {
            $result = wp_remote_get( 'http://democontent.codex-themes.com/av_validate_code.php', array(
                'timeout'     => 5,
            ) );

            $ok = is_wp_error($result) ? false : ($result['response']['code'] == 200);

            set_transient('thegem_theme_options_server_connection',$ok ? 1:0 ,$ok ? 300:60);
        }

        return boolval($ok);
    }

    function getUploadsFolderWritable() {
        $ok = get_transient('thegem_theme_options_uploads_writable');

        if ($ok === false) {
            $result = wp_upload_bits('thegem_test.jpg',null,base64_decode('/9j/2wBDAAMCAgICAgMCAgIDAwMDBAYEBAQEBAgGBgUGCQgKCgkICQkKDA8MCgsOCwkJDRENDg8QEBEQCgwSExIQEw8QEBD/yQALCAABAAEBAREA/8wABgAQEAX/2gAIAQEAAD8A0s8g/9k='));

            $ok = !boolval($result['error']);

            if ($ok) {
                unlink($result['file']);
            }

            set_transient('thegem_theme_options_uploads_writable',$ok ? 1:0 ,$ok ? 300:60);
        }

        return boolval($ok);
    }

    function getSystemStatus() {
        $data = array(
            'apiServerConnection' => $this->getApiServerConnection(),
            'uploadsFolderWritable' => $this->getUploadsFolderWritable(),
            'activated' => boolval(get_option('thegem_activation')),
            'pluginElementsActive' => !!thegem_is_plugin_active('thegem-elements-elementor/thegem-elements-elementor.php'),
            'pageBuilderVersion' => !!defined( 'ELEMENTOR_VERSION' ),
            'phpVersion' => PHP_VERSION,
            'phpMemoryLimit' => $this->unitToInt(ini_get('memory_limit')),
            'phpTimeLimit' => ini_get('max_execution_time'),
            'phpMaxInputVars' => ini_get('max_input_vars'),
            'phpPostMaxSize' => $this->unitToInt(ini_get('post_max_size')),
            'phpUploadMaxFilesize' => $this->unitToInt(ini_get('upload_max_filesize')),
            'wpVersion' => $GLOBALS['wp_version'],
            'wpFileSystem' => get_filesystem_method(),
            'blogLanguage' => get_bloginfo('language'),
            'siteUrl' => get_site_url(),
            'direction' => is_rtl() ? 'RTL':'LTR',
            'homeUrl' => get_home_url()
        );

        $data=array_merge($data, array(
            'phpMemoryLimitFormatted' => $this->formatBytes($data['phpMemoryLimit']),
            'phpMemoryLimitOk' => $data['phpMemoryLimit'] >= 512*1024*1024,
            'phpVersionOk' => version_compare(PHP_VERSION,'5.6.22'),
            'phpTimeLimitOk' => $data['phpTimeLimit'] >= 180,
            'phpMaxInputVarsOk' => $data['phpMaxInputVars'] >= 4000,
            'phpPostMaxSizeFormatted' => $this->formatBytes($data['phpPostMaxSize']),
            'phpPostMaxSizeOk' => $data['phpPostMaxSize'] >= 96*1024*1024,
            'phpUploadMaxFilesizeFormatted' => $this->formatBytes($data['phpUploadMaxFilesize']),
            'phpUploadMaxFilesizeOk' => $data['phpUploadMaxFilesize'] >= 16*1024*1024,
            'wpVersionOk' => version_compare(get_bloginfo('version'), '5.2.0') >= 0,
        ));

        return $data;
    }

    function getIconPacksInfo() {
        $fontpacks = array(
            $this->parseFontPack('/css/icons-material.css','%\.mdi-([^:]*).*?content:\s*".([^"]*)%s', array(
                'title'=>'Material Design Icons',
                'icon'=>'font-size',
                'value'=>'material',
                'fontFamily'=>'MaterialDesignIcons'
            )),
            $this->parseFontPack('/css/icons-fontawesome.css','%\.fa-([^:]*).*?content:\s*".([^"]*)%s', array(
                'title'=>'Font Awesome Icons',
                'icon'=>'font-size',
                'value'=>'fontawesome',
                'fontFamily'=>'FontAwesome',
            )),
            $this->parseFontPack('/css/icons-elegant.css','%\.([^:{}]*):before.*?content:\s*".([^"]*)%s', array(
                'title'=>'Elegant Icons',
                'icon'=>'font-size',
                'value'=>'elegant',
                'fontFamily'=>'ElegantIcons'
            )),
        );

        return $fontpacks;
    }

    function prePageWrapper($page) {
        $this->renderDashboardPage();
        echo '<div class="thegem-install-plugins-wrapper">';
    }

    function postPageWrapper($page) {
        echo "</div>";
        echo
        "<style>
            .wrap{
                position: relative;
                z-index: 100;
            }
            .dashboard + .wrap{
                margin-top: -50px;
            }
            .thegem-importer-header{
                background-color: #ffffff;
                margin-top: 0;
            }
            .thegem-importer-content{
                padding-top: 20px;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }
            .thegem-importer-header > .thegem-importer-logo{
                display: none;
            }
             .thegem-importer-header > .thegem-importer-title{
                font-family: 'Montserrat UltraLight';
                margin-left: 0;
                color: #3c3950;
            }
            .thegem-importer-header > .thegem-importer-remover a:hover {
                background-color: #3c3950;
            }

            .tgmpa{
                background-color: #ffffff;
                padding: 0 40px 40px 40px;
            }
            .tgmpa.wrap{
                margin-top: -20px;
            }
            .tgmpa.wrap > h1{
                display: none;
            }
        </style>";
    }

    function getFontsInfo() {
        $fonts = array();
        foreach(thegem_fonts_list(true) as $font) {
            $fontData=array(
                'family' => $font['family'],
            );

            if (!empty($font['subsets'])) {
                $fontData['subsets']=$font['subsets'];
            }

            $fonts[]=$fontData;
        }

        return $fonts;
    }

    function filesystemCredentials() {
        ob_start();

        $url = wp_nonce_url('admin_ajax.php?page=thegem-theme-options','thegem-theme-options');
        $creds = request_filesystem_credentials($url, '', false, get_stylesheet_directory() . '/css/', array('action'=>'wp_ajax_thegem_theme_options_credentials'));
        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($url, '', false, get_stylesheet_directory() . '/css/', array('action'=>'wp_ajax_thegem_theme_options_credentials'));
        }
        $form = ob_get_clean();

    }

    function getCredentialsForm($additionalText) {
        $form = ob_get_clean();

        if ($additionalText) {
           $form = preg_replace('%<[^>]*id="request-filesystem-credentials-desc[^>]*>%', "$0<strong>$additionalText</strong><br/><br/>", $form);
        }

        return $form;
    }

    function thegem_generate_custom_css($additionalText= false) {
        thegem_get_option(false, false, false, true);
        ob_start();
        thegem_custom_fonts();
        require get_template_directory() . '/inc/custom-css.php';
        if(file_exists(get_stylesheet_directory() . '/inc/custom-css.php') && get_stylesheet_directory() != get_template_directory()) {
            require get_stylesheet_directory() . '/inc/custom-css.php';
        }
        $custom_css = ob_get_clean();

        $editor_bg = '#ffffff';
        $body_color = thegem_get_option('body_color');
        $body_color = str_replace('#', '', $body_color);
        if(strlen($body_color) >= 6 ) {
            $r = hexdec(substr($body_color, 0, 2));
            $g = hexdec(substr($body_color, 2, 2));
            $b = hexdec(substr($body_color, 4, 2));
            $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
            if($brightness > 128) {
                $editor_bg = '#dfe5e8';
            }
        }

        ob_start();
        require get_template_directory() . '/inc/style-editor-css.php';
        $editor_css = ob_get_clean();
        $action = array('action');

        ob_start();

        $old_name = thegem_get_custom_css_filename();
        $new_name = thegem_generate_custom_css_filename();
        $upload_dir = wp_upload_dir();
        $folder_path = $upload_dir['basedir'] . '/thegem/css/';
        if ( ! file_exists( $folder_path ) ) {
            wp_mkdir_p( $folder_path );
        }
        if(!file_put_contents( $folder_path . $new_name . '.css', $custom_css )) {
            update_option('thegem_genearte_css_error', '1');
            return array('reason'=>'error','message'=>sprintf(esc_html__('TheGem\'s styles cannot be customized because file "%s" cannot be modified. Please check your server\'s settings. Then click "Save" button.', 'thegem'), $folder_path . '/custom.css'));
        } else {
            file_put_contents( $folder_path . 'style-editor.css', $editor_css );
            $custom_css_files = glob($folder_path . '/custom-*.css');
            foreach($custom_css_files as $file) {
                if(basename($file, '.css') != $new_name) {
                    unlink($file);
                }
            }
            delete_option( 'rewrite_rules' );
            thegem_save_custom_css_filename($new_name);
            delete_option('thegem_genearte_css_error');
            delete_option('thegem_generate_empty_custom_css_fail');
        }

        return true;
    }

    function default_page_data($data, $post_id, $item_data, $type) {
        $defaults = array(
            'global' => get_option('thegem_options_page_settings_global'),
            'page' => get_option('thegem_options_page_settings_default'),
            'post' => get_option('thegem_options_page_settings_post'),
            'portfolio' => get_option('thegem_options_page_settings_portfolio'),
            'blog' => get_option('thegem_options_page_settings_blog'),
            'search' => get_option('thegem_options_page_settings_search'),
        );
        foreach($data as $key => $value) {
            $data[$key] = !empty($defaults['global'][$key]) ? $defaults['global'][$key] : $data[$key];
            if($type === 'blog' || $type === 'term') {
                $data[$key] = !empty($defaults['blog'][$key]) && thegem_get_option('global_settings_apply_blog'.thegem_get_option_group_by_key($key)) ? $defaults['blog'][$key] : $data[$key];
            } elseif($type === 'search') {
                $data[$key] = !empty($defaults['search'][$key]) && thegem_get_option('global_settings_apply_search'.thegem_get_option_group_by_key($key)) ? $defaults['search'][$key] : $data[$key];
            } else {
                if(in_array(get_post_type($post_id), array('post', 'page', 'portfolio')) || $type === 'default') {
                    $data[$key] = !empty($defaults['page'][$key]) && thegem_get_option('global_settings_apply_default'.thegem_get_option_group_by_key($key)) ? $defaults['page'][$key] : $data[$key];
                }
                if(get_post_type($post_id) === 'post') {
                    $data[$key] = !empty($defaults['post'][$key]) && thegem_get_option('global_settings_apply_post'.thegem_get_option_group_by_key($key)) ? $defaults['post'][$key] : $data[$key];
                }
                if(get_post_type($post_id) === 'portfolio') {
                    $data[$key] = !empty($defaults['portfolio'][$key]) && thegem_get_option('global_settings_apply_portfolio'.thegem_get_option_group_by_key($key)) ? $defaults['portfolio'][$key] : $data[$key];
                }
            }
        }
        return $data;
    }

    function apply_page_settings($page_settings_type) {
        /* update post data */
    }
}

$thegemThemeOptions=new ThegemThemeOptions();
