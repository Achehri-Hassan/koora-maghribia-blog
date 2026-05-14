<?php
// تفعيل الجلسة للتحقق من هوية المدير
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
   إضافة تعليق جديد (المنطق المحدث)
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $username = trim(htmlspecialchars($_POST['username']));
    $comment_text = trim(htmlspecialchars($_POST['comment']));

    if (!empty($username) && !empty($comment_text)) {
        
        // التحقق: إذا كان المدير مسجل دخوله، يتم نشر التعليق مباشرة (approved)
        if (isset($_SESSION['user_id'])) {
            $article->addComment($id, $username, $comment_text, 'approved');
            $status_msg = "published";
        } else {
            // إذا كان زائراً عادياً، يبقى قيد الانتظار (pending)
            $article->addComment($id, $username, $comment_text, 'pending');
            $status_msg = "pending";
        }

        header("Location: adetails.php?id=" . $id . "&msg=" . $status_msg);
        exit();
    }
}

/* =========================
   جلب البيانات للعرض
========================= */

// المدير يرى كل شيء (المقبولة والمعلقة) لإدارتها
if (isset($_SESSION['user_id'])) {
    $comments = $article->getCommentsByArticleAdmin($id);
} else {
    // الزائر يرى المقبولة فقط
    $comments = $article->getCommentsByArticle($id);
}

// المقالات ذات الصلة
$relatedArticles = $article->getRelated($id);



?>

<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($art['title']) ?></title>
    <link rel="stylesheet" href="css/details.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        .comment-pending { border: 2px dashed #ffc107; background-color: #fff8e1; padding: 10px; margin-bottom: 10px; }
        .admin-badge { background: #ffc107; color: #000; padding: 2px 5px; font-size: 11px; border-radius: 4px; }
        .admin-actions { margin-top: 8px; display: flex; gap: 15px; border-top: 1px solid #ddd; padding-top: 5px; }
        .btn-approve { color: #27ae60; text-decoration: none; font-weight: bold; font-size: 0.9em; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; font-size: 0.9em; }
        .msg-info { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>

<header>
    <div class="head">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php"><button class="btn-login">لوحة التحكم</button></a>
            <a href="logout.php"><button class="btn-login" style="background: #e74c3c;">خروج</button></a>
        <?php else: ?>
            <a href="login.php"><button class="btn-login">دخول</button></a>
        <?php endif; ?>

        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="#">من نحن</a>
            <a href="#">اتصل بنا</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="article-main">
        <h1><?= htmlspecialchars($art['title']) ?></h1>
        <img src="assest/<?= htmlspecialchars($art['image']) ?>" class="main-img" alt="article image">
        <p class="content-text"><?= nl2br(htmlspecialchars($art['content'])) ?></p>

        <hr>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'pending'): ?>
                <div class="msg-info" style="background: #d1ecf1; color: #0c5460;">تم إرسال تعليقك وهو قيد المراجعة حالياً.</div>
            <?php elseif ($_GET['msg'] == 'published'): ?>
                <div class="msg-info" style="background: #d4edda; color: #155724;">تم نشر تعليقك مباشرة (مدير).</div>
            <?php endif; ?>
        <?php endif; ?>

        <section class="comments-section">
            <h3>التعليقات (<?= count($comments) ?>)</h3>

            <form method="POST">
                <input type="text" name="username" placeholder="اسمك" value="<?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '' ?>" required>
                <textarea name="comment" placeholder="اكتب تعليقك..." required></textarea>
                <button type="submit" name="submit_comment">إرسال التعليق</button>
            </form>

            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <p>لا توجد تعليقات بعد لهذا المقال.</p>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="comment <?= ($c['status'] == 'pending') ? 'comment-pending' : '' ?>">
                            <div class="comment-header">
                                <strong><?= htmlspecialchars($c['username']) ?></strong>
                                <?php if ($c['status'] == 'pending'): ?>
                                    <span class="admin-badge">قيد الانتظar</span>
                                <?php endif; ?>
                            </div>

                            <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
                            <small><?= date("d-m-Y H:i", strtotime($c['created_at'])) ?></small>

                            <?php if (isset($_SESSION['user_id']) && $c['status'] == 'pending'): ?>
                                <div class="admin-actions">
                                    <a href="approve_comment.php?id=<?= $c['id'] ?>&art_id=<?= $id ?>" class="btn-approve">✅ موافقة</a>
                                    <a href="delete_comment.php?id=<?= $c['id'] ?>&art_id=<?= $id ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">🗑️ حذف</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </section>

    <aside class="sidebar">
        <h3>مقالات مشابهة</h3>
        <div class="related-list">
            <?php foreach ($relatedArticles as $r): ?>
                <a href="adetails.php?id=<?= $r['id'] ?>" class="related-item"><?= htmlspecialchars($r['title']) ?></a>
            <?php endforeach; ?>
        </div>

        <br>
       
    </aside>
</main>

</body>
</html>