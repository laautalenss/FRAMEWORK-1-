
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    
</head>
<body>


    
        
        <form action="./validacionProcesamiento.php" method="POST" >
            

            <br> <label for="idEmail" class="form-label">Introduce tu email: </label> </br>
            <input type="email" name="email" class="form-control" id="idEmail" placeholder="Email..">
            <br></br>
            <br> <label for="idNumero" class="form-label">Introduce tu número: </label> </br>
            <input type="number" name="numero" class="form-control" id="idNumero" placeholder="Edad..">
            <br></br>
            <br> <label for="idNombre" class="form-label">Introduce tu nombre: </label> </br>
            <input type="text" name="nombre" class="form-control" id="idNombre" placeholder="Nombre..">

            
        </form>

        <br></br>
        <input type="submit" class="btn btn-primary" /> <!--botón de envío-->
        
    </div>
</body>
</html> 



<?php

/*

nombre @ dominio .com 
@ .com -> obligatorios

dígitos 0-9 minima 0-15 máxima -> obligatorios

Letra (mayúscula/minúscula), se permiten letras, -, números, _ 
5-20 caracteres

*/

/*
Si no está vacío entra
*/

$email = $_POST['email'];
$numero = $_POST['numero'] ? $_POST['numero'] : '';
//$nombre = $_POST['nombre'] ? $_POST['nombre'] : '';



        $patronEmail = "/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/";
        if(preg_match($patronEmail, $email)){
            echo "Email válido {$email}";
        } else {
            echo  "Email inválido";
        }

    
    



    $patronNumero = "/^[0-9]{9, 15}$/";
    if(preg_match($patronNumero, $numero)){
        echo"Número válido";

    } else {
        echo "Número inválido";
    }


   
 

/*
    $mensaje = "
    ¡Hola, {$_POST['nombre']}!

";

if (strlen($_POST['nombre']) < 5)
{
$mensaje = "
    <div class=\"alert alert-danger\" role=\"alert\">
        El número de caracteres \"{$_POST['nombre']}\" debe ser superior o igual a 5.
    </div>
";
}


if($nombre !== 0){

    $patronNombre = "/^$/";
    if(preg_match($patronNumero, $numero)){
        echo "Número válido";

    } else {
        echo "Número inválido";
    }


}  

*/

?>



