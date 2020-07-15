<?php

namespace App\Http\Controllers;

use App\Helpers\Fisica;
use App\Helpers\Wit;
use App\Http\Requests\ResolverProblemaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProblemaController extends Controller
{
    public function resolverProblema(ResolverProblemaRequest $request) {
        $wit = new Wit();

        $mensajeString = $request->input('problema');

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

            return Response::json([
                'error' => false,
                'problema' => [
                    'datos' => $fisica->getDatos(),
                    'se_solicita' => $fisica->getSeSolicita(),
                    'resultado' => $fisica->getResultado(),
                ]
            ]);
        }

        return Response::json([
            'error' => true,
            'mensaje' => 'No se pudo resolver el problema',
        ]);
    }
}
