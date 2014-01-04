<?php

namespace spaaza\client;

class APIException extends \Exception {
    public function __construct($error) {
        $code = array_keys($error)[0];
        parent::__construct($error[$code]['name'] . ': ' . $error[$code]['description']);

        $this->code = $code;
        $this->name = $error[$code]['name'];
        $this->description = $error[$code]['description'];
    }
    
}
