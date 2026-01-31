// let globelFloorValue = null;

let building_id = $('.get_bread_buildname option:selected').val();
selectBuilding(building_id, 0); // when page load capture building id and show floor.
buildingUsage(building_id);
latestGuest();
let selectedYear = parseInt($('#selectYear').val());
let selectedMonth = parseInt($('#selectMonth').val());
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth() + 1;
datewiseFilterInDashboard(currentYear, currentMonth);

$(document).on('change', '#selectYear, #selectMonth', function () {
    var selectedYear = parseInt($('#selectYear').val());
    var selectedMonth = parseInt($('#selectMonth').val());

    datewiseFilterInDashboard(selectedYear, selectedMonth);

});
$(document).ready(function() {
    $("#daysLeftKnob").knob({
        'min': 0,
        'max': 30,
        'readOnly': true,
        'fgColor': '#39CCCC',
        'width': 75,
        'height': 75,
        'draw': function () {
            // Custom function to display days left inside the knob
            var daysLeft = this.cv;
            var textColor = daysLeft <= 10 ? 'red' : 'black'; // Change text color to red when 10 days or less left
            this.i.css('font-size', '16px').css('color', textColor).text(daysLeft + " days left");
        }
    });
});

function buildingUsage(buildingId){
    $.ajax({
        url: BASE_URL + "/buildingUsage",
        type: 'POST',
        data: {buildingId: buildingId},
        datatype: "json",
        success: function(res){
            if(res.status == 'success'){
                $('.buildingUsage').html(res.html);
            } else if(res.status == 'error'){
                $('.buildingUsage').html("");
            }
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}   
function latestGuest(){
    $.ajax({
        url: BASE_URL + "/latestGuest",
        type: 'POST',
        datatype: "json",
        success: function(res){
            $('.guestNumber').html("");

            if(res.status == 'success'){
                $('.latestGuest').html(res.html);
                $('.guestNumber').html(res.guestCount + ' Total Guests');
            } else if(res.status == 'error'){
                $('.latestGuest').html("");
            }
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}
function datewiseFilterInDashboard(selectYear, selectMonth){
    
    $.ajax({
        url: BASE_URL + "/datewiseFilterInDashboard",
        type: 'POST',
        data: {'year': selectYear, 'month': selectMonth},
        datatype: "json",
        success: function(res){
            if(res.status == 'success'){
                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var monthName = monthNames[selectMonth - 1]; // Adjust the index since months are zero-based in JavaScript
                $('.yearmonthShow').empty().append('<i class="fas fa-filter"></i>').append(document.createTextNode(' '+ monthName + ' ' + selectYear));

                $('.paidPaymentMessage').empty().html(res.paidPaymentMessage);
                $('.unpaidPaymentMessage').empty().html(res.unpaidPaymentMessage);
                $('.returnAdvanceAmtMessage').empty().html(res.returnAdvanceAmtMessage);
                var numericData = res.monthlyPayment.map(function(value) {
                    return parseInt(value.replace(',', ''), 10);
                });
                $('.yearShow').empty().append('<i class="fas fa-filter"></i>').append(document.createTextNode(' ' + selectYear));
                
                Highcharts.chart('container', {
                    chart: { type: 'spline' },
                    title: { text: '' },
                    xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    accessibility: { description: 'Months of the year' }
                    },
                    yAxis: {
                        title: {
                            text: 'Amount'
                        },
                        labels: {
                            format: '{value}'
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true,
                        formatter: function() {
                            return '<b>' + this.x + '</b><br/>' + this.series.name + ': ₹' + this.y.toLocaleString();
                        }
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            },
                        },
                    },
                    series: [{
                        name: 'Month',
                        marker: {
                            symbol: 'square'
                        },
                        data: numericData
                    }]
                });

             
                // Monthly Guest Rent Analysis
                donut(res.guestPaidAmtMonthly , res.guestunPaidAmtMonthly);
                
            } else if(res.status == 'error'){
                $('.datewiseFilterInDashboard').html("");
            }
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}
function donut(guestPaidAmtMonthly , guestunPaidAmtMonthly){
    // Initial label values
    var rentPayingLabel = `Total Rent-paying Guests: ${guestPaidAmtMonthly}`;
    var nonPayingLabel = `Total Non-paying Guests: ${guestunPaidAmtMonthly}`;

    // Donut Chart Data
    var donutData = {
        labels: [rentPayingLabel, nonPayingLabel],
        datasets: [{
        data: [guestPaidAmtMonthly, guestunPaidAmtMonthly],
        backgroundColor: ['#00a65a','#f56954' ], // Adjusted colors for two datasets
        }]
    };

    // Donut Chart Options
    var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
        labels: {
            fontColor: 'white',
            fontStyle: 'bold' 
        }
        },
        tooltips: {
        callbacks: {
            label: function(tooltipItem, data) {
            var label = data.labels[tooltipItem.index] || '';
            if (label) {
                label;
            }
            return label;
            }
        }
        }
    };

    // Get the canvas element
    var donutChartCanvas = $('#donut-chart').get(0).getContext('2d');

    // Create the donut chart
    var donutChart = new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    });
  
}