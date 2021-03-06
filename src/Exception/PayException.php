<?php

namespace NaturalGao\PhpPay\Exception;

use ErrorException;

class PayException extends ErrorException
{
    public function __construct($message, $code = 0, $severity = 1, $filename = __FILE__, $lineno = __LINE__, $previous = null)
    {
        parent::__construct($message, $code, $severity, $filename, $lineno, $previous);
    }
}
