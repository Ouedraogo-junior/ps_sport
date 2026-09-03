<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'bookmaker',
        'code',
    ];

    // Label lisible du bookmaker
    public function bookmakerLabel(): string
    {
        return match(strtolower($this->bookmaker)) {
            '1xbet'     => '1xBet',
            'betwinner' => 'BetWinner',
            'melbet'    => 'Melbet',
            '1win'      => '1Win',
            default     => ucfirst($this->bookmaker),
        };
    }

    // Initiales affichées sur le pavé d'identité du ticket
    public function shortLabel(): string
    {
        return match(strtolower($this->bookmaker)) {
            '1xbet'     => '1X',
            'betwinner' => 'BW',
            'melbet'    => 'MB',
            '1win'      => '1W',
            default     => strtoupper(substr($this->bookmaker, 0, 2)),
        };
    }

    // Couleur d'accent du pavé d'identité, propre à chaque bookmaker
    public function accentColor(): string
    {
        return match(strtolower($this->bookmaker)) {
            '1xbet'     => '#1565C0',
            'betwinner' => '#bf360c',
            'melbet'    => '#6a1b9a',
            '1win'      => '#1b5e20',
            default     => '#333333',
        };
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}