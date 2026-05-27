<?php

require_once "../src/models/article.php";


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}else{
    $id = $_GET['id'];
   deleteArticle($id);
    header("Location: dashboard.php");
    exit;
}



?>