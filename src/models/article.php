<?php

require_once "../src/config/connection.php";



// read all article
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



function createArticle($title, $content, $image, $category, $admin_id)
{

    $conn = getConnection();
    $sql = "INSERT INTO articles (title, content, image, category, admin_id)
            VALUES (:title, :content, :image, :category, :admin_id)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        "title" => $title,
        "content" => $content,
        "image" => $image,
        "category" => $category,
        "admin_id" => $admin_id
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




// new function
function getRelatedArticles($category, $current_id, $limit = 5)
{
    $conn = getConnection();
    

    $sql = "SELECT * FROM articles 
            WHERE category = :category AND id != :current_id 
            ORDER BY id DESC 
            LIMIT :limit";
            
    $stmt = $conn->prepare($sql);
    
   
    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    $stmt->bindValue(':current_id', $current_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTotalArticlesCount() {
    $conn = getConnection();
    $sql = "SELECT COUNT(*) AS total FROM articles";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}


function getTotalArticlesCountByCategory($category) {
    $conn = getConnection();
    $sql = "SELECT COUNT(*) AS total FROM articles WHERE category = :category";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        "category" => $category
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}


function readAllArticlesWithPagination($limit, $offset) {
    $conn = getConnection();
    
  
    $sql = "SELECT * FROM articles ORDER BY id DESC LIMIT :limit OFFSET :offset"; 
    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArticlesByCategoryWithPagination($category, $limit, $offset) {
    $conn = getConnection();
    
    $sql = "SELECT * FROM articles WHERE category = :category ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}