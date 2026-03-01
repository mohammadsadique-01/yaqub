<div id="dataTableWrapper" class="mt-2">
    <table id="dataTable" class="table table-bordered table-hover text-nowrap">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Invoice</th>
            <th>Account</th>
            <th>tax</th>
            <th>Amount</th>
            <th>A. Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

@push('scripts')
<script>
    function initInvoiceTable() {
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: "{{ route('invoice.data') }}",
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'invoice', name: 'invoice_number' },
                    { data: 'debitor', name: 'debitor.account_name' },
                    { data: 'tax', name: 'tax' },
                    { data: 'amount', name: 'net_amount' },
                    { data: 'a_amount', name: 'net_a_amount' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            
            });
        }
    }

    initInvoiceTable();

    $(document).on('click', '.deleteInvoice', function () {

        let id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this invoice?')) {
            return;
        }

        $.ajax({
            url: "/invoice/" + id,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {

                if (response.status) {

                    toastr.success(response.message);

                    if (dataTable) {
                        dataTable.ajax.reload(null, false);
                    }
                }
            },
            error: function () {
                toastr.error('Something went wrong!');
            }
        });

    });

   
</script>
    
@endpush