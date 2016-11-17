-- --------------------------------------------------------
-- Host:                         192.168.26.24
-- Server Version:               5.0.24-standard-log - MySQL Community Edition - Standard (GPL)
-- Server Betriebssystem:        pc-linux-gnu
-- HeidiSQL Version:             7.0.0.4392
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportiere Struktur von Tabelle lemonadestand.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL auto_increment,
  `supplierId` int(11) default NULL,
  `reservationPrice` double default NULL,
  `reservationIncrease` double default NULL,
  `shopAroundCount` int(11) default NULL,
  `loyaltyOdds` double default NULL,
  `seed` double default NULL,
  `zoi` double default NULL,
  `price` double default NULL,
  `purchases` varchar(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3541001 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle lemonadestand.customer: 0 rows
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle lemonadestand.properties
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL auto_increment,
  `currentRound` int(11) default NULL,
  `roundEnd` datetime default NULL,
  `totalCustomers` int(11) default NULL,
  `baseFixedCost` double default NULL,
  `variableCost` double default NULL,
  `zoiMin` double default NULL,
  `zoiMode` double default NULL,
  `zoiMax` double default NULL,
  `reservationPriceMin` double default NULL,
  `reservationPriceMode` double default NULL,
  `reservationPriceMax` double default NULL,
  `reservationIncreaseMin` double default NULL,
  `reservationIncreaseMode` double default NULL,
  `reservationIncreaseMax` double default NULL,
  `loyaltyOddsMin` double default NULL,
  `loyaltyOddsMode` double default NULL,
  `loyaltyOddsMax` double default NULL,
  `marketShareReportCost` double default NULL,
  `newProcessFixCostAdder` double default NULL,
  `newProcessVariableCostAdder` double default NULL,
  `loyaltyBoostPercent` double default NULL,
  `loyaltyBoostPricePerCustomer` double default NULL,
  `shopAroundPercentages` varchar(250) default NULL,
  `percentageOfCustomersWillingToPurchase` varchar(250) default NULL,
  `marketResearchAvailable` varchar(250) default NULL,
  `advertisingAvailable` varchar(250) default NULL,
  `pricingSurveyAvailable` varchar(250) default NULL,
  `pricingSurveyCost` double default NULL,
  `customerReportAvailable` varchar(250) default NULL,
  `customerReportCost` double default NULL,
  `efficiencyAvailable` varchar(250) default NULL,
  `loyaltyAvailable` varchar(250) default NULL,
  `initialPrice` double default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1460 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle lemonadestand.properties: 1 rows
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` (`id`, `currentRound`, `roundEnd`, `totalCustomers`, `baseFixedCost`, `variableCost`, `zoiMin`, `zoiMode`, `zoiMax`, `reservationPriceMin`, `reservationPriceMode`, `reservationPriceMax`, `reservationIncreaseMin`, `reservationIncreaseMode`, `reservationIncreaseMax`, `loyaltyOddsMin`, `loyaltyOddsMode`, `loyaltyOddsMax`, `marketShareReportCost`, `newProcessFixCostAdder`, `newProcessVariableCostAdder`, `loyaltyBoostPercent`, `loyaltyBoostPricePerCustomer`, `shopAroundPercentages`, `percentageOfCustomersWillingToPurchase`, `marketResearchAvailable`, `advertisingAvailable`, `pricingSurveyAvailable`, `pricingSurveyCost`, `customerReportAvailable`, `customerReportCost`, `efficiencyAvailable`, `loyaltyAvailable`, `initialPrice`) VALUES
	(1459, 0, NULL, 1000, 85, 0.35, 0, 0.02, 0.04, 0.3, 0.95, 2, 0, 0.25, 1, 0, 0.45, 1, 0.1, 20, -0.1, 0.1, 0.1, '10,25,15,50', '95,95,95,95,95,95', 'false,false,false,false,false,false', 'false,false,false,false,false,false', 'false,false,false,false,false,false', 15, 'false,false,false,false,false,false', 20, 'false,false,false,false,false,false', 'false,false,false,false,false,false', 0.5);
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle lemonadestand.team
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) default NULL,
  `password` varchar(250) default NULL,
  `customerReportPurchased` char(1) default NULL,
  `efficiencyPurchased` char(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3946 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle lemonadestand.team: 0 rows
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
/*!40000 ALTER TABLE `team` ENABLE KEYS */;


-- Exportiere Struktur von Tabelle lemonadestand.teamRound
CREATE TABLE IF NOT EXISTS `teamRound` (
  `id` int(11) NOT NULL auto_increment,
  `teamId` int(11) default NULL,
  `round` int(11) default NULL,
  `price` double default NULL,
  `investInEfficiency` char(1) default NULL,
  `investInLoyalty` char(1) default NULL,
  `buyMarketResearch` char(1) default NULL,
  `buyPricingSurvey` char(1) default NULL,
  `buyCustomerReport` char(1) default NULL,
  `advertisingBudget` double default NULL,
  `fixedCost` double default NULL,
  `variableCost` double default NULL,
  `cash` double default NULL,
  `revenue` double default NULL,
  `revenueShare` double default NULL,
  `volumeShare` double default NULL,
  `customerBase` int(11) default NULL,
  `highestPrice` double default NULL,
  `lowestPrice` double default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6917 DEFAULT CHARSET=latin1;

-- Exportiere Daten aus Tabelle lemonadestand.teamRound: 0 rows
/*!40000 ALTER TABLE `teamRound` DISABLE KEYS */;
/*!40000 ALTER TABLE `teamRound` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
