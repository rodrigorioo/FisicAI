<?php

namespace App\Helpers\Traits;

trait Problema {

    use Matematica;

    /**
     * SE CARGA EL RESULTADO SEGUN LOS DATOS QUE SE SOLICITARON
     */
    public function cargarResultado () {
        $datos = $this->getDatos();
        $se_solicita = $this->getSeSolicita();

        foreach($se_solicita as $dato_que_se_solicita) {

            $dato = [];
            for($i = 0; $i < count($datos); $i++) {
                if($datos[$i]['dato'] == $dato_que_se_solicita) {
                    $dato = $datos[$i];
                }
            }

            $this->setResultado([
                $dato_que_se_solicita => $dato['valor'].' '.$dato['unidad'],
            ]);
        }
    }

    /**
     *
     */
    public function tratarProblema() {

        $se_pide = $this->getSeSolicita();

        foreach($se_pide as $dato_que_se_pide) {
            $this->resolverEcuacion($dato_que_se_pide);
        }

        $this->cargarResultado();
    }


}
