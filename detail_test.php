<?php
require_once "article.php";

if (!isset($_GET["id"])) {
    echo "Article Not found";
    exit();
}

$id = $_GET['id'];
$article = new Article();
$art = $article->getById($id);

// هادي خاص تكون عندك في كلاص Article باش تجيب التعليقات
// إذا مازال ما درتيهاش، خلي هاد السطر حتى تزيدها
// $comments = $article->getCommentsByArticle($id); 

$relatedArticles = $article->getRelated($id);





// ... الكود القديم ديالك ...
$id = $_GET['id'];

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
    <title><?= htmlspecialchars($art['title']) ?> - أخبار البطولة</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="css/details.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="css/components/stracture.css">
    <link rel="stylesheet" href="css/components/header.css ">

    <style>
        /* CSS الخاص بالفورم والتعليقات */
        .comments-section {
            margin-top: 50px;
            padding: 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .comments-section h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 25px;
            border-right: 5px solid #e74c3c;
            padding-right: 15px;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #f1f1f1;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            transition: 0.3s;
            outline: none;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e74c3c;
            background: #fff;
        }

        .btn-submit {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            width: fit-content;
        }

        .btn-submit:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* ستيل قائمة التعليقات */
        .comment-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-right: 3px solid #ddd;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .comment-user {
            font-weight: bold;
            color: #2c3e50;
        }

        .comment-date {
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .comment-text {
            line-height: 1.6;
            color: #34495e;
        }
    </style>
</head>

<body>
    <header>
        <div class="head">
            <button class="btn-login">تسجيل الخروج</button>
            <div>
                <a href="index.php" class="logo">
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

    <main class="container">
        <section class="article-main">
            <div class="author-meta">
                <span><i class="fa-solid fa-user"></i> <?= htmlspecialchars($art['author']) ?> </span>
                <span><i class="fa-solid fa-calendar-days"></i> <?= date("d-m-Y", strtotime($art["created_at"])) ?> </span>
            </div>
            <h1><?= htmlspecialchars($art['title']) ?></h1>

            <img src="assest/<?= htmlspecialchars($art['image']) ?>" class="main-img" alt="<?= htmlspecialchars($art['title']) ?>" />

            <div class="article-content">
                <h2>تفاصيل المقال</h2>
                <?php
                $paragraphs = explode("\n", $art['content']);
                foreach ($paragraphs as $paragraph) {
                    if (trim($paragraph)) {
                        echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                    }
                }
                ?>
            </div>

            <section class="comments-section">
                <h3>شاركنا برأيك</h3>

                <form action="add_comment.php" method="POST" class="comment-form">
                    <input type="hidden" name="article_id" value="<?= $id ?>">

                    <div class="form-group">
                        <input type="text" name="username" placeholder="الإسم الكامل" required>
                    </div>

                    <div class="form-group">
                        <textarea name="comment" placeholder="اكتب تعليقك هنا..." rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">إرسال التعليق</button>
                </form>

                <div class="comments-list">
                    <p style="color: #7f8c8d; font-style: italic;">لا توجد تعليقات بعد. كن أول المعلقين!</p>
                </div>
            </section>

            <div class="post-footer-stats">
                <span><i class="fa-regular fa-thumbs-up"></i> 1.3 Likes</span>
                <span><i class="fa-regular fa-comment"></i> 55 Comments</span>
                <span><i class="fa-solid fa-share"></i> 960 Shares</span>
            </div>
        </section>

        <section class="sidebar">
            <div class="sidebar-section">
                <span class="sidebar-title">شارك المقال</span>
                <div class="share-icons">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

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

    <footer class="footer">
        <div class="footer__cta">
            <h2 class="cta__title">تابع آخر أخبار كرة القدم المغربية</h2>
            <p class="cta__text">موقعك لمتابعة أخبار البطولة الاحترافية المغربية لحظة بلحظة.</p>
            <a href="contact.html" class="cta__button">اتصل بنا</a>
            <hr class="cta__divider" />
            <nav class="footer__nav">
                <a href="index.php">الرئيسية</a>
                <a href="#">من نحن</a>
                <a href="#">المقالات</a>
                <a href="#">اتصل بنا</a>
            </nav>
        </div>
        <div class="footer__bottom">
            <div class="footer__logo"><i class="fa-brands fa-readme"></i></div>
            <p class="footer__copyright">2026 جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>

</html>