<?php

require_once "../src/config/connection.php";



    // Login user (IMPORTANT)
    function login($email)
    {
         
       $conn = getConnection();

        $sql = "SELECT * FROM admin WHERE email = :email ";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            "email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   

