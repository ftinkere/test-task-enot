<?php
include __DIR__ . '/../vendor/autoload.php';

session_start();
date_default_timezone_set('Europe/Moscow');
function db() {
    static $db;

    if (!$db) {
        $db = mysqli_connect('mysql', 'user', 'secret', 'app', 3306);
    }

    return $db;
}

function flash($message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
            <div>
                <?=$_SESSION['flash']?>
            </div>
        <?php }
        unset($_SESSION['flash']);
    }
}

function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}
