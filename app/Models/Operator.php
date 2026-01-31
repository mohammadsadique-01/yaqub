<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'remark',
    ];

    public function drillingReports()
    {
        return $this->hasMany(DrillingReport::class);
    }

}
