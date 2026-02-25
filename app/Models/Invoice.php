<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'financial_year_id',
        'debitor_id',
        'debitor_site_id',
        'total_qty',
        'total_amount',
        'total_a_amount',
        'cgst_percent',
        'cgst_amount',
        'sgst_percent',
        'sgst_amount',
        'igst_percent',
        'igst_amount',
        'freight_amount',
        'discount_type',
        'discount_amount',
        'net_amount',
        'net_a_amount',
        'with_tax',
        'remark',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
