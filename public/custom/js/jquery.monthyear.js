$(function () {
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth() + 1;

    for (var year = currentYear; year >= currentYear - 1; year--) {
        $('#selectYear').append('<option value="' + year + '">' + year + '</option>');
    }
    monthLoop(currentYear, currentMonth);

    // Set default values to current year and month
    $('#selectYear').val(currentYear);
    $('#selectMonth').val(new Date().getMonth() + 1);


    // Disable future months and years
    $(document).on('change', '#selectYear, #selectMonth', function () {
        var selectedYear = parseInt($('#selectYear').val());
        var selectedMonth = parseInt($('#selectMonth').val());

        // if (selectedYear > currentYear || (selectedYear === currentYear && selectedMonth > currentMonth)) {
        //     // Disable future months and years
        //     $('#selectYear option[value="' + selectedYear + '"]').prop('selected', false);
        //     $('#selectMonth option[value="' + selectedMonth + '"]').prop('selected', false);
        // }
        monthLoop(selectedYear, selectedMonth);
    });
})

function monthLoop(selectedYear, selectedMonth){
    $('#selectMonth').html("");
    let currentYear = new Date().getFullYear();

    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    for (var month = 1; month <= 12; month++) {
        if (selectedYear == currentYear){
            let selectedMonth = new Date().getMonth() + 1;

            if(month > selectedMonth){
                $('#selectMonth').append('<option value="' + month + '" disabled>' + monthNames[month - 1] + '</option>');
            } else {
                $('#selectMonth').append('<option value="' + month + '">' + monthNames[month - 1] + '</option>');
            }
        } else {
            $('#selectMonth').append('<option value="' + month + '">' + monthNames[month - 1] + '</option>');
        }
    }
    // if (selectedYear == currentYear){
    //     let selectedMonth = new Date().getMonth() + 1;

    //     $('#selectYear').val(selectedYear);
    //     $('#selectMonth').val(selectedMonth);
    // } else {
    //     $('#selectYear').val(selectedYear);
    //     $('#selectMonth').val(selectedMonth);

    // }
    $('#selectYear').val(selectedYear);
    $('#selectMonth').val(selectedMonth);


}
// $(function () {
//     var currentDate = new Date();
//     var currentYear = currentDate.getFullYear();
//     var currentMonth = currentDate.getMonth();
//     $(".datepicker").datepicker({
//         dateFormat: 'MM yy',
//         changeMonth: true,
//         changeYear: true,
//         showButtonPanel: true,
//         minDate: new Date(2023, 0 , 1), // Set minimum date to the first day of the previous month
//         maxDate: '0', // Restrict selection to today's date or earlier
//         defaultDate: new Date(currentYear, currentMonth, 1), // Set default date to first day of current month and year
//         onClose: function(dateText, inst) {
//             var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//             var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//             $(this).datepicker('setDate', new Date(year, month, 1));
//         }
//     }).focus(function() {
//         $(".ui-datepicker-calendar").hide();
//     });

//     $(".datepicker").datepicker('setDate', new Date(currentYear, currentMonth, 1));





//     // Populate the year dropdown with a range of years (adjust as needed)
//     var currentYear = new Date().getFullYear();
//     for (var i = currentYear; i >= currentYear - 1; i--) {
//         $('#selectYear').append('<option value="' + i + '">' + i + '</option>');
//     }

//     // Set default values (current year and month)
//     $('#selectYear').val(currentYear);
//     $('#selectMonth').val(new Date().getMonth() + 1); // Months are 0-indexed in JavaScript

//     // Event listener for changes in the dropdowns
//     $('.form-group').on('change', 'select', function() {
//         var selectedYear = $('#selectYear').val();
//         var selectedMonth = $('#selectMonth').val();

//         // Use the selectedYear and selectedMonth as needed
//         console.log('Selected Year:', selectedYear);
//         console.log('Selected Month:', selectedMonth);
//     });
// })
