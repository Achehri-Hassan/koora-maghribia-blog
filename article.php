<?php

require_once "connection.php";

class Article
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
     
    
    

    public function readAll()
    {
        $sql = "SELECT * FROM articles ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createArticle($title, $content, $image, $author, $category)
    {
        $sql = "INSERT INTO articles (title, content, image, author, category) 
                VALUES (:title, :content, :image, :author, :category)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            "title" => $title,
            "content" => $content,
            "image" => $image,
            "author" => $author,
            "category" => $category
        ]);
    }

    public function Update($id, $title, $content, $image, $author, $category)
    {
        $sql = "UPDATE articles SET title=:title, content=:content, category=:category, 
                image=:image, author=:author WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            "id" => $id,
            "title" => $title,
            "content" => $content,
            "category" => $category,
            "image" => $image,
            "author" => $author
        ]);
    }

    public function deleted($id)
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRelated($currentId)
    {
        $sql = "SELECT * FROM articles WHERE id != :id ORDER BY id DESC LIMIT 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $currentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readByCategory($category)
    {
        $sql = "SELECT * FROM articles WHERE category = :category ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   

    public function addComment($article_id, $username, $comment, $status = 'pending')
    {
        $sql = "INSERT INTO comments (article_id, username, comment, status)
            VALUES (:article_id, :username, :comment, :status)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "article_id" => $article_id,
            "username" => $username,
            "comment" => $comment,
            "status" => $status
        ]);
    }
    // 
    public function getCommentsByArticle($article_id)
    {
        $sql = "SELECT * FROM comments
                WHERE article_id = :article_id AND status = 'approved'
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    public function getCommentsByArticleAdmin($article_id)
    {
        $sql = "SELECT * FROM comments 
                WHERE article_id = :article_id 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getLatestCommentsByArticle($article_id, $limit = 5)
    {
        $sql = "SELECT * FROM comments 
                WHERE article_id = :article_id AND status = 'approved'
                ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':article_id', (int)$article_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveComment($id)
    {
        $sql = "UPDATE comments SET status = 'approved' WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }




    public function getPendingComments()
    {
        // JOIN articles
        $sql = "SELECT comments.*, articles.title as article_title 
            FROM comments 
            JOIN articles ON comments.article_id = articles.id 
            WHERE comments.status = 'pending' 
            ORDER BY comments.created_at DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }




    public function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }

    public function getCommentsCount($article_id)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
