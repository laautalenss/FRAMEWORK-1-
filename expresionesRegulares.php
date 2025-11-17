#!/usr/bin/php 
<?php

/*
De esta forma la cadena tendrá el mismo formato: dd/mm/yyyy
/^ -> inicio
$/ -> final

Separadores escalados \/
Cada barra escapada corresponde a la separación entre dia / mes / año

Día: (0[1-9]|[12][0-9]|3[0-1])
0[1-9] -> 01, 02 ... 09 , días con 0 delante
[12][0-9] -> 10-29 días
3[0-1] -> 30, 31 , días con 3 delante
Conjunto: del 01-31 siempre con dos dígitos, 0 a la izquierda


Mes: (0[1-9]|1[0-2])
0[1-9] -> igual que el anterior
1[0-2] -> 10, 12, días con 2 delante
Conjunto: del 01-12 siempre con dos dígitos, 0 a la izquierda


Año: ([0-9]{4})
[0-9]{4} -> cuatro dígitos del 0000-9999


Uso de OR | para manejar los diferentes casos



preg_match($patron, $cadena, $coincidencia):
$coincidencia[0] → toda la cadena coincidente (ej. 25/12/2023)
$coincidencia[1] → día (ej. 25)
$coincidencia[2] → mes (ej. 12)
$coincidencia[3] → año (ej. 2023)

*/
$cadena = 'hvkjhbvkjh 28/02/2023';
$patron = "/^(0[1-9]|[12][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/";

if(preg_match($patron, $cadena, $coincidencia)){
    $dia = $coincidencia[1];
    
$mes = $coincidencia[2];

$anho = $coincidencia[3];

echo "<p> 

<b>Fecha válida encontrada</b>
<ul>dia: {$dia}</ul>
<ul>{$mes}</ul>
<ul>{$anho}</ul>

</p>";
    
    

}

$patron = '/[A-ZAEIOU][a-zaeiou]+/';

if(pregu_match_all($patron, $cadena, $coincidencia)){

    echo "<ul>";
    foreach($marches[0] as $palabra){
        echo "<li>{$dia}</li>
        <li>{$mes}</li>
        <li>{$anho}</li>
        
        ";
    }
    echo "</ul>";
}



/*
Ejecuto en zonzamas.lan expresionesRegulares.php

*/
?>