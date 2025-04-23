<?php
include_once '../conexion.php';

// Verificamos que el usuario haya dado click en el boton 'Borrar alumno' y asi procesar la accion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM prestamos WHERE id_prestamo = ?";
    $res = $con->prepare(query: $sql);
    $res->execute([$id]);
}

// Verificamos que el usuario haya dado al boton de agregar Alumno en la pantalla del Index y ocultamos el otro formulario y actualizamos con este.
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
   
    $accion = $_POST['accion'] ?? '';

    if($accion === 'agregar'){  
        
        $sql = "SELECT id_alumno, nombre, apellido, matricula FROM alumnos ";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);      
        
        $sql = "SELECT id_equipo, codigo, marca FROM equipo ";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);     
        echo '
                <main class="container"> 
                <h2>Nuevo Préstamo</h2>     
                <div class="form-container"> 
                    <form action="procesar.php" method="POST" id="prestamoForm"> 
                    <input type="hidden" name="accion" value="guardar">
                        <div class="form-group"> 
                            <label for="alumno">Seleccionar Alumno:</label> 
                            <select id="alumno" name="alumno" required> 
                                <option value="">Seleccionar alumno</option>';
                                foreach ($alumnos as $alumno){
                        echo '  <option value="'. $alumno['id_alumno'] .'"> '
                                 . $alumno['nombre'] . ' '. $alumno['apellido'] .' - ' . $alumno['matricula'] . '
                                </option>'; 
                                }
                        echo '  </select> 
                             </div>
                        
        
                        <div class="form-group"> 
                            <label for="equipo">Seleccionar Equipo:</label> 
                            <select id="equipo" name="equipo" required> 
                                <option value="">Seleccionar equipo</option>'; 
                                foreach($equipos as $equipo) {
                        echo   '<option value="' . $equipo['id_equipo'] . '"> ' . $equipo['codigo'] .  ' - ' . $equipo['marca'] . '
                                </option> '; 
                                }
                        echo ' </select> 
                                </div> 
                    
                                <div class="form-group"> 
                                    <label for="fechaPrestamo">Fecha de Préstamo:</label> 
                                    <input type="date" id="fechaPrestamo" name="fechaPrestamo" required value=""> 
                                </div> 
                    
                                <div class="form-group"> 
                                    <label for="fechaDevolucion">Fecha Límite de Devolución:</label> 
                                    <input type="date" id="fechaDevolucion" name="fechaDevolucion" required value=""> 
                                </div> 
                    
                                <div class="form-group"> 
                                    <label for="observaciones">Observaciones:</label> 
                                    <textarea id="observaciones" name="observaciones" rows="4"></textarea> 
                                </div> 
                    
                                <div class="form-group"> 
                                    <button type="submit" class="btn btn-submit">Registrar Préstamo</button> 
                                    <a href="index.php" class="btn">Cancelar</a> 
                                </div> 
                            </form> 
                        </div> 
                    </main>';
            exit;
            }
        }

// Verificamos que el usuario haya dado al boton de 'Agregar Alumno' para empezar a guardar el usuario mediante el valor 'guardar'
if($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['accion']) && $_POST['accion'] === 'guardar'){
    $id_alumno = $_POST['alumno'] ?? null;
    $id_equipo = $_POST['equipo'] ?? null;
    $fechaPrestamo = $_POST['fechaPrestamo'] ?? null;
    $fechaDevolucion = $_POST['fechaDevolucion'] ?? null;
    $observaciones = $_POST['observaciones'] ?? '';

    if($id_alumno){
        $sql = "SELECT COUNT(*) FROM prestamos WHERE id_alumno = :id_alumno";
        $res = $con->prepare($sql);
        $res->execute([':id_alumno' => $id_alumno]);
    }

    $existe = $res->fetchColumn();
    if($existe > 0){
        echo '<script>alert("Este Alumno ya tiene un prestamo");
            window.location.href = "index.php";
            </script>';
    } else {

        $sql = "INSERT INTO prestamos (id_alumno, id_equipo, fecha_Prestamo, fecha_devolucion, estado)
        VALUES (:id_alumno, :id_equipo, :fecha_Prestamo, :fecha_Devolucion, :observaciones)";
       $str = $con->prepare($sql);
       $res = $str->execute([
           ':id_alumno' => $id_alumno,
           ':id_equipo' => $id_equipo,
           ':fecha_Prestamo' => $fechaPrestamo,
           ':fecha_Devolucion' => $fechaDevolucion,
           ':observaciones' => $observaciones
       ]);
       if($res){
           $mensaje = "Usuario creado";
           header('Location: index.php');
       } else {
           $mensaje = "Error al crear este usuario";
           header('Location: index.php');
           exit;
       }

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