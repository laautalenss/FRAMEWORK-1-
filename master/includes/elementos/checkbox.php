<?php

class Checkbox extends Elemento
{
    
    function __construct($datos = [])
    {
        parent::__construct($datos);
        $this->type = 'checkbox';
    }

    function pintar()
    {
        $checked = Campo::val($this->nombre) ? 'checked' : '';
        $disabled = $this->disabled ? 'disabled' : '';

        return "
        <div class='mb-3 form-check'>
            <input type='checkbox' class='form-check-input' id='id{$this->nombre}' name='{$this->nombre}' $checked $disabled>
            <label class='form-check-label' for='id{$this->nombre}'>" . Idioma::lit($this->nombre) . "</label>
        </div>";
    }
}
