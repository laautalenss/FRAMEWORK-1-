<?php

class CalendarioController
{
    static $hora, $modulo, $oper, $id, $paso, $lunes, $martes, $miercoles, $jueves, $viernes;

    static function pintar()
    {

        $contenido = '';
        $h1cabecera = '';
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
                <section class=\"page-section horarios\" id=\"horarios\">
                    {$h1cabecera}
                    {$contenido}
                </section>
                </div>

            ";
    }

    static function inicializacion_campos()
    {
        self::$hora         = new Hidden(['nombre' => 'hora']);
        self::$modulo       = new Hidden(['nombre' => 'modulo']);
        self::$oper         = new Hidden(['nombre' => 'oper']);
        self::$id           = new Hidden(['nombre' => 'id']);
        self::$lunes        = new Text(['nombre' => 'lunes']);
        self::$martes       = new Text(['nombre' => 'martes']);
        self::$miercoles    = new Text(['nombre' => 'miercoles']);
        self::$jueves       = new Password(['nombre' => 'jueves']);
        self::$viernes      = new IEmail(['nombre' => 'viernes']);

        Formulario::cargar_elemento(self::$hora);
        Formulario::cargar_elemento(self::$modulo);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$lunes);
        Formulario::cargar_elemento(self::$martes);
        Formulario::cargar_elemento(self::$miercoles);
        Formulario::cargar_elemento(self::$jueves);
        Formulario::cargar_elemento(self::$viernes);
    }

    static function cons()
    {
        $horario = new Calendario();
        $registro = $horario->recuperar(Campo::val('id'));

        self::sincro_form_bbdd($registro);

        return self::formulario('', [], '', " disabled=\"disabled\" ");
    }

    static function listado()
    {
        $filtro_completo = Campo::val('modulo') ?? '';

        $opciones_html = '';
        $cursos_disponibles = [];

        $query_cursos = new Query(
            "SELECT nombre_grado, curso_numero, letra
        FROM cursos
        ORDER BY nombre_grado, curso_numero, letra"
        );

        if ($query_cursos) {
            while ($curso = $query_cursos->recuperar()) {
                $value = $curso['curso_numero'] . ' ' . $curso['nombre_grado'] . ' ' . $curso['letra'];
                $cursos_disponibles[] = $value;
                $selected = ($filtro_completo == $value) ? 'selected' : '';
                $opciones_html .= "<option value=\"$value\" $selected>$value</option>";
            }
        }


        if (empty($filtro_completo) || !in_array($filtro_completo, $cursos_disponibles)) {
            $filtro_completo = $cursos_disponibles[0] ?? '';
        }

        $listado_horarios = '';
        $tabla_referencia_modulos = '';


        if (!empty($filtro_completo)) {

            $sql = "
        SELECT
            h.dia, h.hora_inicio, h.hora_fin,
            m.nombre AS nombre_modulo,
            m.siglas AS siglas_modulo,
            m.color AS color_modulo,
            CONCAT(p.nombre,' ',p.apellidos) AS nombre_profesor
        FROM horarios h
        JOIN modulos m ON h.id_modulo = m.id
        JOIN personas p ON h.id_profesor = p.id
        JOIN cursos c ON m.curso_asignado = c.id
        WHERE CONCAT(c.curso_numero,' ',c.nombre_grado,' ',c.letra) = '" . addslashes($filtro_completo) . "'
        ORDER BY h.hora_inicio ASC
        ";

            $query = new Query($sql);

            if ($query) {
                $horario_cuadricula = [];
                $modulos_unicos_usados = [];

                while ($clase = $query->recuperar()) {
                    $hora = substr($clase['hora_inicio'], 0, 5);
                    $horario_cuadricula[$hora][$clase['dia']] = $clase;
                    $siglas = $clase['siglas_modulo'];
                    $modulos_unicos_usados[$siglas] = $clase;
                }

                $horario_completo = [
                    '08:00-08:55' => 'clase',
                    '08:55-09:50' => 'clase',
                    '09:50-10:45' => 'clase',
                    '10:45-11:15' => 'recreo',
                    '11:15-12:10' => 'clase',
                    '12:10-13:05' => 'clase',
                    '13:05-14:00' => 'clase'
                ];

                $dias = ['L', 'M', 'X', 'J', 'V'];

                foreach ($horario_completo as $hora => $tipo) {
                    $key = substr($hora, 0, 5);
                    $listado_horarios .= "<tr><th>$hora</th>";

                    if ($tipo === 'recreo') {
                        $listado_horarios .= "<td colspan='5' style='background:#ffA500;text-align:center;font-weight:bold'>RECREO</td>";
                    } else {
                        foreach ($dias as $d) {
                            if (isset($horario_cuadricula[$key][$d])) {
                                $c = $horario_cuadricula[$key][$d];
                                $listado_horarios .= "<td style='background:{$c['color_modulo']};color:white'>
                                <strong>{$c['siglas_modulo']}</strong><br>
                                <small>{$c['nombre_profesor']}</small>
                            </td>";
                            } else {
                                $listado_horarios .= "<td></td>";
                            }
                        }
                    }
                    $listado_horarios .= "</tr>";
                }


                $tabla_referencia_modulos = "<h2 style='margin-top:40px'>Referencia de módulos</h2>
            <table class='table table-bordered'>
            <thead><tr><th>Módulo</th><th>Profesor</th></tr></thead><tbody>";

                foreach ($modulos_unicos_usados as $m) {
                    $tabla_referencia_modulos .= "<tr>
                    <td style='background:{$m['color_modulo']}'><strong>{$m['siglas_modulo']}</strong> {$m['nombre_modulo']}</td>
                    <td>{$m['nombre_profesor']}</td>
                </tr>";
                }

                $tabla_referencia_modulos .= "</tbody></table>";
            }
        }


        return "
    <form method='GET'>
        <label>Selecciona curso:</label>
        <select class=\"form-select form-select-lg mb-3\" aria-label=\"Large select example\" name='modulo' onchange='this.form.submit()'>
            $opciones_html
        </select>
    </form>

    <table class='table table-bordered table-striped'>
        <thead>
            <tr><th>Hora</th><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th></tr>
        </thead>
        <tbody>$listado_horarios</tbody>
    </table>

    $tabla_referencia_modulos
    ";
    }
}
