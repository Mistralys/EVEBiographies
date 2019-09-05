<?php

namespace EVEBiographies;

require_once 'Website/NavigationItem.php';

abstract class Website_Navigation
{
   /**
    * @var Website_Screen
    */
    protected $screen;
    
   /**
    * @var Skins_Skin
    */
    protected $skin;
    
   /**
    * @var Website
    */
    protected $website;
    
    public function __construct(Website_Screen $screen)
    {
        $this->screen = $screen;
        $this->website = $screen->getWebsite();
        $this->skin = $screen->getSkin();
        
        $this->configure();
    }
    
    public function getScreen()
    {
        return $this->screen;
    }
    
    public function getWebsite()
    {
        return $this->website;
    }
    
    abstract protected function configure();
    
    public function render()
    {
        $tpl = $this->skin->createTemplate('navigation');
        $tpl->addVar('nav', $this);
        return $tpl->render();
    }
    
    protected function addScreen($name) : Website_NavigationItem
    {
        $screen = $this->website->createScreen($name);
        
        $screen->start($this->website);
        
        return $this->addURL($screen->getURL(), $screen->getNavigationTitle(), $screen->requiresAdmin());
    }
    
   /**
    * Adds a screen, but only if a user is authenticated. 
    * @param string $name
    * @return Website_NavigationItem|NULL
    */
    protected function addAuthScreen($name) : ?Website_NavigationItem
    {
        if($this->website->isUserAuthenticated()) {
            return $this->addScreen($name);
        }
        
        return null;
    }
    
   /**
    * @var Website_NavigationItem[]
    */
    protected $items;
    
    protected function addURL($url, $label, $adminOnly=false) : Website_NavigationItem
    {
        $item = new Website_NavigationItem($this, $url, $label, $adminOnly);
        $this->items[] = $item;
        return $item;
    }
    
   /**
    * @return \EVEBiographies\Website_NavigationItem[]
    */
    public function getItems()
    {
        $result = array();
        $charIsAdmin = $this->website->isUserAuthenticated() && $this->website->getCharacter()->isAdmin();
        
        foreach($this->items as $item) 
        {
            if($item->isAdminOnly() && !$charIsAdmin) {
                continue;
            }
            
            $result[] = $item;
        }
        
        return $result;
    }
}
