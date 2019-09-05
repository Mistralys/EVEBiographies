<?php

namespace EVEBiographies;

class Fonts_Font_Exo2 extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Exo+2:400,700&amp;subset=latin-ext';
    }
    
    public function getFontFamily()
    {
        return 'Exo 2';
    }
    
    public function getLabel()
    {
        return 'Exo 2';
    }
    
    public function getWeight()
    {
        return '400';
    }
    
    public function getDefaultSize()
    {
        return '1.1';
    }
    
    public function getTypeID()
    {
        return Fonts::TYPE_SANS_SERIF;
    }
}