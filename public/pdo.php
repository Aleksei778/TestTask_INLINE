<?php

// настройки подлкючения к БД
$dbHost = 'db';
$dbName = 'blog_db';
$dbUser = 'blog_user';
$dbPass = 'blog_password';

function getDbConnection() {
    global $dbHost, $dbName, $dbUser, $dbPass;

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    } catch(PDOException $pdo_e) {
        die("Ошибка БД: " . $pdo_e->getMessage());
    } catch (Exception $e) {
        die("Ошибка: " . $e->getMessage());
    }
}