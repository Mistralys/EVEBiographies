<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Crimson extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Crimson';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_DARK;
    }

    public function getCanvasBackground()
    {
        return '95, 27, 27';
    }
    
    public function getCanvasOpacity()
    {
        return '0.9';
    }
    
    public function getTextColor()
    {
        return 'dba48c';
    }

    public function getHeadersColor()
    {
        return 'ffd0bc';
    }
    
    public function getBodyBackground()
    {
        return '3a0000';
    }
    
    public function getDialogueColor()
    {
        return 'e3dbad';
    }
    
    public function getLinkColor()
    {
        return 'ff8a2f';
    }
    
    public function isDark() : bool
    {
        return true;
    }
}