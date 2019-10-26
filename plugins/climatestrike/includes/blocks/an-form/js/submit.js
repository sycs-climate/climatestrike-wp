function formToJSON(form) {
    var result = {};
    jQuery.each(form.serializeArray(), function(){
        result[this.name] = this.value;
    });

    return result;
}

jQuery(document).ready(function($) {
    var $form = $('#climatestrike-signup-form');
    var $btn = $form.find('input[type=submit]');

    $form.on('submit', function(e) {
        $form.addClass('loading');
        $btn.addClass('btn--loading');

        $.ajax({
            url:ajax_url,
            type: 'POST',
            data: {
                action: 'climatestrike_signup_form',
                data: formToJSON($form)
            },
            success: function(response){
                console.log(response); 
                if(response['success']) {
                    alert("Success");
                    $form.addClass('grayed-out');
                    $.each($form.find('input'), function(){
                        this.disabled = true;
                        console.log(this.value);
                    });
                    $('#climatestrike-signup-wrapper #notice').empty();
                    $('#climatestrike-signup-wrapper').append($('<h2>').addClass('jumbo').append("Thanks! You will hear from us shortly."));
                } else {
                    alert("fail");
                    $('#climatestrike-signup-wrapper #notice').removeClass('is-hidden');
                    $.each(response['data']['errors'], function(key, value){
                        $('#climatestrike-signup-wrapper #notice ul').append($('<li>').append(value));
                    });
                }
            },
            error: function(response){
                console.log(response);
                $('#climatestrike-signup-wrapper #notice').removeClass('is-hidden');
                $('#climatestrike-signup-wrapper #notice ul').append($('<li>').append("Sorry, something went wrong. Please try again or contact us."));

            }
        });
        e.preventDefault();
    });
});
