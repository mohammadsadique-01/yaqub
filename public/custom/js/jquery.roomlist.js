
$(function () {
    var isModalOpen = false;
    
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
    $(document).on('change', '.get_bread_buildname', function(e){
        e.preventDefault();
        var selectedBuildingId = $(this).val();
        selectBuilding(selectedBuildingId, 0)
    })
    $(document).on('change','.get_bread_floor_name', function(e){
        e.preventDefault();
        var selectedRoomId = $(this).val();
        filterRoomList(selectedRoomId, 0); // it capture the building id , floor id and month-year and show the rooms.
    })
    $(document).on('submit', '.singleDeleteBtnRoomForm', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        let paginationTag = $('.pagination > li.active > a').text() || 1;

        $.ajax({
            url: BASE_URL + "/singleDeleteRoom",
            method: "post",
            data: formData,
            success: function(res) {
                if(res.status == 'error'){
                    errorToastr(res.message)
                } else if(res.status == 'success'){
                    let floor_id = $('.get_bread_floor_name option:selected').val();
                    filterRoomList(floor_id , 0, paginationTag); // it capture the building id , floor id and month-year and show the rooms.
                    successToastr(res.message)
                }
            }
        });
    })
    $(document).on('submit', '.singleEditBtnRoomForm', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();

        $.ajax({
            url: BASE_URL + "/singleEditRoom",
            method: "post",
            data: formData,
            success: function(res) {
                $('#modal-global').find('.modal-dialog').removeClass('modal-xl');
                $('#modal-global').find('.modal-dialog').addClass('modal-md');
                $('#modal-global').find('.modal-content').html(res);
                $('#modal-global').find('.modal-title').text('Edit Room');
                $('#modal-global').modal('show');
            }
        });
    });
    $(document).on('submit', '.submitSingleEditRoomForm', function(e){
        e.preventDefault();
        let input = ['room_name','room_code','cost_per_person'];
        if(!FormValidation('submit',input)){
            let building_id = $('.get_bread_buildname option:selected').val();
            let floor_id = $('.get_bread_floor_name option:selected').val();
            
            let form = $(this);
            let formDataArray = form.serializeArray();
            // Add additional data
            formDataArray.push({ name: 'building_id', value: building_id });
            if(floor_id != 'all'){
                formDataArray.push({ name: 'floor_id', value: floor_id });
            }

            let formData = $.param(formDataArray);

            let btnVal = $('.submitBtn');
            btnVal.addClass('disabled loading');
            let paginationTag = $('.pagination > li.active > a').text() || 1;
            $.ajax({
                url: BASE_URL + "/submitSingleEditRoomForm",
                method: "post",
                data: formData,
                success: function(res) {
                    console.log(res)
                    if(res.status == 'error'){
                        errorToastr(res.errors)
                        btnVal.removeClass('disabled loading');
                    } else if(res.status == 'success'){
                        $('#modal-global').modal('hide');
                        let floor_id = $('.get_bread_floor_name option:selected').val();
                        filterRoomList(floor_id , 0 , paginationTag); // it automatically capture the building id , floor id and month-year and show the rooms.
                        $('.submitSingleEditRoomForm')[0].reset();
                        btnVal.removeClass('disabled loading');

                        setTimeout(function() {
                            successToastr(res.message)
                        }, 500);
                    }
                }
            });
        }
    })
    $(document).on('click', '.addNewRoomOpenModel', function(e){
        e.preventDefault();
        $('#modal-global').find('.modal-content').html("");
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var formData = new FormData();
        formData.append('building_id', $('.get_bread_buildname').val());
        formData.append('floor_id', $('.get_bread_floor_name').val());
        $.ajax({
            url: BASE_URL + "/addNewRoomOpenModel",
            method: "post",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(res) {
                if(res.status == 'success'){
                    let dataFromStrongTag = res.roomData.room_name;
                    let dataAfterSpace = parseInt(dataFromStrongTag.split(' ')[1]) + 1;
                    let result;
                    if (!isNaN(dataAfterSpace)) {
                        result = dataAfterSpace;
                    } else {
                        // Generate a random two-digit number
                        result = Math.floor(Math.random() * 90) + 10; // Random number between 10 and 99
                    }

                    $('#modal-global').find('.modal-dialog').removeClass('modal-md');
                    $('#modal-global').find('.modal-dialog').addClass('modal-xl');
                    $('#modal-global').find('.modal-content').html(roomForm(result , res.auth_user_mode));
                    $('#modal-global').find('.modal-title').html("Add New Rooms");
                    $('#modal-global').find('.floorid').val($('.get_bread_floor_name option:selected').val());
                    $('#modal-global').modal('show');
                    isModalOpen = true;
                }
            }
        })

        // var lastDiv = $('.col-12.col-sm-6.col-md-3:last');
        // var dataFromStrongTag = lastDiv.find('strong').text();
        // var dataAfterSpace = parseInt(dataFromStrongTag.split(' ')[1]) + 1;

       

    })
    $(document).on('click', '.crossRoom', function(e) {
        e.preventDefault();
        $(this).closest('.col-12').remove(); 
        checkRoomName();
    })
    $(document).on('click', '.addMoreRoomBtn', function(e){
        e.preventDefault();
        $('#modal-global').find('.roomFormDiv:last').css({'backround':'red'})
        const lastDivRoom = $('#modal-global').find('.roomFormDiv:last');
        var iValValue = parseInt(lastDivRoom.prev().find('.iVal').val()) + 1;
        $('.roomFormDiv').before(indivisualRoomForm(iValValue));
        checkRoomName();
    })
    $(document).on('change', '#global_room_beds', function(e){
        e.preventDefault();
        let bedsNum = $(this).val();
        $('.room_beds').val(bedsNum)
    })
    $(document).on('keyup', '#global_cost_per_person', function(e){
        e.preventDefault();
        let cost = $(this).val();
        if(cost !== ''){
            if ($.isNumeric(cost)) {
                cost = parseInt(cost, 10);
                if (cost > 0 && Number.isInteger(cost)) {
                    $('.costperpersonmodel').val(cost)
                }
            }
        }
    })
    $(document).on('keyup', '.checkRoomName', function(e){
        e.preventDefault();
        checkRoomName();
    })
    $(document).on('submit', '.submitAddRooms', function(e){
        e.preventDefault();
        const input = ['roomnamemodel','roomcodemodel','roombedsmodel','costperpersonmodel'];
        if(!FormValidation('submit',input)){
            let form = $(this);
            let formData = form.serialize();
            let btnVal = $('.submitBtn');
            btnVal.addClass('disabled loading');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: BASE_URL + "/submitAddRooms",
                method: "post",
                data: formData,
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                success: function(res) {
                    btnVal.removeClass('disabled loading');
                    console.log(res)
                    if(res.status == 'error'){
                        errorToastr(res.errors)
                    } else if(res.validstatus == 'error'){
                        const errorMessage = Object.keys(res.errors).map(index => {
                            return `<div>${res.errors[index][0]}</div>`;
                        }).join(' ');
                        errorToastr(errorMessage)
                    } else if(res.exceptstatus == 'error'){
                        errorToastr(res.message)
                    } else if(res.status == 'success'){
                        isModalOpen = false;
                        $('#modal-global').modal('hide');
                        let floor_id = $('.get_bread_floor_name option:selected').val();
                        filterRoomList(floor_id , 0); // it automatically capture the building id , floor id and month-year and show the rooms.
                        $('.submitAddRooms')[0].reset();

                        setTimeout(function() {
                            successToastr(res.message)
                        }, 500);
                    }
                }
            });
        }
    })
    $(document).on('change', '#selectYear', function(e){
        e.preventDefault();
        let floor_id = $('.get_bread_floor_name option:selected').val();
        filterRoomList(floor_id , 0); // it capture the building id , floor id and month-year and show the rooms.
    })
    $(document).on('change', '#selectMonth', function(e){
        e.preventDefault();
        let floor_id = $('.get_bread_floor_name option:selected').val();
        filterRoomList(floor_id , 0); // it capture the building id , floor id and month-year and show the rooms.
    })
})
function openModal() {
    isModalOpen = true;
    $('#modal-global').modal('show');
}
function closeModal() {
    $('#modal-global').modal('hide');
    isModalOpen = false;
}
function roomForm(roomCountDiv , auth_user_mode){
    let bgColor = 'bg-light';
    let backgroundColor = '#dee2e6';
    let childBackgroundColor = '#eceff2';
    if(auth_user_mode == 1){
        bgColor = 'bg-dark';
        backgroundColor = '#000';
        childBackgroundColor = '#2a2b2c';

    }
    const roomFormDiv = `
        <div class="modal-header">
            <h4 class="modal-title">Edit Guest</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form class="submitAddRooms" method="post" enctype="multipart/form-data">
            <input type="hidden" name="floor_id" class="floorid">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4 col-md-12">
                        <div class="color-palette-set">
                            <div class="${bgColor} color-palette" style="background: ${backgroundColor} !important;"><span class="ml-2"><b>SET FOR ALL ROOMS</b></span></div>
                            <div class="${bgColor} color-palette" style="padding: 5px;background: ${childBackgroundColor} !important;">
                                <div class="row" style="margin-top: 13px;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="global_cost_per_person" step="0.01" placeholder="Cost Per Person">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select id="global_room_beds" class="form-control">
                                                <option value="">Select Number Of Beds</option>
                                                <option value="1">1 Bed</option>
                                                <option value="2">2 Beds</option>
                                                <option value="3">3 Beds</option>
                                                <option value="4">4 Beds</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-5"></div>
                                                  
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    ${indivisualRoomForm(roomCountDiv)}
                    <div class="col-md-12 roomFormDiv">
                        <button type="submit" class="btn btn-block btn-outline-primary float-right addMoreRoomBtn">
                            <i class="fas fa-plus-circle"></i> Add More Rooms
                        </button>
                    </div>
                </div>            
            </div>            
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary savechange_loader submitBtn">
                    Submit
                    <span class="loader-container">
                        <span class="loader"></span>
                    </span>
                </button>
            </div>
        </form>
    `;
    return roomFormDiv;
}
function checkRoomName(){
    let inputs = $('.checkRoomName');
    inputs.closest('.singleRoomForm').removeClass('duplicate');
    for (let i = 0; i < inputs.length; i++) {
        for (let j = i + 1; j < inputs.length; j++) {
            if (inputs[i].value === inputs[j].value && inputs[i] !== inputs[j]) {
                $(inputs[i]).closest('.singleRoomForm').addClass('duplicate');
                $(inputs[j]).closest('.singleRoomForm').addClass('duplicate');
            }
        }
    }
    // alert('Two rooms cannot have the same name.');
}
function indivisualRoomForm(roomCountDiv){
    const $formHtml =`
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column outerBox">
            <input type="hidden" value="${roomCountDiv}" class="iVal">
            <div class="card bg-light d-flex flex-fill singleRoomForm" style="border-radius: 0  25px 0 0;">
                <div class="text-muted border-bottom-0">
                    <div class="text-muted border-bottom-0 text-right">
                        <i class="fas fa-times-circle text-danger fa-2x crossRoom" style="cursor:pointer"></i>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Room Name</label>
                                <input type="text" name="room_name[]" value="Room ${roomCountDiv}" class="form-control checkRoomName roomnamemodel" placeholder="Ex. Room 1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="room_code[]" value="R${roomCountDiv}" class="form-control room_code roomcodemodel" placeholder="Ex. R1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Number of Beds</label>
                                <select id="" class="form-control room_beds roombedsmodel" name="number_of_beds[]">
                                    <option value="1">1 Bed</option>
                                    <option value="2">2 Beds</option>
                                    <option value="3">3 Beds</option>
                                    <option value="4">4 Beds</option>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cost_per_person">Cost Per Person</label>
                                <input type="number" class="form-control costperpersonmodel" name="cost_per_person[]" step="0.01" placeholder="Enter cost">
                            </div>
                        </div>            
                    </div>            
                </div>            
            </div>            
        </div> 
        `;
        return $formHtml;
}
