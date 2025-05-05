<?php

require_once 'pdo.php';

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$searchResults = [];
$errorMessage = '';

if ($searchQuery && strlen($searchQuery) >= 3) {
    try {
        $query = "
        SELECT p.id, p.title, p.body,
            c.id AS comment_id, c.body AS comment_body
        FROM posts p
        JOIN comments c ON p.id = c.postId
        WHERE c.body LIKE :search
        ORDER BY p.id
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':search', '%' . $searchQuery . '%', PDO::PARAM_STR);
        $stmt->execute();
        
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch (PDOException $pdo_e) {
        $errorMessage = "Ошибка при выполнении запроса: " . $e->getMessage();
    }
} elseif ($searchQuery && strlen($searchQuery) < 3) {
    $errorMessage = "Запрос должен содержать минимум 3 символа!";
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание INLINE</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Поиск записей по комментариям</h1>
    <div class="search-form">
        <form action="" method="GET">
            <input type="text" name="query" class="search-input" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Введите текст для поиска (минимум 3 символа)">
            <button type="submit" class="search-button">Найти</button>
        </form>
    </div>

    <?php if ($errorMessage): ?>
        <div class="error"><?php echo htmlspecialchars($searchQuery); ?></div>
    <?php endif; ?>

    <?php if ($searchQuery && strlen($searchQuery) >= 3): ?>
        <h2>Результаты поиска для "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        <?php if (count($searchResults) > 0): ?>
            <?php foreach ($searchResults as $result): ?>
                <div class="result-item">
                    <div class="post-title">Запись #<?php echo $result['id']; ?>: <?php echo htmlspecialchars($result['title']); ?></div>
                    <div class="comment-body">
                        <?php echo htmlspecialchars($result['comment_body']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">По Вашему запросу ничего не найдено!</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>