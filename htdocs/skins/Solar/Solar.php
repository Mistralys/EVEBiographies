<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Solar extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Solar';
    }
    
    public function getLayoutTypeID() : string 
    {
        return self::LAYOUT_TYPE_DARK;
    }

    public function getCanvasBackground()
    {
        return '27, 65, 95';
    }
    
    public function getCanvasOpacity()
    {
        return '0.9';
    }
    
    public function getTextColor()
    {
        return 'a2b1c1';
    }

    public function getHeadersColor()
    {
        return '8ab6d0';
    }
    
    public function getBodyBackground()
    {
        return '002B36';
    }
    
    public function getDialogueColor()
    {
        return 'a9ccd6';
    }

    public function getLinkColor()
    {
        return '58b1d7';
    }
    
    public function isDark() : bool
    {
        return false;
    }
}