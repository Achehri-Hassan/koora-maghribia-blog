<?php

require_once "article.php";

// if (!isset($_GET["id"])) {
//   echo "Article Not found";
//   exit();
// }

// $id = $_GET['id'];

// $article = new Article();
// $art = $article->getById($id);

// $relatedArticles = $article->getRelated($id);

// ... الكود القديم ديالك ...
$id = $_GET['id'];
$article = new Article();

// يلا صيفط المستخدم تعليق
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
  $username = htmlspecialchars($_POST['username']);
  $comment_text = htmlspecialchars($_POST['comment']);

  if (!empty($username) && !empty($comment_text)) {
    $article->addComment($id, $username, $comment_text);
    // إعادة تحميل الصفحة باش يبان التعليق
    header("Location: adetails.php?id=" . $id);
    exit();
  }
}

$art = $article->getById($id);
$comments = $article->getCommentsByArticle($id); // جلب التعليقات
$relatedArticles = $article->getRelated($id);

?>


<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>تفاصيل المقال - أخبار البطولة</title>

  <!-- icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

  <!-- css -->
  <link rel="stylesheet" href="css/details.css">
  <!-- font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo&display=swap"
    rel="stylesheet" />
</head>

<body>
  <header>
    <div class="head">

      <button class="btn-login">تسجيل الخروج</button>

      <div>
        <a href="#" class="logo">
          <i class="fa-brands fa-readme"></i>
        </a>
        <nav>
          <a href="index.php">الرئيسية</a>
          <a href="#">من نحن</a>
          <a href="#">اتصل بنا</a>
        </nav>
      </div>

    </div>
  </header>

  <!-- MAIN -->
  <main class="container">
    <!-- ARTICLE -->
    <section class="article-main">

      <div class="author-meta">
        <span> <?= htmlspecialchars($art['author'])  ?> <?= date("d-m-Y", strtotime($art["created_at"])) ?> </span>
      </div>
      <h1><?= htmlspecialchars($art['title']) ?></h1>


      <img
        src="assest/<?= htmlspecialchars($art['image']) ?>"
        class="main-img"
        alt="Jakarta City" />



      <div class="article-content">
        <h2>تفاصيل المقال-</h2>
        <?php
        $paragraphs = explode("\n", $art['content']);
        foreach ($paragraphs as $paragraph) {
          if (trim($paragraph)) {
            echo '<p>' . htmlspecialchars($paragraph) . '</p>';
          }
        }
        ?>
      </div>
      <!-- قسم التعليقات -->
<section class="comments-section">
    <h3>التعليقات (<?= count($comments) ?>)</h3>

    <!-- فورم إضافة تعليق -->
    <form action="" method="POST" class="comment-form">
        <input type="text" name="username" placeholder="إسمك" required>
        <textarea name="comment" placeholder="أكتب تعليقك هنا..." required></textarea>
        <button type="submit" name="submit_comment">نشر التعليق</button>
    </form>

    <hr>

    <!-- عرض التعليقات -->
    <div class="comments-list">
        <?php if (empty($comments)): ?>
            <p>لا توجد تعليقات بعد. كن أول من يعلق!</p>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>
                <div class="comment-item">
                    <strong><?= htmlspecialchars($c['username']) ?></strong>
                    <small><?= date("d-m-Y H:i", strtotime($c['created_at'])) ?></small>
                    <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>


      <!-- <div class="post-footer-stats">
        <span><i class="fa-regular fa-thumbs-up"></i> 1.3 Likes</span>
        <span><i class="fa-regular fa-comment"></i> 55 Comments</span>
        <span><i class="fa-solid fa-share"></i> 960 Shares</span>
      </div> -->
    </section>


    <!-- SIDEBAR -->
    <section class="sidebar">
      <!-- share -->
      <div class="sidebar-section">
        <span class="sidebar-title">شارك المقال</span>

        <div class="share-icons">
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>


      <!-- related -->
      <div class="sidebar-section">
        <span class="sidebar-title">مقالات مشابهة</span>

        <div class="related-list">
          <?php foreach ($relatedArticles as $related): ?>
            <a href="adetails.php?id=<?= $related['id'] ?>" class="related-item">

              <img src="assest/<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" />
              <div>
                <h4><?= htmlspecialchars($related['title']) ?></h4>
                <span><?= htmlspecialchars($related['category']) ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer__cta">
      <h2 class="cta__title">تابع آخر أخبار كرة القدم المغربية</h2>

      <p class="cta__text">
        موقعك لمتابعة أخبار البطولة الاحترافية المغربية لحظة بلحظة.
      </p>

      <a href="contact.html" class="cta__button">اتصل بنا</a>

      <hr class="cta__divider" />

      <nav class="footer__nav">
        <a href="index.html">الرئيسية</a>
        <a href="#">من نحن</a>
        <a href="#">المقالات</a>
        <a href="#">اتصل بنا</a>
      </nav>
    </div>

    <div class="footer__bottom">
      <div class="footer__logo">
        <i class="fa-brands fa-readme"></i>
      </div>
      <p class="footer__copyright">2026 جميع الحقوق محفوظة</p>
    </div>
  </footer>
</body>

</html>