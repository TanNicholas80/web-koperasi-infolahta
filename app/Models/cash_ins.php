<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cash_ins extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_start',
        'date_end', 
        'periode',  
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(cash_in_trans::class);
    }
}
