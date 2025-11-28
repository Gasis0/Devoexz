<?php
session_start(); // Обязательно стартуем сессию

$pageTitle = 'Список заявок';
require_once "db/db.php";     // Подключаемся к базе данных
require_once "struc.php";      // Структура страниц или дополнительные скрипты

// Проверяем, подключились ли мы к БД
if (!$db || !mysqli_ping($db)) {
    die('Ошибка соединения с базой данных');
}

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id']; // ID текущего пользователя

// Подготовленный запрос
$query = "
    SELECT s.id_service, s.address, s.user_ID, s.servis_type_ID, s.data, s.time, s.pay_tapy_ID, s.status_ID, s.reason_cancel,
           st.name_service, pt.name_pay, stat.name_status
    FROM service s
    JOIN service_type st ON s.servis_type_ID = st.id_service_type
    JOIN pay_type pt ON s.pay_tapy_ID = pt.id_pay_type
    JOIN status stat ON s.status_ID = stat.id_status
    WHERE s.user_ID = ?
    ORDER BY s.data DESC, s.time DESC
";

// Выполняем подготовленный запрос
$stmt = mysqli_prepare($db, $query);
if (!$stmt) {
    die('Ошибка подготовки запроса: ' . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt, 'i', $user_id); // Привязываем параметр
mysqli_stmt_execute($stmt);                     // Выполняем запрос
$result = mysqli_stmt_get_result($stmt);       // Получаем результат
$zayavki = mysqli_fetch_all($result, MYSQLI_ASSOC); // Конвертируем в ассоциативный массив
mysqli_stmt_close($stmt);                      // Закрываем запрос

// Формируем контент страницы
ob_start(); // Начинаем буферизацию вывода
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Остальные метаданные -->
</head>
<body>
    <h1>Мои заявки</h1>

    <?php if (empty($zayavki)): ?>
        <div class="no-zayavki">
            <p>У вас пока нет заявок.</p>
            <a href="order.php" class="create-link">Создать первую заявку</a>
        </div>
    <?php else: ?>
        <div class="cards-container">
            <?php foreach ($zayavki as $z): ?>
                <div class="card">
                    <div class="card-header">
                        Заявка #<?= htmlspecialchars($z['id_service']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Адрес:</strong> <?= htmlspecialchars($z['address']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Услуга:</strong> <?= htmlspecialchars($z['name_service']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Дата:</strong> <?= htmlspecialchars($z['data']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Время:</strong> <?= htmlspecialchars($z['time']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Оплата:</strong> <?= htmlspecialchars($z['name_pay']) ?>
                    </div>
                    <div class="card-field">
                        <strong>Статус:</strong> <?= htmlspecialchars($z['name_status']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="order.php" class="create-link">Создать новую заявку</a>
    <?php endif; ?>
</body>
</html>

<?php
$pageContent = ob_get_clean(); // Завершаем буферизацию и сохраняем результат
?>