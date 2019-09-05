<?php

namespace EVEBiographies;

class Website_Screen_Legal extends Website_Screen
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
        return t('Legal information');
    }
    
    public function getNavigationTitle()
    {
        return t('Legal');
    }
    
    public function getDispatcher()
    {
        return 'legal.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'legal';
    }
    
    protected function _render()
    {
        $tpl = $this->skin->createTemplate('legal');
        return $tpl->render();
    }
}