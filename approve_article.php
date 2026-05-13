<?php
session_start();
require_once "article.php";

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
    $article = new Article();
    $article->approve($_GET['id']);
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: index.php");
}
?>