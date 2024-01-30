<?php

session_start();
unset($_SESSION['nombre']);
unset($_SESSION['cesta']);
//session_unset();
//session_destroy();
header("Location: login.php");
