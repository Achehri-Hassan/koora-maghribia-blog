<?php


session_start();
require_once "../src/models/comments.php";
require_once "../src/models/article.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

$articles = readAllArticles();

$totalArticles = count($articles);
$totalCommentsCount = 0;
$commentCounts = [];


foreach ($articles as $a) {
    $commentCounts[$a['id']] = getCommentsCount($a['id']);
    $totalCommentsCount += $commentCounts[$a['id']];
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - إدارة المحتوى</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assest/css/dashboard.css">
</head>

<body>

    <header>
       <div class="header-inner">
            <a href="index.php" class="header-logo">الرئيسية </a>
            <button class="nav-toggle">
                <i class="fa-solid fa-bars"></i>
                <i class="fa-solid fa-xmark"></i>
            </button>

            <nav class="nav-menu">
                <div class="nav-links">
                    <a href="create_Article.php">إضافة مقال جديد</a>
                    <a href="create_match.php">إضافة مباراة </a>
                </div>
                <div class="admin-actions">
                    <a href="logout.php"> تسجيل الخروج </a>
                </div>
            </nav>
       </div>
    </header>

    <div class="total">
        <h2> <i class="fa-solid fa-newspaper"></i> إجمالي المقالات: <?= $totalArticles ?></h2>
        <br>
        <h2> <i class="fa-solid fa-comments"></i> إجمالي التعليقات الكلية: <?= $totalCommentsCount ?></h2>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>عنوان المقال</th>
                    <th>التعليقات لكل مقال</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art): ?>
                    <tr>
                        <td>#<?= $art['id'] ?></td>
                        <td><strong><?= htmlspecialchars($art['title']) ?></strong></td>
                        <td>
                            <p class="view-comments">
                                <i class="fa-regular fa-comment"></i> التعليقات: (<?= $commentCounts[$art['id']] ?>)
                            </p>
                        </td>
                        <td>
                            <div class="actions-wrap">
                                <a href="update.php?id=<?= $art['id'] ?>" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="delete.php?id=<?= $art['id'] ?>" class="delete-btn" onclick="return confirm('حذف المقال نهائياً؟')"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../assest/js/header.js"></script>
</body>
</html>