<?php

namespace App\Helpers\Traits\Temas;

use App\Helpers\Datos;

trait MRU {

    public function mru_distancia($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'distancia',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm',
        ]);
    }

    public function mru_velocidad ($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'velocidad',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
    }

    public function mru_tiempo($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'tiempo',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 's',
        ]);
    }

    public function mru_posicion_inicial($datos, $ecuacion) {

        $this->setDatos([
            'dato' => 'posicion_inicial',
            'valor' => 0,
            'unidad' => 'm',
        ]);
    }

    public function mru_hora ($datos, $ecuacion) {

        $fecha = null;
        $tiempo = 0;
        foreach($datos as $dato) {
            if($dato['dato'] == 'fecha') {
                $fecha = $dato['valor'];
            }

            if($dato['dato'] == 'tiempo') {
                $tiempo = $dato['valor'];
            }
        }

        $fechaNueva = $fecha->copy()->addSeconds($tiempo);
        $diferencia = $fecha->diff($fechaNueva);

        $this->setDatos([
            'dato' => 'hora',
            'valor' => $diferencia->format('Dias: %D - Hora: %H:%I:%S'),
            'unidad' => '',
        ]);
    }

    public function mru_rapidez($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'rapidez',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
        $this->setDatos([
            'dato' => 'velocidad',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
    }

}
