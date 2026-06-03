<?php

require_once "../src/config/connection.php";



function getAllTeams() {
    $pdo = getConnection();
    $sql = "SELECT * FROM teams ORDER BY team_name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMatch($team_one_id, $team_two_id, $stadium, $match_date, $match_time, $youtube_url) {
    $pdo = getConnection();
    
    $sql = "INSERT INTO matches_table (team_one_id, team_two_id, stadium, match_date, match_time, youtube_url) 
            VALUES (:team_one_id, :team_two_id, :stadium, :match_date, :match_time, :youtube_url)";
            
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
        'team_one_id'  => $team_one_id,
        'team_two_id'  => $team_two_id,
        'stadium'      => $stadium,
        'match_date'   => $match_date,
        'match_time'   => $match_time,
        'youtube_url'  => $youtube_url
    ]);
}


function readAllMatches($date) {
    $pdo = getConnection();
    
    $sql = "SELECT 
                m.id, 
                m.stadium, 
                m.match_date, 
                m.match_time, 
                m.youtube_url,
                t1.team_name AS team_one_name, 
                t1.team_image AS team_one_image,
                t2.team_name AS team_two_name, 
                t2.team_image AS team_two_image
            FROM matches_table m
            INNER JOIN teams t1 ON m.team_one_id = t1.id
            INNER JOIN teams t2 ON m.team_two_id = t2.id
            WHERE m.match_date = :match_date 
            ORDER BY m.match_time DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'match_date' => $date
    ]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function readMatchById($id) {
    $pdo = getConnection();
    
    $sql = "SELECT m.id, m.stadium, m.match_date, m.match_time, 
                m.youtube_url,
                t1.team_name AS team_one_name, 
                t1.team_image AS team_one_image,
                t2.team_name AS team_two_name, 
                t2.team_image AS team_two_image
            FROM matches_table m
            INNER JOIN teams t1 ON m.team_one_id = t1.id
            INNER JOIN teams t2 ON m.team_two_id = t2.id
            WHERE m.id = :id 
            LIMIT 1";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>