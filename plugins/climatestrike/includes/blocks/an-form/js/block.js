function climatestrike_SignupForm() {
    const { registerBlockType } = wp.blocks;
    const {
        TextControl,
        CheckboxControl
    } = wp.components;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/signup-form', {
        title: 'Signup Form',
        icon: 'media-text',
        category: 'layout',
        supports: {
            customClassName: false,
        },
        attributes: {
            title: {
                type: 'string',
                default: 'Stay Informed'
            },
            join: {
                type: 'boolean',
                default: false
            }
        },
        edit: function(props) {
           return [
                el('div', { className: props.className }, 
                    el('div', { className: 'placeholder'},
                        el('p', {}, "Signup Form")
                    ),
                    el('div', {}, 
                        el(TextControl, {
                            label: 'Title',
                            onChange: ( value ) => {
                                props.setAttributes( { title: value } );
                            },
                            value: props.attributes.title
                        }),
                        el(CheckboxControl, {
                            label: 'Join',
                            onChange: ( value ) => {
                                props.setAttributes( { join: value } );
                            },
                            value: props.attributes.join
                        })
                    )
                )
           ];
        },
        save: function(props) {
            return "";
        }
    });
}

climatestrike_SignupForm();
