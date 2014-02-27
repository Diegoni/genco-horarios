-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-02-2014 a las 10:10:29
-- Versión del servidor: 5.1.33
-- Versión de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `controlfinal2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma`
--

CREATE TABLE IF NOT EXISTS `cronograma` (
  `idcronograma` int(11) NOT NULL,
  `departamento_iddepartamento` int(11) NOT NULL,
  `entradaM` varchar(45) DEFAULT NULL,
  `salidaM` varchar(45) DEFAULT NULL,
  `entradaT` varchar(45) DEFAULT NULL,
  `salidaT` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcronograma`),
  KEY `fk_cronograma_departamento1` (`departamento_iddepartamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `cronograma`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma_has_dias`
--

CREATE TABLE IF NOT EXISTS `cronograma_has_dias` (
  `cronograma_idcronograma` int(11) NOT NULL,
  `dias_iddias` int(11) NOT NULL,
  PRIMARY KEY (`cronograma_idcronograma`,`dias_iddias`),
  KEY `fk_cronograma_has_dias_dias1` (`dias_iddias`),
  KEY `fk_cronograma_has_dias_cronograma1` (`cronograma_idcronograma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `cronograma_has_dias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_departamento`, `nombre`) VALUES
(1, 'Grupo Genco'),
(2, 'Genco S.A.'),
(3, 'Genco Automoviles S.A.'),
(4, 'Genco Trucks S.A.'),
(5, '01-Administraci?n'),
(6, '01-Taller de Mantenimiento'),
(7, '02-Repuestos'),
(9, '02-Ventas BMW'),
(10, '01-Mantenimiento y Limpieza'),
(11, '03-Administraci?n'),
(13, '03-Repuestos'),
(14, '03-Servicios'),
(15, '03-Ventas'),
(16, '02-Servicios'),
(17, '01-Servicios'),
(19, '02-Mantenimiento y Limpieza'),
(20, '02-Recepci?n'),
(21, '01-Secretar?a'),
(22, '01-Ventas Kia'),
(23, '01-Ventas Chrysler'),
(24, '01-Ventas Usados'),
(25, '01-Marketing'),
(27, '02-Ventas Mini'),
(28, 'Gerentes'),
(29, '01-Gerentes'),
(30, '02-Gerentes'),
(31, '03-Gerentes'),
(32, '01-Repuestos'),
(33, '01-Constructora'),
(34, 'Sistemas'),
(35, '03-Mant. y Limpieza'),
(37, '01-Taller de Pintura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias`
--

CREATE TABLE IF NOT EXISTS `dias` (
  `iddias` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iddias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `dias`
--

INSERT INTO `dias` (`iddias`, `nombre`) VALUES
(1, 'lunes'),
(2, 'martes'),
(3, 'miercoles'),
(4, 'jueves'),
(5, 'viernes'),
(6, 'sabado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feriados`
--

CREATE TABLE IF NOT EXISTS `feriados` (
  `idferiados` int(11) NOT NULL AUTO_INCREMENT,
  `dia` date DEFAULT NULL,
  PRIMARY KEY (`idferiados`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `feriados`
--

INSERT INTO `feriados` (`idferiados`, `dia`) VALUES
(5, '2012-10-08'),
(6, '2012-11-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_auditoria_usuario`
--

CREATE TABLE IF NOT EXISTS `log_auditoria_usuario` (
  `id_log_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `Accion` varchar(10) DEFAULT NULL,
  `Creacion` datetime DEFAULT NULL,
  `Usuario` varchar(45) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `nombre_old` varchar(45) DEFAULT NULL,
  `nombre_new` varchar(45) DEFAULT NULL,
  `activo_old` int(11) DEFAULT NULL,
  `activo_new` int(11) DEFAULT NULL,
  `departamento_iddepartamento_old` int(11) DEFAULT NULL,
  `departamento_iddepartamento_new` int(11) DEFAULT NULL,
  `legajo_old` int(11) DEFAULT NULL,
  `legajo_new` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_log_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `log_auditoria_usuario`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcada`
--

CREATE TABLE IF NOT EXISTS `marcada` (
  `id_marcada` int(11) NOT NULL AUTO_INCREMENT,
  `entrada` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_parametros_access` int(11) NOT NULL,
  `id_parametros` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id_marcada`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91142 ;

--
-- (Evento) desencadenante `marcada`
--
DROP TRIGGER IF EXISTS `controlfinal2`.`marcada_update`;
DELIMITER //
CREATE TRIGGER `controlfinal2`.`marcada_update` AFTER UPDATE ON `controlfinal2`.`marcada`
 FOR EACH ROW BEGIN
  INSERT INTO log_auditoria_usuario
	(	Accion,
		id_usuario,
        entrada_old,
        entrada_new,
        id_parametros_old,
        id_parametros_new,
		id_estado_old,
		id_estado_new)
	VALUES	(
	'Update',
	OLD.id_usuario,
	OLD.entrada,
	NEW.entrada,
	OLD.id_parametros,
	NEW.id_parametros,
	OLD.id_estado,
	NEW.id_estado,
	NOW(),
	CURRENT_USER());
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota`
--

CREATE TABLE IF NOT EXISTS `nota` (
  `id_nota` int(11) NOT NULL,
  `nota` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `nota`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otrahora`
--

CREATE TABLE IF NOT EXISTS `otrahora` (
  `id_otrahora` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_tipootra` int(11) NOT NULL,
  `id_nota` int(11) NOT NULL,
  `horas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_otrahora`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `otrahora`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otrashoras`
--

CREATE TABLE IF NOT EXISTS `otrashoras` (
  `idotrashoras` int(11) NOT NULL AUTO_INCREMENT,
  `enfermedad` varchar(45) DEFAULT NULL,
  `accidente` varchar(45) DEFAULT NULL,
  `feriado` varchar(45) DEFAULT NULL,
  `ausencia` varchar(45) DEFAULT NULL,
  `vacaciones` varchar(45) DEFAULT NULL,
  `otros` varchar(45) DEFAULT NULL,
  `observaciones` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  PRIMARY KEY (`idotrashoras`),
  KEY `fk_otrashoras_usuario1` (`usuario_idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `otrashoras`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id_parametros` int(11) NOT NULL,
  `inicio` int(11) NOT NULL,
  `final` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_turno` int(11) NOT NULL,
  `considerar` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `parametros`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sequence`
--

CREATE TABLE IF NOT EXISTS `sequence` (
  `SEQ_NAME` varchar(50) NOT NULL,
  `SEQ_COUNT` decimal(38,0) DEFAULT NULL,
  PRIMARY KEY (`SEQ_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `sequence`
--

INSERT INTO `sequence` (`SEQ_NAME`, `SEQ_COUNT`) VALUES
('SEQ_GEN', 151);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `tipo`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipootra`
--

CREATE TABLE IF NOT EXISTS `tipootra` (
  `id_tipootra` int(11) NOT NULL,
  `tipootra` varchar(45) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `tipootra`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `totales`
--

CREATE TABLE IF NOT EXISTS `totales` (
  `idtotales` int(11) NOT NULL AUTO_INCREMENT,
  `accidente` varchar(45) DEFAULT NULL,
  `ausencia` varchar(45) DEFAULT NULL,
  `cien` varchar(45) DEFAULT NULL,
  `cincuenta` varchar(45) DEFAULT NULL,
  `desde` varchar(45) DEFAULT NULL,
  `enfermedad` varchar(45) DEFAULT NULL,
  `feriado` varchar(45) DEFAULT NULL,
  `hasta` varchar(45) DEFAULT NULL,
  `normales` varchar(45) DEFAULT NULL,
  `otros` varchar(45) DEFAULT NULL,
  `vacaciones` varchar(45) DEFAULT NULL,
  `USU_idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idtotales`),
  KEY `FK_totales_USU_idusuario` (`USU_idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `totales`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int(11) NOT NULL AUTO_INCREMENT,
  `turno` varchar(45) NOT NULL,
  PRIMARY KEY (`id_turno`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `turno`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `update`
--

CREATE TABLE IF NOT EXISTS `update` (
  `id_update` int(11) NOT NULL AUTO_INCREMENT,
  `ultima_fecha` datetime NOT NULL,
  `ultimo_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `registros` int(11) NOT NULL,
  PRIMARY KEY (`id_update`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `update`
--

INSERT INTO `update` (`id_update`, `ultima_fecha`, `ultimo_id`, `fecha`, `registros`) VALUES
(1, '2012-08-14 09:08:12', 44, '2014-02-26 09:02:52', 91141);



--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `cronograma`
--
ALTER TABLE `cronograma`
  ADD CONSTRAINT `fk_cronograma_departamento1` FOREIGN KEY (`departamento_iddepartamento`) REFERENCES `departamento` (`iddepartamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cronograma_departamento_iddepartamento` FOREIGN KEY (`departamento_iddepartamento`) REFERENCES `departamento` (`iddepartamento`);

--
-- Filtros para la tabla `cronograma_has_dias`
--
ALTER TABLE `cronograma_has_dias`
  ADD CONSTRAINT `fk_cronograma_has_dias_cronograma1` FOREIGN KEY (`cronograma_idcronograma`) REFERENCES `cronograma` (`idcronograma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cronograma_has_dias_cronograma_idcronograma` FOREIGN KEY (`cronograma_idcronograma`) REFERENCES `cronograma` (`idcronograma`),
  ADD CONSTRAINT `fk_cronograma_has_dias_dias1` FOREIGN KEY (`dias_iddias`) REFERENCES `dias` (`iddias`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cronograma_has_dias_dias_iddias` FOREIGN KEY (`dias_iddias`) REFERENCES `dias` (`iddias`);


