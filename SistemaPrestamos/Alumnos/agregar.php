<?php  

include '../conexion.php';

$nombre = $_GET['nombre'];
$apellido = $_GET['apellido'];
$matricula = $_GET['matricula'];
$grado = $_GET['grado'];
$grupo = $_GET['grupo'];

$sql = $con->prepare("INSERT INTO alumnos (nombre, apellido, matricula, grado, grupo) VALUES (?, ?, ?, ?, ?)");
$sql->execute([$nombre, $apellido, $matricula, $grado, $grupo]);

echo "Agregado exitosamente. <br>";

echo "<a href='index.php'>Regresar</a>";


?>