<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Source/Custom.php';

class Skin_Alabaster extends Skins_Skin_Source_Custom
{
    public function getLabel() : string
    {
        return 'Alabaster';
    }
    
    public function getLayoutTypeID() : string
    {
        return self::LAYOUT_TYPE_LIGHT;
    }
    
    public function supportsBackground() : bool
    {
        return false;
    }
    
    public function isDark() : bool
    {
        return false;
    }
}
