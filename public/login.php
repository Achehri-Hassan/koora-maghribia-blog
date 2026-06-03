<?php


session_start();
require_once "../src/auth/admin.php";

$error_login = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
   
  $email = $_POST['email'];
  $pass  = $_POST['password'];
  
 
  if (!empty($email) && !empty($pass)) {
  
    $admin = login($email);
     
    if($admin && password_verify($pass, $admin['password'])) {

      $_SESSION['is_admin'] = $admin['id'];
      header("Location: dashboard.php");
      exit();
      
    } else {
      $error_login = "الإيميل أو كلمة المرور غلط!";
    }
  } else {
    $error_login = "عمر الخانات كامل";
  }
}

?>


<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- title page -->
  <title>تسجيل الدخول - البطولة</title>
   
  <!-- link font family -->
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
    rel="stylesheet" />
  <!-- link icon -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

   <!-- link style css -->
  <link rel="stylesheet" href="../assest/css/components/login.css"/>
</head>

<body>

  <div class="login-container">
     
   <!-- form -->
    <form method="post" class="login-card">
        <!-- header form -->
      <div class="form-header">
        <i class="fa-solid fa-trophy"></i>
        <h1>تسجيل الدخول</h1>

        <?php if ($error_login): ?>
          <p class="error"><?= $error_login ?></p>
        <?php endif; ?>
      </div>
       
       <!-- input group label and input -->
      <div class="input-group">
        <label>البريد الإلكتروني</label>

        <div class="input-box">
          <input type="email" name="email" placeholder="email@example.com" required>
          <i class="fa-regular fa-envelope"></i>
        </div>
      </div>
       
      <!-- input group label and input -->
      <div class="input-group">
        <label>كلمة المرور</label>

        <div class="input-box">
          <input type="password" name="password" placeholder="••••••••" required>
          <i class="fa-solid fa-lock"></i>
        </div>
      </div>
       
      <!-- button login -->
      <button type="submit" name="login" class="btn-login">
        تسجيل الدخول
      </button>
       
      <!-- link visitor -->
      <a href="index.php" class="btn-visitor">
        دخول كزائر
      </a>

    </form>

  </div>

</body>

</html>