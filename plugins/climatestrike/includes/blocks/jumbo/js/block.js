function climatestrike_BackgroundContainer(){
    const { registerBlockType } = wp.blocks;

    const { 
        RichText
    } = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {
    } = wp.components;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/jumbo', {
        title: 'Jumbo',
        icon: 'format-image',
        category: 'common',
        supports: {
            customClassName: false,
        },
        attributes: {
            text: {
                type: 'string',
                default: ''
            }
        },

        edit: function(props) {
            
            return [
                el(RichText, 
                    {
                        tagName: 'p',
                        className: props.className,
                        onChange: ( value ) => {
                            props.setAttributes( { text: value } );
                        },
                        value: props.attributes.text
                    }
                )
            ];
        },

        save: function(props) {
            return (
                props.attributes.text
            );
        },
    });
}

climatestrike_BackgroundContainer();
