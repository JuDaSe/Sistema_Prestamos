<?php

include '../conexion.php';

$id = $_GET['id'];

$sql = "DELETE FROM alumnos WHERE id_alumno = ?";
$res = $con->prepare($sql);
$res->execute([$id]);

echo "Alumno eliminado";

echo "<a href='index.php'>Regresar</a>";
?>