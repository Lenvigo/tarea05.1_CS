<?php
session_start();

// variable creada para ver el valor de la directiva session.auto_start
$autoStart = ini_get("session.auto_start");

require_once 'conexion.php';

// Función para mostrar mensajes de error y redirigir a la página de inicio de sesión
function error($mensaje)
{
    $_SESSION['error'] = $mensaje;
    header('Location:login.php');
    die();
}
?>

<!-- Codigo HTML-->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>Login</title>
</head>

<body style="background:silver;">

    <?php
    // Si se ha enviado el formulario de login
    if (isset($_POST['login'])) {
        $nombre = trim($_POST['usuario']);
        $pass = trim($_POST['pass']);

        // Validar que el nombre y la contraseña no contengan solo espacios en blanco
        if (strlen($nombre) == 0 || strlen($pass) == 0) {
            error("Error, El nombre o la contraseña no pueden contener solo espacios en blancos.");
        }

        //creamos el sha256 de la contraseña que es como se almacena en mysql
        $pass1 = hash('sha256', $pass);

        // Consultar la base de datos para verificar las credenciales
        $consulta = "select * from usuarios where usuario=:u AND pass=:p";
        $stmt = $conProyecto->prepare($consulta);
        try {
            $stmt->execute([
                ':u' => $nombre,
                ':p' => $pass1
            ]);
        } catch (PDOException $ex) {
            cerrarTodo($conProyecto, $stmt);
            error("Error en la consulta a la base de datos.");
        }
        // Verificar si se encontró algún usuario con las credenciales proporcionadas.
        // No se encontró ningún usuario con las credenciales proporcionadas
        if ($stmt->rowCount() == 0) {
            unset($_POST['login']);
            cerrarTodo($conProyecto, $stmt);
            error("Error, Nombre de usuario o password incorrecto");
        }
        cerrarTodo($conProyecto, $stmt);

        //Nos hemos validado correctamente creamos la sesion de usuario con el nombre de usuario

        $_SESSION['nombre'] = $nombre;
        header('Location:listado.php');
    } else {
    ?>
        <!-- Formulario de inicio de sesión -->
        <div class="container mt-5">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form name='login' method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="usuario" name='usuario' required>

                            </div>
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" placeholder="contraseña" name='pass' required>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Login" class="btn float-right btn-success" name='login'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
            // Mostrar mensajes de error, si los hay
            if (isset($_SESSION['error'])) {
                echo "<div class='mt-3 text-danger font-weight-bold text-lg'>";
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                echo "</div>";
            }
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>