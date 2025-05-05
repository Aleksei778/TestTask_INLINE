<?php
require_once 'pdo.php';

$commentsUrl = 'https://jsonplaceholder.typicode.com/comments';
$postsUrl = 'https://jsonplaceholder.typicode.com/posts';

function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die("Ошибка cURL: " . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}

try {
    $pdo = getDbConnection();

    // Очистка таблиц перед загрузкой
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE comments");
    $pdo->exec("TRUNCATE TABLE posts");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Загрузка постов
    $posts = fetchData($postsUrl);
    $postsCount = 0;

    $postsQuery = "INSERT INTO posts (id, userId, title, body) VALUES (:id, :userId, :title, :body)";
    $postsStmt = $pdo->prepare($postsQuery);

    foreach ($posts as $post) {
        $postsStmt->bindParam(':id', $post['id'], PDO::PARAM_INT);
        $postsStmt->bindParam(':userId', $post['userId'], PDO::PARAM_INT);
        $postsStmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
        $postsStmt->bindParam(':body', $post['body'], PDO::PARAM_STR);
        $postsStmt->execute();
        $postsCount++;
    }

    // Загрузка комментариев
    $comments = fetchData($commentsUrl);
    $commentsCount = 0;

    $commentsQuery = "INSERT INTO comments (id, postId, name, body, email) VALUES (:id, :postId, :name, :body, :email)";
    $commentsStmt = $pdo->prepare($commentsQuery);

    foreach ($comments as $comment) {
        $commentsStmt->bindParam(':id', $comment['id'], PDO::PARAM_INT);
        $commentsStmt->bindParam(':postId', $comment['postId'], PDO::PARAM_INT);
        $commentsStmt->bindParam(':name', $comment['name'], PDO::PARAM_STR);
        $commentsStmt->bindParam(':body', $comment['body'], PDO::PARAM_STR);
        $commentsStmt->bindParam(':email', $comment['email'], PDO::PARAM_STR);
        $commentsStmt->execute();
        $commentsCount++;
    }

    echo "Загружено {$postsCount} записей и {$commentsCount} комментариев\n";
} catch (PDOException $e) {
    die("Ошибка PDO: " . $e->getMessage());
} catch (Exception $e) {
    die("Общая ошибка: " . $e->getMessage());
}