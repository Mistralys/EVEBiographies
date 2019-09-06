<?php

namespace EVEBiographies;

class Website_Logger_Echo extends Website_Logger
{
    public function init()
    {
        echo '<style>DIV.debug-line{font-family:monospace;}</style><div style="margin-top:60px;">&#160;</div>';
    }
    
    public function debug(string $message)
    {
        echo '<div class="debug-line">'.$message.'</div>';
    }
}