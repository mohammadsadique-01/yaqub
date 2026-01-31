var isModalOpen = false;
// let globelFloorValue = null;
$(document).on('click', function(e) {
    // Check if the clicked element is outside the modal and not part of the select2 dropdown
    if (isModalOpen && !$(e.target).closest('.modal').length && !$(e.target).hasClass('select2-search__field')) {
        closeModal();
    }
});
// Additional code to handle the modal close button
$(document).on('click', '.close', function() {
    closeModal();
});
function openModal() {
    isModalOpen = true;
    $('#modal-global').modal('show');
}
function closeModal() {
    $('#modal-global').modal('hide');
    isModalOpen = false;
}

let building_id = $('.get_bread_buildname option:selected').val();
selectBuilding(building_id, 0); // when page load capture building id and show floor.

$(document).on('change', '.get_bread_buildname , .buildingModel', function(e){
    e.preventDefault();
    var selectedBuildingId = $(this).val();
    if (isModalOpen) {
        selectBuilding(selectedBuildingId, 1);
    } else {
        selectBuilding(selectedBuildingId, 0);
    }
})
$(document).on('change','.get_bread_floor_name , .floorModel', function(e){
    e.preventDefault();
    var selectedFloorId = $(this).val();
    let paginationTag = $('.pagination > li.active > a').text() || 1;

    if (isModalOpen) {
        $('#modal-global').find('.get_bread_floor_name option:selected').val(selectedFloorId);
        filterRoomList(selectedFloorId, 1 , paginationTag); // it capture the building id , floor id and month-year and show the rooms.
    } else {
        // globelFloorValue = selectedFloorId;
        $('.get_bread_floor_name').val(selectedFloorId);
        filterRoomList(selectedFloorId, 0 , paginationTag); // it capture the building id , floor id and month-year and show the rooms.
    }
})
$(document).on('change', '#selectYear', function(e){
    e.preventDefault();
    let building_id = $('.get_bread_buildname option:selected').val();
    let floor_id = $('.get_bread_floor_name option:selected').val();
    let year = $(this).val();
    let month = $('#selectMonth').val();

    var formData = new FormData();
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);

    loadunpaidGuest(formData);

})
$(document).on('change', '#selectMonth', function(e){
    e.preventDefault();
    let building_id = $('.get_bread_buildname option:selected').val();
    let floor_id = $('.get_bread_floor_name option:selected').val();
    let year = $('#selectYear').val();
    let month = $(this).val();

    var formData = new FormData();
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);

    loadunpaidGuest(formData);

})
$(document).on('click', '.shareBillToGuest', function(e){
    e.preventDefault();
    const sharBillBtn = $(this);
    let get_floor_id = $('.get_bread_floor_name option:selected').val();
    if(get_floor_id === 'all'){
        get_floor_id = sharBillBtn.closest('tr').find('.floor').children('input').val();
    }

    let guestUserId = sharBillBtn.val();
    sharBillBtn.addClass('disabled loading');

   

    let html = `
    <div class="modal-header">
        <h4 class="modal-title">Edit Guest</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 pb-3">
                <div class="card card-widget widget-user-2 shadow-sm mb-0">
                    <div class="widget-user-header bg-info">
                        <div class="user-block">
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2 mt-2" src="${$(this).closest('tr').find('td:eq(1) img').attr('src')}" alt="User Avatar" style="width: 60px; height: 60px;">
                            </div>
                            <h3 class="widget-user-username">${$(this).closest('tr').find('td:eq(2)').text()}</h3>
                            <p class="widget-user-desc">${$(this).closest('tr').find('td:eq(4)').text()} , ${$(this).closest('tr').find('td:eq(3)').text()}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay-wrapper">
            <div class="overlay" style="display:none">
                <div class="overlay-content">
                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    <div class="text-bold pt-2">Loading...</div>
                </div>
            </div>
            <div class="container">
                <input type="hidden" class="guest_user_id" value="${guestUserId}">
                <input type="hidden" class="guest_floor_id" value="${get_floor_id}">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <form class="whatsapp">
                            <i class="fa fa-whatsapp fa-lg text-success fa-4" style="font-size:76px"></i> <!-- WhatsApp Icon -->
                            <div>Share on WhatsApp</div>
                        </form>
                    </div>
                    <div class="col-md-6 text-center">
                        <form class="mail">
                            <i class="fas fa-envelope fa-lg text-danger fa-4" style="font-size:76px"></i> <!-- Gmail Icon -->
                            <div>Share on Gmail</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    `;
    $('#modal-global').find('.modal-dialog').addClass('modal-md');
    $('#modal-global').find('.modal-content').html(html);
    $('#modal-global').find('.modal-title').text('Share Allert Message To Guest');
    $('#modal-global').modal('show');
    sharBillBtn.removeClass('disabled loading');
    $('.select2').select2();
})
$(document).on('click', '.whatsapp', function(e){
    e.preventDefault();
    const guest_user_id = $('.guest_user_id').val();
    var formData = new FormData();
    formData.append('type', 'whatsapp');
    formData.append('userId', guest_user_id);
    formData.append('building_id', $('.get_bread_buildname option:selected').val());
    formData.append('floor_id', $('.guest_floor_id').val());
    formData.append('year', $('#selectYear').val());
    formData.append('month', $('#selectMonth').val());

    shareBill(formData)
})
$(document).on('click', '.mail', function(e){
    e.preventDefault();
    const guest_user_id = $('.guest_user_id').val();
    var formData = new FormData();
    formData.append('type', 'mail');
    formData.append('userId', guest_user_id);
    formData.append('building_id', $('.get_bread_buildname option:selected').val());
    formData.append('floor_id', $('.guest_floor_id').val());
    formData.append('year', $('#selectYear').val());
    formData.append('month', $('#selectMonth').val());

    shareBill(formData)
})
$(document).on('change', '#checkAll', function (e) {
    e.preventDefault();
    $('.checkbox-primary').prop('checked', $(this).prop('checked'));

    if ($('.checkbox-primary:checked').length == $('.checkbox-primary').length) {
        $('.multiple').css({'display':'block'})
    } else {
        $('.multiple').css({'display':'none'})
    }
});
$(document).on('change', '.checkbox-primary', function (e) {
    e.preventDefault();
    if($('.checkbox-primary:checked').length === 0){
        $('.multiple').css({'display':'none'})
    } else if($('.checkbox-primary:checked').length > 0){
        $('.multiple').css({'display':'block'})
    }
    if ($('.checkbox-primary:checked').length == $('.checkbox-primary').length) {
        $('#checkAll').prop('checked', true);
    } else {
        $('#checkAll').prop('checked', false);
    }
});
$(document).on('click', '.shareAll', function(e){
    e.preventDefault();

    var selectedGuestUserIds = [];
    var selectedFloorIds = [];

    $('.checkbox-primary:checked').each(function () {
        var checkbox = $(this);
        var guestUserId = checkbox.val();
        checkbox.parent('div').css({'background':'red !important'})
        var floorId = checkbox.closest('tr').find('input[name="get_floor_id[]"]').val();

        selectedGuestUserIds.push(guestUserId);
        selectedFloorIds.push(floorId);
    });
        
    // var selectedGuestUserIds = $('.checkbox-primary:checked').map(function () {
    //     return $(this).val();
    // }).get();
    var formData = new FormData();
    formData.append('type', 'allmail');
    formData.append('building_id', $('.get_bread_buildname option:selected').val());
    // formData.append('floor_id', $('.get_bread_floor_name option:selected').val());
    formData.append('year', $('#selectYear').val());
    formData.append('month', $('#selectMonth').val());
    for (var i = 0; i < selectedGuestUserIds.length; i++) {
        formData.append('guestUserId[]', selectedGuestUserIds[i]);
        formData.append('floor_id[]', selectedFloorIds[i]);
    }
    // for (var i = 0; i < selectedGuestUserIds.length; i++) {
    //     formData.append('guestUserId[]', selectedGuestUserIds[i]);
    // }
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('.overlay').css({'display':'block'})
    let btnVal = $('.savechange_loader');
    btnVal.addClass('disabled loading');

    $.ajax({
        url: BASE_URL + "/shareUnpaidBillToMultipleGuest",
        method: "post",
        data: formData,
        processData: false,  // Important when sending FormData
        contentType: false,  // Important when sending FormData
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res) {
            console.log(res)
            $('.overlay').css({'display':'none'})

            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
            btnVal.removeClass('disabled loading');

            if(res.exceptstatus == 'error'){
                errorToastr(res.message)
            } else if(res.status == 'error'){
                errorToastr(res.errors)
            } else if(res.status == 'success'){
                $('.multiple').css({'display':'none'})
                $('#checkAll').prop('checked', false);
                $('.checkbox-primary').prop('checked', false);

                successToastr(res.message)
            }
        }
    })
})
$(document).on('click', '.single_pay_now', function(e){
    e.preventDefault();

    var confirmMsg = "Please confirm: Once you submit the payment, Your payment amount will not change after submission.";
    if (confirm(confirmMsg)) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        let btnVal = $(this);
        btnVal.addClass('disabled loading');
        let building_id = $('.get_bread_buildname option:selected').val();
        const selected_floor_id = $('.get_bread_floor_name option:selected').val();
        if(selected_floor_id === 'all'){
            floor_id = btnVal.closest('tr').find('.floor').children('input').val();
        }
        $('.overlay').css({'display':'block'})
        $('.multiple').css({'display':'none'})

        var formData = new FormData();
        formData.append('type', 'singlepay');
        formData.append('userId', btnVal.val());
        formData.append('year', $('#selectYear').val());
        formData.append('month', $('#selectMonth').val());
        formData.append('building_id', building_id);
        formData.append('floor_id', floor_id);

        $.ajax({
            url: BASE_URL + "/singlePayBill",
            method: "post",
            data: formData,
            processData: false, 
            contentType: false,  
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(res) {
                $('.select2').select2();
                $('[data-toggle="tooltip"]').tooltip();

                if(res.status == 'error'){
                    errorToastr(res.message)
                } else if(res.status == 'success'){
                    if(selected_floor_id === 'all'){
                        formData.append('floor_id', selected_floor_id);
                    }
                    loadunpaidGuest(formData);
                    successToastr(res.message)
                }
                $('.overlay').css({'display':'none'})
                btnVal.removeClass('disabled loading');
            }
        })
    }
})
$(document).on('click', '.payAll', function(e){
    e.preventDefault();
    var confirmMsg = 'Are you sure you want to proceed with the payment?';
    if (confirm(confirmMsg)) {

        var selectedGuestUserIds = [];
        var selectedFloorIds = [];

        $('.checkbox-primary:checked').each(function () {
            var checkbox = $(this);
            var guestUserId = checkbox.val();
            checkbox.parent('div').css({'background':'red !important'})
            var floorId = checkbox.closest('tr').find('input[name="get_floor_id[]"]').val();

            selectedGuestUserIds.push(guestUserId);
            selectedFloorIds.push(floorId);
        });
        
        // var selectedGuestUserIds = $('.checkbox-primary:checked').map(function () {
        //     return $(this).val();
        // }).get();
        var formData = new FormData();
        formData.append('type', 'allmail');
        formData.append('building_id', $('.get_bread_buildname option:selected').val());
        formData.append('floor_id', $('.get_bread_floor_name option:selected').val());
        formData.append('year', $('#selectYear').val());
        formData.append('month', $('#selectMonth').val());
        for (var i = 0; i < selectedGuestUserIds.length; i++) {
            formData.append('guestUserId[]', selectedGuestUserIds[i]);
            formData.append('get_floor_id[]', selectedFloorIds[i]);
        }
        // for (var i = 0; i < selectedGuestUserIds.length; i++) {
        //     formData.append('guestUserId[]', selectedGuestUserIds[i]);
        // }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('.overlay').css({'display':'block'})
        let btnVal = $(this);
        btnVal.addClass('disabled loading');


        $.ajax({
            url: BASE_URL + "/payBillToMultipleGuest",
            method: "post",
            data: formData,
            processData: false,  // Important when sending FormData
            contentType: false,  // Important when sending FormData
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(res) {
                console.log(res)
                $('.overlay').css({'display':'none'})

                $('.select2').select2();
                $('[data-toggle="tooltip"]').tooltip();

                if(res.exceptstatus == 'error'){
                    errorToastr(res.message)
                } else if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    $('.multiple').css({'display':'none'})
                    $('#checkAll').prop('checked', false);
                    $('.checkbox-primary').prop('checked', false);
                    loadunpaidGuest(formData);
                    successToastr(res.message)
                }
                btnVal.removeClass('disabled loading');
            }
        })
    }
})
function shareBill(formData){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('.overlay').css({'display':'block'})
    $.ajax({
        url: BASE_URL + "/shareUnpaidBill",
        method: "post",
        data: formData,
        processData: false,  // Important when sending FormData
        contentType: false,  // Important when sending FormData
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res) {
            console.log(res)
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();

            if(res.status == 'error'){
                errorToastr(res.errors)
            } else if(res.status == 'success'){
                if(res.type == "whatsapp"){
                    $('.overlay').css({'display':'none'})
                    $('#modal-global').modal('hide');

                    let mobileNumber = res.mobile;
                    let unpaidBillNotice = res.unpaidBillNotice;
                    let whatsappLink = `https://wa.me/${mobileNumber}?text=${encodeURIComponent(unpaidBillNotice)}`;
                    successToastr(res.message)

                    window.open(whatsappLink, '_blank');

                } else if(res.type == "mail"){
                    $('.overlay').css({'display':'none'})
                    $('#modal-global').modal('hide');
                    successToastr(res.message)
                }
            }
        }
    })
}