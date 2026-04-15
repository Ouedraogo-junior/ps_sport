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

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}