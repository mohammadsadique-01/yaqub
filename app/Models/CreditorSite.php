<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditorSite extends Model
{
    protected $fillable = [
        'creditor_id',
        'site_name'
    ];

    public function sites()
    {
        return $this->hasMany(CreditorSite::class);
    }
}
