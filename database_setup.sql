-- Database setup untuk Giacinta Blog
-- Jalankan script ini di phpMyAdmin atau SQLyog

-- Buat database
CREATE DATABASE IF NOT EXISTS giacinta_blog CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Pilih database
USE giacinta_blog;

-- Buat tabel comments
CREATE TABLE IF NOT EXISTS comments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    section VARCHAR(50) NOT NULL COMMENT 'Section blog: video, recommendations, synopsis',
    NAME VARCHAR(100) NOT NULL COMMENT 'Nama komentator',
    COMMENT TEXT NOT NULL COMMENT 'Isi komentar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu komentar dibuat',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu komentar diupdate',
    PRIMARY KEY (id),
    INDEX idx_section (section),
    INDEX idx_created_at (created_at)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Tabel untuk menyimpan komentar blog';

-- 