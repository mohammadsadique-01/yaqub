<div id="form-errors" class="mt-2"></div>

{{-- ROW 1 --}}
<div class="row mt-3">
    {{-- BASIC INFO --}}
    <div class="col-md-3 d-flex">
        <div class="card card-outline card-primary flex-fill">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Basic Info</h3>
            </div>
            <div class="card-body p-2">
                <label class="small text-muted">Account Name</label>
                <input type="text" name="account_name" class="form-control form-control-sm mb-2">

                <label class="small text-muted">Phone No</label>
                <input type="text" name="phone" class="form-control form-control-sm mb-2">

                <label class="small text-muted">GST Number</label>
                <input type="text" name="gst_number" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    {{-- ADDRESS --}}
    <div class="col-md-3 d-flex">
        <div class="card card-outline card-info flex-fill">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Address</h3>
            </div>
            <div class="card-body p-2">
                <label class="small text-muted">Actual Address</label>
                <textarea name="actual_address" class="form-control form-control-sm mb-2" rows="2"></textarea>

                <label class="small text-muted">Billing Address</label>
                <textarea name="billing_address" id="billing_address" class="form-control form-control-sm mb-1" rows="2"></textarea>

                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="sameAddress">
                    <label for="sameAddress" class="small">Same as Actual</label>
                </div>
            </div>
        </div>
    </div>

    {{-- LOCATION --}}
    <div class="col-md-3 d-flex">
        <div class="card card-outline card-warning flex-fill">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Location</h3>
            </div>
            <div class="card-body p-2">
                <label class="small text-muted">Create Location</label>
                <div class="input-group input-group-sm mb-2">
                    <select id="locationSelect" name="location_id" class="form-control"></select>
                    <div class="input-group-append">
                        <button type="button" id="deleteLocationBtn" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="small text-muted">District</label>
                        <input type="text" name="district" id="district" class="form-control form-control-sm mb-2">
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted">State</label>
                        <input type="text" name="state" id="state"
                            class="form-control form-control-sm mb-2">
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted">State Code</label>
                        <input type="text" name="state_code" id="state_code" class="form-control form-control-sm mb-2">
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted">Village</label>

                        <select name="village_id" id="villageSelect" class="form-control form-control-sm mb-1">
                            <option value="">-- Create Village --</option>
                            {{-- existing villages loaded here --}}
                        </select>

                        <input type="text" name="village_name" id="villageInput"
                            class="form-control form-control-sm" placeholder="Or add new village">
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- LEASE --}}
    <div class="col-md-3 d-flex">
        <div class="card card-outline card-success flex-fill">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Lease</h3>
            </div>
            <div class="card-body p-2">
                <label class="small text-muted">Lease Area</label>
                <input type="text" name="lease_area" class="form-control form-control-sm mb-2">

                <label class="small text-muted">Lease Period</label>
                <input type="text" name="lease_period" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</div>


{{-- ROW 2 --}}
<div class="row">

    {{-- SITE --}}
    <div class="col-md-6">
        <div class="card card-outline card-secondary">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Site Names</h3>
            </div>

            <div class="card-body p-2">

                <div id="site-wrapper"></div>

                <button type="button"
                        class="btn btn-success btn-sm mt-2 add-site">
                    <i class="fas fa-plus"></i> Add Site
                </button>

            </div>
        </div>
    </div>


    {{-- SITE --}}
    {{-- <div class="col-md-6">
        <div class="card card-outline card-secondary">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Site Names</h3>
            </div>
            <div class="card-body p-2">

                <div class="row">
                    <div class="col-md-10">
                        <label class="small text-muted">Site Name</label>
                        <input type="text"
                            name="site_name[]"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted d-block">&nbsp;</label>
                        <button type="button"
                                class="btn btn-success btn-sm btn-block add-site">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div id="site-wrapper"></div>
            </div>
        </div>
    </div> --}}

    {{-- REMARK --}}
    <div class="col-md-6">
        <div class="card card-outline card-dark">
            <div class="card-header p-2">
                <h3 class="card-title text-sm">Remark</h3>
            </div>
            <div class="card-body p-2">

                <label class="small text-muted">Remark</label>
                <textarea name="remark"
                        class="form-control form-control-sm"
                        rows="3"></textarea>
            </div>
        </div>
    </div>

</div>