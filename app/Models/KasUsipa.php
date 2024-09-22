<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasUsipa extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_usipa',
        'saldo_usipa',
        'saldo_before_usipa_trans'
    ];

    public function transactions()
    {
        return $this->hasMany(KasUsipaTrans::class, 'kas_usipa_id');
    }
}
