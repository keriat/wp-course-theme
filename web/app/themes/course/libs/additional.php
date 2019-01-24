<?php
load_theme_textdomain('wp-course', get_template_directory() . '/assets/languages');

function create_post_type() {
    register_post_type( 'events',
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'events'),
        )
    );
    add_image_size('220x220',220, 220, true);
    add_image_size('100x100',100, 100, true);


    wp_set_lang_dir();
}
add_action( 'init', 'create_post_type' );