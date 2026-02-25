<table width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <td align="center">
            <table cellspacing="0" cellpadding="5" align="center">
                <tr>
                    <td valign="middle" style="padding-right:10px;">
                        <img src="{{ public_path('logo.png') }}" height=80">
                    </td>
                    <td valign="middle">
                        <h2 style="margin:0;">ALPHA MINING & EXPLOSIVE</h2>
                        <p style="margin:2px 0 0 0;">
                            Bilaspur (C.G)
                        </p>
                        <p style="margin:2px 0 0 0;">
                            Drilling Report
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<hr>

<table width="100%" cellspacing="0" cellpadding="3">
    <tr>
        <td align="right" style="font-size:12px;">
            <strong>Report Period:</strong>
            {{ $request->from_date ? format_date($request->from_date) : 'Beginning' }}
            &nbsp; to &nbsp;
            {{ $request->to_date ? format_date($request->to_date) : format_date(now()) }}
        </td>
    </tr>
</table>

<br>


<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>#</th>
        <th>Debitor</th>
        <th>Site</th>
        <th>Operator</th>
        <th>Hole</th>
        <th>Meter</th>
        <th>Diesel</th>
        <th>Hours</th>
        <th>Balance Diesel</th>
    </tr>
    </thead>

    <tbody>
    @foreach($reports as $i => $r)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r->debitor->account_name ?? '-' }}</td>
            <td>{{ $r->site->site_name ?? '-' }}</td>
            <td>{{ $r->operator->name ?? '-' }}</td>
            <td align="right">{{ indian_number($r->hole) }}</td>
            <td align="right">{{ indian_number($r->meter) }}</td>
            <td align="right">{{ indian_number($r->diesel) }}</td>
            <td align="right">{{ indian_number($r->total_hours) }}</td>
            <td align="right">{{ indian_number($r->balance_diesel) }}</td>


        </tr>
    @endforeach

    {{-- TOTAL ROW --}}
    <tr>
        <th colspan="4" align="right">Total</th>
        <th align="right">{{ $totals['holes'] }}</th>
        <th align="right">{{ $totals['meter'] }}</th>
        <th align="right">{{ $totals['diesel'] }}</th>
        <th align="right">{{ $totals['hours'] }}</th>
        <th align="right">{{ $totals['balance_diesel'] }}</th>
    </tr>
    </tbody>
</table>
