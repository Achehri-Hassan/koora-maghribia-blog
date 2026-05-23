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
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f9; margin: 0; padding: 20px; direction: rtl; }
        .container { max-width: 850px; margin: 0 auto; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .header-titles { display: flex; flex-direction: column; align-items: flex-start; }
        .badge-device { background-color: #510505; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 11px; margin-bottom: 5px; font-weight: 600; }
        .main-title { background-color: #510505; color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 16px; font-weight: 700; margin: 0; }
        .filter-buttons { display: flex; gap: 8px; }
        .btn-filter { padding: 10px 18px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: 700; font-size: 13px; }
        .btn-tomorrow { background-color: #1e70bf; }
        .btn-today { background-color: #510505; }
        .btn-yesterday { background-color: #7b1fa2; }
        .btn-filter.active { outline: 3px solid #111; }
        .matches-list { display: flex; flex-direction: column; gap: 16px; }
        .match-container { background-color: #ffffff; border-radius: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid #e6ecf0; overflow: hidden; cursor: pointer; transition: transform 0.2s ease; }
        .match-container:hover { transform: translateY(-2px); }
        .match-card { display: grid; grid-template-columns: 1fr 180px 1fr; align-items: center; padding: 24px 30px; text-align: center; }
        .team { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .team img { width: 60px; height: 60px; object-fit: contain; }
        .team-name { font-size: 15px; font-weight: 700; color: #2c3e50; }
        .match-info { display: flex; flex-direction: column; align-items: center; }
        .match-time { font-size: 12px; color: #8a99a6; font-weight: 600; margin-bottom: 6px; }
        .live-score-display { font-size: 32px; font-weight: 800; color: #1e293b; letter-spacing: 6px; margin: 2px 0; }
        .match-status { color: #fff; font-size: 11px; padding: 5px 16px; border-radius: 20px; font-weight: 700; }
        .no-matches { text-align: center; background: #fff; padding: 50px 20px; border-radius: 14px; color: #64748b; font-size: 16px; border: 1px solid #e2e8f0; }
        .back-home { display: inline-flex; align-items: center; gap: 8px; margin-top: 25px; color: #510505; text-decoration: none; font-weight: 700; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    </style>
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
                 onclick="location.href='watch_match.php?id=<?= $match['id'] ?>'">
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

        if (now < startTime) {
            statusBadge.textContent = "لم تبدأ بعد";
            statusBadge.style.backgroundColor = "#1e293b";
            statusBadge.style.animation = "none";

        } else if (now >= startTime && now <= endTime) {
            const diffInSeconds = Math.floor((now - startTime) / 1000);
            let currentMinute = Math.floor(diffInSeconds / 60);
            if (currentMinute < 1) currentMinute = 1;
            if (currentMinute > 90) currentMinute = 90;

            statusBadge.textContent = `مباشر 🔴 د ${currentMinute}'`;
            statusBadge.style.backgroundColor = "#ef4444";
            statusBadge.style.animation = "pulse 1.5s infinite";
        } else {
            statusBadge.textContent = "انتهت";
            statusBadge.style.backgroundColor = "#22c55e";
            statusBadge.style.animation = "none";
        }
        scoreElement.textContent = "0 - 0";
    });
}
updateLiveScores();
setInterval(updateLiveScores, 1000);
</script>
</body>
</html>