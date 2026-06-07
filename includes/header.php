

<?php 
 
$isAdmin = isset($_SESSION['is_admin']);

?>



<!-- HEADER -->
<header>

    <!-- Right Side  nav-->
    <div class="right-side">
        <a href="index.php">الرئيسية</a>
        <a href="#">من نحن</a>
        <a href="#">اتصل بنا</a>
    </div>

    <!-- admin -->
    <?php if ($isAdmin): ?>
        <div class="admin-actions">

            <a href="dashboard.php"> لوحة التحكم</a>
            <a href="logout.php"> تسجيل الخروج </a>
        </div>
    <?php endif; ?>

</header>