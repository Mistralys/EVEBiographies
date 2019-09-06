<?php

namespace EVEBiographies;

class Website_Logger_File extends Website_Logger
{
    protected $logFile;
    
    public function init()
    {
        $this->logFile = APP_ROOT.'/logs/app-'.date('Y-m').'.log';
    }
    
    public function debug(string $message)
    {
        error_log(
            $message.PHP_EOL,
            3,
            $this->logFile
        );
    }
}