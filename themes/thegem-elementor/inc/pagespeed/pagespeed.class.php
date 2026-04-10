<?php

require get_template_directory() . '/inc/pagespeed/lazy-items.class.php';


class TGM_PageSpeed {
    public static function activate() {
        self::activateComponents();
    }

    private static function activateComponents() {
        if(thegem_get_option('page_speed_image_load') == 'js') {
            self::activateLazyItemsComponent();
        }
        if(thegem_get_option('page_speed_image_load') == 'native') {
            self::activateNativeLazyItemsComponent();
        }
    }

    private static function activateLazyItemsComponent() {
        $lazyItems = new TGM_PageSpeed_Lazy_Items();
    }

    private static function activateNativeLazyItemsComponent() {
        $lazyItems = new TGM_PageSpeed_Native_Lazy_Items();
    }
}
