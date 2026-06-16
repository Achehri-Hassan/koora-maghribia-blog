<?php


session_start();
require_once "../src/auth/admin.php";


// declare variable to store error
$error_login = "";


// handel form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

  //  create variable to add name input
  $email = $_POST['email'];
  $pass  = $_POST['password'];

  // condition is not empty  name variable
  if (!empty($email) && !empty($pass)) {

    // create variable  to add function login
    $admin = login($email);

    //  condition to verification 
    if ($admin && password_verify($pass, $admin['password'])) {

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
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link rel="stylesheet" href="../assest/css/components/login.css">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
</head>

<body>

  <main>
    <section class="contact-container">

      <div class="contact__image">
        <img src="../assest/banner/download.jpg" alt="Login" />

      </div>

      <div class="contact__form-section">

        <div class="form__header">
          <i class="fa-solid fa-trophy"></i>
          <h1>تسجيل الدخول</h1>
          <?php if ($error_login): ?>
            <p class="error"><?= htmlspecialchars($error_login) ?></p>
          <?php endif; ?>
        </div>

        <!-- LOGIN FORM -->
        <form class="form__body" method="post" action="login.php" style="margin-top: 70px;">

          <div class="form__group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required />
          </div>

          <div class="form__group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required />
          </div>

          <button type="submit" class="form__button" name="login">
            Login
          </button>

        </form>

        <!-- link visitor -->
        <a href="index.php" class="btn-visitor">
          دخول كزائر
        </a>

      </div>
    </section>
  </main>

</body>

</html>