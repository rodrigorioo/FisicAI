<?php

namespace App\Helpers\Traits\Temas;

use App\Helpers\Datos;

trait MRUV {

    public function mruv_velocidad_inicial() {
        $this->setDatos([
            'dato' => 'velocidad_inicial',
            'valor' => 0,
            'unidad' => 'm/s',
        ]);
    }

    public function mruv_velocidad_final($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'velocidad_final',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
    }

    public function mruv_velocidad($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'velocidad',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
        $this->setDatos([
            'dato' => 'velocidad_final',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
    }

    public function mruv_posicion_inicial($datos, $ecuacion) {

    }

    public function mruv_posicion_final($datos, $ecuacion) {

    }

    public function mruv_posicion($datos, $ecuacion) {

    }

    public function mruv_rapidez($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'velocidad',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
        $this->setDatos([
            'dato' => 'velocidad_final',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
        $this->setDatos([
            'dato' => 'rapidez',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s',
        ]);
    }

    public function mruv_tiempo_inicial($datos, $ecuacion) {
        $this->setDatos([
            'dato' => 'tiempo_inicial',
            'valor' => 0,
            'unidad' => 's',
        ]);
    }

    public function mruv_tiempo_final($datos, $ecuacion) {
        $this->setDatos([
            'dato' => 'tiempo_final',
            'valor' => 0,
            'unidad' => 's',
        ]);
    }

    public function mruv_tiempo($datos, $ecuacion) {
        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'tiempo',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 's',
        ]);
    }

    public function mruv_aceleracion($datos, $ecuacion) {

        foreach($datos as $dato) {
            $ecuacion = preg_replace('/\b'.$dato['dato'].'\b/', $dato['valor'], $ecuacion);
        }

        $this->setDatos([
            'dato' => 'aceleracion',
            'valor' => $this->evaluate_math_string($ecuacion),
            'unidad' => 'm/s2',
        ]);
    }
}
