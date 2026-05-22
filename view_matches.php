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

// جلب المباريات مع الأهداف
$matches = readAllMatchesWithGoals($target_date);
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
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container { max-width: 850px; margin: 0 auto; }
        
        /* شريط التحكم العلوي */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .header-titles { display: flex; flex-direction: column; align-items: flex-start; }
        .badge-device { background-color: #510505; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 11px; margin-bottom: 5px; font-weight: 600; letter-spacing: 0.5px; }
        .main-title { background-color: #510505; color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 16px; font-weight: 700; margin: 0; boxShadow: 0 4px 12px rgba(81,5,5,0.15); }
        .filter-buttons { display: flex; gap: 8px; }
        .btn-filter { padding: 10px 18px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: 700; font-size: 13px; transition: all 0.2s ease; }
        .btn-filter:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-tomorrow { background-color: #1e70bf; }
        .btn-today { background-color: #510505; }
        .btn-yesterday { background-color: #7b1fa2; }
        .btn-filter.active { outline: 3px solid #111; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
       
        .matches-list { display: flex; flex-direction: column; gap: 16px; }
        
        /* كارت المباراة المطور */
        .match-container {
            background-color: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #e6ecf0;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .match-container:hover { transform: translateY(-2px); }
        
        .match-card {
            display: grid;
            grid-template-columns: 1fr 180px 1fr;
            align-items: center;
            padding: 24px 30px;
            text-align: center;
        }
        
        /* تصميم الفِرق */
        .team { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .team img { width: 60px; height: 60px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.06)); }
        .team-name { font-size: 15px; font-weight: 700; color: #2c3e50; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        /* منطقة السكور والوقت الوسطى */
        .match-info { display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .match-time { font-size: 12px; color: #8a99a6; font-weight: 600; margin-bottom: 6px; display: flex; align-items: center; gap: 4px; }
        .live-score-display { font-size: 32px; font-weight: 800; color: #1e293b; letter-spacing: 6px; margin: 2px 0; font-variant-numeric: tabular-nums; }
        .match-status { color: #fff; font-size: 11px; padding: 5px 16px; border-radius: 20px; font-weight: 700; letter-spacing: 0.3px; }

        /* شريط الأحداث الأفقي الجديد المتناسق */
        .match-events {
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 10px 30px;
            display: grid;
            grid-template-columns: 1fr 1fr; /* منقسم بالتساوي بين اليمين واليسار */
            gap: 20px;
        }
        
        /* حاويات الأهداف لكل فريق */
        .team-events-col { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
        .team-events-col.team-1-goals { justify-content: flex-start; }
        .team-events-col.team-2-goals { justify-content: flex-end; }

        /* ودجيت الهدف الصغير (الدقيقة + الكورة) */
        .event-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 3px 8px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .event-minute { font-size: 11px; font-weight: 700; color: #475569; }
        .event-icon { color: #334155; font-size: 11px; }
        
        /* ديسلاي لايف كورة داخل الودجيت */
        .class-match-live .event-icon .fa-soccer-ball {
            animation: rotateBall 4s linear infinite;
        }
        @keyframes rotateBall { 100% { transform: rotate(360deg); } }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        .no-matches { text-align: center; background: #fff; padding: 50px 20px; border-radius: 14px; color: #64748b; font-size: 16px; border: 1px solid #e2e8f0; font-weight: 600; }
        .back-home { display: inline-flex; align-items: center; gap: 8px; margin-top: 25px; color: #510505; text-decoration: none; font-weight: 700; font-size: 14px; transition: color 0.2s; }
        .back-home:hover { color: #000; }
        
        /* ريسبونسيف للموبايل */
        @media (max-width: 650px) {
            .match-card { grid-template-columns: 1fr; gap: 15px; padding: 20px; }
            .match-events { grid-template-columns: 1fr; gap: 10px; padding: 10px 20px; }
            .team-events-col.team-2-goals { justify-content: flex-start; }
            .top-bar { flex-direction: column-reverse; align-items: stretch; }
            .filter-buttons { justify-content: space-between; }
            .btn-filter { flex: 1; text-align: center; padding: 10px 5px; font-size: 12px; }
        }
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
                <div class="no-matches">
                    <i class="fa-solid fa-calendar-xmark fa-2x" style="margin-bottom: 12px; color: #94a3b8; display:block;"></i>
                    لا توجد مباريات مجدولة لهذا التاريخ.
                </div>
            <?php else: ?>
                <?php foreach ($matches as $match): 
                    $start_iso = $match['match_date'] . 'T' . $match['match_time'];
                    $end_iso = date('Y-m-d\TH:i:s', strtotime('+105 minutes', strtotime($start_iso)));
                    $goals_json = json_encode($match['goals'] ?? []);
                ?>
                    <div class="match-container class-match-live" 
                         data-start="<?= $start_iso ?>" 
                         data-end="<?= $end_iso ?>"
                         data-final-score-1="<?= $match['team_one_score'] ?>"
                         data-final-score-2="<?= $match['team_two_score'] ?>"
                         data-goals='<?= htmlspecialchars($goals_json, ENT_QUOTES, 'UTF-8') ?>'>
                        
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

                                <span class="match-status live-status" 
                                      data-start="<?= $start_iso ?>" 
                                      data-end="<?= $end_iso ?>">
                                        جاري التحميل...
                                </span>
                            </div>

                            <div class="team">
                                <img src="assest/mathes/<?= htmlspecialchars($match['team_two_image']) ?>" alt="<?= htmlspecialchars($match['team_two_name']) ?>">
                                <span class="team-name"><?= htmlspecialchars($match['team_two_name']) ?></span>
                            </div>
                        </div>

                        <div class="match-events dynamic-events" style="display: none;"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-right"></i> الرجوع للرئيسية</a>
    </div>

    <script>
    function updateLiveScores() {
        const now = new Date();

        document.querySelectorAll('.class-match-live').forEach(container => {
            const startTime = new Date(container.getAttribute('data-start'));
            const endTime = new Date(container.getAttribute('data-end'));
            
            const finalScore1 = parseInt(container.getAttribute('data-final-score-1'));
            const finalScore2 = parseInt(container.getAttribute('data-final-score-2'));
            const goals = JSON.parse(container.getAttribute('data-goals'));

            const scoreElement = container.querySelector('.dynamic-score');
            const statusBadge = container.querySelector('.live-status');
            const eventsContainer = container.querySelector('.dynamic-events');

            let currentMinute = 0;
            let isLive = false;
            let isFinished = false;

            if (now < startTime) {
                statusBadge.textContent = "لم تبدأ بعد";
                statusBadge.style.backgroundColor = "#1e293b";
                statusBadge.style.animation = "none";
                scoreElement.textContent = "0 - 0";
                eventsContainer.style.display = "none";
                eventsContainer.innerHTML = "";
                return;
            } else if (now >= startTime && now <= endTime) {
                isLive = true;
                statusBadge.style.backgroundColor = "#ef4444";
                statusBadge.style.animation = "pulse 1.5s infinite";

                const diffInSeconds = Math.floor((now - startTime) / 1000);
                let calculatedMin = Math.floor(diffInSeconds / 60);
                
                if (calculatedMin < 1) calculatedMin = 1;
                
                if (calculatedMin > 45 && calculatedMin <= 60) {
                    statusBadge.textContent = "الشوط الأول +";
                    currentMinute = 45;
                } else if (calculatedMin > 60) {
                    currentMinute = calculatedMin - 15; 
                    if (currentMinute > 90) currentMinute = 90;
                    statusBadge.textContent = `مباشر 🔴 د ${currentMinute}'`;
                } else {
                    currentMinute = calculatedMin;
                    statusBadge.textContent = `مباشر 🔴 د ${currentMinute}'`;
                }
            } else {
                isFinished = true;
                statusBadge.textContent = "انتهت";
                statusBadge.style.backgroundColor = "#22c55e";
                statusBadge.style.animation = "none";
                currentMinute = 90; 
            }

            let runningScore1 = 0;
            let runningScore2 = 0;
            
            // تقسيم بناء الـ HTML لجهتين: يمين وليسار
            let team1GoalsHTML = "";
            let team2GoalsHTML = "";

            goals.forEach(goal => {
                if (isFinished || (isLive && currentMinute >= goal.minute)) {
                    if (goal.team_number === 1) {
                        runningScore1++;
                        team1GoalsHTML += `
                            <div class="event-badge">
                                <span class="event-icon"><i class="fa-solid fa-soccer-ball"></i></span>
                                <span class="event-minute">${goal.minute}'</span>
                            </div>`;
                    } else {
                        runningScore2++;
                        team2GoalsHTML += `
                            <div class="event-badge">
                                <span class="event-minute">${goal.minute}'</span>
                                <span class="event-icon"><i class="fa-solid fa-soccer-ball"></i></span>
                            </div>`;
                    }
                }
            });

            // حقن النتيجة اللحظية أو النهائية
            if (isFinished) {
                scoreElement.textContent = `${finalScore1} - ${finalScore2}`;
            } else {
                scoreElement.textContent = `${runningScore1} - ${runningScore2}`;
            }

            // إظهار البارات على شكل حاويتين متناسقتين
            if (team1GoalsHTML !== "" || team2GoalsHTML !== "") {
                eventsContainer.innerHTML = `
                    <div class="team-events-col team-1-goals">${team1GoalsHTML}</div>
                    <div class="team-events-col team-2-goals">${team2GoalsHTML}</div>
                `;
                eventsContainer.style.display = "grid";
            } else {
                eventsContainer.style.display = "none";
            }
        });
    }

    updateLiveScores();
    setInterval(updateLiveScores, 1000);
    </script>
</body>
</html>