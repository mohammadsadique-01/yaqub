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

                <form id="invoiceForm" method="POST">
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

        if (dataTable) {
            dataTable.columns.adjust();
        }
    });

    // Load Sites & Operators based on Debitor
    $('.debitorSelect').on('change', function() {
        let debitorId = $(this).val();
        if (!debitorId) return;

        // Example AJAX for sites
        let url = window.APP.routes.debitorSites.replace(':id', debitorId);

        $.get(url, function(data) {
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

        let invoiceId = $('#invoice_id').val();
        let url = "";
        let method = "";

        if(invoiceId) {
            // UPDATE
            url = "/invoice/" + invoiceId;
            method = "PUT";
        } else {
            // STORE
            url = "{{ route('invoice.store') }}";
            method = "POST";
        }
        
        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {

                if(response.status) {
                    $('#invoiceForm')[0].reset();
                    $('.debitorSelect').val(null).trigger('change');

                    $('#formSection, #showTableBtn').addClass('d-none');
                    $('#tableSection, #filterCard, #showFormBtn').removeClass('d-none');

                    loadInvoiceNumber();

                    if (dataTable) {
                        dataTable.ajax.reload(null, false);
                    }
                    
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

     // ================= EDIT INVOICE =================
    $(document).on('click', '.editBtn', function () {

        let id = $(this).data('id');

        // Show form same as Add button
        $('#filterCard, #tableSection').addClass('d-none');
        $('#formSection').removeClass('d-none').addClass('card card-outline card-primary');
        $('#showFormBtn').addClass('d-none');
        $('#showTableBtn').removeClass('d-none');
        $('#form-errors').html('');

        // Clear previous rows except first
        $('#itemsTable tbody').html('');
        rowIndex = 0;

        // Get invoice data
        $.ajax({
            url: "/invoice/" + id + "/edit",
            type: "GET",
            success: function(response) {

                let invoice = response.data;
                let items   = invoice.items;

                $('#invoiceForm').attr('action', '/invoice/' + invoice.id);

                if ($('#invoiceForm input[name=_method]').length === 0) {
                    $('#invoiceForm').append('<input type="hidden" name="_method" value="PUT">');
                }

                // ========= Fill Main Fields =========
                $('#invoice_id').val(invoice.id);
                $('#invoice_number').val(invoice.invoice_number);
                $('input[name="date"]').val(invoice.date);
                $('.debitorSelect').val(invoice.debitor_id).trigger('change');
                $('.siteSelect').val(invoice.debitor_site_id);

                $('#cgstPercent').val(invoice.cgst_percent);
                $('#sgstPercent').val(invoice.sgst_percent);
                $('#igstPercent').val(invoice.igst_percent);
                $('#freightAmount').val(invoice.freight_amount);
                $('#discountType').val(invoice.discount_type);
                $('#discount').val(invoice.discount);
                $('#discountAmount').val(invoice.discount_amount);

                if(invoice.with_tax == 1){
                    $('#with_tax').prop('checked', true);
                } else {
                    $('#with_tax').prop('checked', false);
                }

                // ========= CLEAR TABLE =========
                $('#itemsTable tbody').empty();
                rowIndex = 0;

                // ========= LOOP ITEMS =========
                items.forEach(function(item, index) {

                    // 🔥 Create new row from ORIGINAL HTML structure
                    let newRow = `
                    <tr>
                        <td class="text-center sn">${index + 1}</td>

                        <td>
                            <select name="items[${index}][item_id]" class="form-control form-control-sm itemSelect">
                                {!! collect($items)->map(function($item){
                                    return '<option value="'.$item->id.'" data-hsn="'.$item->hsn_sac.'" data-unit="'.$item->unit.'">'.$item->name.'</option>';
                                })->implode('') !!}
                            </select>
                        </td>

                        <td><input type="text" name="items[${index}][hsn_sac]" class="form-control form-control-sm hsn" readonly></td>
                        <td><input type="text" name="items[${index}][unit]" class="form-control form-control-sm unit" readonly></td>
                        <td><input type="number" name="items[${index}][qty]" class="form-control form-control-sm qty"></td>
                        <td><input type="number" name="items[${index}][price]" class="form-control form-control-sm price"></td>
                        <td><input type="number" name="items[${index}][amount]" class="form-control form-control-sm amount" readonly></td>
                        <td><input type="number" name="items[${index}][a_price]" class="form-control form-control-sm aprice"></td>
                        <td><input type="number" name="items[${index}][a_amount]" class="form-control form-control-sm aamount" readonly></td>

                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;

                    $('#itemsTable tbody').append(newRow);

                    let lastRow = $('#itemsTable tbody tr:last');

                    lastRow.find('.itemSelect').val(item.item_id).trigger('change');
                    lastRow.find('.hsn').val(item.hsn_sac);
                    lastRow.find('.unit').val(item.unit);
                    lastRow.find('.qty').val(item.qty);
                    lastRow.find('.price').val(item.price);
                    lastRow.find('.amount').val(item.amount);
                    lastRow.find('.aprice').val(item.a_price);
                    lastRow.find('.aamount').val(item.a_amount);

                    rowIndex++;
                });

                calculateTotals();

                
            }
        });

    });

 
$(document).on('click', '.viewInvoice', function () {

    const url = $(this).data('url');

    $('#modal-global .modal-dialog')
        .removeClass('modal-sm modal-lg modal-xl')
        .addClass('modal-lg');
        
    $('#modal-global').modal('show');
    $('#modal-global .modal-content').html('Loading...');

    $.get(url)
        .done(function (html) {
            $('#modal-global .modal-content').html(html);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText); // See real error
            $('#modal-global .modal-content').html(
                '<div class="modal-body text-danger">Something went wrong!</div>'
            );
        });

});




});

</script>

@endpush
