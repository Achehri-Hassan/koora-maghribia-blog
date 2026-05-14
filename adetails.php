<?php

require_once "article.php";

/* =========================
   VALIDATION ID
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Article not found");
}

$id = (int) $_GET['id'];

$article = new Article();

/* =========================
   GET ARTICLE
========================= */

$art = $article->getById($id);

if (!$art) {
    die("Article not found");
}

/* =========================
   ADD COMMENT (PENDING)
========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {

    $username = trim(htmlspecialchars($_POST['username']));
    $comment_text = trim(htmlspecialchars($_POST['comment']));

    if (!empty($username) && !empty($comment_text)) {

        $article->addComment($id, $username, $comment_text);

        header("Location: adetails.php?id=" . $id . "&success=1");
        exit();
    }
}

/* =========================
   DATA
========================= */

// only APPROVED comments
$comments = $article->getCommentsByArticle($id);

// related articles
$relatedArticles = $article->getRelated($id);

// latest comments sidebar
$latestComments = $article->getLatestComments();

?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($art['title']) ?></title>

    <link rel="stylesheet" href="css/details.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<header>
    <div class="head">

        <a href="logout.php">
            <button class="btn-login">تسجيل الخروج</button>
        </a>

        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="#">من نحن</a>
            <a href="#">اتصل بنا</a>
        </nav>

    </div>
</header>

<!-- MAIN -->
<main class="container">

    <!-- ARTICLE -->
    <section class="article-main">

        <h1><?= htmlspecialchars($art['title']) ?></h1>

        <img src="assest/<?= htmlspecialchars($art['image']) ?>" class="main-img">

        <p><?= nl2br(htmlspecialchars($art['content'])) ?></p>

        <!-- SUCCESS MESSAGE -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">تم إرسال التعليق (في انتظار الموافقة)</p>
        <?php endif; ?>

        <!-- COMMENTS -->
        <section class="comments-section">

            <h3>التعليقات (<?= count($comments) ?>)</h3>

            <!-- FORM -->
            <form method="POST">

                <input type="text" name="username" placeholder="اسمك" required>

                <textarea name="comment" placeholder="اكتب تعليقك..." required></textarea>

                <button type="submit" name="submit_comment">
                    إرسال
                </button>

            </form>

            <!-- LIST -->
            <div class="comments-list">

                <?php if (empty($comments)): ?>

                    <p>لا توجد تعليقات بعد</p>

                <?php else: ?>

                    <?php foreach ($comments as $c): ?>

                        <div class="comment">

                            <strong><?= htmlspecialchars($c['username']) ?></strong>

                            <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>

                            <small>
                                <?= date("d-m-Y H:i", strtotime($c['created_at'])) ?>
                            </small>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </section>

    </section>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- RELATED -->
        <h3>مقالات مشابهة</h3>

        <?php foreach ($relatedArticles as $r): ?>

            <a href="details.php?id=<?= $r['id'] ?>">
                <?= htmlspecialchars($r['title']) ?>
            </a>

        <?php endforeach; ?>

        <!-- LATEST COMMENTS -->
        <h3>آخر التعليقات</h3>

        <?php foreach ($latestComments as $lc): ?>

            <div>
                <strong><?= htmlspecialchars($lc['username']) ?></strong>
                <p><?= htmlspecialchars(substr($lc['comment'], 0, 40)) ?>...</p>
            </div>

        <?php endforeach; ?>

    </aside>

</main>

</body>
</html>