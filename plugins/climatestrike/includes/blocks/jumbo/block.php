<?php

class climatestrike_Jumbo
{
    function __construct() {}

    function blockRegistration()
    {
        // Editor Script
        wp_register_script(
            'climatestrike-jumbo-block',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        // Editor Style
        wp_register_style(
            'climatestrike-jumbo-block',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        register_block_type('climatestrike/jumbo', array(
            'editor_script' => 'climatestrike-jumbo-block',
            'editor_style'  => 'climatestrike-jumbo-block',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['content'] = $content;
        $context['attributes'] = $attributes;

        $output = Timber::compile('blocks/jumbo.twig', $context);

        return $output;
    }
}

add_action('init', function(){
    $block = new climatestrike_Jumbo();
    $block->blockRegistration();
});
