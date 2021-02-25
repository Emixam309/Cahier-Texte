-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 25 fév. 2021 à 13:11
-- Version du serveur :  5.7.11
-- Version de PHP : 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cahier-texte`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectation`
--

DROP TABLE IF EXISTS `affectation`;
CREATE TABLE IF NOT EXISTS `affectation` (
  `idUser` int(11) NOT NULL,
  `idModule` int(11) NOT NULL,
  `idPromo` int(11) NOT NULL,
  `heuresPrevues` int(11) NOT NULL,
  PRIMARY KEY (`idUser`,`idModule`,`idPromo`),
  KEY `Affectation_Modules0_FK` (`idModule`),
  KEY `Affectation_Promos1_FK` (`idPromo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `affectation`
--

INSERT INTO `affectation` (`idUser`, `idModule`, `idPromo`, `heuresPrevues`) VALUES
(3, 1, 3, 20),
(3, 3, 2, 30),
(3, 3, 3, 30),
(3, 6, 4, 2),
(8, 4, 3, 40);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `idCommentaire` int(11) NOT NULL AUTO_INCREMENT,
  `dateHeure` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `commentaire` varchar(255) NOT NULL,
  `idFormation` int(11) DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idCommentaire`,`dateHeure`),
  KEY `Commentaire_Formations_FK` (`idFormation`),
  KEY `Commentaire_Users0_FK` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`idCommentaire`, `dateHeure`, `commentaire`, `idFormation`, `idUser`) VALUES
