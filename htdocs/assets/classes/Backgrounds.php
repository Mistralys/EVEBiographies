<?php

namespace EVEBiographies;

require_once 'Backgrounds/Background.php';

class Backgrounds
{
    const ERROR_UNKNOWN_BACKGROUND = 30901;
    
    protected $path;
    
    protected $url;
    
    public function __construct()
    {
        $this->path = APP_ROOT.'/backgrounds';
        $this->url = APP_URL.'/backgrounds';
    }
    
   /**
    * @var Backgrounds_Background[]
    */
    protected $items;
    
    protected $imgExtensions = array(
        'png',
        'jpg'
    );
    
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
            
            $ext = strtolower($item->getExtension());
            if(!in_array($ext, $this->imgExtensions)) {
                continue;
            }
            
            $name = $item->getBasename();
            
            
            $bg = new Backgrounds_Background($this->url.'/'.$name, $this->path.'/'.$name);
            $result[$bg->getID()] = $bg;
        }
        
        $this->items = $result;
    }
    
   /**
    * Retrieves all available backgrounds.
    * @return Backgrounds_Background[]
    */
    public function getAll()
    {
        $this->load();
        
        return array_values($this->items);
    }
    
    public function idExists($id) : bool
    {
        $this->load();
        
        return isset($this->items[$id]);
    }
    
    public function getByID($id) : Backgrounds_Background
    {
        $this->load();
        
        if(isset($this->items[$id])) {
            return $this->items[$id];
        }
        
        throw new Website_Exception(
            'Unknown background', 
            self::ERROR_UNKNOWN_BACKGROUND,
            sprintf(
                'Tried loading background [%s]',
                $id
            )
        );
    }
}
