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
        <li><a href="../alumnos/index.html">Alumnos</a></li>
        <li><a href="../equipos/index.html">Equipos</a></li>
        <li><a href="../prestamos/index.html">Préstamos</a></li>
        </ul>
        </nav>
        </div>
        </header>
            <main class="container">
                <div class="page-header">
                <h2>Lista de Alumnos</h2>
                <a href="editar.php" id="agregarAlumno" class="btn">Agregar Alumno</a>
                </div>
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
                <!-- Aquí se cargarán los datos de PHP -->

                <?php

                    include '../conexion.php';

                    $sql = "SELECT * FROM alumnos";
                    $res = $con->prepare($sql);
                    $res->execute();

                    foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $row) {?>
                        <tr>
                        <td><?= $row['id_alumno'] ?></td>
                        <td><?= $row['matricula'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['apellido'] ?></td>
                        <td><?= $row['grado'] ?></td>
                        <td><?= $row['grupo'] ?></td>
                        <td>Acciones?</td>
                      
                    <?php } ?>
                    <td class="actions">
                        <a href="editar.php?id=<?= $row['id_alumno'] ?>" class="btn btn-edit">Editar</a>
                        <a href="#" class="btn btn-delete">Eliminar</a>
                    </td>
                    </tr>
                    <!-- Ejemplo de datos -->

                </tbody>
                </table>
            </main>
        <footer>
        <div class="container">
        <p>&copy; 2025 Sistema de Préstamos de Equipos</p>
        </div>
        </footer>
    </body>
</html>