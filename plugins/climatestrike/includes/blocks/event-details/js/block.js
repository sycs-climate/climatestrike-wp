//var StartPickerVisible = true;
//var EndPickerVisible = false;
function climatestrike_EventDetails(){
    var el = wp.element.createElement;

    const { registerBlockType } = wp.blocks;

    const { 
        MediaUpload
    } = wp.editor;

    /* An awesome tutorial on InspectorControls https://rudrastyh.com/gutenberg/inspector-controls.html 
     * WP component reference https://developer.wordpress.org/block-editor/components/ */

    const {
        DateTimePicker,
        TextControl
    } = wp.components;

    const { __experimentalGetSettings } = wp.date;

    registerBlockType('climatestrike/event-details', {
        title: 'Event Details',
        icon: 'calendar-alt',
        category: 'common',
        supports: {
            customClassName: false,
        },
        attributes: {
            location: {
                type: 'string',
                source: 'meta',
                meta: 'climatestrike_event_details_location'
            },
            postcode: {
                type: 'string',
                source: 'meta',
                meta: 'climatestrike_event_details_postcode'
            },
            eventStart: {
                type: 'string',
                source: 'meta',
                meta: 'climatestrike_event_details_start',
                default: moment().format("DD-MM-YYYY HH:mm:ss")
            },
            eventEnd: {
                type: 'string',
                source: 'meta',
                meta: 'climatestrike_event_details_end',
                default: moment().format("DD-MM-YYYY HH:mm:ss")
            }
        },

        edit: function(props) {
            const settings = __experimentalGetSettings();
            // To know if the current timezone is a 12 hour time with look for an "a" in the time format.
            // We also make sure this a is not escaped by a "/".
            const is12HourTime = /a(?!\\)/i.test(
                settings.formats.time
                    .toLowerCase() // Test only the lower case a
                    .replace( /\\\\/g, '' ) // Replace "//" with empty strings
                    .split( '' ).reverse().join( '' ) // Reverse the string and test for "a" not followed by a slash
            );

            var StartPicker = el(DateTimePicker, {
                currentDate: props.attributes.eventStart,
                onChange: ( date ) => {
                    props.setAttributes({ eventStart: date.replace("T", " ") });
                    StartPickerVisible = false;
                },
                is12HourTime: is12HourTime
            });
            //function ShowStartPicker(){ if(StartPickerVisible) return StartPicker; }

            var EndPicker =  el(DateTimePicker, {
                currentDate: props.attributes.eventEnd,
                onChange: ( date ) => {
                    props.setAttributes({ eventEnd: date.replace("T", " ") });
                    EndPickerVisible = false;
                },
                is12HourTime: is12HourTime
            });
            //function ShowEndPicker(){ if(EndPickerVisible) return EndPicker; }


            return [
                el('div', {className: props.className},
                    // Start & end times
                    el('div', {className: "form-group form-group-2"}, 
                        el(TextControl, {
                            label: "Event Start",
                            value: moment(props.attributes.eventStart).format("DD-MM-YYYY HH:mm:ss")
                            //onClick: function() { StartPickerVisible = true },
                        }),
                        //ShowStartPicker()
                        StartPicker
                    ),
                    el('div', {className: "form-group form-group-2"},
                        el(TextControl, {
                            label: "Event End",
                            value: moment(props.attributes.eventEnd).format("DD-MM-YYYY HH:mm:ss")
                            //onClick: function() { EndPickerVisible = true },
                        }),
                        //ShowEndPicker()
                        EndPicker
                    ),
                    // Location
                    el('div', {className: "form-group form-group-2"}, 
                        el(TextControl, {
                            label: "Location",
                            onChange: ( value) => { 
                                props.setAttributes({ location: value });
                            },
                            value: props.attributes.location
                        })
                    ),
                    el('div', {className: "form-group form-group-2"}, 
                        el(TextControl, {
                            label: "Postcode",
                            onChange: ( value) => { 
                                props.setAttributes({ postcode: value });
                            },
                            value: props.attributes.postcode
                        })
                    )

                )
            ];
        },

        save: function(props) {
            return null;
        },
    });
}

climatestrike_EventDetails();
