<!doctype html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>تسجيل الدخول - البطولة</title>

    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />

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
          </div>

          <form>
            <div class="input-group">
              <label>الإسم الكامل</label>
              <div class="input-box">
                <input type="text" placeholder="أدخل اسمك الكامل" />
                <i class="fa-solid fa-user"></i>
              </div>
            </div>
            <div class="input-group">
              <label>البريد الإلكتروني</label>
              <div class="input-box">
                <input type="email" placeholder="email@example.com" />
                <i class="fa-regular fa-envelope"></i>
              </div>
            </div>

            <div class="input-group">
              <label>كلمة المرور</label>
              <div class="input-box">
                <input type="password" placeholder="••••••••" />
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
