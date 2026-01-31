var isModalOpen = false;
// let globelFloorValue = null;





let building_id = $('.get_bread_buildname option:selected').val();
selectBuilding(building_id, 0); // when page load capture building id and show floor.

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
    if (isModalOpen) {
        $('#modal-global').find('.get_bread_floor_name').prop('value', selectedFloorId);
        filterRoomList(selectedFloorId, 1); // it capture the building id , floor id and month-year and show the rooms.
    } else {
        // globelFloorValue = selectedFloorId;
        $('.get_bread_floor_name').prop('value', selectedFloorId);
        filterRoomList(selectedFloorId, 0); // it capture the building id , floor id and month-year and show the rooms.
    }
})
$(document).on('change', '.get_bread_room_name , .roomModel', function(e){
    e.preventDefault();
    var $this = $(this);
    var selectedRoomId = $this.val();
    var formData = new FormData();
    formData.append('room_id', selectedRoomId);
    $('.room_cost_div').remove();
    $('.overlay').css({'display':'block'})

    $.ajax({
        url: BASE_URL + "/filterSingleRoom",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('.overlay').css({'display':'none'})
            $('.additional_cost').css({'display':'none'})
            if(res){
                $('.room_div').after(res);
                $('.select2').select2();
                $('.additional_cost').css({'display':'block'})
                $('#modal-global').find('.alertDiv').css({'display':'block'})
                let currentRoomCostVal = $('#modal-global').find('.currentRoomCost').val();
                let selectedRoomCost = $('#modal-global').find('input[name="room_cost"]').val();
                let $alertMsg =`<div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible">
                            <h5><i class="icon fas fa-warning"></i> Alert!</h5>
                            <span class=""></span>
                            The current room rate is set at ₹${currentRoomCostVal}, while the selected room rate is ₹${selectedRoomCost}.
                        </div>
                    </div>`;
                $('#modal-global').find('.alertDiv').html($alertMsg)
            }
        }
    });
})
$(document).on('submit', '.editBtnGuestForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    $('#modal-global').find('.modal-content').html("");

    $.ajax({
        url: BASE_URL + "/editBtnGuestForm",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('#modal-global').find('.modal-dialog').addClass('modal-xl');
            $('#modal-global').find('.modal-content').html(res);

            // Check if the element with ID 'mobileInput' exists in the updated content
            if ($('#modal-global #mobileInput').length) {
                $('#modal-global #mobileInput').inputmask({"mask": "(999) 999-9999"});
            }
            
            $('#modal-global').modal('show');
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    })
})
$(document).on('submit', '.submitGuestModel', function(e){
    e.preventDefault();
    const input = ['model_guest_name','model_email','model_mobile'];
    if(!FormValidation('submit',input)){
        let form = $(this);
        let formData = new FormData(form[0]);
        let btnVal = $('.savechange_loader');
        btnVal.addClass('disabled loading');
        let paginationTag = $('.pagination > li.active > a').text() || 1;

        $.ajax({
            url: BASE_URL + "/submitGuest",
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
                    $('#modal-global').find('.submitGuestModel')[0].reset();
                    let building_id = $('.get_bread_buildname option:selected').val();
                    // selectBuilding(building_id, 0 , paginationTag); // when page load capture building id and show floor.
                    let floor_id = $('.get_bread_floor_name option:selected').val();
                    filterRoomList(floor_id, 0 , paginationTag);
                    successToastr(res.message)
                    $('#modal-global').modal('hide');
                    $('.select2').select2();
                }
            }
        })
    }
})
$(document).on('submit', '.roomAllotBtnGuestForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    $('#modal-global').find('.modal-content').html("");
    
    let roomName = form.closest('td').siblings('td.room').text();
    let roomId = form.closest('td').siblings('td.room').children('.roomid').val();
    let roomCost = form.closest('td').siblings('td.room').children('.roomcost').val();
    let roomInfo = roomName + `<input type="hidden" class="currentRoom" value="${roomId}"><input type="hidden" class="currentRoomCost" value="${roomCost}">`;
    
    const breadcrum = `
        <ol class="breadcrumb">
            <li><b>Current Room Detail: &nbsp </b></li>
            <li class="breadcrumb-item bread_buildname">${$('.get_bread_buildname option:selected').text()}</li>
            <li class="breadcrumb-item bread_floorname">${$('.get_bread_floor_name option:selected').text()}</li>
            <li class="breadcrumb-item active bread_room" aria-current="page">${roomInfo}</li>
        </ol>
    `;
    $.ajax({
        url: BASE_URL + "/roomAllotBtnGuestForm",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            let building_id = $('.get_bread_buildname option:selected').val();

            $('#modal-global').find('.modal-dialog').addClass('modal-lg');
            $('#modal-global').find('.modal-content').html(res);
            $('#modal-global').find('.modal-title').text('Change Room');
            $('#modal-global').find('.advanceAmt').remove();
            $('#modal-global').find('.breadcrumModel').css({'display':'block'})
            $('#modal-global').find('.breadcrumModel').html(breadcrum)

            $('#modal-global').find('.get_bread_buildname').val(building_id);
            selectBuilding(building_id, 1); // when page load capture building id and show floor.
            
            $('#checkInDate2').datetimepicker({
                format: 'YYYY-MM-DD', // Adjust the format as needed
                minDate: moment(),
                defaultDate: moment().toDate(),
            });
            $('#checkInDate2 input').on('click', function() {
                $('#checkInDate2').datetimepicker('toggle');
            });

            $('#modal-global').modal('show');
            $('.select2').select2();
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
            isModalOpen = true;
        }
    })
})

