<?php

namespace EVEBiographies;

class Fonts_Font_Lora extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Lora:400,700&amp;subset=latin-ext';
    }
    
    public function getLabel()
    {
        return 'Lora';
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
        return 'Lora';
    }
}



