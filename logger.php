<?php

class Logger {
    public function Write($data) {
        file_put_contents("log.log", json_encode($data), FILE_APPEND);
    }
}