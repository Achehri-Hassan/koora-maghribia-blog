<?php

require_once "connection.php";




function readAllArticles()
{
    $conn = getConnection();

    $sql = "SELECT * FROM articles ORDER BY id DESC";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}



function getArticlesByCategory($category)
{
    $conn = getConnection();

    $sql = "SELECT * FROM articles WHERE category = :category ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "category" => $category
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getArticleById($id)
{

    $conn = getConnection();
    $sql = "SELECT * FROM articles WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "id" => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}



function createArticle($title, $content, $image, $category, $user_id)
{

    $conn = getConnection();
    $sql = "INSERT INTO articles (title, content, image, category, user_id)
            VALUES (:title, :content, :image, :category, :user_id)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        "title" => $title,
        "content" => $content,
        "image" => $image,
        "category" => $category,
        "user_id" => $user_id
    ]);
}



function updateArticle($id, $title, $content, $image, $category)
{
    $conn = getConnection();

    $sql = "UPDATE articles 
            SET title = :title,
                content = :content,
                image = :image,
                category = :category
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "id" => $id,
        "title" => $title,
        "content" => $content,
        "image" => $image,
        "category" => $category
    ]);
}



function deleteArticle($id)
{
    $conn = getConnection();

    $sql = "DELETE FROM articles WHERE id = :id";
    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "id" => $id
    ]);
}
