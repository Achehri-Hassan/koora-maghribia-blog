<?php


session_start();
require_once "../src/models/article.php";
require_once "../src/models/comments.php";



$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit();
}

$art = getArticleById($id);
if (!$art) {
    header("Location: index.php");
    exit();
}

$comment_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $username     = htmlspecialchars(trim($_POST['username']));
    $comment_text = htmlspecialchars(trim($_POST['comment']));

    if (!empty($username) && !empty($comment_text)) {

        addComment($id, $username, $comment_text);

        header("Location: adetails.php?id=" . $id);
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

     <!-- call header -->
    <?php include '../includes/header.php'; ?>

    <!-- main content  -->
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

        <section class="comments-section">
            <h3>التعليقات (<?= count($comments) ?>)</h3>
             <?php if ($comment_error): ?>
                <p class="error"><?= $comment_error ?></p>
              <?php endif; ?>
              
            <form method="POST" class="comment-form">
                <input type="text" name="username" placeholder="اسمك المستعار" >
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


    <!-- footer -->
    <?php include '../includes/footer.php'; ?>


</body>
</html>