
<?php

require_once "../src/models/article.php";
session_start();



if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

// variable to add function get articles
$art = getArticleById($id);

if (!$art) {
    header("Location: index.php"); 
    exit();
}

// handel form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {
    
    
    // declare variable to add name input
    $title    = trim($_POST['title']);
    $content  = trim($_POST['content']);
    $category = $_POST["select"];
    
    $imageToSave = $_POST['old_image']; 

    //  handel image 
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName   = $_FILES['image']['tmp_name'];
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        
    
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp' ,"jfif"];

        if (in_array(strtolower($extension), $allowedExtensions)) {
        
            $imageName   = uniqid('art_', true) . "." . $extension;
            $uploadPath  = "../assest/articles/" . $imageName; 

            if (move_uploaded_file($tmpName, $uploadPath)) {

                $imageToSave = $imageName;
                $oldImagePath = "../assest/articles/" . $_POST['old_image'];
                if (!empty($_POST['old_image']) && file_exists($oldImagePath)) {
                    
                    unlink($oldImagePath); 
                }
            }
        }
    }

    // call function to update 
    updateArticle($id, $title, $content, $imageToSave, $category);
    header("Location: index.php");
    exit();
}
?>


<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- title page -->
    <title>تعديل مقال - البطولة</title>
     <!-- font family -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
     <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- style css -->
    <link rel="stylesheet" href="../assest/css/create.css" />
</head>

<body>

   <!-- form container -->
  <div class="form_container">
     
     <!-- form -->
    <form method="post" enctype="multipart/form-data" class="form_card">
        <h1>تعديل المقال</h1>
          
        <!-- add image  -->
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($art['image'] )?>">
        <input type="hidden" name="id" value="<?= $art['id'] ?>">
         
        <!-- return title articles -->
        <div class="input-group">
            <label>عنوان المقال</label>
            <input type="text" name="title" value="<?= htmlspecialchars($art['title']  ?? '') ?>" required >
        </div>
         
        <!-- return category  -->
        <div class="row">
            <div class="input-group">
                <label>التصنيف</label>
             <select name="select" required>
                <option value="news" <?= ($art['category'] ?? '') == 'news' ? 'selected' : '' ?>> أخبار</option>
                <option value="Transfers"<?= ($art['category'] ?? '') == 'Transfers' ? 'selected' : '' ?>> الانتقالات</option>
             </select>
            </div>

            <div class="input-group">
                <label>صورة الغلاف</label>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
            </div>
        </div>
        <!-- return content -->
        <div class="input-group">
            <label>المحتوى</label>
            <textarea rows="8" name="content" required ><?= htmlspecialchars($art['content']  ?? '') ?></textarea>
        </div>
         <!-- buttons to submit data or return dashboard -->
        <div class="buttons">
            <button type="submit" name="update_article" class="btn-submit" > تحديث المقال</button> 
            <a href="dashboard.php" class="btn-back"> إلغاء</a>
        </div>

    </form>
    <!-- end form -->

</div>
<!-- end  container -->

    
</body>
</html>
