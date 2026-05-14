<?php

session_start();

require_once "article.php";

$article = new Article();

/* =========================
   CHECK ADMIN
========================= */

$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;

/* =========================
   FILTER CATEGORY
========================= */

if (isset($_GET['cat']) && !empty($_GET['cat'])) {

    $category = trim($_GET['cat']);

    $articles = $article->readByCategory($category);

} else {

    $articles = $article->readAll();
}

?>

<!doctype html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>
        أخبار البطولة المغربية
    </title>

    <!-- FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo&display=swap"
        rel="stylesheet" />

    <!-- CSS -->

    <link rel="stylesheet" href="style.css" />

    <!-- ICONS -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

</head>

<body>

    <!-- HEADER -->

    <header>

        <div class="head">

            <a href="logout.php">

                <button class="btn-login">
                    تسجيل الخروج
                </button>

            </a>

            <div>

                <a href="#" class="logo">
                    <i class="fa-brands fa-readme"></i>
                </a>

                <nav>

                    <?php if ($isAdmin): ?>

                        <a href="dashboard.php">
                            لوحة التحكم
                        </a>

                    <?php endif; ?>

                    <a href="index.php">
                        الرئيسية
                    </a>

                    <a href="#">
                        من نحن
                    </a>

                    <a href="#">
                        اتصل بنا
                    </a>

                </nav>

            </div>

        </div>

    </header>

    <!-- HERO -->

    <section class="hero-section">

        <h1>
            آخر أخبار البطولة الاحترافية المغربية
        </h1>

        

        <div class="category-filters">

            <a
                href="index.php"
                class="filter-pill <?= !isset($_GET['cat']) ? 'active' : '' ?>">
                All
            </a>

            <a
                href="index.php?cat=news"
                class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] == 'news') ? 'active' : '' ?>">
                أخبار
            </a>

            <a
                href="index.php?cat=matches"
                class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] == 'matches') ? 'active' : '' ?>">
                مباريات
            </a>

            <a
                href="index.php?cat=Analysis"
                class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] == 'Analysis') ? 'active' : '' ?>">
                تحليل
            </a>

            <a
                href="index.php?cat=Transfers"
                class="filter-pill <?= (isset($_GET['cat']) && $_GET['cat'] == 'Transfers') ? 'active' : '' ?>">
                انتقالات
            </a>

        </div>

    </section>

    <!-- FEATURED -->

    <section class="featured-card">

        <img
            src="assest/inwi.jpg"
            alt="كرة القدم"
            class="featured-img" />

        <div class="featured-content">

            <span class="tag-news">
                أخبار
            </span>

            <h2 class="featured-title">
                تألق جديد في البطولة الاحترافية المغربية
            </h2>

            <p class="featured-desc">
                تعرف على آخر أخبار الفرق وأبرز النجوم في منافسات البطولة هذا الموسم
            </p>

            <a href="#" class="btn-read">
                اقرأ المزيد
            </a>

        </div>

    </section>

    <!-- ARTICLES -->

    <main>

        <section class="modern-grid">

            <?php if (empty($articles)): ?>

                <h2>
                    لا توجد مقالات حاليا
                </h2>

            <?php else: ?>

                <?php foreach ($articles as $art): ?>

                    <?php
                    $commentsCount = $article->getCommentsCount($art['id']);
                    ?>

                    <a
                        href="adetails.php?id=<?= $art['id'] ?>"
                        class="card-link">

                        <article class="modern-card">

                            <!-- IMAGE -->

                            <div class="card-image-wrapper">

                                <img
                                    src="assest/<?= htmlspecialchars($art['image']) ?>"
                                    alt="<?= htmlspecialchars($art['title']) ?>">

                                <div class="card-date-badge">

                                    <?= date("d M", strtotime($art["created_at"])) ?>

                                </div>

                            </div>

                            <!-- CONTENT -->

                            <div class="card-content">

                                <span class="card-tag">

                                    <?= htmlspecialchars($art['category']) ?>

                                </span>

                                <h3 class="card-title">

                                    <?= htmlspecialchars($art['title']) ?>

                                </h3>

                                <!-- META -->

                                <div class="card-meta">

                                    <span>
                                        💬 <?= $commentsCount ?> comments
                                    </span>

                                </div>

                                <!-- FOOTER -->

                                <div class="card-footer">

                                    <span>
                                        Read More
                                    </span>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <line x1="5" y1="12" x2="19" y2="12"></line>

                                        <polyline points="12 5 19 12 12 19"></polyline>

                                    </svg>

                                </div>

                            </div>

                        </article>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

        <!-- PAGINATION -->

        <section>

            <div class="pagination">

                <a href="#" class="pagination__btn">
                    السابق
                </a>

                <a href="#" class="pagination__btn">
                    1
                </a>

                <a href="#" class="pagination__btn">
                    2
                </a>

                <a href="#" class="pagination__btn">
                    التالي
                </a>

            </div>

        </section>

    </main>

    <!-- FOOTER -->

    <footer class="footer">

        <div class="footer__cta">

            <h2 class="cta__title">
                تابع آخر أخبار الكرة المغربية
            </h2>

            <p class="cta__text">
                موقعك الأول لمتابعة أخبار البطولة الاحترافية المغربية.
            </p>

            <a href="#" class="cta__button">
                اتصل بنا
            </a>

            <hr class="cta__divider" />

            <nav class="footer__nav">

                <a href="#">
                    الرئيسية
                </a>

                <a href="#">
                    من نحن
                </a>

                <a href="#">
                    المقالات
                </a>

                <a href="#">
                    اتصل بنا
                </a>

            </nav>

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