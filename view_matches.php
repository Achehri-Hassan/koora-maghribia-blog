<?php 

session_start();
require_once "connection.php";
require_once "matches.php";

$current_filter = isset($_GET['day']) ? $_GET['day'] : 'today';

switch ($current_filter) {
    case 'tomorrow':
        $target_date = date('Y-m-d', strtotime('+1 day'));
        break;
    case 'yesterday':
        $target_date = date('Y-m-d', strtotime('-1 day'));
        break;
    case 'today':
    default:
        $target_date = date('Y-m-d');
        $current_filter = 'today';
        break;
}

$matches = readAllMatches($target_date);
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>أهم مباريات اليوم - Live Score</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
     <link rel="stylesheet" href="css/view_match.css">
</head>
<body>

<div class="container">
    <div class="top-bar">
        <div class="filter-buttons">
            <a href="view_matches.php?day=tomorrow" class="btn-filter btn-tomorrow <?= $current_filter === 'tomorrow' ? 'active' : '' ?>">مباريات الغد</a>
            <a href="view_matches.php?day=today" class="btn-filter btn-today <?= $current_filter === 'today' ? 'active' : '' ?>">مباريات اليوم</a>
            <a href="view_matches.php?day=yesterday" class="btn-filter btn-yesterday <?= $current_filter === 'yesterday' ? 'active' : '' ?>">مباريات الأمس</a>
        </div>
        <div class="header-titles">
            <span class="badge-device">بتوقيت جهازك</span>
            <h1 class="main-title">
                <?php
                if ($current_filter === 'tomorrow') echo "مباريات الغد (" . date('d-m-Y', strtotime('+1 day')) . ")";
                elseif ($current_filter === 'yesterday') echo "مباريات الأمس (" . date('d-m-Y', strtotime('-1 day')) . ")";
                else echo "أهم مباريات اليوم (" . date('d-m-Y') . ")";
                ?>
            </h1>
        </div>
    </div>

    <div class="matches-list">
        <?php if (empty($matches)): ?>
            <div class="no-matches">لا توجد مباريات لهذا اليوم</div>
        <?php else: ?>
            <?php foreach ($matches as $match):
                $start_iso = $match['match_date'] . 'T' . $match['match_time'];
                $end_iso = date('Y-m-d\TH:i:s', strtotime('+105 minutes', strtotime($start_iso)));
            ?>
            <div class="match-container class-match-live"
                 data-start="<?= $start_iso ?>"
                 data-end="<?= $end_iso ?>"
                 data-url="watch_match.php?id=<?= $match['id'] ?>">
                <div class="match-card">
                    <div class="team">
                        <img src="assest/mathes/<?= htmlspecialchars($match['team_one_image']) ?>" alt="<?= htmlspecialchars($match['team_one_name']) ?>">
                        <span class="team-name"><?= htmlspecialchars($match['team_one_name']) ?></span>
                    </div>

                    <div class="match-info">
                        <span class="match-time">
                            <i class="fa-regular fa-clock"></i> <?= date("g:i A", strtotime($match['match_time'])) ?>
                        </span>
                        <div class="live-score-display dynamic-score">0 - 0</div>
                        <span class="match-status live-status">جاري التحميل...</span>
                    </div>

                    <div class="team">
                        <img src="assest/mathes/<?= htmlspecialchars($match['team_two_image']) ?>" alt="<?= htmlspecialchars($match['team_two_name']) ?>">
                        <span class="team-name"><?= htmlspecialchars($match['team_two_name']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="index.php" class="back-home">
        <i class="fa-solid fa-arrow-right"></i> الرجوع للرئيسية
    </a>
</div>

<script>
function updateLiveScores() {
    const now = new Date();
    document.querySelectorAll('.class-match-live').forEach(container => {
        const startTime = new Date(container.getAttribute('data-start'));
        const endTime = new Date(container.getAttribute('data-end'));
        const scoreElement = container.querySelector('.dynamic-score');
        const statusBadge = container.querySelector('.live-status');
        const matchUrl = container.getAttribute('data-url');

        if (now < startTime) {
            statusBadge.textContent = "لم تبدأ بعد";
            statusBadge.style.backgroundColor = "#1e293b";
            statusBadge.style.animation = "none";
            
            // إلغاء كلاس التحويم والـ click
            container.classList.remove('clickable');
            container.onclick = null;

        } else if (now >= startTime && now <= endTime) {
            const diffInSeconds = Math.floor((now - startTime) / 1000);
            let currentMinute = Math.floor(diffInSeconds / 60);
            if (currentMinute < 1) currentMinute = 1;
            if (currentMinute > 90) currentMinute = 90;

            statusBadge.textContent = `مباشر 🔴 د ${currentMinute}'`;
            statusBadge.style.backgroundColor = "#ef4444";
            statusBadge.style.animation = "pulse 1.5s infinite";
            
            // تفعيل كلاس التحويم والـ click فقط في المباشر
            container.classList.add('clickable');
            container.onclick = function() {
                location.href = matchUrl;
            };
        } else {
            statusBadge.textContent = "انتهت";
            statusBadge.style.backgroundColor = "#22c55e";
            statusBadge.style.animation = "none";
            
            // إلغاء كلاس التحويم والـ click
            container.classList.remove('clickable');
            container.onclick = null;
        }
        scoreElement.textContent = "0 - 0";
    });
}
updateLiveScores();
setInterval(updateLiveScores, 1000);
</script>
</body>
</html>