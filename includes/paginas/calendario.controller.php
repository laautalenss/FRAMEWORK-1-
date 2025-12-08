<?php

// Si necesitas BOTON_ENVIAR para futuros formularios
if (Campo::val('modo') == 'ajax') {
    define('BOTON_ENVIAR', "<button onclick=\"fetchJSON('/calendario/" . Campo::val('oper') . "/" . Campo::val('id') . "?modo=ajax','formulario');return false\" class=\"btn btn-primary\">" . Idioma::lit('enviar' . Campo::val('oper')) . "</button>");
} else {
    define('BOTON_ENVIAR', "<button type=\"submit\" class=\"btn btn-primary\">" . Idioma::lit('enviar' . Campo::val('oper')) . "</button>");
}

class CalendarioController
{
    static $curso_seleccionado, $oper, $id, $paso;
    
    static function pintar()
    {
        $contenido = '';
        $h1cabecera = '';
        
        // Inicializar solo campos necesarios
        self::$paso = new Hidden(['nombre' => 'paso']);
        self::$oper = new Hidden(['nombre' => 'oper']);
        self::$id = new Hidden(['nombre' => 'id']);
        self::$curso_seleccionado = new Hidden(['nombre' => 'modulo']); // Para mantener filtro
        
        switch (Campo::val('oper')) {
            case 'cons':
                $contenido = self::cons();
                break;
            case 'alta':
                $contenido = self::alta();
                break;
            case 'modi':
                $contenido = self::modi();
                break;
            case 'baja':
                $contenido = self::baja();
                break;
            default:
                $contenido = self::listado_horario();
                break;
        }
        
        if (Campo::val('modo') != 'ajax') {
            $h1cabecera = "<h1>Calendario de Horarios</h1>";
        }
        
        return "
        <div class=\"container contenido\">
        <section class=\"page-section calendario\" id=\"calendario\">
            {$h1cabecera}
            {$contenido}
        </section>
        </div>";
    }
    
    static function listado_horario()
    {
        // 1. SELECTOR DE CURSOS (igual que funciona)
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
        
        // 2. TABLA DE HORARIO (igual que funciona)
        $listado_horarios = '';
        $tabla_referencia_modulos = '';
        
        if (!empty($filtro_completo)) {
            $sql = "
            SELECT
                h.dia, h.hora_inicio, h.hora_fin,
                m.nombre AS nombre_modulo,
                m.siglas AS siglas_modulo,
                m.color AS color_modulo,
                CONCAT(p.nombre,' ',p.apellidos) AS nombre_profesor,
                a.nombre AS nombre_aula
            FROM horarios h
            JOIN modulos m ON h.id_modulo = m.id
            JOIN personas p ON h.id_profesor = p.id
            JOIN aulas a ON h.id_aula = a.id
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
                
                $horas_tramos = [
                    '08:00' => '08:00-08:55',
                    '08:55' => '08:55-09:50', 
                    '09:50' => '09:50-10:45',
                    '11:15' => '11:15-12:10',
                    '12:10' => '12:10-13:05',
                    '13:05' => '13:05-14:00'
                ];
                
                $dias = ['L', 'M', 'X', 'J', 'V'];
                
                foreach ($horas_tramos as $hora_inicio => $rango) {
                    $listado_horarios .= "<tr><th>$rango</th>";
                    
                    foreach ($dias as $dia) {
                        if (isset($horario_cuadricula[$hora_inicio][$dia])) {
                            $clase = $horario_cuadricula[$hora_inicio][$dia];
                            $listado_horarios .= "
                            <td style='background:{$clase['color_modulo']};color:white;cursor:pointer'
                                onclick=\"fetchJSON('/calendario/cons/1?modo=ajax')\"
                                data-bs-toggle='modal'
                                data-bs-target='#ventanaModal'
                                title='{$clase['nombre_modulo']} - {$clase['nombre_profesor']} - {$clase['nombre_aula']}'>
                                <strong>{$clase['siglas_modulo']}</strong><br>
                                <small>{$clase['nombre_profesor']}</small>
                            </td>";
                        } else {
                            $listado_horarios .= "<td></td>";
                        }
                    }
                    $listado_horarios .= "</tr>";
                }
                
                // Tabla de referencia
                if (!empty($modulos_unicos_usados)) {
                    $tabla_referencia_modulos = "
                    <div class='mt-4'>
                        <h3>Módulos del curso</h3>
                        <table class='table table-bordered'>
                        <thead><tr><th>Módulo</th><th>Profesor</th><th>Aula</th></tr></thead>
                        <tbody>";
                    
                    foreach ($modulos_unicos_usados as $modulo) {
                        $tabla_referencia_modulos .= "
                        <tr>
                            <td style='background:{$modulo['color_modulo']};color:white'>
                                <strong>{$modulo['siglas_modulo']}</strong> {$modulo['nombre_modulo']}
                            </td>
                            <td>{$modulo['nombre_profesor']}</td>
                            <td>{$modulo['nombre_aula']}</td>
                        </tr>";
                    }
                    
                    $tabla_referencia_modulos .= "</tbody></table></div>";
                }
            }
        }
        
        // 3. BOTÓN PARA AÑADIR NUEVO HORARIO
        $boton_nuevo = "
        <div class='mt-3'>
            <a onclick=\"fetchJSON('/calendario/alta?modo=ajax')\" 
               data-bs-toggle='modal' 
               data-bs-target='#ventanaModal' 
               class='btn btn-primary'>
                <i class='bi bi-plus-circle'></i> Añadir Horario
            </a>
        </div>";
        
        return "
        <div class='row'>
            <div class='col-md-12'>
                <form method='GET' class='mb-4'>
                    <label class='form-label'><strong>Selecciona curso:</strong></label>
                    <select class='form-select' name='modulo' onchange='this.form.submit()'>
                        $opciones_html
                    </select>
                </form>
            </div>
        </div>
        
        <div class='table-responsive'>
            <table class='table table-bordered table-hover calendario-semanal'>
                <thead class='table-dark'>
                    <tr><th>Hora</th><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th></tr>
                </thead>
                <tbody>$listado_horarios</tbody>
            </table>
        </div>
        
        $tabla_referencia_modulos
        $boton_nuevo";
    }
    
    // Métodos para alta/modi/baja (simplificados)
    static function cons()
    {
        return "<div class='alert alert-info'>Consulta de horario individual (en construcción)</div>";
    }
    
    static function alta()
    {
        return "<div class='alert alert-info'>Formulario para añadir nuevo horario (en construcción)</div>";
    }
    
    static function modi()
    {
        return "<div class='alert alert-info'>Formulario para modificar horario (en construcción)</div>";
    }
    
    static function baja()
    {
        return "<div class='alert alert-info'>Formulario para eliminar horario (en construcción)</div>";
    }
}