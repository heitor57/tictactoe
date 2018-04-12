-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 27-Nov-2017 às 14:59
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jogodavelha`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `desafio`
--

CREATE TABLE `desafio` (
  `d_j1` varchar(50) NOT NULL,
  `d_j2` varchar(50) NOT NULL,
  `d_j1_a` tinyint(1) NOT NULL,
  `d_j2_a` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `desafio`
--

INSERT INTO `desafio` (`d_j1`, `d_j2`, `d_j1_a`, `d_j2_a`) VALUES
('hec', 'heitor', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogador`
--

CREATE TABLE `jogador` (
  `nome_j` varchar(50) NOT NULL,
  `senha_j` varchar(60) NOT NULL,
  `imagem_j` varchar(80) DEFAULT 'uploads/imagempadrao.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jogador`
--

INSERT INTO `jogador` (`nome_j`, `senha_j`, `imagem_j`) VALUES
('hec', '$2y$10$pDHO1aoau6BMNsh94QRU3ukxGwvZa4s/63CiCtVY8MIwjZmIU6Ku.', 'uploads/ecbfbce6c294a339adf58fe4ebd1701f.jpg'),
('heitor', '$2y$10$cBm5ZAgc6cKDRpZgFDZceOSWlB1sYNF9RBg40NQa/bqnVyiSbjj4.', 'uploads/mago.jpg'),
('joao', '$2y$10$JZHwFIap85U3IPcBkzLguenMUT6RcN5Y0SiQEkLBH8O8HEb.8BAnO', 'uploads/Linux-Wallpaper-32.png'),
('moyses', '$2y$10$EJcvpEpoNoLDWyox7kaehemyGcZ2nqJxdrVmF.d8b827jCJZ1VHl.', 'uploads/Cosmetic_icon_Vengeance_of_the_Sunwarrior.png'),
('name', '$2y$10$p07QKXAsHfuEDvZNlMNlJudIryhjmL21KdP0IP8gG451QK21oBksC', 'uploads/imagempadrao.png'),
('ted', '$2y$10$OI0xsoyClAoSEX/pCbZ4BeSyhTCjLnKY.JOBCNqh.F7I8g7GbedG.', 'uploads/maxresdefault.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `partida`
--

CREATE TABLE `partida` (
  `p_id_partida` int(11) NOT NULL,
  `p_j1` varchar(50) NOT NULL,
  `p_j2` varchar(50) NOT NULL,
  `p_j1s` char(1) NOT NULL,
  `p_j2s` char(1) NOT NULL,
  `p_ativa` tinyint(1) NOT NULL,
  `p_vez` int(1) NOT NULL,
  `p_vencedor` varchar(50) DEFAULT NULL,
  `p_a1` char(1) DEFAULT '',
  `p_a2` char(1) DEFAULT '',
  `p_a3` char(1) DEFAULT '',
  `p_b1` char(1) DEFAULT '',
  `p_b2` char(1) DEFAULT '',
  `p_b3` char(1) DEFAULT '',
  `p_c1` char(1) DEFAULT '',
  `p_c2` char(1) DEFAULT '',
  `p_c3` char(1) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `partida`
--

INSERT INTO `partida` (`p_id_partida`, `p_j1`, `p_j2`, `p_j1s`, `p_j2s`, `p_ativa`, `p_vez`, `p_vencedor`, `p_a1`, `p_a2`, `p_a3`, `p_b1`, `p_b2`, `p_b3`, `p_c1`, `p_c2`, `p_c3`) VALUES
(1, 'heitor', 'hec', 'O', 'X', 0, 1, 'hec', '', 'X', '', 'O', '', '', '', '', ''),
(2, 'moyses', 'heitor', 'O', 'X', 0, 2, 'moyses', '', '', '', '', '', '', '', '', ''),
(3, 'moyses', 'heitor', 'O', 'X', 0, 1, NULL, 'X', 'X', 'O', 'O', 'X', 'X', 'X', 'O', 'O'),
(4, 'heitor', 'hec', 'O', 'X', 0, 1, 'hec', 'X', 'X', 'X', 'O', 'O', 'X', 'O', '', ''),
(5, 'joao', 'heitor', 'X', 'O', 0, 1, 'heitor', '', '', '', 'X', '', 'O', '', '', ''),
(6, 'heitor', 'joao', 'O', 'X', 0, 2, 'heitor', 'X', 'X', 'O', 'O', '', 'O', '', 'X', 'O'),
(7, 'joao', 'heitor', 'X', 'O', 0, 2, 'joao', '', '', '', '', '', '', '', '', ''),
(8, 'heitor', 'moyses', 'O', 'X', 0, 2, 'moyses', '', '', '', '', '', 'O', '', '', ''),
(9, 'hec', 'heitor', 'X', 'O', 1, 1, NULL, '', '', '', 'O', 'O', 'X', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `desafio`
--
ALTER TABLE `desafio`
  ADD PRIMARY KEY (`d_j1`,`d_j2`),
  ADD UNIQUE KEY `d_j1` (`d_j1`,`d_j2`),
  ADD KEY `d_j2_fk` (`d_j2`);

--
-- Indexes for table `jogador`
--
ALTER TABLE `jogador`
  ADD PRIMARY KEY (`nome_j`);

--
-- Indexes for table `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`p_id_partida`),
  ADD KEY `p_j1_fk` (`p_j1`),
  ADD KEY `p_j2_fk` (`p_j2`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `partida`
--
ALTER TABLE `partida`
  MODIFY `p_id_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `desafio`
--
ALTER TABLE `desafio`
  ADD CONSTRAINT `d_j1_fk` FOREIGN KEY (`d_j1`) REFERENCES `jogador` (`nome_j`),
  ADD CONSTRAINT `d_j2_fk` FOREIGN KEY (`d_j2`) REFERENCES `jogador` (`nome_j`);

--
-- Limitadores para a tabela `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `p_j1_fk` FOREIGN KEY (`p_j1`) REFERENCES `jogador` (`nome_j`),
  ADD CONSTRAINT `p_j2_fk` FOREIGN KEY (`p_j2`) REFERENCES `jogador` (`nome_j`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
