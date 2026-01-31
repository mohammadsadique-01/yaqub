notificationData();

function notificationData(page = 1) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: BASE_URL + "/notificationData",
        type: 'POST',
        data: {page: page},
        datatype: "json",
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(res){
            $('.head_notification').removeAttr('style'); // Remove inline CSS style

            if(res.status === 'success'){
                if(res.count > 3){
                    $('.head_notification').css({'height':'250px'});
                }
                $('.user-data').html(res.tble);
                $('.show_pagination').html(res.pagination)
                $('.notification_count').html(res.notificationCount);
                $('.head_notification').html(res.headeNotification);

            }

        }
    });
}