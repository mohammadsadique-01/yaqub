<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientEmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_user_id',
        'email_templates_id',
        'subject',
        'template',
        'tag_desc',
    ];
}
