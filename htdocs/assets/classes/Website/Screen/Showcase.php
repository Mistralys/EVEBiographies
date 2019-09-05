<?php

namespace EVEBiographies;

class Website_Screen_Showcase extends Website_Screen
{
    protected function _start()
    {
    }
    
    public function requiresAuthentication()
    {
        return false;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    public function getPageTitle()
    {
        return t('Showcase of EVE Online biographies');
    }
    
    public function getNavigationTitle()
    {
        return t('Showcase');
    }
    
    public function getDispatcher()
    {
        return 'showcase.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'showcase';
    }
    
    protected function _render()
    {
        $collection = $this->website->createBiographies();
        
        
        $tpl = $this->skin->createTemplate('showcase');
        return $tpl->render();
    }
}