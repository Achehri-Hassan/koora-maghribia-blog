<?php


require_once "article.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_article'])) {

  $title    = trim($_POST['title']);
  $content  = trim($_POST['content']);
  $category = $_POST["select"];


  $imageName = $_FILES['image']['name'];
  $tmpName   = $_FILES['image']['tmp_name'];

  if (empty($title) || empty($content) || empty($category) || empty($imageName)) {
    $error = "المرجو ملء جميع الخانات المطلوبة.";
  } else {
    $path = "assest/" . $imageName;

    if (move_uploaded_file($tmpName, $path)) {
      $article = new Article();


      $result = $article->createArticle($title, $content, $imageName, $category, $_SESSION['user_id']);

      if ($result) {

        header("Location: index.php");
        exit();
      } else {
        $error = "خطأ في قاعدة البيانات.";
      }
    } else {
      $error = "فشل في رفع الصورة.";
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
  <link rel="stylesheet" href="css/create.css" />
</head>

<body>

  <div class="form_container">
    <form method="post" enctype="multipart/form-data" class="form_card">

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
              <option value="" disabled selected>  اختر النوع </option>
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