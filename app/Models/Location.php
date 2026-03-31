<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['state', 'district', 'state_code'];

    public function debitors()
    {
        return $this->hasMany(Debitor::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
