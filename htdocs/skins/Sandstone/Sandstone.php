<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Bootswatch.php';

class Skin_Sandstone extends Skins_Skin_Source_Bootswatch
{
    public function getLabel() : string
    {
        return 'Sandstone';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_LIGHT;
    }

    public function getCanvasBackground()
    {
        return '255, 251, 220';
    }
    
    public function getCanvasOpacity()
    {
        return '0.8';
    }
    
    public function getTextColor()
    {
        return '583e23';
    }
    
    public function getBodyBackground()
    {
        return '1f0f06';
    }
    
    public function getDialogueColor()
    {
        return '42230d';
    }
}