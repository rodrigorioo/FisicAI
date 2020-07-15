<?php

namespace App\Helpers;

use App\Helpers\Traits\Ecuacion;
use App\Helpers\Traits\Problema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Fisica {

    use Problema, Ecuacion;

    private $se_solicita = [];
    private $datos = [];
    private $ecuaciones = [
        'mru' => [
            'distancia' => 'posicion_inicial + (velocidad * tiempo)',
            'velocidad' => '(distancia - posicion_inicial) / tiempo',
            'tiempo' => '(distancia - posicion_inicial) / velocidad',
            'posicion_inicial' => '0',
            'hora' => 'tiempo',
            'rapidez' => '(distancia - posicion_inicial) / tiempo',
        ],
        'mruv' => [
            'velocidad_inicial' => '0',
            // 'velocidad_final' => 'velocidad_inicial + (aceleracion * tiempo)',
            'velocidad' => 'velocidad_inicial + (aceleracion * tiempo)',

            'rapidez' => 'velocidad_inicial + (aceleracion * tiempo)',

            'tiempo_inicial' => '0',
            // 'tiempo_final' => '0',
            // 'tiempo' => '(velocidad_final - velocidad_inicial) / aceleracion',
            'tiempo' => '(velocidad - velocidad_inicial) / aceleracion',

            'posicion_final' => 'posicion_inicial + (velocidad_inicial * tiempo) + ( (aceleracion * (tiempo * tiempo)) / 2)',
            'posicion_inicial' => '0',
            'posicion' => 'posicion_inicial + (velocidad_inicial * tiempo) + ( (aceleracion * (tiempo * tiempo)) / 2)',

            // 'aceleracion' => '(velocidad_final - velocidad_inicial) / (tiempo_final - tiempo_inicial)',
            'aceleracion' => '(velocidad - velocidad_inicial) / (tiempo - tiempo_inicial)',

            // 'posicion_inicial + (velocidad_inicial * tiempo) + ( (aceleracion * (tiempo * tiempo)) / 2)' => 'velocidad_inicial + (aceleracion * tiempo)',

        ],
    ];
    private $tema = '';
    private $resultado = [];

    public function __construct($tema = 'mru') {
        $this->setTema($tema);
    }

    /**
     * @param $entities
     */
    public function cargarSeSolicita($entities) {

        foreach($entities as $iEntity => $entity) {
            if($entity->name == 'se_solicita') {
                $this->setSeSolicita(Cadena::removeAccents($entities[$iEntity+1]->value));
            }
        }
    }

    /**
     * @param $entities
     * @param $mensaje
     * CARGAMOS LOS DATOS DEL PROBLEMA SEGÚN EL TIPO DE ENTITY
     */
    public function cargarDatos($entities, $mensaje) {

        foreach($entities as $iEntitie => $entity) {

            switch($entity->name) {
                case 'valor':
                    $this->setDatos([
                        'dato' => Datos::tipoDeDato(Datos::unidad($entity->value)),
                        'valor' => Datos::valor($entity->value),
                        'unidad' => Datos::unidad($entity->value),
                    ]);
                    break;

                case 'wit$distance':
                    $this->setDatos([
                        'dato' => 'distancia',
                        'valor' => $entity->value,
                        'unidad' => Datos::traducirUnidad($entity->unit),
                    ]);
                    break;

                case 'wit$duration':
                    $this->setDatos([
                        'dato' => 'tiempo',
                        'valor' => $entity->value,
                        'unidad' => Datos::traducirUnidad($entity->unit),
                    ]);
                    break;

                case 'wit$datetime':

                    // WIT DETECTA "EN UN SEGUNDO" COMO DATE TIME
                    // PARA SOLUCIONAR ESTO REMOVEMOS LAS PALABRAS ANTECESORAS A LO QUE MARCA EL TIEMPO (EN ESTE CASO, LA PALABRA "EN")
                    // Y MANDAMOS A ANALIZAR NUEVAMENTE EL MENSAJE PARA PODER EXTRAER EL TIEMPO
                    if($entity->grain == 'second') {
                        $removerPalabras = [
                            'en',
                        ];

                        $mensajeString = preg_replace('/(en)/', '', $entity->body);

                        $wit = new Wit();
                        $responseMensaje = $wit->mensaje($mensajeString);
                        if(!$responseMensaje['error']) {

                            $mensaje = $responseMensaje['data'];
                            $entities = Wit::ordenarEntities(Wit::agruparEntities($mensaje->entities));
                            $entities = Wit::limpiarEntitiesNumber($entities);
                            $this->cargarDatos($entities, $mensajeString);
                        }

                    } else {

                        $this->setDatos([
                            'dato' => 'fecha',
                            'valor' => Carbon::parse($entity->value),
                            'unidad' => '',
                        ]);
                    }

                    break;
            }
        }

        $this->evaluarTema();
    }

    public function evaluarTema() {
        $ecuaciones = $this->getEcuaciones();
        $temas = array_keys($ecuaciones);

        $datosProblema = [];

        $datos = $this->getDatos();
        foreach($datos as $dato) {
            $datosProblema[] = $dato['dato'];
        }

        $se_solicita = $this->getSeSolicita();
        foreach($se_solicita as $dato_que_se_solicita) {
            $datosProblema[] = $dato_que_se_solicita;
        }

        $cantidadDatosPorTema = [];
        foreach($temas as $tema) {

            $cantidadDatosPorTema[$tema] = 0;
            $datosTema = array_keys($ecuaciones[$tema]);

            foreach($datosProblema as $datoProblema) {
                if(in_array($datoProblema, $datosTema)) {
                    $cantidadDatosPorTema[$tema]++;
                }
            }
        }

        $tema = array_keys($cantidadDatosPorTema, max($cantidadDatosPorTema));

        $this->setTema($tema[0]);
    }

    /**
     * @return array
     */
    public function getSeSolicita(): array
    {
        return $this->se_solicita;
    }

    /**
     * @param string $se_solicita
     */
    public function setSeSolicita($se_solicita): void
    {
        if($se_solicita == 'metros') $se_solicita = 'distancia';

        $this->se_solicita[] = $se_solicita;
    }

    /**
     * @return array
     */
    public function getEcuaciones(): array
    {
        return $this->ecuaciones;
    }

    /**
     * @param array $ecuaciones
     */
    public function setEcuaciones(array $ecuaciones): void
    {
        $this->ecuaciones = $ecuaciones;
    }

    /**
     * @return array
     */
    public function getDatos(): array
    {
        return $this->datos;
    }

    /**
     * @param array $datos
     */
    public function setDatos(array $datos): void
    {
        $this->datos[] = $datos;
    }

    /**
     * @return string
     */
    public function getTema(): string
    {
        return $this->tema;
    }

    /**
     * @param string $tema
     */
    public function setTema(string $tema): void
    {
        $this->tema = $tema;
    }

    /**
     * @return array
     */
    public function getResultado(): array
    {
        return $this->resultado;
    }

    /**
     * @param array $resultado
     */
    public function setResultado(array $resultado): void
    {
        $this->resultado = array_merge($this->resultado, $resultado);
    }
}
