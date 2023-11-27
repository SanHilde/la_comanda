-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2023 a las 21:34:26
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
(1, 'con cliente esperando pedido', 10, 'NHY23'),
(2, 'con clientes comiendo', 10, 'ASD22'),
(3, 'con clientes pagando', 10, 'HYT88'),
(4, 'con clientes esperando pedido', 4, 'ASD22'),
(5, 'cerrada', 4, '-'),
(6, 'cerrada', 0, '-'),
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
  `tiempoPreparacion` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `producto`, `cantidad`, `mesa`, `mozo`, `fecha`, `estado`, `sector`, `tiempoPreparacion`) VALUES
(58, 'S4JX1', 5, 2, 1, 10, '2023-11-18 22:07:47', 'servido', 'bartender', 2),
(59, 'S4JX1', 2, 1, 4, 4, '2023-11-18 22:08:08', 'servido', 'cocina', 10),
(67, '7BODQ', 1, 2, 6, 10, '2023-11-20 14:41:17', 'servido', 'cocina', 10),
(68, '7BODQ', 5, 2, 6, 10, '2023-11-20 14:41:38', 'servido', 'bartender', 0),
(69, 'FMDWI', 5, 2, 6, 10, '2023-11-20 16:37:59', 'servido', 'bartender', 10),
(70, 'FMDWI', 5, 2, 6, 10, '2023-11-20 16:40:59', 'servido', 'bartender', 20),
(72, 'ZF2X4', 5, 1, 6, 10, '2023-11-27 21:02:00', 'listo para servir', 'bartender', 10),
(73, 'ZF2X4', 33, 1, 6, 10, '2023-11-27 20:23:01', 'pendiente', 'cervecero', 0),
(74, 'ZF2X4', 32, 1, 6, 10, '2023-11-27 20:23:06', 'pendiente', 'cocina', 0),
(75, 'ZF2X4', 11, 2, 6, 10, '2023-11-27 20:23:48', 'pendiente', 'cocina', 0);

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
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
