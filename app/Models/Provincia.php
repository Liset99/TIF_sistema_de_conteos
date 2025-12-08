<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;
    
    protected $table = 'provincias';  // <-- AGREGAR ESTA LÍNEA
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
}
