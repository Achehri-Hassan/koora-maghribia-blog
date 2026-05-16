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
            
          
            $result = $article->createArticle($title, $content, $imageName , $category);

            if ($result) {
                
                header("Location: dashboard.php?msg=success");
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
  <link rel="stylesheet" href="css/create.css"/>
</head>
<body>

  <div class="form_container">

    <div class="login-card">

      <div class="form-side">
        <div class="form-header">
          
          <h1>إضافة مقال جديد</h1>

          <?php if($error): ?>
              <p style="color: #ff4d4d;"><?= $error ?></p>
          <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data">

          <div class="input-group">
            <label>عنوان المقال</label>
            <div class="input-box">
              <input type="text" placeholder="اكتب العنوان هنا..." required name="title" />
              
            </div>
          </div>

          <div class="row_img_select">

            <div class="input-group">

              <label>التصنيف</label>
              <div class="input-box">
                <select required name="select">
                  <option value="" disabled selected>اختر النوع</option>
                  <option value="news">أخبار</option>
                  <option value="Transfers">الانتقالات</option>
                </select>
              </div>

            </div>

            <div class="input-group">

              <label>صورة الغلاف</label>
              <div class="input-box">
                <input type="file" name="image" required />
              </div>

            </div>
          </div>

          <div class="input-group">
            <label>المحتوى</label>
            <div class="input-box">
              <textarea rows="8" name="content" placeholder="اكتب محتوى المقال هنا..." required style="resize: vertical;"></textarea>
            </div>
          </div>

          
          
              <button type="submit" class="btn-submit" name="add_article">نشر المقال الآن</button>
              <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
       
        </form>
      </div>

     
    </div>


  </div>
</body>
</html>