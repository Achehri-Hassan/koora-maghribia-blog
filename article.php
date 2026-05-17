<?php

require_once "connection.php";


// create class
class Article
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }


    // function to reade all article 
    public function readAll()
    {
        $sql = "SELECT * FROM articles ORDER BY id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // function category
    public function getByCategory($category)
    {
        $sql = "SELECT * FROM articles WHERE category = :category ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // function to git  by id 
    public function getById($id)
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //   function to create article 
    public function createArticle($title, $content, $image, $category , $admin_id)
    {
        $sql = "INSERT INTO articles (title, content, image , category , user_id) 
                VALUES (:title, :content, :image,   :category , :user_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            "title" => $title,
            "content" => $content,
            "image" => $image,
            "category" => $category,
            "user_id" => $admin_id
        ]);
    }


    // function update article 
    public function Update($id, $title, $content, $image ,  $category )
    {
        $sql = "UPDATE articles SET title=:title, content=:content, category=:category, 
                image=:image WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            "id" => $id,
            "title" => $title,
            "content" => $content,
            "category" => $category,
            "image" => $image,
        ]);
    }


    // function delete article
    public function delete($id)
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
