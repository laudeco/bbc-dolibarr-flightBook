
-- -----------------------------------------------------
-- Table `BBC_reservations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `llx_bbc_reservations` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` text NOT NULL,
  `pilote` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nbrpax` int(11) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

