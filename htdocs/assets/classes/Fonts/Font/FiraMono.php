<?php 

namespace EVEBiographies;

class Fonts_Font_FiraMono extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Fira+Mono:400,500,700&;subset=latin-ext';
    }
    
    public function getLabel()
    {
        return 'Fira Mono';
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
        return 'Fira Mono';
    }
}
