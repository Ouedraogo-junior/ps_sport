<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldeAffiliation extends Model
{
    protected $table = 'soldes_affiliation';
    
    protected $fillable = [
        'user_id',
        'solde',
        'total_cumule',
        'filleuls_depuis_retrait',
    ];

    protected $casts = [
        'solde'                   => 'float',
        'total_cumule'            => 'float',
        'filleuls_depuis_retrait' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soldeFormate(): string
    {
        return number_format($this->solde, 0, ',', ' ') . ' FCFA';
    }

    public function totalCumuleFormate(): string
    {
        return number_format($this->total_cumule, 0, ',', ' ') . ' FCFA';
    }
}