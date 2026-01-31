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

fetchPaidBuildingList();




$(document).on('change', '.paid_buildname', function(e){
    e.preventDefault();
    const selectedBuildingId = $(this).val();
    selectBuildingToFetchFloor(selectedBuildingId);
})
$(document).on('change', '.paid_floorname', function(e){
    e.preventDefault();
    let building_id = $('.paid_buildname option:selected').val();
    let floor_id = $(this).val();
    let year = $('#selectYear').val();
    let month = $('#selectMonth').val();

    var formData = new FormData();
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);
    formData.append('page', 1);

    $('.overlay').css({'display':'block'})

    loadpaidGuest(formData);
})
$(document).on('change', '#selectYear', function(e){
    e.preventDefault();
    let building_id = $('.paid_buildname option:selected').val();
    let floor_id = $('.paid_floorname option:selected').val();
    let year = $(this).val();
    let month = $('#selectMonth').val();

    var formData = new FormData();
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);
    formData.append('page', 1);

    $('.overlay').css({'display':'block'})

    loadpaidGuest(formData);
})
$(document).on('change', '#selectMonth', function(e){
    e.preventDefault();
    let building_id = $('.paid_buildname option:selected').val();
    let floor_id = $('.paid_floorname option:selected').val();
    let year = $('#selectYear').val();
    let month = $(this).val();

    var formData = new FormData();
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);
    formData.append('page', 1);

    $('.overlay').css({'display':'block'})

    loadpaidGuest(formData);
})
$(document).on('click', '.shareBillToGuest', function(e){
    e.preventDefault();
    const sharBillBtn = $(this);
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
                                <img class="img-circle elevation-2 mt-2" src="${$(this).closest('tr').find('td:eq(0) img').attr('src')}" alt="User Avatar" style="width: 60px; height: 60px;">
                            </div>
                            <h3 class="widget-user-username">${$(this).closest('tr').find('td:eq(1)').text()}</h3>
                            <p class="widget-user-desc">${$(this).closest('tr').find('td:eq(3)').text()} , ${$(this).closest('tr').find('td:eq(2)').text()}</p>
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
    $('#modal-global').find('.modal-title').text('Share Bill To Guest');
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
    formData.append('building_id', $('.paid_buildname option:selected').val());
    formData.append('floor_id', $('.paid_floorname option:selected').val());
    formData.append('year', $('#selectYear').val());
    formData.append('month', $('#selectMonth').val());

    shareBill(formData,'')

})
$(document).on('click', '.mail', function(e){
    e.preventDefault();
    const guest_user_id = $('.guest_user_id').val();
    var formData = new FormData();

    formData.append('type', 'mail');
    formData.append('userId', guest_user_id);
    formData.append('building_id', $('.paid_buildname option:selected').val());
    formData.append('floor_id', $('.paid_floorname option:selected').val());
    formData.append('year', $('#selectYear').val());
    formData.append('month', $('#selectMonth').val());

    shareBill(formData,'')
})
$(document).on('submit', '.downloadBill', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = new FormData(form[0]);
    form.children('button').addClass('disabled loading');

    let building_id = $('.paid_buildname option:selected').val();
    let floor_id = $('.paid_floorname option:selected').val();
    let year = $('#selectYear').val();
    let month = $('#selectMonth').val();
    formData.append('type', 'download');
    formData.append('building_id', building_id);
    formData.append('floor_id', floor_id);
    formData.append('year', year);
    formData.append('month', month);

    shareBill(formData, form)
})



function fetchPaidBuildingList(){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: BASE_URL + "/fetchPaidBuildingList",
        method: "post",
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res) {
            $('label[for="paidBuildingSelect"]').nextAll().remove();
            $('label[for="paidBuildingSelect"]').after(res.building)
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();

            let building_id = $('.paid_buildname option:selected').val();
            selectBuildingToFetchFloor(building_id); // when page load capture building id and show floor.

        }
    })
}
function selectBuildingToFetchFloor(buildname){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: BASE_URL + "/selectBuildingToFetchFloor",
        method: "post",
        data: {'buildname':buildname},
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res) {
            $('label[for="paidFloorSelect"]').nextAll().remove();
            $('label[for="paidFloorSelect"]').after(res.floor)

            let building_id = $('.paid_buildname option:selected').val();
            let floor_id = $('.paid_floorname option:selected').val();
            let year = $('#selectYear').val();
            let month = $('#selectMonth').val();

            var formData = new FormData();
            formData.append('building_id', building_id);
            formData.append('floor_id', floor_id);
            formData.append('year', year);
            formData.append('month', month);
            formData.append('page', 1);

            loadpaidGuest(formData);


            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    })
}
function loadpaidGuest(formData){
    $.ajax({
        url: BASE_URL + "/loadpaidGuest",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        datatype: "json",
        success: function(res){
            $('.user-data').html(res.tble);
            $('.show_pagination').html(res.pagination)
            $('.overlay').css({'display':'none'})
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
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
function shareBill(formData, form){
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('.overlay').css({'display':'block'})
    $.ajax({
        url: BASE_URL + "/generatePaidPDFBill",
        method: "post",
        data: formData,
        processData: false,  // Important when sending FormData
        contentType: false,  // Important when sending FormData
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res) {
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();

            if(res.status == 'error'){
                errorToastr(res.errors)
            } else if(res.exceptstatus == 'error'){
                errorToastr(res.message)
                form ? form.children('button').removeClass('disabled loading') : '';

            } else if(res.status == 'success'){
                if(res.type == "whatsapp"){
                    $('.overlay').css({'display':'none'})
                    $('#modal-global').modal('hide');

                    let mobileNumber = res.mobile;
                    let pdfFilePath = res.pdfFilePath;
                    let name = res.name;
                    var formattedLink = pdfFilePath.replace(/\s/g, '%20');
                    let whatsappLink = `https://wa.me/${mobileNumber}?text=${encodeURIComponent(formattedLink)}`;
                    successToastr(res.message)

                    window.open(whatsappLink, '_blank');

                } else if(res.type == "mail"){
                    $('.overlay').css({'display':'none'})
                    $('#modal-global').modal('hide');
                    successToastr(res.message)
                } else if(res.type == "download"){
                    $('.downloadBill').children('button').removeClass('disabled loading');
                    if (res.pdf) {
                        var blob = new Blob([base64ToArrayBuffer(res.pdf)], { type: 'application/pdf' });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.target = '_blank';
                        link.download = res.name +'.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        successToastr(res.message)
                        $('.select2').select2();
                        $('[data-toggle="tooltip"]').tooltip();
                    } else {
                        errorToastr('PDF content is undefined in the response.')
                    }
                }
            }
            $('.overlay').css({'display':'none'})


        }
    })
}