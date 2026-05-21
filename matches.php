<?php

require_once "connection.php";

function readAllMatches()
{
    $conn = getConnection();
    $sql = "SELECT * FROM matches_table ORDER BY match_date ASC, match_time ASC";
    $stmt = $conn->query($sql);
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