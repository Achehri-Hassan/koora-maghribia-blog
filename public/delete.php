<?php

require_once "../src/models/article.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

   
  deleteArticle($id);

    header("Location: dashboard.php");
    exit;
}

