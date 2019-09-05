<?php

namespace EVEBiographies;

require_once 'Fonts/Font.php';

class Fonts
{
    const ERROR_UNKNOWN_FONT = 31001;
    
    const ERROR_UNKNOWN_TYPE = 31002;

    const TYPE_SANS_SERIF = 'sans';
    
    const TYPE_SERIF = 'serif';
    
    const TYPE_MONOSPACE = 'mono';
    
    protected $path;
    
    protected $types = array();
    
    public function __construct()
    {
        $this->path = APP_ROOT.'/assets/classes/Fonts/Font';
        
        $this->registerType(self::TYPE_SANS_SERIF, t('Sans serif'), 'sans-serif');
        $this->registerType(self::TYPE_SERIF, t('Serif'), 'serif');
        $this->registerType(self::TYPE_MONOSPACE, t('Monospace'), 'monospace');
    }
    
    protected function registerType($typeID, $label, $fallback)
    {
        $this->types[$typeID] = array(
            'label' => $label,
            'fallback' => $fallback
        );
    }
    
    public function getTypeLabel($typeID)
    {
        if(isset($this->types[$typeID])) {
            return $this->types[$typeID]['label'];
        }
        
        throw new Website_Exception(
            'Unknown font type',
            self::ERROR_UNKNOWN_TYPE
        );
    }

    public function getTypeFallback($typeID)
    {
        if(isset($this->types[$typeID])) {
            return $this->types[$typeID]['fallback'];
        }
        
        throw new Website_Exception(
            'Unknown font type',
            self::ERROR_UNKNOWN_TYPE
        );
    }
    
    /**
     * @var Fonts_Font[]
     */
    protected $items;
    
    protected function load()
    {
        if(isset($this->items)) {
            return;
        }
        
        $d = new \DirectoryIterator($this->path);
        $result = array();
        foreach($d as $item)
        {
            if(!$item->isFile()) {
                continue;
            }
            
            $name = 'Fonts_Font_'.$item->getBasename('.php');
            
            $file = APP_ROOT.'/assets/classes/'.str_replace('_', '/', $name).'.php';
            require_once $file;
            
            $class = 'EVEBiographies\\'.$name;
            
            $font = new $class($this);
            $result[$font->getID()] = $font;
        }
        
        $this->items = $result;
    }
    
    /**
     * Retrieves all available fonts.
     * @return Fonts_Font[]
     */
    public function getAll()
    {
        $this->load();
        
        return array_values($this->items);
    }
    
    public function getAllCategorized()
    {
        $all = $this->getAll();
        $result = array();

        foreach($all as $font) 
        {
            $type = $font->getTypeLabel();
            
            if(!isset($result[$type])) {
                $result[$type] = array();
            }
            
            $result[$type][] = $font;
        } 
        
        ksort($result);
        
        foreach($result as $type => $fonts) {
            usort($fonts, array($this, 'sortFonts'));
            $result[$type] = $fonts;
        }
        
        return $result;
    }
    
    public function sortFonts(Fonts_Font $a, Fonts_Font $b)
    {
        return strnatcasecmp($a->getLabel(), $b->getLabel());
    }
    
    public function getByID($id) : Fonts_Font
    {
        $this->load();
        
        if(isset($this->items[$id])) {
            return $this->items[$id];
        }
        
        throw new Website_Exception(
            'Unknown font',
            self::ERROR_UNKNOWN_FONT,
            sprintf(
                'Tried loading font [%s]',
                $id
            )
        );
    }
}
