-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-07-2023 a las 00:27:13
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
-- Base de datos: `db_sistemacreditos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_movimiento` (`id_movimiento` BIGINT)   BEGIN
        DECLARE sp_idCuenta Bigint;
        DECLARE sp_saldoActual DECIMAL(10,2);
        DECLARE sp_movimiento int;
        DECLARE sp_montoMovimiento DECIMAL(10,2);
        DECLARE sp_nuevoSaldo DECIMAL(10,2);

        SELECT cuentaid, movimiento, monto INTO sp_idCuenta, sp_movimiento, sp_montoMovimiento FROM movimiento WHERE idmovimiento = id_movimiento;
        SELECT saldo INTO sp_saldoActual FROM cuenta WHERE idcuenta = sp_idCuenta;

        IF sp_movimiento = 1 OR sp_movimiento = 2 THEN
            IF sp_movimiento = 1 THEN
                SET sp_nuevoSaldo = sp_saldoActual + sp_montoMovimiento;
            ELSE
                SET sp_nuevoSaldo = sp_saldoActual - sp_montoMovimiento;
            END IF;
            UPDATE movimiento SET status = 0 WHERE idmovimiento = id_movimiento;
            UPDATE cuenta SET saldo = sp_nuevoSaldo WHERE idcuenta = sp_idCuenta;
            SELECT idcuenta,saldo FROM cuenta WHERE idcuenta = sp_idCuenta;
        END IF;
        
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` bigint(20) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `nombrefiscal` varchar(200) DEFAULT NULL,
  `direccionfiscal` varchar(200) DEFAULT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `identificacion`, `nombres`, `apellidos`, `telefono`, `email`, `direccion`, `nit`, `nombrefiscal`, `direccionfiscal`, `datecreated`, `status`) VALUES
(1, '2000', 'Marcial', 'Francisco Nicolas', 7461160077, 'marcialf473@gmail.com', 'Calle Hernan Cortes', '126543232', 'Marcial FN', 'Venustiano Carranza', '2023-07-14 15:16:11', 1),
(2, '1000', 'Gabriel', 'Velazquez Maldonado', 7821136543, 'gabriel@gmail.com', 'Calle Hernan Cortes', '12421', 'Gabriel VM', 'Venustiano Carranza', '2023-07-14 15:17:42', 1),
(3, '3000', 'Erick Ivan', 'Fernandez Salazar', 7821129809, 'erick@gmail.com', 'Centro', '98971', 'Ivan FS', 'Poza Rica', '2023-07-14 15:19:07', 1),
(4, '4000', 'Dora Elena', 'Galvan Ventura', 7821600912, 'dora.galvan@gmail.com', 'Calle 5 de mayo', '6254', 'Dora GV', 'Poza Rica', '2023-07-14 15:20:25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `idcuenta` bigint(20) NOT NULL,
  `clienteid` bigint(20) NOT NULL,
  `productoid` bigint(20) NOT NULL,
  `frecuenciaid` bigint(20) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `cuotas` int(11) NOT NULL,
  `monto_cuotas` decimal(10,2) NOT NULL,
  `cargo` decimal(10,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`idcuenta`, `clienteid`, `productoid`, `frecuenciaid`, `monto`, `cuotas`, `monto_cuotas`, `cargo`, `saldo`, `datecreated`, `status`) VALUES
(1, 1, 2, 2, 3400.00, 10, 340.00, 3400.00, 3450.00, '2023-07-21 12:10:12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frecuencia`
--

