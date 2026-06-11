
<?php

session_start();

require_once "../src/models/matches.php";
require_once "../src/config/connection.php";

deleteOldMatches(3);
$allowed = ['today', 'tomorrow', 'yesterday'];

$current_filter = in_array($_GET['day'] ?? '', $allowed) ? $_GET['day'] : 'today';

$days = [
    'today' => 0,
    'tomorrow' => 1,
    'yesterday' => -1
];

$match_days_names = [
    'today' => "أهم مباريات اليوم",
    'tomorrow' => "مباريات الغد",
    'yesterday' => "مباريات الأمس"
];

$current_date = date('d-m-Y', strtotime($days[$current_filter] . ' day'));

$title = $match_days_names[$current_filter] . " ($current_date)";

$target_date = date('Y-m-d', strtotime($days[$current_filter] . ' day'));

$matches = readAllMatches($target_date);

?>


<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- title page -->
    <title>أهم مباريات اليوم - Live Score</title>

    <!-- font family -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- font icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

     <!-- link css -->
    <link rel="stylesheet" href="../assest/css/view_match.css">
    <link rel="stylesheet" href="../assest/css/components/header.css">
    <link rel="stylesheet" href="../assest/css/components/footer.css">
</head>
<body>
    
    <!-- call header -->
    <?php include '../includes/header.php';?>
    
    <div class="container">
        <div class="top-bar">
            <div class="filter-buttons">
                <a href="view_matches.php?day=tomorrow" class="btn-filter btn-tomorrow <?= $current_filter === 'tomorrow' ? 'active' : '' ?>">مباريات الغد</a>
                <a href="view_matches.php?day=today"    class="btn-filter btn-today    <?= $current_filter === 'today'    ? 'active' : '' ?>">مباريات اليوم</a>
                <a href="view_matches.php?day=yesterday" class="btn-filter btn-yesterday <?= $current_filter === 'yesterday' ? 'active' : '' ?>">مباريات الأمس</a>
            </div>
            <div class="header-titles">
                <h1 class="main-title"><?= htmlspecialchars($title) ?></h1>
            </div>
        </div>

        <div class="matches-list">
            <?php if (empty($matches)): ?>
                <div class="no-matches">لا توجد مباريات لهذا اليوم</div>
            <?php else: ?>
            <?php foreach ($matches as $match):
                    $start_ma = $match['match_date'] . 'T' . $match['match_time'];
                    $end_iso   = date('Y-m-d\TH:i:s', strtotime('+105 minutes', strtotime($start_ma)));
                ?>
                <div class="match-container class-match-live"
                     data-start="<?= htmlspecialchars($start_ma) ?>"
                     data-end="<?= htmlspecialchars($end_iso) ?>"
                     data-url="watch_match_live.php?id=<?= htmlspecialchars($match['id']) ?>">
                    
                    <div class="match-card">

                        <div class="team">
                            <img src="../assest/mathes/<?= htmlspecialchars($match['team_one_image']) ?>" alt="<?= htmlspecialchars($match['team_one_name']) ?>">
                            <span class="team-name"><?= htmlspecialchars($match['team_one_name']) ?></span>
                        </div>

                        <div class="match-info">
                            <span class="match-time">
                                <i class="fa-regular fa-clock"></i> <?= date("g:i A", strtotime($match['match_time'])) ?>
                            </span>
                            <span class="match-status live-status">جاري التحميل...</span>
                        </div>

                        <div class="team">
                            <img src="../assest/mathes/<?= htmlspecialchars($match['team_two_image']) ?>" alt="<?= htmlspecialchars($match['team_two_name']) ?>">
                            <span class="team-name"><?= htmlspecialchars($match['team_two_name']) ?></span>
                        </div>

                    </div>
                    <div class="match-details-bar">
                        <span class="detail-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <b>الملعب:</b> <?= htmlspecialchars($match['stadium_name']) ?>
                        </span>
                        <span class="detail-item">
                            <i class="fa-solid fa-microphone"></i>
                            <b>المعلق:</b> <?= htmlspecialchars($match['commentator_name']) ?>
                        </span>
                    </div>

                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

     <!-- call header -->
     <?php include '../includes/footer.php'; ?>

     <!-- script js -->
    <script src="../assest/js/view_match.js"></script>
</body>
</html>