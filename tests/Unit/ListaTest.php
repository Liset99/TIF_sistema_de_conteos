<?php

namespace Tests\Unit;

use App\Models\Lista;
use App\Models\Candidato;
use PHPUnit\Framework\TestCase;

class ListaTest extends TestCase
{
    public function test_lista_tiene_candidatos()
    {
        $lista = new Lista();
        $lista->setRelation('candidatos', collect([new Candidato()]));

        $this->assertTrue($lista->tieneCandidatos());
    }

    public function test_lista_no_tiene_candidatos()
    {
        $lista = new Lista();
        $lista->setRelation('candidatos', collect([]));

        $this->assertFalse($lista->tieneCandidatos());
    }

    public function test_puede_ser_eliminada_cuando_no_tiene_resultados()
    {
        $lista = new Lista();
        $lista->setRelation('resultados', collect([]));

        $this->assertTrue($lista->puedeSerEliminada());
    }

    public function test_no_puede_ser_eliminada_si_tiene_resultados()
    {
        $lista = new Lista();
        $lista->setRelation('resultados', collect([1])); 

        $this->assertFalse($lista->puedeSerEliminada());
    }
}
