<?php
include __DIR__ . "/config.php";


//Получаем и декодируем уведомление
$request = json_decode(file_get_contents('php://input'));



echo $checkkey;
