<?php

namespace App\Helpers\Traits;

use App\Helpers\Datos;
use App\Helpers\Traits\Temas\MRU;
use App\Helpers\Traits\Temas\MRUV;

trait Ecuacion {

    use MRU, MRUV;

    /** FUNCIONES AUXILIARES */

    /**
     * @param $datosQueFaltan
     * @return void
     */
    public function tratarDatosQueFaltan($datosQueFaltan) {

        $datosTratados = [];

        foreach($datosQueFaltan as $datoQueFalta) {
            $datosTratados[$datoQueFalta] = $this->resolverEcuacion($datoQueFalta);
        }
    }

    /**
     * @param $ecuacion
     * SE OBTIENEN LOS DATOS QUE FALTAN EN LA ECUACION
     */
    public function datosQueFaltan($ecuacion) {

        $datos = $this->getDatos();

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $datosQueFaltan = [];
        preg_match_all('/[a-zA-Z_?]+/', $ecuacion, $datosQueFaltan);

        $this->tratarDatosQueFaltan($datosQueFaltan[0]);
    }

    /**
     * @param $dato_que_se_pide
     * @return mixed
     */
    public function resolverEcuacion($dato_que_se_pide) {

        $tema = $this->getTema();

        $ecuacion = $this->getEcuaciones()[$tema][$dato_que_se_pide];

        $this->datosQueFaltan($ecuacion);

        $datos = $this->getDatos();
        $datos = Datos::normalizarUnidades($datos);

        return $this->{$tema.'_'.$dato_que_se_pide}($datos, $ecuacion);
    }
}
