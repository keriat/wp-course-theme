<?php

add_action( 'wp_enqueue_scripts', function(){
    $version = '0.1';
//<!-- Framework Css -->
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri().'/assets/css/lib/bootstrap.min.css', [], $version );
//    <!-- Owl Carousel -->
    wp_enqueue_style('owl', get_stylesheet_directory_uri().'/assets/css/lib/owl.carousel.min.css', ['bootstrap'], $version );

    wp_enqueue_style('font', '//fonts.googleapis.com/css?family=Muli:300,400,400i,600,700,700i,800,900', [], $version );

    wp_enqueue_style('slick', get_stylesheet_directory_uri().'/assets/css/lib/slick.css', [], $version );
    wp_enqueue_style('animation', get_stylesheet_directory_uri().'/assets/css/lib/animations.min.css', [], $version );
    wp_enqueue_style('style', get_stylesheet_directory_uri().'/assets/css/style.css', ['font'], $version );
    wp_enqueue_style('responsive', get_stylesheet_directory_uri().'/assets/css/responsive.css', [], $version );

} );
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