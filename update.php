<?php
require_once "article.php";
$article = new Article();

$art = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $art = $article->getById($id);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST["select"];
    $author = $_POST["author"];

    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $path = "assest/" . $imageName;
        move_uploaded_file($tmpName, $path);
        $imageToSave = $imageName;
    } else {
        $imageToSave = $_POST['old_image'];
    }

    $article->Update($id, $title, $content, $imageToSave, $author, $category);
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>تعديل مقال - البطولة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="css/create.css" />
</head>

<body>
    <div class="form_container">
        <div class="login-card">
            <div class="form-side">
                <div class="form-header">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <h1>تعديل المقال</h1>
                    <div class="line"></div>
                </div>

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $art['id'] ?? '' ?>">
                    <input type="hidden" name="old_image" value="<?= $art['image'] ?? '' ?>">

                    <div class="input-group">
                        <label>عنوان المقال</label>
                        <div class="input-box">
                            <input type="text" name="title" value="<?= $art['title'] ?? '' ?>" required />
                            <i class="fa-solid fa-heading"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-group">
                            <label>اسم الكاتب</label>
                            <div class="input-box">
                                <input type="text" name="author" value="<?= $art['author'] ?? '' ?>" required />
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                        <div class="input-group">
                            <label>التصنيف</label>

                            <div class="input-box">
                                <select name="select" required>
                                    <option value="news" <?= ($art['category'] ?? '') == 'news' ? 'selected' : '' ?>>أخبار</option>
                                    <option value="matches" <?= ($art['category'] ?? '') == 'matches' ? 'selected' : '' ?>>مباريات</option>


                                    <option value="Analysis" <?= ($art['category'] ?? '') == 'Analysis' ? 'selected' : '' ?>>تحليل</option>


                                    <option value="Transfers" <?= ($art['category'] ?? '') == 'Transfers' ? 'selected' : '' ?>>نتقالات</option>
                                </select>
                                <i class="fa-solid fa-tag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>صورة الغلاف (اتركها فارغة للحفاظ على الحالية)</label>
                        <div class="input-box">
                            <input type="file" name="image" />
                            <i class="fa-solid fa-image"></i>
                        </div>
                    </div>

                    <div class="input-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                            <label style="margin: 0;">المحتوى</label>
                            <!-- <a href="test.html" target="_blank" style="font-size: 13px; color: #007bff; text-decoration: none; font-weight: bold;">
                                <i class="fa-solid fa-up-right-from-square"></i> فتح محرر الكتابة (Copy/Paste)
                            </a> -->


                        </div>
                        <div class="input-box">
                            <textarea rows="8" name="content" id="mainContent" placeholder="الصق المحتوى هنا بعد نسخه من المحرر..." id="mainContent"><?= $art['content'] ?? ''  ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" name="update_article">تحديث المقال</button>
                    <a href="index.php" class="btn-back" style="margin-top: 10px;">إلغاء</a>
                </form>
            </div>
            <div class="image-side">
                <img src="assest/hakim.png" alt="Hakim" />
            </div>
        </div>
    </div>


</body>


</html>