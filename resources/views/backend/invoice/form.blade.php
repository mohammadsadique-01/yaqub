<div class="card-body">

    <div class="form-row">
            <div class="form-group col-md-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group col-md-3">
                <label>Debitor <span class="text-danger">*</span></label>
                <select name="debitor_id" class="form-control select2bs4 debitorSelect" required>
                    <option value="">Select Debitor</option>
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
    </div>

</div>
