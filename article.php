


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

    public   function readAll()
    {

        $sql = "SELECT * FROM articles";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createArticle($title , $content, $image, $author ,$category)
    {


        $sql = "INSERT into articles (title , content , image  , author , category) VALUES (:title , :content , :image , :author  , :category)";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([

            "title" => $title,
            "content" => $content,
            "image" => $image,
            "author" => $author,
            "category" => $category
        ]);
    }

    public function Update($id, $title, $content, $image, $author ,$category)
    {
        $sql = "UPDATE articles set title=:title , content=:content , category=:category , image=:image Where id=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([

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

        $sql = "DELETE FROM articles where id=:id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([

            "id" => $id
        ]);
    }

    // get by id 
    public function getById($id)
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


?>