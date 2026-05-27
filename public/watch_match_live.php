<?php


session_start();
require_once "../src/config/connection.php";
require_once "../src/models/matches.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_matches.php");
    exit();
}

$match = readMatchById($_GET['id']);

if (!$match) {
    die("المباراة المطلوبة غير موجودة.");
}

?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>بث مباشر: <?= htmlspecialchars($match['team_one_name']) ?> ضد <?= htmlspecialchars($match['team_two_name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../assest/css/watch_match_live.css">
</head>
<body>

    <a href="view_matches.php" class="back-btn">
        <i class="fa-solid fa-arrow-right"></i> مباريات اليوم مباشر
    </a>

<div class="watch-container">

    <div class="video-wrapper">
        <?php if (!empty($match['youtube_url'])): ?>
            <?php 
                
                $embed_url = str_replace(['watch?v=', 'live/', 'youtu.be/'], ['embed/', 'embed/', 'youtube.com/embed/'], $match['youtube_url']);
                $embed_url = explode('?', $embed_url)[0];
            ?>
            <iframe 
                src="<?= htmlspecialchars($embed_url) ?>?autoplay=1&mute=1&rel=0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        <?php else: ?>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #94a3b8;">
                رابط البث غير صالح أو غير متوفر حالياً.
            </div>
        <?php endif; ?>
    </div>

  
</div>

</body>
</html>