<?php

try { 
$con = new PDO("mysql:host=localhost;dbname=sistema_prestamos", 'root', '');
} catch (PDOException $e) {
    echo 'Error en base de datos'. $e->getMessage() .'';
}
?>