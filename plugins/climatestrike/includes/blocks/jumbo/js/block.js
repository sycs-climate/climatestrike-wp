function climatestrike_BackgroundContainer(){
    const { registerBlockType } = wp.blocks;

    const { 
        RichText,
        InspectorControls
    } = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {
        PanelBody,
        PanelRow,
        TextControl
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
            },
            id: {
                type: 'string',
                default: null
            }
        },

        edit: function(props) {
            
            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Meta' },
                        el(PanelRow, {},
                            el(TextControl, 
                                {
                                    label: 'id',
                                    onChange: ( value ) => {
                                        props.setAttributes( { id: value } );
                                    },
                                    value: props.attributes.id
                                }
                            )
                        )
                    )
                ),
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
