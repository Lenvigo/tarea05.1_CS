<?php

//Se inicia la sesión y se verifica si el usuario está autenticado. Si no lo está, se redirige a la página de inicio de sesión.
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location:login.php');
}

// conexion a la bd
require_once 'conexion.php';


/* Si existe una cesta en la sesión, se itera sobre los elementos de la cesta y se obtiene información adicional sobre cada producto llamando a la función consultarProducto.
La información del producto se almacena en un array asociativo llamado $listado*/
if (isset($_SESSION['cesta'])) {
    foreach ($_SESSION['cesta'] as $key => $value) {
        $producto = consultarProducto($key);
        $listado[$key] = [$producto->nombre, $producto->pvp, $_SESSION['cesta'][$key]];
        $producto = null;
    }
    cerrar($conProyecto);
}

?>

<!-- Codigo HTML-->
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- css Fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Cesta de la compra</title>
</head>

<body style="background: gray">
    <?php require_once 'header_view.php' ?>
    <br>
    <h4 class="container text-center mt-4 font-weight-bold">Comprar Productos</h4>
    <div class="container mt-3">
        <div class="card text-white bg-success mb-3 m-auto" style="width:40rem">
            <div class="card-body">
                <h5 class="card-title"><i class="fa fa-shopping-cart mr-2"></i>Carrito</h5>
                <?php
                if (!isset($_SESSION['cesta'])) {
                    echo "<p class='card-text'>Carrito Vacio</p>";
                } else {
                    $total = 0;
                    echo "<table class='table table-striped table-success mt-3'>
                <thead>
                    <tr class='text-center'>
                        <th scope='col'>Nombre</th>
                        <th scope='col'>Precio unitario</th>
                        <th scope='col'>Unidades</th>
                        <th scope='col'>Subtotal</th>
                    </tr>
                </thead>
    <tbody>";
                    foreach ($listado as $key => $value) {

                        $subtotal = $value[1] * $value[2];
                        echo    "<tr>
                                    <td>$value[0]</td> 
                                    <td>$value[1] €</td> 
                                    <td>$value[2]</td>
                                    <td>$subtotal €</td>";
                        $total += $subtotal;
                    }
                    echo "</tbody> </table>";
                    echo "<hr style='border:none; height:2px; background-color: white'>";
                    echo "<p class='card-text'><b>Total:</b><span class='ml-3'>$total €</span></p>";
                }
                ?>
                <a href="listado.php" class="btn btn-primary mr-2">Volver</a>
                <a href="pagar.php" class="btn btn-danger">Pagar</a>
            </div>
        </div>
    </div>
</body>

</html>