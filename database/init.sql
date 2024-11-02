-- -----------------------------------------------------
-- Schema developer_test
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `developer_test` DEFAULT CHARACTER SET utf8 ;
USE `developer_test`;

-- -----------------------------------------------------
-- Table product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `price` INT UNSIGNED NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_category` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_has_product_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_has_product_category` (
  `product_id` INT UNSIGNED NOT NULL,
  `product_category_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`, `product_category_id`),
  INDEX `product_category_id` (`product_category_id` ASC),
  INDEX `product_id` (`product_id` ASC),
  CONSTRAINT `fk_product_has_product_category_product`
    FOREIGN KEY (`product_id`)
    REFERENCES `product` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_product_category_product_category1`
    FOREIGN KEY (`product_category_id`)
    REFERENCES `product_category` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
