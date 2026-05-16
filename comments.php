


<?php

  require_once "connection.php";

// class comments
class Comments
{


    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    
    // function to add comments 
    public function addComment($article_id, $username, $comment)
    {
        $sql = "INSERT INTO comments (article_id, username, comment, created_at)
                VALUES (:article_id, :username, :comment, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            "article_id" => $article_id,
            "username" => $username,
            "comment" => $comment
        ]);
    }

    
    public function getCommentsByArticle($article_id)
    {
        $sql = "SELECT * FROM comments 
                WHERE article_id = :article_id 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    // function to count comments
    public function getCommentsCount($article_id)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // function to remove any comments
    public function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }
}








?>