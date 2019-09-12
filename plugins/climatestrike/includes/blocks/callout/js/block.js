function climatestrike_BackgroundContainer(){
    const { registerBlockType } = wp.blocks;
    
    const { 
        MediaUpload
    } = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {
        TextControl
    } = wp.components;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/callout', {
        title: 'Callout',
        icon: 'format-image',
        category: 'common',
        supports: {
            customClassName: false,
        },
        attributes: {
            thumbnail: {
                type: 'object',
                default: null
            },
            text: {
                type: 'string',
                default: ''
            },
            href: {
                type: 'string',
                default: 'http://'
            }
        },

        edit: function(props) {
            
            return [
                el(MediaUpload,
                    {
                        type: 'image',
                        onSelect: (image) => {
                            props.setAttributes( { thumbnail: image } );
                        },
                        render: (obj) => {
                            if(props.attributes.thumbnail) {
                                return el('div', {className: 'components-media-upload'}, 
                                    el('img', {src: props.attributes.thumbnail.url, onClick: obj.open, className: 'components-media-upload-img'}),
                                    el(Button, 
                                        {
                                                className: 'components-button components-media-upload__clear-button', 
                                                onClick: function() {
                                                    props.setAttributes( { thumbnail: null } );
                                                }
                                            }, 'Clear image'
                                        )
                                )
                            } else {
                                return el('div', {className: 'components-media-upload'},
                                    el(Button, {className: 'components-button components-media-upload__button', onClick: obj.open}, 'Set background image')
                                )
                            }
                        }
                    }
                ),
                el(TextControl, 
                    {
                        label: 'Text',
                        onChange: ( value ) => {
                            props.setAttributes( { text: value } );
                        },
                        value: props.attributes.text
                    }
                ),
                el(TextControl, 
                    {
                        label: 'Link Location',
                        onChange: ( value ) => {
                            props.setAttributes( { href: value } );
                        },
                        value: props.attributes.href
                    }
                ),


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
