<?php

if (Campo::val('modo') == 'ajax')
    define('BOTON_ENVIAR',"<button onclick=\"fetchJSON('/aula/".Campo::val('oper')."/". Campo::val('id') ."?modo=ajax','formulario');return false\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
else
    define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");

class AulaController
{
    static $id, $oper, $paso, $nombre, $letra, $numero, $planta;

    static function pintar()
    {
        // Inicializar elementos del formulario antes de pintar
        self::inicializacion_campos();

        $contenido = "<h2>". Idioma::lit('aula') ."</h2>";

        switch(Campo::val('oper'))
        {
            case 'cons':
                $contenido = self::cons();
            break;
            case 'modi':
                $contenido = self::modi();
            break;
            case 'baja':
                $contenido = self::baja();
            break;
            case 'alta':
                $contenido = self::alta();
            break;
            default:
                $contenido = self::listado();
                $volver = '';
            break;
        }

        if (Campo::val('modo') != 'ajax')
        {
            $h1cabecera = "<h1>". Idioma::lit('titulo'.Campo::val('oper'))." ". Idioma::lit(Campo::val('seccion')) ."</h1>";
        } else {
            $h1cabecera = '';
        }

        return "
        <div class=\"container contenido\">
        <section class=\"page-section aula\" id=\"aula\">
            {$h1cabecera}
            {$contenido}
        </section>
        </div>
        ";
    }

    static function inicializacion_campos()
    {

        self::$paso     = new Hidden(['nombre' => 'paso']);
        self::$oper      = new Hidden(['nombre' => 'oper']);
        self::$id        = new Hidden(['nombre' => 'id']);
        self::$nombre    = new Text(['nombre' => 'nombre']);
        self::$letra     = new Text(['nombre' => 'letra']);
        self::$numero    = new Text(['nombre' => 'numero']);
        self::$planta    = new Text(['nombre' => 'planta']);

        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$nombre);
        Formulario::cargar_elemento(self::$letra);
        Formulario::cargar_elemento(self::$numero);
        Formulario::cargar_elemento(self::$planta);

    }

    static function formulario($boton_enviar='',$errores=[],$mensaje_exito='',$disabled='')
    {
        Formulario::disabled($disabled);

        Campo::val('paso', '1');

        return Formulario::pintar('/aula/',
            $boton_enviar,
            $errores,
            $mensaje_exito
        );
    }

    static function sincro_form_bbdd($registro)
    {
        Formulario::sincro_form_bbdd($registro);
    }

    static function cons()
    {
        // Lógica para consultar un aula
        $aula = new Aula();

        $registro = $aula->recuperar(Campo::val('id'));

        self::sincro_form_bbdd($registro);

        return self::formulario('',[],''," disabled=\"disabled\" ");
    }

    static function baja()
    {
        // Lógica para dar de baja un aula
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = " disabled=\"disabled\" ";

        if (!Campo::val('paso'))
        {
            $aula = new Aula();
            $resultado = $aula->recuperar(Campo::val('id'));

            self::sincro_form_bbdd($resultado);
        }
        else
        {
            $aula = new Aula();
            $datos_actualizar = [];
            $datos_actualizar['fecha_baja'] = date('Y-m-d');

            $aula->actualizar($datos_actualizar, Campo::val('id'));

            $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

            $boton_enviar = '';
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function modi()
    {
        // Lógica para modificar un aula
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = '';

        if (!Campo::val('paso'))
        {
            // mostrar formulario con datos
            $aula = new Aula();
            $registro = $aula->recuperar(Campo::val('id'));

            self::sincro_form_bbdd($registro);
        }
        else
        {
            // procesar modificación
            $aula = new Aula();

            $datos_actualizar = [];
            $datos_actualizar['nombre'] = Campo::val('nombre');
            $datos_actualizar['letra'] = Campo::val('letra');
            $datos_actualizar['numero'] = Campo::val('numero');
            $datos_actualizar['planta'] = Campo::val('planta');

            $aula->actualizar($datos_actualizar, Campo::val('id'));

            $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';
            
            $disabled = " disabled=\"disabled\" ";
            $boton_enviar = '';
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function alta()
    {
        // Lógica para dar de alta un aula
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito = '';
        $disabled = '';

        if (!Campo::val('paso')) 
        {
            //mostrar formulario vacío para alta pq no hay que hacer nada especial

        }
        else
        {
            $numero_errores = Formulario::validacion();

            if (!$numero_errores)
            {
                $aula = new Aula();

                $datos_insertar = [];
                $datos_insertar['nombre'] = Campo::val('nombre');
                $datos_insertar['letra'] = Campo::val('letra');
                $datos_insertar['numero'] = Campo::val('numero');
                $datos_insertar['planta'] = Campo::val('planta');
                $datos_insertar['fecha_alta'] = date('Y-m-d');

                $aula->insertar($datos_insertar);

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled = " disabled=\"disabled\" ";
                $boton_enviar = '';
            }
        }

        return self::formulario($boton_enviar, $errores, $mensaje_exito, $disabled);
    }

    static function listado()
    {
        // Lógica para listar aulas
        if (is_numeric(Campo::val('pagina')))
        {
            $pagina = (int)Campo::val('pagina');
            $offset = ( ($pagina - 1) * LISTADO_TOTAL_POR_PAGINA );
        }
        else{
            $pagina = 1;
            $offset = 0;
        }

        $aula = new Aula();

        $datos_consulta = $aula->get_rows([
            'where' => 'fecha_baja IS NULL',
            'limit' => LISTADO_TOTAL_POR_PAGINA,
            'offset' => $offset
        ]);
            
        $listado_aulas = '';
        $total_registros = 0;

        foreach($datos_consulta as $indice => $registro)
        {

            $botonera = "
                <a onclick=\"fetchJSON('/aula/cons/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-secondary\"><i class=\"bi bi-search\"></i></a>
                <a onclick=\"fetchJSON('/aula/modi/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-pencil-square\"></i></a>
                <a onclick=\"fetchJSON('/aula/baja/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-danger\"><i class=\"bi bi-trash\"></i></a>
            ";

            $listado_aulas .= "
                <tr>
                    <td>{$registro['nombre']}</td>
                    <td>{$registro['letra']}</td>
                    <td>{$registro['numero']}</td>
                    <td>{$registro['planta']}</td>
                    <td>
                        <div class=\"btn-group\" role=\"group\" aria-label=\"Botonera\">
                            {$botonera}
                        </div>
                    </td>
                </tr>
            ";

            $total_registros++;
        }

        $barra_navegacion = Template::navegacion($total_registros, $pagina);

    
        return "
            <div class=\"table-responsive\">
                <table class=\"table table-striped table-hover\">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Letra</th>
                            <th>Número</th>
                            <th>Planta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$listado_aulas}
                    </tbody>
                </table>
            </div>
            {$barra_navegacion}
            <a onclick=\"fetchJSON('/aula/alta?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-file-earmark-plus\"></i> Alta aula</a>
            ";
    }
}