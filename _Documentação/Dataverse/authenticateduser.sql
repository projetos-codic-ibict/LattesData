-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 01-Fev-2022 às 14:44
-- Versão do servidor: 10.4.13-MariaDB
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dataverse`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `authenticateduser`
--

DROP TABLE IF EXISTS `authenticateduser`;
CREATE TABLE IF NOT EXISTS `authenticateduser` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `affiliation` varchar(255) COLLATE koi8u_bin DEFAULT NULL,
  `createdtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `deactivated` tinyint(1) DEFAULT NULL,
  `deactivatedtime` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE koi8u_bin NOT NULL,
  `emailconfirmed` timestamp NULL DEFAULT NULL,
  `firstname` varchar(255) COLLATE koi8u_bin NOT NULL,
  `lastapiusetime` timestamp NULL DEFAULT NULL,
  `lastlogintime` timestamp NULL DEFAULT NULL,
  `lastname` varchar(255) COLLATE koi8u_bin NOT NULL,
  `position` varchar(255) COLLATE koi8u_bin DEFAULT NULL,
  `superuser` tinyint(1) NOT NULL DEFAULT 0,
  `useridentifier` varchar(255) COLLATE koi8u_bin NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=koi8u COLLATE=koi8u_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
