<?php



require "article.php";

$article = new Article();
$articles = $article->readAll();




?>





<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo&display=swap"
    rel="stylesheet" />

  <!-- css -->
  <link rel="stylesheet" href="style.css" />

  <!-- icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

  <title>أخبار البطولة</title>
</head>

<body>
  <!-- HEADER -->
  <header>
    <div class="head">
    <a href="logout.php"><button class="btn-login">تسجيل الخروج</button></a>

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
  <main>
    <!-- HERO -->
    <section class="hero-section">
      <h1>آخر أخبار البطولة الاحترافية المغربية</h1>

      <div class="category-filters">
        <a href="#" class="filter-pill active">All</a>
        <a href="#" class="filter-pill">أخبار</a>
        <a href="#" class="filter-pill">مباريات</a>
        <a href="#" class="filter-pill">تحليل</a>
        <a href="#" class="filter-pill">انتقالات</a>
      </div>
    </section>

    <!-- FEATURED -->
    <section class="featured-card">
      <img src="assest/rajaM.jpg" alt="كرة القدم" class="featured-img" />
      <div class="featured-content">
        <span class="tag-news">أخبار</span>
        <h2 class="featured-title">
          تألق جديد في البطولة الاحترافية المغربية
        </h2>
        <p class="featured-desc">
          تعرف على آخر أخبار الفرق وأبرز النجوم في منافسات البطولة هذا الموسم
        </p>
        <a href="#" class="btn-read">اقرأ المزيد</a>
      </div>
    </section>

    <!-- ARTICLES -->
    <section class="article-card">

      <?php foreach ($articles as $art): ?>
        
        <a href="adetails.php?id=<?= $art['id']?>">
        <div class="article">
          <img src="assest/<?= htmlspecialchars($art['image']) ?>" alt="">

          <div class="category">
            <p><?= date("d-m-Y", strtotime($art["created_at"])) ?></p>
          </div>
          <!-- <p><?= date("d-m-Y", strtotime($art["created_at"])) ?></p> -->
          <p class="con">
            <?= htmlspecialchars(($art['title'])) ?>
          </p>
        </div>
        </a>

      <?php endforeach; ?>

    </section>

    <!-- PAGINATION -->
    <section>
      <div class="pagination">
        <a href="#" class="pagination__btn">السابق</a>
        <a href="#" class="pagination__btn">1</a>
        <a href="#" class="pagination__btn">2</a>
        <a href="#" class="pagination__btn">التالي</a>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer__cta">
      <h2 class="cta__title">تابع آخر أخبار الكرة المغربية</h2>
      <p class="cta__text">
        موقعك الأول لمتابعة أخبار البطولة الاحترافية المغربية.
      </p>
      <a href="#" class="cta__button">اتصل بنا</a>

      <hr class="cta__divider" />

      <nav class="footer__nav">
        <a href="#">الرئيسية</a>
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