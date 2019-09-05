<?php

namespace EVEBiographies;

class Fonts_Font_NanumGothicCoding extends Fonts_Font
{
    public function getURL()
    {
        return 'https://fonts.googleapis.com/css?family=Nanum+Gothic+Coding:400,700';
    }
    
    public function getLabel()
    {
        return 'Nanum Gothic Coding';
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
        return Fonts::TYPE_MONOSPACE;
    }
    
    public function getFontFamily()
    {
        return 'Nanum Gothic Coding';
    }
}






