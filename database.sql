-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2023 a las 14:51:06
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `superautos_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autoak`
--

CREATE TABLE `autoak` (
  `id` int(11) NOT NULL,
  `irudia` text NOT NULL,
  `marka` text NOT NULL,
  `izena` text NOT NULL,
  `prezioa` int(11) NOT NULL,
  `potentzia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `autoak`
--

INSERT INTO `autoak` (`id`, `irudia`, `marka`, `izena`, `prezioa`, `potentzia`) VALUES
(1, 'https://cdn.ferrari.com/cms/network/media/img/resize/5ddb97392cdb32285a799dfa-laferrari-2013-share?width=1080', 'Ferrari', 'LaFerrari', 1000009, 800),
(7, 'https://media.es.wired.com/photos/6425e0e4b4e328f8839787f4/16:9/w_2560%2Cc_limit/Lamborghini-Revuelto-Featured-Gear.jpg', 'Lamborghini', 'Revuelto', 4000000, 800),
(8, 'https://cdn.motor1.com/images/mgl/kvjPR/s3/2022-porsche-911-carrera-gts-front-3-4.jpg', 'Porsche', 'Porsche 911', 125000, 400),
(9, 'https://phantom-marca.unidadeditorial.es/cbec612a0857bfb6ee71347d19b2f8bf/resize/828/f/jpg/assets/multimedia/imagenes/2020/10/23/16034683855922.jpg', 'Bugatti', 'Divo', 90000000, 800);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `erabiltzaileak`
--

CREATE TABLE `erabiltzaileak` (
  `id` int(11) NOT NULL,
  `izen_abizenak` varchar(50) NOT NULL,
  `nan` varchar(10) NOT NULL,
  `telefonoa` int(9) NOT NULL,
  `jaiotze_data` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pasahitza` varchar(70) NOT NULL,
  `erabiltzaileIzena` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `erabiltzaileak`
--

INSERT INTO `erabiltzaileak` (`id`, `izen_abizenak`, `nan`, `telefonoa`, `jaiotze_data`, `email`, `pasahitza`, `erabiltzaileIzena`) VALUES
(42, 'admin', '79183768-N', 688688688, '2000-01-01', 'admin@admin.admin', '$2y$10$/2cMj1efPEYyVnvMnGRwtOPoQOlA9Xj7vaAxJiy9ifkfUFr5v.yj.', 'admin'),
(43, 'juanbelio', '79233587-J', 688688688, '2000-01-01', 'juanbelio@gmail.com', '$2y$10$YEpfbKTv2D764qg0.9UUju1u6O4hu1YNr2/zycQUubeBY9J5m5b5C', 'juanbelio');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autoak`
--
ALTER TABLE `autoak`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `erabiltzaileak`
--
ALTER TABLE `erabiltzaileak`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autoak`
--
ALTER TABLE `autoak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `erabiltzaileak`
--
ALTER TABLE `erabiltzaileak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
