<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Préstamos</title>
    <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <header>
        <div class="container">
        <h1>Sistema de Préstamos de Equipos</h1>
        <nav>
            <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="./Alumnos/index.php">Alumnos</a></li>
            <li><a href="./Equipos/index.php">Equipos</a></li>
            <li><a href="./Prestamos/index.php">Préstamos</a></li>
            </ul>
        </nav>
        </div>
        </header>
    <main class="container">
    <h2>Bienvenido al Sistema de Préstamos</h2>
        <div class="dashboard">
            <div class="card">
                <h3>Alumnos</h3>
                <?php include_once("conexion.php");

                    $sql = "SELECT * FROM alumnos";
                    $result = $con->query($sql);
                    $total = $result->rowCount();
                    
                     ?>
                            <td><?= $total ?></td>
                     <?php
                 ?>
            </div>
            <div class="card">
                <h3>Equipos</h3>
                <p class="number">0</p>
            </div>
            <div class="card">
                <h3>Préstamos Activos</h3>
                <p class="number">0</p>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2025 Sistema de Préstamos de Equipos</p>
        </div>
    </footer>
    </body>
</html>