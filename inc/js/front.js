( function ( $ ) {
	// main part of script
	$( document ).ready( function () { 
        

        $('body').on('submit', '.feedback-request-form', function(e){
            e.preventDefault();
            let form = $(this);
            let formData = form.serialize();
            console.log(formData);
            let data = {
                'action': 'fdbckrqst_submit_form',
            };

            $.post({
                url: fdbckrqstValues.ajaxurl,
                data: formData,
                processData: false
            }).done(function(response) {
                form.replaceWith(response);
            });
        })
    });
} )( jQuery );