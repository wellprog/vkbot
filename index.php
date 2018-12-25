<?php
include __DIR__ . "/config.php";
include __DIR__ . "/app.php";
include __DIR__ . "/vendor/autoload.php";


if (!isset($callbackkey)) $callbackkey = "";
if (!isset($checkkey)) $checkkey = "";
if (!isset($apikey)) $apikey = "";


App::$apikey = $apikey;
App::$callbackkey = $callbackkey;
App::$checkkey = $checkkey;

$app = new App();

$app->run();