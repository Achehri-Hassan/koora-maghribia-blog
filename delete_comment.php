<?php

require_once "comments.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];




deleteComment($id);

header("Location: dashboard.php");
exit();


?>