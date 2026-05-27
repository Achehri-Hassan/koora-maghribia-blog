<?php


require_once "../src/models/article.php";
session_start();


if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}


$art = null;

$id = $_GET['id'];
$art = getArticleById($id);


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {


    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST["select"];

    if (!empty($_FILES['image']['name'])) {
        
        $imageName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $path = "../assest/articles/" . $imageName;
        move_uploaded_file($tmpName, $path);
        $imageToSave = $imageName;

    } else {
        $imageToSave = $_POST['old_image'];
    }

   updateArticle($id, $title, $content, $imageToSave, $category);
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
    <link rel="stylesheet" href="../assest/css/create.css" />
</head>

<body>


  <div class="form_container">

    <form method="post" enctype="multipart/form-data" class="form_card">

        <h1>تعديل المقال</h1>

        <input type="hidden" name="old_image" value="<?= $art['image'] ?>">
        <input type="hidden" name="id" value="<?= $art['id'] ?>">

        <div class="input-group">
            <label>عنوان المقال</label>
            <input type="text" name="title" value="<?= $art['title'] ?? '' ?>" required >
        </div>

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
                <input type="file" name="image">
            </div>
        </div>

        <div class="input-group">
            <label>المحتوى</label>
            <textarea rows="8"name="content"required><?= $art['content'] ?? '' ?></textarea>
        </div>

        <div class="buttons">
            <button type="submit" name="update_article" class="btn-submit"> تحديث المقال</button> 
            <a href="dashboard.php" class="btn-back"> إلغاء</a>
        </div>

    </form>

</div>

    
</body>
</html>