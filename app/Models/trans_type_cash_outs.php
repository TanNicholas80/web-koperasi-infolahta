<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trans_type_cash_outs extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'detail_kas_keluar',  
        'kredit_trans_type',
    ];

    public function transCashOut()
    {
        return $this->hasMany(cash_out_trans::class);
    }
}
