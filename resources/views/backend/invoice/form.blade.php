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


        {{-- Add / Manage Item --}}
        <div class="form-group col-md-3">
            <label>Add / Manage Item</label>

            <div class="d-flex align-items-start">

                {{-- Left side : input + select --}}
                <div class="flex-grow-1 mr-2">
                    <input type="text" id="itemInput" class="form-control mb-2" placeholder="New site name">

                    <select id="itemSelect" class="form-control">
                        <option value="">Select site</option>
                    </select>
                </div>

                {{-- Right side : vertical buttons --}}
                <div class="btn-group-vertical btn-group-sm">
                    <button type="button" id="addItem" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" id="editItem" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button type="button" id="deleteItem" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>



    </div>



    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Items</h3>
            <button type="button" id="addRowBtn" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add Row
            </button>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-sm mb-0" id="itemsTable">
                <thead class="thead-light text-center">
                    <tr>
                        <th style="width:40px;">S.No</th>
                        <th>Item</th>
                        <th>HSN / SAC</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>A.Price</th>
                        <th>A.Amount</th>
                        <th style="width:40px;">❌</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center sn">1</td>

                        <td>
                            <select class="form-control form-control-sm itemSelect">
                                <option value="">Select Item</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" class="form-control form-control-sm hsn">
                        </td>

                        <td>
                            <input type="text" class="form-control form-control-sm unit">
                        </td>

                        <td>
                            <input type="number" class="form-control form-control-sm qty">
                        </td>

                        <td>
                            <input type="number" class="form-control form-control-sm price">
                        </td>

                        <td>
                            <input type="number" class="form-control form-control-sm amount" readonly>
                        </td>

                        <td>
                            <input type="number" class="form-control form-control-sm aprice">
                        </td>

                        <td>
                            <input type="number" class="form-control form-control-sm aamount" readonly>
                        </td>

                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10" style="height:60px; border:none;" class="font-weight-bold text-center">Tax Calculation</td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4">Total</td>

                        <!-- Total Qty -->
                        <td>
                            <input type="number" id="totalQty"
                                class="form-control form-control-sm text-right" readonly>
                        </td>

                        <td></td>

                        <!-- Total Amount -->
                        <td>
                            <input type="number" id="totalAmount"
                                class="form-control form-control-sm text-right" readonly>
                        </td>

                        <td></td>

                        <!-- Total A.Amount -->
                        <td>
                            <input type="number" id="totalAAmount"
                                class="form-control form-control-sm text-right" readonly>
                        </td>

                        <td></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>CGST %</td>
                        <td>
                            <input type="number" id="cgstPercent" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="cgstAmount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>SGST %</td>
                        <td>
                            <input type="number" id="sgstPercent" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="sgstAmount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>IGST %</td>
                        <td>
                            <input type="number" id="igstPercent" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="igstAmount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="6">Net Amount</td>
                        <td>
                            <input type="number" id="netAmount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>

            
        </div>
    </div>

</div>
