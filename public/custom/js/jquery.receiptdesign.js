
loadReceiptDetail();


$(document).on('submit', '.saveReceiptBill', function(e){
    e.preventDefault();
    const input = ['businessNameInput','address','footerInput'];
    if(!FormValidation('submit',input)){
        let form = $(this);
        let formData = new FormData(form[0]);
        let btnVal = $('.submitBtn');
        btnVal.addClass('disabled loading');

        $.ajax({
            url: BASE_URL + "/saveReceiptBill",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            datatype: "json",
            success: function(res){
                btnVal.removeClass('disabled loading');
                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    loadReceiptDetail()
                    successToastr(res.message)
                }
            }
        })
    }
})

function loadReceiptDetail(){
    $('.overlay').css({'display':'block'})

    $.ajax({
        url: BASE_URL + '/loadReceiptDetail',
        type: 'POST',
        success: function(res){
            if(res.status == 'error'){
                errorToastr(res.errors)
            } else if(res.status == 'success'){
                if(res.receiptData){
                    $('#receipt_detail_id').val(res.receiptData.id)
                    $('#businessNameInput').val(res.receiptData.businessname)
                    $('#address').val(res.receiptData.address)
                    $('#footerInput').val(res.receiptData.footer)
                }
                if (res.pdf) {
                    var blob = new Blob([base64ToArrayBuffer(res.pdf)], { type: 'application/pdf' });
                    var url = window.URL.createObjectURL(blob);
                    $('#iframe').attr('src', url);
                }
            }
            $('.overlay').css({'display':'none'})

        }
    })
}
function base64ToArrayBuffer(base64) {
    var binaryString = window.atob(base64);
    var binaryLen = binaryString.length;
    var bytes = new Uint8Array(binaryLen);
    for (var i = 0; i < binaryLen; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
}
