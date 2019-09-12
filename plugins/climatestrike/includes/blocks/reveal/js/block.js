var climatestrikeReveal = function () {
    const { registerBlockType } = wp.blocks;
    const { TextareaControl } = wp.components;
    const { InnerBlocks } = wp.editor;
    const { TextControl } = wp.components;
    var el = wp.element.createElement;


    registerBlockType('climatestrike/reveal', {
        title: 'Reveal',
        icon: 'media-text',
        category: 'common',
        supports: {
            customClassName: false,
        },

        attributes: {
            title: {
                type: 'string',
                default: ''
            },
            visibleText: {
                type: 'string',
                default: ''
            }
        },

        edit: function(props) {
            const { setAttributes } = props;
            const { attributes } = props;

            function saveTitle(title) {
                setAttributes({
                    title: title
                });
            }

            function saveVisibleText(visibleText) {
                setAttributes({
                    visibleText: visibleText
                });
            }

            return (
                el('div', { className: props.className },
                    el('div', { className: 'title' },
                        el(TextControl, {
                            label: 'Reveal title',
                            value: attributes.title,
                            onChange: function(data) {
                                saveTitle(data);
                            }
                        })
                    ),
                    el('div', { className: 'visible-text' },
                        el(TextareaControl, {
                            label: 'Reveal visible text (optional)',
                            value: attributes.visibleText,
                            onChange: function(data) {
                                saveVisibleText(data);
                            }
                        })
                    ),
                    el('div', { className: 'visible-text' },
                        el(InnerBlocks, {
                            template: [
                                ['core/paragraph', { placeholder: 'Enter hidden content...' }]
                            ]
                        })
                    )
                )
            );
        },

        save: function(props) {
            return (
                el(InnerBlocks.Content)
            );
        },
    });
}();
