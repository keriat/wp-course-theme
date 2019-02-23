<?php

add_action('wp_enqueue_scripts', function () {
    $version = '0.1';
//<!-- Framework Css -->
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/lib/bootstrap.min.css', [], $version);
//    <!-- Owl Carousel -->
    wp_enqueue_style('owl', get_stylesheet_directory_uri() . '/assets/css/lib/owl.carousel.min.css', ['bootstrap'], $version);

    wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Muli:300,400,400i,600,700,700i,800,900', [], $version);

    wp_enqueue_style('slick', get_stylesheet_directory_uri() . '/assets/css/lib/slick.css', [], $version);
    wp_enqueue_style('animation', get_stylesheet_directory_uri() . '/assets/css/lib/animations.min.css', [], $version);
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/assets/css/style.css', ['font'], $version);
    wp_enqueue_style('responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css', [], $version);

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.3.1.min.js', 'jquery', $version);
    wp_enqueue_script('popper', get_stylesheet_directory_uri() . '/assets/js/lib/popper.min.js', 'jquery', $version);
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/lib/bootstrap.min.js', 'jquery', $version);
    wp_enqueue_script('owl.carousel', get_stylesheet_directory_uri() . '/assets/js/lib/owl.carousel.min.js', 'jquery', $version);
    wp_enqueue_script('masonry.pkgd', get_stylesheet_directory_uri() . '/assets/js/lib/masonry.pkgd.min.js', 'jquery', $version);
    wp_enqueue_script('slick', get_stylesheet_directory_uri() . '/assets/js/lib/slick.min.js', 'jquery', $version);
    wp_enqueue_script('css3-animate-it', get_stylesheet_directory_uri() . '/assets/js/lib/css3-animate-it.js', 'jquery', $version);
    wp_enqueue_script('main', get_stylesheet_directory_uri() . '/assets/js/main.js', 'jquery', $version);
    wp_localize_script('main', 'wordpress_vars', [
        'ajaxurl' => get_admin_url() . 'admin-ajax.php'
    ]);

});
//    <!-- Slick Carousel -->
//    <link rel="stylesheet" type="text/css" href="{{ site.theme.link }}/assets/css/lib/slick.css">
//    <!-- Animation -->
//    <link rel="stylesheet" type="text/css" href="{{ site.theme.link }}/assets/css/lib/.min.css">
//    <!-- Google Font -->
//    <link href="" rel="stylesheet">
//    <!-- Style Theme -->
//    <link rel="stylesheet" type="text/css" href="{{ site.theme.link }}/assets/css/.css">
//    <!-- Responsive Theme -->
//    <link rel="stylesheet" type="text/css" href="{{ site.theme.link }}/assets/css/.css">


//wp_enqueue_style();