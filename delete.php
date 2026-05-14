<?php

require_once "article.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $article = new Article();
    $article->delete($id);

    header("Location: index.php");
    exit;
}

