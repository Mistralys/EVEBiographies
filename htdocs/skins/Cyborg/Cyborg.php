<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Cyborg extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Cyborg';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_DARK;
    }
    
    public function getCanvasBackground()
    {
        return '1, 1, 1';
    }
    
    public function getCanvasOpacity()
    {
        return '0.85';
    }
    
    public function getTextColor()
    {
        return '9a9898';
    }
    
    public function getBodyBackground()
    {
        return '000';
    }
    
    public function getDialogueColor()
    {
        return 'cecac8';
    }
}