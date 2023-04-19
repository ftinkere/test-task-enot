<?php
require_once "../utils/boot.php";

function get_currency($currency_code, $format) {

    $date = date('d/m/Y'); // Текущая дата
    $cache_time_out = 10800; // Время жизни кэша в секундах

    $file_currency_cache = '../db/currency.xml'; // Файл кэша

    if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $out = curl_exec($ch);

        curl_close($ch);

        file_put_contents($file_currency_cache, $out);
    }

    $content_currency = simplexml_load_file($file_currency_cache);

    return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);

}

$usd = get_currency('USD', 3);
$eur = get_currency('EUR', 3);

$date = date('Y-m-d H:i:s');

$db = db();

$statement = $db->prepare('INSERT INTO `rates` (`datetime`, `usd`, `eur`) VALUES (?, ?, ?)');
$statement->bind_param('sss', $date, $usd, $eur);
$statement->execute();