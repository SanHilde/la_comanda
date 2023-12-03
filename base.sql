-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2023 a las 00:20:10
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `la_comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `pedido` varchar(6) NOT NULL,
  `mesa` int(11) DEFAULT NULL,
  `restaurante` int(11) DEFAULT NULL,
  `mozo` int(11) DEFAULT NULL,
  `cocinero` int(11) DEFAULT NULL,
  `comentarios` varchar(66) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id`, `pedido`, `mesa`, `restaurante`, `mozo`, `cocinero`, `comentarios`) VALUES
(2, '7BODQ', 2, 2, 3, 1, 'horrible todo'),
(3, 'S4JX1', 5, 5, 5, 5, 'normal todo'),
(5, 'FMDWI', 8, 8, 9, 10, 'buenisimo todo'),
(6, 'ZF2X4', 8, 7, 7, 7, 'normal todo'),
(7, 'IU2K1', 1, 10, 10, 10, 'Me encanto!!'),
(8, 'S0QDV', 7, 8, 6, 10, 'Volveria a venir');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `accion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id`, `user`, `fecha`, `accion`) VALUES
(1, 'Santiago Hildebrandt', '2023-12-04 00:57:20', 'LogIn'),
(2, NULL, '2023-12-04 01:43:13', 'Traer mejores comentarios'),
(3, NULL, '2023-12-04 01:44:05', 'Traer mejores comentarios'),
(4, NULL, '2023-12-04 01:46:05', 'Traer mejores comentarios'),
(5, 'Santiago Hildebrandt', '2023-12-04 01:46:27', 'Traer mejores comentarios'),
(6, 'mozo', '2023-12-04 01:55:59', 'LogIn'),
(7, 'mozo', '2023-12-04 01:56:31', 'Cargar un pedido'),
(8, 'mozo', '2023-12-04 01:56:47', 'Cargar un pedido'),
(9, 'mozo', '2023-12-04 01:56:56', 'Cargar un pedido'),
(10, 'mozo', '2023-12-04 01:58:05', 'Modificar una mesa'),
(11, 'cervecero', '2023-12-04 01:59:30', 'LogIn'),
(12, 'cervecero', '2023-12-04 01:59:46', 'Traer todos los pedidos'),
(13, 'cervecero', '2023-12-04 02:00:42', 'Modificar un pedido'),
(14, 'cervecero', '2023-12-04 02:00:53', 'Modificar un pedido'),
(15, 'cervecero', '2023-12-04 02:01:26', 'Traer todos los pedidos'),
(16, 'cervecero', '2023-12-04 02:02:22', 'Modificar un pedido'),
(17, 'cervecero', '2023-12-04 02:02:31', 'Modificar un pedido'),
(18, 'cocinero', '2023-12-04 02:03:10', 'LogIn'),
(19, 'cocinero', '2023-12-04 02:03:30', 'Traer todos los pedidos'),
(20, 'cocinero', '2023-12-04 02:03:45', 'Modificar un pedido'),
(21, 'cocinero', '2023-12-04 02:03:54', 'Traer todos los pedidos'),
(22, 'cocinero', '2023-12-04 02:04:15', 'Modificar un pedido'),
(23, 'mozo', '2023-12-04 02:06:18', 'Modificar una mesa'),
(24, 'Santiago Hildebrandt', '2023-12-04 02:08:32', 'Traer todos los pedidos'),
(25, 'mozo', '2023-12-04 02:10:24', 'Traer todos los pedidos'),
(26, 'mozo', '2023-12-04 02:10:58', 'Modificar una mesa'),
(27, 'Santiago Hildebrandt', '2023-12-04 02:11:36', 'Traer todas las mesas'),
(28, 'mozo', '2023-12-04 02:12:29', 'Calcular cuenta'),
(29, 'Santiago Hildebrandt', '2023-12-04 02:13:52', 'Modificar una mesa'),
(30, 'Santiago Hildebrandt', '2023-12-04 02:15:27', 'Traer mejores comentarios'),
(31, 'Santiago Hildebrandt', '2023-12-04 02:15:52', 'Traer mesa mas usada'),
(32, 'Santiago Hildebrandt', '2023-12-04 02:17:31', 'Traer mesa mas usada'),
(33, 'Santiago Hildebrandt', '2023-12-04 02:18:47', 'Traer mesa mas usada'),
(34, 'Santiago Hildebrandt', '2023-12-04 02:20:43', 'Traer mesa mas usada'),
(35, 'Santiago Hildebrandt', '2023-12-04 02:21:10', 'Traer mesa mas usada'),
(36, 'Santiago Hildebrandt', '2023-12-04 02:22:55', 'Traer mesa mas usada'),
(37, 'Santiago Hildebrandt', '2023-12-04 02:24:23', 'Traer mesa mas usada'),
(38, 'cocinero', '2023-12-04 02:25:04', 'Modificar un pedido'),
(39, 'cocinero', '2023-12-04 02:26:44', 'Modificar un pedido'),
(40, 'mozo', '2023-12-04 02:28:33', 'Modificar una mesa'),
(41, 'Santiago Hildebrandt', '2023-12-04 02:32:04', 'Traer todos los pedidos en tiempo'),
(42, 'Santiago Hildebrandt', '2023-12-04 02:32:04', 'Traer todos los pedidos'),
(43, 'Santiago Hildebrandt', '2023-12-04 02:32:19', 'Traer todos los pedidos en tiempo'),
(44, 'Santiago Hildebrandt', '2023-12-04 02:32:19', 'Traer todos los pedidos'),
(45, 'Santiago Hildebrandt', '2023-12-04 02:32:37', 'Descargar PDF'),
(46, 'Santiago Hildebrandt', '2023-12-04 03:19:10', 'Traer todas las encuestas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(40) DEFAULT NULL,
  `mozo` int(5) DEFAULT NULL,
  `pedido` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `mozo`, `pedido`) VALUES
