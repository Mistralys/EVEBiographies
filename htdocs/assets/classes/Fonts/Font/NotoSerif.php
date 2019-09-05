<?php

namespace EVEBiographies;

class Fonts_Font_NotoSerif extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Noto+Serif+SC:400,500,700';
    }
    
    public function getLabel()
    {
        return 'Noto Serif SC';
    }
    
    public function getWeight()
    {
        return '400';
    }
    
    public function getDefaultSize()
    {
        return '1.2';
    }
    
    public function getTypeID()
    {
        return Fonts::TYPE_SERIF;
    }
    
    public function getFontFamily()
    {
        return 'Noto Serif SC';
    }
}



