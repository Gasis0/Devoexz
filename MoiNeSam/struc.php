
<?php
require_once "db/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой не сам <?php echo $pageTittle ?></title>
    <link rel='icon' href='images/logo.jpeg'>
    <link rel='stylesheet' href='style/style.css'>
</head>

<header> <img src='images/logo.jpeg' alt='логотип'>
        <h1>Мой не сам</h1>
    </header>
    <nav>
        <a href="/Devoexz/MoiNeSam/">Главная</a>
        <a href="/Devoexz/MoiNeSam/admin.php">Админ-панель</a>
        <a href="/Devoexz/MoiNeSam/reg.php">Регистрация</a>
        <a href="/Devoexz/MoiNeSam/order.php">Список-заявок</a>
        <a href="/Devoexz/MoiNeSam/order-list.php">Создание заявки</a>
    </nav>
<body>
    <main>
    <h1>  
    <?php echo $pageTittle;?>
    <h1>
        <div class= 'content'>
            <?php echo $pageContent ?? '';?>
        </div>   
        <footer>
    <h3>2025</h3>
        <footer>
    </main>
<script src="script/script.js"></script>
</body>
</html>