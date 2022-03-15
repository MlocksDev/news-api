/* DROP AND RECREATE DATABASE */
DROP DATABASE IF EXISTS api_news;

CREATE DATABASE api_news;

USE api_news;

/* CREATE TABLE  `users` */
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

/* CREATE TABLE 'authors'*/
CREATE TABLE IF NOT EXISTS `authors` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(60) NOT NULL,
  `gender` ENUM('F', 'M') NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `authors_users_id` (`users_id` ASC),
  CONSTRAINT `authors_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

/* CREATE TABLE  `comment` */
CREATE TABLE IF NOT EXISTS `comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

/* CREATE TABLE  `news` */
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `author_id` INT NOT NULL,
  `comment_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `subtitle` VARCHAR(155) NOT NULL,
  `description` TEXT NOT NULL,
  `published_at` DATETIME NULL,
  `slug` VARCHAR(255) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `news_authors_id` (`author_id` ASC),
  INDEX `news_comment_id` (`comment_id` ASC),
  CONSTRAINT `news_authors` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

/* CREATE TABLE  `image_news` */
CREATE TABLE IF NOT EXISTS `image_news` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `news_id` INT NOT NULL,
  `image` TEXT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `image_news_news` (`news_id` ASC),
  CONSTRAINT `image_news_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

/* CREATE TABLE  `migrations` */
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;