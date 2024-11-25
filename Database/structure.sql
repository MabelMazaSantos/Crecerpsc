-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 03:05:53
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
-- Base de datos: `bd_psicologia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia_paciente`
--

CREATE TABLE `familia_paciente` (
  `idFamilia` int(11) NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `idParentesco` int(11) DEFAULT NULL,
  `Parentesco` int(11) DEFAULT NULL,
  `Fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `familia_paciente`
--

INSERT INTO `familia_paciente` (`idFamilia`, `idPaciente`, `idParentesco`, `Parentesco`, `Fecha_registro`) VALUES
(5, 8, 1, 2, '2024-11-24 20:00:33'),
(1, 4, 2, 0, '2024-11-24 20:54:56'),
(5, 2, 2, 8, '2024-11-24 20:56:28'),
(5, 1, 7, 2, '2024-11-24 20:56:33'),
(2, 5, 8, 3, '2024-11-24 20:56:59'),
(2, 3, 8, 5, '2024-11-24 20:57:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Edad` int(11) NOT NULL,
  `Sexo` varchar(20) NOT NULL,
  `Grupo_Familiar` varchar(50) NOT NULL,
  `Trastorno` varchar(120) NOT NULL,
  `Observacion` varchar(200) NOT NULL,
  `Fecha_Registro` date NOT NULL,
  `Estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`Id`, `Nombre`, `Edad`, `Sexo`, `Grupo_Familiar`, `Trastorno`, `Observacion`, `Fecha_Registro`, `Estado`) VALUES
(1, 'Aldo Zapata Alvines', 19, 'Masculino', 'Zapata Alvines', '0', 'Episodios de ansiedad', '2023-11-11', 0),
(2, 'Gustavo Zapata Alvines', 36, 'Masculino', 'Zapata Alvines', '1', 'pensamientos suicidas', '2024-11-11', 0),
(3, 'Mabel Maza Santos', 22, 'Femenino', 'Maza', '0', 'Preocupación constante y excesiva sobre una variedad de temas, inquietud, dificultad para relajarse, tensión muscular y problemas de concentración.', '2024-11-11', 1),
(4, 'Jose Sandoval Ramos', 38, 'Masculino', 'Sin grupo familiar', '2', 'Dificultades para mantener la atención, impulsividad, inquietud constante y falta de organización', '2024-11-11', 0),
(5, 'Gustavo sanchez perez', 22, 'Masculino', 'Sin grupo familiar', '6', 'Conductas de manipulación, impulsividad, falta de empatía y violación de las normas sociales.', '2024-11-11', 1),
(8, 'Diego Arturo', 22, 'Masculino', '', '4', 'holitas', '2024-11-24', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parentesco`
--

CREATE TABLE `parentesco` (
  `idParentesco` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parentesco`
--

INSERT INTO `parentesco` (`idParentesco`, `nombre`) VALUES
(1, 'Hijo'),
(2, 'Padre'),
(3, 'Madre'),
(4, 'Abuelo'),
(5, 'Abuela'),
(6, 'Conyuge'),
(7, 'Hermano'),
(8, 'Hermana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `psicologos`
--

CREATE TABLE `psicologos` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Sexo` varchar(30) NOT NULL,
  `Edad` int(11) NOT NULL,
  `Especialidad` varchar(200) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefono` int(11) NOT NULL,
  `Dni` int(11) NOT NULL,
  `Activo` int(11) NOT NULL DEFAULT 1,
  `Fecha_Registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `psicologos`
--

INSERT INTO `psicologos` (`Id`, `Nombre`, `Apellido`, `Sexo`, `Edad`, `Especialidad`, `Email`, `Telefono`, `Dni`, `Activo`, `Fecha_Registro`) VALUES
(1, 'Leydi Mabel', 'Maza Santos', 'Femenino', 24, 'Psicóloga', 'mazasantosl88@gmail.com', 903359536, 12345678, 1, '2024-11-11 09:56:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `terapia_familiar`
--

CREATE TABLE `terapia_familiar` (
  `Id` int(11) NOT NULL,
  `Grupo_Familiar` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `terapia_familiar`
--

INSERT INTO `terapia_familiar` (`Id`, `Grupo_Familiar`) VALUES
(1, 'Zapata'),
(2, 'Maza'),
(5, 'Zapata Alvines');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id` int(11) NOT NULL,
  `Usuario` varchar(30) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Password` varchar(120) NOT NULL,
  `Activacion` int(11) NOT NULL DEFAULT 1,
  `Id_Psicologo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id`, `Usuario`, `Nombre`, `Password`, `Activacion`, `Id_Psicologo`) VALUES
(1, 'mazasantosl88@gmail.com', 'Leydi Mabel Maza Santos', '$2y$10$FafEPwBgwEKihm2Q.vYBR..THSYNVmjcC7MY7BTwwhxvI636CYYVC', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `parentesco`
--
ALTER TABLE `parentesco`
  ADD PRIMARY KEY (`idParentesco`);

--
-- Indices de la tabla `psicologos`
--
ALTER TABLE `psicologos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `terapia_familiar`
--
ALTER TABLE `terapia_familiar`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `parentesco`
--
ALTER TABLE `parentesco`
  MODIFY `idParentesco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `psicologos`
--
ALTER TABLE `psicologos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `terapia_familiar`
--
ALTER TABLE `terapia_familiar`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
