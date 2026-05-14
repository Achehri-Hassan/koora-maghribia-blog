<?php

require_once "article.php";

if (isset($_GET['id'])) {

    $article = new Article();

    $article->approveComment($_GET['id']);

    header("Location: dashboard.php");
    exit();
}