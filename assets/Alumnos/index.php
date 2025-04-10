<?php
if (isset($_GET['accion'])) {
    if ($_GET['accion'] == 'agregado') {
        echo "<script>alert('Alumno agregado correctamente.');</script>";
    } elseif ($_GET['accion'] == 'error') {
        echo "<script>alert('Hubo un error al agregar el alumno.');</script>";
    } elseif ($_GET['accion'] == 'eliminado') {
        echo "<script>alert('Alumno eliminado correctamente.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - Sistema de Préstamos</title>
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
        <li><a href="../equipos/index.php">Equipos</a></li>
        <li><a href="../prestamos/index.php">Préstamos</a></li>
        </ul>
        </nav>
        </div>
        </header>
        <main class="container">
        <div class="page-header">
            <h2>Lista de Alumnos</h2>
            <button id="agregarAlumno" class="btn agregarAlumno">Agregar Alumno</button>
        </div>
            <div id="form-container"></div>

            <div id="contenido">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Grado</th>
                            <th>Grupo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../conexion.php';
                        $sql = "SELECT * FROM alumnos";
                        $res = $con->prepare($sql);
                        $res->execute();

                        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
                            <tr class="info">
                                <td><?= $row['id_alumno'] ?></td>
                                <td><?= $row['matricula'] ?></td>
                                <td><?= $row['nombre'] ?></td>
                                <td><?= $row['apellido'] ?></td>
                                <td><?= $row['grado'] ?></td>
                                <td><?= $row['grupo'] ?></td>
                                <td class="actions">
                                    <a href="#" class="btn btn-edit" data-id="<?= $row['id_alumno'] ?>">Editar</a>
                                    <a href="#" class="btn btn-delete borrarAlumno" data-id="<?= $row['id_alumno'] ?>">Borrar Alumno</a>

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
                const agregarAlumno = document.getElementById('agregarAlumno');
                if(agregarAlumno){

                        agregarAlumno.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('contenido').style.display = 'none';
                        document.getElementById('agregarAlumno').style.display = 'none';

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
                    // Eliminar al Alumno ->
                    const borrarAlumnos = document.querySelectorAll('.borrarAlumno');
                    borrarAlumnos.forEach(function(borrarAlumnobtn) {
                    borrarAlumnobtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const id = this.getAttribute('data-id');
                        const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este alumno?');

                        if (confirmDelete) {
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
            });
        </script>
    </body>
</html>