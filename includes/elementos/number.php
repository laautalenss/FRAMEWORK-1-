<?php

class Number Extends Elemento
{
    function __construct($datos=[])
        {
            $datos['type'] = 'number';

            parent::__construct($datos);

        }

        function validar()
        {
            if(empty(Campo::val($this->nombre)))
            {
                $this->error = True;
                Formulario::$numero_errores++;
            }
        }
}