-- MySQL Script generated by MySQL Workbench
-- 06/26/16 21:05:05
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema skopietz_grundlagenstudie
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema skopietz_grundlagenstudie
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `skopietz_grundlagenstudie` ;
USE `skopietz_grundlagenstudie` ;

-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_participant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_participant` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_participant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `participated_at` DATETIME NOT NULL,
  `participation_id` VARCHAR(8) NOT NULL,
  `dropout` TINYINT(1) NOT NULL,
  `location` CHAR NOT NULL COMMENT 'Either:\nL = Labor,\nO = Online,\nT = Test',
  `participation_group` VARCHAR(2) NOT NULL COMMENT 'Either:\nG1, G2 or G3',
  `participation_condition` VARCHAR(2) NOT NULL COMMENT 'Either:\nC1 or C2',
  `previous_participant` TINYINT(1) NOT NULL,
  `reward` SMALLINT(6) NOT NULL,
  `payout` FLOAT NULL,
  `total_time` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_user` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) NOT NULL,
  `participate_in_other` TINYINT(1) NOT NULL,
  `location` CHAR NOT NULL,
  `comments` VARCHAR(500) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_experiment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_experiment` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_experiment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `task` VARCHAR(3) NOT NULL COMMENT 'A, B oder C',
  `task_pos` SMALLINT(6) NOT NULL COMMENT '1, 2 oder 3',
  `time_to_finish` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_experiment_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_experiment_tl_participant1`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_stress_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_stress_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_stress_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_8` SMALLINT(6) NOT NULL,
  `q_me4` SMALLINT(6) NOT NULL,
  `time_to_answer` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  `tl_experiment_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`, `tl_experiment_id`),
  INDEX `fk_tl_stress_question_tl_participant_idx` (`tl_participant_id` ASC),
  INDEX `fk_tl_stress_question_tl_experiment1_idx` (`tl_experiment_id` ASC),
  CONSTRAINT `fk_tl_stress_question_tl_participant`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tl_stress_question_tl_experiment1`
    FOREIGN KEY (`tl_experiment_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_experiment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_resilience_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_resilience_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_resilience_question` (
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
  `q_sum` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant1`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_demographics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_demographics` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_demographics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `age` INT(11) NOT NULL,
  `gender` CHAR NOT NULL,
  `graduation` SMALLINT(6) NOT NULL,
  `live_status` SMALLINT(6) NOT NULL,
  `device` VARCHAR(45) NOT NULL,
  `academic_degree` SMALLINT(6) NOT NULL,
  `apprenticeship` TINYINT(1) NOT NULL,
  `psycho_studies` TINYINT(1) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_demographics_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_demographics_tl_participant1`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_maximizing_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_maximizing_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_maximizing_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_4` SMALLINT(6) NOT NULL,
  `q_num_5` SMALLINT(6) NOT NULL,
  `q_num_6` SMALLINT(6) NOT NULL,
  `q_sum` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant10`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_trial`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_trial` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_trial` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` INT(11) NOT NULL,
  `pair_comparison` SMALLINT(6) NOT NULL,
  `number_of_acquisitions` SMALLINT(6) NOT NULL,
  `chosen_option` SMALLINT(6) NOT NULL,
  `order_of_acqusitions` VARCHAR(7) NOT NULL DEFAULT '0:0:0:0' COMMENT 'Beispiel:\n\nA:C:D:0\n\nIn diesem Trial wurde zuerst Cue A dann Cue C und danach Cue B aufgedecket.',
  `time_to_finish` INT(11) NOT NULL,
  `acquisition_time` INT(11) NOT NULL,
  `acquired_weights` FLOAT NOT NULL,
  `local_accuracy` FLOAT NOT NULL,
  `local_accuracy2` FLOAT NOT NULL,
  `acquisition_pattern` SMALLINT(6) NOT NULL,
  `score` INT(11) NOT NULL,
  `tl_experiment_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_experiment_id`),
  INDEX `fk_tl_trial_tl_experiment1_idx` (`tl_experiment_id` ASC),
  CONSTRAINT `fk_tl_trial_tl_experiment1`
    FOREIGN KEY (`tl_experiment_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_experiment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_meta_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_meta_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_meta_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_4` SMALLINT(6) NOT NULL,
  `q_num_5` SMALLINT(6) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant100`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_nfc_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_nfc_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_nfc_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_4` SMALLINT(6) NOT NULL,
  `q_sum` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant101`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `skopietz_grundlagenstudie`.`tl_risk_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `skopietz_grundlagenstudie`.`tl_risk_question` ;

CREATE TABLE IF NOT EXISTS `skopietz_grundlagenstudie`.`tl_risk_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `q_num_1` SMALLINT(6) NOT NULL,
  `q_num_2` SMALLINT(6) NOT NULL,
  `q_num_3` SMALLINT(6) NOT NULL,
  `q_num_4` INT(11) NOT NULL,
  `tl_participant_id` INT NOT NULL,
  PRIMARY KEY (`id`, `tl_participant_id`),
  INDEX `fk_tl_maximising_question_tl_participant1_idx` (`tl_participant_id` ASC),
  CONSTRAINT `fk_tl_maximising_question_tl_participant102`
    FOREIGN KEY (`tl_participant_id`)
    REFERENCES `skopietz_grundlagenstudie`.`tl_participant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
