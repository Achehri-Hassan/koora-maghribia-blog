<?php
session_start();
require_once "connection.php";
require_once "matches.php";

// حماية الصفحة: فقط الأدمن (user_id == 1) هو اللي يقدر يدخل
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

$message = "";
$status = "";

if (isset($_POST['add_match'])) {
    $team_one_name = trim($_POST['team_one_name']);
    $team_two_name = trim($_POST['team_two_name']);
    $stadium       = trim($_POST['stadium']);
    $match_date    = $_POST['match_date'];
    $match_time    = $_POST['match_time'];

    // إعداد مسار مجلد الصور (assest)
    $target_dir = "assest/";

    // معالجة ورفع شعار الفريق الأول
    $img_one_name = time() . "_" . basename($_FILES["team_one_image"]["name"]);
    $target_file_one = $target_dir . $img_one_name;
    
    // معالجة ورفع شعار الفريق الثاني
    $img_two_name = time() . "_" . basename($_FILES["team_two_image"]["name"]);
    $target_file_two = $target_dir . $img_two_name;

    // رفع الملفات فعلياً للسيرفر
    if (move_uploaded_file($_FILES["team_one_image"]["tmp_name"], $target_file_one) && 
        move_uploaded_file($_FILES["team_two_image"]["tmp_name"], $target_file_two)) {
        
        // إدخال البيانات في قاعدة البيانات باستعمال الدالة اللي عندك فـ matches.php
        $result = createMatch($team_one_name, $img_one_name, $team_two_name, $img_two_name, $stadium, $match_date, $match_time);

        if ($result) {
            $message = "تمت جدولة المباراة بنجاح بنجاح! ⚽";
            $status = "success";
        } else {
            $message = "حدث خطأ أثناء حفظ المباراة في قاعدة البيانات.";
            $status = "error";
        }
    } else {
        $message = "فشل في رفع شعارات الفرق، تأكد من صلاحيات مجلد assest.";
        $status = "error";
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
  <link rel="stylesheet" href="css/create.css" /> 
  
  <style>
    /* ستايل خفيف مضاف فقط لإشعارات النجاح أو الخطأ دون تخريب الـ Design الأصلي */
    .alert {
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 14px;
      font-weight: bold;
      text-align: center;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
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
          <label>اسم الفريق الأول (المستضيف)</label>
          <input type="text" name="team_one_name" placeholder="مثال: الوداد الرياضي" required>
        </div>
        <div class="input-group">
          <label>شعار الفريق الأول</label>
          <input type="file" name="team_one_image" accept="image/*" required>
        </div>
      </div>

      <div class="row">
        <div class="input-group">
          <label>اسم الفريق الثاني (الضيف)</label>
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

      <div class="row">
        <div class="input-group">
          <label>تاريخ المباراة</label>
          <input type="date" name="match_date" required>
        </div>
        <div class="input-group">
          <label>توقيت المباراة</label>
          <input type="time" name="match_time" required>
        </div>
      </div>

      <div class="buttons">
        <button type="submit" class="btn-submit" name="add_match">جدولة المباراة الآن</button>
        <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
      </div>
    </form>
  </div>

</body>
</html>