<?php



require_once "../src/config/connection.php";


// get all teams 
function getAllTeams() {
    $pdo = getConnection();
    $sql = "SELECT * FROM teams ORDER BY team_name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// get all Stadiums
function getAllStadiums() {
    $pdo = getConnection();
    $sql = "SELECT * FROM stadiums ORDER BY stadium_name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// get all Commentators
function getAllCommentators() {
    $pdo = getConnection();
    $sql = "SELECT * FROM commentators ORDER BY commentator_name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// function to create match 
function createMatch($team_one_id, $team_two_id, $stadium_id, $commentator_id, $match_date, $match_time, $youtube_url) {
    $pdo = getConnection();
    
    $sql = "INSERT INTO matches_table (team_one_id, team_two_id, stadium_id, commentator_id, match_date, match_time, youtube_url) 
            VALUES (:team_one_id, :team_two_id, :stadium_id, :commentator_id, :match_date, :match_time, :youtube_url)";
            
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
        'team_one_id'    => $team_one_id,
        'team_two_id'    => $team_two_id,
        'stadium_id'     => $stadium_id,
        'commentator_id' => $commentator_id,
        'match_date'     => $match_date,
        'match_time'     => $match_time,
        'youtube_url'    => $youtube_url
    ]);
}


// function to reade all match date
function readAllMatches($date) {
    $pdo = getConnection();
    
    $sql = "SELECT 
                m.id, 
                m.match_date, 
                m.match_time, 
                m.youtube_url,
                s.stadium_name,
                c.commentator_name,
                t1.team_name AS team_one_name, 
                t1.team_image AS team_one_image,
                t2.team_name AS team_two_name, 
                t2.team_image AS team_two_image
            FROM matches_table m
            INNER JOIN teams t1 ON m.team_one_id = t1.id
            INNER JOIN teams t2 ON m.team_two_id = t2.id
            INNER JOIN stadiums s ON m.stadium_id = s.id
            INNER JOIN commentators c ON m.commentator_id = c.id
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
    
    $sql = "SELECT m.id, m.match_date, m.match_time, m.youtube_url,
                s.stadium_name,
                c.commentator_name,
                t1.team_name AS team_one_name, 
                t1.team_image AS team_one_image,
                t2.team_name AS team_two_name, 
                t2.team_image AS team_two_image
            FROM matches_table m
            INNER JOIN teams t1 ON m.team_one_id = t1.id
            INNER JOIN teams t2 ON m.team_two_id = t2.id
            INNER JOIN stadiums s ON m.stadium_id = s.id
            INNER JOIN commentators c ON m.commentator_id = c.id
            WHERE m.id = :id 
            LIMIT 1";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//  function to delete more > 3 matches
function deleteOldMatches($daysCount = 3) {

    $pdo = getConnection();
    $targetDate = date('Y-m-d', strtotime("-$daysCount days"));
    $sql = "DELETE FROM matches_table WHERE match_date < :target_date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':target_date' => $targetDate]);
        
    
}


?>