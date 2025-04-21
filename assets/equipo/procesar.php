<?php
include_once '../conexion.php';

// Verificamos que el usuario haya dado click en el boton 'Borrar alumno' y asi procesar la accion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM equipo WHERE id_equipo = ?";
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
                        <label for="codigo">Codigo:</label>
                        <input type="text" name="codigo" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <input type="text" name="tipo" required>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca:</label>
                        <input type="text" name="marca" required>
                    </div>

                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <input type="text" name="modelo" required>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <input type="text" name="estado" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-submit">Guardar equipo</button> 
                    </div>
                </form>
            ';
            exit;
        }
}

// Verificamos que el usuario haya dado al boton de 'Agregar Alumno' para empezar a guardar el usuario mediante el valor 'guardar'
if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['accion']) && $_POST['accion'] === 'guardar'){
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO equipo (codigo, tipo, marca, modelo, estado) VALUES (?,?,?,?,?)";
    $str = $con->prepare($sql);
    $res = $str->execute([$codigo,$tipo,$marca,$modelo,$estado]);
    if($res){
        $mensaje = "Usuario creado";
        header('Location: index.php');
    } else {
        $mensaje = "Error al crear este usuario";
        header('Location: index.php');
        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) ) {

    $id = $_POST['id'];

    $accion = $_POST['accion'] ?? '';

    if($accion === 'editar'){   

    try {
        $sql = "SELECT * FROM equipo WHERE id_equipo = ?";
        $str = $con->prepare($sql);
        
        if ($str->execute([$id])) {
            $datos = $str->fetchAll(PDO::FETCH_ASSOC);
    
            if (!empty($datos)) {
                foreach ($datos as $equipos) {
                        echo '
                                <form method="POST" action="procesar.php">
                                    <input type="hidden" name="accion" value="editarGuardar">
                                    <input type="hidden" name="id" value=' . $equipos['id_equipo'] . '>
                
                                    <div class="form-group">
                                        <label for="codigo">Codigo:</label>
                                        <input type="text" name="codigo" value=' . $equipos['codigo'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="tipo">Tipo:</label>
                                        <input type="text" name="tipo" value=' . $equipos['tipo'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="marca">Marca:</label>
                                        <input type="text" name="marca" value=' . $equipos['marca'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="modelo">Modelo:</label>
                                        <input type="text" name="modelo" value=' . $equipos['modelo'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="estado">Estado:</label>
                                        <input type="text" name="estado" value=' . $equipos['estado'] . ' required>
                                    </div>
                
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-submit">Guardar equipo</button> 
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
        $codigo = $_POST['codigo'];
        $tipo = $_POST['tipo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $estado = $_POST['estado'];
        $id = $_POST['id'];
    
        echo "<script>console.log('Paso 1 el id es: ', " . $id . ");</script>";
        $sql = "UPDATE equipo SET codigo  = ?, tipo = ?, marca = ?, modelo = ?, estado = ? WHERE id_equipo = ?";
        $str = $con->prepare($sql);
        $res = $str->execute([$codigo,$tipo,$marca,$modelo,$estado, $id]);
        echo "<script>console.log('Paso 2');</script>";
        if($res){
            $mensaje = "Usuario creado";
            header('Location: index.php');
        } else {
            $mensaje = "Error al crear este usuario";
            header('Location: index.php');
            exit;
        }
    } else {
        echo 'ID no reconocido o vacio';
    }
    
   
}
?>