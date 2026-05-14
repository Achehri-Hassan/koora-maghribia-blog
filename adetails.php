<?php
// تفعيل الجلسة
session_start();

require_once "article.php";

/* =========================
   التحقق من المعرف (ID)
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Article not found");
}

$id = (int) $_GET['id'];
$article = new Article();

/* =========================
   جلب بيانات المقال
========================= */
$art = $article->getById($id);

if (!$art) {
    die("Article not found");
}

/* =========================
   إضافة تعليق جديد
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $username = trim(htmlspecialchars($_POST['username']));
    $comment_text = trim(htmlspecialchars($_POST['comment']));

    if (!empty($username) && !empty($comment_text)) {
        // حفظ الاسم في الجلسة لملء الحقل تلقائياً المرة القادمة
        $_SESSION['my_comment_name'] = $username;
        
        // إضافة التعليق مباشرة في قاعدة البيانات
        $article->addComment($id, $username, $comment_text);

        // إعادة التوجيه لمنع تكرار الإرسال عند تحديث الصفحة
        header("Location: adetails.php?id=" . $id . "&msg=published");
        exit();
    }
}

/* =========================
   جلب التعليقات للعرض
========================= */
$comments = $article->getCommentsByArticle($id);
$relatedArticles = $article->getRelated($id);

// التحقق من حالة المدير لعرض أزرار لوحة التحكم فقط في الهيدر
$isAdmin = isset($_SESSION['user_id']);
$currentVisitorName = $_SESSION['my_comment_name'] ?? '';

?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($art['title']) ?></title>
    <link rel="stylesheet" href="css/details.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .comment-item { border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 10px; }
        .comment-user { color: #2c3e50; font-weight: bold; font-size: 1.1em; }
        .comment-date { color: #999; font-size: 0.85em; display: block; margin-top: 5px; }
        .msg-success { padding: 12px; background: #d4edda; color: #155724; border-radius: 8px; text-align: center; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .comments-section h3 { border-bottom: 2px solid #f1c40f; display: inline-block; padding-bottom: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <div class="head">
        <?php if ($isAdmin): ?>
            <a href="dashboard.php"><button class="btn-login">لوحة التحكم</button></a>
            <a href="logout.php"><button class="btn-login" style="background: #e74c3c;">خروج</button></a>
        <?php else: ?>
            <a href="login.php"><button class="btn-login">دخول الإدارة</button></a>
        <?php endif; ?>

        <nav>
            <a href="index.php">الرئيسية</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="article-main">
        <h1><?= htmlspecialchars($art['title']) ?></h1>
        
        <?php if (!empty($art['image'])): ?>
            <img src="assest/<?= htmlspecialchars($art['image']) ?>" class="main-img" alt="article image">
        <?php endif; ?>

        <div class="content-text">
            <?= nl2br(htmlspecialchars($art['content'])) ?>
        </div>

        <hr>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'published'): ?>
            <div class="msg-success">تم نشر تعليقك بنجاح!</div>
        <?php endif; ?>

        <section class="comments-section">
            <h3>التعليقات (<?= count($comments) ?>)</h3>

            <form method="POST" class="comment-form">
                <input type="text" name="username" placeholder="اسمك المستعار" value="<?= htmlspecialchars($currentVisitorName) ?>" required>
                <textarea name="comment" placeholder="شاركنا برأيك حول هذا الموضوع..." required></textarea>
                <button type="submit" name="submit_comment">نشر التعليق</button>
            </form>

            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <p style="color: #7f8c8d;">لا توجد تعليقات بعد. كن أول من يشارك برأيه!</p>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="comment-item">
                            <span class="comment-user"><?= htmlspecialchars($c['username']) ?></span>
                            <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
                            <span class="comment-date">نُشر في: <?= date("d-m-Y H:i", strtotime($c['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </section>

    <aside class="sidebar">
        <h3>قد يهمك أيضاً</h3>
        <div class="related-list">
            <?php foreach ($relatedArticles as $r): ?>
                <a href="adetails.php?id=<?= $r['id'] ?>" class="related-item">
                    <?= htmlspecialchars($r['title']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </aside>
</main>

</body>
</html>