<?php

include __DIR__ . "/request.php";
include __DIR__ . "/responce.php";
include __DIR__ . "/executer.php";
include __DIR__ . "/logger.php";

class App {

    const BaseAddress = __DIR__ . "/";
    const Controllers = "controller/";
    const Model = "model/";
    const Cache = "cache/";
    const Session = "session/";

    public static $callbackkey = "";
    public static $checkkey = "";
    public static $apikey = "";

    /** @var Request $Request */
    public static $Request;
    /** @var Responce $Responce */
    public static $Responce;
    /** @var Executer $Executer */
    public static $Executer;
    /** @var Logger $Logger */
    public static $Logger;

    public function run() {
        App::$Request = new Request();
        App::$Responce = new Responce();
        App::$Executer = new Executer(App::$Responce, App::$Request);
        App::$Logger = new Logger();

        if (self::$Request->Raw("secret", "") !== self::$callbackkey) {
            self::$Responce->WriteError(Errors::ACCESS_DENIED);
        }

        App::$Executer->ExecPath();
    }

}