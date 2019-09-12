<?php

class climatestrike_Callout
{
    function __construct() {}

    function blockRegistration()
    {
        // Editor Script
        wp_register_script(
            'climatestrike-callout-block',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        // Editor Style
        wp_register_style(
            'climatestrike-callout-block',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        register_block_type('climatestrike/callout', array(
            'editor_script' => 'climatestrike-callout-block',
            'editor_style'  => 'climatestrike-callout-block',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['content'] = $content;
        if(array_key_exists('thumbnail', $attributes)) $context['thumbnail'] = new TimberImage($attributes['thumbnail']);
        if(array_key_exists('text', $attributes)) $context['text'] = $attributes['text'];
        if(array_key_exists('href', $attributes)) $context['href'] = $attributes['href'];

        $context['attributes'] = $attributes;

        $output = Timber::compile('blocks/callout.twig', $context);

        return $output;
    }
}

add_action('init', function(){
    $block = new climatestrike_Callout();
    $block->blockRegistration();
});
