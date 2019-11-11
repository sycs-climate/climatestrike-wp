<?php

class climatestrike_SignupForm
{
    function __contruct() {}

    function blockRegistration()
    {
        // Editor Script
        wp_register_script(
            'climatestrike-signup-form-block',
            plugins_url('js/block.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-editor'
            )
        );

        // Editor Style
        wp_register_style(
            'climatestrike-signup-form-block',
            plugins_url('css/editor.css', __FILE__),
            array('wp-edit-blocks'),
            filemtime(plugin_dir_path( __FILE__ ) . 'css/editor.css')
        );

        // Frontend Script
        wp_register_script(
            'climatestrike-signup-form',
            plugins_url('js/submit.js', __FILE__),
            array(
                'jquery'
            )
        );
        wp_localize_script('climatestrike-signup-form', 'ajax_url', admin_url('admin-ajax.php'));

        register_block_type('climatestrike/signup-form', array(
            'editor_script' => 'climatestrike-signup-form-block',
            'editor_style'  => 'climatestrike-signup-form-block',
            'script' => 'climatestrike-signup-form',
            'render_callback' => array($this, 'render'),
        ));
    }

    function render($attributes, $content = '')
    {
        $context['content'] = $content;
        $context['title'] = $attributes['title'];
        $context['button'] = $attributes['button'];
        $context['join'] = $attributes['join'];

        $context['attributes'] = $attributes;

        $output = Timber::compile('blocks/signup-form.twig', $context);
    
        return $output;
    }
}

add_action('init', function(){
    $block = new climatestrike_SignupForm();
    $block->blockRegistration();
});
