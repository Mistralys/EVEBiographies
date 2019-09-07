<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Swamp extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Swamp';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_DARK;
    }

    public function getCanvasBackground()
    {
        return '71, 95, 27';
    }
    
    public function getCanvasOpacity()
    {
        return '0.9';
    }
    
    public function getTextColor()
    {
        return 'b5cc89';
    }

    public function getHeadersColor()
    {
        return 'b8d77f';
    }
    
    public function getBodyBackground()
    {
        return '213004';
    }
    
    public function getDialogueColor()
    {
        return 'd6efa7';
    }
    
    public function getLinkColor()
    {
        return 'c1ff4c';
    }
    
    public function isDark() : bool
    {
        return true;
    }
}