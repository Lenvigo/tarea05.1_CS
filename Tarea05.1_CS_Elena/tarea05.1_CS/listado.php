<?php
// inicio de sesion
session_start();

require_once 'util_cookies.php';

//verificamos si el usuario esta almacenado en la sesion y si no lo esta loredirigimos a la pagina de inicio de sesion

if (!isset($_SESSION['nombre'])) {
    header('Location:login.php');
}

// datos de conexion a la bd
require_once 'conexion.php';
//consulta  a la bd
$consulta = "select id, nombre, pvp from productos order by nombre";
$stmt = $conProyecto->prepare($consulta);
//manejo de excepciones PDO durante la ejecucion nde la consulta
try {
    $stmt->execute();
} catch (PDOException $ex) {
    cerrarTodo($conProyecto, $stmt);
    die("Error al recuperar los productos " . $ex->getMessage());
}


?>
<!doctype html>
<html lang="es">

<head>
    <!--   metadatos y hojas de estilo -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- css Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Cesta de la compra</title>
</head>

<?php

// Si se envía el formulario con el botón "Vaciar Carro" ($_POST['vaciar']), se elimina la variable de sesión cesta
if (isset($_POST['vaciar'])) {
    unset($_SESSION['cesta']);
}


if (isset($_POST['comprar']) && isset($_POST["unidad"])) {
    $datos = consultarProducto($_POST['id']);
    if ($datos !== false) {

        if (!isset($_SESSION['cesta'][$datos->id])) {
            $_SESSION['cesta'][$datos->id] = $_POST["unidad"];
        } else {
            $_SESSION['cesta'][$datos->id] += $_POST["unidad"];
        }

        gestionar_cookie_familia($datos->familia);
              
    }
}
?>

<body style="background: gray">
    <!-- Se incluye un encabezado (header_view.php).
Se muestra un título y se presenta un formulario que permite ir a la página de la cesta de compra o vaciar el carro.
Se muestra una tabla con la lista de productos obtenidos de la base de datos.
Cada fila de la tabla incluye un botón "Añadir" que permite agregar el producto a la cesta. También se muestra un ícono indicando si el producto ya está en la cesta. -->
    <?php require_once 'header_view.php' ?>
    <br>
    <h4 class="container text-center mt-4 font-weight-bold">Tienda onLine</h4>
    <div class="container mt-3">
        <form class="form-inline" name="vaciar" method="POST" action='<?php echo $_SERVER['PHP_SELF']; ?>'>
            <a href="cesta.php" class="btn btn-success mr-2">Ir a Cesta</a>
            <input type='submit' value='Vaciar Carro' class="btn btn-danger" name="vaciar">
        </form>
        <table class="table table-striped table-dark mt-3">
            <thead>
                <tr class="text-center">
                    <th scope="col">Añadir</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Añadido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*  Este bucle recorre cada fila de resultados obtenidos de la consulta a la base de datos. fetch(PDO::FETCH_OBJ) recupera la siguiente fila como un objeto. */
                while ($filas = $stmt->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    // BOTON AÑADIR e id oculto
                    echo "<th scope='row' class='text-center'>";
                    echo  "<form id ='addProductForm{$filas->id}' action='{$_SERVER['PHP_SELF']}' method='POST'>";
                    echo "<input type='hidden' name='id' value='{$filas->id}'>";
                    echo "<input type='submit' class='btn btn-primary' name='comprar' value='Añadir'>";
                    echo "</form>";
                    echo "</th>";
                    // NOMBRE PRODUCTO
                    echo "<td>{$filas->nombre}, Precio: {$filas->pvp} (€)</td>";
                    // UNIDADES 
                    echo "<td>";
                    echo "<input form='addProductForm{$filas->id}' type='number' name='unidad' value='1' min='1' max='3'>";
                    echo "</td>";
                    // ICONO
                    echo "<td class='text-center'>";
                    if (isset($_SESSION['cesta'][$filas->id])) {
                        echo "<i class='fas fa-check fa-2x'></i>";
                    } else {
                        echo "<i class='far fa-times-circle fa-2x'></i>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                /* Luego de completar el bucle, se llama a una función (cerrarTodo) para cerrar la conexión a la base de datos y liberar los recursos asociados al statement. */
                cerrarTodo($conProyecto, $stmt);
                ?>
            </tbody>
        </table>

    </div>
</body>

</html>