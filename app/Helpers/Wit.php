<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;
use Ixudra\Curl\Facades\Curl;

class Wit {

    private $url = '';
    private $app_id = '';
    private $token = '';

    public function __construct() {

        $this->url = config('wit.url');
        $this->app_id = config('wit.app_id');
        $this->token = config('wit.token');
    }

    /**
     * @param $mensaje
     * @return array
     */
    public function mensaje ($mensaje) {
        $response = Curl::to($this->url.'message')
            ->withHeader('Authorization: Bearer '.$this->token)
            ->withData([
                'q' => $mensaje,
            ])
            ->asJsonResponse()
            ->get();

        if(isset($response->error)) {
            return [
                'error' => true,
                'mensaje' => $response->error,
            ];
        }

        return [
            'error' => false,
            'data' => $response,
        ];
    }

    /**
     * @param $entitiesWit
     * @return array
     * AGRUPAMOS TODAS LAS ENTITIES EN UN ARRAY EN VEZ DE AGRUPARLAS POR EL TIPO DE ENTITY
     */
    public static function agruparEntities($entitiesWit) {
        $entities = [];
        foreach($entitiesWit as $entityWit) {

            foreach($entityWit as $entity) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    /**
     * @param $entities
     * @return mixed
     * SE ORDENAN LAS ENTITIES SEGÚN SU APARACIÓN EN EL MENSAJE
     */
    public static function ordenarEntities($entities) {

        for($i = 0; $i < count($entities); $i++) {
            for($j = 0; $j < count($entities) - 1; $j++) {

                $entityActual = $entities[$j];
                $entitySiguiente = $entities[$j+1];

                if($entityActual->start > $entitySiguiente->start) {
                    $entities[$j] = $entitySiguiente;
                    $entities[$j+1] = $entityActual;
                }
            }
        }

        return $entities;
    }

    /**
     * @param $entities
     * @return mixed
     */
    public static function eliminarSeSolicita($entities) {
        foreach($entities as $iEntity => $entity) {
            if($entity->name == 'se_solicita') {
                unset($entities[$iEntity]);
                unset($entities[$iEntity+1]);
            }
        }

        return $entities;
    }

    /**
     * @param $entities
     * @return mixed
     */
    public static function limpiarEntitiesNumber($entities) {

        $limpiarPalabras = [
            'Un', 'un',
            'Una', 'una',
        ];

        foreach($entities as $iEntity => $entity) {

            if($entity->name == 'wit$number') {
                if(in_array($entity->body, $limpiarPalabras)) {
                    unset($entities[$iEntity]);
                }
            }
        }

        return $entities;
    }
}
