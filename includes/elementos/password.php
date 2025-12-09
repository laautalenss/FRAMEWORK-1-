<?php

    class Password extends Elemento
    {

        function __construct($datos=[])
        {
            $datos['type'] = 'password'; // Fuerza el tipo password

            parent::__construct($datos); // Llama al constructor de la clase padre para configurar el resto de propiedades

        }

        function validar()
        {
            $valor = Campo::val($this->nombre); // Obtiene el valor del campo del formulario enviado por POST

            if (empty($valor) || strlen($valor) <= 5) { //Si es menor o igual a 5 caracteres error (mínimo 6)

                $this->error = true;
                $this->literal_error = "<span class='error'>La contraseña debe tener al menos 6 caracteres</span>";
                Formulario::$numero_errores++;
            }
        }
    }

    /*
    Se pinta así en el formulario:
        $form->add(new password(['nombre'=>'password', 'label'=>'password']));

    Genera:
        <div class="mb-3">
            <label for="idpassword" class="form-label">Contraseña</label>
            <input name="password" type="password" class="form-control" id="idpassword" placeholder="Introduzca su contraseña">
            <span class='error'>La contraseña debe tener al menos 6 caracteres</span>
        </div>

    Si hay error: 
        <span class="error">ISBN inválido</span>
    */