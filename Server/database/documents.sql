-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2021 a las 07:31:57
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `textos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `iddocumento` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `snipped` varchar(50) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `documents`
--

INSERT INTO `documents` (`iddocumento`, `titulo`, `snipped`, `content`) VALUES
(1, 'Cancion.txt', 'Duermo en un acorde mágico\r\nY despierto al oírlo ', 'Duermo en un acorde mágico\nY despierto al oírlo tocar\nSoy la esencia de la humanidad\nRepresento la promiscuidad\nDe las almas que enferman de paz\nMe presento, soy la libertad\nDe tu cuerpo y no cobro con fe\nY ahora dime, ¿Cuánto vale tu alma?\nY ahora pide, ¿Dinero o placer?\n¿Sueñas con curar el cáncer?\nEl Sida, fue cosa de Yahvé\nQuiero estar junto a ti y alimentar tu boca\nHay veces que el dolor, duerme en una canción\nY sé, que moriré de amor decadente\nLúgubres besos ¡Quémate en mí!\nEl Príncipe de la dulce pena soy\nY mi sangre alime'),
(2, 'He renunciado a ti.txt', 'He renunciado a ti definitivamente\r\nHe renunciado ', 'He renunciado a ti definitivamente\nHe renunciado a ti y esta vez para siempre\nTe habrás fijado que no te busco\nQue pasa el tiempo y no voy por tu casa\nQue no me ves por los sitios que pasas\nHe renunciado a ti, he renunciado a ti\nPorque es pura fantasía nuestro amor\nIlusiones que se forjan con el tiempo\nPorque es tanta la distancia entre los dos\nQue es difícil que podamos entendernos\nPorque es pura fantasía nuestro amor\nIlusiones que se forjan con el tiempo\nPorque es tanta la distancia entre los dos\nQue es difícil que'),
(3, 'Quiero que apagues mi luz.txt', 'Hace mucho tiempo que mi cuerpo\r\nY mi alma están d', 'Hace mucho tiempo que mi cuerpo\nY mi alma están divorciados\nY ya ni se hablan\nUno no es tan fuerte\nY no quiere, no puede aceptar\nQue mi vida caduca ya\nQue el dolor va ganando la partida\nY el llanto no me deja seguir\nY mi alma suplica morir\nCierra mis ojos y bésame, amor\nDéjame irme, haz que pare el dolor\nAyúdame a marcharme de aquí\n¡Quiero poder decidir!\nDame tu mano y no llores por mí\nMe llevo toda la esencia de ti\nNunca he querido que cargues mi cruz\nQuiero que apagues mi luz\nBusco en tu mirada la que fuiste\nY no estás\nHace tie'),
(4, 'Text1.txt', 'He renunciado por el mero hecho\r\nde renunciar asi ', 'He renunciado por el mero hecho\r\nde renunciar asi como el principe');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`iddocumento`);
ALTER TABLE `documents` ADD FULLTEXT KEY `content` (`content`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
  MODIFY `iddocumento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
