<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentInvoices extends Model
{
     protected $table = 'sentinvoices';
      protected $casts = [
        'jsondata' => 'json'
    ];
    use HasFactory;
}
