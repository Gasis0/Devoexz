<?php
require_once "db/db.php"; 
$navLinks = [];
$showAuthLinks = true;

// Check if user is logged in via session
if (isset($_SESSION['user'])) {
    $showAuthLinks = false;
    $user = $_SESSION['user'];
    $userTypeId = $user['user_type_id'] ?? null;
    
    if ($userTypeId == 2) { // Преподаватель
        $navLinks = [
            ['href' => 'admin.php', 'text' => 'Панель преподавателя'],
        ];
    } else { // Студент
        $navLinks = [
            ['href' => 'reports.php', 'text' => 'Мои отчеты'],
            ['href' => 'create_report.php', 'text' => 'Подать отчет'],
        ];
    }
    $navLinks[] = ['href' => 'logout.php', 'text' => 'Выход'];
} else {
    $navLinks = [
        ['href' => 'index.php', 'text' => 'Авторизация'],
        ['href' => 'registration.php', 'text' => 'Регистрация'],
    ];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практика Онлайн<?php echo isset($pageTitle) ? " - $pageTitle" : ''; ?></title>
    <link rel='icon' href='images/logo.png'>
    <link rel='stylesheet' href='css/style.css'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        header img {
            height: 50px;
        }
        
        header h1 {
            font-size: 1.8rem;
        }
        
        nav {
            background-color: #34495e;
            padding: 1rem 2rem;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        nav a:hover {
            background-color: #3498db;
        }
        
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 500px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #3498db;
        }
        
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        
        form div {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        button {
            background-color: #3498db;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .error {
            background-color: #ffeaea;
            color: #c0392b;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border-left: 4px solid #c0392b;
        }
        
        .success {
            background-color: #e8f6f3;
            color: #27ae60;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border-left: 4px solid #27ae60;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            font-weight: bold;
            font-size: 1.2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-field {
            margin-bottom: 0.8rem;
            line-height: 1.5;
        }
        
        .card-field strong {
            color: #34495e;
            min-width: 120px;
            display: inline-block;
        }
        
        .create-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 1rem 2rem;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .create-link:hover {
            background-color: #229954;
        }
        
        .no-reports {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .status-1 { background-color: #fff3cd; color: #856404; } /* На проверке */
        .status-2 { background-color: #d4edda; color: #155724; } /* Принято */
        .status-3 { background-color: #f8d7da; color: #721c24; } /* На доработку */
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        footer {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <header>
        <img src='images/logo.png' alt='Логотип'>
        <h1>Практика Онлайн</h1>
    </header>

    <nav>
        <?php foreach ($navLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['href']); ?>"><?php echo htmlspecialchars($link['text']); ?></a>
        <?php endforeach; ?>
    </nav>

    <main>
        <h1><?php echo $pageTitle;?></h1>
        <div class="content">
            <?php 
            if (isset($pageContent) && !empty($pageContent)) {
                echo $pageContent;
            }
            ?>
        </div>
        <footer>
            <h3>© 2025 Техникум. Система "Практика Онлайн"</h3>
        </footer>
    </main>
</body>
</html>