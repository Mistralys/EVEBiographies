<?php

namespace EVEBiographies;

class Website_Screen_Nexus extends Website_Screen
{
    protected function _start()
    {
    }
    
    public function requiresAuthentication()
    {
        return true;
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
        return 'nexus.php';
    }
  
    public function getPrettyDispatcher()
    {
        return 'nexus';
    }
    
    protected function _render()
    {
        $tpl = $this->skin->createTemplate('nexus');
        return $tpl->render();
    }
}