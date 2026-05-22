<?php
session_start();
require_once "connection.php";
require_once "matches.php";

// حماية الصفحة: فقط الأدمن (user_id == 1) هو اللي يقدر يدخل
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

$today     = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$tomorrow  = date('Y-m-d', strtotime('+1 day'));
$allowed_dates = [$yesterday, $today, $tomorrow];

$message = "";
$status = "";

if (isset($_POST['add_match'])) {
    $team_one_name  = trim($_POST['team_one_name']);
    $team_two_name  = trim($_POST['team_two_name']);
    $stadium        = trim($_POST['stadium']);
    $match_date     = $_POST['match_date']; 
    $match_time     = $_POST['match_time'];
    $team_one_score = intval($_POST['team_one_score']);
    $team_two_score = intval($_POST['team_two_score']);

    if (!in_array($match_date, $allowed_dates)) {
        $message = "عذراً! يمكنك فقط جدولة المباريات لتاريخ اليوم، غداً، أو الأمس.";
        $status = "error";
    } elseif ($team_one_score < 0 || $team_two_score < 0) {
        $message = "الرجاء إدخال أهداف صحيحة.";
        $status = "error";
    } else {
        $target_dir = "assest/mathes/";

        $img_one_name = time() . "_" . basename($_FILES["team_one_image"]["name"]);
        $target_file_one = $target_dir . $img_one_name;
        
        $img_two_name = time() . "_" . basename($_FILES["team_two_image"]["name"]);
        $target_file_two = $target_dir . $img_two_name;

        if (move_uploaded_file($_FILES["team_one_image"]["tmp_name"], $target_file_one) && 
            move_uploaded_file($_FILES["team_two_image"]["tmp_name"], $target_file_two)) {
            
            
            $match_id = createMatch($team_one_name, $img_one_name, $team_two_name, $img_two_name, $stadium, $match_date, $match_time, $team_one_score, $team_two_score);

            if ($match_id) {
               
                $total_goals = $team_one_score + $team_two_score;
                $generated_minutes = [];
                
                // توليد دقائق بدون تكرار بين 1 و 10 دقائق
                // (ملاحظة: إذا أردت إرجاعها للنظام الحقيقي 90 دقيقة، قم بتغيير rand(1, 10) إلى rand(1, 90))
                while (count($generated_minutes) < $total_goals) {
                    $rand_min = rand(1, 10); 
                    if (!in_array($rand_min, $generated_minutes)) {
                        $generated_minutes[] = $rand_min;
                    }
                }
                
                // ترتيب الدقائق عشوائياً قبل توزيعها
                shuffle($generated_minutes);

                // حفظ أهداف الفريق الأول (المستضيف) -> نرسل رقم 1
                for ($i = 0; $i < $team_one_score; $i++) {
                    $minute = array_pop($generated_minutes);
                    createMatchGoal($match_id, 1, $minute);
                }

                // حفظ أهداف الفريق الثاني (الضيف) -> نرسل رقم 2
                for ($i = 0; $i < $team_two_score; $i++) {
                    $minute = array_pop($generated_minutes);
                    createMatchGoal($match_id, 2, $minute);
                }

                $message = "تمت جدولة المباراة وتوليد دقائق الأهداف بنجاح! ⚽";
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
    .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: bold; text-align: center; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .score-input { text-align: center; font-weight: bold; font-size: 16px; }
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

      <div class="row" style="background: rgba(81, 5, 5, 0.05); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <div class="input-group">
          <label style="color: #510505; font-weight: bold;">أهداف الفريق الأول (النهائية)</label>
          <input type="number" name="team_one_score" class="score-input" min="0" value="0" required>
        </div>
        <div class="input-group">
          <label style="color: #510505; font-weight: bold;">أهداف الفريق الثاني (النهائية)</label>
          <input type="number" name="team_two_score" class="score-input" min="0" value="0" required>
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
        <button type="submit" class="btn-submit" name="add_match">جدولة المباراة وتوليد النتيجة</button>
        <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
      </div>
    </form>
  </div>

</body>
</html>