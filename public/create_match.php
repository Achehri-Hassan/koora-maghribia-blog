<?php



session_start();
require_once "../src/config/connection.php";
require_once "../src/models/matches.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}


$teams = getAllTeams();

$today         = date('Y-m-d');
$tomorrow      = date('Y-m-d', strtotime('+1 day'));
$allowed_dates = [$today, $tomorrow];

$message = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_match'])) {


    $team_one_id = $_POST['team_one_id'] ?? '';
    $team_two_id = $_POST['team_two_id'] ?? '';

    $stadium     = htmlspecialchars(trim($_POST['stadium']));
    $match_date  = $_POST['match_date'];
    $match_time  = $_POST['match_time'];
    $youtube_url = htmlspecialchars(trim($_POST['youtube_url']));

    if (empty($team_one_id) || empty($team_two_id) || empty($stadium)) {
        $message = "المرجو ملء جميع الخانات المطلوبة واختيار الفرق.";
    } elseif ($team_one_id === $team_two_id) {
        $message = "لا يمكن مواجهة الفريق لنفسه! اختر فريقين مختلفين.";
    } elseif (!in_array($match_date, $allowed_dates)) {
        $message = "يمكنك جدولة المباريات لليوم أو الغد فقط.";
    } else {


        createMatch($team_one_id, $team_two_id, $stadium, $match_date, $match_time, $youtube_url);
        header("Location: index.php");
        exit();
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
        <form method="post">
            <h1>إضافة مباراة جديدة ⚽</h1>

            <?php if (!empty($message)): ?>
                <p class="error"> <?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div class="row">
                <div class="input-group">
                    <label>الفريق الأول</label>
                    <select name="team_one_id" required>
                        <option value="">-- اختر الفريق الأول --</option>
                        <?php foreach ($teams as $team): ?>
                            <option value="<?= htmlspecialchars($team['id']) ?>"><?= htmlspecialchars($team['team_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

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
            <div class="input-group full-width">
                <label>الملعب</label>
                <input type="text" name="stadium" placeholder="مثال: مركب محمد الخامس" required>
            </div>

            <div class="input-group full-width">
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