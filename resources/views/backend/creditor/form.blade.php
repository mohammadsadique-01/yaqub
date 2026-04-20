<div class="card-body">

    <!-- Account Name -->
    <div class="form-group">
        <label>Account Name :</label>
        <input type="text" class="form-control" name="account_name">
    </div>

    <!-- Consignee Address -->
    <div class="form-group">
        <label>Consignee Address :</label>
        <textarea class="form-control" rows="2" name="consignee_address"></textarea>
    </div>

    <div class="form-group">
        <label>Consignee Address 2 :</label>
        <input type="text" class="form-control" name="consignee_address2">
    </div>

    <!-- Location -->
    <div class="row">
        <div class="col-md-4">
            <label>Consignee District :</label>
            <input type="text" class="form-control" name="consignee_district">
        </div>
        <div class="col-md-4">
            <label>Consignee City :</label>
            <input type="text" class="form-control" name="consignee_city">
        </div>
        <div class="col-md-4">
            <label>Consignee State :</label>
            <input type="text" class="form-control" name="consignee_state">
        </div>
    </div>

    <br>

    <!-- Billing -->
    <div class="form-group">
        <label>Billing Address :</label>
        <textarea class="form-control" rows="2" name="billing_address"></textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Billing City :</label>
            <input type="text" class="form-control" name="billing_city">
        </div>
        <div class="col-md-6">
            <label>Billing State :</label>
            <input type="text" class="form-control" name="billing_state">
        </div>
    </div>

    <br>

    <!-- Contact -->
    <div class="row">
        <div class="col-md-4">
            <label>Contact Person :</label>
            <input type="text" class="form-control" name="contact_person">
        </div>
        <div class="col-md-4">
            <label>Phone No :</label>
            <input type="text" class="form-control" name="phone">
        </div>
        <div class="col-md-4">
            <label>Customer Code :</label>
            <input type="text" class="form-control" name="customer_code">
        </div>
    </div>

    <br>

    <!-- Site At (Multiple) -->
    <div class="row">
        <div class="col-md-12">
            <label>Site At :</label>

            <div id="site-container">
                <div class="input-group mb-2">
                    <input type="text" name="site_at[]" class="form-control" placeholder="Enter Site">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-success add-site">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Extra -->
    <div class="row">
        <div class="col-md-6">
            <label>Magazine No :</label>
            <input type="text" class="form-control" name="magazine_no">
        </div>
        <div class="col-md-6">
            <label>Thana :</label>
            <input type="text" class="form-control" name="thana">
        </div>
    </div>

    <br>

    <!-- License -->
    <div class="row">
        <div class="col-md-4">
            <label>Licence No :</label>
            <input type="text" class="form-control" name="licence_no">
        </div>
        <div class="col-md-4">
            <label>Licence Valid :</label>
            <input type="date" class="form-control" name="licence_valid">
        </div>
        <div class="col-md-4">
            <label>Licence Type :</label>
            <input type="text" class="form-control" name="licence_type">
        </div>
    </div>

    <br>

    <div class="form-group">
        <label>GSTIN :</label>
        <input type="text" class="form-control" name="gstin">
    </div>

    <!-- Agreement -->
    <div class="row">
        <div class="col-md-6">
            <label>Agreement Period :</label>
            <input type="text" class="form-control" name="agreement_period">
        </div>
        <div class="col-md-6">
            <label>Lease Area :</label>
            <input type="text" class="form-control" name="lease_area">
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-6">
            <label>Lease Period :</label>
            <input type="text" class="form-control" name="lease_period">
        </div>
        <div class="col-md-6">
            <label>Hide Quantity :</label>
            <select class="form-control" name="hide_quantity">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
    </div>

    <br>

    <!-- Remarks -->
    <div class="form-group">
        <label>Remarks :</label>
        <textarea class="form-control" rows="3" name="remarks"></textarea>
    </div>

    <!-- Opening -->
    <div class="row">
        <div class="col-md-6">
            <label>Opening Amount :</label>
            <input type="number" class="form-control" name="opening_amount" value="0">
        </div>
        <div class="col-md-6">
            <label>Amount Type :</label>
            <select class="form-control" name="amount_type">
                <option value="DR">DR</option>
                <option value="CR">CR</option>
            </select>
        </div>
    </div>

</div>