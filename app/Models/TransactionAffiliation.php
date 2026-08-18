<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAffiliation extends Model
{
    protected $table = 'transactions_affiliation';
    protected $fillable = [
        'user_id',
        'type',
        'montant',
        'palier_atteint',
        'description',
    ];

    protected $casts = [
        'montant' => 'float',
        'palier_atteint' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function montantFormate(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
}
