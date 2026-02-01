<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitorSite extends Model
{
    protected $fillable = ['debitor_id', 'site_name'];

    public function debitor()
    {
        return $this->belongsTo(Debitor::class);
    }

    public function drillingReports()
    {
        return $this->hasMany(DrillingReport::class, 'debitor_site_id');
    }
}
