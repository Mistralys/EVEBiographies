<?php

namespace EVEBiographies;

class Fonts_Font_GentiumBasic extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Gentium+Basic:400,700&amp;subset=latin-ext';
    }
    
    public function getLabel()
    {
        return 'Gentium Basic';
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
        return 'Gentium Basic';
    }
}



