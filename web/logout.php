<?php
require_once "../utils/boot.php";

unset($_SESSION['user_id']);
header('Location: /');