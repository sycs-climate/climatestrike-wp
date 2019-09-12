<?php

class climatestrike_BackgroundContainer
{
    function __construct() {}

    function blockRegistration()
    {
        // Editor Script
        wp_register_script(
            'climatestrike-background-container-block',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        // Editor Style
        wp_register_style(
            'climatestrike-background-container-block',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        register_block_type('climatestrike/background-container', array(
            'editor_script' => 'climatestrike-background-container-block',
            'editor_style'  => 'climatestrike-background-container-block',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['content'] = $content;
        $context['background_color'] = $attributes['background_color'];

        $context['attributes'] = $attributes;

        $output = Timber::compile('blocks/background-container.twig', $context);

        return $output;
    }
}

add_action('init', function(){
    $block = new climatestrike_BackgroundContainer();
    $block->blockRegistration();
});
