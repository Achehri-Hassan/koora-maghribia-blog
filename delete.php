<?php

require_once "article.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

   
  deleteArticle($id);

    header("Location: dashboard.php");
    exit;
}

