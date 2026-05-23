<?php


session_start();
require_once "connection.php";
require_once "matches.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_matches.php");
    exit();
}

$match = readMatchById($_GET['id']);

if (!$match) {
    die("المباراة المطلوبة غير موجودة.");
}


function getYouTubeId($url) {
    $video_id = "";
    
    // التعبير النمطي المحدث ليشمل كلمة live داخل الرابط
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
    
    if (preg_match($pattern, $url, $matches)) {
        $video_id = $matches[1];
    }
    return $video_id;
}

$yt_id = getYouTubeId($match['youtube_url']);
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>بث مباشر: <?= htmlspecialchars($match['team_one_name']) ?> ضد <?= htmlspecialchars($match['team_two_name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #0f172a; color: #fff; margin: 0; padding: 20px; direction: rtl; }
        .watch-container { max-width: 900px; margin: 0 auto; }
        .match-header-info { text-align: center; margin-bottom: 20px; background: #1e293b; padding: 20px; border-radius: 12px; }
        .teams-versus { display: flex; justify-content: center; align-items: center; gap: 30px; font-size: 20px; font-weight: bold; }
        .team-box { display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .team-box img { width: 50px; height: 50px; object-fit: contain; }
        .versus-label { font-size: 24px; color: #ef4444; }
        .video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); background: #000; }
        .video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
        .stadium-info { margin-top: 15px; font-size: 14px; color: #94a3b8; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; color: #38bdf8; text-decoration: none; font-weight: bold; }
        .back-btn:hover { color: #7dd3fc; }
    </style>
</head>
<body>

<div class="watch-container">
    <div class="match-header-info">
        <div class="teams-versus">
            <div class="team-box">
                <img src="assest/mathes/<?= htmlspecialchars($match['team_one_image']) ?>" alt="">
                <span><?= htmlspecialchars($match['team_one_name']) ?></span>
            </div>
            <div class="versus-label">VS</div>
            <div class="team-box">
                <img src="assest/mathes/<?= htmlspecialchars($match['team_two_image']) ?>" alt="">
                <span><?= htmlspecialchars($match['team_two_name']) ?></span>
            </div>
        </div>
        <div class="stadium-info">
            <i class="fa-solid fa-stadium"></i> الملعب: <?= htmlspecialchars($match['stadium']) ?> | 
            <i class="fa-regular fa-calendar"></i> <?= date("d-m-Y", strtotime($match['match_date'])) ?>
        </div>
    </div>

    <div class="video-wrapper">
        <?php if (!empty($yt_id)): ?>
            <iframe 
                src="https://www.youtube.com/embed/<?= $yt_id ?>?autoplay=1&mute=1&rel=0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        <?php else: ?>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #94a3b8;">
                رابط البث غير صالح أو غير متوفر حالياً.
            </div>
        <?php endif; ?>
    </div>

    <a href="view_matches.php" class="back-btn">
        <i class="fa-solid fa-arrow-right"></i> العودة لجدول المباريات
    </a>
</div>

</body>
</html>