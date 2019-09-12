$(document).ready(function() {
    var $dropdowns = $('.js-reveal');

    $dropdowns.each(function() {
        var $dropdown = $(this);
        var $heading = $dropdown.find('.js-reveal-heading');
        $button = $('<button />');
        $button.click(function() {
            toggleDropDown($dropdown);
        });

        $heading.wrapInner($button);
    });
});

function toggleDropDown($el) {
    if ($el.hasClass('is-open')) {
        $el.removeClass('is-open');
    } else {
        $el.addClass('is-open');
    }
}
