<div class="card-body">
    <input type="hidden" id="invoice_id" name="invoice_id">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Invoice Number</label>
            <input type="text" id="invoice_number" name="invoice_number" class="form-control" readonly>
        </div>
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
            <label>Site</label>
            <select name="debitor_site_id" class="form-control select2bs4 siteSelect">
                <option value="">Select Site</option>
            </select>
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
                            <select name="items[0][item_id]" class="form-control form-control-sm itemSelect">
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option 
                                        value="{{ $item->id }}"
                                        data-hsn="{{ $item->hsn_sac }}"
                                        data-unit="{{ $item->unit }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <input type="text" name="items[0][hsn_sac]" class="form-control form-control-sm hsn" readonly>
                        </td>

                        <td>
                            <input type="text" name="items[0][unit]" class="form-control form-control-sm unit" readonly>
                        </td>

                        <td>
                            <input type="number" name="items[0][qty]" class="form-control form-control-sm qty">
                        </td>

                        <td>
                            <input type="number" name="items[0][price]" class="form-control form-control-sm price">
                        </td>

                        <td>
                            <input type="number" name="items[0][amount]" class="form-control form-control-sm amount" readonly>
                        </td>

                        <td>
                            <input type="number" name="items[0][a_price]" class="form-control form-control-sm aprice">
                        </td>

                        <td>
                            <input type="number" name="items[0][a_amount]" class="form-control form-control-sm aamount" readonly>
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
                            <input type="number" id="totalQty" name="total_qty"
                                class="form-control form-control-sm text-right" readonly>
                        </td>

                        <td></td>

                        <!-- Total Amount -->
                        <td>
                            <input type="number" id="totalAmount" name="total_amount"
                                class="form-control form-control-sm text-right" readonly>
                        </td>

                        <td></td>

                        <!-- Total A.Amount -->
                        <td>
                            <input type="number" id="totalaAmount" name="total_a_amount"
                                class="form-control form-control-sm text-right" readonly>       
                        </td>

                        <td></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>CGST %</td>
                        <td>
                            <input type="number" id="cgstPercent" name="cgst_percent" value="9" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="cgstAmount" name="cgst_amount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>SGST %</td>
                        <td>
                            <input type="number" id="sgstPercent" name="sgst_percent" value="9" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="sgstAmount" name="sgst_amount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>IGST %</td>
                        <td>
                            <input type="number" id="igstPercent" name="igst_percent" value="0" class="form-control form-control-sm text-right">
                        </td>
                         <td>
                            <input type="number" id="igstAmount" name="igst_amount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>Freight</td>
                        <td>
                        </td>
                        <td>
                            <input type="number" id="freightAmount" name="freight_amount" class="form-control form-control-sm text-right">
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="4"></td>
                        <td>Discount</td>
                        <td>
                            <div class="d-flex">
                                <select id="discountType" name="discount_type" class="form-control form-control-sm mr-1" style="width:80px;">
                                    <option value="fixed">₹</option>
                                    <option value="percent">%</option>
                                </select>

                                <input type="number" id="discount" class="form-control form-control-sm text-right" placeholder="Enter">
                            </div>
                        </td>
                        <td>
                            <input type="number" id="discountAmount" name="discount_amount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="bg-light font-weight-bold text-right">
                        <td colspan="6">Net Amount</td>
                        <td>
                            <input type="number" id="netAmount" name="net_amount" class="form-control form-control-sm text-right" readonly>
                        </td>
                        <td></td>
                        <td>
                           <div class="d-flex align-items-center">
                                <input type="checkbox" id="with_tax" name="with_tax" value="1" class="mr-1" checked>
                                <label for="with_tax" class="mb-0 mr-2">Tax</label>

                                <input type="number" id="netaAmount" name="net_a_amount"
                                    class="form-control form-control-sm text-right" readonly>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            
        </div>
    </div>

</div>
