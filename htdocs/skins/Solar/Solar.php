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
        return '7, 54, 66';
    }
    
    public function getCanvasOpacity()
    {
        return '0.9';
    }
    
    public function getTextColor()
    {
        return '9eadaf';
    }
    
    public function getBodyBackground()
    {
        return '002B36';
    }
    
    public function getDialogueColor()
    {
        return 'a9ccd6';
    }
}