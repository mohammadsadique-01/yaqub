<div class="modal-header bg-info">
    <h5 class="modal-title text-white">
        Invoice : {{ $invoice->invoice_number }}
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

    <div class="row mb-3">

        {{-- Left Side --}}
        <div class="col-md-6">
            @if(optional($invoice->debitor)->account_name)
                <h6 class="mb-1">
                    <strong>Account :</strong>
                    {{ $invoice->debitor->account_name }}
                </h6>
            @endif

            @if(optional($invoice->debitorSite)->site_name)
                <h6 class="mb-0">
                    <strong>Site :</strong>
                    {{ $invoice->debitorSite->site_name }}
                </h6>
            @endif
        </div>

        {{-- Right Side --}}
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            @if($invoice->date)
                <h6 class="mb-0">
                    <strong>Date :</strong>
                    {{ format_date($invoice->date) }}
                </h6>
            @endif
        </div>

    </div>

    {{-- Items Table --}}
    @if($invoice->items->count() > 0)
        <h6 class="font-weight-bold">Items</h6>

        <table class="table table-sm table-bordered text-center">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>HSN</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th>A.Price</th>
                    <th>A.Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($item->item)->name ?? '-' }}</td>
                        <td>{{ $item->hsn_sac ?? '-' }}</td>
                        <td>{{ $item->unit ?? '-' }}</td>
                        <td>{{ indian_number($item->qty) }}</td>
                        <td>₹ {{ indian_number($item->price) }}</td>
                        <td>₹ {{ indian_number($item->amount) }}</td>
                        <td>₹ {{ indian_number($item->a_price) }}</td>
                        <td>₹ {{ indian_number($item->a_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Totals --}}
    @if($invoice->total_qty || $invoice->total_amount || $invoice->net_amount)
        <div class="row mt-3">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    @if($invoice->total_qty)
                        <tr>
                            <th>Total Qty</th>
                            <td>{{ indian_number($invoice->total_qty) }}</td>
                        </tr>
                    @endif

                    @if($invoice->total_amount)
                        <tr>
                            <th>Total Amount</th>
                            <td>₹ {{ indian_number($invoice->total_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->cgst_amount)
                        <tr>
                            <th>CGST ({{ $invoice->cgst_percent }}%)</th>
                            <td>₹ {{ indian_number($invoice->cgst_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->sgst_amount)
                        <tr>
                            <th>SGST ({{ $invoice->sgst_percent }}%)</th>
                            <td>₹ {{ indian_number($invoice->sgst_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->igst_amount)
                        <tr>
                            <th>IGST ({{ $invoice->igst_percent }}%)</th>
                            <td>₹ {{ indian_number($invoice->igst_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->freight_amount)
                        <tr>
                            <th>Freight</th>
                            <td>₹ {{ indian_number($invoice->freight_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->discount_amount)
                        <tr>
                            <th>Discount</th>
                            <td>₹ {{ indian_number($invoice->discount_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->net_amount)
                        <tr class="bg-light font-weight-bold">
                            <th>Net Amount</th>
                            <td>₹ {{ indian_number($invoice->net_amount) }}</td>
                        </tr>
                    @endif

                    @if($invoice->net_a_amount)
                        <tr class="bg-light font-weight-bold">
                            <th>Net A Amount</th>
                            <td>₹ {{ indian_number($invoice->net_a_amount) }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    @endif

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>