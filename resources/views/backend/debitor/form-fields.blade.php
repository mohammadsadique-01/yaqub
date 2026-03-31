<div id="form-errors" class="mt-2"></div>

{{-- NAV PILLS (BETTER THAN TABS) --}}
<ul class="nav nav-pills nav-justified mb-3 shadow-sm p-2 bg-light rounded" id="debitorTabs">

    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#basic" data-tab="basic">
            <i class="fas fa-user"></i><br>
            <small>Basic</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#address" data-tab="address">
            <i class="fas fa-map-marker-alt"></i><br>
            <small>Address</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#location" data-tab="location">
            <i class="fas fa-globe"></i><br>
            <small>Location</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#lease" data-tab="lease">
            <i class="fas fa-file-contract"></i><br>
            <small>Lease</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#sites" data-tab="sites">
            <i class="fas fa-building"></i><br>
            <small>Sites</small>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#remark" data-tab="remark">
            <i class="fas fa-sticky-note"></i><br>
            <small>Remark</small>
        </a>
    </li>

</ul>

<div class="tab-content">

    {{-- ================= BASIC ================= --}}
    <div class="tab-pane fade show active" id="basic">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Basic Info</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Account Name</label>
                    <input type="text" name="account_name" class="form-control form-control-lg">
                </div>

                <div class="form-group">
                    <label>Phone No</label>
                    <input type="text" name="phone" class="form-control form-control-lg">
                </div>

                <div class="form-group">
                    <label>GST Number</label>
                    <input type="text" name="gst_number" class="form-control">
                </div>

                <button type="button" class="btn btn-primary float-right next-tab">
                    Next <i class="fas fa-arrow-right"></i>
                </button>

            </div>
        </div>
    </div>

    {{-- ================= ADDRESS ================= --}}
    <div class="tab-pane fade" id="address">
        <div class="card card-info card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Address</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Actual Address</label>
                    <textarea name="actual_address" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label>Billing Address</label>
                    <textarea name="billing_address" id="billing_address" class="form-control" rows="2"></textarea>
                </div>

                <div class="icheck-primary">
                    <input type="checkbox" id="sameAddress">
                    <label for="sameAddress">Same as Actual</label>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-secondary prev-tab">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <button type="button" class="btn btn-primary float-right next-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= LOCATION ================= --}}
    <div class="tab-pane fade" id="location">
        <div class="card card-warning card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-globe"></i> Location</h3>
            </div>
            <div class="card-body">

                <div class="input-group mb-3">
                    <select id="locationSelect" name="location_id" class="form-control"></select>
                    <div class="input-group-append">
                        <button type="button" id="deleteLocationBtn" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>District</label>
                        <input type="text" name="district" id="district" class="form-control mb-3" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>State</label>
                        <input type="text" name="state" id="state" class="form-control mb-3" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>State Code</label>
                        <input type="text" name="state_code" id="state_code" class="form-control mb-3" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Village</label>
                        <select name="village_id" id="villageSelect" class="form-control"></select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-secondary prev-tab">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <button type="button" class="btn btn-primary float-right next-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= LEASE ================= --}}
    <div class="tab-pane fade" id="lease">
        <div class="card card-success card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-contract"></i> Lease</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label>Lease Area</label>
                    <input type="text" name="lease_area" class="form-control">
                </div>

                <div class="form-group">
                    <label>Lease Period</label>
                    <input type="text" name="lease_period" class="form-control">
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-secondary prev-tab">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <button type="button" class="btn btn-primary float-right next-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= SITES ================= --}}
    <div class="tab-pane fade" id="sites">
        <div class="card card-secondary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-building"></i> Site Names</h3>
            </div>
            <div class="card-body">

                <div id="site-wrapper"></div>

                <button type="button" class="btn btn-success mt-2 add-site">
                    <i class="fas fa-plus"></i> Add Site
                </button>

                <div class="mt-3">
                    <button type="button" class="btn btn-secondary prev-tab">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <button type="button" class="btn btn-primary float-right next-tab">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= REMARK ================= --}}
    <div class="tab-pane fade" id="remark">
        <div class="card card-dark card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sticky-note"></i> Remark</h3>
            </div>
            <div class="card-body">

                <textarea name="remark" class="form-control" rows="4"></textarea>

                <div class="mt-3">
                    <button type="button" class="btn btn-secondary prev-tab">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                   
                </div>

            </div>
        </div>
    </div>

</div>