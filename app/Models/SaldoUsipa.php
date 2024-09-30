<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoUsipa extends Model
{
    use HasFactory;

    protected $fillable = [
        'saldo_awal_usipa'
    ];
}
