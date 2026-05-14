<?php

require_once "article.php";

/* =========================
   VALIDATION
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

$article = new Article();

/* =========================
   DELETE COMMENT
========================= */

$article->deleteComment($id);

/* =========================
   REDIRECT BACK
========================= */

header("Location: dashboard.php");
exit();