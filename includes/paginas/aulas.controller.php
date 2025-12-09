<?php


class AulasController
{
    static $oper, $id, $paso, $numero, $nombre, $letra, $planta;

    static function pintar()
    {
        $contenido = '';

        self::inicializacion_campos();

        switch (Campo::val('oper')) {
            case 'cons':
                $contenido = self::cons();
                break;
            default:
                $contenido = self::listado();
                $volver = '';
                break;
        }


        if (Campo::val('modo') != 'ajax') {
            $h1cabecera = "<h1>" . Idioma::lit('titulo' . Campo::val('oper')) . " " . Idioma::lit(Campo::val('seccion')) . "</h1>";
        }

        return "
        <div class=\"container contenido\">
        <section class=\"page-section aulas\" id=\"aulas\">
            {$h1cabecera}
            {$contenido}
        </section>
        </div>
        
        ";
    }

    static function inicializacion_campos()
    {
        self::$paso      = new Hidden(['nombre' => 'paso']);
        self::$oper      = new Hidden(['nombre' => 'oper']);
        self::$id        = new Hidden(['nombre' => 'id']);
        self::$numero    = new Text(['nombre' => 'numero']);
        self::$nombre    = new Text(['nombre' => 'nombre']);
        self::$letra     = new Text(['nombre' => 'letra']);
        self::$planta    = new Text(['nombre' => 'planta']);

        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$numero);
        Formulario::cargar_elemento(self::$nombre);
        Formulario::cargar_elemento(self::$letra);
        Formulario::cargar_elemento(self::$planta);
    }

    static function formulario($boton_enviar = '', $errores = [], $mensaje_exito = '', $disabled = '')
    {
        Formulario::disabled($disabled);

        Campo::val('paso', '1');

        return Formulario::pintar('/aulas/', $boton_enviar, $mensaje_exito);
    }

    static function sincro_form_bbdd($registro)
    {
        Formulario::sincro_form_bbdd($registro);
    }

    static function cons()
    {
        $horario = new Aulas();
        $registro = $horario->recuperar(Campo::val('id'));

        self::sincro_form_bbdd($registro);

        return self::formulario('', [], '', " disabled=\"disabled\" ");
    }

    static function baja()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = " disabled=\"disabled\" ";
        if (!Campo::val('paso')) {
            $aulas = new Aulas();
            $registro = $aulas->recuperar(Campo::val('id'));

            self::sincro_form_bbdd($registro);
        } else {

            $aulas = new Aulas();

            $datos_actualizar = [];
            $datos_actualizar['fecha_baja'] = date('Ymd');

            $aulas->actualizar($datos_actualizar, Campo::val('id'));

            $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

            $boton_enviar = '';
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function modi()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = '';
        if (!Campo::val('paso')) {
            $aulas = new Aulas();
            $registro = $aulas->recuperar(Campo::val('id'));


            self::sincro_form_bbdd($registro);
        } else {

            $numero_errores = Formulario::validacion();

            if (!$numero_errores) {
                $aulas = new Aulas();

                $datos_actualizar = [];
                $datos_actualizar['numero']      = Campo::val('numero');
                $datos_actualizar['nombre']    = Campo::val('nombre');
                $datos_actualizar['letra'] = Campo::val('letra');
                $datos_actualizar['planta']     = Campo::val('planta');

                $aulas->actualizar($datos_actualizar, Campo::val('id'));

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled = " disabled=\"disabled\" ";
                $boton_enviar = '';
            }
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function alta()
    {

        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = '';
        if (Campo::val('paso')) {

            $numero_errores = Formulario::validacion();

            if (!$numero_errores) {
                $nuevo_aula = [];
                $nuevo_aula['numero']      = Campo::val('numero');
                $nuevo_aula['nombre']    = Campo::val('nombre');
                $nuevo_aula['letra'] = Campo::val('letra');
                $nuevo_aula['planta']     = Campo::val('planta');

                $aulas = new Aulas();
                $aulas->insertar($nuevo_aula);

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled = " disabled=\"disabled\" ";
                $boton_enviar = '';
            }
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function listado()
    {
        $filtro_completo = Campo::val('modulo') ?? '';

        $opciones_html = '';
        $nombres_disponibles = [];

        $query_nombres = new Query(
            "SELECT numero, nombre, letra, planta
        FROM aula
        ORDER BY numero, nombre, letra, planta"
        );

        if ($query_nombres) {
            while ($nombre = $query_nombres->recuperar()) {
                $value = $nombre['numero'] . ' ' . $nombre['nombre'] . ' ' . $nombre['letra'];
                $nombres_disponibles[] = $value;
                $selected = ($filtro_completo == $value) ? 'selected' : '';
                $opciones_html .= "<option value=\"$value\" $selected>$value</option>";
            }
        }


        if (empty($filtro_completo) || !in_array($filtro_completo, $nombres_disponibles)) {
            $filtro_completo = $nombres_disponibles[0] ?? '';
        }


        $listado_aulas = '';
        $total_registros = 0;
        foreach ($nombres_disponibles as $indice => $registro) {

            $botonera = "
                <a onclick=\"fetchJSON('/aulas/cons/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-secondary\"><i class=\"bi bi-search\"></i></a>
                <a onclick=\"fetchJSON('/aulas/modi/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-pencil-square\"></i></a>
                <a onclick=\"fetchJSON('/aulas/baja/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-danger\"><i class=\"bi bi-trash\"></i></a>
                

            ";

            $listado_aulas .= "
                <tr>
                    <th style=\"white-space:nowrap\" scope=\"row\">{$botonera}</th>
                    <td>{$registro['numero']}</td>
                    <td>{$registro['nombre']}</td>
                    <td>{$registro['letra']}</td>
                    <td>{$registro['planta']}</td>
                    <td>" . fmto_fecha($registro['fecha_alta']) . "</td>
                    <td>" . fmto_fecha($registro['fecha_baja']) . "</td>
                </tr>
            ";

            $total_registros++;
        }

        $barra_navegacion = Template::navegacion($total_registros, $pagina);

        return "
            <table class=\"table\">
            <thead>
                <tr>
                <th scope=\"col\">#</th>
                <th scope=\"col\">Numero</th>
                <th scope=\"col\">Nombre</th>
                <th scope=\"col\">Letra</th>
                <th scope=\"col\">Planta</th>
                <th scope=\"col\">Fecha Alta</th>
                <th scope=\"col\">Fecha Baja</th>
                </tr>
            </thead>
            <tbody>
            {$listado_aulas}
            </tbody>
            </table>
            {$barra_navegacion}

            <a onclick=\"fetchJSON('/aulas/alta/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-file-earmark-plus\"></i> Alta Aula</a>
            ";
    }
}
