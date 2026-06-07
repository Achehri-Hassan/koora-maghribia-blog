<?php

// session start
session_start();
require_once "../src/models/article.php";


if (!isset($_SESSION['is_admin'])) {
  header("Location: login.php");
  exit();
}

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_article'])) {


  $title    = htmlspecialchars(trim($_POST['title']));
  $content  = htmlspecialchars(trim($_POST['content']));
  $category = $_POST["select"] ?? '';


  if (empty($title) || empty($content) || empty($category)) {

    $error = "المرجو ملء جميع الخانات المطلوبة.";

  } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {

     $error = "المرجو اختيار صورة غلاف صالحة.";
     
  } else {
    $tmpName   = $_FILES['image']['tmp_name'];
    $ori_gName  = $_FILES['image']['name'];
    $extension = strtolower(pathinfo($ori_gName, PATHINFO_EXTENSION));


    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowedExtensions)) {
      $error = "صيغة الصورة غير مدعومة! المرجو رفع (JPG, JPEG, PNG, WEBP).";
    } else {

      $newImageName = uniqid('art_', true) . "." . $extension;
      $uploadPath   = "../assest/articles/" . $newImageName;

      if (move_uploaded_file($tmpName, $uploadPath)) {

        $result = createArticle($title, $content, $newImageName, $category, $_SESSION['is_admin']);

        header("Location: index.php");
        exit();
      }
    }
  }
}
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <title>إضافة مقال جديد</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="../assest/css/create.css" />
</head>

<body>

  <div class="form_container">

    <form method="post" enctype="multipart/form-data">

      <h1>إضافة مقال جديد</h1>

      <?php if ($error): ?>
        <p style="color:#ff4d4d; margin-bottom:15px;">
          <?= $error ?>
        </p>
      <?php endif; ?>

      <div class="input-group">

        <label>عنوان المقال</label>
        <input type="text" name="title" placeholder="اكتب العنوان هنا..." required>

      </div>

      <div class="row">
        <div class="input-group">

          <label>التصنيف</label>
          <select name="select" required>
            <option value="" disabled selected> اختر النوع </option>
            <option value="news"> أخبار </option>
            <option value="Transfers"> الانتقالات</option>
          </select>

        </div>

        <div class="input-group">

          <label>صورة الغلاف</label>
          <input type="file" name="image" required>

        </div>

      </div>

      <div class="input-group">
        <label>المحتوى</label>
        <textarea rows="8" name="content" placeholder="اكتب محتوى المقال هنا..." required></textarea>
      </div>


      <div class="buttons">
        <button type="submit" class="btn-submit" name="add_article"> نشر المقال </button>
        <a href="dashboard.php" class="btn-back"> الرجوع للوحة التحكم </a>
      </div>

    </form>

  </div>

</body>

</html>