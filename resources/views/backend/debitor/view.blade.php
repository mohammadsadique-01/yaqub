<div class="modal-header">
    <h5 class="modal-title">Debitor Details</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <table class="table table-sm table-bordered">
        <tr><th>Account Name</th><td>{{ $debitor->account_name }}</td></tr>
        <tr><th>Phone</th><td>{{ $debitor->phone }}</td></tr>
        <tr><th>GST Number</th><td>{{ $debitor->gst_number ?? '-' }}</td></tr>
        <tr><th>Actual Address</th><td>{{ $debitor->actual_address }}</td></tr>
        <tr><th>Billing Address</th><td>{{ $debitor->billing_address }}</td></tr>
        <tr><th>District</th><td>{{ optional($debitor->location)->district }}</td></tr>
        <tr><th>State</th><td>{{ optional($debitor->location)->state }}</td></tr>
        <tr><th>State Code</th><td>{{ optional($debitor->location)->state_code }}</td></tr>
        <tr><th>Village</th><td>{{ optional($debitor->village)->village_name  }}</td></tr>
        <tr><th>Lease Area</th><td>{{ $debitor->lease_area }}</td></tr>
        <tr><th>Lease Period</th><td>{{ $debitor->lease_period }}</td></tr>
        <tr>
            <th>Site</th>
            <td>
                @forelse($debitor->sites as $site)
                    <span class="badge badge-info">{{ $site->site_name }}</span>
                @empty
                    -
                @endforelse
            </td>
        </tr>
        <tr><th>Remark</th><td>{{ $debitor->remark }}</td></tr>
    </table>
</div>
