<?php

session_start();
require_once "user.php";
$error = null;
$userModel = new User();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';


  if (empty($username) || empty($email) || empty($password)) {
    $error = "عفاك عمر كاع الخانات";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "البريد الإلكتروني ماشي صحيح";
  } else {


    $result = $userModel->create($username, $email, $password);

    if ($result) {
      $_SESSION["username"] = $username;
      $_SESSION["email"] = $email;
      header("Location: index.php");
      exit();
    } else {
      $error = "هاد البريد الإلكتروني مستعمل ديجا";
    }
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
          <h1>إنشاء حساب جديد</h1>
          <div class="line"></div>
          <?php if ($error): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
          <?php endif; ?>
        </div>

        <form method="post" action="">
          <div class="input-group">
            <label>الإسم الكامل</label>
            <div class="input-box">
              <input type="text" placeholder="أدخل اسمك الكامل" name="username" />
              <i class="fa-solid fa-user"></i>
            </div>
          </div>
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

          <button type="submit" class="btn-login">تسجيل الدخول</button>
        </form>
      </div>

      <div class="image-side">
        <img src="assest/hakim.png" alt="Player" />
      </div>
    </div>


  </div>
</body>

</html>