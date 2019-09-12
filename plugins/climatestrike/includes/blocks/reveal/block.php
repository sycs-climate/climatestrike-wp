<?php

/*
 * Stolen from XR
 */

class climatestrike_Reveal
{
    function __construct() {}

    function blockRegistration()
    {
        wp_register_script(
            'climatestrike-reveal',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        wp_register_style(
            'climatestrike-reveal',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        register_block_type('climatestrike/reveal', array(
            'editor_script' => 'climatestrike-reveal',
            'editor_style'  => 'climatestrike-reveal',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['title'] = $attributes['title'];
        if (isset($attributes['visibleText'])) {
            $context['visibleText'] = $attributes['visibleText'];
        }
        $context['hiddenContent'] = $content;

        return Timber::compile('blocks/reveal.twig', $context);
    }
}

add_action('init', function(){
    $block = new climatestrike_Reveal();
    $block->blockRegistration();
});
