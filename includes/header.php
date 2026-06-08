

<?php
$isAdmin = isset($_SESSION['is_admin']);
?>

<header>
    <div class="header-inner">

        <a href="index.php" class="header-logo">كورة مغربية</a>
        

        <button class="nav-toggle">
            <i class="fa-solid fa-bars"></i>
            <i class="fa-solid fa-xmark"></i>
        </button>

        <nav class="nav-menu">
            <div class="nav-links">
                <a href="index.php">الرئيسية</a>
                <a href="#">من نحن</a>
                <a href="#">اتصل بنا</a>
            </div>

            <?php if ($isAdmin): ?>
            <div class="admin-actions">
                <a href="dashboard.php">لوحة التحكم</a>
                <a href="logout.php">تسجيل الخروج</a>
            </div>
            <?php endif; ?>
        </nav>

    </div>
</header>

<!-- script js -->
<script src="../assest/js/header.js"></script>