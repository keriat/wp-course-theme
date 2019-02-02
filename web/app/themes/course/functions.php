<?php
require 'libs/additional.php';
require 'libs/assets.php';

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });

    add_filter('template_include', function ($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
    });

    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_filter('timber_context', array($this, 'add_to_context'));
        add_filter('get_twig', array($this, 'add_to_twig'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        parent::__construct();
    }

    /** This is where you can register custom post types. */
    public function register_post_types()
    {
        register_post_type('events',
            array(
                'labels'      => array(
                    'name'          => __('Events'),
                    'singular_name' => __('Event')
                ),
                'public'      => true,
                'has_archive' => true,
                'rewrite'     => array('slug' => 'events'),
            )
        );
        register_post_type('team',
            array(
                'labels'              => array(
                    'name'          => __('Команда'),
                    'singular_name' => __('Сотрудник')
                ),
                'menu_icon'           => 'dashicons-groups',
                'public'              => true,
                'exclude_from_search' => true,
                'has_archive'         => true,
                'rewrite'             => array('slug' => 'events'),
            )
        );
    }

    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {
        register_taxonomy('team-roles', 'team', [
            'labels'       => [
                'name'          => __('Должности'),
                'singular_name' => __('Должность')
            ],
            'hierarchical' => true,
//            'capabilities' => [
//                'manage_terms' => 'manage_team',
//                'edit_terms'   => 'edit_team',
//                'delete_terms' => 'delete_team',
//                'assign_terms' => 'assign_team',
//            ]
        ]);
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['foo']         = 'bar';
        $context['stuff']       = 'I am a value set in your functions.php file';
        $context['notes']       = 'These values are available everytime you call Timber::get_context()';
        $context['header_menu'] = new Timber\Menu('header_menu');
        $context['footer_menu'] = new Timber\Menu('footer_menu');
        $context['team']        = new Timber\PostQuery(['post_type' => 'team']);

        $context['sidebar_footer'] = Timber::get_widgets('sidebar_footer');

        $context['site'] = $this;

        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5', array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats', array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');
        register_nav_menus([
            'header_menu' => 'Меню в шапке',
            'footer_menu' => 'Меню в подвале',
        ]);

        add_theme_support('widgets');
        register_sidebar([
            'name' => 'Сайдбар в футере',
            'id'   => 'sidebar_footer',
            'before_widget' => '<div class="col-xl-3 col-lg-2 col-md-6 col-12"><div class="inside">',
            'after_widget'  => '</div></div>',
            'before_title'  => '<h4 class="title">',
            'after_title'   => '</h4>',
        ]);
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo($text)
    {
        $text .= ' bar!';

        return $text;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig_Extension_StringLoader());
        $twig->addFilter(new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));

        return $twig;
    }

}

new StarterSite();

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Настройки темы',
        'menu_title' => 'Наши Опции',
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Theme Header Settings',
        'menu_title'  => 'Header',
        'parent_slug' => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Theme Footer Settings',
        'menu_title'  => 'Footer',
        'parent_slug' => 'theme-general-settings',
    ));

}