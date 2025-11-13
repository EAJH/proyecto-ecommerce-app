/* * 02-DATA.SQL
 * Este script llena las tablas con datos de ejemplo.
 */

-- 1. Selecciona la base de datos
USE tsic2;

-- 2. Insertar Usuarios de Ejemplo
INSERT INTO Clientes (email, passwd, rol) 
VALUES 
('admin@tienda.com', 'admin123', 1),  -- Usuario Admin
('cliente@correo.com', 'cliente123', 0); -- Usuario Cliente


INSERT INTO Productos (nombre, imagen) 
VALUES 
('Playera azul', 'src/img/playeras/playeraAzul.jpg'), 
('Playera Deportiva 1', 'src/img/playeras/playeraDeportiva1.jpeg'),
('Playera Elegante 1', 'src/img/playeras/playeraElegante1.jpeg'),
('Playera Elegante 2', 'src/img/playeras/playeraElegante2.jpeg'),
('Playera negra', 'src/img/playeras/playeraNegra.jpeg'),
('Playera roja', 'src/img/playeras/playeraRoja.jpeg'),
('Chamarra negra con cuero', 'src/img/chamarras/chamarraNegraHombre.jpeg'),
('Chamarra verde abrigada', 'src/img/chamarras/chamarraVerdeHombre.jpeg'),
('Chamarra vino impermeable', 'src/img/chamarras/chamarraVinoHombre.jpeg'),
('Pantalon de mezclilla clasico', 'src/img/pantalones/mezclillaAzulHombre.jpeg'),
('Pantalon crema estilo pants', 'src/img/pantalones/pantalonCremaHombre.jpeg'),
('Pantalon verde multibolsa', 'src/img/pantalones/pantalonVerdeHombre.jpeg');


INSERT INTO Variantes (producto_id, talla, precio, stock)
VALUES
(1, 'Chica', 150.00, 50),
(1, 'Mediana', 150.00, 50),
(1, 'Grande', 150.00, 50),
(2, 'Chica', 250.00, 50),
(2, 'Mediana', 250.00, 50),
(2, 'Grande', 250.00, 50),
(3, 'Chica', 200.00, 50),
(3, 'Mediana', 200.00, 50),
(3, 'Grande', 200.00, 50),
(4, 'Chica', 200.00, 50),
(4, 'Mediana', 200.00, 50),
(4, 'Grande', 200.00, 50),
(5, 'Chica', 150.00, 50),
(5, 'Mediana', 150.00, 50),
(5, 'Grande', 150.00, 50),
(6, 'Chica', 150.00, 50),
(6, 'Mediana', 150.00, 50),
(6, 'Grande', 150.00, 50),
(7, 'Chica', 500.00, 50),
(7, 'Mediana', 500.00, 50),
(7, 'Grande', 500.00, 50),
(8, 'Chica', 600.00, 50),
(8, 'Mediana', 600.00, 50),
(8, 'Grande', 600.00, 50),
(9, 'Chica', 400.00, 50),
(9, 'Mediana', 400.00, 50),
(9, 'Grande', 400.00, 50),
(10, 'Chica', 250.00, 50),
(10, 'Mediana', 250.00, 50),
(10, 'Grande', 250.00, 50),
(11, 'Chica', 350.00, 50),
(11, 'Mediana', 350.00, 50),
(11, 'Grande', 350.00, 50),
(12, 'Chica', 200.00, 50),
(12, 'Mediana', 200.00, 50),
(12, 'Grande', 200.00, 50);

