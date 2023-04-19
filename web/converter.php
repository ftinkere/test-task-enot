<?php
require_once "../utils/boot.php";
require_once "../utils/parse.php"; // Костыль из-за неработающего cron

flash();
$result ="";

$db = db();
$statement = $db->prepare('SELECT * FROM `rates` WHERE datetime = (SELECT MAX(datetime) FROM rates)');
$statement->execute();
$result  = $statement->get_result();
if ($result->num_rows == 0) {
    flash('Нет данных в базе данных.');
    header('Location: /converter.php');
    die; // Остановка выполнения скрипта
}
$rates = $result->fetch_assoc();
?>
Курс доллара: <?= $rates['usd'] ?> RUB <br>
Курс евро: <?= $rates['eur'] ?> RUB <br>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['currency_from'] == "rub") {
        if ($_POST['currency_to'] == "rub") {
            $result = $_POST['from'];
        } else if ($_POST['currency_to'] == "usd") {
            $result = ((float)$_POST['from']) / ((float) $rates['usd']);
        } else if ($_POST['currency_to'] == "eur") {
            $result = ((float) $_POST['from']) / ((float) $rates['eur']);
        }
    } else if ($_POST['currency_from'] == "usd") {
        if ($_POST['currency_to'] == "rub") {
            $result = ((float) $_POST['from']) * ((float) $rates['usd']) ;
        } else if ($_POST['currency_to'] == "usd") {
            $result = $_POST['from'];
        } else if ($_POST['currency_to'] == "eur") {
            $result = ((float) $_POST['from']) * ((float) $rates['usd']) / ((float) $rates['eur']);
        }
    } else if ($_POST['currency_from'] == "eur") {
        if ($_POST['currency_to'] == "rub") {
            $result = ((float) $_POST['from']) * ((float) $rates['eur']) ;
        } else if ($_POST['currency_to'] == "usd") {
            $result = ((float) $_POST['from']) * ((float) $rates['eur']) / ((float) $rates['usd']);
        } else if ($_POST['currency_to'] == "eur") {
            $result = $rates['eur'];
        }
    }
    ?>

    <form action="converter.php" method="post">
        <label for="from">Из</label>
        <input name="from" value="<?= $_POST['from'] ?>"/>

        <select name="currency_from">
            <option value="rub" <?= ($_POST['currency_from'] == 'rub' ? 'selected' : '') ?>>RUB рубль</option>
            <option value="usd" <?= ($_POST['currency_from'] == 'usd' ? 'selected' : '') ?>>USD доллар</option>
            <option value="eur" <?= ($_POST['currency_from'] == 'eur' ? 'selected' : '') ?>>EUR евро</option>
        </select>

        <label for="to">В</label>
        <input name="to" disabled value="<?= $result ?>"/>
        <select name="currency_to" value="<?= $_POST['currency_to'] ?>">
            <option value="rub" <?= ($_POST['currency_to'] == 'rub' ? 'selected' : '') ?>>RUB рубль</option>
            <option value="usd" <?= ($_POST['currency_to'] == 'usd' ? 'selected' : '') ?>>USD доллар</option>
            <option value="eur" <?= ($_POST['currency_to'] == 'eur' ? 'selected' : '') ?>>EUR евро</option>
        </select>

        <button type="submit">Convert</button>
    </form>

    <?php
} else {
?>

    <form action="converter.php" method="post">
        <label for="from">Из</label>
        <input name="from"/>

        <select name="currency_from">
            <option value="rub">RUB рубль</option>
            <option value="usd">USD доллар</option>
            <option value="eur">EUR евро</option>
        </select>

        <label for="to">В</label>
        <input name="to" disabled />
        <select name="currency_to">
            <option value="rub">RUB рубль</option>
            <option value="usd">USD доллар</option>
            <option value="eur">EUR евро</option>
        </select>

        <button type="submit">Convert</button>
    </form>

<?php
}