<?php

class climatestrike_Nearest
{
    function __construct() {}

    function blockRegistration()
    {
        // Editor Script
        wp_register_script(
            'climatestrike-nearest-strike',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        // Editor Style
        wp_register_style(
            'climatestrike-nearest-strike',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        register_block_type('climatestrike/nearest-strike', array(
            'editor_script' => 'climatestrike-nearest-strike',
            'editor_style'  => 'climatestrike-nearest-strike',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['content'] = $content;
        $output = Timber::compile('blocks/nearest-strike.twig', $context);

        return $output;
    }
}

add_action('init', function(){
    $block = new climatestrike_Nearest();
    $block->blockRegistration();
});
