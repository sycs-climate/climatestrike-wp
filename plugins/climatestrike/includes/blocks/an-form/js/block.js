function climatestrike_SignupForm() {
    const { registerBlockType } = wp.blocks;

    var el = wp.element.createElement;

    registerBlockType('climatestrike/signup-form', {
        title: 'Signup Form',
        icon: 'media-text',
        category: 'layout',
        supports: {
            customClassName: false,
        },
        attributes: {},
        edit: function(props) {
           return el('div', { className: props.className }, 
                el('p', {}, "Signup Form")
           ) 
        },
        save: function(props) {
            return "";
        }
    });
}

climatestrike_SignupForm();
