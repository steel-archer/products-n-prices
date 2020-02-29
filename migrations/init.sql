CREATE DATABASE IF NOT EXISTS `products_n_prices`;

CREATE USER 'products_n_prices'@'localhost' IDENTIFIED BY '##PASSWORD##';

GRANT ALL PRIVILEGES ON products_n_prices.* TO 'products_n_prices'@'localhost';

CREATE TABLE `products_n_prices`.`currencies` (
  `code` char(3) NOT NULL,
  `exchange_rate` FLOAT NOT NULL,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `products_n_prices`.`currencies` VALUES ('GBP', '1.0'), ('USD', '2.55'), ('CAD', '3.0');

CREATE TABLE `products_n_prices`.`products` (
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `normal_price_override` BOOLEAN NOT NULL DEFAULT FALSE,
  `special_price_override` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `products_n_prices`.`normal_prices` (
  `product_code` varchar(255) NOT NULL,
  `currency_code` char(3) NOT NULL,
  `price` FLOAT NOT NULL,
  PRIMARY KEY (product_code, currency_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `products_n_prices`.`special_prices` (
  `product_code` varchar(255) NOT NULL,
  `currency_code` char(3) NOT NULL,
  `price` FLOAT NOT NULL,
  PRIMARY KEY (product_code, currency_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
