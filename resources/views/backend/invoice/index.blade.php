@extends('backend.layouts.admin')

@section('title', 'Invoice')

@push('styles')
    <style>
        .vertical-divider {
            width: 2px;
            height: 70px;
            background-color: #dee2e6;
            margin-top: 22px;
        }
    </style>
    <style>
        /* Strong top border for entire tfoot */
        #itemsTable tfoot {
            border-top: 3px solid #000 !important;
        }

        /* Optional: make totals section more highlighted */
        #itemsTable tfoot tr {
            background-color: #f8f9fa;
        }

        /* Optional: extra bold line before first total row only */
        #itemsTable tfoot tr:first-child td {
            border-top: 3px solid #000 !important;
        }
    </style>
    <style>
        #itemsTable tfoot tr:first-child td {
            padding-top: 15px !important;   /* space above */
            border-top: 3px solid #000 !important;
        }
    </style>


@endpush

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoice</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button id="showFormBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Invoice
                    </button>

                    <button id="showTableBtn" class="btn btn-secondary d-none">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div id="alertContainer" class="mt-2"></div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div id="form-errors" class="mt-2"></div>

            {{-- ================= TABLE SECTION ================= --}}
            <div id="tableSection">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list mr-1"></i> Invoice List</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @include('backend.invoice.table')
                    </div>
                </div>
            </div>

            {{-- ================= FORM SECTION ================= --}}
            <div id="formSection" class="d-none">
                <div class="card-header">
                    <h3 class="card-title">Add Invoice</h3>
                </div>

                <form id="invoiceForm" action="{{ route('drilling.store') }}" method="POST">
                    @csrf
                    @include('backend.invoice.form')

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
$(function() {
    // Show form button
    $('#showFormBtn').on('click', function() {
        $('#invoiceForm')[0].reset();
        loadInvoiceNumber();
        $('#filterCard, #tableSection').addClass('d-none');
        $('#formSection').removeClass('d-none').addClass('card card-outline card-primary');
        $('#form-errors').html('');
        $('#showTableBtn').removeClass('d-none');
        $(this).addClass('d-none');
    });

    // Back to list button
    $('#showTableBtn').on('click', function() {
        $('#formSection').addClass('d-none');
        $('#tableSection, #filterCard').removeClass('d-none');
        $('#showFormBtn').removeClass('d-none');
        $(this).addClass('d-none');

        if (drillingTable) {
            drillingTable.columns.adjust();
        }
    });

    // Load Sites & Operators based on Debitor
    $('.debitorSelect').on('change', function() {
        let debitorId = $(this).val();
        if (!debitorId) return;

        // Example AJAX for sites
        $.get('/master/debitors/' + debitorId + '/sites', function(data) {
            $('.siteSelect').html('<option value="">Select Site</option>');
            data.forEach(site => {
                $('.siteSelect').append('<option value="'+site.id+'">'+site.site_name+'</option>');
            });
        });

    });

    // Add Row
    let rowIndex = 1;

    $('#addRowBtn').on('click', function () {

        let newRow = $('#itemsTable tbody tr:first').clone();

        newRow.find('input, select').each(function () {

            let name = $(this).attr('name');

            if (name) {
                let updatedName = name.replace(/\d+/, rowIndex);
                $(this).attr('name', updatedName);
            }

            $(this).val('');
        });

        $('#itemsTable tbody').append(newRow);

        rowIndex++;

        updateSerialNumbers();
        calculateTotals();
    });

    // Remove Row
    $(document).on('click', '.removeRow', function () {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            reindexRows();
            calculateTotals();
        }
    });

    function reindexRows() {

        rowIndex = 0;

        $('#itemsTable tbody tr').each(function () {

            $(this).find('input, select').each(function () {

                let name = $(this).attr('name');

                if (name) {
                    let updatedName = name.replace(/\d+/, rowIndex);
                    $(this).attr('name', updatedName);
                }
            });

            rowIndex++;
        });

        updateSerialNumbers();
    }

    // Auto calculation
    $(document).on('input', '.qty, .price, .aprice', function () {
        let row = $(this).closest('tr');
        calculateRow(row);
        calculateTotals();
    });
    
    $(document).on('input', '#cgstPercent, #sgstPercent, #igstPercent', function () {
        calculateTotals();
    });

    $(document).on('input change', '#discount, #discountType', function () {
        calculateTotals();
    });


    function updateSerialNumbers() {
        $('#itemsTable tbody tr').each(function (index) {
            $(this).find('.sn').text(index + 1);
        });
    }

    function calculateTotals() {

        let totalQty = 0;
        let totalAmount = 0;
        let totalaAmount = 0;

        $('#itemsTable tbody tr').each(function () {

            totalQty += parseFloat($(this).find('.qty').val()) || 0;
            totalAmount += parseFloat($(this).find('.amount').val()) || 0;
            totalaAmount += parseFloat($(this).find('.aamount').val()) || 0;

        });

        $('#totalQty').val(totalQty.toFixed(2));
        $('#totalAmount').val(totalAmount.toFixed(2));
        $('#totalaAmount').val(totalaAmount.toFixed(2));

        // TAX CALCULATION
        let cgstPercent = parseFloat($('#cgstPercent').val()) || 0;
        let sgstPercent = parseFloat($('#sgstPercent').val()) || 0;
        let igstPercent = parseFloat($('#igstPercent').val()) || 0;

        let cgstAmount = (totalAmount * cgstPercent) / 100;
        let sgstAmount = (totalAmount * sgstPercent) / 100;
        let igstAmount = (totalAmount * igstPercent) / 100;

        $('#cgstAmount').val(cgstAmount.toFixed(2));
        $('#sgstAmount').val(sgstAmount.toFixed(2));
        $('#igstAmount').val(igstAmount.toFixed(2));

        // ================= DISCOUNT =================
        let discountType = $('#discountType').val();
        let discountValue = parseFloat($('#discount').val()) || 0;
        let discountAmount = 0;

        let grossAmount = totalAmount + cgstAmount + sgstAmount + igstAmount;

        if (discountType === 'percent') {
            discountAmount = (grossAmount * discountValue) / 100;
        } else {
            discountAmount = discountValue;
        }

        if (discountAmount > grossAmount) {
            discountAmount = grossAmount;
        }

        $('#discountAmount').val(discountAmount.toFixed(2));

        let netAmount = grossAmount - discountAmount;
        
        $('#netAmount').val(netAmount.toFixed(2));

        calculateFinalTotal();
    }

    function calculateRow(row) {

        let qty = parseFloat(row.find('.qty').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let aprice = parseFloat(row.find('.aprice').val()) || 0;

        let amount = qty * price;
        let aamount = qty * aprice;

        row.find('.amount').val(amount.toFixed(2));
        row.find('.aamount').val(aamount.toFixed(2));
    }

    calculateTotals();

    $('#itemSelect').change(function () {
        let selectedText = $("#itemSelect option:selected").text();
        $('#itemInput').val(selectedText);
    });

    $(document).on('change', '#with_tax', function () {
        calculateFinalTotal();
    });

    function calculateFinalTotal() {

        let totalAmount   = parseFloat($('#totalAmount').val()) || 0;
        let totalaAmount   = parseFloat($('#totalaAmount').val()) || 0;
        let cgstAmount    = parseFloat($('#cgstAmount').val()) || 0;
        let sgstAmount    = parseFloat($('#sgstAmount').val()) || 0;
        let igstAmount    = parseFloat($('#igstAmount').val()) || 0;
        let freightAmount = parseFloat($('#freightAmount').val()) || 0;
        let discountAmount= parseFloat($('#discountAmount').val()) || 0;

        let aAmountTotal = totalaAmount;
        // If checkbox checked → add tax
        if ($('#with_tax').is(':checked')) {
            aAmountTotal = totalaAmount + cgstAmount + sgstAmount + igstAmount + freightAmount - discountAmount;
        }

        $('#netaAmount').val(aAmountTotal.toFixed(2));
    }

    $(document).on('change', '.itemSelect', function () {
        
        let selectedOption = $(this).find(':selected');

        let hsn  = selectedOption.data('hsn');
        let unit = selectedOption.data('unit');

        let row = $(this).closest('tr');

        row.find('.hsn').val(hsn);
        row.find('.unit').val(unit);
    });

    $('#invoiceForm').on('submit', function(e) {

        e.preventDefault();
        
           
        
        $.ajax({
            url: "{{ route('invoice.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {

                if(response.status) {


                $('#invoiceForm')[0].reset();
                $('.debitorSelect').val(null).trigger('change');

                $('#formSection, #showTableBtn').addClass('d-none');
                    $('#tableSection, #filterCard, #showFormBtn').removeClass('d-none');

                      loadInvoiceNumber();

                    showAlert('success', response.message);
                    toastr.success( response.message);


                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '';

                    $.each(errors, function (key, value) {
                        errorHtml += `<div>${value[0]}</div>`;
                    });

                    showAlert('error', errorHtml);

                    toastr.error(errorHtml);
                } else {

                    showAlert('error', 'Something went wrong!');
                    toastr.error('Something went wrong!');

                }
            }
        });

    });

    function loadInvoiceNumber() {
        $.ajax({
            url: "{{ route('invoice.number') }}",
            type: "GET",
            success: function (response) {
                $('#invoice_number').val(response);
            },
            error: function () {
                toastr.error('Failed to generate invoice number');
            }
        });
    }

    loadInvoiceNumber();

});

</script>

@endpush
