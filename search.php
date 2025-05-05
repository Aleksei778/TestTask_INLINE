<?php 

require_once 'pdo.php';

$commentsUrl = 'https://jsonplaceholder.typicode.com/comments';
$postsUrl = 'https://jsonplaceholder.typicode.com/posts';

function fetchData($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url); // указываем нужный url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ответ-строка
    
    $response = curl_exec($ch);

    if (curl_error($ch)) {
        die("Ошибка cURL: " . curl_error($ch));
    }

    curl_close($ch);
    return json_decode($response, true);
}

try {
    $pdo = getDbConnection();

    // Загрузка постов
    $posts = fetchData($postsUrl);
    $postsCount = 0;

    $postsQuery = "INSERT INTO posts (id, userId, title, body) VALUES (:id, :userId, :title, :body)";

    $postsStmt = $pdo->prepare($postsQuery);

    foreach($posts as $post) {
        $postsStmt->bindParam(':id', $post['id'], PDO::PARAM_INT);
        $postsStmt->bindParam(':userId', $post['userId'], PDO::PARAM_INT);
        $postsStmt->bindParam(':title', $post['title'], PDO::PARAM_INT);
        $postsStmt->bindParam(':body', $post['body'], PDO::PARAM_INT);
        
        $postsStmt->execute();

        $postsCount++;
    }

    // Загрузка комментариев
    $comments = fetchData($commentsUrl);
    $commentsCount = 0;

    $commentsQuery = "INSERT INTO comments (id, postId, name, body, email) VALUES (:id, :postId, :name, :body, :email)";

    $commentsStmt = $pdo->prepare($commentQuery);

    foreach($comments as $comment) {
        $commentsStmt->bindParam(':id', $post['id'], PDO::PARAM_INT);
        $commentsStmt->bindParam(':userId', $post['userId'], PDO::PARAM_INT);
        $commentsStmt->bindParam(':title', $post['title'], PDO::PARAM_INT);
        $commentsStmt->bindParam(':body', $post['body'], PDO::PARAM_INT);
        
        $commentsStmt->execute();

        $commentsCount++;
    }

    echo "Загружено {$postsCount} записей и {$commentsCount} комментариев\n";
} catch(Exception $e) {
    die("Ошибка: " . $e->getMessage());
}