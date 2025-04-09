<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Modificacion de Alumnos</title>
</head>
<body>
    
<?php
include_once '../conexion.php';


$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $matricula = $_POST['matricula'] ?? '';
    $grado = $_POST['grado'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $id = $_POST['id'] ?? null;

    $sql = "UPDATE alumnos SET nombre = ?, apellido = ?, matricula = ?, grado = ?, grupo = ? WHERE id_alumno = ?";
    $res = $con->prepare($sql);
    $res->execute([$nombre, $apellido, $matricula, $grado, $grupo, $id]);

    echo "<p>Alumno actualizado correctamente.</p>";

 }

 if(isset($_GET['id'])){

    $sql = "SELECT * FROM alumnos WHERE id_alumno = ? ";
    $res = $con->prepare($sql);
    $res->execute([$id]);

        foreach ($res as $row){
            echo '<form method="POST" class="formulario">';
            echo ' 
            <input type="hidden" name="id" value="' . $row['id_alumno'] . '">
                ';
            echo '
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" value="'  . $row['nombre'] . '" ">
            ';
            echo '
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" value="'  . $row['apellido'] . '" ">
            ';
            echo '
            <label for="matricula">Matricula</label>
            <input type="text" name="matricula" value="'  . $row['matricula'] . '" ">
            ';
            echo '
            <label for="grado">Grado</label>
            <input type="text" name="grado" value="'  . $row['grado'] . '" ">
            ';
            echo '
            <label for="grupo">Grupo</label>
            <input type="text" name="grupo" value="'  . $row['grupo'] . '" ">';

            echo '<input type="submit" value="Guardar alumno">';
            echo '</form>';
        } 
 } 

 echo "<a href='index.php'>Regresar</a>";
?>

</body>
</html>
