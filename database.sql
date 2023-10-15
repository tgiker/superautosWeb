-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2023 a las 14:23:00
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
-- Base de datos: `database`
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
(1, 'https://cdn.ferrari.com/cms/network/media/img/resize/5ddb97392cdb32285a799dfa-laferrari-2013-share?width=1080', 'Ferrari', 'LaFerrari', 10000000, 800),
(2, 'https://www.lavanguardia.com/files/article_main_microformat/uploads/2023/02/07/63e24f38e851e.png', 'Bugatti', 'Veyron', 77700000, 2000),
(5, 'https://cdn.topgear.es/sites/navi.axelspringer.es/public/media/image/2021/04/honda-civic-type-r-limited-edition-2021-2290163.jpg?tf=3840x', 'Honda', 'Civic', 50000, 300);

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
  `pasahitza` varchar(16) NOT NULL,
  `erabiltzaileIzena` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `erabiltzaileak`
--

INSERT INTO `erabiltzaileak` (`id`, `izen_abizenak`, `nan`, `telefonoa`, `jaiotze_data`, `email`, `pasahitza`, `erabiltzaileIzena`) VALUES
(32, 'juan belio', '79233587-J', 777888777, '2000-01-01', 'juanjuanjuan@juan.juan', 'juanbelio777', 'juanbelio'),
(33, 'admin', '79183768-N', 688658788, '2000-01-01', 'tgiker@gmail.com', 'admin777', 'admin');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `erabiltzaileak`
--
ALTER TABLE `erabiltzaileak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
