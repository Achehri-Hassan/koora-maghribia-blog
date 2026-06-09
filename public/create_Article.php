<?php

// session start
session_start();
require_once "../src/models/article.php";


// verification is not admin 
if (!isset($_SESSION['is_admin'])) {
  header("Location: login.php");
  exit();
}

// declare variable to store error
$error = "";


// handel form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_article'])) {

  // declare variable to add name input 
  $title    = trim($_POST['title']);
  $content  = trim($_POST['content']);
  $category = $_POST["select"] ?? '';

  //  empty name input 
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- title page  -->
  <title>إضافة مقال جديد</title>
   <!-- font family -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
   <!-- icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- style -->
  <link rel="stylesheet" href="../assest/css/create.css" />
</head>

<body>
   
  <!-- form container -->
  <div class="form_container">
    
    <!--form  -->
    <form method="post" enctype="multipart/form-data">
       <!-- header form -->
      <h1>إضافة مقال جديد</h1>

      <!-- error -->
      <?php if ($error): ?>
        <p style="color:#ff4d4d; margin-bottom:15px;">
          <?=  htmlspecialchars($error) ?>
        </p>
      <?php endif; ?>

       <!-- input group -->
      <div class="input-group">
        <label>عنوان المقال</label>
        <input type="text" name="title" placeholder="اكتب العنوان هنا..." required>
      </div>
      
      <!-- row div  id he category & choice image about articles -->
      <div class="row">
        <div class="input-group">
          <label>التصنيف</label>
          <select name="select" required>
            <option value="" disabled selected> اختر النوع </option>
            <option value="news"> أخبار </option>
            <option value="Transfers"> الانتقالات</option>
          </select>
        </div>

         <!-- choice image  -->
        <div class="input-group">
          <label>صورة الغلاف</label>
          <input type="file" name="image" required accept=".jpg,.jpeg,.png,.webp">
        </div>

      </div>
       <!-- content -->
      <div class="input-group">
        <label>المحتوى</label>
        <textarea rows="8" name="content" placeholder="اكتب محتوى المقال هنا..." required></textarea>
      </div>

         <!-- div buttons -->
      <div class="buttons">
        <button type="submit" class="btn-submit" name="add_article"> نشر المقال </button>
        <a href="dashboard.php" class="btn-back"> الرجوع للوحة التحكم </a>
      </div>

    </form>

  </div>
  <!-- end container -->

</body>

</html>