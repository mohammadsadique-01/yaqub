loadQuotationList()
function loadQuotationList(){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: BASE_URL + "/showQuotationList",
        type: 'post',
        processData: false,
        contentType: false,
        datatype: "json",
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            $('.quotationList').html(res)
            $('.example').DataTable()
        }
    });
}
function addMore(count) {
    let finalCount = count + 1;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: BASE_URL +"/ajaxAddMore",
      type: 'POST',
      data: {'count': finalCount},
      datatype: "json",
      headers: {
          'X-CSRF-Token': csrfToken
    },
    success: function(res){
        console.log(res)
        $('#selectRows').append(res);
        $('#count').val(finalCount);
        // MultiselectDropdown();
      }
    });
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

        
$(document).ready(function() {
    $('.example').DataTable({
      responsive: true
    });

    // Add More Button Click Event
    $('#addMoreBtn').click(function() {
      var elements = document.getElementsByClassName('clone-row');
      var count = elements.length;
      addMore(count);
    });
    // Remove Button Click Event
    $(document).on('click', '.remove-btn', function() {
      $(this).closest('.clone-row').remove();
    });
});

/**
 * Quotation List Page
 */
$(document).on('click', '#setQuotationPriceBtn', function(e){
    e.preventDefault();
    let faisal_client_info_id = $(this).val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: BASE_URL +"/setQuotationPrice",
        type: 'POST',
        data: {'faisal_client_info_id' : faisal_client_info_id , 'page' : 'quotationlist'},
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            $('#quotationPriceModel').empty().html(res);
            $('#myModal').modal('show');
        }
    });
});
$(document).on('submit', '.submitQuotationPrice', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    let btnVal = $('.QuotationPrice');
    btnVal.addClass('disabled loading');
    $.ajax({
        url: BASE_URL +"/submitQuotationPrice",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            btnVal.removeClass('disabled loading');

            if(res.status == 'error'){
                errorToastr(res.errors)
            } else if(res.status == 'success'){
                successToastr(res.message)
                $('#myModal').modal('hide');
                if(res.page === '') {
                    // Redirect to quotationlist if page is empty
                    window.location.href = res.redirect;
                } else {
                    loadQuotationList();
                }
            }
        }
    });
});
$(document).on('click', '#makeQuotationPDF', function(e){
    e.preventDefault();
    let faisal_client_info_id = $(this).val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    let btnVal = $('#makeQuotationPDF');
    btnVal.addClass('disabled loading');
    $.ajax({
        url: BASE_URL +"/makeQuotationPDF",
        type: 'POST',
        data: {'faisal_client_info_id' : faisal_client_info_id},
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            var blob = new Blob([base64ToArrayBuffer(res.pdf)], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.target = '_blank';
            link.download = res.name +'.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            btnVal.removeClass('disabled loading');
        }
    });
});
$(document).on('keyup', '.price', function(e){
    e.preventDefault();
    var totalPrice = 0;

    // Loop through each input with class "price"
    $(".price").each(function(){
        // Get the value of each input and add it to totalPrice
        var price = parseFloat($(this).val());
        if(!isNaN(price)) {
            totalPrice += price;
        }
    });

    // Display the total price
    $(".totalPrice").text(totalPrice);
    $(".totalPrice").val(totalPrice);
})
$(document).on('click', '.deleteQuotation', function(e){
    e.preventDefault();
    let faisal_client_info_id = $(this).val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    let btnVal = $('#deleteQuotation');
    btnVal.addClass('disabled loading');

    if (confirm("Are you sure you want to delete?")) {
        $.ajax({
            url: BASE_URL +"/deleteQuotation",
            type: 'POST',
            data: {'faisal_client_info_id' : faisal_client_info_id},
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(res){

                console.log(res)
                btnVal.removeClass('disabled loading');
                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    successToastr(res.message)
                    loadQuotationList();
                }
            }
        });
    }   
});

/**
 * Create Quotation Page
 */
$(document).on('submit', '#submitQuotation', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    let btnVal = $('.btnClient');
    btnVal.addClass('disabled loading');
    $.ajax({
        url: BASE_URL +"/submitQuotation",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            btnVal.removeClass('disabled loading');
            console.log(res.faisal_client_info_id)
            if(res.status == 'success'){
                $.ajax({
                    url: BASE_URL +"/setQuotationPrice",
                    type: 'POST',
                    data: {'faisal_client_info_id' : res.faisal_client_info_id},
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(result){
                        console.log(result)
                        $('#quotationPriceModel').empty().html(result);
                        $('#myModal').modal('show');
                    }
                });

            }
        }
    });
});

