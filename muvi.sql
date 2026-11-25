-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2026 a las 21:30:11
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
-- Base de datos: `muvi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `butacas`
--

CREATE TABLE `butacas` (
  `id_butaca` int(10) UNSIGNED NOT NULL,
  `posicionX` varchar(2) NOT NULL,
  `posicionY` int(11) NOT NULL,
  `disponibilidad` tinyint(1) NOT NULL,
  `id_entrada` int(10) UNSIGNED NOT NULL,
  `id_sala` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `butacas`
--

INSERT INTO `butacas` (`id_butaca`, `posicionX`, `posicionY`, `disponibilidad`, `id_entrada`, `id_sala`, `created_at`, `updated_at`) VALUES
(20, 'B', 1, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(21, 'B', 2, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(22, 'B', 3, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(23, 'B', 4, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(25, 'B', 7, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(26, 'B', 8, 1, 0, 1, '2026-11-16 20:55:42', '2026-11-16 20:55:42'),
(199, 'B', 6, 1, 0, 1, '2026-11-16 20:56:14', '2026-11-16 20:56:14'),
(215, 'B', 9, 1, 0, 1, '2026-11-16 20:57:34', '2026-11-16 20:57:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo`
--

CREATE TABLE `combo` (
  `id_combo` int(10) UNSIGNED NOT NULL,
  `comida` varchar(20) NOT NULL,
  `bebida` varchar(20) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combo`
--

INSERT INTO `combo` (`id_combo`, `comida`, `bebida`, `precio`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 'pochoclos', 'gaseosa_lata', 7000.00, NULL, NULL, NULL),
(2, 'pochoclos', 'gaseosa botella 2L', 10000.00, NULL, NULL, NULL),
(3, 'hamburguesa', 'gaseosa botella 2L', 9000.00, NULL, NULL, NULL),
(4, 'Pancho', 'Gaseosa Lata', 9000.00, NULL, NULL, NULL),
(5, 'Pancho', 'Gaseosa Botella 2L', 9000.00, NULL, '2026-11-11 20:21:36', '2026-11-11 20:21:36'),
(6, 'Hamburguesa Doble', 'Sprite', 6000.00, NULL, '2026-11-17 22:06:32', '2026-11-17 22:06:32'),
(7, 'Hamburguesa Doble', 'Sprite', 6000.00, 'uploads/combos/1795635611_522e8ddcadae44b4ed41.png', '2026-11-25 19:40:11', '2026-11-25 19:40:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combocompra`
--

CREATE TABLE `combocompra` (
  `id_compra` int(11) UNSIGNED NOT NULL,
  `id_combo` int(11) UNSIGNED NOT NULL,
  `estado_entrega` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(10) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha_compra` datetime DEFAULT NULL,
  `codigo_qr` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_butaca`
--

CREATE TABLE `compra_butaca` (
  `id_Compra_Butaca` int(10) UNSIGNED NOT NULL,
  `id_compra` int(10) UNSIGNED NOT NULL,
  `id_butaca` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id_funcion` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `hora_salida` time NOT NULL,
  `id_sala` int(10) UNSIGNED NOT NULL,
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id_funcion`, `fecha`, `hora_ingreso`, `hora_salida`, `id_sala`, `id_pelicula`, `created_at`, `updated_at`) VALUES
(1, '2026-11-30', '20:30:00', '22:30:00', 1, 1, NULL, '2026-11-24 21:41:12'),
(2, '2026-12-02', '22:00:00', '00:00:00', 1, 1, '2026-11-11 19:40:52', '2026-11-11 19:40:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(53, '2026-09-29-192909', 'App\\Database\\Migrations\\CreateCompras', 'default', 'App', 1793399262, 1),
(54, '2026-10-02-172034', 'App\\Database\\Migrations\\CreateUsuarios', 'default', 'App', 1793399262, 1),
(55, '2026-10-07-204214', 'App\\Database\\Migrations\\CreatePeliculas', 'default', 'App', 1793399262, 1),
(56, '2026-10-07-204214', 'App\\Database\\Migrations\\CreateSalas', 'default', 'App', 1793399262, 1),
(57, '2026-10-08-204214', 'App\\Database\\Migrations\\CreateFunciones', 'default', 'App', 1793399262, 1),
(58, '2026-10-09-173758', 'App\\Database\\Migrations\\CreatePago', 'default', 'App', 1793399262, 1),
(59, '2026-10-09-175145', 'App\\Database\\Migrations\\CreateCombo', 'default', 'App', 1793399262, 1),
(60, '2026-10-09-175854', 'App\\Database\\Migrations\\CreateButaca', 'default', 'App', 1793399830, 2),
(61, '2026-10-10-130000', 'App\\Database\\Migrations\\CreateComboCompra', 'default', 'App', 1793399830, 2),
(62, '2026-10-16-224800', 'App\\Database\\Migrations\\CreateCompraButaca', 'default', 'App', 1793399831, 2),
(63, '2026-11-24-100000', 'App\\Database\\Migrations\\CreateCategorias', 'default', 'App', 1795554187, 3),
(64, '2026-11-24-100100', 'App\\Database\\Migrations\\AddImagenCategoriaToPeliculas', 'default', 'App', 1795554187, 3),
(65, '2026-11-25-100000', 'App\\Database\\Migrations\\PeliculasCategoriaTextoYEstado', 'default', 'App', 1795635498, 4),
(66, '2026-11-25-100100', 'App\\Database\\Migrations\\AddImagenToCombo', 'default', 'App', 1795635498, 4),
(67, '2026-11-25-100200', 'App\\Database\\Migrations\\AddIdUsuarioToPagos', 'default', 'App', 1795635498, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(10) UNSIGNED NOT NULL,
  `monto` int(10) UNSIGNED NOT NULL,
  `moneda` varchar(20) NOT NULL,
  `metodo_pago` varchar(20) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `monto`, `moneda`, `metodo_pago`, `estado`, `id_usuario`, `created_at`, `updated_at`) VALUES
(1, 500, 'Robux', 'yellow card', 'Pendiente', NULL, '2026-11-03 22:10:15', '2026-11-03 22:10:15'),
(2, 21000, 'ars', 'tarjeta', 'Pagado', NULL, '2026-11-24 23:17:12', '2026-11-24 23:17:12'),
(3, 14000, 'ars', 'efectivo', 'Pendiente', 2, '2026-11-25 19:42:46', '2026-11-25 19:42:46'),
(4, 10000, 'ars', 'tarjeta', 'Pagado', 2, '2026-11-25 20:20:33', '2026-11-25 20:20:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id_pelicula` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(40) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `duracion` int(10) UNSIGNED NOT NULL,
  `clasificacion` varchar(40) NOT NULL,
  `categoria` varchar(40) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'ESTRENO',
  `trailer_url` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `sinopsis`, `duracion`, `clasificacion`, `categoria`, `estado`, `trailer_url`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 'Jumanji', 'La película Jumanji (1995) trata sobre Alan Parrish, un niño que en 1969 descubre un misterioso juego 		de mesa y es mágicamente absorbido por su jungla interior tras tirar los dados.', 120, 'ATP', 'Aventura', 'ESTRENO', 'https://www.youtube.com/watch?v=KcgsDnt3CIw', 'uploads/peliculas/1795555491_f81dcf501201b6e65631.png', NULL, '2026-11-24 21:51:06'),
(2, 'A Minecraft Movie', 'Una película de Minecraft sigue a cuatro inadaptados que son absorbidos por un portal misterioso hacia el Overworld, un mundo cúbico donde deben usar su creatividad y aliarse con Steve para sobrevivir y volver a casa', 101, 'ATP', 'Comedia', 'ESTRENO', 'https://www.youtube.com/watch?v=wJO_vIDZn-I', 'uploads/peliculas/1795565974_4acbd8cb74e2190bda0e.png', '2026-11-25 00:19:34', '2026-11-25 00:19:34'),
(3, 'Coyote VS. ACME', 'Coyote vs. Acme (2026) trata sobre Wile E. Coyote, quien decide demandar a la corporación ACME tras años de sufrir explosiones, caídas y accidentes por sus productos defectuosos al intentar atrapar al Correcaminos', 103, 'ATP', 'Comedia', 'ESTRENO', 'https://www.youtube.com/watch?v=Yj-Nm47IW9k', 'uploads/peliculas/1795566075_0073eec22a80c458e0d9.png', '2026-11-25 00:21:15', '2026-11-25 00:21:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `capacidad` smallint(5) UNSIGNED NOT NULL,
  `tipo_sala` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id_sala`, `nombre`, `capacidad`, `tipo_sala`, `created_at`, `updated_at`) VALUES
(1, 'Sala 1', 250, 3.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cuil` varchar(11) NOT NULL,
  `role` enum('CLIENTE','ADMINISTRADOR','EMPLEADO') NOT NULL DEFAULT 'CLIENTE',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `last_name`, `email`, `password`, `cuil`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Juan', 'Elias', 'juanelias@alumnos.itr3.edu.ar', '$2y$10$x2lWXR.bTaFrWYFaTS4b2es1C0aCjn6RlVucFlO4MwW8YxlsS8xuO', '12345678912', 'ADMINISTRADOR', '2026-10-30 23:04:27', '2026-10-30 23:04:27'),
(2, 'Joaquin', 'Carranza', 'joaquincarranza@alumnos.itr3.edu.ar', '$2y$10$MHx7lAEg7Tur1rWGp.xbo.qkSH7HlnfaXwAbqdgzch1EdUhnz5V8m', '12121212121', 'CLIENTE', '2026-11-02 21:59:52', '2026-11-02 21:59:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `butacas`
--
ALTER TABLE `butacas`
  ADD PRIMARY KEY (`id_butaca`),
  ADD KEY `butacas_id_sala_foreign` (`id_sala`);

--
-- Indices de la tabla `combo`
--
ALTER TABLE `combo`
  ADD PRIMARY KEY (`id_combo`);

--
-- Indices de la tabla `combocompra`
--
ALTER TABLE `combocompra`
  ADD PRIMARY KEY (`id_compra`,`id_combo`),
  ADD KEY `ComboCompra_id_combo_foreign` (`id_combo`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `compra_butaca`
--
ALTER TABLE `compra_butaca`
  ADD PRIMARY KEY (`id_Compra_Butaca`),
  ADD KEY `compra_butaca_id_compra_foreign` (`id_compra`),
  ADD KEY `compra_butaca_id_butaca_foreign` (`id_butaca`);

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`id_funcion`),
  ADD KEY `funciones_id_sala_foreign` (`id_sala`),
  ADD KEY `funciones_id_pelicula_foreign` (`id_pelicula`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `fk_pagos_usuario` (`id_usuario`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id_pelicula`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cuil` (`cuil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `butacas`
--
ALTER TABLE `butacas`
  MODIFY `id_butaca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT de la tabla `combo`
--
ALTER TABLE `combo`
  MODIFY `id_combo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra_butaca`
--
ALTER TABLE `compra_butaca`
  MODIFY `id_Compra_Butaca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id_funcion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id_pelicula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `butacas`
--
ALTER TABLE `butacas`
  ADD CONSTRAINT `butacas_id_sala_foreign` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `combocompra`
--
ALTER TABLE `combocompra`
  ADD CONSTRAINT `ComboCompra_id_combo_foreign` FOREIGN KEY (`id_combo`) REFERENCES `combo` (`id_combo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ComboCompra_id_compra_foreign` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compra_butaca`
--
ALTER TABLE `compra_butaca`
  ADD CONSTRAINT `compra_butaca_id_butaca_foreign` FOREIGN KEY (`id_butaca`) REFERENCES `butacas` (`id_butaca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_butaca_id_compra_foreign` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD CONSTRAINT `funciones_id_pelicula_foreign` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `funciones_id_sala_foreign` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
