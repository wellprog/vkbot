<?php
include __DIR__ . "/config.php";
include __DIR__ . "/app.php";


if (!isset($callbackkey)) $callbackkey = "";
if (!isset($checkkey)) $checkkey = "";
if (!isset($apikey)) $apikey = "";


App::$apikey = $apikey;
App::$callbackkey = $callbackkey;
App::$checkkey = $checkkey;

$app = new App();

$app->run();




// switch ($request["type"]) {
//     case "confirmation":
//         echo $checkkey;
//         break;
//     default:
//         die("Access Denied");
//         break;
// }
