-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 15 juil. 2021 à 16:22
-- Version du serveur :  10.3.16-MariaDB
-- Version de PHP : 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `id16032922_cahiertexte`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectation`
--

CREATE TABLE `affectation`
(
    `idUser`        int(11) NOT NULL,
    `idModule`      int(11) NOT NULL,
    `idPromo`       int(11) NOT NULL,
    `heuresPrevues` int(11) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `affectation`
--

INSERT INTO `affectation` (`idUser`, `idModule`, `idPromo`, `heuresPrevues`)
VALUES (3, 3, 3, 30),
       (4, 7, 5, 40),
       (7, 9, 8, 30),
       (8, 4, 3, 40),
       (8, 8, 5, 30),
       (9, 8, 5, 20),
       (10, 7, 5, 40),
       (13, 9, 8, 40);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires`
(
    `idCommentaire` int(11)      NOT NULL,
    `dateHeure`     timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `commentaire`   varchar(255) NOT NULL,
    `idFormation`   int(11)               DEFAULT NULL,
    `idUser`        int(11)      NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`idCommentaire`, `dateHeure`, `commentaire`, `idFormation`, `idUser`)
VALUES (1, '2021-01-27 13:40:34', 'rtes', NULL, 2),
       (4, '2021-02-11 13:12:34', 'essai', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `compterendu`
--

CREATE TABLE `compterendu`
(
    `idCompteRendu` int(11)      NOT NULL,
    `idPromo`       int(11)      NOT NULL,
    `idModule`      int(11)      NOT NULL,
    `idUser`        int(11)      NOT NULL,
    `duree`         int(11)      NOT NULL,
    `contenu`       varchar(255) NOT NULL,
    `moyen`         varchar(255) NOT NULL,
    `objectif`      varchar(255)          DEFAULT NULL,
    `evaluation`    varchar(255) NOT NULL,
    `distanciel`    tinyint(1)            DEFAULT NULL,
    `date`          date         NOT NULL,
    `dateEntree`    timestamp    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `compterendu`
--

INSERT INTO `compterendu` (`idCompteRendu`, `idPromo`, `idModule`, `idUser`, `duree`, `contenu`, `moyen`, `objectif`,
                           `evaluation`, `distanciel`, `date`, `dateEntree`)
VALUES (4, 3, 1, 2, 1, 'test de test', '', '', '', NULL, '2021-02-02', '2021-02-02 14:32:19'),
       (15, 3, 3, 3, 1, 'informatique', 'ordinateur', '', '', NULL, '2021-02-03', '2021-02-03 09:24:16'),
       (16, 3, 3, 1, 4, 'Apprentissage du clavier', 'powerpoint + ordi', 'AT1C1', 'qcm', NULL, '2021-02-09',
        '2021-02-09 15:26:25'),
       (17, 3, 3, 3, 4, 'jj\'ai raconté une histoire', 'pc\r\npowerpoint', 'CCP1    AT5C4', '', 1, '2021-02-11',
        '2021-02-11 13:14:31'),
       (18, 3, 3, 3, 4, 'fghdfgdgdfgdfgdfgdfg', 'fghfgh', 'a1c5', 'oui', NULL, '2021-03-24', '2021-03-24 13:36:31'),
       (19, 3, 3, 3, 2, 'sdfsdfsdf', 'word', 'at1c2', '', NULL, '2021-04-09', '2021-04-09 14:51:58');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations`
(
    `idFormation`   int(11)      NOT NULL,
    `reference`     varchar(20)  NOT NULL,
    `libelle`       varchar(100) NOT NULL,
    `duree`         int(11)      NOT NULL,
    `volumeHoraire` int(11) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`idFormation`, `reference`, `libelle`, `duree`, `volumeHoraire`)
VALUES (1, 'TPSAMS', 'TP Secrétaire Assistant(e) Médico-social', 8, NULL),
       (4, 'TPCLOWN', 'ecole du cirque', 6, 450),
       (5, 'TPSAMSCPF', 'TPSAMSCPF', 8, 550);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules`
(
    `idModule`    int(11)      NOT NULL,
    `reference`   varchar(20)  NOT NULL,
    `libelle`     varchar(80)  NOT NULL,
    `nbHeures`    int(11)      NOT NULL,
    `commentaire` varchar(255) NOT NULL,
    `idFormation` int(11)      NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`idModule`, `reference`, `libelle`, `nbHeures`, `commentaire`, `idFormation`)
VALUES (1, 'Cv', 'Recherche de stage – Insertion à l’emploi', 50, 'gfdghjkjghdfscvbnhtgfddsqhrttredsqw', 1),
       (2, 'CCP', 'CCP', 50, '', 1),
       (3, 'Info', 'Informatique Bureautique', 30, '', 1),
       (4, 'EE', 'Expression écrite', 40, '', 1),
       (5, 'Anglais', 'Anglais Professionnel', 50, '', 1),
       (7, 'nezrouge', 'nezrouge', 80, '', 4),
       (8, 'grandeschaussures', 'godasses', 50, '', 4),
       (9, 'M_MilieuProf', 'Milieu professionnel', 70,
        'cv, lettre de motivation, insertion emploi\r\nconnaissance du metier\r\n', 5),
       (11, 'M_ccp', 'les 3 CCP', 240, 'CCP1, CCP2, CCP3', 5),
       (12, 'M_BONUS', 'Matières bonus', 60, 'Voltaire, PCS1', 5);

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau`
(
    `idNiveau` int(11)                             NOT NULL,
    `libelle`  varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`idNiveau`, `libelle`)
VALUES (2, 'Non Évalué'),
       (3, 'Non Acquis'),
       (4, ' À Renforcer'),
       (5, 'En Cours'),
       (6, 'Acquis');

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions`
(
    `idPromo`      int(11)     NOT NULL,
    `libelle`      varchar(30) NOT NULL,
    `dateDebut`    date        NOT NULL,
    `dateFin`      date        NOT NULL,
    `idFormation`  int(11)     NOT NULL,
    `verrouillage` tinyint(1) DEFAULT 0
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`idPromo`, `libelle`, `dateDebut`, `dateFin`, `idFormation`, `verrouillage`)
VALUES (2, 'TPSAMS 2020', '2020-06-01', '2021-01-31', 1, 1),
       (3, 'TPSAMS 2021', '2021-01-01', '2021-06-30', 1, 0),
       (5, 'TPCLOWN 2021', '2021-02-12', '2021-08-25', 4, 0),
       (8, 'TPSAMSCPF 2021', '2021-02-11', '2021-04-08', 5, 0);

-- --------------------------------------------------------

--
-- Structure de la table `stagiaires`
--

CREATE TABLE `stagiaires`
(
    `idStagiaire` int(11)     NOT NULL,
    `nom`         varchar(30) NOT NULL,
    `prenom`      varchar(30) NOT NULL,
    `idPromo`     int(11)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `stagiaires`
--

INSERT INTO `stagiaires` (`idStagiaire`, `nom`, `prenom`, `idPromo`)
VALUES (7, 'Croisier', 'Maxime', 3),
       (8, 'durand', 'marcel', 3),
       (9, 'Charlemagne', 'Gilles', 3),
       (10, 'Petit', 'Sulivan', 3),
       (11, 'Flash', 'Macqueen', 3),
       (12, 'toto', 'ttata', 3),
       (13, 'durand', 'rene', 8),
       (14, 'true', 'cedric', 8),
       (15, 'serue', 'marcel', 8),
       (16, 'Pioi', 'sylvie', 8);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users`
(
    `idUser`     int(11)     NOT NULL,
    `username`   varchar(20) NOT NULL,
    `password`   varchar(20) NOT NULL,
    `nom`        varchar(30) NOT NULL,
    `prenom`     varchar(30) NOT NULL,
    `specialite` varchar(255) DEFAULT NULL,
    `mail`       varchar(30)  DEFAULT NULL,
    `telephone`  varchar(10)  DEFAULT NULL,
    `admin`      tinyint(1)  NOT NULL,
    `actif`      tinyint(4)   DEFAULT 1
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `username`, `password`, `nom`, `prenom`, `specialite`, `mail`, `telephone`, `admin`,
                     `actif`)
VALUES (1, 'abataille', '1234', 'Bataille', 'Antoine', '', '', '', 1, 1),
       (2, 'E20', '123', 'Croisier', 'Maxime', NULL, NULL, NULL, 1, 1),
       (3, 'operart', '1234', 'Perart', 'Olivier', 'informatique', '', '', 0, 1),
       (4, 'spilloy', '1234', 'Pilloy', 'Sandrine', '', '', '', 0, 1),
       (5, 'gdelattre', '1234', 'Delattre', 'Gaëlle', '', '', '', 0, 1),
       (6, 'dvandenbrouque', '1234', 'Vandenbrouque', 'Dominique', '', '', '', 0, 1),
       (7, 'ileroux', '1234', 'Leroux', 'Isabelle', '', '', '', 0, 1),
       (8, 'alavoye', '1234', 'Lavoye', 'Audrey', '', '', '', 0, 1),
       (9, 'nhecquefeuille', '1234', 'Hecquefeuille', 'Nathalie', '', '', '', 0, 1),
       (10, 'jfrancoise', '1234', 'Joly', 'François', '', '', '0111111111', 0, 1),
       (11, 'AROSE', '1234', 'Rose', 'Audrey', '', 'sec.etab@st-jo.com', '0321990699', 1, 1),
       (13, 'sbocquet', '987654', 'Bocquet', 'Steeve', 'aucun', 'ddkjf@dlfkldk.fr', '', 0, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `affectation`
--
ALTER TABLE `affectation`
    ADD PRIMARY KEY (`idUser`, `idModule`, `idPromo`),
    ADD KEY `Affectation_Modules0_FK` (`idModule`),
    ADD KEY `Affectation_Promos1_FK` (`idPromo`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
    ADD PRIMARY KEY (`idCommentaire`, `dateHeure`),
    ADD KEY `Commentaire_Formations_FK` (`idFormation`),
    ADD KEY `Commentaire_Users0_FK` (`idUser`);

--
-- Index pour la table `compterendu`
--
ALTER TABLE `compterendu`
    ADD PRIMARY KEY (`idCompteRendu`, `idPromo`, `idModule`, `idUser`, `dateEntree`) USING BTREE,
    ADD KEY `CompteRendu_Modules_FK` (`idModule`),
    ADD KEY `CompteRendu_Users0_FK` (`idUser`),
    ADD KEY `CompteRendu_Promo_FK` (`idPromo`);

--
-- Index pour la table `formations`
--
ALTER TABLE `formations`
    ADD PRIMARY KEY (`idFormation`),
    ADD UNIQUE KEY `reference` (`reference`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
    ADD PRIMARY KEY (`idModule`),
    ADD UNIQUE KEY `reference` (`reference`),
    ADD KEY `Modules_Formations_FK` (`idFormation`);

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
    ADD PRIMARY KEY (`idNiveau`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
    ADD PRIMARY KEY (`idPromo`),
    ADD UNIQUE KEY `libelle` (`libelle`),
    ADD KEY `Promos_Formations_FK` (`idFormation`);

--
-- Index pour la table `stagiaires`
--
ALTER TABLE `stagiaires`
    ADD PRIMARY KEY (`idStagiaire`),
    ADD KEY `Stagiaires_Promos_FK` (`idPromo`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`idUser`),
    ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
    MODIFY `idCommentaire` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `compterendu`
--
ALTER TABLE `compterendu`
    MODIFY `idCompteRendu` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 20;

--
-- AUTO_INCREMENT pour la table `formations`
--
ALTER TABLE `formations`
    MODIFY `idFormation` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
    MODIFY `idModule` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT pour la table `niveau`
--
ALTER TABLE `niveau`
    MODIFY `idNiveau` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
    MODIFY `idPromo` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 9;

--
-- AUTO_INCREMENT pour la table `stagiaires`
--
ALTER TABLE `stagiaires`
    MODIFY `idStagiaire` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 17;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
    MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 14;

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
