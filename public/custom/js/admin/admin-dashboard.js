clientDetails();









function clientDetails(){
    $.ajax({
        url: BASE_URL + "/clientDetails",
        type: 'POST',
        data: {pagename: 'dashboard'},
        datatype: "json",
        success: function(res){
            console.log(res)
            if(res.status == 'success'){
                $('.showClientDetails').html(res.basicDetail);
            } else if(res.status == 'error'){
                $('.showClientDetails').html("");
            }
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}