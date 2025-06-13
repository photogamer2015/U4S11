<?php
// Parámetros de conexión
$server = 'localhost:3306'; // Cambia si es necesario
$username = 'root'; // Tu usuario de MariaDB
$password = ''; // Tu contraseña de MariaDB
$database = 'datos'; // Nombre de la base de datos

// Conexión usando PDO
try {
    $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Modo de manejo de errores

    // Manejo de la inserción de nuevos alumnos
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "INSERT INTO Alumnos (Cedula, Nombres, Apellidos, Correo, Telefono, FechaNacimiento) 
                VALUES (:cedula, :nombres, :apellidos, :correo, :telefono, :fecha_nacimiento)";
        
        $stmt = $con->prepare($sql);
        
        // Bind de parámetros
        $stmt->bindParam(':cedula', $_POST['cedula']);
        $stmt->bindParam(':nombres', $_POST['nombres']);
        $stmt->bindParam(':apellidos', $_POST['apellidos']);
        $stmt->bindParam(':correo', $_POST['correo']);
        $stmt->bindParam(':telefono', $_POST['telefono']);
        $stmt->bindParam(':fecha_nacimiento', $_POST['fecha_nacimiento']);
        
        // Ejecutar la consulta
        $stmt->execute();
    }

    // Consulta para obtener los alumnos
    $sql = 'SELECT `Id`, `Cedula`, `Nombres`, `Apellidos`, `Correo`, `Telefono`, `FechaNacimiento`  
            FROM `Alumnos`
            ORDER BY `Id`';
    $resultado = $con->query($sql);
    $cantidad = $resultado->rowCount();

    $con = null; // Cerrar la conexión

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Listado de Alumnos</h2>

    <form action="" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="cedula" class="form-label">Cédula</label>
            <input type="text" class="form-control" id="cedula" name="cedula" required>
        </div>
        <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="nombres" name="nombres" required>
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Alumno</button>
    </form>

    <p class="text-muted">Cantidad de registros: <?php echo $cantidad; ?></p>

    <table class="table table-bordered table-hover table-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha Nacimiento</th>
            </tr>
        </thead>
        <tbody> 
            <?php foreach ($resultado as $fila): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['Id']); ?></td>
                    <td><?php echo htmlspecialchars($fila['Cedula']); ?></td>
                    <td><?php echo htmlspecialchars($fila['Nombres']); ?></td>
                    <td><?php echo htmlspecialchars($fila['Apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($fila['Correo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['Telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['FechaNacimiento']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
