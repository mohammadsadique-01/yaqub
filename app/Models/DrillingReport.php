<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrillingReport extends Model
{
    protected $fillable = [
        'date',
        'financial_year_id',
        'debitor_id',
        'debitor_site_id',
        'operator_id',
        'start_time',
        'end_time',
        'total_hours',
        'diesel',
        'hole',
        'meter',
        'balance_diesel',
        'remark',
    ];

    public function debitor()
    {
        return $this->belongsTo(Debitor::class);
    }

    public function site()
    {
        return $this->belongsTo(DebitorSite::class, 'debitor_site_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
