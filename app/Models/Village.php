<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'village_name',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
