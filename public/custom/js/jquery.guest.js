var isModalOpen = false;
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
$(function () {


    
    loadGuestToAllotRoom(); // Load initial data
    guestDocument();

    $('.contentStep1, .btnStep1').addClass('active');
    let building_id = $('.get_bread_buildname option:selected').val();
    selectBuilding(building_id, 0); // when page load capture building id and show floor.

    $(document).on('click', '#saveGuest', function(e){
        e.preventDefault();
        $('.submitGuest').submit();
    })
    $(document).on('submit', '.submitGuest', function(e){
        e.preventDefault();
        let form = $(this);
        let modelValue = form.find('input[name="model"]').val();
        let input;
        if(modelValue == 1){
            input = ['model_guest_name','model_email','model_mobile'];
        } else if(modelValue == 0){
            input = ['guest_name','email','mobile'];
        }
        if(!FormValidation('submit',input)){
            let formData = new FormData(form[0]);
            submitEditGuestForBothForm(modelValue, formData)
        }
    })
    $(document).on('submit', '.submitGuestModel', function(e){
        e.preventDefault();
        let form = $(this);
        let modelValue = form.find('input[name="model"]').val();
        let input;
        if(modelValue == 1){
            input = ['model_guest_name','model_email','model_mobile'];
        } else if(modelValue == 0){
            input = ['guest_name','email','mobile'];
        }
        if(!FormValidation('submit',input)){
            let formData = new FormData(form[0]);
            submitEditGuestForBothForm(modelValue, formData)
        }

    })
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
            $('#modal-global').find('.get_bread_floor_name option:selected').val(selectedFloorId);
            filterRoomList(selectedFloorId, 1); // it capture the building id , floor id and month-year and show the rooms.
        } else {
            $('.get_bread_floor_name option:selected').val(selectedFloorId);
            filterRoomList(selectedFloorId, 0); // it capture the building id , floor id and month-year and show the rooms.
        }
    })
    $(document).on('change', '.get_bread_room_name , .roomModel', function(e){
        e.preventDefault();
        var $this = $(this);
        var selectedRoomId = $this.val();
        var formData = new FormData();
        formData.append('room_id', selectedRoomId);
        if (isModalOpen) {
            $('#modal-global').find('.room_cost_div').remove();
            $('#modal-global').find('.overlay').css({'display':'block'})
            $('#modal-global').find('.additional_cost').css({'display':'none'})

        } else {
            $('.room_cost_div').remove();
            $('.overlay').css({'display':'block'})
            $('.additional_cost').css({'display':'none'})
        }

        $.ajax({
            url: BASE_URL + "/filterSingleRoom",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            datatype: "json",
            success: function(res){
                if (isModalOpen) {
                    $('#modal-global').find('.room_div').after(res);
                    $('#modal-global').find('.overlay').css({'display':'none'})
                    $('#modal-global').find('.additional_cost').css({'display':'block'})
                } else {
                    $('.room_div').after(res);
                    $('.overlay').css({'display':'none'})
                    $('.additional_cost').css({'display':'block'})
                }
                $('.select2').select2();
            }
        });
    })
    $(document).on('click', '#saveRoomAllot', function(e){
        e.preventDefault();
        $('.submitRoomAllot').submit();
    })
    $(document).on('submit', '.submitRoomAllot', function(e){
        e.preventDefault();
        const input = ['get_bread_buildname','get_bread_floor_name','get_bread_room_name','monthly_rent','checkInDate'];
        if(!FormValidation('submit',input)){
            let form = $(this);
            let formData = new FormData(form[0]);
            formData.append('page', 'addguest');
            let btnVal = $('#saveRoomAllot');
            // $('.statusHidden').val($('.statusCheckbox').bootstrapSwitch('state') ? '1' : '0');
            $('.statusHidden').val($('.statusCheckbox').prop('checked') ? '1' : '0');

            if (confirm("Please confirm: Once you submit the form, allocate the room to the guest and make the payment. Your payment amount will not change after submission.")) {
                submitRoomAllotForBoth(formData, btnVal, 0);
            }
        }
    })
    $(document).on('submit', '.submitRoomAllotModel', function(e){
        e.preventDefault();
        const input = ['buildingModel','floorModel','roomModel','monthly_rent_model','checkInDate'];
        if(!FormValidation('submit',input)){
            let form = $(this);
            let formData = new FormData(form[0]);
            let btnVal = $('.savechange_loader');
            formData.append('page', 'addguest');
            // $('#modal-global').find('.statusHidden').val($('.statusCheckbox').bootstrapSwitch('state') ? '1' : '0');
            $('#modal-global').find('.statusHidden').val($('.statusCheckbox').prop('checked') ? '1' : '0');

            submitRoomAllotForBoth(formData, btnVal, 1);
        }
    })
    $(document).on('submit', '.deleteGuestRoomNotAllot', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        let paginationTag = $('.pagination > li.active > a').text() || 1;

        $.ajax({
            url: BASE_URL + "/deleteGuestRoomNotAllot",
            method: "post",
            data: formData,
            success: function(res) {
                $('[data-toggle="tooltip"]').tooltip('dispose');

                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    $('.deleteGuestRoomNotAllot')[0].reset();
                    loadGuestToAllotRoom(paginationTag); // Load initial data
                    successToastr(res.message)
                }
            }
        });
    });
    $(document).on('click', '#roomAllot', function(e){
        e.preventDefault();
        roomAllotment();
        $('#checkInDate2').datetimepicker({
            format: 'YYYY-MM-DD', // Adjust the format as needed
            minDate: moment(),
            defaultDate: moment().toDate(),
      
          });
          $('#checkInDate2 input').on('click', function() {
            $('#checkInDate2').datetimepicker('toggle');
          });
        $('.select2').select2();
      
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
                // Destroy tooltip before showing the modal
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $('#modal-global').modal('show');

                $('.select2').select2();
                $('[data-toggle="tooltip"]').tooltip();
    
            }
        })
    })
    $(document).on('submit', '.roomAllotBtnGuestForm', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(form[0]);
        $('#modal-global').find('.modal-content').html("");

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
                $('#modal-global').find('.modal-title').text('Room Allotment');

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

                // Destroy tooltip before showing the modal
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $('#modal-global').modal('show');
                $('.select2').select2();
                $("input[data-bootstrap-switch]").each(function(){
                    $(this).bootstrapSwitch('state', $(this).prop('checked'));
                })
                isModalOpen = true;

            }
        })
    })
    $(document).on('click', '#plusBtn', function(e){
        e.preventDefault();
        let html = guestDocument();
        let existingDivs = $('.showGuestDocument').nextAll('div');
        if(existingDivs.length > 0) {
            existingDivs.last().after(html); // Append after last div
        } else {
            $('.showGuestDocument').after(html)
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
})




function loadGuestToAllotRoom(page = 1) {
    $.ajax({
        url: BASE_URL + "/loadGuestToAllotRoom",
        type: 'POST',
        data: {page: page},
        datatype: "json",
        success: function(res){
            if(res.status == 'success'){
                $('#user-data').html(res.tble);
                $('.show_pagination').html(res.pagination)

                $('[data-toggle="tooltip"]').tooltip();

            }
        }
    });
}
function submitEditGuestForBothForm(modelValue,formData){
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
            if(res.validstatus == 'error'){
                errorToastr(res.errors)
            } else if(res.exceptstatus == 'error'){
                errorToastr(res.message)
            } else if(res.status == 'success'){
                loadGuestToAllotRoom(paginationTag); // Load initial data

                if(modelValue == 1){
                    $('#modal-global').find('.submitGuestModel')[0].reset();
                    $('#modal-global').modal('hide');
                    $('.select2').select2();
                    successToastr(res.message)
                } else if(modelValue == 0){
                    $('.guestIdInput').val(res.guest_id);
                    $('.guestDetailId').val(res.guestDetailId);
                    $('.contentStep1, .btnStep1').removeClass('active');
                    $('.contentStep2, .btnStep2').addClass('active');
                    $('.submitBtnstep1').css({'display':'none'});
                    $('.submitBtnstep2').css({'display':'block'});
                    $('.select2').select2();
                }
                let building_id = $('.get_bread_buildname option:selected').val();
                selectBuilding(building_id, 0); // when page load capture building id and show floor.
            }
        }
    })
}
function goToPreviousStep(){
    $('.contentStep1, .btnStep1').addClass('active');
    $('.contentStep2, .btnStep2').removeClass('active');
    $('.submitBtnstep2').css({'display':'none'});
    $('.submitBtnstep1').css({'display':'block'});
}
function submitRoomAllotForBoth(formData, btnVal, model){
    const guestListUrl = $('.guestListUrl').data('client-list-route'); // Retrieve the URL
    btnVal.addClass('disabled loading');

    $.ajax({
        url: BASE_URL + "/submitRoomAllot",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
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
                    btnVal.removeClass('disabled loading');
                }, 2000);
            }
            $('.select2').select2();
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