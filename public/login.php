<?php


session_start();
require_once "admin.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
   
  $email = $_POST['email'];
  $pass  = $_POST['password'];
  
 
  if (!empty($email) && !empty($pass)) {
  

    $user = login($email);
     

    if ($user &&   $pass === $user['password']) {

       $_SESSION['user_id'] = $user['id'];
      header("Location: dashboard.php");
      exit();

    } else {
      $error = "الإيميل أو كلمة المرور غلط!";
    }
  } else {
    $error = "عمر الخانات كامل";
  }
}

?>




<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>تسجيل الدخول - البطولة</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <link rel="stylesheet" href="css/components/Form.css" />
</head>

<body>



<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>تسجيل الدخول</title>

  <link rel="stylesheet" href="css/components/Form.css">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <div class="login-container">

    <form method="post" class="login-card">

      <div class="form-header">
        <i class="fa-solid fa-trophy"></i>
        <h1>تسجيل الدخول</h1>

        <?php if ($error): ?>
          <p class="error"><?= $error ?></p>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <label>البريد الإلكتروني</label>

        <div class="input-box">
          <input type="email" name="email" placeholder="email@example.com" required>
          <i class="fa-regular fa-envelope"></i>
        </div>
      </div>

      <div class="input-group">
        <label>كلمة المرور</label>

        <div class="input-box">
          <input type="password" name="password" placeholder="••••••••" required>
          <i class="fa-solid fa-lock"></i>
        </div>
      </div>

      <button type="submit" name="login" class="btn-login">
        تسجيل الدخول
      </button>

      <a href="index.php" class="btn-guest">
        دخول كزائر
      </a>

    </form>

  </div>

</body>
</html>
  
</body>

</html>