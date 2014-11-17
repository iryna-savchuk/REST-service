CREATE DATABASE IF NOT EXISTS address_db;

CREATE TABLE IF NOT EXISTS address_db.address (
  `ADDRESSID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The unique address ID.',
  `LABEL` varchar(100) NOT NULL COMMENT 'The name of the person or organisation to which the address belongs.',
  `STREET` varchar(100) NOT NULL COMMENT 'The name of the street.',
  `HOUSENUMBER` varchar(10) NOT NULL COMMENT 'The house number (and any optional additions).',
  `POSTALCODE` varchar(6) NOT NULL COMMENT 'The postal code for the address.',
  `CITY` varchar(10) NOT NULL COMMENT 'The city in which the address is located.',
  `COUNTRY` varchar(100) NOT NULL COMMENT 'The country in which the address is located.',
  PRIMARY KEY (`ADDRESSID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A physical address belonging to a person or organisation.' AUTO_INCREMENT=28 ;



INSERT INTO address_db.address (`ADDRESSID`, `LABEL`, `STREET`, `HOUSENUMBER`, `POSTALCODE`, `CITY`, `COUNTRY`) VALUES
(26, 'School', 'Lesi Ukrainky', '112', '91222', 'Kyiv', 'Ukraine'),
(27, 'National University', 'Svobody', '4', '93000', 'Kharkiv', 'Ukraine');
