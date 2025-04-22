

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
            <h2>Lista de Alumnos</h2>
            <button id="agregarEquipo" class="btn agregarEquipo">Agregar equipo</button>
        </div>
            <div id="form-container"></div>

            <div id="contenido">
                <table>
                    <thead>
                        <tr>
                        <th>ID</th> 
                        <th>Código</th> 
                        <th>Tipo</th> 
                        <th>Marca</th> 
                        <th>Modelo</th> 
                        <th>Estado</th> 
                        <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Cargamos la informacion de la base de datos para que con un ForEach procesar todos los alumnos y mostrarlo en index.php
                        include '../conexion.php';
                        $sql = "SELECT * FROM equipo";
                        $res = $con->prepare($sql);
                        $res->execute();
                        
                        foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
                            <tr class="info">
                                <td><?= $row['id_equipo'] ?></td>
                                <td><?= $row['codigo'] ?></td>
                                <td><?= $row['tipo'] ?></td>
                                <td><?= $row['marca'] ?></td>
                                <td><?= $row['modelo'] ?></td>
                                <td><?= $row['estado'] ?></td>
                                <td class="actions">
                                    <button class="btn btn-edit editarEquipo" data-id="<?= $row['id_equipo'] ?>">Editar</button>
                                    <a href="#" class="btn btn-delete borrarEquipo" data-id="<?= $row['id_equipo'] ?>">Borrar</a>

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
            document.addEventListener('DOMContentLoaded', function() { // Creamos el evento para que cargue despues del HTML y no tener errores en los procesos.
                const agregarEquipo = document.getElementById('agregarEquipo');
                if(agregarEquipo){

                        agregarEquipo.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('contenido').style.display = 'none';
                        document.getElementById('agregarEquipo').style.display = 'none';

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
                    // Obtenemos la accion del boton mediante JavaScript y creamos un evento click en la funcion, desde la clase .borrarEquipo
                    const borrarEquipo = document.querySelectorAll('.borrarEquipo');
                    borrarEquipo.forEach(function(borrarEquipobtn) {
                    borrarEquipobtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        const id = this.getAttribute('data-id');
                        const confirmDelete = confirm('¿Estás seguro de que deseas eliminar este alumno?'); 

                        if (confirmDelete) { // Verificamos que el usuario este de acuerdo con la eliminacion del usuario
                            document.getElementById('contenido').style.display = 'none';

                            const datos = new URLSearchParams();
                            datos.append('accion', 'eliminar');
                            datos.append('id', id);

                            fetch('procesar.php', { // Procesamo mediante fetch las acciones del usuario y las enviamos a procesar.php para concluir con el siguiente paso mediante PHP
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
                            .catch(err => console.error('Error al eliminar el equipo:', err));
                             }
                        });
                     });
                     const editarEquipo = document.querySelectorAll('.editarEquipo');
                     editarEquipo.forEach(function (editarAlumnobtn){
                        editarAlumnobtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.getElementById('contenido').style.display = 'none';
                        document.getElementById('agregarEquipo').style.display = 'none';

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