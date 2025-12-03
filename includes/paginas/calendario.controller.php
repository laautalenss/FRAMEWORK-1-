<?php

class CalendarioController
{

    static $hora, $lunes, $martes, $miercoles, $jueves, $viernes, $modulo, $id, $oper, $paso;

    public static function pintar()
    {
        $contenido = '';
        $h1cabecera = '';
        self::inicializacion_campos();

        switch (Campo::val('oper')){
            case 'cons':
        }

    }

    static function inicializacion_campos()
    {
        self::$hora       = Campo::val('hora', '');
        self::$lunes     = Campo::val('lunes', '');
        self::$martes    = Campo::val('martes', '');
        self::$miercoles = Campo::val('miercoles', '');
        self::$jueves    = Campo::val('jueves', '');
        self::$viernes   = Campo::val('viernes', '');
        self::$modulo    = new Hidden(['nombre' => 'modulo']);
        self::$id        = new Hidden(['nombre' => 'id']);
        self::$oper      = new Hidden(['nombre' => 'oper']);
        self::$paso      = new Hidden(['nombre' => 'paso']);
    }

}
