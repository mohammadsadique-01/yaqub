@php
    $hideAccountColumn = $selectedDebitors->isNotEmpty();
    $hideSiteColumn = $selectedSites->count() === 1;
    $hideOperatorColumn = $selectedOperators->count() === 1;

    $colspan = 1; // #
    if(!$hideAccountColumn) $colspan++;
    if(!$hideSiteColumn) $colspan++;
    if(!$hideOperatorColumn) $colspan++;
@endphp


{{-- ================= COMPANY HEADER ================= --}}
<table width="100%" cellspacing="0" cellpadding="6">
    <tr>
        <td align="center">
            <table cellspacing="0" cellpadding="6">
                <tr>
                    <td valign="middle" style="padding-right:20px;">
                        <img src="{{ public_path('logo.png') }}" height="85">
                    </td>
                    <td valign="middle" align="left">
                        <h1 style="margin:0; font-size:24px; letter-spacing:1px;">
                            ALPHA MINING & EXPLOSIVE
                        </h1>
                        <p style="margin:3px 0; font-size:14px;">
                            Bilaspur (C.G)
                        </p>
                        <p style="margin:3px 0; font-size:16px; font-weight:bold;">
                            Drilling Report
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<hr style="margin-top:12px; margin-bottom:18px; border:1px solid #000;">


{{-- ================= FILTER + PERIOD SECTION ================= --}}
<table width="100%" cellspacing="0" cellpadding="6" style="font-size:15px;">
    <tr>
        {{-- LEFT SIDE (50%) --}}
        <td width="50%" valign="top">

            @if($selectedDebitors->isNotEmpty())
                <div style="margin-bottom:6px;">
                    <strong>Account :</strong>
                    {{ $selectedDebitors->implode(', ') }}
                </div>
            @endif

            @if($selectedSites->count() === 1)
                <div style="margin-bottom:6px;">
                    <strong>Site :</strong>
                    {{ $selectedSites->first() }}
                </div>
            @endif

            @if($selectedOperators->count() === 1)
                <div>
                    <strong>Operator :</strong>
                    {{ $selectedOperators->first() }}
                </div>
            @endif

        </td>

        {{-- RIGHT SIDE (50%) --}}
        <td width="50%" align="right" valign="top">
            <div style="font-size:15px;">
                <strong>Report Period :</strong><br>

                {{ $request->from_date ? format_date($request->from_date) : 'Beginning' }}
                &nbsp; to &nbsp;
                {{ $request->to_date ? format_date($request->to_date) : format_date(now()) }}
            </div>
        </td>
    </tr>
</table>

<br>


{{-- ================= DATA TABLE ================= --}}
<table width="100%" border="1" cellspacing="0" cellpadding="8"
       style="border-collapse:collapse; font-size:14px;">

    <thead>
        <tr style="background:#eaeaea; font-weight:bold;">
            <th width="5%">#</th>

            @if(!$hideAccountColumn)
                <th>Account</th>
            @endif

            @if(!$hideSiteColumn)
                <th>Site</th>
            @endif

            @if(!$hideOperatorColumn)
                <th>Operator</th>
            @endif

            <th align="right">Hole</th>
            <th align="right">Meter</th>
            <th align="right">Diesel</th>
            <th align="right">Hours</th>
            <th align="right">Balance Diesel</th>
        </tr>
    </thead>

    <tbody>
        @foreach($reports as $i => $r)
            <tr>
                <td align="center">{{ $i+1 }}</td>

                @if(!$hideAccountColumn)
                    <td>{{ $r->debitor->account_name ?? '-' }}</td>
                @endif

                @if(!$hideSiteColumn)
                    <td>{{ $r->site->site_name ?? '-' }}</td>
                @endif

                @if(!$hideOperatorColumn)
                    <td>{{ $r->operator->name ?? '-' }}</td>
                @endif

                <td align="right">{{ indian_number($r->hole) }}</td>
                <td align="right">{{ indian_number($r->meter) }}</td>
                <td align="right">{{ indian_number($r->diesel) }}</td>
                <td align="right">{{ indian_number($r->total_hours) }}</td>
                <td align="right">{{ indian_number($r->balance_diesel) }}</td>
            </tr>
        @endforeach

        {{-- TOTAL ROW --}}
        <tr style="font-weight:bold; background:#f5f5f5;">
            <td colspan="{{ $colspan }}" align="right">Total</td>
            <td align="right">{{ $totals['holes'] }}</td>
            <td align="right">{{ $totals['meter'] }}</td>
            <td align="right">{{ $totals['diesel'] }}</td>
            <td align="right">{{ $totals['hours'] }}</td>
            <td align="right">{{ $totals['balance_diesel'] }}</td>
        </tr>
    </tbody>
</table>