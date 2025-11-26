<?php


//define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
if (Campo::val('modo') == 'ajax'){
    define('BOTON_ENVIAR',"<button onclick=\"fetchJSON('/libros/".Campo::val('oper')."/". Campo::val('id') ."?modo=ajax','formulario');
    return false\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
}else{
    define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
}

class LibroController
{

    static $id,$titulo,$autor,$genero,$isbn,$fecha_publicacion,$paso,$oper;


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
        <section class=\"page-section libros\" id=\"libros\">
            {$h1cabecera}
            {$contenido}
        </section>
        </div>
        
        ";


    }

    static function inicializacion_campos()
    {
        self::$paso              = new Hidden(['nombre' => 'paso']);
        self::$oper              = new Hidden(['nombre' => 'oper']);
        self::$id                = new Hidden(['nombre' => 'id']);
        self::$titulo            = new Text(['nombre' => 'titulo']);
        self::$autor             = new Text(['nombre' => 'autor']);
        self::$genero            = new Text(['nombre' => 'genero']);
        self::$isbn              = new ISBN(['nombre' => 'isbn']);
        self::$fecha_publicacion = new Text(['nombre' => 'fecha_publicacion']);

        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$titulo);
        Formulario::cargar_elemento(self::$autor);
        Formulario::cargar_elemento(self::$genero);
        Formulario::cargar_elemento(self::$isbn);
        Formulario::cargar_elemento(self::$fecha_publicacion);

    }


    static function formulario($boton_enviar='',$errores=[],$mensaje_exito='',$disabled='')
    {
        Formulario::disabled($disabled);

        Campo::val('paso','1');

        return Formulario::pintar('/libros/',$boton_enviar,$mensaje_exito);

    }

    static function sincro_form_bbdd($registro)
    {
        Formulario::sincro_form_bbdd($registro);
    }


    static function cons()
    {
        $libro = new Libro();
        $registro = $libro->recuperar(Campo::val('id'));

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
            $libro = new Libro();
            $registro = $libro->recuperar(Campo::val('id'));

            self::sincro_form_bbdd($registro);

        }
        else
        {

            $libro = new Libro();

            $datos_actualizar = [];
            $datos_actualizar['fecha_baja'] = date('Ymd');

            $libro->actualizar($datos_actualizar,Campo::val('id'));

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
            $libro = new Libro();
            $registro = $libro->recuperar(Campo::val('id'));


            self::sincro_form_bbdd($registro);

        }
        else
        {

            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $libro = new Libro();

                $datos_actualizar = [];
                $datos_actualizar['titulo']      = Campo::val('titulo');
                $datos_actualizar['autor']    = Campo::val('autor');
                $datos_actualizar['genero'] = Campo::val('genero');
                $datos_actualizar['isbn']     = Campo::val('isbn');
                $datos_actualizar['fecha_publicacion']  = Campo::val('fecha_publicacion');

                $libro->actualizar($datos_actualizar,Campo::val('id'));

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';

                $disabled =" disabled=\"disabled\" ";
                $boton_enviar = '';
            }


        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }

    static function alta()
    {

        /*
            ,nick          VARCHAR(255) NOT NULL
            ,nombre        VARCHAR(255)
            ,apellidos     VARCHAR(255)
            ,email         VARCHAR(255)
            ,password      VARCHAR(255)
        */
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled='';
        if(Campo::val('paso'))
        {

            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $nuevo_libro = [];
                $nuevo_libro['titulo']              = Campo::val('titulo');
                $nuevo_libro['autor']               = Campo::val('autor');
                $nuevo_libro['genero']              = Campo::val('genero');
                $nuevo_libro['isbn']                = Campo::val('isbn');
                $nuevo_libro['fecha_publicacion']   = Campo::val('fecha_publicacion');

                $libro = new Libro();
                $libro->insertar($nuevo_libro);

              
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

        $libro = new Libro();

        $datos_consulta = $libro->get_rows([
             'wheremayor' => [
                'fecha_baja' => date('Ymd')
            ]
            ,'limit'  => LISTADO_TOTAL_POR_PAGINA
            ,'offset' => $offset
        ]);

        


        $listado_libros= '';
        $total_registros = 0;
        foreach($datos_consulta as $indice => $registro)
        {
            $botonera = "
                <a onclick=\"fetchJSON('/libros/cons/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-secondary\"><i class=\"bi bi-search\"></i></a>
                <a onclick=\"fetchJSON('/libros/modi/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-pencil-square\"></i></a>
                <a onclick=\"fetchJSON('/libros/baja/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-danger\"><i class=\"bi bi-trash\"></i></a>
            ";

            $listado_libros .= "
                <tr>
                    <th style=\"white-space:nowrap\" scope=\"row\">{$botonera}</th>
                    <td>{$registro['titulo']}</td>
                    <td>{$registro['autor']}</td>
                    <td>{$registro['genero']}</td>
                    <td>{$registro['isbn']}</td>
                    <td>{$registro['fecha_publicacion']}</td>
                    <td>". fmto_fecha($registro['fecha_alta']) . "</td>
                    <td>". fmto_fecha($registro['fecha_baja']) . "</td>
                </tr>
            ";

            $total_registros++;
        }


        $barra_navegacion = Template::navegacion_libros($total_registros,$pagina);


        return "
            <table class=\"table\">
            <thead>
                <tr>
                <th scope=\"col\">#</th>
                <th scope=\"col\">Titulo</th>
                <th scope=\"col\">Autor</th>
                <th scope=\"col\">Genero</th>
                <th scope=\"col\">Isbn</th>
                <th scope=\"col\">Fecha_publicacion</th>
                <th scope=\"col\">Fecha Alta</th>
                <th scope=\"col\">Fecha Baja</th>
                </tr>
            </thead>
            <tbody>
            {$listado_libros}
            </tbody>
            </table>
            {$barra_navegacion}
            <a href=\"/libros/alta\" class=\"btn btn-primary\"><i class=\"bi bi-file-earmark-plus\"></i> Alta libro</a>
            ";

    }



}