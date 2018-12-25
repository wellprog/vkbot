<?php

class Errors {
    const ACCESS_DENIED = 1;

    const ControllerNotExists   = 100;
    const ActionNotExists       = 101;
    const ActionNotAccess       = 102;
    const WrongController       = 103;
    const WrongControllerFile   = 104;
    const WrongControllerClass  = 105;
    const WrongControllerBase   = 106;
    const WrongModel            = 107;
    const WrongModelFile        = 108;
    const WrongModelClass       = 109;


    const SessionStringError    = 500;


    public static $errors = [
        Errors::ACCESS_DENIED           => "Access Denied",

        
        Errors::ControllerNotExists     => "Controller not Exists",
        Errors::ActionNotExists         => "Action Not Exests",
        Errors::ActionNotAccess         => "Access to Action is Denied",
        Errors::WrongController         => "Controller path not found",
        Errors::WrongControllerFile     => "Controller file not found",
        Errors::WrongControllerClass    => "Controller class not found",
        Errors::WrongControllerBase     => "Controller base classs not found",
        Errors::WrongModel              => "Model path not found",
        Errors::WrongModelFile          => "Model file not found",
        Errors::WrongModelClass         => "Model class not found",


        Errors::SessionStringError      => "Session string in wrong format",


    ];

    public static function get($code) {
        if (!isset(self::$errors[$code]))
            return "Unknown error";

        return self::$errors[$code];
    }
}

class Responce {

    public function __construct()
    {
        
    }

    /**
     * Функция выбрасывает ошибку на экран
     * 
     * В случае если передан код ошибки то идет просмотр кодов
     * впротивном случае выбрасывается сама ошибка
     */
    public function WriteRawError($code, $description = "") {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        if (is_numeric($code))
            die(Errors::get($code) . ": " . $description);

        die($code . ": " . $description);
    }

    public function WriteError($code, $description = "") {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        header('Content-Type: application/json', true);
        $data = $this->getTemplate();

        if (is_numeric($code)) {
            $data["ErrorID"] = $code;
            $data["ErrorDescription"] = Errors::get($code);
        } else {
            $data["ErrorID"] = 999;
            $data["ErrorDescription"] = $code;
        }

        $data["ErrorAdditional"] = $description;

        die(json_encode($data));
    }


    public function WriteRaw($data) {
        echo $data;
    }


    private function getTemplate() {
        return [
            "ErrorID" => 0,
            "ErrorDescription" => "",
            "ErrorAdditional" => "",
            "Data" => ""
        ];
    }
}