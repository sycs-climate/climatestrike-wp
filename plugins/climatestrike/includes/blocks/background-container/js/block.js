function climatestrike_BackgroundContainer(){
    if (!registerBlockType) {
        const { registerBlockType } = wp.blocks;
    }

    
    const { 
        InspectorControls,
        MediaUpload,
        InnerBlocks 
    } = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {
        Button,
        PanelBody,
        PanelRow,
        TextControl,
        ColorPicker
    } = wp.components;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/background-container', {
        title: 'Background Container',
        icon: 'format-image',
        category: 'layout',
        supports: {
            customClassName: false,
        },
        attributes: {
            background_color: {
                type: 'object',
                default: { r: 0, g: 0, b: 0, a: 0 }
            },
            background_image: {
                type: 'object',
                default: null
            },
            foreground_color: {
                type: 'string',
                default: '#000'
            },
            min_height: {
                type: 'string',
                default: null
            }
        },

        edit: function(props) {
            
            return [
                el(InspectorControls, {},
                    el(PanelBody, {title: 'Background Image'}, 
                        el(PanelRow, {}, 
                            el(MediaUpload, 
                                {
                                    type: 'image',
                                    onSelect: (image) => {
                                        props.setAttributes( { background_image: image } );
                                    },
                                    render: (obj) => {
                                        if(props.attributes.background_image) {
                                            return el('div', {},
                                                el('img', {src: props.attributes.background_image.url, onClick: obj.open, className: 'components-media-upload-img'}),
                                                el(Button, 
                                                    {
                                                        className: 'components-button components-media-clear-button', 
                                                        onClick: function() {
                                                            props.setAttributes( { background_image: null } );
                                                        }
                                                    }, 'Remove background image'
                                                )
                                            )
                                        } else {
                                            return el(Button, {className: 'components-button components-media-upload-button', onClick: obj.open}, 'Set background image')
                                        }
                                    }
                                }
                            )
                        )
                    ),
                    el(PanelBody, {title: 'Background Colour'},
                        el(PanelRow, {}, 
                            el(ColorPicker, 
                                {
                                    onChangeComplete: ( value ) => {
                                        props.setAttributes( { background_color: value.rgb } );
                                    },
                                    color: props.attributes.background_color
                                }
                            )
                        )
                    ),
                    el(PanelBody, {title: 'Foreground Colour'},
                        el(PanelRow, {}, 
                            el(ColorPicker, 
                                {
                                    onChangeComplete: ( value ) => {
                                        props.setAttributes( { foreground_color: value.hex } );
                                    },
                                    color: props.attributes.foreground_color
                                }
                            )
                        )

                    ),
                    el(PanelBody, {title: 'Size'},
                        el(PanelRow, {}, 
                            el(TextControl, 
                                {
                                    label: 'min-height',
                                    onChange: ( value ) => {
                                        props.setAttributes( { min_height: value } );
                                    },
                                    value: props.attributes.min_height
                                }
                            )
                        )
                    )
                ),
                el('div', {className: props.className, style: { backgroundColor: `rgb(${props.attributes.background_color.r}, ${props.attributes.background_color.g}, ${props.attributes.background_color.b})`, color: props.attributes.foreground_color } }, el(InnerBlocks) )
            ];
        },

        save: function(props) {
            return (
                el(InnerBlocks.Content)
            );
        },
    });
}

climatestrike_BackgroundContainer();
