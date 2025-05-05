-- Создание БД
CREATE DATABASE IF NOT EXISTS blog_db;
USE blog_db;

-- Создание таблицы "Записи"
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создание таблицы "Комментарии"
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postId INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postId) REFERENCES posts(id)
);

-- Создание индексов для ускорения поиска
CREATE INDEX idx_posts_userId ON posts(userId);
CREATE INDEX idx_posts_postId ON comments(postId);

CREATE FULLTEXT INDEX idx_comments_body ON comments(body);