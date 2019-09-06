<?php

namespace EVEBiographies;

class Website_Screen_SqliteAdmin extends Website_Screen
{
    protected function _start()
    {
        if(!$this->website->isUserAuthenticated() || !$this->website->getCharacter()->isAdmin()) {
            $this->redirect($this->website->createScreen('Administration')->getURL(array('action' => 'sqliteadmin')));
        }
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
        return t('Biographies showcase');
    }
    
    public function getNavigationTitle()
    {
        return t('Showcase');
    }
    
    public function getDispatcher()
    {
        return 'storage/';
    }
    
    public function getPrettyDispatcher()
    {
        return 'storage/';
    }
    
    protected function _render()
    {
        return ''; 
    }
}