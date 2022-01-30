$('#notice-form').on('submit', (e) => {
    e.preventDefault();
    const form = $('#notice-form');
    const formData = form.serialize();
    $.ajax({
        type: 'POST',
        url: '/api/form',
        data: formData,
        dataType: 'json',
        success: (response) => {
            $('#status').removeClass().addClass('text-success');
            $('#status').text(response.message);
        },
        error: (response) => {
            if(response.status === 409) {
                $('#save-btn')
                    .removeClass('btn-panda').addClass('btn-warning')
                    .text('Would you like to override?');
                $('#override').val(true);
            } 
            $('#status').removeClass().addClass('text-danger');
            $('#status').text(response.responseJSON.message);
        }
    });
});