CREATE TABLE `frecuencia` (
  `idfrecuencia` bigint(20) NOT NULL,
  `frecuencia` varchar(200) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `frecuencia`
--

INSERT INTO `frecuencia` (`idfrecuencia`, `frecuencia`, `datecreated`, `status`) VALUES
(1, 'Anual', '2023-07-20 11:21:45', 1),
(2, 'Quincenal', '2023-07-20 11:31:23', 1),
(3, 'Mensual', '2023-07-20 11:32:12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

CREATE TABLE `movimiento` (
  `idmovimiento` bigint(20) NOT NULL,
  `cuentaid` bigint(20) NOT NULL,
  `tipomovimientoid` bigint(20) NOT NULL,
  `movimiento` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `descripcion` text NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`idmovimiento`, `cuentaid`, `tipomovimientoid`, `movimiento`, `monto`, `descripcion`, `datecreated`, `status`) VALUES
(1, 1, 1, 1, 100.00, 'Abono recurrente', '2023-07-22 17:23:18', 0),
(2, 1, 3, 2, 50.00, 'Cargo a la cuenta', '2023-07-22 17:24:24', 0);

--
-- Disparadores `movimiento`
--
DELIMITER $$
CREATE TRIGGER `movimiento_A_I` AFTER INSERT ON `movimiento` FOR EACH ROW BEGIN
        DECLARE saldoActual DECIMAL(10,2);
        SELECT saldo into saldoActual FROM cuenta WHERE idcuenta = new.cuentaid;
        if new.movimiento = 1 then
            UPDATE cuenta SET saldo = saldoActual - new.monto WHERE idcuenta = new.cuentaid;
        else
            UPDATE cuenta SET saldo = saldoActual + new.monto WHERE idcuenta = new.cuentaid;
        end if;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL,
  `codigo` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idproducto`, `codigo`, `nombre`, `descripcion`, `precio`, `datecreated`, `status`) VALUES
(1, '9999', 'Mouse Gamer', 'Mouse gamer con luces RGB, comodo para el tacto y sensibilidad perfecta', 780.00, '0000-00-00 00:00:00', 1),
(2, '6666', 'Teclado Gamer', 'Teclado gamer mecanico con luces RGB', 3400.00, '2023-07-15 10:17:32', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_movimiento`
--

CREATE TABLE `tipo_movimiento` (
  `idtipomovimiento` bigint(20) NOT NULL,
  `movimiento` varchar(200) NOT NULL,
  `tipo_movimiento` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_movimiento`
--

INSERT INTO `tipo_movimiento` (`idtipomovimiento`, `movimiento`, `tipo_movimiento`, `descripcion`, `datecreated`, `status`) VALUES
(1, 'Abono', 1, 'Abono recurrente', '2023-07-20 16:08:08', 1),
(2, 'Cargo', 2, 'Cargo a la cuenta', '2023-07-20 19:36:41', 1),
(3, 'Cargo Por Demora', 2, 'Cargo por demora de credito', '2023-07-20 19:38:28', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` bigint(20) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `email`, `password`, `datecreated`, `status`) VALUES
(1, 'Marcial', 'Francisco Nicolas', 'marcialf473@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '2023-07-23 14:34:49', 1),
(2, 'Gabriel', 'Velazquez Maldonado', 'gabriel23@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '2023-07-23 14:41:52', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`idcuenta`),
  ADD KEY `clienteid` (`clienteid`),
  ADD KEY `productoid` (`productoid`),
  ADD KEY `frecuenciaid` (`frecuenciaid`);

--
-- Indices de la tabla `frecuencia`
--
ALTER TABLE `frecuencia`
  ADD PRIMARY KEY (`idfrecuencia`);

--
-- Indices de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD PRIMARY KEY (`idmovimiento`),
  ADD KEY `cuentaid` (`cuentaid`),
  ADD KEY `tipomovimientoid` (`tipomovimientoid`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`);

--
-- Indices de la tabla `tipo_movimiento`
--
ALTER TABLE `tipo_movimiento`
  ADD PRIMARY KEY (`idtipomovimiento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `idcuenta` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `frecuencia`
--
ALTER TABLE `frecuencia`
  MODIFY `idfrecuencia` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  MODIFY `idmovimiento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_movimiento`
--
ALTER TABLE `tipo_movimiento`
  MODIFY `idtipomovimiento` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD CONSTRAINT `cuenta_ibfk_1` FOREIGN KEY (`clienteid`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_ibfk_2` FOREIGN KEY (`productoid`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cuenta_ibfk_3` FOREIGN KEY (`frecuenciaid`) REFERENCES `frecuencia` (`idfrecuencia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD CONSTRAINT `movimiento_ibfk_1` FOREIGN KEY (`tipomovimientoid`) REFERENCES `tipo_movimiento` (`idtipomovimiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movimiento_ibfk_2` FOREIGN KEY (`cuentaid`) REFERENCES `cuenta` (`idcuenta`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
