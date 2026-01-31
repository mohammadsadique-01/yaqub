@extends('backend.layouts.admin')

@section('title', 'Drilling Report')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Drilling Reports</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button id="showFormBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Drilling
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

            {{-- ================= FILTER SECTION ================= --}}
            @include('backend.drilling.filter')

            {{-- ================= TABLE SECTION ================= --}}
            <div id="tableSection">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Drilling List</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @include('backend.drilling.table')
                    </div>
                </div>
            </div>

            {{-- ================= FORM SECTION ================= --}}
            <div id="formSection" class="d-none">
                <div class="card-header">
                    <h3 class="card-title">Add Drilling Report</h3>
                </div>

                <form id="drillingForm" action="{{ route('drilling.store') }}" method="POST">
                    @csrf
                    @include('backend.drilling.form')

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
let drillingTable;

$(function() {

    function initDrillingTable() {
        if(!$.fn.DataTable.isDataTable('#drillingTable')) {
            drillingTable = $('#drillingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('drilling.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false },
                    { data: 'debitor', name: 'debitor.account_name' },
                    { data: 'site', name: 'site.site_name' },
                    { data: 'operator', name: 'operator.name' },
                    { data: 'total_hours', name: 'total_hours' },
                    { data: 'diesel', name: 'diesel' },
                    { data: 'meter', name: 'meter' },
                    { data: 'remark', name: 'remark' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        }
    }

    initDrillingTable();

    
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    // Calculate total hours
    function calculateHours() {
        let start = parseFloat($('#startTime').val());
        let end   = parseFloat($('#endTime').val());

        if (isNaN(start) || isNaN(end)) {
            $('#totalHours').val('');
            return;
        }

        let total = end - start;

        if (total < 0) {
            $('.hr_error').text('End time must be greater than Start time').css('color', 'red !impotant');
            $('#totalHours').val('');
            return;
        }
        
        if (total === 0) {
            $('#totalHours').val('');
            return;
        }
        $('.hr_error').text('');

        $('#totalHours').val(total.toFixed(2));
    }

    $('#startTime, #endTime').on('input', calculateHours);

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

    // Show form button
    $('#showFormBtn').on('click', function() {
        $('#filterCard').addClass('d-none');
        $('#tableSection').addClass('d-none');
        $('#formSection').removeClass('d-none');
        $('#form-errors').html(''); // clear previous alerts
        $('#showTableBtn').removeClass('d-none');
        $('#formSection').addClass('card card-outline card-primary');
        $(this).addClass('d-none');

    });

    // Back to list button
    $('#showTableBtn').on('click', function() {
        $('#formSection').addClass('d-none');
        $('#tableSection').removeClass('d-none');
        $('#filterCard').removeClass('d-none');
        $('#showFormBtn').removeClass('d-none');
        $(this).addClass('d-none');
        drillingTable.columns.adjust().draw();
    });

});

$('#drillingForm').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(res) {
            toastr.success(res.message);

            form[0].reset();
            $('.select2bs4').val(null).trigger('change');

            $('#formSection').addClass('d-none');
            $('#tableSection').removeClass('d-none');
            $('#showFormBtn').removeClass('d-none');
            $('#showTableBtn').addClass('d-none');

            drillingTable.ajax.reload(null, false);
        },
        error: function(xhr) {
             let errors = xhr.responseJSON.errors;
            let msg = '';
            $.each(errors, function(key, value) { msg += value[0] + '<br>'; });
            toastr.error(msg);
        }
    });
});

$(document).on('click', '.deleteDrilling', function () {

    let id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this record?')) {
        return;
    }

    const url = "{{ route('drilling.destroy', ':id') }}".replace(':id', id);

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (res) {
            toastr.success(res.message);

            if (typeof drillingTable !== 'undefined') {
                drillingTable.ajax.reload(null, false);
            }
        },
        error: function () {
            toastr.error('Failed to delete record');
        }
    });
});

$(document).on('click', '.viewDrilling', function () {
    const url = $(this).data('url');

    $('#modal-global').modal('show');
    $('#modal-global .modal-content').html('Loading...');

    $.get(url, function (html) {
        $('#modal-global .modal-content').html(html);
    });
});

</script>

@endpush