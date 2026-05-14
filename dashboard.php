<?php

session_start();
require_once "article.php";

/* =========================
   SECURITY (ADMIN ONLY)
========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$article = new Article();

/* =========================
   SEARCH ARTICLES
========================= */
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $articles = $article->searchArticles($_GET['search']);
} else {
    $articles = $article->readAll();
}

/* =========================
   COMMENTS (PENDING)
========================= */
$pendingComments = $article->getPendingComments();

/* =========================
   STATS
========================= */
$totalArticles = count($articles);
$totalPending = count($pendingComments);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <header class="header">

        <h1>
            <i class="fa-solid fa-screwdriver-wrench"></i>
            لوحة التحكم
        </h1>

        <a href="create.php" class="btn-add">
            <i class="fa-solid fa-plus"></i>
            إضافة مقال
        </a>

    </header>

    <!-- STATS -->
    <div class="stats">

        <div class="card">
            <h2><?= $totalArticles ?></h2>
            <p>المقالات</p>
        </div>

        <div class="card">
            <h2><?= $totalPending ?></h2>
            <p>تعليقات في الانتظار</p>
        </div>

    </div>

    <!-- SEARCH -->
    <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="بحث عن مقال...">
        <button>بحث</button>
    </form>

    <!-- ARTICLES TABLE -->
    <div class="table-card">

        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>العنوان</th>
                <th>التعليقات</th>
                <th>الإجراءات</th>
            </tr>
            </thead>

            <tbody>

            <?php if (empty($articles)): ?>

                <tr>
                    <td colspan="4" style="text-align:center; padding:30px;">
                        لا توجد مقالات
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($articles as $art): ?>

                    <tr>

                        <td>#<?= $art['id'] ?></td>

                        <td><?= htmlspecialchars($art['title']) ?></td>

                        <td>
                            <?= $article->getCommentsCount($art['id']) ?>
                        </td>

                        <td>

                            <div class="actions-wrap">

                                <a href="update.php?id=<?= $art['id'] ?>" class="edit-btn">
                                    تعديل
                                </a>

                                <a href="delete.php?id=<?= $art['id'] ?>"
                                   onclick="return confirm('متأكد من الحذف؟')"
                                   class="delete-btn">
                                    حذف
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- PENDING COMMENTS -->
    <div class="comments-section">

        <h2>تعليقات في الانتظار</h2>

        <?php if (empty($pendingComments)): ?>

            <p>لا توجد تعليقات في الانتظار</p>

        <?php else: ?>

            <?php foreach ($pendingComments as $c): ?>

                <div class="comment-box">

                    <strong><?= htmlspecialchars($c['username']) ?></strong>

                    <p><?= htmlspecialchars($c['comment']) ?></p>

                    <a href="approve_comment.php?id=<?= $c['id'] ?>" class="approve-btn">
                        Approve
                    </a>

                    <a href="delete_comment.php?id=<?= $c['id'] ?>" class="delete-btn">
                        Delete
                    </a>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

</body>
</html>