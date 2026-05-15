<?php
session_start();
require_once "article.php";

// الحماية: التحقق من تسجيل دخول المدير
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$article = new Article();
$articles = $article->readAll();

/* =========================
   المنطق: جلب تعليقات مقال محدد عند الضغط على الأيقونة
========================= */
$selectedComments = null;
$selectedArticleTitle = "";

if (isset($_GET['view_comments'])) {
    $art_id = (int)$_GET['view_comments'];
    // جلب التعليقات باستخدام الدالة التي حددتها
    $selectedComments = $article->getCommentsByArticle($art_id);

    // جلب عنوان المقال المختار لعرضه في رأس قائمة التعليقات
    $currentArt = $article->getById($art_id);
    if ($currentArt) {
        $selectedArticleTitle = $currentArt['title'];
    }
}

/* =========================
   الإحصائيات
========================= */
$totalArticles = count($articles);
$totalCommentsCount = 0;
foreach ($articles as $a) {
    $totalCommentsCount += $article->getCommentsCount($a['id']);
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
       <div class="container">
         <div class="header">

        <h1>
            <i class="fa-solid fa-gauge-high"></i>
            لوحة الإدارة
        </h1>

        <div class="header-actions">
            <a href="create.php" class="btn-add">
                <i class="fa-solid fa-plus"></i>
                إضافة مقال جديد
            </a>

            <a href="index.php" class="btn-add">
                Home
            </a>
        </div>

        </div>
</div>
 <!-- End Header -->

  

        <div class="stats">
            <div class="card">
               
                <h2><?= $totalArticles ?></h2>
                <p>إجمالي المقالات</p>
            </div>
            <div class="card">
                
                <h2><?= $totalCommentsCount ?></h2>
                <p>إجمالي التعليقات</p>
            </div>

        </div>

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
                                <span class="badge"><?= $article->getCommentsCount($art['id']) ?></span>
                                <a href="dashboard.php?view_comments=<?= $art['id'] ?>#comments-section" class="view-comments-btn">
                                    عرض تعليقات
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
                <div class="comments-focus-area">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0; color: #2c3e50;">
                           
                            تعليقات المقال: <span ><?= htmlspecialchars($selectedArticleTitle) ?></span>
                        </h3>
                        <a href="dashboard.php" style="color: #95a5a6; text-decoration: none;"><i class="fa-solid fa-circle-xmark fa-xl"></i> إغلاق</a>
                    </div>

                    <?php if (empty($selectedComments)): ?>
                        <p style="color: #7f8c8d; text-align: center; padding: 20px;">لا توجد تعليقات منشورة لهذا المقال حتى الآن.</p>
                    <?php else: ?>
                        <?php foreach ($selectedComments as $sc): ?>
                            <div class="comment-row">
                                <div>
                                    <strong style="color: #2980b9;"><?= htmlspecialchars($sc['username']) ?></strong>: 
                                    <span style="color: #444;"><?= htmlspecialchars($sc['comment']) ?></span>
                                    <div style="font-size: 0.75rem; color: #999; margin-top: 4px;">
                                        <i class="fa-regular fa-clock"></i> <?= date("Y-m-d H:i", strtotime($sc['created_at'])) ?>
                                    </div>
                                </div>
                                <a href="delete_comment.php?id=<?= $sc['id'] ?>&from=dashboard" 
                                   class="delete-btn" 
                                   style="padding: 6px 12px; font-size: 0.8rem;"
                                   onclick="return confirm('هل تريد حذف هذا التعليق؟')">
                                   <i class="fa-solid fa-trash-can"></i> حذف
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