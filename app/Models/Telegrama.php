<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telegrama extends Model
{
    use HasFactory;

    protected $table = 'telegramas';
    protected $primaryKey = 'idTelegrama';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'idTelegrama', 'votosDiputados', 'votosSenadores',
        'blancos', 'nulos', 'impugnados',
        'fechaHora', 'idMesa'
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa', 'idMesa');
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idTelegrama', 'idTelegrama');
    }

    // LÓGICA DE DOMINIO

    public function totalVotos(): int
    {
        return $this->votosDiputados +
               $this->votosSenadores +
               $this->blancos +
               $this->nulos +
               $this->impugnados;
    }

    public function votosExcedenElectores(): bool
    {
        return $this->mesa && ($this->totalVotos() > $this->mesa->electores);
    }

    public function validarQueNoSupereElectores(): void
    {
        if ($this->votosExcedenElectores()) {
            throw new \DomainException("Los votos superan la cantidad de electores");
        }
    }
}
