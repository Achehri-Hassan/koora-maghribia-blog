<?php

require_once "article.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  if (isset($_POST['add_article'])) {


    $title =  trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST["author"]);
    $category = $_POST["select"];

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];

    if (
      empty($title) ||
      empty($content) ||
      empty($author) ||
      empty($category) ||
      empty($imageName)
    ) {
      echo " خاصك تعمر جميع الخانات ";
      exit();
    }

    $path = "assest/" . $imageName;

    move_uploaded_file($tmpName, $path);

    $article = new Article();
    $article->createArticle($title, $content, $imageName, $author, $category);

    header("Location: index.php");
    exit();
  }
}



?>






<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>إضافة مقال - البطولة</title>

  <!-- link font  -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
    rel="stylesheet" />
  <!-- link icon -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- link css design -->
  <link rel="stylesheet" href="css/create.css" />
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
          <div class="input-group">
            <label>عنوان المقال</label>
            <div class="input-box">
              <input type="text" placeholder="اكتب العنوان هنا..." required name="title" />
              <i class="fa-solid fa-heading"></i>
            </div>
          </div>

          <div class="row">
            <div class="input-group">
              <label>اسم الكاتب</label>
              <div class="input-box">
                <input type="text" placeholder="اسم الكاتب" required name="author" />
                <i class="fa-solid fa-user"></i>
              </div>
            </div>

            <div class="input-group">
              <label>التصنيف</label>
              <div class="input-box">
                <select required name="select">
                  <option value="" disabled selected>اختر النوع</option>
                  <option value="news">أخبار</option>
                  <option value="matches">مباريات</option>
                  <option value="matches">تحليل</option>
                  <option value="matches">انتقالات</option>
                </select>
                <i class="fa-solid fa-tag"></i>
              </div>
            </div>
          </div>

          <div class="input-group">
            <label>صورة الغلاف</label>
            <div class="input-box">
              <input type="file" name="image" />
              <i class="fa-solid fa-image"></i>
            </div>
          </div>

          <!-- <div class="input-group">
            <label>المحتوى</label>
            <div class="input-box">
              <textarea
                rows="4"
                placeholder="اكتب محتوى المقال..." name="content"></textarea>
            </div>
          </div> -->

          <div class="input-group">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
              <label style="margin: 0;">المحتوى</label>
              <a href="editor.html" target="_blank" style="font-size: 13px; color: #007bff; text-decoration: none; font-weight: bold;">
                <i class="fa-solid fa-up-right-from-square"></i> فتح محرر الكتابة (Copy/Paste)
              </a>
            </div>
            <div class="input-box">
              <textarea rows="8" name="content" id="mainContent" placeholder="الصق المحتوى هنا بعد نسخه من المحرر..." id="mainContent"><?= $art['content'] ?? ''  ?></textarea>
            </div>
          </div>

          <button type="submit" class="btn-submit" name="add_article">نشر المقال</button>

          <div class="divider">أو</div>

          <a href="dashboard.php" class="btn-back">لوحة التحكم</a>
        </form>
      </div>

      <div class="image-side">
        <img src="assest/hakim.png" alt="Hakim" />
      </div>
    </div>
  </div>
</body>

</html>