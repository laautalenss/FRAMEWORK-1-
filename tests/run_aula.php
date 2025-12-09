<?php

// Script de prueba CLI para reproducir error de sección aula

// Simular parámetros GET
$_GET['seccion'] = 'aula';
$_GET['oper'] = 'listado';

require __DIR__ . '/../includes/general.php';

try {
    echo Template::seccion('aula');
} catch (Throwable $e) {
    echo "Excepción capturada: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
