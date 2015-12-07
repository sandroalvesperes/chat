SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';
SET NAMES UTF8 COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `support_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `support_user` ;

CREATE  TABLE IF NOT EXISTS `support_user` (
  `id_support_user` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `sex` ENUM('M','F') NOT NULL ,
  `email` VARCHAR(120) NOT NULL ,
  `password` CHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL COMMENT 'MD5' ,
  `last_activity` DATETIME NULL ,
  `typing` BIT NOT NULL DEFAULT 0 ,
  `online` BIT NOT NULL DEFAULT 0 ,
  `active` BIT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id_support_user`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uq_user_email` ON `support_user` (`email` ASC) ;


-- -----------------------------------------------------
-- Table `client_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `client_user` ;

CREATE  TABLE IF NOT EXISTS `client_user` (
  `id_client_user` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(60) NOT NULL ,
  `email` VARCHAR(120) NOT NULL ,
  `sex` ENUM('M','F') NOT NULL ,
  `last_activity` DATETIME NOT NULL ,
  `typing` BIT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_client_user`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `chat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chat` ;

CREATE  TABLE IF NOT EXISTS `chat` (
  `id_chat` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_client_user` INT UNSIGNED NOT NULL ,
  `id_support_user` SMALLINT UNSIGNED NULL ,
  `subject` VARCHAR(45) NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `closed` DATETIME NULL ,
  `closed_by` ENUM('Support','Client','System') NULL ,
  `rate` TINYINT UNSIGNED NULL COMMENT '1 to 5' ,
  PRIMARY KEY (`id_chat`) ,
    FOREIGN KEY (`id_client_user` )
    REFERENCES `client_user` (`id_client_user` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
    FOREIGN KEY (`id_support_user` )
    REFERENCES `support_user` (`id_support_user` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_chat_client_user1` ON `chat` (`id_client_user` ASC) ;

CREATE INDEX `fk_chat_support_user1` ON `chat` (`id_support_user` ASC) ;


-- -----------------------------------------------------
-- Table `chat_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chat_message` ;

CREATE  TABLE IF NOT EXISTS `chat_message` (
  `id_chat_message` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_chat` BIGINT UNSIGNED NOT NULL ,
  `sent_by` ENUM('Support', 'Client') NOT NULL ,
  `sent_by_id` INT UNSIGNED NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `message` TEXT NOT NULL ,
  PRIMARY KEY (`id_chat_message`) ,
    FOREIGN KEY (`id_chat` )
    REFERENCES `chat` (`id_chat` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_chat_message_chat1` ON `chat_message` (`id_chat` ASC) ;


-- -----------------------------------------------------
-- Table `param`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `param` ;

CREATE  TABLE IF NOT EXISTS `param` (
  `id_param` TINYINT ZEROFILL NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(20) NOT NULL ,
  `value` VARCHAR(120) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id_param`) )
ENGINE = MyISAM;

CREATE UNIQUE INDEX `un_param_name` ON `param` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `support_user_access`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `support_user_access` ;

CREATE  TABLE IF NOT EXISTS `support_user_access` (
  `id_support_user` SMALLINT UNSIGNED NOT NULL ,
  `maintain_user` BIT NOT NULL DEFAULT 0 ,
  `support_status` BIT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_support_user`) ,
    FOREIGN KEY (`id_support_user` )
    REFERENCES `support_user` (`id_support_user` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_support_user_access_support_user1` ON `support_user_access` (`id_support_user` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `support_user`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `support_user` (`id_support_user`, `name`, `sex`, `email`, `password`, `last_activity`, `typing`, `online`, `active`) VALUES (null, 'Admin', 'M', 'your.email@yourhost.com.br', '81dc9bdb52d04dc20036dbd8313ed055', NOW(), 0, 0, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `param`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `param` (`id_param`, `name`, `value`, `description`) VALUES (null, 'STATUS', '0', 'support status (0 or 1)');
INSERT INTO `param` (`id_param`, `name`, `value`, `description`) VALUES (null, 'EMAIL', 'you_email@your_host.com', 'this is the e-mail that will be used to receive the messages from the clients');
INSERT INTO `param` (`id_param`, `name`, `value`, `description`) VALUES (null, 'WAITING_TOO_MUCH', '7', 'this is to highlight the client when the he is waiting to much to receive support. The time is in minutes');
INSERT INTO `param` (`id_param`, `name`, `value`, `description`) VALUES (null, 'SET_OFFLINE_IN', '60', 'if the support has no activity for a time that exceeds this parameter, the user will be turned offline automatically. This time is in minutes');

COMMIT;

-- -----------------------------------------------------
-- Data for table `support_user_access`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `support_user_access` (`id_support_user`, `maintain_user`, `support_status`) VALUES (1, 1, 1);

COMMIT;