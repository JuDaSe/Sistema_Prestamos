<?php
include_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    $id = $_POST['id_alumno'];
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $grado = $_POST['grado'];
    $grupo = $_POST['grupo'];

    $sql = "UPDATE alumnos SET nombre = ?, apellido = ?, matricula = ?, grado = ?, grupo = ? WHERE id_alumno = ?";
    $stmt = $con->prepare($sql);
    $res = $stmt->execute([$nombre, $apellido, $matricula, $grado, $grupo, $id]);

    if ($res) {
        header("Location: index.php?actualizado=1");
        exit;
    } else {
        $error = "Ocurrió un error al actualizar el alumno.";
    }
}

?>

<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Editar Alumno - Sistema de Préstamos</title> 
    <link rel="stylesheet" href="../css/style.css"> 
</head> 
<body> 
    <header> 
        <div class="container"> 
            <h1>Sistema de Préstamos de Equipos</h1> 
            <nav> 
                <ul> 
                    <li><a href="../index.php">Inicio</a></li> 
                    <li><a href="../alumnos/index.html">Alumnos</a></li> 
                    <li><a href="../equipos/index.html">Equipos</a></li> 
                    <li><a href="../prestamos/index.html">Préstamos</a></li> 
                </ul> 
            </nav> 
        </div> 
    </header> 
 
    <main class="container"> 

<?php

                include_once '../conexion.php';
                $id = $_GET['id'];

                $sql = "SELECT * FROM alumnos WHERE id_alumno = ? ";
                $res = $con->prepare($sql);
                $res->execute([$id]);;

                foreach ($res as $row) {
                
                $matriculaAlumno = $row['matricula'];
                $nombreAlumno = $row['nombre'];
                $apellidoAlumno = $row['apellido'];
                $gradoAlumno = $row['grado'];
                $grupoAlumno = $row['grupo'];

?>
        <h2>Editar Alumno</h2> 
 
        <div class="form-container"> 
            <form method="POST" id="editarAlumnoForm"> 
                <div class="form-group"> 

                    <input type="hidden" name="id_alumno" value="<?= $row['id_alumno']; ?>">
                    <label for="matricula">Matrícula:</label> 
                    <input type="text" id="matricula" name="matricula" value="<?= $matriculaAlumno ?>" required> 
                </div> 
 
                <div class="form-group"> 
                    <label for="nombre">Nombre:</label> 
                    <input type="text" id="nombre" name="nombre" value="<?= $nombreAlumno ?>" required> 
                </div> 
 
                <div class="form-group"> 
                    <label for="apellido">Apellido:</label> 
                    <input type="text" id="apellido" name="apellido" value="<?= $apellidoAlumno ?>" required> 
                </div> 
 
                <div class="form-group"> 
                    <label for="grado">Grado:</label> 
                    <input type="text" id="grado" name="grado" value="<?= $gradoAlumno ?>" required> 
                </div> 
 
                <div class="form-group"> 
                    <label for="grupo">Grupo:</label> 
                    <input type="text" id="grupo" name="grupo" value="<?= $grupoAlumno ?>" required> 
                </div> 
 
                <div class="form-group"> 
                    <button type="submit" class="btn btn-submit">Actualizar Alumno</button> 
                    <a href="index.php" class="btn">Cancelar</a> 
                </div> 
            </form> 
        </div> 
        <?php 
        }?>
    </main> 
 
    <footer> 
        <div class="container"> 
            <p>&copy; 2025 Sistema de Préstamos de Equipos</p> 
        </div> 
    </footer> 


    <script>
       document.getElementById('agregarUsuario').addEventListener('click', function(e) => {
            e.preventDefault();

            fetch('editar.php', {
                method: 'POST',
                body: 'accion=agregar'
            }).then(response => response.text())
            .then(html => {
                document.getElementById('formularioCont').innerHTML = html;
            })
            .catch( err => console.error('Error al cargar el Formulario', err));
        }); 
    </script>
</body> 
</html> 
