<?php

class climatestrike_ThemeSetup 
{
    function __construct() {
        $this->addSupport();
        $this->menus();
        $this->sidebars();
        $this->assets();
    }

    function addSupport() {
        /*add_theme_support(
            'custom-header',
            array(
                'header-text'   => true
            )
        );*/

        add_theme_support('post-thumbnails');
    }

    function menus() {
        register_nav_menus(
            array(
                'primary-navigation' => __('Primary Navigation', 'climatestrike'),
                'socials' => __('Social Media', 'climatestrike'),
                'footer' => __('Footer Links', 'climatestrike')
            )
        );
    }

    function sidebars() {
        register_sidebar(
            array(
                'name'              => 'Page Footer',
                'id'                => 'footer'
            )
        );
    }

    function assets() { 
        add_action('wp_enqueue_scripts', function() {

            // styles
            wp_register_style('climatestrike-style', get_template_directory_uri() . '/css/main.css');
            wp_enqueue_style('climatestrike-style');

            // scripts
            $mainScript = (WP_ENV == 'prod' ? 'main.min.js' : 'main.js');
            wp_register_script(
                'climatestrike-main', 
                get_template_directory_uri() . '/js/' . $mainScript, 
                array('jquery')
            );
            wp_enqueue_script('climatestrike-main');
        });
    }
}

if(!function_exists('climatestrike_ThemeSetupInit')) {
    function climatestrike_ThemeSetupInit() {
        new climatestrike_ThemeSetup();
    }
}
add_action('after_setup_theme', 'climatestrike_ThemeSetupInit');
