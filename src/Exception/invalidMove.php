<?php

namespace App\Exception;

use Exception;

class InvalidMove extends Exception
{
    public function __construct($message = 'Invalid move')
    {
        parent::__construct($message);
    }
}
