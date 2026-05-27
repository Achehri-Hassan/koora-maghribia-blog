<?php

session_start();
// require_once "connection.php";
require_once "../src/models/matches.php";


if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$allowed_dates = [ $today, $tomorrow];

$message = "";
$status = "";

if (isset($_POST['add_match'])) {
    $team_one_name = trim($_POST['team_one_name']);
    $team_two_name = trim($_POST['team_two_name']);
    $stadium       = trim($_POST['stadium']);
    $match_date    = $_POST['match_date'];
    $match_time    = $_POST['match_time'];
    $youtube_url   = trim($_POST['youtube_url']);

    if (!in_array($match_date, $allowed_dates)) {
        $message = "يمكنك جدولة المباريات لأيام الأمس، اليوم، أو الغد فقط.";
        $status = "error";
    } else {
        $target_dir = "../assest/mathes/";

        $img_one_name = time() . "_" . basename($_FILES["team_one_image"]["name"]);
        $target_file_one = $target_dir . $img_one_name;

        $img_two_name = time() . "_" . basename($_FILES["team_two_image"]["name"]);
        $target_file_two = $target_dir . $img_two_name;

        if (
            move_uploaded_file($_FILES["team_one_image"]["tmp_name"], $target_file_one) &&
            move_uploaded_file($_FILES["team_two_image"]["tmp_name"], $target_file_two)
        ) {

          $match_id = createMatch( $team_one_name, $img_one_name, $team_two_name, $img_two_name,$stadium,
                $match_date,
                $match_time,
                $youtube_url
            );

            header("Location: index.php");

        } else {
            $message = "فشل رفع شعارات الفرق، يرجى التحقق من مسار ومساحة السيرفر.";
            $status = "error";
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
    <style>
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: bold; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="form_container">
        <form method="post" enctype="multipart/form-data">
            <h1>إضافة مباراة جديدة ⚽</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $status ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
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