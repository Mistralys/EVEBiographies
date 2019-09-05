<?php 

namespace EVEBiographies;

class Fonts_Font_OxygenMono extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Oxygen+Mono';
    }
    
    public function getLabel()
    {
        return 'Oxygen Mono';
    }
    
    public function getTypeID()
    {
        return Fonts::TYPE_MONOSPACE;
    }

    public function getWeight()
    {
        return '400';
    }
    
    public function getDefaultSize()
    {
        return '1.1';
    }
    
    public function getFontFamily()
    {
        return 'Oxygen Mono';
    }
}
