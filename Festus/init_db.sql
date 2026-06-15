CREATE DATABASE IF NOT EXISTS raic_open_data CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE raic_open_data;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(40) DEFAULT NULL,
  institution VARCHAR(150) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('citizen','publisher','admin') NOT NULL DEFAULT 'citizen',
  verification_token VARCHAR(64) DEFAULT NULL,
  is_verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX (role),
  INDEX (is_verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  reset_token VARCHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS information_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tracking_number VARCHAR(50) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  ministry VARCHAR(150) NOT NULL,
  category VARCHAR(100) NOT NULL,
  attachment_path VARCHAR(255) DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Pending',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX (tracking_number),
  INDEX (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS datasets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  ministry VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  tags VARCHAR(255) DEFAULT NULL,
  author VARCHAR(150) DEFAULT NULL,
  year INT DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Draft',
  file_path VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  INDEX (category),
  INDEX (ministry)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
