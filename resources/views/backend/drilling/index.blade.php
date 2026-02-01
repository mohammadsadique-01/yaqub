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
        if (!$.fn.DataTable.isDataTable('#drillingTable')) {
            drillingTable = $('#drillingTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: "{{ route('drilling.data') }}",
                    data: function (d) {
                        d.debitors   = $('#filterDebitor').val();
                        d.sites      = $('#filterSite').val();
                        d.operators  = $('#filterOperator').val();
                        d.from_date  = $('#filterFromDate').val();
                        d.to_date    = $('#filterToDate').val();
                    }
                },
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
        $('#drillingForm')[0].reset();
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

    $('#applyFilter').on('click', function () {
        if (drillingTable) drillingTable.ajax.reload();
    });

    $('#resetFilter').on('click', function () {
        $('#filterCard select').val(null).trigger('change');
        $('#filterCard input').val('');

        if (drillingTable) drillingTable.ajax.reload();
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

                $('#drillingForm')[0].reset();

                $('#drillingForm').attr('action', "{{ route('drilling.store') }}");
                $('#drillingForm input[name="_method"]').remove();

                $('select').prop('disabled', false).val(null).trigger('change');

                $('#filterCard').removeClass('d-none');
                $('#formSection').addClass('d-none');
                $('#tableSection').removeClass('d-none');
                $('#showFormBtn').removeClass('d-none');
                $('#showTableBtn').addClass('d-none');

                setInterval(() => {
                    window.location.reload();
                }, 500);
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
                    drillingTable.ajax.reload();
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

    $(document).on('click', '.editDrilling', function () {

        let id = $(this).data('id');

        $.get("{{ route('drilling.edit', ':id') }}".replace(':id', id), function (data) {

            // switch UI
            $('#tableSection').addClass('d-none');
            $('#formSection').removeClass('d-none');
            $('#formSection').addClass('card card-outline card-primary');
            $('#showFormBtn').addClass('d-none');
            $('#showTableBtn').removeClass('d-none');
            $('#filterCard').addClass('d-none');

            // change form action to UPDATE
            $('#drillingForm').attr('action',
                "{{ route('drilling.update', ':id') }}".replace(':id', id)
            );

            if ($('#drillingForm input[name="_method"]').length === 0) {
                $('#drillingForm').append('<input type="hidden" name="_method" value="PUT">');
            }

            // fill values
            $('input[name="date"]').val(data.date);
            $('input[name="start_time"]').val(data.start_time);
            $('input[name="end_time"]').val(data.end_time);
            $('input[name="total_hours"]').val(data.total_hours);
            $('input[name="diesel"]').val(data.diesel);
            $('input[name="hole"]').val(data.hole);
            $('input[name="meter"]').val(data.meter);
            $('input[name="balance_diesel"]').val(data.balance_diesel);
            $('textarea[name="remark"]').val(data.remark);

            // set selects but DISABLE them
            $('select[name="debitor_id"]').val(data.debitor_id).trigger('change').prop('disabled', true);
            $('select[name="operator_id"]').val(data.operator_id).trigger('change').prop('disabled', true);

            // load sites & lock
            $.get('/master/debitors/' + data.debitor_id + '/sites', function (sites) {
                let siteSelect = $('.siteSelect');
                siteSelect.html('');
                sites.forEach(site => {
                    siteSelect.append(
                        `<option value="${site.id}">${site.site_name}</option>`
                    );
                });
                siteSelect.val(data.debitor_site_id).trigger('change').prop('disabled', true);
            });

        });
    });

    $('#filterDebitor').on('change', function () {
        let debitorIds = $(this).val();

        $('#filterSite').html('').trigger('change');

        if (!debitorIds || debitorIds.length === 0) {
            return;
        }

        $.ajax({
            url: "{{ route('drilling.filterSiteBydebitor') }}",
            data: { debitor_ids: debitorIds },
            success: function (sites) {
                sites.forEach(site => {
                    $('#filterSite').append(
                        `<option value="${site.id}">${site.site_name}</option>`
                    );
                });

                $('#filterSite').trigger('change');
            }
        });
    });

});


</script>

@endpush