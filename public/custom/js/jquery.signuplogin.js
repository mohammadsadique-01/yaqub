$(function () {
    $(document).on('submit', '#submitSignupClient', function(e){
        e.preventDefault();
        input = ['client_name','client_email','client_mobile','client_password','client_buildingname'];
        if(!FormValidation('submit',input)){
            let btnVal = $('.btnClient');
            btnVal.addClass('disabled loading');
            
            let form = $(this);
            let formData = new FormData(form[0]);

            $.ajax({
                url: BASE_URL + "/submitSignupClient",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                datatype: "json",
                success: function(res){
                    if(res.validstatus == 'error'){
                        errorToastr(res.message)
                        btnVal.removeClass('disabled loading');
                    } else if(res.exceptstatus == 'error'){
                        errorToastr(res.message)
                        btnVal.removeClass('disabled loading');
                    } else if(res.status == 'success'){
                        $('#userIdInput').val("")
                        successToastr(res.message)
                        setTimeout(function() {
                            window.location.href = BASE_URL + '/otp/' + res.userId;
                            btnVal.removeClass('disabled loading');
                        }, 2000);
                    }
                }
            });
        }    
    })


})