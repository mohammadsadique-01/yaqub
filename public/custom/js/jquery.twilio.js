function loadTwilioAccount(page = 1) {
    $('.card-title').text('Add New Twilio');
    $.ajax({
        url: BASE_URL + "/loadTwilioAccount",
        type: 'POST',
        data: {page: page},
        datatype: "json",
        success: function(res){
            $('#user-data').html(res);
            $('[data-toggle="tooltip"]').tooltip();

        }
    });
}

loadTwilioAccount(); // Load initial data

$(document).on('submit', '.editTwilioAccount', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = form.serialize();

    $.ajax({
        url: BASE_URL + "/editTwilioAccount",
        method: "post",
        data: formData,
        success: function(res) {
            if(res.id){
                $('.card-title').text('Update Twilio');
            }
            $('#userIdInput').val(res.id)
            $('#twilioUserIdInput').val(res.twilio_account.id)
            $('#nameInput').val(res.name)
            $('#emailInput').val(res.email)
            $('#mobileInput').val(res.mobile)
            $('.account_sid').val(res.twilio_account.account_sid)
            $('.auth_token').val(res.twilio_account.auth_token)
        }
    })
})
$(document).on('submit', '#submitTwilioAccount', function(e){
    e.preventDefault();
    const input = ['nameInput','emailInput','mobileInput','account_sid','auth_token'];
    if(!FormValidation('submit',input)){
        let form = $(this);
        let formData = form.serialize();
        let btnVal = $('.submitBtn');
        btnVal.addClass('disabled loading');
        
        $.ajax({
            url: BASE_URL + "/submitTwilioAccount",
            method: "post",
            data: formData,
            success: function(res) {
                btnVal.removeClass('disabled loading');
                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    $('#userIdInput').val("")
                    $('#submitTwilioAccount')[0].reset();
                    loadTwilioAccount(); // Load initial data
                    successToastr(res.message)
                }
            }
        })
    }
})