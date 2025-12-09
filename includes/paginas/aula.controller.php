<?php


//define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");

if (Campo::val('modo') == 'ajax'){
    define('BOTON_ENVIAR',"<button onclick=\"fetchJSON('/aulas/".Campo::val('oper')."/". Campo::val('id') ."?modo=ajax','formulario');
    return false\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
}else{
    define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
}

class AulaController
{

    static $nombre,$numero,$oper,$id,$paso,$letra,$planta;



    static function pintar()
    {
        $contenido = '';




        self::inicializacion_campos();

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
        self::$nombre    = new Text(['nombre' => 'nombre']);
        self::$numero    = new Number(['nombre' => 'numero']); //Solo números
        self::$letra     = new Letra(['nombre' => 'letra']); //D o I 
        self::$planta    = new Planta(['nombre' => 'planta']); //Primera, Segunda o Tercera 

        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$nombre);
        Formulario::cargar_elemento(self::$numero);
        Formulario::cargar_elemento(self::$letra);
        Formulario::cargar_elemento(self::$planta);

    }


    static function formulario($boton_enviar='',$errores=[],$mensaje_exito='',$disabled='')
    {
        Formulario::disabled($disabled);

        Campo::val('paso','1');

        return Formulario::pintar('/aulas/',$boton_enviar,$mensaje_exito);

    }

    static function sincro_form_bbdd($registro)
    {
        Formulario::sincro_form_bbdd($registro);
    }


    static function cons()
    {
        $aula = new Aulas();
        $registro = $aula->recuperar(Campo::val('id'));

        self::sincro_form_bbdd($registro);

        return self::formulario('',[],''," disabled=\"disabled\" ");
    }

    static function baja()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled =" disabled=\"disabled\" ";
        if(!Campo::val('paso'))
        {
            $aula = new Aula();
            $registro = $aula->recuperar(Campo::val('id'));

            self::sincro_form_bbdd($registro);

        }
        else
        {

            $aula = new Aula();

            $datos_actualizar = [];
            //$datos_actualizar['fecha_baja'] = date('Ymd');

            $aula->actualizar($datos_actualizar,Campo::val('id'));

            $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

            $boton_enviar = '';
        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }

    static function modi()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled='';
        if(!Campo::val('paso'))
        {
            $aula = new Aula();
            $registro = $aula->recuperar(Campo::val('id'));


            self::sincro_form_bbdd($registro);

        }
        else
        {


            
            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $aula = new Aula();

                $datos_actualizar = [];
                $datos_actualizar['nombre']    = Campo::val('nombre');
                $datos_actualizar['letra']     = Campo::val('letra');
                $datos_actualizar['numero']    = Campo::val('numero');
                $datos_actualizar['planta']    = Campo::val('planta');

                $aula->actualizar($datos_actualizar,Campo::val('id'));

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled =" disabled=\"disabled\" ";
                $boton_enviar = '';
            }


        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }

    static function alta()
    {

        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled='';
        if(Campo::val('paso'))
        {

            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $nuevo_aula = [];
                $nuevo_aula['nombre']    = Campo::val('nombre');
                $nuevo_aula['letra']     = Campo::val('letra');
                $nuevo_aula['numero']    = Campo::val('numero');
                $nuevo_aula['planta']    = Campo::val('planta');

                $aula = new Aula();
                $aula->insertar($nuevo_aula);

              
                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled =" disabled=\"disabled\" ";
                $boton_enviar = '';
            }


        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);

    }


    static function listado()
    {
        if(is_numeric(Campo::val('pagina')))
        {
            $pagina = Campo::val('pagina');
            $offset = LISTADO_TOTAL_POR_PAGINA * $pagina;
        }
        else{
            $offset = '0';
        }
        $pagina++;


        // Obtener la letra seleccionado (si existe)
        $letra_filtro = Campo::val('letra_filtro');
        
        // Formulario bootstrap para filtrar
        //Quito: <input type='hidden' name='oper' value=''> para que se vea más limpia 
        $form_filtro = "
        <form method='GET' action='/letras/' class='mb-3'>
                <div class='input-group'>
                    <select name='letra_filtro' class='form-select'>
                        <option value=''>Opciones</option>
                        <option value='D' ".($letra_filtro=='D'?'selected':'').">D</option>
                        <option value='I' ".($letra_filtro=='I'?'selected':'').">I</option>
                    </select>
                    <button class='btn btn-primary' type='submit'>Filtrar</button>
                </div>
            </form>
        ";



        $aula = new Aula();

        $datos_consulta = $aula->get_rows([
            // 'wheremayor' => [
             //   'fecha_baja' => date('Ymd')
           // ]
            'limit'  => LISTADO_TOTAL_POR_PAGINA
            ,'offset' => $offset
        ]);

        
        if ($letra_filtro) {
            $condiciones['where'] = ['letra' => $letra_filtro];
        }


        $listado_aulas= '';
        $total_registros = 0;
        foreach($datos_consulta as $indice => $registro)
        {

            $botonera = "
                <a onclick=\"fetchJSON('/aulas/cons/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-secondary\"><i class=\"bi bi-search\"></i></a>
                <a onclick=\"fetchJSON('/aulas/modi/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-pencil-square\"></i></a>
                <a onclick=\"fetchJSON('/aulas/baja/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-danger\"><i class=\"bi bi-trash\"></i></a>
            ";


            $listado_aulas .= "
                <tr>
                    <th style=\"white-space:nowrap\" scope=\"row\">{$botonera}</th>
                    <td>{$registro['nombre']}</td>
                    <td>{$registro['letra']}</td>
                    <td>{$registro['numero']}</td>
                    <td>{$registro['planta']}</td>
                </tr>
            ";


            
            // Formulario bootstrap para filtrar
            //Quito: <input type='hidden' name='oper' value=''> para que se vea más limpia 
            $form_filtro = "
    
            ";
            
            $total_registros++;
        }


        $barra_navegacion = Template::navegacion_aulas($total_registros,$pagina);

        


        return "
        {$form_filtro}
            <table class=\"table\">
            <thead>
                <tr>
                <th scope=\"col\">#</th>
                <th scope=\"col\">Nombre</th>
                <th scope=\"col\">Letra</th>
                <th scope=\"col\">Número</th>
                <th scope=\"col\">Planta</th>
                </tr>
            </thead>
            <tbody>
            {$listado_aulas}
            </tbody>
            </table>
            {$barra_navegacion}
            <a onclick=\"fetchJSON('/aulas/alta/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-file-earmark-plus\"></i> Alta aula</a>
            ";

    }



}