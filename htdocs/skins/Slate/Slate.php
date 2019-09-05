<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Slate extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Slate';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_DARK;
    }

    public function getCanvasBackground()
    {
        return '58, 63, 68';
    }
    
    public function getCanvasOpacity()
    {
        return '0.9';
    }
    
    public function getTextColor()
    {
        return 'aaa';
    }
    
    public function getBodyBackground()
    {
        return '272B30';
    }
    
    public function getDialogueColor()
    {
        return 'bbb';
    }
}