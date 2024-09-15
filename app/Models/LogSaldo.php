<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSaldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_cash_id',
        'main_cash_trans_id',
        'old_saldo',
        'new_saldo',
        'action_type'
    ];
}
