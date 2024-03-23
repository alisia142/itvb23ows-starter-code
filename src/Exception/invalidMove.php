<?php

namespace App\Exception;

use Exception;

// is called when a move is invalid. Message is then displayed in the parent class
class InvalidMove extends Exception
{
    public function __construct($message = 'Invalid move')
    {
        parent::__construct($message);
    }
}
