CREATE TABLE `user` (
  `id_user` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(320) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL UNIQUE,
  `password` CHAR(64) NOT NULL,
  `admin` BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE `folder` (
  `id_folder` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `id_parent` INT DEFAULT NULL,
  `public` BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (`id_parent`) REFERENCES `folder` (`id_folder`) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `file` (
  `id_file` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_user` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `size` INT NOT NULL,
  `mime_type` VARCHAR(255) NOT NULL,
  `upload_date` DATETIME NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `folder_permission` (
  `id_user` INT NOT NULL,
  `id_folder` INT NOT NULL,
  PRIMARY KEY (`id_user`, `id_folder`),
  FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  FOREIGN KEY (`id_folder`) REFERENCES `folder` (`id_folder`) ON UPDATE RESTRICT ON DELETE RESTRICT
) ENGINE=InnoDB;

drop table folder_permission;
drop table folder;
drop table file;
drop table user;