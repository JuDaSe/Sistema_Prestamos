

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos - Sistema de Préstamos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <header>
        <div class="container">
        <h1>Sistema de Préstamos de Equipos</h1>
        <nav>
        <ul>
        <li><a href="../index.php">Inicio</a></li>
        <li><a href="../alumnos/index.php">Alumnos</a></li>
        <li><a href="../equipo/index.php">Equipos</a></li>
        <li><a href="../prestamos/index.php">Préstamos</a></li>
        </ul>
        </nav>
        </div>
        </header>
        <main class="container">
        <div class="page-header">
            <h2>Lista de Prestamos</h2>
            <button id="agregarPrestamo" class="btn agregarPrestamo">Nuevo Prestamo</button>
        </div>
            <div id="form-container"></div>

            <div id="contenido">
                <table>
                    <thead>
                        <tr>
                        <th>ID</th> 
                        <th>Alumno</th> 
                        <th>Equipo</th> 
                        <th>Fecha Prestamo</th> 
                        <th>Fecha Devolucion</th> 
                        <th>Estado</th> 
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Cargamos la informacion de la base de datos para que con un foreach procesar todos los prestamos y mostrarlo en index.php
                        include '../conexion.php';
                      /*  $sql = "
                            SELECT 
                                prestamos.id_prestamo,
                                prestamos.fecha_prestamo,
                                equipo.codigo,
                                alumnos.matricula
                            FROM prestamos
                            INNER JOIN equipos ON prestamos.id_equipo = equipos.id_equipo
                            INNER JOIN alumnos ON prestamos.id_alumno = alumnos.id_alumno
                        "; */
                        
                        $sql = "
                        SELECT 
                            prestamos.id_prestamos,
                            prestamos.fecha_prestamo,
                            prestamos.fecha_devolucion,
                            prestamos.estado,
                
                            alumnos.nombre,
                            alumnos.apellido,
                
                            equipo.marca,
                            equipo.modelo
                
                        FROM prestamos
                        INNER JOIN alumnos ON prestamos.id_alumno = alumnos.id_alumno
                        INNER JOIN equipo ON prestamos.id_equipo = equipo.id_equipo ";
                        $res = $con->prepare($sql);
                        $res->execute();
                        
                        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
                            <tr class="info">
                                <td><?= $row['id_prestamos'] ?></td>
                                <td><?= $row['nombre']  . ' ' . $row['apellido'] ?></td>
                                <td><?= $row['marca'] . ' ' . $row['modelo']?></td>
                                <td><?= $row['fecha_prestamo'] ?></td>
                                <td><?= $row['fecha_devolucion'] ?></td>
                                <td><?= $row['estado'] ?></td>
                                <td class="actions">
                                <a href="devolver.html?id=1" class="btn btn-return">Devolver</a> 
                                    <button class="btn btn-edit editarAlumno" data-id="<?= $row['id_prestamos'] ?>">Editar</button>
                                    <a href="#" class="btn btn-delete borrarPrestamo" data-id="<?= $row['id_prestamos'] ?>">Borrar</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
        <div class="container">
        <p>&copy; 2025 Sistema de Préstamos de Equipos</p>
        </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const agregarPrestamo = document.getElementById('agregarPrestamo');
                if(agregarPrestamo){

                        agregarPrestamo.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('contenido').style.display = 'none';
                        document.getElementById('agregarPrestamo').style.display = 'none';

                        const datos = new URLSearchParams();
                        datos.append('accion', 'agregar');

                        fetch('procesar.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: datos
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('form-container').innerHTML = html;
                        })
                        .catch(err => console.error('Error al cargar el formulario:', err));
                    });

                }
                    const borrarPrestamo = document.querySelectorAll('.borrarPrestamo');
                    borrarPrestamo.forEach(function(borrarAlumnobtn) {
                    borrarAlumnobtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const id = this.getAttribute('data-id');
                        const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este prestamo?'); 

                        if (confirmDelete) { // Verificamos que el usuario este de acuerdo con la eliminacion del prestamo
                            document.getElementById('contenido').style.display = 'none';

                            const datos = new URLSearchParams();
                            datos.append('accion', 'eliminar');
                            datos.append('id', id);

                            fetch('procesar.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: datos
                            })
                            .then(response => response.text())
                            .then(data => {
                                window.location.reload();
                            })
                            .catch(err => console.error('Error al eliminar el alumno:', err));
                             }
                        });
                     });
                     const editarAlumno = document.querySelectorAll('.editarAlumno');
                     editarAlumno.forEach(function (editarAlumnobtn){
                        editarAlumnobtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('contenido').style.display = 'none';
                        document.getElementById('agregarPrestamo').style.display = 'none';

                        const id = this.getAttribute('data-id');
                        const datos = new URLSearchParams();
                        datos.append('accion', 'editar');
                        datos.append('id', id);

                        fetch('procesar.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: datos
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('form-container').innerHTML = html;
                        })
                        .catch(err => console.error('Error al cargar el formulario:', err));
                    });
                });
            });        
        </script>
    </body>
</html>