<?php

class Request {

    private $GET;
    private $POST;
    private $COOKIE;
    private $REQUEST;
    private $HEADERS;
    private $RAW;

    private $sessionkey = "";
    private $SESSION = [];

    const SessionKey = "SESSION";

    /**
     * @var string[] $path
     */
    private $path;

    public function __construct()
    {
        $this->GET = $_GET;
        $this->POST = $_POST;
        $this->REQUEST = $_REQUEST;
        $this->COOKIE = $_COOKIE;
        $this->HEADERS = [];
        $this->RAW = json_decode(file_get_contents('php://input'), true);

        foreach ($_SERVER as $k => $v) 
            if (substr($k, 0, 5) == "HTTP_")
                $this->HEADERS[substr($k,5)] = $v;

        $this->TrimPath();
        $this->SessionLoad();
    }

    private function TrimPath() {
        //Получаем строку запроса
        $path = $_SERVER["REQUEST_URI"];
        
        //Вычищаем её от параметров
        $qPath = strpos($path, "?");
        if ($qPath !== false) {
            $path = substr($path, 0, $qPath);
        }
        
        //Разбиваем строку по разделителю
        $tParts = explode("/", $path);

        //Убираем пустые строки
        $parts = [];
        foreach ($tParts as $k => $v) {
            if ($v != "")
                $parts[] = $v;
        }

        $this->path = $parts;
    }

    public function RequestedVersion() {
        if (count($this->path) < 1)
            return "v1";
        return trim($this->path[0]);
    }

    public function RequestedController() {
        if ($this->Raw("type", false) !== false) {
            $controller = $this->Raw("type");
            $parts = explode("_", $controller);

            if (count($parts) == 1)
                return ucfirst(strtolower($this->Raw("type")));

            $controller = "";
            for ($i = 0; $i < count($parts); $i++) {
                if (count($parts) - $i <= 1) break;
                $controller .= $parts[$i];
            }

            return ucfirst(strtolower($controller));
        }

        if (count($this->path) < 2)
            return "Home";
        
        return ucfirst(strtolower(trim($this->path[1])));
    }

    public function RequestedAction() {
        if ($this->Raw("type", false) !== false) {
            $action = $this->Raw("type");
            $parts = explode("_", $action);

            if (count($parts) == 1)
                return "IndexAction";

            return ucfirst(strtolower($parts[count($parts) - 1])) . "Action";
        }

        if (count($this->path) < 3)
            return "IndexAction";
        return ucfirst(strtolower(trim($this->path[2]))) . "Action";
    }



    public function GetValue ($key, $default = "") {
        if (!isset($this->GET[$key]))
            return $default;
        return $this->GET[$key];
    }

    public function GetAll() {
        return $this->GET;
    }

    public function PostValue ($key, $default = "") {
        if (!isset($this->POST[$key]))
            return $default;
        return $this->POST[$key];
    }

    public function PostAll() {
        return $this->POST;
    }

    public function RequestValue ($key, $default = "") {
        if (!isset($this->REQUEST[$key]))
            return $default;
        return $this->REQUEST[$key];
    }

    public function RequestAll() {
        return $this->REQUEST;
    }

    public function Raw($key, $default = "") {
        if (!isset($this->RAW[$key]))
            return $default;
        return $this->RAW[$key];
    }

    public function RawAll() {
        return $this->RAW;
    }

    public function CookeyValue ($key, $default = "") {
        if (!isset($this->COOKIE[$key]))
            return $default;
        return $this->COOKIE[$key];
    }

    public function HeaderValue ($key, $default = "") {
        if (!isset($this->HEADERS[$key]))
            return $default;
        return $this->HEADERS[$key];
    }

    public function SessionValue($key, $default = "") {
        if (!isset($this->SESSION[$key]))
            return $default;
        return $this->SESSION[$key];
    }

    public function SessionSet($key, $value) {
        $this->SESSION[$key] = $value;
    }

    public function SessionDel($key) {
        $arr = [];
        foreach ($this->SESSION as $k => $value) {
            if ($key == $k) continue;
            $arr[$k] = $value;
        }

        $this->SESSION = $arr;
    }

    public function SessionClear() {
        $this->SESSION = [];
    }

    public function SessionSetKey($key) {
        if ($this->sessionkey != "")
            $this->SessionDestroy();
        
        $this->SESSION = [];
        $this->sessionkey = $key;
    }

    private function SessionLoad() {
        $sessionKey = $this->HeaderValue(self::SessionKey, false);
        if ($sessionKey === false) return;

        $this->sessionkey = $sessionKey;
        
        if (!preg_match('/^[A-Z0-9-]{36}$/', $this->sessionkey)) {
            $this->sessionkey = "";
            App::$Responce->WriteError(Errors::SessionStringError);
        }

        $filepath = App::BaseAddress . App::Cache . App::Session . $this->sessionkey;
        if (!file_exists($filepath)) return;

        $content = file_get_contents($filepath);
        $this->SESSION = json_decode($content, true);
    }

    public function SessionSave() {
        if ($this->sessionkey == "") return;
        $data = json_encode($this->SESSION);
        $filepath = App::BaseAddress . App::Cache . App::Session . $this->sessionkey;
        file_put_contents($filepath, $data);
    }

    public function SessionDestroy() {
        $this->SessionClear();
        if ($this->sessionkey == "") return;

        $filepath = App::BaseAddress . App::Cache . App::Session . $this->sessionkey;
        $this->sessionkey = "";

        if (!file_exists($filepath)) return;

        unlink($filepath);
    }

    public function SessionExists() {
        return $this->sessionkey != "";
    }
}