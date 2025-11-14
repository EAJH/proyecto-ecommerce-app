<?php
// ===============================================
// 1. INICIAR Y CONECTAR
// ===============================================
session_start();
require 'db.php';
$db = conectarDB();

// ===============================================
// 2. LEER DATOS DE LOS FORMULARIOS (GET)
// ===============================================
// Leemos los valores de la URL, si no existen, los dejamos vacíos.
$filtro_categoria = $_GET['categoria'] ?? '';
$busqueda_nombre = $_GET['busqueda'] ?? '';

// ===============================================
// 3. CONSTRUIR LA CONSULTA DINÁMICA
// ===============================================
$sql = "SELECT * FROM Productos WHERE 1=1";
$params = []; // Array para los valores

// AÑADIR FILTRO DE CATEGORÍA
// El usuario puede seleccionar "Playeras" Y buscar "azul"
if (!empty($filtro_categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $filtro_categoria;
}

// AÑADIR FILTRO DE BÚSQUEDA
if (!empty($busqueda_nombre)) {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%" . $busqueda_nombre . "%";
}

// Preparar y ejecutar la consulta
$stmt = $db->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de ropa. Productos</title>
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    
    <header class="header inicio">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="index.php" class="logo">
                   <img src="build/img/iconos/logo.webp" alt="Logo de la tienda">
                </a>
                <h1>Tienda de ropa Online para hombres</h1>
                <nav class="navegacion">
                    <div class="iconos-header">
                        <div class="icono">
                            <a href="carrito.php">
                                <img src="build/img/iconos/carrito-compras.svg" alt="Carrito de compras" loading="lazy">
                            </a>
                        </div>
                        <div class="icono">
                            <a href="inventario.php">
                                <img src="build/img/iconos/estrella.svg" alt="Inventario" loading="lazy">
                            </a>
                        </div>
                        <div class="icono">
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <a href="logout.php" title="Cerrar Sesión">
                                    <img src="build/img/iconos/user-logout.svg" alt="Logout" loading="lazy">
                                </a>
                            <?php else: ?>
                                <a href="login.php" title="Iniciar Sesión">
                                    <img src="build/img/iconos/user.svg" alt="Login" loading="lazy">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav> 
            </div>
        </div>
        <p class="slogan">Tu estilo, a un clic.</p>
    </header>

    <section class="contenido-barra-busqueda">
        
        <form action="productos.php" method="GET" class="form-busqueda-combinada">
            
            <div class="campo-filtro">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria">
                    <option value="">-- Todas --</option>
                    <option value="Playeras" <?php echo ($filtro_categoria === 'Playeras') ? 'selected' : ''; ?>>Playeras</option>
                    <option value="Chamarras" <?php echo ($filtro_categoria === 'Chamarras') ? 'selected' : ''; ?>>Chamarras</option>
                    <option value="Pantalones" <?php echo ($filtro_categoria === 'Pantalones') ? 'selected' : ''; ?>>Pantalones</option>
                </select>
            </div>

            <div class="campo-busqueda">
                <label for="busqueda">Producto</label>
                <input type="text" placeholder="Buscar por nombre..." id="busqueda" name="busqueda" value="<?php echo htmlspecialchars($busqueda_nombre); ?>">
            </div>

            <input type="submit" value="Buscar" class="boton-buscar boton boton-verde">
        
        </form>

    </section>

    <main class="main-productos">
        <h2>Productos en venta</h2>
        <div class="contenedor-productos">
            
            <?php if (empty($productos)): ?>
                <p>No se encontraron productos que coincidan con tu búsqueda.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    
                    <?php
                    // Obtenemos las variantes (tallas y precios) para este producto
                    $stmt_variantes = $db->prepare("
                        SELECT talla, precio, id AS variante_id 
                        FROM Variantes 
                        WHERE producto_id = ? 
                        ORDER BY precio ASC
                    ");
                    $stmt_variantes->execute([$producto['id']]);
                    $variantes = $stmt_variantes->fetchAll(PDO::FETCH_ASSOC);

                    // Obtenemos el precio más bajo para mostrarlo
                    $precio_display = !empty($variantes) ? $variantes[0]['precio'] : 0.00;
                    
                    // ===============================================
                    // LÓGICA DE RUTAS DE IMAGEN (CORREGIDA)
                    // ===============================================
                    // 1. La ruta de la imagen desde la BD (ej. 'playeras/playera_azul.jpg')
                    $ruta_imagen_db = htmlspecialchars($producto['imagen']);
                    
                    // 2. Quitamos la extensión .jpg o .jpeg para poder añadir .webp
                    $ruta_sin_extension = str_replace(['.jpg', '.jpeg'], '', $ruta_imagen_db);
                    
                    // 3. Creamos las rutas finales
                    $ruta_webp = "build/img/" . $ruta_sin_extension . ".webp";
                    $ruta_original = "build/img/" . $ruta_imagen_db;
                    ?>

                    <div class="producto">
                        <picture>
                            <source srcset="<?php echo $ruta_webp; ?>" type="image/webp">
                            <source srcset="<?php echo $ruta_original; ?>" type="image/jpeg">
                            <img loading="lazy" src="<?php echo $ruta_original; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        </picture>
                        <div class="contenido-producto">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['descripcion'] ?? 'Descripción no disponible'); ?></p>
                            <p class="precio">$<?php echo htmlspecialchars($precio_display); ?></p>
                            
                            <form action="agregar_carrito.php" method="POST">
                                <label for="talla-<?php echo $producto['id']; ?>">Talla:</label>
                                <select name="variante_id" id="talla-<?php echo $producto['id']; ?>" required>
                                    <option value="" disabled selected>-- Seleccionar Talla --</option>
                                    <?php foreach ($variantes as $variante): ?>
                                        <option value="<?php echo $variante['variante_id']; ?>">
                                            <?php echo htmlspecialchars($variante['talla']); ?> ($<?php echo htmlspecialchars($variante['precio']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="submit" value="Añadir al carrito" class="boton boton-amarillo">
                            </form>
                        </div> 
                    </div> 
                <?php endforeach; ?>
            <?php endif; ?>

        </div> </main>

    <footer class="footer">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>