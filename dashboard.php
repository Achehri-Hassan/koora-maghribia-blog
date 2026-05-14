<?php

session_start();
require_once "article.php";

/* =========================
   الحماية (للمدير فقط)
========================= */
// يمكنك تفعيل هذا الشرط إذا كنت تريد منع أي شخص غير المدير من الدخول
// if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
//     header("Location: login.php");
//     exit();
// }

$article = new Article();

/* =========================
   جلب المقالات
========================= */
$articles = $article->readAll();

/* =========================
   التعليقات المنتظرة (مع اسم المقال)
========================= */
$pendingComments = $article->getPendingComments();

/* =========================
   الإحصائيات
========================= */
$totalArticles = count($articles);
$totalPending = count($pendingComments);

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
    <style>
        /* تحسينات إضافية للتصميم */
        .article-link-hint {
            font-size: 0.85rem;
            color: #d35400;
            margin-bottom: 8px;
            display: block;
        }
        .comment-meta {
            border-bottom: 1px dashed #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="text-decoration: none; color: #3498db;">
            <i class="fa-solid fa-house"></i> العودة للرئيسية
        </a>
    </div>

    <header class="header">
        <h1>
            <i class="fa-solid fa-screwdriver-wrench"></i>
            لوحة التحكم
        </h1>

        <a href="create.php" class="btn-add">
            <i class="fa-solid fa-plus"></i>
            إضافة مقال جديد
        </a>
    </header>

    <div class="stats">
        <div class="card">
            <!-- <i class="fa-solid fa-newspaper fa-2x" style="color: #3498db;"></i> -->
            <h2><?= $totalArticles ?></h2>
            <p>إجمالي المقالات</p>
        </div>

        <div class="card">
            <!-- <i class="fa-solid fa-comments fa-2x" style="color: #f1c40f;"></i> -->
            <h2><?= $totalPending ?></h2>
            <p>تعليقات تحتاج موافقة</p>
        </div>
    </div>

    <div class="table-card">
        <h2 style="padding: 20px;"><i class="fa-solid fa-list"></i> إدارة المقالات</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>عنوان المقال</th>
                <th>عدد التعليقات</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:30px;">لا توجد مقالات حالياً</td>
                </tr>
            <?php else: ?>
                <?php foreach ($articles as $art): ?>
                    <tr>
                        <td>#<?= $art['id'] ?></td>
                        <td>
                            <a href="adetails.php?id=<?= $art['id'] ?>" target="_blank" style="color: #2c3e50; font-weight: 600;">
                                <?= htmlspecialchars($art['title']) ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge"><?= $article->getCommentsCount($art['id']) ?></span>
                        </td>
                        <td>
                            <div class="actions-wrap">
                                <a href="update.php?id=<?= $art['id'] ?>" class="edit-btn"><i class="fa-solid fa-pen"></i> تعديل</a>
                                <a href="delete.php?id=<?= $art['id'] ?>" onclick="return confirm('هل أنت متأكد من حذف المقال؟')" class="delete-btn"><i class="fa-solid fa-trash"></i> حذف</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="comments-section">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> تعليقات في الانتظار</h2>

        <?php if (empty($pendingComments)): ?>
            <p style="padding: 20px; color: #7f8c8d;">لا توجد تعليقات جديدة للمراجعة.</p>
        <?php else: ?>
            <?php foreach ($pendingComments as $c): ?>
                <div class="comment-box">
                    <div class="comment-meta">
                        <span class="article-link-hint">
                            <i class="fa-solid fa-file-lines"></i> 
                            تعليق على: <strong><?= htmlspecialchars($c['article_title']) ?></strong>
                        </span>
                        <strong><i class="fa-solid fa-user"></i> <?= htmlspecialchars($c['username']) ?></strong>
                    </div>

                    <p style="background: #fff; padding: 10px; border-radius: 4px; border-right: 3px solid #3498db;">
                        <?= htmlspecialchars($c['comment']) ?>
                    </p>

                    <div style="margin-top: 15px;">
                        <a href="approve_comment.php?id=<?= $c['id'] ?>" class="approve-btn">
                            <i class="fa-solid fa-check"></i> موافقة
                        </a>

                        <a href="delete_comment.php?id=<?= $c['id'] ?>" class="delete-btn" onclick="return confirm('حذف التعليق نهائياً؟')">
                            <i class="fa-solid fa-xmark"></i> حذف
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>