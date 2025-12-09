<?php

    class IEmail extends Elemento
    {

        function __construct($datos=[])
        {
            $datos['type'] = 'email'; //Obliga a que el navegador valide el formato de email

            parent::__construct($datos); //Llama al constructor de la clase padre para configurar el resto de propiedades

        }

        function validar()
        {
            $valor = Campo::val($this->nombre);

            if (empty($valor)) { //Campo vacío = error, es decir que si el usuario no lo introduce, muestra un mensaje de "valor obligatorio"

                $this->error = true;
                $this->literal_error = "<span class='error'>" . Idioma::lit('valor_obligatorio') . "</span>";
                Formulario::$numero_errores++; //Incrementa el contador de errores del formulario

            } elseif (!filter_var($valor, FILTER_VALIDATE_EMAIL)) { //Si no tiene correcto el formato email error, "email inválido"
                $this->error = true;
                $this->literal_error = "<span class='error'>" . Idioma::lit('email_invalido') . "</span>";
                Formulario::$numero_errores++;
            }
        }
    }


    /*
    Se pinta así en el formulario:
        $form->add(new IEmail(['nombre'=>'email', 'label'=>'Correo Electrónico']));

    Genera:
        <div class="mb-3">
            <label for="idcorreo" class="form-label">Correo</label>
            <input name="correo" type="email" class="form-control" id="idcorreo" placeholder="Introduzca su correo">
        </div>

    Si hay error: 
        <span class="error">Email inválido</span>
    */