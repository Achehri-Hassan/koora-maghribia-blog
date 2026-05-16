<?php


session_start();
require_once "comments.php";
require_once "article.php";



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$comments = new Comments();
$article = new Article();
$articles = $article->readAll();

$selectedComments = null;
$selectedArticleTitle = "";

if (isset($_GET['view_comments'])) {

    $art_id = $_GET['view_comments'];

    $selectedComments = $comments->getCommentsByArticle($art_id);

    $currentArt = $article->getById($art_id);

    if ($currentArt) {
        $selectedArticleTitle = $currentArt['title'];
    }
}


$totalArticles = count($articles);
$totalCommentsCount = 0;
foreach ($articles as $a) {
    $totalCommentsCount += $comments->getCommentsCount($a['id']);
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
    <link rel="stylesheet" href="css/dashboard.css">

</head>

<body>


    <!-- header -->
    <header>
        <!-- Right Side  nav-->
        <div class="right-side">
            <a href="index.php">الرئيسية</a>
            <a href="create.php">إضافة مقال جديد</a>
        </div>

        <div class="admin-actions">
            <a href="logout.php"> تسجيل الخروج </a>
        </div>

    </header>
    <!-- End Header -->

    <!-- total article and comments -->
    <div class="total">
        <h2> <?= $totalArticles ?> إجمالي المقالات</h2>
        <br>
        <h2> <?= $totalCommentsCount ?> إجمالي التعليقات</h2>
    </div>

    <!-- table card -->
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>عنوان المقال</th>
                    <th>التعليقات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art): ?>
                    <tr>
                        <td>#<?= $art['id'] ?></td>
                        <td><strong><?= htmlspecialchars($art['title']) ?></strong></td>
                        <td>
                            <a href="dashboard.php?view_comments=<?= $art['id'] ?>#comments-section" class="view-comments">
                                <?= $comments->getCommentsCount($art['id']) ?> عرض تعليقات
                            </a>
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

    <div id="comments-section">
        <?php if ($selectedComments !== null): ?>
            <div class="comments-card">

                <h3 class="title_article">
                    <a href="dashboard.php"><i class="fa-solid fa-circle-xmark fa-xl"></i></a> تعليقات المقال: <span><?= htmlspecialchars($selectedArticleTitle) ?></span>
                </h3>


                <?php if (empty($selectedComments)): ?>
                    <p>لا توجد تعليقات منشورة لهذا المقال حتى الآن.</p>

                <?php else: ?>

                    <?php foreach ($selectedComments as $sc): ?>
                        <div class="comment-row">
                            <div>
                                <strong><?= htmlspecialchars($sc['username']) ?></strong>:
                                <span><?= htmlspecialchars($sc['comment']) ?></span>
                                <p> <?= date("Y-m-d H:i", strtotime($sc['created_at'])) ?></p>
                            </div>
                            <a href="delete_comment.php?id=<?= $sc['id'] ?>" class="delete-btn"
                                onclick="return confirm('هل تريد حذف هذا التعليق؟')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    </div>

</body>

</html>