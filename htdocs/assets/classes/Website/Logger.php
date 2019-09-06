<?php

namespace EVEBiographies;

abstract class Website_Logger
{
    abstract public function debug(string $message);
    
    abstract protected function init();
    
    public function __construct()
    {
        $this->init();
    }
}