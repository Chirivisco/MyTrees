-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-11-2024 a las 05:34:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mytrees`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actualizaciones_arbol`
--

CREATE TABLE `actualizaciones_arbol` (
  `ID_ACTUALIZACION` int(11) NOT NULL,
  `ID_ARBOL` int(11) NOT NULL,
  `FECHA_ACTUALIZACION` date NOT NULL,
  `TAMANO` varchar(50) DEFAULT NULL,
  `ESTADO` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arboles`
--

CREATE TABLE `arboles` (
  `ID_ARBOL` int(11) NOT NULL,
  `ESPECIE` int(11) DEFAULT NULL,
  `UBICACION` varchar(255) DEFAULT NULL,
  `PRECIO` int(11) NOT NULL,
  `FOTO_ARBOL` varchar(255) DEFAULT NULL,
  `ESTADO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arboles`
--

INSERT INTO `arboles` (`ID_ARBOL`, `ESPECIE`, `UBICACION`, `PRECIO`, `FOTO_ARBOL`, `ESTADO`) VALUES
(1, 1, 'Turrucares', 15520, '../imagenes/fotos_arboles/arbol_1.jpg', 1),
(2, 2, 'Ciudad Quesada', 44242, '../imagenes/fotos_arboles/arbol_2.jpg', 1),
(3, 3, 'Aguas Zarcas', 41414, '../imagenes/fotos_arboles/arbol_3.jpg', 1),
(4, 5, 'Puntarenas', 1444, '../imagenes/fotos_arboles/arbol_4.jpg', 1),
(5, 6, 'Zarcero', 42424554, '../imagenes/fotos_arboles/arbol_5.jpeg', 1),
(6, 7, 'Ciudad Quesada frente al LSC', 788787987, '../imagenes/fotos_arboles/arbol_6.jpg', 1),
(7, 8, 'Alajuela centro', 658778, '../imagenes/fotos_arboles/arbol_7.png', 1),
(8, 9, 'Puriscal', 9666, '../imagenes/fotos_arboles/arbol_8.jpeg', 1),
(9, 10, 'Pital', 33662, '../imagenes/fotos_arboles/arbol_9.jpg', 1),
(10, 10, 'Barrio nuevo', 9235666, '../imagenes/fotos_arboles/arbol_10.jpg', 2),
(11, 5, 'Curridabat', 78962322, '../imagenes/fotos_arboles/arbol_11.jpg', 1),
(12, 3, 'Turrugalito', 2023335, '../imagenes/fotos_arboles/arbol_12.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arboles_vendidos`
--

CREATE TABLE `arboles_vendidos` (
  `ID_VENTA` int(11) NOT NULL,
  `ID_ARBOL` int(11) DEFAULT NULL,
  `ID_DUENO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arboles_vendidos`
--

INSERT INTO `arboles_vendidos` (`ID_VENTA`, `ID_ARBOL`, `ID_DUENO`) VALUES
(1, 2, 2),
(2, 3, 2),
(3, 4, 2),
(4, 8, 3),
(5, 11, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `ID_CARRITO` int(11) NOT NULL,
  `USUARIO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`ID_CARRITO`, `USUARIO`) VALUES
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_carrito_compras`
--

CREATE TABLE `detalle_carrito_compras` (
  `ID_DETALLE` int(11) NOT NULL,
  `CARRITO` int(11) DEFAULT NULL,
  `ARBOL` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_usuarios`
--

CREATE TABLE `direcciones_usuarios` (
  `ID_DIRECCION` int(11) NOT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `DIRECCION` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones_usuarios`
--

INSERT INTO `direcciones_usuarios` (`ID_DIRECCION`, `ID_USUARIO`, `DIRECCION`) VALUES
(1, 1, '300m norte del plantel municipal de San Carlos, Ciudad Quesada.'),
(2, 2, '100m sur del Colegio Dioscesano'),
(3, 3, '250m este del Pollolandia'),
(4, 4, '1600 Fake Street');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies_arboles`
--

CREATE TABLE `especies_arboles` (
  `ID_ESPECIE` int(11) NOT NULL,
  `NOMBRE_COMERCIAL` varchar(100) NOT NULL,
  `NOMBRE_CIENTIFICO` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especies_arboles`
--

INSERT INTO `especies_arboles` (`ID_ESPECIE`, `NOMBRE_COMERCIAL`, `NOMBRE_CIENTIFICO`) VALUES
(1, 'Matasano', 'Couratari scottmorii'),
(2, 'Ajillo', 'Balizia elegans'),
(3, 'Cedro amargo', 'Cedrela odorata'),
(5, 'Camibar', ' Copaifera camibar'),
(6, 'Cornizuelo', 'Acacia collinsii'),
(7, 'Ron Ron', 'Astronium graveolens'),
(8, 'Zapatero', 'Euphorbiaceae'),
(9, 'Pisquil', 'Albizia carbonaria Britto'),
(10, 'Surá', 'Combretaceae');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `ID_ESTADO` int(11) NOT NULL,
  `ESTADO` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`ID_ESTADO`, `ESTADO`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos_usuarios`
--

CREATE TABLE `telefonos_usuarios` (
  `ID_TELEFONO` int(11) NOT NULL,
  `ID_USUARIO` int(11) DEFAULT NULL,
  `TELEFONO` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefonos_usuarios`
--

INSERT INTO `telefonos_usuarios` (`ID_TELEFONO`, `ID_USUARIO`, `TELEFONO`) VALUES
(1, 1, '83676372'),
(2, 2, '80906520'),
(3, 3, '87245530'),
(4, 4, '6019521325');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `ID_TIPO` int(11) NOT NULL,
  `TIPO` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_usuarios`
--

INSERT INTO `tipos_usuarios` (`ID_TIPO`, `TIPO`) VALUES
(1, 'Admin'),
(2, 'Amigo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_USUARIO` int(11) NOT NULL,
  `TIPO_USUARIO` int(11) DEFAULT NULL,
  `USUARIO` varchar(100) NOT NULL,
  `CONTRASENA` varchar(255) NOT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `APELLIDOS` varchar(100) NOT NULL,
  `PAIS` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_USUARIO`, `TIPO_USUARIO`, `USUARIO`, `CONTRASENA`, `NOMBRE`, `APELLIDOS`, `PAIS`) VALUES
(1, 1, 'j@gmail.com', '$2y$10$akY79s2mQrtrUjdWa1TkueYpE4pxkUwS5a4F43fI9ufKsDSlIdkFi', 'Josue David', 'Alvarez Villalobos', 'Costa Rica'),
(2, 2, 'pepe@gmail.com', '$2y$10$F5aQe815AyD0cf9Madv/qOE9/qtiMnPcMFiLHVIjlu/Zq18bVHujO', 'Pepe', 'Aguilar Sanchez', 'Panama'),
(3, 2, 'carlos@gmail.com', '$2y$10$sP2HsfuD4l3PzU5VJ2c9x.ZsYV/D.CxVgmIq7ZxIamYxZgrZzv2PS', 'Carlos', 'Perez Pereira', 'Costa Rica'),
(4, 2, 'test@example.us', '$2y$10$4q0iER2GKTpjNrq..CVWde0Y.68LY6.cFyUGNUyGxJlIf4yXgpfQu', 'Jon', 'Doe', 'USA');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arboles_admin`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_arboles_admin` (
`ID_ARBOL` int(11)
,`NOMBRE_COMERCIAL` varchar(100)
,`NOMBRE_CIENTIFICO` varchar(100)
,`ESPECIE` varchar(203)
,`UBICACION` varchar(255)
,`PRECIO` int(11)
,`ESTADO` varchar(10)
,`RUTA_FOTO_ARBOL` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arboles_carrito`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_arboles_carrito` (
`ARBOL` int(11)
,`USUARIO` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arboles_disponibles`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_arboles_disponibles` (
`ID_ARBOL` int(11)
,`NOMBRE_COMERCIAL` varchar(100)
,`NOMBRE_CIENTIFICO` varchar(100)
,`ESPECIE` varchar(203)
,`UBICACION` varchar(255)
,`PRECIO` int(11)
,`ESTADO` varchar(10)
,`RUTA_FOTO_ARBOL` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arboles_vendidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_arboles_vendidos` (
`ID_VENTA` int(11)
,`ID_ARBOL` int(11)
,`ID_DUENO` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arbol_info`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_arbol_info` (
`ID_ARBOL` int(11)
,`ESPECIE` varchar(203)
,`UBICACION` varchar(255)
,`PRECIO` int(11)
,`RUTA_FOTO_ARBOL` varchar(255)
,`ESTADO` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuarios_1`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_usuarios_1` (
`ID_USUARIO` int(11)
,`NOMBRE_COMPLETO` varchar(201)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arboles_admin`
--
DROP TABLE IF EXISTS `vista_arboles_admin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arboles_admin`  AS SELECT `a`.`ID_ARBOL` AS `ID_ARBOL`, `e`.`NOMBRE_COMERCIAL` AS `NOMBRE_COMERCIAL`, `e`.`NOMBRE_CIENTIFICO` AS `NOMBRE_CIENTIFICO`, concat(`e`.`NOMBRE_COMERCIAL`,' - ',`e`.`NOMBRE_CIENTIFICO`) AS `ESPECIE`, `a`.`UBICACION` AS `UBICACION`, `a`.`PRECIO` AS `PRECIO`, `est`.`ESTADO` AS `ESTADO`, `a`.`FOTO_ARBOL` AS `RUTA_FOTO_ARBOL` FROM ((`arboles` `a` join `especies_arboles` `e` on(`a`.`ESPECIE` = `e`.`ID_ESPECIE`)) join `estados` `est` on(`a`.`ESTADO` = `est`.`ID_ESTADO`)) ORDER BY `a`.`ID_ARBOL` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arboles_carrito`
--
DROP TABLE IF EXISTS `vista_arboles_carrito`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arboles_carrito`  AS SELECT `dc`.`ARBOL` AS `ARBOL`, `cc`.`USUARIO` AS `USUARIO` FROM (`carrito_compras` `cc` join `detalle_carrito_compras` `dc` on(`cc`.`ID_CARRITO` = `dc`.`CARRITO`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arboles_disponibles`
--
DROP TABLE IF EXISTS `vista_arboles_disponibles`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arboles_disponibles`  AS SELECT `a`.`ID_ARBOL` AS `ID_ARBOL`, `ea`.`NOMBRE_COMERCIAL` AS `NOMBRE_COMERCIAL`, `ea`.`NOMBRE_CIENTIFICO` AS `NOMBRE_CIENTIFICO`, concat(`e`.`NOMBRE_COMERCIAL`,' - ',`e`.`NOMBRE_CIENTIFICO`) AS `ESPECIE`, `a`.`UBICACION` AS `UBICACION`, `a`.`PRECIO` AS `PRECIO`, `est`.`ESTADO` AS `ESTADO`, `a`.`FOTO_ARBOL` AS `RUTA_FOTO_ARBOL` FROM ((((`arboles` `a` join `especies_arboles` `e` on(`a`.`ESPECIE` = `e`.`ID_ESPECIE`)) join `estados` `est` on(`a`.`ESTADO` = `est`.`ID_ESTADO`)) join `especies_arboles` `ea` on(`a`.`ESPECIE` = `ea`.`ID_ESPECIE`)) left join `arboles_vendidos` `av` on(`a`.`ID_ARBOL` = `av`.`ID_ARBOL`)) WHERE `av`.`ID_ARBOL` is null AND `est`.`ESTADO` = 'Activo' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arboles_vendidos`
--
DROP TABLE IF EXISTS `vista_arboles_vendidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arboles_vendidos`  AS SELECT `arboles_vendidos`.`ID_VENTA` AS `ID_VENTA`, `arboles_vendidos`.`ID_ARBOL` AS `ID_ARBOL`, `arboles_vendidos`.`ID_DUENO` AS `ID_DUENO` FROM `arboles_vendidos` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arbol_info`
--
DROP TABLE IF EXISTS `vista_arbol_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arbol_info`  AS SELECT `a`.`ID_ARBOL` AS `ID_ARBOL`, concat(`e`.`NOMBRE_COMERCIAL`,' - ',`e`.`NOMBRE_CIENTIFICO`) AS `ESPECIE`, `a`.`UBICACION` AS `UBICACION`, `a`.`PRECIO` AS `PRECIO`, `a`.`FOTO_ARBOL` AS `RUTA_FOTO_ARBOL`, `es`.`ESTADO` AS `ESTADO` FROM ((`arboles` `a` join `especies_arboles` `e` on(`a`.`ESPECIE` = `e`.`ID_ESPECIE`)) join `estados` `es` on(`a`.`ESTADO` = `es`.`ID_ESTADO`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuarios_1`
--
DROP TABLE IF EXISTS `vista_usuarios_1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios_1`  AS SELECT `usuarios`.`ID_USUARIO` AS `ID_USUARIO`, concat(`usuarios`.`NOMBRE`,' ',`usuarios`.`APELLIDOS`) AS `NOMBRE_COMPLETO` FROM `usuarios` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actualizaciones_arbol`
--
ALTER TABLE `actualizaciones_arbol`
  ADD PRIMARY KEY (`ID_ACTUALIZACION`),
  ADD KEY `ID_ARBOL` (`ID_ARBOL`);

--
-- Indices de la tabla `arboles`
--
ALTER TABLE `arboles`
  ADD PRIMARY KEY (`ID_ARBOL`),
  ADD KEY `fk_estado` (`ESTADO`);

--
-- Indices de la tabla `arboles_vendidos`
--
ALTER TABLE `arboles_vendidos`
  ADD PRIMARY KEY (`ID_VENTA`);

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`ID_CARRITO`);

--
-- Indices de la tabla `detalle_carrito_compras`
--
ALTER TABLE `detalle_carrito_compras`
  ADD PRIMARY KEY (`ID_DETALLE`);

--
-- Indices de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  ADD PRIMARY KEY (`ID_DIRECCION`);

--
-- Indices de la tabla `especies_arboles`
--
ALTER TABLE `especies_arboles`
  ADD PRIMARY KEY (`ID_ESPECIE`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`ID_ESTADO`);

--
-- Indices de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  ADD PRIMARY KEY (`ID_TELEFONO`);

--
-- Indices de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD PRIMARY KEY (`ID_TIPO`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`),
  ADD UNIQUE KEY `USUARIO` (`USUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actualizaciones_arbol`
--
ALTER TABLE `actualizaciones_arbol`
  MODIFY `ID_ACTUALIZACION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `arboles`
--
ALTER TABLE `arboles`
  MODIFY `ID_ARBOL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `arboles_vendidos`
--
ALTER TABLE `arboles_vendidos`
  MODIFY `ID_VENTA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `ID_CARRITO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_carrito_compras`
--
ALTER TABLE `detalle_carrito_compras`
  MODIFY `ID_DETALLE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  MODIFY `ID_DIRECCION` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `especies_arboles`
--
ALTER TABLE `especies_arboles`
  MODIFY `ID_ESPECIE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `ID_ESTADO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  MODIFY `ID_TELEFONO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `ID_TIPO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actualizaciones_arbol`
--
ALTER TABLE `actualizaciones_arbol`
  ADD CONSTRAINT `actualizaciones_arbol_ibfk_1` FOREIGN KEY (`ID_ARBOL`) REFERENCES `arboles` (`ID_ARBOL`);

--
-- Filtros para la tabla `arboles`
--
ALTER TABLE `arboles`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`ESTADO`) REFERENCES `estados` (`ID_ESTADO`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
