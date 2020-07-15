<?php

namespace Tests\Feature\App\Helpers;

use App\Helpers\Fisica;
use App\Helpers\Wit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class WitTest extends TestCase
{

    public function testMensaje()
    {

        $mensajeString = 'Un cuerpo posee una velocidad inicial de 12 m/s y una aceleración de 2 m/s2 ¿Cuánto tiempo tardará en adquirir una velocidad de 144 Km/h? ';

        $wit = new Wit();

        $responseMensaje = $wit->mensaje($mensajeString);
        if(!$responseMensaje['error']) {

            $mensaje = $responseMensaje['data'];

            $entities = Wit::ordenarEntities(Wit::agruparEntities($mensaje->entities));
            $entities = Wit::limpiarEntitiesNumber($entities);

            // TRATAMIENTO DEL PROBLEMA
            $fisica = new Fisica();
            $fisica->cargarSeSolicita($entities);
            $entities = Wit::eliminarSeSolicita($entities);
            $fisica->cargarDatos($entities, $mensajeString);
            $fisica->tratarProblema();

            dd($fisica->getResultado());
        }

        $this->assertTrue(true);
    }
}
