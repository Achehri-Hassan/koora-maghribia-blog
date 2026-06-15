<?php


session_start();

require_once "../src/config/connection.php";
require_once "../src/models/matches.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: view_matches.php");
    exit();
}

$match = readMatchById($id);

if (!$match) {
    header("Location: view_matches.php");
    exit();
}

$embed_url = '';

// condition is not empty url youtube
if (!empty($match['youtube_url'])) {

    preg_match('/(?:v=|\/embed\/|youtu\.be\/|\/live\/)([a-zA-Z0-9_-]{11})/', $match['youtube_url'], $m);
    $embed_url = isset($m[1]) ? "https://www.youtube.com/embed/" . $m[1] : '';
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
            <?php if (!empty($embed_url)): ?>
                <iframe
                    src="<?= htmlspecialchars($embed_url) ?>?autoplay=1&mute=1&rel=0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            <?php else: ?>
                <div class="no-stream">
                    رابط البث غير صالح أو غير متوفر حالياً.
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>