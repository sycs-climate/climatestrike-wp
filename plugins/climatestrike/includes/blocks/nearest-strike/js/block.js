function climatestrike_NearestStrike(){
    const { registerBlockType } = wp.blocks;
    
    const {} = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {} = wp.components;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/nearest-strike', {
        title: 'Nearest Strike Locator',
        icon: 'search',
        category: 'common',
        supports: {
            customClassName: true,
        },
        attributes: {},

        edit: function(props) {
            
             return el('div', { className: props.className }, 
                el('p', {}, "Nearest Strike")
           );
        },

        save: function(props) {
            return null;
        },
    });
}

climatestrike_NearestStrike();
