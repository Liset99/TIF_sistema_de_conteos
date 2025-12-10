<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'provincias';
    protected $primaryKey = 'idProvincia';
    public $incrementing = true;
    protected $keyType = 'int';

    public function listas()
    {
        return $this->hasMany(Lista::class, 'idProvincia');
    }

    public function mesas()
    {
        return $this->hasMany(Mesa::class, 'idProvincia');
    }

    // LÓGICA DE DOMINIO

    public function totalMesas()
    {
        return $this->cantidad_mesas ?? 0;
    }

    public function cantidadDeMesasEsValida()
    {
        return is_int($this->cantidad_mesas) && $this->cantidad_mesas >= 0;
    }

}

