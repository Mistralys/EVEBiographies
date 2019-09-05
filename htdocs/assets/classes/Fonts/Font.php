<?php

namespace EVEBiographies;

abstract class Fonts_Font
{
   /**
    * @var Fonts
    */
    protected $fonts;
    
    public function __construct(Fonts $fonts)
    {
        $this->fonts = $fonts;
    }
    
    abstract public function getURL();
    
    abstract public function getTypeID();
    
    abstract public function getLabel();
    
    abstract public function getFontFamily();
    
    abstract public function getWeight();
    
    abstract public function getDefaultSize();
    
    protected $id;
    
    public function getID()
    {
        if(!isset($this->id)) {
            $this->id = str_replace('EVEBiographies\Fonts_Font_', '', get_class($this));
        }
        
        return $this->id;
    }
    
    public function getTypeLabel()
    {
        return $this->fonts->getTypeLabel($this->getTypeID());
    }
    
    public function getFallbackFamily()
    {
        return $this->fonts->getTypeFallback($this->getTypeID());
    }
}