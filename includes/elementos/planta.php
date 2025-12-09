<?php
class Planta extends Elemento
{
    function __construct($datos=[])
    {
        $datos['type'] = 'text'; // o 'number' si quieres solo dígitos
        
        parent::__construct($datos);
    }


    
    function validar()
    {
        $valor = Campo::val($this->nombre);

        $patron_planta = "/^[PST]$/"; 
        //$patron_letra = "/^(97[89][-]?)?\d{1,5}[-]?\d{1,7}[-]?\d{1,7}[-]?(\d|X)$/"; 

        if (empty($valor)) {
            $this->error = true;
            $this->literal_error = "<span class='error'>" . Idioma::lit('valor_obligatorio') . "</span>";
            Formulario::$numero_errores++;
        } elseif (!preg_match($patron_planta, $valor)) {
            $this->error = true;
            $this->literal_error = "<span class='error'>" . Idioma::lit('planta_invalido') . "</span>";
            Formulario::$numero_errores++;
        }
    }
}


//self::$planta    = new Planta(['nombre' => 'planta']); //Primera [P], Segunda [S] o Tercera [T]
