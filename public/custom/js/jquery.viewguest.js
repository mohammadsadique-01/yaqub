guestList();

$(document).on('change',' .get_guest', function(e){
    e.preventDefault();
    var selectedGuestId = $(this).val();
    singleGuestDetail(selectedGuestId)
    $('.select2').select2();

})

function guestList(){
    $('label[for="guestSelect"]').nextAll().remove();

    $.ajax({
        url: BASE_URL + "/viewGuestList",
        type: 'POST',
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('label[for="guestSelect"]').after(res);
            const session_guest = $('#session_guest').val();
            if(session_guest){
                // Check if the value is different before setting it
                if ($('.get_guest').val() !== session_guest) {
                    $('.get_guest').val(session_guest).trigger('change');
                }
            }
            $('.select2').select2();
        }
    })
}
function singleGuestDetail(guestId , page = 1){
    var formData = new FormData();
    formData.append('guestUserId', guestId);
    formData.append('page', page);
    $('.overlay').css({'display':'block'})
    $.ajax({
        url: BASE_URL + "/singleViewGuest",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            if(res.status === 'success'){
                $('.guestBox').css({'display':'block'})
    
                $('#html').html(res.html)
                $('#basicDetail').html(res.basicDetail)
                $('#roomHistories').html(res.roomHistories)
                $('#paymentRecord').html(res.paymentRecord)
                $('.show_pagination').html(res.paginationPaymentHtml)
                $('.TotalRentPaid').html(res.TotalRentPaid)
                $('.stayDays').html(res.stayDays)
            } else {
                $('.guestBox').css({'display':'none'})
                errorToastr(res.message)
            }
            
            $('.overlay').css({'display':'none'})
            $('.select2').select2();
        }
    })
}
