<?php
include_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM alumnos WHERE id_alumno = ?";
    $res = $con->prepare($sql);
    $res->execute([$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
   
    $accion = $_POST['accion'] ?? '';

    if($accion === 'agregar'){    
        echo '
                <form method="POST" action="procesar.php">
                    <input type="hidden" name="accion" value="guardarNuevo">

                    <div class="form-group">
                        <label for="matricula">Matrícula:</label>
                        <input type="text" name="matricula" required>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" required>
                    </div>

                    <div class="form-group">
                        <label for="grado">Grado:</label>
                        <input type="text" name="grado" required>
                    </div>

                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <input type="text" name="grupo" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-submit">Guardar Alumno</button> 
                    </div>
                </form>
            ';
            exit;

        }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['accion']) && $_POST['accion'] === 'guardarNuevo'){
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $grado = $_POST['grado'];
    $grupo = $_POST['grupo'];

    $sql = "INSERT INTO alumnos (matricula, nombre, apellido, grado, grupo) VALUES (?,?,?,?,?)";
    $str = $con->prepare($sql);
    $res = $str->execute([$matricula,$nombre,$apellido,$grupo,$grado]);
    if($res){
        $mensaje = "Usuario creado";
        //echo "<script> alert('" . $mensaje . "'); </script>";
        header('Location: index.php');
    } else {
        $mensaje = "Error al crear este usuario";
        header('Location: index.php');
        exit;
    }
}

?>