<?php

namespace EVEBiographies;

class Fonts_Font_EncodeSansCondensed extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Encode+Sans+Condensed:400,700&amp;subset=latin-ext';
    }
    
    public function getFontFamily()
    {
        return 'Encode Sans Condensed';
    }
    
    public function getLabel()
    {
        return 'Encode Sans Condensed';
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