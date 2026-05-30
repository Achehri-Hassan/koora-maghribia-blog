<?php



session_start();

require_once "../src/models/article.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}else{
    $id = $_GET['id'];
   deleteArticle($id);
    header("Location: dashboard.php");
    exit;
}



?>