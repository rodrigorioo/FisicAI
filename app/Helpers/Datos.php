<?php

namespace App\Helpers;

class Datos {
    /**
     * @param $valor
     * @return string
     */
    public static function tipoDeDato($valor) {

        $valor = strtolower($valor);

        switch($valor) {
            case 'm/s2':
            case 'km/s2':
            case 'km/h2': $devolver = 'aceleracion'; break;

            case 'm/s':
            case 'km/s':
            case 'km/h': $devolver = 'velocidad'; break;
        }

        return $devolver;
    }

    /**
     * @param $valor
     * @return string
     * RETORNA LA UNIDAD QUE TIENE UN VALOR (EJ: 10 m/s => m/s)
     */
    public static function unidad($valor) {
        return strtolower(str_replace(' ', '', preg_replace('/[\d+(?:\,\d{1,2})]/A', '', $valor)));
    }

    /**
     * @param $unidad
     * @return string
     * TRADUCIMOS LA UNDIAD QUE NOS TRAE WIT A UNA QUE PODAMOS TRABAJAR
     */
    public static function traducirUnidad($unidad) {

        $devolver = '';

        switch($unidad) {
            case 'kilometre': $devolver = 'km'; break;
            case 'minute': $devolver = 'min'; break;
            case 'metre': $devolver = 'm'; break;
            case 'second': $devolver = 's'; break;
        }

        return $devolver;
    }

    public static function valor($valor) {
        // return preg_replace('/[\D+(?:\,\D{1,2})]/', '', str_replace(',', '.', $valor));
        $retornar = [];
        preg_match('/([0-9.]+(\.[0-9]+)*)/', str_replace(',', '.', $valor), $retornar);

        return $retornar[0];
    }

    /**
     * @param $datos
     * @return array
     * PASAMOS LAS UNIDADES DE TODOS LOS DATOS A metros Y segundos PARA TRABAJARLOS MÁS FÁCIL
     */
    public static function normalizarUnidades($datos) {

        $devolverDatos = [];

        foreach($datos as $dato) {

            switch($dato['dato']) {

                case 'distancia':
                    if($dato['unidad'] == 'km') {
                        $dato['valor'] = $dato['valor'] * 1000;
                    }

                    $dato['unidad'] = 'm';
                    break;

                case 'velocidad':
                    if($dato['unidad'] == 'km/h') {
                        $dato['valor'] = $dato['valor'] * (5 / 18);

                    } else if($dato['unidad'] == 'km/s') {
                        $dato['valor'] = $dato['valor'] * 1000;
                    }

                    $dato['unidad'] = 'm/s';
                    break;

                case 'tiempo':
                    if($dato['unidad'] == 'min') {
                        $dato['valor'] = $dato['valor'] * 60;
                    }

                    $dato['unidad'] = 's';
                    break;

                case 'aceleracion':
                    if($dato['unidad'] == 'km/h2') {
                        $dato['valor'] = ($dato['valor'] * 1000 * ( (1 / 3600) * (1 / 3600) ));
                    }

                    $dato['unidad'] = 'm/s2';
                    break;
            }

            $devolverDatos[] = $dato;
        }

        return $devolverDatos;
    }
}
