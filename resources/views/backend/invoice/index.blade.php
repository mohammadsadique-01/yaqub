@extends('backend.layouts.admin')

@section('title', 'Invoice')

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
});

</script>

@endpush
