<?php
$pageTitle = 'Авторизация';
require_once "struc.php";
$loginError = '';

// Check if the user is already logged in and redirect them if necessary
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['user_type_id'] == 1) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: order-list.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

    // Sanitize input before passing to find function (though find() also sanitizes)
    $login = strip_tags($login);
    $password = strip_tags($password);

    $user = find($login, $password); // This now returns user data or false

    if ($user) {
        // Successful login
        $_SESSION['user'] = $user; // Store the entire user data array in session

        // Redirect based on user type
        if ($user['user_type_id'] == 1) {
            header("Location:  admin.php");
            exit();
        } else {
            header("Location: order-list.php");
            exit();
        }
    } else {
        // Failed login
        $loginError = "Неверный логин или пароль.";
    }
}
?>

    <main>    
        <form method="post" action="index.php">
        <label>Логин
        <input type="text" name="login" required value="<?php echo htmlspecialchars($login ?? ''); ?>" autocomplete="username">
        </label>

        <label>Пароль
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Вход</button>
    </form>

    <?php if (!empty($loginError)): ?>
        <p class="Error"><?php echo htmlspecialchars($loginError); ?></p>
    <?php endif; ?>
    </main>