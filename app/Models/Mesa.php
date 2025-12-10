<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMesa';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'idMesa', 'electores', 'establecimiento',
        'circuito', 'idProvincia'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'idProvincia', 'idProvincia');
    }

    public function telegramas()
    {
        return $this->hasMany(Telegrama::class, 'idMesa', 'idMesa');
    }

    // LÓGICA DE DOMINIO

    public function tieneTelegramasCargados(): bool
    {
        return count($this->telegramas) > 0;
    }

    public function electoresValidos(): bool
    {
        return $this->electores > 0;
    }
}

