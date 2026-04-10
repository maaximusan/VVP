<?php
/* автоматическое исчезновение не влезающих пунктов верхнего меню */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'vvp-menu-overflow',
        get_stylesheet_directory_uri() . '/js/vvp-menu-overflow.js',
        array(),
        '2.0.0',
        true
    );

    wp_enqueue_style(
        'vvp-menu-overflow',
        get_stylesheet_directory_uri() . '/css/vvp-menu-overflow.css',
        array(),
        '2.0.0'
    );
}, 30);

/* Отключаем автосохранение в записях */
add_action('admin_init', function() {
    global $pagenow;

    if ($pagenow === 'post.php' || $pagenow === 'post-new.php') {
        wp_deregister_script('autosave');
    }
});