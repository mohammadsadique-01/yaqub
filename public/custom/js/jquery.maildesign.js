loadMailDesign();



$(document).on('change', '.get_email_template', function(e){
    e.preventDefault();
    var selectedEmailTemplateId = $(this).val();
    singleEmailTemplateDetail(selectedEmailTemplateId)
})
$(document).on('submit', '#submitEmailTemplate', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    let btnVal = $('.savechange_loader');
    btnVal.addClass('disabled loading');
    $('.overlay').css({'display':'block'})

    $.ajax({
        url: BASE_URL + "/submitEmailTemplate",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            btnVal.removeClass('disabled loading');
            successToastr(res.message)
            $('#submitEmailTemplate')[0].reset();
            CKEDITOR.instances['editor2'].setData('');

            $('.overlay').css({'display':'none'})
            $('.select2').select2();

        }
    })
})


function loadMailDesign(){
    $('label[for="mailPageSelect"]').nextAll().remove();

    $.ajax({
        url: BASE_URL + "/mailPageSelect",
        type: 'POST',
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('label[for="mailPageSelect"]').after(res);
            $('.select2').select2();
        }
    })
}
function singleEmailTemplateDetail(emailTemplateId){
    var formData = new FormData();
    formData.append('emailTemplateId', emailTemplateId);
    $('.overlay').css({'display':'block'})
    $('#editor2').html("");

    $.ajax({
        url: BASE_URL + "/singleEmailTemplateDetail",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('.overlay').css({'display':'none'})
            if(res.status == "success"){
                // $('#editor2').val(res.emailTemplateData.template);
                $('#email_template_id').val(res.id)
                $('.subject').val(res.emailTemplateData.subject)
                CKEDITOR.instances['editor2'].setData(res.emailTemplateData.template);
            }
            $('.select2').select2();
        }
    })
}