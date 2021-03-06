<?php

namespace EVEBiographies;

require_once 'Skins/Skin.php';

class Skins
{
    const ERROR_NO_DEFAULT_BIOGRAPHY_SKIN = 37301;
    
    protected $path;
    
    protected $url;
    
   /**
    * @var Website
    */
    protected $website;

   /**
    * @var Website_Screen
    */
    protected $screen;
    
    public function __construct(Website $website)
    {
        $this->path = APP_ROOT.'/skins';
        $this->url = APP_URL.'/skins';
        $this->website = $website;
        $this->screen = $this->website->getScreen();
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getURL()
    {
        return $this->url;
    }
    
   /**
    * @var Skins_Skin
    */
    protected $skins;
    
    protected function load()
    {
        if(isset($this->skins)) {
            return;
        }
        
        $folder = $this->getPath();
        $this->skins = array();
        $d = new \DirectoryIterator($folder);
        foreach($d as $item)
        {
            if($item->isDot() || $item->isFile()) {
                continue;
            }
            
            $id = $item->getBasename();
            
            $skin = $this->getByID($id);
            if($skin->isBiographySkin()) {
                $this->skins[] = $skin;
            }
        }
        
        return $this->skins;
    }
    
    public function getByID($skinID) : Skins_Skin
    {
        if(empty($skinID)) {
            $skinID = $this->getDefaultBiographySkin()->getID();
        }
        
        $class = '\EVEBiographies\Skin_'.$skinID;
        $file = $this->getPath().'/'.$skinID.'/'.$skinID.'.php';
        
        require_once $file;
        
        return new $class($this, $this->screen, $skinID);
    }
    
    public function getDefaultBiographySkin() : ?Skins_Skin
    {
        $this->load();
        
        foreach($this->skins as $skin) 
        {
            if($skin->isBiographySkin()) {
                return $skin;
            }
        }
        
        throw new Website_Exception(
            'No default biography skin found',
            self::ERROR_NO_DEFAULT_BIOGRAPHY_SKIN
        );
    }
    
   /**
    * @return Skins_Skin[]
    */
    public function getAll()
    {
        $this->load();
        
        return $this->skins;
    }
}