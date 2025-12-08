<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telegrama extends Model
{
    use HasFactory;

    protected $table = 'telegramas';  // <-- AGREGAR ESTA LÍNEA
    protected $primaryKey = 'idTelegrama';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'idTelegrama', 'votosDiputados', 'votosSenadores', 'blancos', 'nulos', 'impugnados',
        'fechaHora', 'idMesa' ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'idMesa', 'idMesa');
    }
    
    public function resultados()
    {
        return $this->hasMany(Resultado::class, 'idTelegrama', 'idTelegrama');
    }
}