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
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/items.js') }}"></script>
<script>
$(function() {
    // Show form button
    $('#showFormBtn').on('click', function() {
        $('#invoiceForm')[0].reset();
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
    $('#addRowBtn').on('click', function () {
        let newRow = $('#itemsTable tbody tr:first').clone();

        newRow.find('input').val('');
        newRow.find('.qty').val();
        newRow.find('.price, .aprice').val();

        $('#itemsTable tbody').append(newRow);
        updateSerialNumbers();
        calculateTotals();
    });

    // Remove Row
    $(document).on('click', '.removeRow', function () {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateSerialNumbers();
            calculateTotals();
        }
    });

    // Auto calculation
    $(document).on('input', '.qty, .price, .aprice', function () {
        let row = $(this).closest('tr');
        calculateRow(row);
        calculateTotals();
    });
    
    $(document).on('input', '#cgstPercent, #sgstPercent, #igstPercent', function () {
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
        let totalAAmount = 0;

        $('#itemsTable tbody tr').each(function () {

            totalQty += parseFloat($(this).find('.qty').val()) || 0;
            totalAmount += parseFloat($(this).find('.amount').val()) || 0;
            totalAAmount += parseFloat($(this).find('.aamount').val()) || 0;

        });

        $('#totalQty').val(totalQty.toFixed(2));
        $('#totalAmount').val(totalAmount.toFixed(2));
        $('#totalAAmount').val(totalAAmount.toFixed(2));

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

        let netAmount = totalAmount + cgstAmount + sgstAmount + igstAmount;

        $('#netAmount').val(netAmount.toFixed(2));
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

});

</script>

@endpush
