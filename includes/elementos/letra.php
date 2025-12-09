<?php
class Letra extends Elemento
{
    function __construct($datos=[])
    {
        $datos['type'] = 'text'; 
        
        parent::__construct($datos);
    }


    
    function validar()
    {
        $valor = Campo::val($this->nombre);

        $patron_letra = "/^[DI]$/"; 
        //$patron_letra = "/^(97[89][-]?)?\d{1,5}[-]?\d{1,7}[-]?\d{1,7}[-]?(\d|X)$/"; 

        if (empty($valor)) {
            $this->error = true;
            $this->literal_error = "<span class='error'>" . Idioma::lit('valor_obligatorio') . "</span>";
            Formulario::$numero_errores++;
        } elseif (!preg_match($patron_letra, $valor)) {
            $this->error = true;
            $this->literal_error = "<span class='error'>" . Idioma::lit('letra_invalido') . "</span>";
            Formulario::$numero_errores++;
        }
    }
}


//self::$letra     = new Letra(['nombre' => 'letra']); //D o I 