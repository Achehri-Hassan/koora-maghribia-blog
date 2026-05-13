<?php
require_once "article.php";
session_start();

// حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_article'])) {
  
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = $_POST["select"];
    
    // جلب المعلومات من السيسيون مباشرة
    $user_id = $_SESSION['user_id']; 
    $author = $_SESSION['username']; 

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];

    if (empty($title) || empty($content) || empty($category) || empty($imageName)) {
        echo "خاصك تعمر جميع الخانات";
        exit();
    }

    $path = "assest/" . $imageName;
    move_uploaded_file($tmpName, $path);

    $article = new Article();
    // صيفط الـ user_id كبارامتر جديد للدالة (تأكد أن الدالة في article.php كتقبل 6 ديال البرامترات)
    $article->createArticle($title, $content, $imageName, $author, $category, $user_id);

    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>إضافة مقال - البطولة</title>

  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/create.css"/>
</head>

<body>
  <div class="form_container">
    <div class="login-card">
      <div class="form-side">
        <div class="form-header">
          <i class="fa-solid fa-pen-nib"></i>
          <h1>إضافة مقال جديد</h1>
          <div class="line"></div>
        </div>

        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="author" value="<?= htmlspecialchars($_SESSION['username']) ?>" />

          <div class="input-group">
            <label>عنوان المقال</label>
            <div class="input-box">
              <input type="text" placeholder="اكتب العنوان هنا..." required name="title" />
              <i class="fa-solid fa-heading"></i>
            </div>
          </div>

          <div class="row">
            <div class="input-group">
              <label>التصنيف</label>
              <div class="input-box">
                <select required name="select">
                  <option value="" disabled selected>اختر النوع</option>
                  <option value="news">أخبار</option>
                  <option value="matches">مباريات</option>
                  <option value="Analysis">تحليل</option>
                  <option value="Transfers">انتقالات</option>
                </select>
                <i class="fa-solid fa-tag"></i>
              </div>
            </div>

            <div class="input-group">
              <label>صورة الغلاف</label>
              <div class="input-box">
                <input type="file" name="image" required />
                <i class="fa-solid fa-image"></i>
              </div>
            </div>
          </div>

          <div class="input-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
              <label style="margin: 0;">المحتوى</label>
            </div>
            <div class="input-box">
              <textarea rows="8" name="content" placeholder="اكتب محتوى المقال هنا..."></textarea>
            </div>
          </div>

          <button type="submit" class="btn-submit" name="add_article">نشر المقال</button>

          <div class="divider">أو</div>
          <a href="index.php" class="btn-back">الرجوع للرئيسية</a>
        </form>
      </div>

      <div class="image-side">
        <img src="assest/hakim.png" alt="Hakim" />
      </div>
    </div>
  </div>
</body>
</html>