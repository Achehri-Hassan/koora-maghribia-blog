


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


    public function createArticle($title, $content, $image, $author, $category)
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

    public function Update($id, $title, $content, $image, $author, $category)
    {
        $sql = "UPDATE articles set title=:title , content=:content , category=:category  , image=:image , author=:author Where id=:id";

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


    public function getRelated($currentId)
    {
        $sql = "SELECT * FROM articles WHERE id != :id ORDER BY id DESC LIMIT 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(

            [
                "id" => $currentId
            ]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // F-west l-Class Article
    public function readByCategory($category)
    {
        $sql = "SELECT * FROM articles WHERE category = :category ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function searchArticles($keyword)
{
    $sql = "SELECT * FROM articles
            WHERE title LIKE :keyword
            OR content LIKE :keyword
            ORDER BY id DESC";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "keyword" => "%$keyword%"
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


  public function addComment($article_id, $username, $comment)
{
    $sql = "INSERT INTO comments
    (article_id, username, comment, status)

    VALUES
    (:article_id, :username, :comment, 'pending')";

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
            AND status = 'approved'
            ORDER BY created_at DESC";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "article_id" => $article_id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function approveComment($id)
{
    $sql = "UPDATE comments
            SET status = 'approved'
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        "id" => $id
    ]);
}


public function getPendingComments()
{
    $sql = "SELECT * FROM comments
            WHERE status = 'pending'
            ORDER BY created_at DESC";

    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}



public function deleteComment($id)
{
    $sql = "DELETE FROM comments WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        "id" => $id
    ]);
}

    

    public function getCommentsCount($article_id)
    {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "article_id" => $article_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


    public function getLatestComments($limit = 5)
    {
        $sql = "SELECT comments.*, articles.title
            FROM comments
            JOIN articles ON comments.article_id = articles.id
            ORDER BY comments.created_at DESC
            LIMIT :limit";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}





?>