$(document).ready(function(){
    $(document).on('click', '#setFloor', function(e) {
        e.preventDefault();

        if(!checkValidation('set')){
            let number_of_rooms = $('#number_of_rooms').val();
            if ($.isNumeric(number_of_rooms)) {
                number_of_rooms = parseInt(number_of_rooms, 10);
                if (number_of_rooms > 0 && Number.isInteger(number_of_rooms)) {
                    let btnVal = $('#setFloor');
                    btnVal.prop('disabled', true);
                    btnVal.removeClass('btn-primary');
                    btnVal.addClass('btn-outline-primary loading');
                    setTimeout(function() {
                        $('.roomBox').css({'display':'block'})
                        let lastDivWithValue = lastDivValue();
                        let htmlForRooms = roomForm(number_of_rooms , lastDivWithValue);
                        $('#addMoreRoom').before(htmlForRooms)
                        htmlForRooms = 0;
                        btnVal.removeClass('btn-outline-primary loading');
                        btnVal.addClass('btn-primary');
                        btnVal.prop('disabled', false);
                        $('.select2').select2();

                    }, 1000); // Interval time in milliseconds (e.g., 2000 for 2 seconds)
                }
            } else {
                $('#number_of_rooms').addClass('is-invalid')
                errorToastr("Room Count should be a numeric value.");
            }
        }
    }) 
    $(document).on('input', '.room_name, .floor_code, #number_of_rooms', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
    $(document).on('click', '#addMoreRoom', function(e) {
        e.preventDefault();
        
        let lastDivWithValue = lastDivValue();
        let htmlForRooms = roomForm(1 , lastDivWithValue);
        $('#addMoreRoom').before(htmlForRooms)
        checkRoomName();
    })  
    $(document).on('click', '.crossRoom', function(e) {
        e.preventDefault();
        $(this).closest('.col-12').remove(); 
        checkRoomName();

    })
    $(document).on('keyup', '#global_cost_per_person', function(e){
        e.preventDefault();
        let cost = $(this).val();
        if(cost !== ''){
            if ($.isNumeric(cost)) {
                cost = parseInt(cost, 10);
                if (cost > 0 && Number.isInteger(cost)) {
                    $('.cost_per_person').val(cost)
                }
            }
        }
    })
    $(document).on('change', '#global_room_beds', function(e){
        e.preventDefault();
        let bedsNum = $(this).val();
        $('.room_beds').val(bedsNum)
    })
    $(document).on('keyup', '.checkRoomName', function(e){
        e.preventDefault();
        checkRoomName();

    })
    $(document).on('submit', '#submitRoomForm', function(e){
        e.preventDefault();
        if(!checkValidation('submit')){
            let number_of_rooms = $('#number_of_rooms').val();
            if ($.isNumeric(number_of_rooms)) {
                number_of_rooms = parseInt(number_of_rooms, 10);
                if (number_of_rooms > 0 && Number.isInteger(number_of_rooms)) {
                    let form = $(this);
                    let btnVal = $('.btnSubmit');
                    btnVal.addClass('disabled loading');
                    let formData = new FormData(form[0]);
                    const roomListRoute = $('.roomListUrl').data('client-list-route'); // Retrieve the URL

                    $.ajax({
                        url: BASE_URL + "/submitRoom",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        datatype: "json",
                        success: function(res){
                            if(res.validstatus == 'error'){
                                errorToastr(res.errors.floor_name[0])
                                btnVal.removeClass('btn-outline-primary disabled loading');
                                btnVal.addClass('btn-primary');
                            } else if(res.exceptstatus == 'error'){
                                errorToastr(res.message)
                                btnVal.removeClass('btn-outline-primary disabled loading');
                                btnVal.addClass('btn-primary');
                            } else if(res.status == 'success'){
                                // $('#submitRoomForm')[0].reset();
                                successToastr(res.message)
                                setTimeout(function() {
                                    window.location.href = roomListRoute;
                                    btnVal.removeClass('btn-outline-primary disabled loading');
                                    btnVal.addClass('btn-primary');
                                }, 2000);
                            }
                        }
                    });
                }
            } else {
                $('#number_of_rooms').addClass('is-invalid')
                errorToastr("Room Count should be a numeric value.");
            }
        }
    });
})





function lastDivValue(){
    const outerBoxDivs = document.querySelectorAll('div.outerBox');
    let lastDivWithValue = null;
    for (let i = outerBoxDivs.length - 1; i >= 0; i--) {
        const input = outerBoxDivs[i].querySelector('input.iVal');
        if (input && input.value !== '') {
            lastDivWithValue = parseInt(input.value) + 1;
            break;
        }
    }
    if (lastDivWithValue) {
        console.log('Last div with input value:', lastDivWithValue);
    } else {
        lastDivWithValue = 1;
        // console.log('No div with input value found.');
    }
    return lastDivWithValue;
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

function roomForm(numberOfRooms , roomNumStart){
    let roomHtml = '';
    for (let i = 0; i < numberOfRooms; i++) {
        roomHtml += 
        `
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column outerBox">
            <input type="hidden" value="`+ (i + roomNumStart) +`" class="iVal">
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
                            <input type="text" name="room_name[]" value="Room `+ (i + roomNumStart) +`" class="form-control checkRoomName" placeholder="Ex. Room 1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="room_code[]" value="R`+ (i + roomNumStart) +`" class="form-control room_code" placeholder="Ex. R1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Number of Beds</label>
                            <select id="" class="form-control room_beds" name="number_of_beds[]">
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
                            <input type="number" class="form-control cost_per_person" name="cost_per_person[]" step="0.01" placeholder="Enter cost">
                        </div>
                    </div>
                </div>
                </div>
            </div>                                            
        </div>
        `;
    }
    return roomHtml; // Return the concatenated HTML

}

function checkValidation(type){
    let inputClasses;
    if(type === 'submit'){
        inputClasses = ['room_name', 'floor_code','room_count','checkRoomName', 'room_code', 'cost_per_person']; // Array of input classes to check
    } else if(type === 'set'){
        inputClasses = ['room_name', 'floor_code','room_count']; // Array of input classes to check
    }

    let missingFields = new Set(); // To store unique labels
    let errorMsg = false; // Error message variable
    
    // Iterate through each input class
    inputClasses.forEach(className => {
        const inputs = document.getElementsByClassName(className);
    
        // Iterate through the inputs of a particular class
        for (let i = 0; i < inputs.length; i++) {
            const input = inputs[i];
            const label = $(input).siblings('label').text().trim(); // Get the label text
    
            // Check if the input is empty
            if (input.value.trim() === '') {
                input.classList.add('is-invalid'); // Add 'invalid' class
    
                // If the label is not already in missingFields, add it
                if (!missingFields.has(label)) {
                    missingFields.add(label);
                    errorMsg = true;
                }
            } else {
                input.classList.remove('is-invalid'); // Remove 'invalid' class
            }
        }
    });
    
    // Show the error message for empty fields
    if (errorMsg) {
        let errorMsgText = `Please fill in the following fields: ${Array.from(missingFields).join(', ')}.`;
        errorToastr(errorMsgText); // Display the error message
    }
    return errorMsg;
}