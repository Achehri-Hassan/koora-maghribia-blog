<?php

session_start();

require_once "article.php";
require_once "comments.php";

$article = new Article();
$comments = new Comments();


$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;


if (isset($_GET['cat']) && !empty($_GET['cat'])) {
    $category = trim($_GET['cat']);

    $articles = $article->getByCategory($category);
} else {

    $articles = $article->readAll();
}

?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> أخبار البطولة المغربية</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="style.css" />

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
</head>

<body>

    <!-- HEADER -->
    <header>

        <!-- Right Side  nav-->
        <div class="right-side">
            <a href="index.php">الرئيسية</a>
            <a href="#">من نحن</a>
            <a href="#">اتصل بنا</a>
        </div>

        <!-- admin -->
        <?php if ($isAdmin): ?>
            <div class="admin-actions">

                <a href="dashboard.php"> لوحة التحكم</a>
                <a href="logout.php"> تسجيل الخروج </a>
            </div>
        <?php endif; ?>

    </header>

    <!-- main content-->
    <main>
        <!-- hero section -->
        <section class="hero-section">
          <h1> آخر أخبار البطولة الاحترافية المغربية </h1>
          <div class="category-filters">

            <a href="index.php" class="filter-pill <?= !isset($_GET['cat']) ? 'active' : '' ?>"> All </a>
            <a href="index.php?cat=news" class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] =='news') ? 'active' : '' ?>">  أخبار</a>
            <a href="index.php?cat=matches" class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] =='matches') ? 'active' : '' ?>"> مباريات </a>

            </div>
        </section>
         
        <!-- Article card -->
        <section class="article_card">

           <?php if (empty($articles)): ?>
              <h2> لا توجد مقالات حاليا </h2>
           <?php else: ?>

           <?php foreach ($articles as $art): ?>
             <?php $commentsCount = $comments->getCommentsCount($art['id']); ?>
                <a href="adetails.php?id=<?= $art['id'] ?>" class="card-link">
                    <div class="card">
                        <!-- IMAGE -->
                        <div class="card-image-wrapper">
                            <img src="assest/<?= htmlspecialchars($art['image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                            <span class="card-date-badge"><?= date("d M", strtotime($art["created_at"])) ?></span>
                        </div>
                        

                        <!-- CONTENT -->
                        <div class="card-content">

                            <span class="category_card"> <?= htmlspecialchars($art['category']) ?></span>
                            <h3 class="card-title"><?= htmlspecialchars($art['title']) ?></h3>
                            <div class="div_read">
                                <span class="card_read_more">  ا قرأ المزيد ...</span>
                                <span class="card_comment"> <?= $commentsCount ?> تعليقات </span>
                            </div>

                        </div>

                    </div>
                </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

        <!-- PAGINATION -->
        <section>

            <div class="pagination">
              <a href="#" class="pagination__btn"> السابق </a>
              <a href="#" class="pagination__btn"> 1</a>
              <a href="#" class="pagination__btn">2 </a>
              <a href="#" class="pagination__btn"> التالي</a>
            </div>

        </section>

    </main>

    <!-- FOOTER -->

    <footer class="footer">

          <h2 class="foot__title"> تابع آخر أخبار الكرة المغربية</h2>
          <p class="foot__text"> موقعك الأول لمتابعة أخبار البطولة الاحترافية المغربية.</p>

            <div class="share-icons">
               <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
               <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
               <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
               <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
               <a href="#"><i class="fa-regular fa-envelope"></i></a>
            </div>

            <hr class="cta__divider" />

           <div class="footer__bottom">
             <i class="fa-brands fa-readme"></i>
             <p class="footer__copyright">2026 جميع الحقوق محفوظة</p>
           </div>

    </footer>

</body>

</html>