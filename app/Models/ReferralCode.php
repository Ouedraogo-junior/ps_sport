<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    protected $fillable = ['user_id', 'code', 'actif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Générer un code unique format PSP-XXXXXX
    public static function generer(int $userId): self
    {
        do {
            $code = 'ZEN-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('code', $code)->exists());

        return self::create([
            'user_id' => $userId,
            'code'    => $code,
            'actif'   => true,
        ]);
    }
}