$(document).on('submit', '.submitRoomAllotModel', function(e){
    e.preventDefault();
    const input = ['buildingModel','floorModel','roomModel','monthly_rent_model','checkInDate'];
    if(!FormValidation('submit',input)){
        let form = $(this);
        let formData = new FormData(form[0]);
        let btnVal = $('.savechange_loader');
        formData.append('page', 'guestlist');

        if (confirm("Please confirm: Once you submit the form, allocate the room to the guest and make the payment. Your payment amount will not change after submission.")) {
            submitRoomAllotForBoth(formData, btnVal, 1);
        }
    }
})
// $(document).on('submit', '.viewGuestInDetail', function(e){
//     e.preventDefault();
//     let form = $(this);
//     let formData = new FormData(form[0]);
//     let btnVal = $(this).children('button');
//     btnVal.addClass('disabled loading');

//     const viewGuestUrl = $('.viewGuestUrl').data('client-list-route'); // Retrieve the URL
    
//     $.ajax({
//         url: "/gotoViewGuest",
//         type: 'POST',
//         data: formData,
//         processData: false,
//         contentType: false,
//         datatype: "json",
//         success: function(res){
//             window.location.href = viewGuestUrl;
//             // btnVal.removeClass('disabled loading');
//         }
//     })
// })
$(document).on('submit', '.discontinueBtnForGuest', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    let btnVal = $(this).children('button');
    btnVal.addClass('disabled loading');

    $.ajax({
        url: BASE_URL + "/discontinueBtnForGuest",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            btnVal.removeClass('disabled loading');
            $('.select2').select2();

            $('#modal-global').find('.modal-dialog').addClass('modal-md');
            $('#modal-global').find('.modal-content').html(res);
            $('#modal-global').find('.modal-title').text('Leave Room');
            $('#modal-global').modal('show');
            
        }
    })
})
$(document).on('submit', '.submitLeaveRoomModel', function(e){
    e.preventDefault();
    const input = ['returned_advance_amount'];
    if(!FormValidation('submit',input)){
        let form = $(this);
        let formData = new FormData(form[0]);
        let guestListUrl = $('.guestListUrl').data('client-list-route'); // Retrieve the URL

        let btnVal = $('.savechange_loader');
        btnVal.addClass('disabled loading');
        let paginationTag = $('.pagination > li.active > a').text() || 1;

        $.ajax({
            url: BASE_URL + "/submitLeaveRoomModel",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            datatype: "json",
            success: function(res){
                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    // let building_id = $('.get_bread_buildname option:selected').val();
                    // selectBuilding(building_id, 0); // when page load capture building id and show floor.

                    successToastr(res.message)
                    $('.submitLeaveRoomModel')[0].reset();
                    $('#modal-global').find('.submitLeaveRoomModel')[0].reset();
                    $('#modal-global').modal('hide');
                    // let building_id = $('.get_bread_buildname option:selected').val();
                    // selectBuilding(building_id, 0 , paginationTag); // when page load capture building id and show floor.
                    let floor_id = $('.get_bread_floor_name option:selected').val();
                    filterRoomList(floor_id, 0 , paginationTag);
                    // setTimeout(function() {
                    //     window.location.href = guestListUrl;
                    // }, 2000);
                }

                btnVal.removeClass('disabled loading');
                $('.select2').select2();

            }
        })
    }
})
$(document).on('click', '#plusBtnModel', function(e){
    e.preventDefault();
    isModalOpen = true;

    let html = guestDocument();
    let existingDivs = $('.showGuestDocumentModel').nextAll('div');
    if(existingDivs.length > 0) {
        existingDivs.last().after(html); // Append after last div
    } else {
        $('.showGuestDocumentModel').after(html)
    }
})
$(document).on('click', '.minusBtn', function(e){
    e.preventDefault();
    e.stopPropagation(); // Stop the event from bubbling up to parent elements

    $(this).closest('.row').remove()
})


