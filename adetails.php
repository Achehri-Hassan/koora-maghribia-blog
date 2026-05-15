<?php
// تفعيل الجلسة
session_start();

require_once "article.php";


$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
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

// $relatedArticles = $article->getRelated($id);


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

      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    
</head>

<body>

   <header>
    <div class="head">

        <!-- Right Side -->
        <div class="right-side">

            <a href="#" class="logo">
                <i class="fa-brands fa-readme"></i>
            </a>

            <nav>
                <a href="index.php">الرئيسية</a>
                <a href="#">من نحن</a>
                <a href="#">اتصل بنا</a>
            </nav>

        </div>

        <!-- Left Side -->
        <?php if ($isAdmin): ?>
        <div class="admin-actions">

            <a href="dashboard.php">
                لوحة التحكم
            </a>

            <a href="logout.php">
                <button class="btn-login">
                    تسجيل الخروج
                </button>
            </a>

        </div>
        <?php endif; ?>

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

        </section>

        <section class="comments-section">
            <h3>التعليقات (<?= count($comments) ?>)</h3>

            <form method="POST" class="comment-form">
                <input type="text" name="username" placeholder="اسمك المستعار" required>
                <textarea name="comment" placeholder="شاركنا برأيك حول هذا الموضوع..." required></textarea>
                <button type="submit" name="submit_comment">نشر التعليق</button>
            </form>

            <div class="comments-list">

                <?php foreach ($comments as $c): ?>
                    <div class="comment-item">
                        <span class="comment-user"><?= htmlspecialchars($c['username']) ?></span>
                        <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
                        <span class="comment-date">نُشر في: <?= date("d-m-Y H:i", strtotime($c['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>

    </main>




      <!-- footer -->
        <footer class="footer">

        <div class="footer__cta">

            <h2 class="cta__title">
                تابع آخر أخبار الكرة المغربية
            </h2>

            <p class="cta__text">
                موقعك الأول لمتابعة أخبار البطولة الاحترافية المغربية.
            </p>

            <div class="sidebar-section">
              <div class="share-icons">
              <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
              <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
              <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
              <a href="#"><i class="fa-regular fa-envelope"></i></a>
            </div>
            <hr class="cta__divider" />

          

        </div>


        <div class="footer__bottom">

            <div class="footer__logo">
                <i class="fa-brands fa-readme"></i>
            </div>

            <p class="footer__copyright">
                2026 جميع الحقوق محفوظة
            </p>

        </div>


    </footer>
    

</body>

</html>