<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftInvoice extends Model
{
     protected $table = 'invoicedraft';

     protected $casts = [
        'jsondata' => 'json'
    ];
    use HasFactory;
}
