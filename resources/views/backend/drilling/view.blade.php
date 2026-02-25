<div class="modal-header">
    <h5 class="modal-title">Drilling Report</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

    {{-- BASIC INFO --}}
    <table class="table table-bordered table-sm mb-3">
        <tr>
            <th width="30%">Date</th>
            <td>{{ $drillingReport->date }}</td>
        </tr>
        <tr>
            <th>Account</th>
            <td>{{ $drillingReport->debitor->account_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Site</th>
            <td>{{ $drillingReport->site->site_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Operator</th>
            <td>{{ $drillingReport->operator->name ?? '-' }}</td>
        </tr>
    </table>

    <hr>

    {{-- WORKING HOURS --}}
    <h6 class="text-primary mb-2">
        <i class="fas fa-clock"></i> Working Hours
    </h6>
    <div class="p-2 border rounded bg-light text-center">
        <strong>
            {{ $drillingReport->start_time }}
            &nbsp;–&nbsp;
            {{ $drillingReport->end_time }}
        </strong>
        <div class="mt-1 text-muted">
            = {{ $drillingReport->total_hours }} Hours
        </div>
    </div>

    <hr>

    {{-- EXTRA DETAILS --}}
    <h6 class="text-primary mb-2">
        <i class="fas fa-industry"></i> Operational Details
    </h6>
    <div class="row">
        <div class="col-md-6">
            <strong>Diesel:</strong> {{ $drillingReport->diesel }}
        </div>
        <div class="col-md-6">
            <strong>Balance Diesel:</strong> {{ $drillingReport->balance_diesel }}
        </div>
        <div class="col-md-6 mt-2">
            <strong>Hole:</strong> {{ $drillingReport->hole }}
        </div>
        <div class="col-md-6 mt-2">
            <strong>Meter:</strong> {{ $drillingReport->meter }}
        </div>
    </div>

    <hr>

    {{-- REMARK --}}
    <h6 class="text-primary mb-2">
        <i class="fas fa-comment"></i> Remark
    </h6>

    <div class="p-2 border rounded">
        {{ $drillingReport->remark ?? 'No remarks provided' }}
    </div>

</div>
