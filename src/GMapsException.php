<?php

namespace dotzero;

class GMapsException extends \Exception
{
    public function __construct($msg, $code = 100)
    {
        parent::__construct($msg, $code);
    }
}
