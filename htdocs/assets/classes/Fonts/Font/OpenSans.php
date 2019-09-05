<?php

namespace EVEBiographies;

class Fonts_Font_OpenSans extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin-ext';
    }
    
    public function getFontFamily()
    {
        return 'Open Sans';
    }
    
    public function getLabel()
    {
        return 'Open Sans';
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