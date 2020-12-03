-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema proj_final
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema proj_final
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `proj_final` DEFAULT CHARACTER SET utf8 ;
USE `proj_final` ;

-- -----------------------------------------------------
-- Table `proj_final`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(128) NOT NULL,
  `email` VARCHAR(64) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `cargo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(256) NULL,
  `foto` VARCHAR(128) NULL,
  `hash_pwd` VARCHAR(64) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`curso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`curso` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `horas` INT NOT NULL,
  `tamanho_grupos` INT NOT NULL,
  `criador_id` INT NOT NULL
  PRIMARY KEY (`id`)),
  INDEX `fk_curso_usuario1_idx` (`criador_id` ASC),
  CONSTRAINT `fk_curso_usuario1`
    FOREIGN KEY (`criador_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`secao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`secao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `data_inicio` DATE NULL,
  `data_final` DATE NULL,
  `curso_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_secao_curso1_idx` (`curso_id` ASC),
  CONSTRAINT `fk_secao_curso1`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proj_final`.`curso` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`aula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`aula` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `descricao` VARCHAR(256) NULL,
  `duracao` INT NULL,
  `video` VARCHAR(128) NOT NULL,
  `secao_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_aula_secao1_idx` (`secao_id` ASC),
  CONSTRAINT `fk_aula_secao1`
    FOREIGN KEY (`secao_id`)
    REFERENCES `proj_final`.`secao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`material_apoio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`material_apoio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `tipo` VARCHAR(45) NULL,
  `secao_id` INT NOT NULL,
  `conteudo` VARCHAR(4000) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_material_apoio_secao1_idx` (`secao_id` ASC),
  CONSTRAINT `fk_material_apoio_secao1`
    FOREIGN KEY (`secao_id`)
    REFERENCES `proj_final`.`secao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`trabalho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`trabalho` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `descricao` VARCHAR(256) NULL,
  `data_hora_limite` DATETIME NULL,
  `anexo` VARCHAR(128) NULL,
  `secao_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_trabalho_secao1_idx` (`secao_id` ASC),
  CONSTRAINT `fk_trabalho_secao1`
    FOREIGN KEY (`secao_id`)
    REFERENCES `proj_final`.`secao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`grupo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`grupo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `curso_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_grupo_curso1_idx` (`curso_id` ASC),
  CONSTRAINT `fk_grupo_curso1`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proj_final`.`curso` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`teste`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`teste` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `descricao` VARCHAR(256) NULL,
  `data_hora_limite` DATETIME NULL,
  `limite_tempo` INT NULL,
  `secao_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_teste_secao1_idx` (`secao_id` ASC),
  CONSTRAINT `fk_teste_secao1`
    FOREIGN KEY (`secao_id`)
    REFERENCES `proj_final`.`secao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`questao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`questao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `enunciado` VARCHAR(128) NOT NULL,
  `tipo` INT NOT NULL,
  `imagem` VARCHAR(128) NULL,
  `nota_maxima` DECIMAL(2,2) NOT NULL,
  `teste_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_questao_1_idx` (`teste_id` ASC),
  CONSTRAINT `fk_questao_1`
    FOREIGN KEY (`teste_id`)
    REFERENCES `proj_final`.`teste` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`opcao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`opcao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `resposta` TINYINT NOT NULL,
  `valor` VARCHAR(64) NOT NULL,
  `questao_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_opcao_questao1_idx` (`questao_id` ASC),
  CONSTRAINT `fk_opcao_questao1`
    FOREIGN KEY (`questao_id`)
    REFERENCES `proj_final`.`questao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`topico_forum`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`topico_forum` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(64) NOT NULL,
  `criado_em` DATETIME NOT NULL,
  `texto` VARCHAR(1024) NOT NULL,
  `permite_resposta` TINYINT NOT NULL,
  `curso_id` INT NOT NULL,
  `criador_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_topico_forum_curso1_idx` (`curso_id` ASC),
  INDEX `fk_topico_forum_usuario1_idx` (`criador_id` ASC),
  CONSTRAINT `fk_topico_forum_curso1`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proj_final`.`curso` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_topico_forum_usuario1`
    FOREIGN KEY (`criador_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`topico_resposta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`topico_resposta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `texto` VARCHAR(1024) NOT NULL,
  `data_hora` DATETIME NOT NULL,
  `topico_forum_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_topico_resposta_topico_forum1_idx` (`topico_forum_id` ASC),
  INDEX `fk_topico_resposta_usuario1_idx` (`usuario_id` ASC),
  CONSTRAINT `fk_topico_resposta_topico_forum1`
    FOREIGN KEY (`topico_forum_id`)
    REFERENCES `proj_final`.`topico_forum` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_topico_resposta_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`mensagem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`mensagem` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `conteudo` VARCHAR(512) NOT NULL,
  `data_hora` DATETIME NOT NULL,
  `lido` TINYINT NOT NULL,
  `remetente_id` INT NOT NULL,
  `destinatario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_mensagem_usuario_idx` (`remetente_id` ASC),
  INDEX `fk_mensagem_usuario1_idx` (`destinatario_id` ASC),
  CONSTRAINT `fk_mensagem_usuario`
    FOREIGN KEY (`remetente_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_mensagem_usuario1`
    FOREIGN KEY (`destinatario_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`grupo_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`grupo_usuario` (
  `usuario_id` INT NOT NULL,
  `grupo_id` INT NOT NULL,
  PRIMARY KEY (`usuario_id`, `grupo_id`),
  INDEX `fk_grupo_usuario_grupo1_idx` (`grupo_id` ASC),
  CONSTRAINT `fk_grupo_usuario_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_grupo_usuario_grupo1`
    FOREIGN KEY (`grupo_id`)
    REFERENCES `proj_final`.`grupo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`curso_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`curso_usuario` (
  `curso_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`curso_id`, `usuario_id`),
  INDEX `fk_curso_has_usuario_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_curso_has_usuario_curso1_idx` (`curso_id` ASC),
  CONSTRAINT `fk_curso_has_usuario_curso1`
    FOREIGN KEY (`curso_id`)
    REFERENCES `proj_final`.`curso` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_curso_has_usuario_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`aula_assistida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`aula_assistida` (
  `aula_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `completo` TINYINT NOT NULL,
  `tempo_assistido` INT NOT NULL,
  PRIMARY KEY (`aula_id`, `usuario_id`),
  INDEX `fk_aula_has_usuario_usuario1_idx` (`usuario_id` ASC),
  INDEX `fk_aula_has_usuario_aula1_idx` (`aula_id` ASC),
  CONSTRAINT `fk_aula_has_usuario_aula1`
    FOREIGN KEY (`aula_id`)
    REFERENCES `proj_final`.`aula` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_aula_has_usuario_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `proj_final`.`usuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`trabalho_entregue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`trabalho_entregue` (
  `trabalho_id` INT NOT NULL,
  `grupo_id` INT NOT NULL,
  `url` VARCHAR(128) NOT NULL,
  `data_hora` DATETIME NOT NULL,
  `nota` DECIMAL(2,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`trabalho_id`, `grupo_id`),
  INDEX `fk_trabalho_has_grupo_grupo1_idx` (`grupo_id` ASC),
  INDEX `fk_trabalho_has_grupo_trabalho1_idx` (`trabalho_id` ASC),
  CONSTRAINT `fk_trabalho_has_grupo_trabalho1`
    FOREIGN KEY (`trabalho_id`)
    REFERENCES `proj_final`.`trabalho` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_trabalho_has_grupo_grupo1`
    FOREIGN KEY (`grupo_id`)
    REFERENCES `proj_final`.`grupo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proj_final`.`opcao_selecionada`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `proj_final`.`opcao_selecionada` (
  `opcao_id` INT NOT NULL,
  `grupo_id` INT NOT NULL,
  `selecionado` TINYINT NOT NULL,
  PRIMARY KEY (`opcao_id`, `grupo_id`),
  INDEX `fk_opcao_has_grupo_grupo1_idx` (`grupo_id` ASC),
  INDEX `fk_opcao_has_grupo_opcao1_idx` (`opcao_id` ASC),
  CONSTRAINT `fk_opcao_has_grupo_opcao1`
    FOREIGN KEY (`opcao_id`)
    REFERENCES `proj_final`.`opcao` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_opcao_has_grupo_grupo1`
    FOREIGN KEY (`grupo_id`)
    REFERENCES `proj_final`.`grupo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
