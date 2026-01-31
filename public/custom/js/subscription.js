$('#success').css('display','none');
subcriptionHistory();

$(document).on('click', '#paynow', function(e){
    e.preventDefault();
    let btnVal = $('.savechange_loader');
    btnVal.addClass('disabled loading');

    $.ajax({
        url: BASE_URL +"/monthlySubscription",
        type: 'POST',
        success: function(res){
            if(res.status == 'success'){
                successToastr(res.message)
                subcriptionHistory();
                btnVal.removeClass('disabled loading');
                $('#paidButton').css('display','none');
                $('#success').css('display','block');

            }
        }
    })
})


function subcriptionHistory(page = 1) {
    $.ajax({
        url: BASE_URL + "/subcriptionHistory",
        type: 'POST',
        data: {page: page},
        datatype: "json",
        success: function(res){
            if(res.status === 'success'){
                $('.user-data').html(res.tble);
                $('.show_pagination').html(res.pagination)

            }

        }
    });
}
