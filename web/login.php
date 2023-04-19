<?php
require_once "../utils/boot.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = db();
    $statement = $db->prepare('SELECT * FROM `users` WHERE username = ?');
    $statement->bind_param('s', $_POST['username']);
    $statement->execute();
    $result  = $statement->get_result();
    if ($result->num_rows == 0) {
        flash('Пользователь с такими данными не зарегистрирован.');
        header('Location: /login.php'); // Возврат на форму регистрации
        die; // Остановка выполнения скрипта
    }
    $user = $result->fetch_assoc();

    if (password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: /');
        die;
    } else {
        flash('Пароль неверен');
        header('Location: login.php');
    }
} else {
    flash();
    ?>

    <form action="login.php" method="post">
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit">Login</button>
        </div>
    </form>

<?php
}