discontinueGuestList();



function discontinueGuestList(page = 1){
    $.ajax({
        url: BASE_URL + "/discontinueGuestList",
        type: 'POST',
        data: {page: page},
        datatype: "json",
        success: function(res){
            if(res.status == 'success'){
                $('.user-data').html(res.tble);
                $('.show_pagination').html(res.pagination)

                $('.select2').select2();
                $('[data-toggle="tooltip"]').tooltip();
            }
        }
    });
}