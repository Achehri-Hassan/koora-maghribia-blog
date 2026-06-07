<?php

// session start
session_start();

require_once "../src/config/connection.php";
require_once "../src/models/article.php";
require_once "../src/models/comments.php";


// add condition 
if (isset($_GET['cat']) && !empty($_GET['cat'])) {
    // decal variable 
    $category = trim($_GET['cat']);
    $articles = getArticlesByCategory($category);
} else {
    $articles = readAllArticles();
}

?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>أخبار البطولة المغربية</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="../assest/css/style.css" />

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    
    <!-- call header -->
   <?php include '../includes/header.php'; ?>

    <!-- main content-->
    <main>
        <!-- hero section -->
        <section class="hero-section">
          <h1>آخر أخبار البطولة الاحترافية المغربية</h1>

          <!-- category filters -->
          <div class="category-filters">

            <!-- all articles -->
            <a href="index.php" class="filter-pill <?= !isset($_GET['cat']) ? 'active' : '' ?>">الكل</a>

             <!-- news articles -->
            <a href="index.php?cat=news" class="filter-pill <?= isset($_GET['cat']) && $_GET['cat'] =='news' ? 'active' : '' ?>">الأخبار</a>
             
            <!-- Transfers articles -->
            <a href="index.php?cat=Transfers" class="filter-pill <?= isset($_GET['cat']) && $_GET['cat'] =='Transfers' ? 'active' : '' ?>">الانتقالات</a>

             <!-- matches  -->
            <a href="view_matches.php" class="filter-pill btn-matches-link"><i class="fa-solid fa-calendar-days"></i> جدول المباريات</a>
          </div>
        </section>
         
        <!-- Article card -->
        <section class="article_card">
            
            <!-- empty articles -->
           <?php if (empty($articles)): ?>
             <div class="no-articles-found">
                 <i class="fa-regular fa-folder-open"></i>
                 <h2>لا توجد مقالات حالياً في هذا التصنيف</h2>
             </div>
           <?php else: ?>
            
            <!-- create loop to call all articles  -->
           <?php foreach ($articles as $art): ?>
             <!-- get count comments -->
             <?php $commentsCount = getCommentsCount($art['id']); ?>

                <!-- add card link to click visitor & admin he see details in  adetails.php-->
                <a href="adetails.php?id=<?= $art['id'] ?>" class="card-link">
                    <div class="card">
                        <!-- IMAGE -->
                        <div class="card-image-wrapper">
                            <img src="../assest/articles/<?= htmlspecialchars($art['image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                            <span class="card-date"><i class="fa-regular fa-calendar"></i> <?= date("d M", strtotime($art["created_at"])) ?></span>
                        </div>
                        
                        <!-- CONTENT -->
                        <div class="card-content">
                            <span class="category_name_articles"><?= htmlspecialchars($art['category']) ?></span>
                            <h3 class="articles-title"><?= htmlspecialchars($art['title']) ?></h3>
                            
                            <div class="div_read">
                                <span class="card_read_more">اقرأ المزيد <i class="fa-solid fa-arrow-left"></i></span>
                                <span class="card_comment"><i class="fa-regular fa-comment"></i> <?= $commentsCount ?> تعليقات</span>
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
              <a href="#" class="pagination__btn nav-btn"><i class="fa-solid fa-chevron-right"></i> السابق</a>
              <a href="#" class="pagination__btn active">1</a>
              <a href="#" class="pagination__btn">2</a>
              <a href="#" class="pagination__btn nav-btn">التالي <i class="fa-solid fa-chevron-left"></i></a>
            </div>
        </section>
        

    </main>

    <!-- FOOTER -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>