<?php


require_once "src/config/connection.php";

function createMatch($team_one_name, $team_one_image, $team_two_name, $team_two_image, $stadium, $match_date, $match_time, $youtube_url) {
    $pdo = getConnection();
    
    $sql = "INSERT INTO matches_table (team_one_name, team_one_image, team_two_name, team_two_image, stadium, match_date, match_time, youtube_url) 
            VALUES (:team_one_name, :team_one_image, :team_two_name, :team_two_image, :stadium, :match_date, :match_time, :youtube_url)";
            
    $stmt = $pdo->prepare($sql);
    
     return $stmt->execute([
        'team_one_name'  => $team_one_name,
        'team_one_image' => $team_one_image,
        'team_two_name'  => $team_two_name,
        'team_two_image' => $team_two_image,
        'stadium'        => $stadium,
        'match_date'     => $match_date,
        'match_time'     => $match_time,
        'youtube_url'    => $youtube_url
    ]);
    
    
}


function readAllMatches($date) {
    $pdo = getConnection();
    
    $sql = "SELECT * FROM matches_table WHERE match_date = :match_date ORDER BY match_time DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        [
        'match_date' => $date
        ]
        );
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function readMatchById($id) {
    $pdo = getConnection();
    
    $sql = "SELECT * FROM matches_table WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>