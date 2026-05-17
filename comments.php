<?php

require_once "connection.php";




function addComment($article_id, $username, $comment)
{
    $conn = getConnection();

    $sql = "INSERT INTO comments (article_id, username, comment, created_at)
            VALUES (:article_id, :username, :comment, NOW())";

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "article_id" => $article_id,
        "username" => $username,
        "comment" => $comment
    ]);
}



function getCommentsByArticle($article_id)
{
    $conn = getConnection();

    $sql = "SELECT * FROM comments 
            WHERE article_id = :article_id 
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "article_id" => $article_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getCommentsCount($article_id)
{
    $conn = getConnection();

    $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "article_id" => $article_id
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'] ?? 0;
}



function deleteComment($id)
{
    $conn = getConnection();

    $sql = "DELETE FROM comments WHERE id = :id";
    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "id" => $id
    ]);
}
