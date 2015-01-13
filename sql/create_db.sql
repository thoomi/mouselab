-- MySQL Script generated by MySQL Workbench
-- So 11 Jan 2015 20:55:20 CET
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema testlabdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `testlabdb` ;
USE `testlabdb` ;

-- -----------------------------------------------------
-- Table `testlabdb`.`tl_participant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `testlabdb`.`tl_participant` ;

CREATE TABLE IF NOT EXISTS `testlabdb`.`tl_participant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `participated_at` DATETIME NOT NULL,
  `participation_id` VARCHAR(7) NOT NULL,
  `dropout` TINYINT(1) NOT NULL,
  `location` CHAR NOT NULL COMMENT 'Either:' /* comment truncated */ /*L = Labor,
O = Online,
T = Test*/,
  `participation_group` VARCHAR(2) NOT NULL COMMENT 'Either:' /* comment truncated */ /*G1, G2 or G3*/,
  `total_time` INT(11) NULL,
  `age` INT(11) NULL,
  `gender` CHAR NULL,
  `graduation` SMALLINT(6) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testlabdb`.`tl_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `testlabdb`.`tl_user` ;

CREATE TABLE IF NOT EXISTS `testlabdb`.`tl_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL,
  `participate_in_other` TINYINT(1) NOT NULL,
  `location` CHAR NOT NULL,
  `comments` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testlabdb`.`tl_experiment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `testlabdb`.`tl_experiment` ;

CREATE TABLE IF NOT EXISTS `testlabdb`.`tl_experiment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `task` CHAR NOT NULL COMMENT 'A, B oder C',
  `task_pos` SMALLINT(6) NOT NULL COMMENT '1, 2 oder 3',
  `chosen_option_rank` SMALLINT(6) NOT NULL,
  `time_to_decision` INT NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_experiment_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_experiment_tl_participant1`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `testlabdb`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testlabdb`.`tl_stress_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `testlabdb`.`tl_stress_question` ;

CREATE TABLE IF NOT EXISTS `testlabdb`.`tl_stress_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_sum` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  `tl_experiment_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`, `tl_experiment_id`),
  INDEX `fk_tl_stress_question_tl_participant_idx` (`tl_participant_id` ASC),
  INDEX `fk_tl_stress_question_tl_experiment1_idx` (`tl_experiment_id` ASC),
  CONSTRAINT `fk_tl_stress_question_tl_participant`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `testlabdb`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tl_stress_question_tl_experiment1`
    FOREIGN KEY (`tl_experiment_id`)
    REFERENCES `testlabdb`.`tl_experiment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testlabdb`.`tl_maximising_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `testlabdb`.`tl_maximising_question` ;

CREATE TABLE IF NOT EXISTS `testlabdb`.`tl_maximising_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_4` SMALLINT(6) NOT NULL,
  `q_num_5` SMALLINT(6) NOT NULL,
  `q_num_6` SMALLINT(6) NOT NULL,
  `q_num_7` SMALLINT(6) NOT NULL,
  `q_num_8` SMALLINT(6) NOT NULL,
  `q_num_9` SMALLINT(6) NOT NULL,
  `q_num_10` SMALLINT(6) NOT NULL,
  `q_num_11` SMALLINT(6) NOT NULL,
  `q_num_12` SMALLINT(6) NOT NULL,
  `q_num_13` SMALLINT(6) NOT NULL,
  `q_sum` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant1`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `testlabdb`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
