<?php
session_start();
require_once "article.php";




if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}



$article = new Article();
$articles = $article->readAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | إدارة المقالات</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- link css design  -->
    <link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1><i class="fa-solid fa-screwdriver-wrench"></i> لوحة التحكم</h1>
            <a href="create.php" class="btn-add">
                <i class="fa-solid fa-plus"></i> إضافة مقال جديد
            </a>
        </header>

        <!-- Main Content -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="60%">عنوان المقال</th>
                        <th width="30%">الإجراءات</th>
                    </tr>
                </thead>
               <tbody>
    <?php foreach ($articles as $art): ?>
    <tr>
        <td class="article-id">#<?= htmlspecialchars($art['id']) ?></td>
        <td class="article-title">
            <?= htmlspecialchars($art['title']) ?>
            <?php if (isset($art['status']) && $art['status'] == 'pending'): ?>
                <span style="color: orange; font-size: 0.8em;"> (قيد الانتظار)</span>
            <?php endif; ?>
        </td>
        <td class="actions">
            <div class="actions-wrap">
                
                <?php 
                // الشرط: تظهر "موافقة" فقط إذا كان المقال pending 
                // وَ الكاتب (user_id) ليس هو الأدمن الحالي المتصل
                if (isset($art['status']) && $art['status'] == 'pending' && $art['user_id'] != $_SESSION['user_id']): 
                ?>
                    <a href="approve_article.php?id=<?= $art['id'] ?>" class="action-btn" style="background-color: #27ae60; color: white;">
                        <i class="fa-solid fa-check"></i> موافقة
                    </a>
                <?php endif; ?>

                <a href="update.php?id=<?= $art['id'] ?>" class="action-btn edit-btn">
                    <i class="fa-solid fa-pen-to-square"></i> تعديل
                </a>
                
                <a href="delete.php?id=<?= $art['id'] ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟')">
                    <i class="fa-solid fa-trash"></i> حذف
                </a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>
            </table>
        </div>
    </div>

</body>

</html>