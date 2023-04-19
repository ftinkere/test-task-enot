<?php
require_once "../utils/boot.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = db();
    $statement = $db->prepare('SELECT * FROM `users` WHERE username = ?');
    $statement->bind_param('s', $_POST['username']);
    $statement->execute();
    if ($statement->get_result()->num_rows > 0) {
        flash('Это имя пользователя уже занято.');
        header('Location: /register.php'); // Возврат на форму регистрации
        die; // Остановка выполнения скрипта
    }

    $statement = $db->prepare('INSERT INTO `users` (`username`, `password`) VALUES (?, ?)');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $statement->bind_param('ss', $_POST['username'], $password);
    $statement->execute();

    header('Location: login.php');

} else {
    flash();
    ?>

    <form action="register.php" method="post">
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit">Register</button>
        </div>
    </form>

    <?php
}