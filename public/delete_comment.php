<?php

session_start();
require_once "../src/models/comments.php";



if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();

}else{ 

    $id = $_GET['id'];
    deleteComment($id);
    header("Location: dashboard.php");
    exit();
} 



?>




