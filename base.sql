-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2023 a las 21:45:42
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
  `estado` varchar(20) DEFAULT NULL,
  `mozo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `mozo`) VALUES
(1, 'Disponible', 1),
(2, 'Ocupada', 2),
(3, 'Reservada', 3),
(4, 'Disponible', 4),
(5, 'Ocupada', 5),
(6, 'Disponible', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` int(11) DEFAULT NULL,
  `producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `mesa` int(11) DEFAULT NULL,
  `mozo` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `producto`, `cantidad`, `mesa`, `mozo`, `fecha`, `estado`) VALUES
(1, 101, 101, 2, 1, 1, '2023-11-05 13:00:00', 'Pendiente'),
(2, 102, 102, 1, 2, 3, '2023-11-05 13:30:00', 'En preparación'),
(3, 103, 103, 1, 3, 2, '2023-11-05 18:45:00', 'Servido'),
(4, 104, 104, 4, 4, 4, '2023-11-05 19:15:00', 'Entregado'),
(5, 105, 105, 2, 5, 6, '2023-11-05 20:30:00', 'Pendiente'),
(6, 106, 106, 1, 2, 3, '2023-11-05 14:45:00', 'En preparación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigoProducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`codigoProducto`, `descripcion`, `tipo`) VALUES
(101, 'Hamburguesa con queso', 'Comida'),
(102, 'Pizza Margarita', 'Comida'),
(103, 'Vino tinto', 'Bebida'),
(104, 'Cerveza IPA', 'Bebida'),
(105, 'Daiquiri de fresa', 'Bebida'),
(106, 'Ensalada César', 'Comida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `clave` varchar(50) DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`, `tipo`, `sector`) VALUES
(1, 'Juan Martínez', 'clave1', 'bartender', 'Bar'),
(2, 'Laura Rodríguez', 'clave2', 'bartender', 'Bar'),
(3, 'Carlos Pérez', 'clave3', 'cerveceros', 'Bar'),
(4, 'María López', 'clave4', 'cerveceros', 'Bar'),
(5, 'Ricardo González', 'clave5', 'cocinero', 'Cocina'),
(6, 'Ana Sánchez', 'clave6', 'cocinero', 'Cocina'),
(7, 'David Ramírez', 'clave7', 'mozo', 'Salón'),
(8, 'Patricia Torres', 'clave8', 'mozo', 'Salón');

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
  ADD PRIMARY KEY (`codigoProducto`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
