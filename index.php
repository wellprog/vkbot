<?php
include __DIR__ . "/config.php";


//Получаем и декодируем уведомление
$request = json_decode(file_get_contents('php://input'));

//Проверяем сообщение на ошибку декодинга
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Access Denied");
}

//Проверка на токен от VK
if (!isset($request["secret"])) {
    die("Access Denied");
}

if ($request["secret"] !== $callbackkey) {
    die("Access Denied");
}


if (!isset($request["type"])) {
    die("Access Denied");
}

switch ($request["type"]) {
    case "confirmation":
        echo $checkkey;
        break;
    default:
        die("Access Denied");
        break;
}
