<?php
// ملف يحتوي على جميع الدوالي الخاصة بالمباريات (Matches & Goals)

/**
 * 1. دالة إنشاء مباراة جديدة بالسكور النهائي ديالها
 */


require_once "connection.php";

function createMatch($team_one_name, $team_one_image, $team_two_name, $team_two_image, $stadium, $match_date, $match_time, $team_one_score, $team_two_score) {
    $conn = getConnection();
    try {
        $sql = "INSERT INTO matches_table (team_one_name, team_one_image, team_two_name, team_two_image, stadium, match_date, match_time, team_one_score, team_two_score) 
                VALUES (:team_one_name, :team_one_image, :team_two_name, :team_two_image, :stadium, :match_date, :match_time, :team_one_score, :team_two_score)";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':team_one_name'  => $team_one_name,
            ':team_one_image' => $team_one_image,
            ':team_two_name'  => $team_two_name,
            ':team_two_image' => $team_two_image,
            ':stadium'        => $stadium,
            ':match_date'     => $match_date,
            ':match_time'     => $match_time,
            ':team_one_score' => $team_one_score,
            ':team_two_score' => $team_two_score
        ]);

        if ($result) {
            return $conn->lastInsertId(); // كنرجعو الـ ID باش نخدمو بيه فـ جدول الأهداف
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * 2. دالة تسجيل هدف مستقل (دقيقة الهدف ورقم الفريق)
 */
function createMatchGoal($match_id, $team_number, $minute) {
    $conn = getConnection();
    try {
        $sql = "INSERT INTO match_goals (match_id, team_number, minute) 
                VALUES (:match_id, :team_number, :minute)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':match_id'    => $match_id,
            ':team_number' => $team_number,
            ':minute'      => $minute
        ]);
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * 3. دالة جلب جميع مباريات تاريخ معين مع الأهداف المرافقة لها
 */
function readAllMatchesWithGoals($target_date) {
    $conn = getConnection();
    try {
        // جلب المباريات المجدولة ف هاد التاريخ
        $sql = "SELECT * FROM matches_table WHERE match_date = :target_date ORDER BY match_time ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':target_date' => $target_date]);
        $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // لكل مباراة، كنجيبو لستة ديال الأهداف لي تسيملات ومترتبة من الدقيقة الأولى للأخيرة
        foreach ($matches as $key => $match) {
            $goal_sql = "SELECT team_number, minute FROM match_goals WHERE match_id = :match_id ORDER BY minute ASC";
            $goal_stmt = $conn->prepare($goal_sql);
            $goal_stmt->execute([':match_id' => $match['id']]);
            
            // كنحطو الأهداف داخل مصفوفة وسط المباراة نفسها لسهولة الاستخراج فـ الـ JavaScript
            $matches[$key]['goals'] = $goal_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $matches;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * 4. دالة حذف مباراة (إضافية لتنظيف اللوحة عند الحاجة)
 */
function deleteMatch($match_id) {
    $conn = getConnection();
    try {
        // خاصنا نحذفو الأهداف أولا حيت كاين Foreign Key غالباً
        $sqlGoals = "DELETE FROM match_goals WHERE match_id = :match_id";
        $stmtGoals = $conn->prepare($sqlGoals);
        $stmtGoals->execute([':match_id' => $match_id]);

        // عاد نحذفو الماتش الرئيسي
        $sqlMatch = "DELETE FROM matches_table WHERE id = :match_id";
        $stmtMatch = $conn->prepare($sqlMatch);
        return $stmtMatch->execute([':match_id' => $match_id]);
    } catch (PDOException $e) {
        return false;
    }
}
?>