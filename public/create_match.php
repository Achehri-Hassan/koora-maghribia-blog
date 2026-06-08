<?php

session_start();
require_once "../src/config/connection.php";
require_once "../src/models/matches.php";


// add condition about verify is admin 
if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}


// call function 
$teams        = getAllTeams();
$stadiums     = getAllStadiums();
$commentators = getAllCommentators();

$today         = date('Y-m-d');
$tomorrow      = date('Y-m-d', strtotime('+1 day'));
$allowed_dates = [$today, $tomorrow];

$message = "";

// start to handel form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_match'])) {


    // declare variable name input 
    $team_one_id    = $_POST['team_one_id'] ?? '';
    $team_two_id    = $_POST['team_two_id'] ?? '';
    $stadium_id     = $_POST['stadium_id'] ?? '';
    $commentator_id = $_POST['commentator_id'] ?? '';
    $match_date     = $_POST['match_date'];
    $match_time     = $_POST['match_time'];
    $youtube_url    = htmlspecialchars(trim($_POST['youtube_url']));
     
    // add condition about if empty any variable 
    if (empty($team_one_id) || empty($team_two_id) || empty($stadium_id) || empty($commentator_id)) {

        $message = "المرجو ملء جميع الخانات المطلوبة واختيار الفرق والملعب والمعلق.";
        
        // you not add 2 team name like (raja vs raja)
    } elseif ($team_one_id === $team_two_id) {
        $message = "لا يمكن مواجهة الفريق لنفسه! اختر فريقين مختلفين.";
       
        // 
    } elseif (!in_array($match_date, $allowed_dates)) {
        $message = "يمكنك جدولة المباريات لليوم أو الغد فقط.";

    } else {
        // call function to add property
        createMatch($team_one_id, $team_two_id, $stadium_id, $commentator_id, $match_date, $match_time, $youtube_url);
        header("Location: index.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
     <!-- title page -->
    <title>إضافة مباراة جديدة</title>
     <!-- font family -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- style css link -->
    <link rel="stylesheet" href="../assest/css/create.css" />
</head>

<body>

      <!-- form container -->
    <div class="form_container">

        <!-- form -->
        <form method="post">
           <!-- header form -->
          <h1>إضافة مباراة جديدة ⚽</h1>

            <!-- call error -->
            <?php if (!empty($message)): ?>
                <p class="error"> <?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <!-- row  team 1 & team 2 -->
            <div class="row">
                <!-- select team 1 -->
                <div class="input-group">
                    <label>الفريق الأول</label>
                    <select name="team_one_id" required>
                        <option value="">-- اختر الفريق الأول --</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= htmlspecialchars($team['id']) ?>"><?= htmlspecialchars($team['team_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                 <!-- select team 2 -->
                <div class="input-group">
                    <label>الفريق الثاني</label>
                    <select name="team_two_id" required>
                        <option value="">-- اختر الفريق الثاني --</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= htmlspecialchars($team['id']) ?>"><?= htmlspecialchars($team['team_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- choice stadium & commentator  -->
            <div class="row">
                <!-- select  stadium to play team 1  team 2-->
                <div class="input-group">
                    <label>الملعب</label>
                    <select name="stadium_id" required>
                        <option value="">-- اختر ملعب المباراة --</option>
                        <?php foreach ($stadiums as $st): ?>
                            <option value="<?= htmlspecialchars($st['id']) ?>"><?= htmlspecialchars($st['stadium_name']) ?> <?= htmlspecialchars($st['city']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- select commentator  -->
                <div class="input-group">
                    <label>المعلق</label>
                    <select name="commentator_id" required>
                        <option value="">-- اختر معلق المباراة --</option>
                        <?php foreach ($commentators as $cm): ?>
                            <option value="<?= htmlspecialchars($cm['id']) ?>"><?= htmlspecialchars($cm['commentator_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

             <!-- add links match live  -->
            <div class="input-group full-width">
                <label>رابط البث المباشر (YouTube Live Link)</label>
                <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=xxxxx" required>
            </div>
            
            <!-- row match : date & time  -->
            <div class="row">
                <!--  select date match -->
                <div class="input-group">
                    <label>تاريخ المباراة</label>
                    <input type="date" name="match_date" required min="<?= date('Y-m-d') ?>">
                </div>

                <!-- time match  -->
                <div class="input-group">
                    <label>توقيت المباراة</label>
                    <input type="time" name="match_time" required>
                </div>
            </div>

            <!-- button to submit data or return in  dashboard.php-->
            <div class="buttons">
                <button type="submit" class="btn-submit" name="add_match">جدولة المباراة</button>
                <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
            </div>
        </form>
        <!-- end div -->
    </div>
    <!-- end container -->
</body>

</html>