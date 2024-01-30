<div class="float float-right d-inline-flex mt-2">
    <i class="fa fa-shopping-cart mr-2 fa-2x"></i>

    <?php

    // Verificar si la sesión 'cesta' está establecida
    if (isset($_SESSION['cesta'])) {

        // Contar la cantidad de productos en la cesta
        $cantidad = array_sum($_SESSION['cesta']);

        // Mostrar la cantidad en un campo de texto deshabilitado
        echo "<input type='text' disabled class='form-control mr-2 bg-transparent text-white' value='$cantidad' size='2px'>";
    } else {

        // Si la cesta no está definida, mostrar '0' en un campo de texto deshabilitado
        echo "<input type='text' disabled class='form-control mr-2 bg-transparent text-white' value='0' size='2px'>";
    }

    ?>
    <i class="fas fa-user mr-3 fa-2x"></i>

    <!-- Mostrar el nombre del usuario en un campo de texto deshabilitado -->
    <input type="text" size='10px' value="<?php echo $_SESSION['nombre']; ?>" class="form-control
    mr-2 bg-transparent text-white" disabled>

    <!-- Enlace para cerrar sesión -->
    <a href="cerrar.php" class="btn btn-warning mr-2">Salir</a>
</div>