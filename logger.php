<?php

class Logger {
    public function Write($data) {
        file_put_contents("log.log", json_encode($data) . "\n\n", FILE_APPEND);
    }
}