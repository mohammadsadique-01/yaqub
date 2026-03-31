@extends('backend.layouts.admin')

@section('title', 'Debitor')
@push('styles')
<style>
.nav-pills .nav-link.tab-error {
    background-color: #dc3545 !important;
    color: #fff !important;
}
</style>
@endpush
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Debitor</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button id="showFormBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Debitor
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
                        <h3 class="card-title">Debitor List</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        @include('backend.debitor.table')
                    </div>
                </div>
            </div>

            {{-- ================= FORM SECTION ================= --}}
            <div id="formSection" class="d-none">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title" id="formTitle">Add Debitor</h3>
                    </div>

                    <form id="debitorForm" action="{{ route('debitor.store') }}" method="POST">
                        @csrf
                        @include('backend.debitor.form-fields')

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </div>

@endsection

@push('scripts')
<script>
    let debitorTable;

    /* ================= SITE HELPERS ================= */
    function addSiteInput(id = null, value = '') {
        $('#site-wrapper').append(`
            <div class="row mt-1 site-row">
                <div class="col-md-10">
                    <input type="text"
                        name="site_name[]"
                        class="form-control form-control-sm"
                        value="${value}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm btn-block remove-site" data-id="${id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
    }

    $(document).on('click', '.add-site', function () {
        addSiteInput();
    });

    $(document).on('click', '.remove-site', function () {
        let siteId = $(this).data('id');
        let row = $(this).closest('.site-row');

        if (!siteId) {
            // New row (not saved yet)
            row.remove();
            return;
        }
        
        if (!confirm('Delete this site?')) return;

        let url = "{{ route('debitor.debitor-sites.destroy', ':id') }}";
        url = url.replace(':id', siteId);

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                row.remove();
                toastr.success(res.message);
            },
            error: function (xhr) {
                // 👇 THIS IS THE IMPORTANT PART
                let msg = 'Unable to delete site';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                toastr.error(msg);
            }
        });
    });

    /* ================= DATATABLE ================= */
    function initDebitorTable() {
        if(!$.fn.DataTable.isDataTable('#debitorTable')) {
            debitorTable = $('#debitorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('debitor.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'account_name', name: 'account_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'district', name: 'district', orderable: false },
                    { data: 'state', name: 'state', orderable: false },
                    { data: 'gst_number', name: 'gst_number' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        }
    }

    $(document).ready(function() {
        initDebitorTable();

        $('#villageSelect').prop('disabled', true);

        $(document).on('change', '#locationSelect', function () {
            $('#villageSelect').prop('disabled', !this.value);
        });


        // Show form button
        $('#showFormBtn').on('click', function() {
            $('#tableSection').addClass('d-none');
            $('#formSection').removeClass('d-none');
            $('#form-errors').html('');
            $('#formTitle').text('Add Debitor');
            loadLocations(); // 🔥 reload locations

            $('#debitorForm')[0].reset();
            $('#debitorForm').attr('action', "{{ route('debitor.store') }}");
            $('#debitorForm input[name="_method"]').remove(); // remove PUT if present
            $(this).addClass('d-none');
            $('#showTableBtn').removeClass('d-none');
        });

        /* ================= BACK TO LIST ================= */
        $('#showTableBtn').on('click', function() {
            $('#formSection').addClass('d-none');
            $('#tableSection').removeClass('d-none');
            $('#showFormBtn').removeClass('d-none');
            $(this).addClass('d-none');

            debitorTable.ajax.reload();
        });

        /* ================= EDIT DEBITOR ================= */


        // Same address checkbox
        $('#sameAddress').on('change', function () {
            $('#billing_address').val(
                this.checked ? $('textarea[name="actual_address"]').val() : ''
            );
        });

        /* ================= FORM SUBMIT ================= */
        $('#debitorForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let method = form.find('input[name="_method"]').val() || 'POST';
            let formData = form.serialize();

            $('#form-errors').html('');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if(response.success){

                        $('#form-errors').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                                ${response.success}
                            </div>
                        `);

                        form[0].reset();
                        $('#site-wrapper').html('');

                        // Switch back to table view
                        $('#formSection').addClass('d-none');
                        $('#tableSection').removeClass('d-none');
                        $('#showFormBtn').removeClass('d-none');
                        $('#showTableBtn').addClass('d-none');

                        // Reload table
                        debitorTable.ajax.reload();
                    }
                },
                error: function (xhr) {
                    // let tabPane = field.closest('.tab-pane').attr('id');
                    // highlightTab(tabPane);

                    let errorHtml = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                    `;

                    // 🔥 remove old errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // ✅ VALIDATION (422)
                    if (xhr.status === 422 && xhr.responseJSON.errors) {

                        let firstField = null;

                        errorHtml += '<ul>';

                        $.each(xhr.responseJSON.errors, function (key, value) {

                            errorHtml += `<li>${value[0]}</li>`;

                            let field = $(`[name="${key}"]`);

                            // 🔥 highlight field
                            field.addClass('is-invalid');

                            // 🔥 show inline error
                            field.after(`<div class="invalid-feedback">${value[0]}</div>`);

                            // store first field
                            if (!firstField) {
                                firstField = field;
                            }
                        });

                        errorHtml += '</ul>';

                        // 🔥 AUTO SWITCH TAB
                        if (firstField) {
                            let tabPane = firstField.closest('.tab-pane').attr('id');

                            if (tabPane) {
                                $(`.nav-pills a[href="#${tabPane}"]`).tab('show');
                            }

                            // 🔥 focus
                            firstField.focus();
                        }

                        toastr.error('Please fix the errors');

                    } 
                    // SERVER ERROR
                    else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorHtml += `<p>${xhr.responseJSON.message}</p>`;
                        toastr.error(xhr.responseJSON.message);
                    } 
                    else {
                        errorHtml += `<p>Something went wrong</p>`;
                        toastr.error('Something went wrong');
                    }

                    errorHtml += '</div>';

                    $('#form-errors').html(errorHtml);
                }
                
            });
        });

        /* ================= EDIT DEBITOR ================= */
        $(document).on('click', '.editDebitor', function () {

            const id = $(this).data('id');
            const url = "{{ route('debitor.edit', ':id') }}".replace(':id', id);
            $('#debitorTabs a[href="#basic"]').tab('show');

            $.get(url, function (res) {
                $('#formTitle').text('Edit Debitor');
                // 1️⃣ Show form
                $('#tableSection').addClass('d-none');
                $('#formSection').removeClass('d-none');
                $('#showFormBtn').addClass('d-none');
                $('#showTableBtn').removeClass('d-none');
                $('#form-errors').html('');

                // 2️⃣ Reset form first
                $('#debitorForm')[0].reset();
                $('#site-wrapper').html('');
                $('#debitorForm input[name="_method"]').remove();

                // 3️⃣ Fill fields
                $('input[name="account_name"]').val(res.account_name);
                $('input[name="phone"]').val(res.phone);
                $('input[name="gst_number"]').val(res.gst_number);
                $('textarea[name="actual_address"]').val(res.actual_address);
                $('textarea[name="billing_address"]').val(res.billing_address);
                $('input[name="lease_area"]').val(res.lease_area);
                $('input[name="lease_period"]').val(res.lease_period);
                $('textarea[name="remark"]').val(res.remark);

                // fill sites (🔥 FIXED)
                if (Array.isArray(res.sites) && res.sites.length) {
                    res.sites.forEach(site => {
                        addSiteInput(site.id, site.site_name);
                    });
                } else {
                    addSiteInput();
                }

                // 4️⃣ Load locations and select correct one
                if(typeof loadLocations === 'function') {
                    selectedVillageId = res.village_id;

                    loadLocations(res.location_id); // this will select the current location
                    loadVillages(res.location_id);
                }

                // 5️⃣ Fill district/state/state_code
                $('#district').val(res.district);
                $('#state').val(res.state);
                $('#state_code').val(res.state_code);

                // 7️⃣ Change form action to UPDATE
                let url = window.APP.routes.debitorUpdate.replace(':id', id);
                $('#debitorForm').attr('action', url);
                if($('#debitorForm input[name="_method"]').length === 0) {
                    $('#debitorForm').append('<input type="hidden" name="_method" value="PUT">');
                }
            });
        });

        $(document).on('click', '.deleteDebitor', function () {

            const id = $(this).data('id');

            if (!confirm('Are you sure you want to delete this debitor?')) {
                return;
            }

            const url = "{{ route('debitor.destroy', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $('#form-errors').html(`
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            ${res.success}
                        </div>
                    `);

                    // reload datatable
                    if (typeof debitorTable !== 'undefined') {
                        debitorTable.ajax.reload();
                    }

                    toastr.success(res.success);
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message || 'Delete failed';

                    $('#form-errors').html(`
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            ${msg}
                        </div>
                    `);

                    toastr.error(msg);

                }
            });
        });

        $(document).on('click', '.viewDebitor', function () {
            const url = $(this).data('url');

            $('#modal-global').modal('show');
            $('#modal-global .modal-content').html('Loading...');

            $.get(url, function (html) {
                $('#modal-global .modal-content').html(html);
            });
        });

        document.getElementById('villageSelect').addEventListener('change', function () {
            document.getElementById('villageInput').disabled = this.value !== '';
        });

    });

    $(document).on('click', '.prev-tab', function () {
        let currentTab = $('.tab-pane.active');

        currentTab.find('.is-invalid').removeClass('is-invalid');

        $('.nav-pills .active').parent().prev().find('a').click();
    });

    $(document).on('click', '.next-tab', function () {

        let currentTab = $('.tab-pane.active');

        // remove old errors
        currentTab.find('.is-invalid').removeClass('is-invalid');
        currentTab.find('.invalid-feedback').remove();

        let hasError = false;

        // 🔥 ONLY REQUIRED FIELD (account_name)
        let accountInput = currentTab.find('[name="account_name"]');

        if (accountInput.length && accountInput.val().trim() === '') {

            accountInput.addClass('is-invalid');
            accountInput.after('<div class="invalid-feedback">Account name is required</div>');

            highlightTab(currentTab.attr('id'));

            accountInput.focus();
            hasError = true;

            toastr.error('Account name is required');
        }

        if (hasError) return;

        // ✅ go to next tab
        $('.nav-pills .active').parent().next().find('a').click();
    });


    function highlightTab(tabId) {
        $('.nav-pills .nav-link').removeClass('tab-error');

        $(`.nav-pills a[href="#${tabId}"]`).addClass('tab-error');
    }

    $(document).on('input', '[name="account_name"]', function () {

        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();

            $('.nav-pills a[href="#basic"]').removeClass('tab-error');
        }
    });

    $('#formTitle').text('Add Debitor');


</script>
@endpush