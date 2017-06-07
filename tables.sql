
CREATE DATABASE HELPDESK;
USE HELPDESK;


--
-- Table structure for table `agentes`
--

CREATE TABLE `agentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rango` int(1) default 1,
  `nombre` varchar(16) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(512) NOT NULL,
  `activo` boolean default 0,
  `disponible` boolean default 0,
  `dias` varchar(256) NOT NULL,  
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `ts_nodisponible` timestamp,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO  `agentes` (`rango` ,`nombre` ,`email` ,`password` ,`activo` ,`disponible` ,`dias` ,`hora_inicio` ,`hora_fin`)
VALUES ('2',  'admin',  'admin@gtech.com.uy',  '1234',  '0',  '0',  'Todos',  '',  ''),
('1',  'agente1',  'agente1@gtech.com.uy',  '1234',  '0',  '0',  'Lunes Martes Miercoles Jueves Viernes',  '08:00',  '23:00'),
('1',  'agente2',  'agente2@gtech.com.uy',  '1234',  '0',  '1',  'Lunes Martes Miercoles Jueves Viernes Sabado',  '16:00',  '00:59'),
('1',  'agente3',  'agente3@gtech.com.uy',  '1234',  '1',  '0',  'Lunes Martes Miercoles Jueves Viernes Sabado Domingo',  '12:00',  '23:30');
-- --------------------------------------------------------

--
-- Table structure for table `webchat_lineas`
--

CREATE TABLE `webchat_lineas` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `receptor` varchar(16) NOT NULL,
  `emisor` varchar(16) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `img` varchar(255),
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `ts` (`ts`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias`
--

CREATE TABLE IF NOT EXISTS `dias` (
  `nombre` varchar(16) NOT NULL,
  `dia` varchar(10) NOT NULL,
  PRIMARY KEY (`nombre`,`dia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `dias`
--

INSERT INTO `dias` (`nombre`, `dia`) VALUES
('agente1', 'Lunes'),
('agente1', 'Martes'),
('agente1', 'Miercoles'),
('agente1', 'Jueves'),
('agente1', 'Viernes'),
('agente2', 'Miercoles'),
('agente2', 'Jueves'),
('agente2', 'Viernes'),
('agente2', 'Sabado'),
('agente3', 'Sabado'),
('agente3', 'Domingo');


--
-- Trigger `dias_agente_delete`
--
DELIMITER $$
CREATE TRIGGER dias_agente_delete AFTER DELETE on agentes
FOR EACH ROW
BEGIN
DELETE FROM dias WHERE dias.nombre = old.nombre;
END $$