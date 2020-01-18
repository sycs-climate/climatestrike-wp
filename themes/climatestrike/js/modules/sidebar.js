jQuery(document).ready(function($) {
    var $burgers = $('.burger');

    $burgers.each(function(){
        $(this).click(function(){
            $('#sidebar').toggleClass('is-open');
        });
    });

    $('#sidebar .menu-item-has-children').each(function(n, $menu){
        $($menu).find('>a').click(function(e){
            e.preventDefault();
            $($menu).find('.sub-menu').toggleClass('is-open');
        });
    });
});