function openModal() {
    isModalOpen = true;
    $('#modal-global').modal('show');
}
function closeModal() {
    $('#modal-global').modal('hide');
    isModalOpen = false;
}
function submitRoomAllotForBoth(formData, btnVal, model){
    let guestListUrl = $('.guestListUrl').data('client-list-route'); // Retrieve the URL
    btnVal.addClass('disabled loading');
    $('#modal-global').find('.statusHidden').val($('.statusCheckbox').bootstrapSwitch('state') ? '1' : '0');

    $.ajax({
        url: BASE_URL + "/submitRoomAllot",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('.select2').select2();

            if(res.status == 'error'){
                errorToastr(res.errors)
                btnVal.removeClass('disabled loading');

            } else if(res.status == 'success'){
                // if(model == 0){
                //     $('.submitRoomAllot')[0].reset();
                // } else {
                //     $('.submitRoomAllotModel')[0].reset();
                // }
                successToastr(res.message)
                setTimeout(function() {
                    window.location.href = guestListUrl;
                }, 2000);
            }
        }
    })
}
function guestDocument(){
    const randomNumber = Math.floor(Math.random() * 900) + 100; // Generates a number between 100 and 999
    let guestDoc = `
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="guestDocument${randomNumber}"></label>
                    <div class="custom-file">
                        <input type="file" name="document[]" class="custom-file-input" id="guestDocument${randomNumber}" accept="image/*" onchange="previewImage(event, 'guestDocument${randomNumber}')">
                        <label class="custom-file-label" for="guestDocument${randomNumber}">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 previewImg">
                <img id="previewGuestDocument${randomNumber}" src="${BASE_URL}/custom/no-product.png" alt="Preview" style="width: 50px; height: 50px;">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger minusBtn" style="margin-top: 22px;margin-left: -9px;">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
    `;

    if (isModalOpen) {
        let existingDivs = $('.showGuestDocumentModel').nextAll('div');
        if(existingDivs.length > 0) {
            existingDivs.last().after(guestDoc); // Append after last div
        } else {
            $('.showGuestDocumentModel').after(guestDoc)
        }
    } else {
        let existingDivs = $('.showGuestDocument').nextAll('div');
        if(existingDivs.length > 0) {
            existingDivs.last().after(guestDoc); // Append after last div
        } else {
            $('.showGuestDocument').after(guestDoc)
        }
    }
    
    // return guestDoc;
}
