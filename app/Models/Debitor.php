<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debitor extends Model
{
    protected $fillable = [
        'account_name',
        'actual_address',
        'billing_address',
        'location_id',
        'village_id',
        'gst_number',
        'phone',
        'lease_area',
        'lease_period',
        'remark',
        'status',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sites()
    {
        return $this->hasMany(DebitorSite::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
