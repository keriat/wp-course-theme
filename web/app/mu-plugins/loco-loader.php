<?php
/*
Plugin Name: Loco Standalone Loader
Description: Mimics Loco_hooks_LoadHelper without dependency on the main plugin
Author: Tim Whitlock
Version: 1.0
*/

new LocoStandaloneLoadHelper;


/**
 * Standalone version of Loco_hooks_LoadHelper in main plugin.
 * Differences:
 * - Manually hooked in constructor
 * - Does not call `loco_constant`
 * - Self-destructs if main plugin enabled
 */
class LocoStandaloneLoadHelper {

    /**
     * Singleton
     * @var LocoStandaloneLoadHelper
     */
    private static $hooked;

    /**
     * @var array [ $subdir, $domain, $locale ]
     */
    private $context;

    /**
     * @var array
     */
    private $lock = array();


    /**
     * @internal
     */
    public function __construct(){
        add_filter( 'theme_locale', array($this,'filter_theme_locale'), 10, 2 );
        add_filter( 'plugin_locale', array($this,'filter_plugin_locale'), 10, 2 );
        add_action( 'load_textdomain', array($this,'on_load_textdomain'), 10, 2 );
        add_action( 'unload_textdomain', array($this,'on_unload_textdomain'), 10, 1 );
        // maintain singleton
        if( self::$hooked ){
            self::$hooked->destroy();
        }
        self::$hooked = $this;
    }


    /**
     * @internal
     */
    private function destroy(){
        remove_filter( 'theme_locale', array($this,'filter_theme_locale'), 10 );
        remove_filter( 'plugin_locale', array($this,'filter_plugin_locale'), 10 );
        remove_action( 'load_textdomain', array($this,'on_load_textdomain'), 10 );
        remove_action( 'unload_textdomain', array($this,'on_unload_textdomain'), 10 );
        if( self::$hooked === $this ){
            self::$hooked = null;
        }
    }


    /**
     * Abandon this plugin if main Loco Translate plugin becomes enabled
     * @return bool whether still required
     */
    private function required(){
        if( $hasLoco = class_exists('Loco_hooks_LoadHelper') ){
            $this->destroy();
        }
        return ! $hasLoco;
    }


    /**
     * `theme_locale` filter callback.
     * Signals the beginning of a "load_theme_textdomain" process
     */
    public function filter_theme_locale( $locale, $domain = '' ){
        if( $this->required() ){
            $this->context = array( 'themes', $domain, $locale );
            unset( $this->lock[$domain] );
        }
        return $locale;
    }



    /**
     * `plugin_locale` filter callback.
     * Signals the beginning of a "load_plugin_textdomain" process
     */
    public function filter_plugin_locale( $locale, $domain = '' ){
        if( $this->required() ){
            $this->context = array( 'plugins', $domain, $locale );
            unset( $this->lock[$domain] );
        }
        return $locale;
    }


    /**
     * `unload_textdomain` action callback.
     * Lets us release lock so that custom file may be loaded again (hopefully for another locale)
     */
    public function on_unload_textdomain( $domain ){
        if( $this->required() ){
            unset( $this->lock[$domain] );
        }
    }



    /**
     * `load_textdomain` action callback.
     * Lets us load our custom translations before WordPress loads what it was going to anyway.
     * We're deliberately not stopping WordPress loading $mopath, if it exists it will be merged on top of our custom strings.
     * @return void
     */
    public function on_load_textdomain( $domain, $mopath ){
        if( ! $this->required() ){
            return;
        }

        $key = '';
        // domains may be split into multiple files
        $name = pathinfo( $mopath, PATHINFO_FILENAME );
        if( $lpos = strrpos( $name, '-') ){
            $slug = substr( $name, 0, $lpos );
            if( $slug !== $domain ){
                $key = $slug;
            }
        }
        // avoid recursion when we've already handled this domain/slug
        if( isset($this->lock[$domain][$key]) ){
            return;
        }

        // language roots
        $wp_lang_dir = trailingslashit( WP_LANG_DIR );
        $lc_lang_dir = defined('LOCO_LANG_DIR') ? trailingslashit(LOCO_LANG_DIR) : $wp_lang_dir.'loco/';

        // if context is set, then a theme or plugin initialized the loading process properly
        if( is_array($this->context) ){
            list( $subdir, $_domain, $locale ) = $this->context;
            $this->context = null;
            // It shouldn't be possible to catch a different domain after setting context, but we'd better bail just in case
            if( $_domain !== $domain ){
                return;
            }
            $mopath = $lc_lang_dir.$subdir.'/'.$domain.'-'.$locale.'.mo';
        }

        // else load_textdomain must have been called directly to bypass locale filters
        else {
            $snip = strlen($wp_lang_dir);
            // direct file loads must be under WP_LANG_DIR if we are to map them
            if( substr( dirname($mopath).'/', 0, $snip ) === $wp_lang_dir ){
                $mopath = substr_replace( $mopath, $lc_lang_dir, 0, $snip );
            }
            // else no way to map files from WP_LANG_DIR to LOCO_LANG_DIR
            else {
                return;
            }
        }

        // Load our custom translations avoiding recursion back into this hook
        $this->lock[$domain][$key] = true;
        load_textdomain( $domain, $mopath );
    }

}