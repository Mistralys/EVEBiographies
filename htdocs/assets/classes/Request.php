<?php

namespace EVEBiographies;

class Request
{
    public function getParam($name, $default=null) : ?string
    {
        if(isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }

        return $default;
    }

    public function getBool($name) : bool
    {
        return $this->getParam($name) === 'yes' || $this->getParam($name) === 'true';
    }

    public function paramExists($name) : bool
    {
        return isset($_REQUEST[$name]);
    }
}
