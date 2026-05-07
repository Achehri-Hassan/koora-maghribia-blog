<?php

require_once "article.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $article = new Article();
    $article->deleted($id);

    header("Location: index.php");
    exit;
}

