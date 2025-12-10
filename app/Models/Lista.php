<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $primaryKey = 'idLista';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'idLista', 'nombre', 'alianza',
        'cargoDiputado', 'cargoSenador', 'idProvincia'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'idProvincia', 'idProvincia');
    }

    public function candidatos()
    {
        return $this->hasMany(Candidato::class, 'idLista', 'idLista');
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idLista', 'idLista');
    }

    // LÓGICA DE DOMINIO

    public function puedeSerEliminada(): bool
    {
        return count($this->resultados) === 0;
    }

    public function tieneCandidatos(): bool
    {
        return count($this->candidatos) > 0;
    }

    public function esValida(): bool
    {
        if (! $this->tieneCandidatos()) {
            return false;
        }

        if (! $this->provincia) {
            return false;
        }

        return true;
    }
}