(1, '2021-01-27 13:40:34', 'rtes', NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `compterendu`
--

DROP TABLE IF EXISTS `compterendu`;
CREATE TABLE IF NOT EXISTS `compterendu` (
  `idCompteRendu` int(11) NOT NULL AUTO_INCREMENT,
  `idPromo` int(11) NOT NULL,
  `idModule` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `duree` int(11) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `moyen` varchar(255) NOT NULL,
  `objectif` varchar(255) DEFAULT NULL,
  `evaluation` varchar(255) NOT NULL,
  `distanciel` tinyint(1) DEFAULT NULL,
  `date` date NOT NULL,
  `dateEntree` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCompteRendu`,`idPromo`,`idModule`,`idUser`,`dateEntree`) USING BTREE,
  KEY `CompteRendu_Modules_FK` (`idModule`),
  KEY `CompteRendu_Users0_FK` (`idUser`),
  KEY `CompteRendu_Promo_FK` (`idPromo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `compterendu`
--

INSERT INTO `compterendu` (`idCompteRendu`, `idPromo`, `idModule`, `idUser`, `duree`, `contenu`, `moyen`, `objectif`, `evaluation`, `distanciel`, `date`, `dateEntree`) VALUES
(4, 3, 1, 8, 1, 'test de test', '', NULL, '', NULL, '2021-02-02', '2021-02-02 14:32:19'),
(15, 3, 3, 3, 1, 'informatique', 'ordinateur', '', '', NULL, '2021-02-03', '2021-02-03 09:24:16');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

DROP TABLE IF EXISTS `formations`;
CREATE TABLE IF NOT EXISTS `formations` (
  `idFormation` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `duree` int(11) NOT NULL,
  `volumeHoraire` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFormation`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`idFormation`, `reference`, `libelle`, `duree`, `volumeHoraire`) VALUES
(1, 'TPSAMS', 'TP Secrétaire Assistant(e) Médico-social', 8, NULL),
(3, 'test', 'test', 1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `idModule` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `libelle` varchar(80) NOT NULL,
  `nbHeures` int(11) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `idFormation` int(11) NOT NULL,
  PRIMARY KEY (`idModule`),
  UNIQUE KEY `reference` (`reference`),
  KEY `Modules_Formations_FK` (`idFormation`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`idModule`, `reference`, `libelle`, `nbHeures`, `commentaire`, `idFormation`) VALUES
(1, 'Cv', 'Recherche de stage – Insertion à l’emploi', 50, 'gfdghjkjghdfscvbnhtgfddsqhrttredsqw', 1),
(2, 'CCP', 'CCP', 50, '', 1),
(3, 'Info', 'Informatique Bureautique', 30, '', 1),
(4, 'EE', 'Expression écrite', 40, '', 1),
(5, 'Anglais', 'Anglais Professionnel', 50, '', 1),
(6, 'testm', 'test module', 4, 'ceci est un test', 3);

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
CREATE TABLE IF NOT EXISTS `promotions` (
  `idPromo` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `idFormation` int(11) NOT NULL,
  `verrouillage` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idPromo`),
  UNIQUE KEY `libelle` (`libelle`),
  KEY `Promos_Formations_FK` (`idFormation`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`idPromo`, `libelle`, `dateDebut`, `dateFin`, `idFormation`, `verrouillage`) VALUES
(2, 'TPSAMS 2020', '2020-06-01', '2021-01-31', 1, 1),
(3, 'TPSAMS 2021', '2021-01-01', '2021-06-30', 1, 0),
(4, 'test promo 2021', '2021-01-04', '2021-02-05', 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `stagiaires`
--

DROP TABLE IF EXISTS `stagiaires`;
CREATE TABLE IF NOT EXISTS `stagiaires` (
  `idStagiaire` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `idPromo` int(11) NOT NULL,
  PRIMARY KEY (`idStagiaire`),
  KEY `Stagiaires_Promos_FK` (`idPromo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `stagiaires`
--

INSERT INTO `stagiaires` (`idStagiaire`, `nom`, `prenom`, `idPromo`) VALUES
(5, 'Croisier', 'Maxime', 4),
(6, 'Deparis', 'Sofiane', 4),
(7, 'Croisier', 'Maxime', 3);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  `mail` varchar(30) DEFAULT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL,
  `actif` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `username`, `password`, `nom`, `prenom`, `specialite`, `mail`, `telephone`, `admin`, `actif`) VALUES
(1, 'abataille', '1234', 'Bataille', 'Antoine', '', '', '', 1, 1),
(2, 'E20', '123', 'Croisier', 'Maxime', NULL, NULL, NULL, 1, 1),
(3, 'operart', '1234', 'Perart', 'Olivier', 'informatique', '', '', 0, 1),
(4, 'spilloy', '1234', 'Pilloy', 'Sandrine', '', '', '', 0, 1),
(5, 'gdelattre', '1234', 'Delattre', 'Gaëlle', '', '', '', 0, 1),
(6, 'dvandenbrouque', '1234', 'Vandenbrouque', 'Dominique', '', '', '', 0, 1),
(7, 'ileroux', '1234', 'Leroux', 'Isabelle', '', '', '', 0, 0),
(8, 'alavoye', '1234', 'Lavoye', 'Audrey', '', '', '', 0, 1),
(9, 'nhecquefeuille', '1234', 'Hecquefeuille', 'Nathalie', '', '', '', 0, 1),
(10, 'jfrancoise', '1234', 'Joly', 'François', '', '', '0111111111', 0, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `affectation`
--
ALTER TABLE `affectation`
  ADD CONSTRAINT `Affectation_Modules0_FK` FOREIGN KEY (`idModule`) REFERENCES `modules` (`idModule`),
  ADD CONSTRAINT `Affectation_Promos1_FK` FOREIGN KEY (`idPromo`) REFERENCES `promotions` (`idPromo`),
  ADD CONSTRAINT `Affectation_Users_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `Commentaire_Formations_FK` FOREIGN KEY (`idFormation`) REFERENCES `formations` (`idFormation`),
  ADD CONSTRAINT `Commentaire_Users0_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Contraintes pour la table `compterendu`
--
ALTER TABLE `compterendu`
  ADD CONSTRAINT `CompteRendu_Modules_FK` FOREIGN KEY (`idModule`) REFERENCES `modules` (`idModule`),
  ADD CONSTRAINT `CompteRendu_Promo_FK` FOREIGN KEY (`idPromo`) REFERENCES `promotions` (`idPromo`),
  ADD CONSTRAINT `CompteRendu_Users0_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Contraintes pour la table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `Modules_Formations_FK` FOREIGN KEY (`idFormation`) REFERENCES `formations` (`idFormation`);

--
-- Contraintes pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `Promos_Formations_FK` FOREIGN KEY (`idFormation`) REFERENCES `formations` (`idFormation`);

--
-- Contraintes pour la table `stagiaires`
--
ALTER TABLE `stagiaires`
  ADD CONSTRAINT `Stagiaires_Promos_FK` FOREIGN KEY (`idPromo`) REFERENCES `promotions` (`idPromo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
