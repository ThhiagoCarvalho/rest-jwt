-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema aula_api_2024
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema aula_api_2024
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `aula_api_2024` DEFAULT CHARACTER SET utf8 ;
USE `aula_api_2024` ;

-- -----------------------------------------------------
-- Table `aula_api_2024`.`Cargo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `aula_api_2024`.`Cargo` (
  `idCargo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeCargo` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`idCargo`),
  UNIQUE INDEX `idCargo_UNIQUE` (`idCargo` ASC),
  UNIQUE INDEX `nomeCargo_UNIQUE` (`nomeCargo` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aula_api_2024`.`Funcionario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `aula_api_2024`.`Funcionario` (
  `idFuncionario` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeFuncionario` VARCHAR(128) NULL,
  `email` VARCHAR(64) NULL,
  `senha` VARCHAR(64) NULL,
  `recebeValeTransporte` TINYINT(1) NULL,
  `Cargo_idCargo` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idFuncionario`),
  UNIQUE INDEX `idFuncionario_UNIQUE` (`idFuncionario` ASC),
  INDEX `fk_Funcionario_Cargo_idx` (`Cargo_idCargo` ASC),
  CONSTRAINT `fk_Funcionario_Cargo`
    FOREIGN KEY (`Cargo_idCargo`)
    REFERENCES `aula_api_2024`.`Cargo` (`idCargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `aula_api_2024`.`Cargo`
-- -----------------------------------------------------
START TRANSACTION;
USE `aula_api_2024`;
INSERT INTO `aula_api_2024`.`Cargo` (`idCargo`, `nomeCargo`) VALUES (1, 'Administrador');
INSERT INTO `aula_api_2024`.`Cargo` (`idCargo`, `nomeCargo`) VALUES (2, 'Técnico em Informática Jr');
INSERT INTO `aula_api_2024`.`Cargo` (`idCargo`, `nomeCargo`) VALUES (3, 'Técnico em Informática Pleno');
INSERT INTO `aula_api_2024`.`Cargo` (`idCargo`, `nomeCargo`) VALUES (4, 'Analista de Sistemas Jr');

COMMIT;

