<?php

    class Number extends Elemento
    {

        function __construct($datos=[])
        {
            $datos['type'] = 'number';

            parent::__construct($datos);

        }


 

        function validar()
        {
            $valor = Campo::val($this->nombre);
            if (empty($valor)) {
                $this->error = true;
                $this->literal_error = "<span class='error'>" . Idioma::lit('valor_obligatorio') . "</span>";
                Formulario::$numero_errores++;
            } elseif (!filter_var($valor)) {
                $this->error = true;
                $this->literal_error = "<span class='error'>" . Idioma::lit('numero_invalido') . "</span>";
                Formulario::$numero_errores++;
            }
        }
    }