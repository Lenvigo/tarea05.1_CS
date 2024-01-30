<?php
$host = "localhost";
$db = "proyecto";
$user = "gestor";
$pass = "secreto";
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    
    // Se crea una nueva instancia de PDO para establecer la conexión con la base de datos
    $conProyecto = new PDO($dsn, $user, $pass);
    
    // Se configura PDO para lanzar excepciones en caso de errores
    $conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $ex) {
     
    // En caso de error en la conexión, se muestra un mensaje y se finaliza el script
    die("Error en la conexión: mensaje: " . $ex->getMessage());
}


// Función para consultar un producto por su ID
function consultarProducto($id)
{
    global $conProyecto;
    $consulta = "select * from productos where id=:i";
    $stmt1 = $conProyecto->prepare($consulta);
    
    try {
          
        // Se ejecuta la consulta con el ID proporcionado como parámetro
        $stmt1->execute([':i' => $id]);

    } catch (PDOException $ex) {
        
        // En caso de error, se muestra un mensaje y se finaliza el script
        die("Error al recuperar Productos: " . $ex->getMessage());
    }

    //esta consulta solo devuelve una fila es innecesario el while para recorrerla
    $producto = $stmt1->fetch(PDO::FETCH_OBJ);
    $stmt1 = null;
    return $producto;
}

function cerrar(&$con)
{
    $con = null;
}

function cerrarTodo(&$con, &$st)
{
    $st = null;
    $con = null;
}