<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Alumno - Eliminar</title>
</head>
<body>

<?php

include '../conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'] ?? '';
$matricula = $_POST['matricula'] ?? '';

$sql = "SELECT * FROM alumnos WHERE nombre LIKE ? AND apellido LIKE ? AND matricula LIKE ?";
$res = $con->prepare($sql);
$res->execute(["%$nombre%", "%$apellido%", "%$matricula%"]);

echo "Alumnos. <br>";
foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "ID: " . $row['id_alumno'] . "<br>";
    echo "Nombre: " . $row['nombre'] . "<br>";
    echo "Apellido: " . $row['apellido'] . "<br>";
    echo "Matricula: " . $row['matricula'] . "<br>";
    echo "Grado: " . $row['grado'] . "<br>";
    echo "Grupo: " . $row['grupo'] . "<br>";
    echo '<a href="eliminar.php?id=' . $row['id_alumno'] . '">
          <button>Eliminar Alumno</button>
          </a>';
    echo '<a href="modificar.php?id=' . $row['id_alumno'] . '">
          <button>Modificar Alumno</button>
          </a>';      
    echo "<hr>";
}

echo "<a href='index.php'>Regresar</a>";
?>
    
</body>
</html>