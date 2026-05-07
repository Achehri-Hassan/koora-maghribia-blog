<?php
session_start();
require_once "article.php";

// I'tibarât dyal l-himaya (Security)
// if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
//     header("Location: index.php");
//     exit();
// }

$article = new Article();
$articles = $article->readAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | إدارة المقالات</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --info: #3498db;
            --bg: #f4f7f6;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-add {
            background: var(--success);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s transform ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        /* Table Design */
        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: right;
        }

        thead {
            background-color: #f8f9fa;
        }

        th {
            padding: 20px;
            color: #7f8c8d;
            font-weight: 600;
            border-bottom: 2px solid #edf2f7;
        }

        td {
            padding: 18px 20px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #fdfdfd;
        }

        .article-id {
            color: #95a5a6;
            font-weight: bold;
            font-family: sans-serif;
        }

        .article-title {
            font-weight: 600;
            color: var(--primary);
            font-size: 16px;
        }

        /* Actions Buttons */
        .actions-wrap {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
        }

        .edit-btn {
            background: #ebf5ff;
            color: var(--info);
        }

        .edit-btn:hover {
            background: var(--info);
            color: white;
        }

        .delete-btn {
            background: #fff5f5;
            color: var(--danger);
        }

        .delete-btn:hover {
            background: var(--danger);
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .actions-wrap {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1><i class="fa-solid fa-screwdriver-wrench"></i> لوحة التحكم</h1>
            <a href="create.php" class="btn-add">
                <i class="fa-solid fa-plus"></i> إضافة مقال جديد
            </a>
        </header>

        <!-- Main Content -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="60%">عنوان المقال</th>
                        <th width="30%">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center; padding: 40px; color: #95a5a6;">
                                لا توجد مقالات حالياً.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                            <tr>
                                <td class="article-id">#<?= htmlspecialchars($art['id']) ?></td>
                                <td class="article-title"><?= htmlspecialchars($art['title']) ?></td>
                                <td class="actions">
                                    <div class="actions-wrap">
                                        <a href="update.php?id=<?= $art['id'] ?>" class="action-btn edit-btn">
                                            <i class="fa-solid fa-pen-to-square"></i> تعديل
                                        </a>
                                        <a href="delete.php?id=<?= $art['id'] ?>"
                                            class="action-btn delete-btn"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المقال؟')">
                                            <i class="fa-solid fa-trash"></i> حذف
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>