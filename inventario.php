<?php
// ===============================================
// 1. INICIAR Y PROTEGER LA PÁGINA
// ===============================================
session_start();

// GUARDIA DE SEGURIDAD DE ADMINISTRADOR
// 1. ¿Está logueado?
if ( !isset($_SESSION['usuario_id']) ) {
    header('Location: login.php'); // No está logueado, fuera.
    exit; 
}

// 2. ¿Es Admin?
if ( $_SESSION['rol'] !== 1 ) {
    header('Location: index.php'); // Es cliente, no admin, fuera.
    exit;
}

// ===============================================
// 2. OBTENER LOS DATOS DEL INVENTARIO
// ===============================================
require 'db.php';
$db = conectarDB();

// Esta consulta junta las dos tablas para tener la info completa
$query = "
    SELECT 
        P.nombre, 
        V.talla, 
        V.precio, 
        V.stock 
    FROM Productos AS P
    JOIN Variantes AS V ON P.id = V.producto_id
    ORDER BY P.nombre, V.talla
";

$stmt = $db->query($query);
$inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===============================================
// 3. RENDERIZADO DEL HTML (LA VISTA)
// ===============================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="build/css/app.css">
    
    <style>
        table { width: 80%; margin: 2rem auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <header>
        <h1>Panel de Inventario</h1>
        <a href="index.php">Volver al Inicio</a> |
        <a href="logout.php">Cerrar Sesión</a>
    </header>

    <main>
        <h2>Inventario Actual</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($inventario)): ?>
                    <tr>
                        <td colspan="4">No hay productos en el inventario.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inventario as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['talla']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['precio']); ?></td>
                            <td><?php echo htmlspecialchars($item['stock']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

</body>
</html>