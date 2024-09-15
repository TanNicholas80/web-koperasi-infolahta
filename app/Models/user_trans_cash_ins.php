<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_trans_cash_ins extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',  
        'debet_user',
    ];

    public function transCashIn()
    {
        return $this->hasMany(cash_in_trans::class);
    }
}
