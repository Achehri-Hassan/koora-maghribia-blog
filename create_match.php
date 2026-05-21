
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>إضافة مباراة جديدة</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/create.css" /> 
</head>
<body>

  <div class="form_container">
    <form method="post" enctype="multipart/form-data">
      <h1>إضافة مباراة جديدة ⚽</h1>

      <div class="row">
        <div class="input-group">
          <label>اسم الفريق الأول (المستضيف)</label>
          <input type="text" name="team_one_name" placeholder="مثال: الوداد الرياضي" required>
        </div>
        <div class="input-group">
          <label>شعار الفريق الأول</label>
          <input type="file" name="team_one_image" accept="image/*" required>
        </div>
      </div>

      <div class="row">
        <div class="input-group">
          <label>اسم الفريق الثاني (الضيف)</label>
          <input type="text" name="team_two_name" placeholder="مثال: الرجاء الرياضي" required>
        </div>
        <div class="input-group">
          <label>شعار الفريق الثاني</label>
          <input type="file" name="team_two_image" accept="image/*" required>
        </div>
      </div>

      <div class="input-group">
        <label>الملعب</label>
        <input type="text" name="stadium" placeholder="مثال: مركب محمد الخامس" required>
      </div>

      <div class="row">
        <div class="input-group">
          <label>تاريخ المباراة</label>
          <input type="date" name="match_date" required>
        </div>
        <div class="input-group">
          <label>توقيت المباراة</label>
          <input type="time" name="match_time" required>
        </div>
      </div>

      <div class="buttons">
        <button type="submit" class="btn-submit" name="add_match">جدولة المباراة الآن</button>
        <a href="dashboard.php" class="btn-back">الرجوع للوحة التحكم</a>
      </div>
    </form>
  </div>

</body>
</html>