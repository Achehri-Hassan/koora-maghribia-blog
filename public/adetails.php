<?php

session_start();
require_once "../src/models/article.php";
require_once "../src/models/comments.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// condition 
if (!$id) {
    header("Location: index.php");
    exit();
}

$art = getArticleById($id);

if (!$art) {
    header("Location: index.php");
    exit();
}


$relatedArticles = getRelatedArticles($art['category'], $id, 5);

$comment_error = "";

// handle form 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {

    // declare variable to add name input
    $username     = trim($_POST['username']);
    $comment_text = trim($_POST['comment']);

    // condition not empty variable
    if (!empty($username) && !empty($comment_text)) {
       
        addComment($id, $username, $comment_text);

      
        header("Location: adetails.php?id=" . $id . "#comments-block");
        exit();
    } else {
        $comment_error = "يرجى ملء جميع الحقول قبل النشر.";
    }
}

$comments = getCommentsByArticle($id);
?>
<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($art['title']) ?></title>
    <link rel="stylesheet" href="../assest/css/details.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <main class="container">

        <section class="article-details">
            <h1><?= htmlspecialchars($art['title']) ?></h1>
            <?php if (!empty($art['image'])): ?>
                <img src="../assest/articles/<?= htmlspecialchars($art['image']) ?>" class="image_details" alt="article image">
            <?php endif; ?>
            <p class="content-details">
                <?= nl2br(htmlspecialchars($art['content'])) ?>
            </p>
        </section>

        <section class="sidebar-section">
            <span class="sidebar-title">مقالات ذات صلة</span>
            <div class="related-list">
                <?php if (empty($relatedArticles)): ?>
                    <p style="color: #666; font-size: 0.9rem;">لا توجد مقالات مرتبطة حالياً.</p>
                <?php else: ?>
                    <?php foreach ($relatedArticles as $related): ?>
                        <a href="adetails.php?id=<?= $related['id'] ?>" class="related-item">
                            <img src="../assest/articles/<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" />
                            <div>
                                <h4><?= htmlspecialchars($related['title']) ?></h4>
                                <span><?= htmlspecialchars($related['category']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="comments-section" id="comments-block">
            <h3>التعليقات (<?= count($comments) ?>)</h3>

            <?php if ($comment_error): ?>
                <p class="error"><?= $comment_error ?></p>
            <?php endif; ?>

            <form method="POST" class="comment-form">
                <input type="text" name="username" placeholder="اسمك المستعار" required>
                <textarea name="comment" placeholder="شاركنا برأيك حول هذا الموضوع..." required></textarea>
                <button type="submit" name="submit_comment">نشر التعليق</button>
            </form>

            <div class="comments-list">
                <?php foreach ($comments as $c): ?>
                    <div class="comment-result">
                        <span class="comment-user"><?= htmlspecialchars($c['username']) ?></span>
                        <p><?= htmlspecialchars($c['comment']) ?></p>
                        <span class="comment-date">نُشر في: <?= date("d-m-Y H:i", strtotime($c['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>