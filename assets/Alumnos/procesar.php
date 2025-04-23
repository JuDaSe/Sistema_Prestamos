<?php
include_once '../conexion.php';

// Verificamos que el usuario haya dado click en el boton 'Borrar alumno' y asi procesar la accion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM alumnos WHERE id_alumno = ?";
    $res = $con->prepare($sql);
    $res->execute([$id]);
}

// Verificamos que el usuario haya dado al boton de agregar Alumno en la pantalla del Index y ocultamos el otro formulario y actualizamos con este.
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
   
    $accion = $_POST['accion'] ?? '';

    if($accion === 'agregar'){    
        echo '
                <form method="POST" action="procesar.php">
                    <input type="hidden" name="accion" value="guardar">

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

// Verificamos que el usuario haya dado al boton de 'Agregar Alumno' para empezar a guardar el usuario mediante el valor 'guardar'
if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['accion']) && $_POST['accion'] === 'guardar'){
    $matricula = trim($_POST['matricula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $grado = trim($_POST['grado']);
    $grupo = trim($_POST['grupo']);

                if($nombre && $apellido){
                    $sql = "SELECT COUNT(*) FROM alumnos 
                    WHERE
                     LOWER(nombre) = LOWER(:nombre) 
                    AND LOWER(apellido) = LOWER(:apellido)";
                    $stmt = $con->prepare($sql);
                    $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellido' => $apellido
                ]);

                    $sql = "SELECT COUNT(*) FROM alumnos 
                    WHERE matricula = :matricula";
                    $res = $con->prepare($sql);
                    $res->execute([
                    ':matricula' => $matricula
                    ]);
                    $existeMatricula = $res->fetchColumn();
                    $existeNombreApellido = $stmt->fetchColumn();

            if($existeNombreApellido > 0){
                echo '<script>alert("Este alumno ya registrado en la base de datos");
                window.location.href = "index.php";
                </script>';
                exit;
            } else if($existeMatricula > 0) {
                echo '<script>alert("Esta Matricula ya registrado en la base de datos");
                window.location.href = "index.php";
                </script>';
                exit;
            } else {
                $sql = "INSERT INTO alumnos (matricula, nombre, apellido, grado, grupo) VALUES (?,?,?,?,?)";
                $str = $con->prepare($sql);
                $res = $str->execute([$matricula,$nombre,$apellido,$grado,$grupo]);
                if($res){
                    header('Location: index.php');
                    exit;
                } else {
                    header('Location: index.php');
                    exit;
                }
            }
        }
    }    



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) ) {

    $id = $_POST['id'];

    $accion = $_POST['accion'] ?? '';

    if($accion === 'editar'){   

    try {
        $sql = "SELECT * FROM alumnos WHERE id_alumno = ?";
        $str = $con->prepare($sql);
        
        if ($str->execute([$id])) {
            $datos = $str->fetchAll(PDO::FETCH_ASSOC);
    
            if (!empty($datos)) {
                foreach ($datos as $alumno) {
                        echo '
                                <form method="POST" action="procesar.php">
                                    <input type="hidden" name="accion" value="editarGuardar">
                                    <input type="hidden" name="id" value=' . $alumno['id_alumno'] . '>
                
                                    <div class="form-group">
                                        <label for="matricula">Matrícula:</label>
                                        <input type="text" name="matricula" value=' . $alumno['matricula'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" name="nombre" value=' . $alumno['nombre'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" name="apellido" value=' . $alumno['apellido'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="grado">Grado:</label>
                                        <input type="text" name="grado" value=' . $alumno['grado'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="grupo">Grupo:</label>
                                        <input type="text" name="grupo" value=' . $alumno['grupo'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-submit">Guardar Alumno</button> 
                                    </div>
                                </form>
                            ';
                }
            } else {
                echo "No se encontró ningún alumno con ese ID.";
            }
        } else {
            echo "Error al ejecutar la consulta.";
        }
    
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
 
    }    
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editarGuardar'){

    if(isset($_POST['id'])){
        $matricula = $_POST['matricula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $grado = $_POST['grado'];
        $grupo = $_POST['grupo'];
        $id = $_POST['id'];

        $sql = "SELECT * FROM alumnos WHERE matricula = :matricula";
        $str = $con->query($sql);
        $str->execute($matricula);
        $datos = $str->fetchAll(PDO::FETCH_ASSOC);

        if($datos['matricula'] === $matricula){
            echo '<script>alert("Matricula ya existente en la base de datos");</script>';
        } else {
                    $sql = "UPDATE alumnos SET matricula =?, nombre=?, apellido=?, grupo = ?, grado = ? WHERE id_alumno= ?";
                    $str = $con->prepare($sql);
                    $res = $str->execute([$matricula,$nombre,$apellido,$grupo,$grado, $id]);
                    echo "<script>console.log('Paso 2');</script>";
                    if($res){
                        $mensaje = "Usuario creado";
                        header('Location: index.php');
                    } else {
                        $mensaje = "Error al crear este usuario";
                        header('Location: index.php');
                        exit;
                    }
                }
            } else {
                echo 'Usuario inexistente en el sistema';
            }
        }   
?>