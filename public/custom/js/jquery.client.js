    function loadUsers(page = 1) {
        $.ajax({
            url: BASE_URL + "/loadClient",
            type: 'POST',
            data: {page: page},
            datatype: "json",
            success: function(res){
                $('#user-data').html(res);
            }
        });
    }

    loadUsers(); // Load initial data


    $(document).on('submit', '.editBtnClientForm', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        let buildidInput = $('.buildid');
        let buildNameInput = $('.buildname');
        let showBuildingDiv = $('.showBuilding');

        $.ajax({
            url: BASE_URL + "/editClient",
            method: "post",
            data: formData,
            success: function(res) {
                // console.log(res)
                buildNameInput.val("");
                buildidInput.val("");
                showBuildingDiv.nextAll().remove();
                
                let buildingDetails = res.building_details;
                buildingDetails.forEach((building, index) => {
                    // if (index === 0) {
                    //     buildidInput.val(building.id);
                    //     buildNameInput.val(building.building_name);
                    // } else {
                        const buildingElement = `
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input type="hidden" name="building_id[]" value="${building.id}">
                                        <input type="text" name="building_name[]" class="form-control" placeholder="Enter ..." value="${building.building_name}">
                                        <button type="submit" class="btn btn-danger input-group-text ml-2 minusBtn" value="${building.id}">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('.showBuilding:last').after(buildingElement);
                    // }
                });

                $('#userIdInput').val(res.id)
                $('#nameInput').val(res.name)
                $('#emailInput').val(res.email)
                $('#mobileInput').val(res.mobile)
                $('#statusCheckbox').bootstrapSwitch('state', res.status, true);
                $('#statusHidden').val(res.status ? '1' : '0');

                if (res.image && res.image.trim() !== '') {
                    let imageUrl = res.image;
                    $('#userImage').closest('.col-sm-4').css({'text-align':'center', 'display':'block' })
                    $('#userImage').attr('src', imageUrl);
                } else {
                    // If image data is not present or empty, hide the image
                    $('#userImage').closest('.col-sm-4').css({'text-align':'center', 'display':'none' })
                }
            },
            error: function(xhr, status, error) {
                // Handle error
            }
        });
    })
    $(document).on('submit', '.deleteBtnClientForm', function(e){
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        $.ajax({
            url: BASE_URL + "/deleteClient",
            method: "post",
            data: formData,
            success: function(res) {
                console.log(res)
                if(res.status == 'success'){
                    loadUsers(); // Load initial data
                    successToastr(res.message)
                }
            }
        });

    })
    $(document).on('submit', '#submitClient', function(e){
        e.preventDefault();
        $('#statusHidden').val($('#statusCheckbox').bootstrapSwitch('state') ? '1' : '0');
        let btnVal = $('.btnClient');
        btnVal.addClass('disabled loading');
        let form = $(this);
        let formData = new FormData(form[0]);
        let showBuildingDiv = $('.showBuilding');
        
        $.ajax({
            url: BASE_URL + "/submitClient",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            datatype: "json",
            success: function(res){
                console.log(res)
                btnVal.removeClass('disabled loading');
                if(res.status == 'error'){
                    errorToastr(res.errors)
                } else if(res.status == 'success'){
                    $('#userIdInput').val("")
                    $('#userImage').closest('.col-sm-4').css({ 'text-align': 'center', 'display': 'none' });
                    $('#userImage').attr('src', '');
                    $('#submitClient')[0].reset();
                    showBuildingDiv.nextAll().remove();
                    BuildInputFormLoad();
                    loadUsers(); // Load initial data
                    successToastr(res.message)
                }
            }
        });
    })
   
    $(document).on('click', '#plusBtn', function(e){
        e.preventDefault();
        let html = crossBtn()
        let existingDivs = $('.showBuilding').nextAll('div');
        if(existingDivs.length > 0) {
            existingDivs.last().after(html); // Append after last div
        } else {
            $('.showBuilding').after(html)
        }
    })
    $(document).on('click', '.minusBtn', function(e){
        e.preventDefault();
        $(this).closest('.col-sm-12').remove()
    })

    
    function crossBtn(){
        let html = `
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                        </div>
                        <input type="text" name="building_name[]" class="form-control" placeholder="Enter ...">
                        <button type="submit" class="btn btn-danger input-group-text ml-2 minusBtn" >
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        return html;
    }

   function BuildInputFormLoad(){
        const builtInputLoad = `
        <div class="col-sm-12">
            <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                </div>
                <input type="text" name="building_name[]" class="form-control" placeholder="Enter ...">
                <button type="submit" class="btn btn-danger input-group-text ml-2 minusBtn">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            </div>
        </div>
        `;
        $('.showBuilding').after(builtInputLoad)
        return builtInputLoad;
   }
   BuildInputFormLoad();