(1, 'con cliente comiendo', 10, 'S0QDV'),
(2, 'con clientes comiendo', 10, 'ASD22'),
(3, 'con clientes pagando', 10, 'HYT88'),
(4, 'con clientes esperando pedido', 4, 'ASD22'),
(5, 'cerrada', 4, '-'),
(6, 'con cliente comiendo', 10, '0IEXR'),
(7, 'con clientes comiendo', 4, 'ASW23'),
(8, 'cerrada', 0, '-');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) NOT NULL,
  `producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `mesa` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` varchar(50) NOT NULL,
  `sector` varchar(15) NOT NULL,
  `tiempoPreparacion` int(5) NOT NULL,
  `tiempoEntrega` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `producto`, `cantidad`, `mesa`, `mozo`, `fecha`, `estado`, `sector`, `tiempoPreparacion`, `tiempoEntrega`) VALUES
(58, 'S4JX1', 5, 2, 1, 10, '2023-11-18 22:07:47', 'servido', 'bartender', 2, 0),
(59, 'S4JX1', 2, 1, 1, 10, '2023-11-18 22:08:08', 'servido', 'cocina', 10, 2),
(67, '7BODQ', 1, 2, 6, 10, '2023-11-20 14:41:17', 'servido', 'cocina', 10, 0),
(68, '7BODQ', 5, 2, 6, 10, '2023-11-20 14:41:38', 'servido', 'bartender', 5, 5),
(69, 'FMDWI', 5, 2, 6, 10, '2023-11-20 16:37:59', 'cancelado', 'bartender', 10, 0),
(70, 'FMDWI', 5, 2, 6, 10, '2023-11-20 16:40:59', 'servido', 'bartender', 20, 7),
(72, 'ZF2X4', 5, 1, 6, 10, '2023-11-27 21:02:00', 'entregado', 'bartender', 10, 2),
(73, 'ZF2X4', 11, 1, 6, 10, '2023-11-27 20:23:01', 'entregado', 'cervecero', 2, 0),
(74, 'ZF2X4', 32, 1, 6, 10, '2023-11-27 20:23:06', 'entregado', 'cocina', 15, 0),
(75, 'ZF2X4', 33, 2, 6, 10, '2023-11-27 20:23:48', 'entregado', 'cocina', 15, 0),
(79, '0IEXR', 33, 1, 1, 10, '2023-12-01 12:24:23', 'entregado', 'cervecero', 15, 4),
(80, '0IEXR', 32, 1, 1, 10, '2023-11-30 21:22:44', 'entregado', 'cocina', 10, -1),
(81, 'IU2K1', 11, 2, 1, 10, '2023-12-02 00:07:52', 'entregado', 'cocina', 15, 11),
(82, 'IU2K1', 33, 2, 1, 10, '2023-12-02 00:08:48', 'entregado', 'cervecero', 5, 1),
(83, 'S0QDV', 33, 2, 1, 10, '2023-12-03 23:00:42', 'entregado', 'cervecero', 5, 3),
(84, 'S0QDV', 33, 2, 1, 10, '2023-12-03 23:00:53', 'entregado', 'cervecero', 5, 3),
(85, 'S0QDV', 32, 1, 1, 10, '2023-12-03 23:03:45', 'entregado', 'cocina', 5, -18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `sector` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `precio`, `sector`) VALUES
(1, 'Hamburguesa', 8.99, 'cocina'),
(2, 'Pizza', 5.00, 'cocina'),
(3, 'Vino', 15.99, 'bartender'),
(4, 'Cerveza', 4.99, 'cervecero'),
(5, 'Daiquiri', 7.99, 'bartender'),
(6, 'Ensalada', 6.99, 'cocina'),
(7, 'Sushi', 12.99, 'cocina'),
(8, 'Refresco', 2.99, 'bartender'),
(9, 'Papas fritas', 5.55, 'cocina'),
(10, 'Torta de coco', 8.50, 'candybar'),
(11, 'Hamburguesa de garbanzos', 4.99, 'cocina'),
(32, 'Milanesa a caballo', 5.99, 'cocina'),
(33, 'Cerveza Corona', 2.99, 'cervecero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `clave` varchar(50) DEFAULT NULL,
  `sector` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`, `sector`) VALUES
(1, 'Santiago Hildebrandt', 'clave1', 'socio'),
(2, 'candybar', 'candybar', 'candybar'),
(3, 'Ricardo Gonzalez', 'clave5', 'cocina'),
(4, 'David Ramirez', 'clave7', 'mozo'),
(5, 'Carlos Perez', 'clave3', 'cervecero'),
(6, 'Juan Martinez', 'clave1', 'bartender'),
(7, 'cocinero', 'cocinero', 'cocina'),
(8, 'bartender', 'bartender', 'bartender'),
(9, 'cervecero', 'cervecero', 'cervecero'),
(10, 'mozo', 'mozo', 'mozo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
