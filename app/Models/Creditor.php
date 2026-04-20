<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creditor extends Model
{
    protected $fillable = [
        'account_name','phone','gstin',
        'consignee_address','consignee_address2',
        'consignee_district','consignee_city','consignee_state',
        'billing_address','billing_city','billing_state',
        'contact_person','customer_code',
        'magazine_no','thana',
        'licence_no','licence_valid','licence_type',
        'agreement_period','lease_area','lease_period',
        'hide_quantity','remarks',
        'opening_amount','amount_type'
    ];

    public function sites()
    {
        return $this->hasMany(CreditorSite::class);
    }
}
