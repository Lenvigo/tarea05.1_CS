<?php  //gestion de cookies

//duracion cookie 30 dias
const DURATION_COOKIE_FAMILIAS = 60 * 60 * 24 * 30;

//verificamos que existe o  inicializamos cesta si no existe
if (!isset($_SESSION['cesta'])) {
    $_SESSION['cesta'] = array();
}

// Función para gestionar la cookie de familia.
function gestionar_cookie_familia(string $cod_familia) 
{
    if (!isset($_COOKIE["familias"])) {

        // Si la cookie "familias" no está definida, creamos la primera entrada.
        setcookie("familias[0]", $cod_familia, time() + DURATION_COOKIE_FAMILIAS);

    } else {

        // Si la cookie "familias" ya existe, comprobamos si la familia ya está presente.
        $familias_array = $_COOKIE["familias"];
        $count_familias = count($familias_array);
        $index_or_found = array_search($cod_familia, $familias_array);

        if ($index_or_found === false) {
            
            // Si la familia no está presente, la añadimos a la cookie.
            setcookie("familias[$count_familias]", $cod_familia, time() + DURATION_COOKIE_FAMILIAS);
        }
    }
}
// Si la cookie "familias" está definida, mostramos las familias.
function mostrar_familias()
{
    if (isset($_COOKIE["familias"])) {

        $familias_array = $_COOKIE["familias"];
        echo "<p> Quizá también te interesen productos de estas categorías...</p>";
        echo "<ul>";
        foreach ($familias_array as $index => $cod_familia) {
            echo "<li>$cod_familia</li>";
        }
        echo "</ul>";
    }
}


/* codigo de manejo sesiones actividad 5.3 sesiones texto array

function cerrarSesion() {

    iniciarSesion();

   

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}


function iniciarSesion(): bool {
    $iniciada = true;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $iniciada = session_start();
    }

    return $iniciada;
}
 */