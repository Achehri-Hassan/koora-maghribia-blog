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

    /* =================================        
       إدارة المقالات (Articles)
    ================================= */

    // جلب جميع المقالات
    public function readAll()
    {
        $sql = "SELECT * FROM articles ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // جلب مقال واحد بواسطة ID
    public function getById($id)
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // إضافة مقال جديد
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

    // تحديث مقال
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

    // حذف مقال
    public function delete($id)
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // جلب مقالات مشابهة (عشوائية أو من نفس القسم)
    public function getRelated($exclude_id)
    {
        $sql = "SELECT * FROM articles WHERE id != :id ORDER BY RAND() LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $exclude_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =================================        
       إدارة التعليقات (Comments)
    ================================= */

    // إضافة تعليق
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

    // جلب تعليقات مقال معين (التي تستخدمها في dashboard و adetails)
    public function getCommentsByArticle($article_id)
    {
        $sql = "SELECT * FROM comments 
                WHERE article_id = :article_id 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // حساب عدد التعليقات لمقال معين (لعرضها في الجدول)
    public function getCommentsCount($article_id)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["article_id" => $article_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // حذف تعليق
    public function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }
}