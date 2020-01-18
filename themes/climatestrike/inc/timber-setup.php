<?php

Timber::$dirname = array('templates', 'views');

class climatestrike_TimberSite extends TimberSite 
{
    function __construct() {
        add_filter('timber_context', array($this, 'addToContext'));
        parent::__construct();
    }

    function addToContext($context) {
        global $post;

        $context['menu_primary_navigation'] = new TimberMenu('primary-navigation');
        $context['menu_socials'] = new TimberMenu('socials');
        $context['menu_footer'] = new TimberMenu('footer');
        $context['site'] = $this;
    
        $context['options'] = array(
            'date_format' => get_option('date_format'),
            'time_format' => get_option('time_format')
        );

        return $context;
    }
}

new climatestrike_TimberSite();
