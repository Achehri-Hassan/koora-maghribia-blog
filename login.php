<?php


session_start();
require_once "user.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

  $email = $_POST['email'];
  $pass  = $_POST['password'];


  if (!empty($email) && !empty($pass)) {
    $userModel = new User();
    $user = $userModel->login($email);


    if ($user && password_verify($pass, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role']    = $user['role'];


      header("Location: " . ($user['role'] === 'admin' ? "dashboard.php" : "index.php"));
      exit();
    } else {
      $error = "الإيميل أو كلمة المرور غلط!";
    }
  } else {
    $error = "عمر الخانات كاملين!";
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


  <div class="form_container">

    <div class="login-card">

      <div class="form-side">
        <div class="form-header">
          <i class="fa-solid fa-trophy"></i>
          <h1>تسجيل الدخول</h1>
          <div class="line"></div>
          <?php if ($error): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
          <?php endif; ?>
        </div>

        <form method="post" action="">
          <div class="input-group">
            <label>البريد الإلكتروني</label>
            <div class="input-box">
              <input type="email" placeholder="email@example.com" name="email" />
              <i class="fa-regular fa-envelope"></i>
            </div>
          </div>

          <div class="input-group">
            <label>كلمة المرور</label>
            <div class="input-box">
              <input type="password" placeholder="••••••••" name="password" />
              <i class="fa-solid fa-lock"></i>
            </div>
          </div>

          <button type="submit" class="btn-login" name="login">تسجيل الدخول</button>

          <div class="divider">أو</div>

          <a href="register.html">
            <button type="button" class="btn-reg">
              <i class="fa-solid fa-user-plus"></i> إنشاء حساب جديد
            </button></a>
        </form>
      </div>


      <div class="image-side">
        <img src="assest/hakim.png" alt="Player" />
      </div>
    </div>
  </div>
</body>

</html>