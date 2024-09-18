<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class main_cashs extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'saldo',
        'saldo_before_trans'
    ];

    public function transactions()
    {
        return $this->hasMany(main_cash_trans::class, 'main_cash_id');
    }
}
