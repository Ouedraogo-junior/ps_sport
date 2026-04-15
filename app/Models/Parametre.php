<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $primaryKey = 'cle';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = ['cle', 'valeur', 'libelle', 'groupe', 'type'];

    // Helper statique — Parametre::get('whatsapp_numero')
    public static function get(string $cle, mixed $defaut = null): mixed
    {
        return static::where('cle', $cle)->value('valeur') ?? $defaut;
    }

    // Helper statique — Parametre::set('whatsapp_numero', '22600000000')
    public static function set(string $cle, mixed $valeur): void
    {
        static::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
    }
}