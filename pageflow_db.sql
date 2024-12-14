-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-12-2024 a las 23:32:01
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
-- Base de datos: `pageflow_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `ano_publicacion` year(4) DEFAULT NULL,
  `estado` enum('disponible','prestado','vendido','reservado') DEFAULT 'disponible',
  `sinopsis` text DEFAULT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id_libro`, `titulo`, `autor`, `genero`, `ano_publicacion`, `estado`, `sinopsis`, `portada`, `fecha_registro`) VALUES
(1, 'El Gran Gatsby', 'F. Scott Fitzgerald	', 'ficción', '1925', 'disponible', 'Es una historia que sigue a un grupo de personajes que viven en la ciudad ficticia de West Egg en la próspera Long Island, en el verano de 1922.', NULL, '2024-12-06 03:56:49'),
(2, '1984', 'George Orwell', 'cómics', '1949', 'disponible', 'Una novela distópica que describe una sociedad totalitaria gobernada por el Gran Hermano, donde la libertad individual es suprimida en favor del control social.', NULL, '2024-12-06 04:00:00'),
(9, 'Cien años de soledad', 'Gabriel García Márquez', 'aventura', '1967', 'disponible', 'La obra más conocida de Gabriel García Márquez, que narra la historia de la familia Buendía en el ficticio pueblo de Macondo, con un estilo único de realismo mágico.', NULL, '2024-12-06 04:00:00'),
(10, 'La sombra del viento', 'Carlos Ruiz Zafón', 'misterio', '2001', 'disponible', 'Una historia de amor, misterio y libros en la Barcelona de posguerra.', NULL, '2024-12-14 02:50:00'),
(11, 'Los juegos del hambre', 'Suzanne Collins', 'ciencia ficción', '2008', 'disponible', 'En un futuro distópico, los jóvenes de diferentes distritos compiten en un brutal juego de supervivencia.', NULL, '2024-12-14 02:50:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `penalizaciones`
--

CREATE TABLE `penalizaciones` (
  `id_penalizacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_prestamo` int(11) NOT NULL,
  `motivo` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `pagado` tinyint(1) DEFAULT 0,
  `fecha_penalizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion_estimada` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('en_prestamo','devuelto','retrasado') DEFAULT 'en_prestamo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id_prestamo`, `id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion_estimada`, `fecha_devolucion`, `estado`) VALUES
(1, 21, 10, '2024-12-10', '2024-12-20', NULL, 'en_prestamo'),
(2, 22, 11, '2024-12-11', '2024-12-21', NULL, 'en_prestamo'),
(3, 23, 9, '2024-12-08', '2024-12-18', '2024-12-12', 'devuelto'),
(4, 22, 1, '2024-12-05', '2024-12-15', NULL, 'retrasado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('administrador','lector') NOT NULL DEFAULT 'lector',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `pregunta_seguridad_1` varchar(255) NOT NULL,
  `pregunta_seguridad_2` varchar(255) NOT NULL,
  `pregunta_seguridad_3` varchar(255) NOT NULL,
  `ultimo_inicio_sesion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena`, `rol`, `fecha_registro`, `pregunta_seguridad_1`, `pregunta_seguridad_2`, `pregunta_seguridad_3`, `ultimo_inicio_sesion`) VALUES
(20, 'Daglier', '$2y$10$38OvAg1wuO19gpVYTH8iguh7fMdDWO1/4U9/D9BQvdhDf0Q5BM6KG', 'administrador', '2024-12-14 02:34:58', 'pizza', 'el tigre', 'cherry', '2024-12-13 23:25:15'),
(21, 'Carlos', '$2y$10$v1A7N60Ydw2vqgb8Ubr.zuEg3M9jZUBhaUXYEFeMFJkziVgaFwog6', 'lector', '2024-12-14 02:35:28', 'espagueti', 'el trigre', 'doge', '2024-12-13 22:35:28'),
(22, 'Fabian', '$2y$10$RgcpGA8R/iuLer0YZZuaL.Um42atrEeDqapl79bmFiI3Dyv5c2cWC', 'lector', '2024-12-14 02:35:54', 'salmon', 'el tigre', 'juan', '2024-12-13 22:35:54'),
(23, 'Maria', '$2y$10$l.zyYq4TW4QYw2VoLBA/werFZfInZCOgjVsAKo43gsqNhULopI1pi', 'lector', '2024-12-14 02:36:43', 'arroz', 'el tigrito', 'sasha', '2024-12-13 22:36:43');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id_libro`);

--
-- Indices de la tabla `penalizaciones`
--
ALTER TABLE `penalizaciones`
  ADD PRIMARY KEY (`id_penalizacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_prestamo` (`id_prestamo`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `penalizaciones`
--
ALTER TABLE `penalizaciones`
  MODIFY `id_penalizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `penalizaciones`
--
ALTER TABLE `penalizaciones`
  ADD CONSTRAINT `penalizaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `penalizaciones_ibfk_2` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamos` (`id_prestamo`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
