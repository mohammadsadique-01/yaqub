$(function () {
    /** Date range picker */
    /** start */
        // var start = moment().subtract(29, 'days');
        var start = moment();
        var end = moment();

        function cb(start, end) {
            $('#startDate').val(start.format('Y-M-D'));
            $('#endDate').val(end.format('Y-M-D'));
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            getActivityLog(page = 1, start = start.format('Y-M-D') , end = end.format('Y-M-D'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    /** end */

        
    function getActivityLog(page = 1, startDt = null, endDt = null){
        var formData = new FormData();
        formData.append('page', page);
        formData.append('startDt', startDt);
        formData.append('endDt', endDt);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('.overlay').css({'display':'block'})

        $.ajax({
            url: BASE_URL + "/getActivityLog",
            method: "post",
            data: formData,
            processData: false,
            contentType: false,
            datatype: "json",
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(res) {
                $('.todo-list').html(res.tble);
                $('.show_pagination').html(res.pagination);
                $('.overlay').css({'display':'none'})

            }
        })
    }


})
