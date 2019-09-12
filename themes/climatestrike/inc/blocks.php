<?php

class xr_Blocks {
    function __construct()
    {
        register_block_type('xr/hero', [
            'render_callback' => array($this, 'heroRender'),
        ]);
    }

    function heroRender($attributes)
    {
        $image = new TimberImage($attributes['imgId']);
        $templateArgs = array();

        $templateArgs['image'] = $image;
        $templateArgs['title'] = get_the_title();

        if (isset($image->sizes['xr_hero_large'])) {
            $templateArgs['large'] = $image->src('xr_hero_large');
        }

        if (isset($image->sizes['xr_hero_medium'])) {
            $templateArgs['medium'] = $image->src('xr_hero_medium');
        }

        if (isset($image->sizes['xr_hero_small'])) {
            $templateArgs['small'] = $image->src('xr_hero_small');
        }

        $templateArgs['full'] = $image->src('full');

        $output = Timber::compile('blocks/hero.twig', $templateArgs);

        return $output;
    }
}
// add_action('init', function () {
//     new xr_Blocks;
// });
