-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-03-2025 a las 18:32:57
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kiosco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `comprobante` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio_total` decimal(8,2) NOT NULL,
  `proveedor_id` int NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proveedor_id` (`proveedor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `fecha`, `comprobante`, `precio_total`, `proveedor_id`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(4, '2025-03-26', 'factura 200', 3302.00, 13, '2025-03-26 14:23:13', NULL),
(5, '2025-03-26', 'factura 200', 24.00, 13, '2025-03-26 14:27:46', NULL),
(6, '2025-03-26', 'factura 200', 12.00, 13, '2025-03-26 14:29:19', NULL);

-- --------------------------------------------------------

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `comprobante` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `cliente_id` int NOT NULL,
  `total_pagado` int NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_ventas`
--

INSERT INTO `ventas` (`id`,  `fecha`, `comprobante`, `cliente_id`, `total_pagado`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(4, '2025-03-26', 'factura 200', 5, 405, '2024-10-23 07:47:01', '0000-00-00 00:00:00'),
(5,  '2025-03-26', 'factura 200', 5, 810, '2024-10-23 08:56:20', '0000-00-00 00:00:00'),
(6, '2025-03-26', 'factura 200', 6, 810, '2024-11-08 20:56:38', '0000-00-00 00:00:00');

-- --------------------------------------------------------


--
-- Estructura de tabla para la tabla `detalle_compras`
--

DROP TABLE IF EXISTS `detalle_compras`;
CREATE TABLE IF NOT EXISTS `detalle_compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int NOT NULL,
  `compra_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compra_id` (`compra_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `cantidad`, `compra_id`, `producto_id`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(1, 6, 4, 5, NULL, NULL),
(2, 6, 4, 6, NULL, NULL),
(3, 2, 4, 7, NULL, NULL),
(4, 2, 5, 5, '2025-03-26 14:27:46', NULL),
(5, 1, 6, 5, '2025-03-26 14:29:19', NULL);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `detalle_ventas`
--

DROP TABLE IF EXISTS `detalle_ventas`;
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int NOT NULL,
  `venta_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_ventas` (`id`, `cantidad`, `venta_id`, `producto_id`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(1, 6, 4, 5, NULL, NULL),
(2, 6, 4, 6, NULL, NULL),
(3, 2, 4, 7, NULL, NULL),
(4, 2, 5, 5, '2025-03-26 14:27:46', NULL),
(5, 1, 6, 5, '2025-03-26 14:29:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmp_compras`
--

DROP TABLE IF EXISTS `tmp_ventas`;
CREATE TABLE IF NOT EXISTS `tmp_ventas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int NOT NULL,
  `producto_id` int NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;



--
-- Estructura de tabla para la tabla `tmp_compras`
--

DROP TABLE IF EXISTS `tmp_compras`;
CREATE TABLE IF NOT EXISTS `tmp_compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int NOT NULL,
  `producto_id` int NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fyh_creacion` datetime DEFAULT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


--
-- Estructura de tabla para la tabla `tb_almacen`
--

DROP TABLE IF EXISTS `tb_almacen`;
CREATE TABLE IF NOT EXISTS `tb_almacen` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `stock` int NOT NULL,
  `stock_minimo` int DEFAULT NULL,
  `stock_maximo` int DEFAULT NULL,
  `precio_compra` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `precio_venta` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `imagen` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `id_usuario` int NOT NULL,
  `id_categoria` int NOT NULL,
  `id_proveedor` int NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_categoria` (`id_categoria`),
  KEY `fk_tb_almacen_tb_proveedores` (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_almacen`
--

INSERT INTO `tb_almacen` (`id_producto`, `codigo`, `nombre`, `descripcion`, `stock`, `stock_minimo`, `stock_maximo`, `precio_compra`, `precio_venta`, `fecha_ingreso`, `imagen`, `id_usuario`, `id_categoria`, `id_proveedor`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(5, '123', 'coca cola', 'de 3 litros', 30, 1, 100, '300', '12', '2024-10-23', '2025-03-26-11-27-40__logo.jpg', 7, 12, 12, '2024-10-23 06:51:14', '2025-03-26 11:27:40'),
(6, '456', 'yerba', 'aa', 20, 1, 50, '300', '405.00', '2024-10-23', '2024-10-23-03-04-30__', 7, 12, 13, '2024-10-23 15:04:30', '0000-00-00 00:00:00'),
(7, 'P-00003', 'Arroz', '', 12, 0, 19, '250', '400', '2024-10-30', '2024-10-30-03-57-23__', 7, 12, 13, '2024-10-30 15:57:23', '0000-00-00 00:00:00');

-- 
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_categorias`
--

DROP TABLE IF EXISTS `tb_categorias`;
CREATE TABLE IF NOT EXISTS `tb_categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_categorias`
--

INSERT INTO `tb_categorias` (`id_categoria`, `nombre_categoria`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(12, 'aa', '2024-10-23 06:50:25', '2024-11-06 14:10:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_clientes`
--

DROP TABLE IF EXISTS `tb_clientes`;
CREATE TABLE IF NOT EXISTS `tb_clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre_cliente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `nit_ci_cliente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `celular_cliente` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `email_cliente` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_clientes`
--

INSERT INTO `tb_clientes` (`id_cliente`, `nombre_cliente`, `nit_ci_cliente`, `celular_cliente`, `email_cliente`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(5, 'Lucas', '', '', '', '2024-10-23 07:46:41', '0000-00-00 00:00:00'),
(6, 'Adrian', '23402340234', '342', 'aa@fd.com', '2024-10-23 15:03:32', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_compras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_proveedores`
--

DROP TABLE IF EXISTS `tb_proveedores`;
CREATE TABLE IF NOT EXISTS `tb_proveedores` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre_proveedor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `celular` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `empresa` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_proveedores`
--

INSERT INTO `tb_proveedores` (`id_proveedor`, `nombre_proveedor`, `celular`, `telefono`, `empresa`, `email`, `direccion`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(12, 'aa', '12', '22', 'sad', 'aa', 'aa', '2024-10-23 06:50:05', '0000-00-00 00:00:00'),
(13, 'DistriNor', '364430202', '641', 'nor', 'a@gmail.com', 'aa', '2024-10-23 15:02:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_roles`
--

DROP TABLE IF EXISTS `tb_roles`;
CREATE TABLE IF NOT EXISTS `tb_roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_roles`
--

INSERT INTO `tb_roles` (`id_rol`, `rol`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(1, 'ADMINISTRADOR', '2023-01-23 23:15:19', '2024-11-06 14:01:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

DROP TABLE IF EXISTS `tb_usuarios`;
CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `dni` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `password_user` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `id_rol` int NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombres`, `dni`, `email`, `password_user`, `token`, `id_rol`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(7, 'Agus', '42.578.525', 'agus@gmail.com', '1234', '', 1, '2024-10-23 05:44:58', '2024-10-23 08:44:14'),
(11, 'exe', '12.345.678', 'exe@gmail.com', '1234', '', 1, '2024-11-06 13:54:50', '2024-11-06 13:55:49');

-- --------------------------------------------------------

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `tb_proveedores` (`id_proveedor`) ;

--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `tb_clientes` (`id_cliente`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `tb_almacen` (`id_producto`) ON DELETE CASCADE;
--  
-- Filtros para la tabla `detalle_ventas`
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `tb_almacen` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_almacen`
--
ALTER TABLE `tb_almacen`
  ADD CONSTRAINT `fk_tb_almacen_tb_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `tb_proveedores` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_almacen_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `tb_categorias` (`id_categoria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_almacen_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_carrito`
--
ALTER TABLE `tmp_ventas`
  ADD CONSTRAINT `tmp_ventas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `tb_almacen` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tmp_compras`
--
ALTER TABLE `tmp_compras`
  ADD CONSTRAINT `tmp_compras_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `tb_almacen` (`id_producto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
