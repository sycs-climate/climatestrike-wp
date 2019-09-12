<?php

Timber::$dirname = array('templates', 'views');

class climatestrike_TimberSite extends TimberSite 
{
    function __construct() {
        add_filter('timber_context', array($this, 'addToContext'));
        parent::__construct();
    }

    function anForm() {
        /* Action Network sign up form, when I get to that */
        return "";
    }

    function addToContext($context) {
        global $post;

        $context['menu_primary_navigation'] = new TimberMenu('primary-navigation');
        $context['menu_socials'] = new TimberMenu('socials');
        $context['menu_footer'] = new TimberMenu('footer');
        $context['site'] = $this;
        $context['anForm'] = $this->anForm();

        /*if ($post) {
            $heroVideoBlock = new xr_HeroVideoBlock();
            $context['videoHero'] = $heroVideoBlock->getVideoId($post->ID, true);

            $heroBlock = new xr_HeroBlock();
            $context['hero'] = $heroBlock->getHero($post->ID, true);
        }*/

        return $context;
    }
}

new climatestrike_TimberSite();
