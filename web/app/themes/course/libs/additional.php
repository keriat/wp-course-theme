<?php
load_theme_textdomain('wp-course', get_template_directory() . '/assets/languages');

function create_post_type() {

    add_image_size('220x220',220, 220, true);
    add_image_size('100x100',100, 100, true);


    wp_set_lang_dir();
}
add_action( 'init', 'create_post_type' );