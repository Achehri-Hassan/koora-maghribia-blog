<?php


session_start();
require_once "../src/models/matches.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

$today         = date('Y-m-d');
$tomorrow      = date('Y-m-d', strtotime('+1 day'));
$allowed_dates = [$today, $tomorrow];

$message = "";
$status  = "";

if (isset($_POST['add_match'])) {

    $team_one_name = htmlspecialchars(trim($_POST['team_one_name']));
    $team_two_name = htmlspecialchars(trim($_POST['team_two_name']));
    $stadium       = htmlspecialchars(trim($_POST['stadium']));
    $match_date    = $_POST['match_date'];
    $match_time    = $_POST['match_time'];
    $youtube_url   = htmlspecialchars(trim($_POST['youtube_url']));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    $ext_one = strtolower(pathinfo($_FILES["team_one_image"]["name"], PATHINFO_EXTENSION));
    $ext_two = strtolower(pathinfo($_FILES["team_two_image"]["name"], PATHINFO_EXTENSION));

    if (empty($team_one_name) || empty($team_two_name) || empty($stadium)) {
        $message = "المرجو ملء جميع الخانات المطلوبة.";
        $status  = "error";
        
    } elseif (
        !isset($_FILES['team_one_image']) || $_FILES['team_one_image']['error'] !== UPLOAD_ERR_OK ||
        !isset($_FILES['team_two_image']) || $_FILES['team_two_image']['error'] !== UPLOAD_ERR_OK
    ){
        $message = "المرجو رفع شعاري الفريقين.";
        $status  = "error";

    } elseif (!in_array($ext_one, $allowedExtensions) || !in_array($ext_two, $allowedExtensions)) {

        $message = "صيغة الصورة غير مدعومة! المرجو رفع (JPG, JPEG, PNG, WEBP).";
        $status  = "error";

    } elseif (!in_array($match_date, $allowed_dates)) {
        $message = "يمكنك جدولة المباريات لليوم أو الغد فقط.";
        $status  = "error";
    } else {
        $img_one_name = uniqid('team1_', true) . "." . $ext_one;
        $img_two_name = uniqid('team2_', true) . "." . $ext_two;

        $target_dir      = "../assest/mathes/";
        $target_file_one = $target_dir . $img_one_name;
        $target_file_two = $target_dir . $img_two_name;

        if (
            move_uploaded_file($_FILES["team_one_image"]["tmp_name"], $target_file_one) &&
            move_uploaded_file($_FILES["team_two_image"]["tmp_name"], $target_file_two)
        ) {
            createMatch(
                $team_one_name,
                $img_one_name,
                $team_two_name,
                $img_two_name,
                $stadium,
                $match_date,
                $match_time,
                $youtube_url
            );
            header("Location: index.php");
            exit();
        } else {
            $message = "فشل رفع شعارات الفرق.";
            $status  = "error";
        }
    }
}
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>إضافة مباراة جديدة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assest/css/create.css" />
   
</head>

<body>
    <div class="form_container">
        <form method="post" enctype="multipart/form-data">
            <h1>إضافة مباراة جديدة ⚽</h1>

            <?php if (!empty($message)): ?>
                 <p class="error">  <?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div class="row">
                <div class="input-group">
                    <label>اسم الفريق الأول</label>
                    <input type="text" name="team_one_name" placeholder="مثال: الوداد الرياضي" required>
                </div>
                <div class="input-group">
                    <label>شعار الفريق الأول</label>
                    <input type="file" name="team_one_image" accept="image/*" required>
                </div>
            </div>

            <div class="row">
                <div class="input-group">
                    <label>اسم الفريق الثاني</label>
                    <input type="text" name="team_two_name" placeholder="مثال: الرجاء الرياضي" required>
                </div>
                <div class="input-group">
                    <label>شعار الفريق الثاني</label>
                    <input type="file" name="team_two_image" accept="image/*" required>
                </div>
            </div>

            <div class="input-group">
                <label>الملعب</label>
                <input type="text" name="stadium" placeholder="مثال: مركب محمد الخامس" required>
            </div>

            <div class="input-group">
                <label>رابط البث المباشر (YouTube Live Link)</label>
                <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=xxxxx" required>
            </div>

            <div class="row">
                <div class="input-group">
                    <label>تاريخ المباراة</label>
                    <input type="date" name="match_date" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="input-group">
                    <label>توقيت المباراة</label>
                    <input type="time" name="match_time" required>
                </div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn-submit" name="add_match">جدولة المباراة</button>
                <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
            </div>
        </form>
    </div>
</body>

</html>