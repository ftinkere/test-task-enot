<?php
include '../vendor/autoload.php';
require_once "../utils/boot.php";

?>
<head>
    <title>Test task</title>
</head>
<body>
    <?php flash(); ?>
    <?php
    if (!check_auth()) {
    ?>
        <a href="login.php"> Login </a>
        <br>
        <a href="register.php"> Register </a>
    <?php
    } else {
    ?>
        <a href="logout.php"> Logout </a>
        <br>
        User_id: <?= $_SESSION['user_id'] ?>
        <br>

        <a href="converter.php"> Converter </a>

    <?php
    }
    ?>
</body>

<?php
