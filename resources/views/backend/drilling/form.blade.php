<div class="card-body">

    <div class="form-row">
        {{-- Debitor --}}
        <div class="form-group col-md-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group col-md-3">
            <label>Account <span class="text-danger">*</span></label>
            <select name="debitor_id" class="form-control select2bs4 debitorSelect" required>
                <option value="">Select Account</option>
                @foreach($debitors as $debitor)
                    <option value="{{ $debitor->id }}">{{ $debitor->account_name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Sites --}}
        <div class="form-group col-md-3">
            <label>Site <span class="text-danger">*</span></label>
            <select name="debitor_site_id" class="form-control select2bs4 siteSelect" required>
                <option value="">Select Site</option>
            </select>
        </div>

        {{-- Operators --}}
        <div class="form-group col-md-3">
            <label>Operator <span class="text-danger">*</span></label>
            <select name="operator_id" id="operatorSelect" class="form-control select2bs4" required>
                <option value="">Select Operator</option>
                @foreach($operators as $operator)
                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Start & End --}}
    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Start Hour <span class="text-danger">*</span></label>
            <input type="number"
                id="startTime"
                name="start_time"
                class="form-control"
                step="0.01"
                min="0"
                required>
        </div>

        <div class="form-group col-md-4">
            <label>End Hour <span class="text-danger">*</span></label>
            <input type="number"
                id="endTime"
                name="end_time"
                class="form-control"
                step="0.01"
                min="0"
                required>
            <small class="text-muted hr_error text-red"></small>
        </div>

        <div class="form-group col-md-4">
            <label>Total Hours</label>
            <input type="number"
                id="totalHours"
                name="total_hours"
                class="form-control"
                step="0.01"
                readonly>
        </div>
    </div>

    {{-- Other fields --}}
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Diesel (L)</label>
            <input type="number" name="diesel" step="0.01" class="form-control">
        </div>
        <div class="form-group col-md-3">
            <label>Hole</label>
            <input type="number" name="hole" class="form-control">
        </div>
        <div class="form-group col-md-3">
            <label>Meter</label>
            <input type="number" name="meter" step="0.01" class="form-control">
        </div>
        <div class="form-group col-md-3">
            <label>Balance Diesel</label>
            <input type="number" name="balance_diesel" step="0.01" class="form-control">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Remark</label>
            <textarea name="remark" rows="2" class="form-control"></textarea>
        </div>
    </div>

</div>