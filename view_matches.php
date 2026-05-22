

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
    <title>أهم مباريات اليوم</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <style>
      
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #eef2f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-titles {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .badge-device {
            background-color: #510505;
            color: #fff;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .main-title {
            background-color: #510505;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-filter {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .btn-filter:hover {
            opacity: 0.9;
        }

        .btn-tomorrow { background-color: #1e70bf; }
        .btn-today { background-color: #510505; }
        .btn-yesterday { background-color: #8b00ff; }

        .btn-filter.active {
            outline: 3px solid #000;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

       
        .matches-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .match-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            padding: 20px 40px;
            text-align: center;
            border: 1px solid #e1e6eb;
        }

        .team {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .team img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .team-name {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }

        .match-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 140px;
        }

        .match-time {
            font-size: 16px;
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }

        .match-status {
            color: #fff;
            font-size: 12px;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 600;
            transition: background-color 0.5s ease;
        }

        /* تأثير الوميض للمباريات المباشرة */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.4; }
            100% { opacity: 1; }
        }

        .no-matches {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            color: #777;
            font-size: 18px;
            border: 1px solid #e1e6eb;
        }
        
        .back-home {
            display: inline-block;
            margin-top: 20px;
            color: #510505;
            text-decoration: none;
            font-weight: bold;
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
                    <i class="fa-solid fa-calendar-xmark fa-2x" style="margin-bottom: 10px; display:block;"></i>
                    لا توجد مباريات مجدولة لهذا التاريخ.
                </div>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <div class="match-card">
                        
                        <div class="team">
                            <img src="assest/<?= htmlspecialchars($match['team_one_image']) ?>" alt="<?= htmlspecialchars($match['team_one_name']) ?>">
                            <span class="team-name"><?= htmlspecialchars($match['team_one_name']) ?></span>
                        </div>

                        <div class="match-info">
                            <span class="match-time">
                                <?= date("A g:i", strtotime($match['match_time'])) ?>
                            </span>

                            <?php 
                            $start_iso = $match['match_date'] . 'T' . $match['match_time'];
                            $end_iso = date('Y-m-d\TH:i:s', strtotime('+2 hours', strtotime($start_iso)));
                            ?>
                            <span class="match-status live-status" 
                                  data-start="<?= $start_iso ?>" 
                                  data-end="<?= $end_iso ?>">
                                    جاري التحميل...
                            </span>
                        </div>

                        <div class="team">
                            <img src="assest/<?= htmlspecialchars($match['team_two_image']) ?>" alt="<?= htmlspecialchars($match['team_two_name']) ?>">
                            <span class="team-name"><?= htmlspecialchars($match['team_two_name']) ?></span>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-right"></i> الرجوع للرئيسية</a>

    </div>

    <script>
    function updateMatchStatuses() {
        const now = new Date();

        document.querySelectorAll('.live-status').forEach(statusBadge => {
         
            const startTime = new Date(statusBadge.getAttribute('data-start'));
            const endTime = new Date(statusBadge.getAttribute('data-end'));

            if (now < startTime) {
                
                statusBadge.textContent = "لم تبدأ بعد";
                statusBadge.style.backgroundColor = "#111";
                statusBadge.style.animation = "none";
            } else if (now >= startTime && now <= endTime) {
              
                statusBadge.textContent = "مباشر 🔴";
                statusBadge.style.backgroundColor = "#d9534f";
                statusBadge.style.animation = "pulse 1.5s infinite";
            } else {
                
                statusBadge.textContent = "انتهت";
                statusBadge.style.backgroundColor = "#5cb85c";
                statusBadge.style.animation = "none";
            }
        });
    }

  
    updateMatchStatuses();

    setInterval(updateMatchStatuses, 1000);
    </script>

</body>
</html>