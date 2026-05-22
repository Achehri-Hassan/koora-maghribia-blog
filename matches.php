<?php

require_once "connection.php";

// دالة جلب المباريات المفلترة بالتاريخ متوافقة 100% مع PDO وحاسوبك
function readAllMatches($date)
{
    // جلب الاتصال من الدالة الخاصة بك
    $conn = getConnection();
    
    // الاستعلام باستعمال المعايير الآمنة (Placeholder :date) اسم الجدول matches_table كما في الدالة الأخرى
    $sql = "SELECT * FROM matches_table WHERE match_date = :match_date ORDER BY match_time ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "match_date" => $date
    ]);
    
    // إرجاع النتائج على شكل مصفوفة مرتبة
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMatch($team_one_name, $team_one_image, $team_two_name, $team_two_image, $stadium, $match_date, $match_time)
{
    $conn = getConnection();

    $sql = "INSERT INTO matches_table (team_one_name, team_one_image, team_two_name, team_two_image, stadium, match_date, match_time, team_one_score, team_two_score)
            VALUES (:team_one_name, :team_one_image, :team_two_name, :team_two_image, :stadium, :match_date, :match_time, 0, 0)";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        "team_one_name"  => $team_one_name,
        "team_one_image" => $team_one_image,
        "team_two_name"  => $team_two_name,
        "team_two_image" => $team_two_image,
        "stadium"        => $stadium,
        "match_date"     => $match_date,
        "match_time"     => $match_time
    ]);
}
?>