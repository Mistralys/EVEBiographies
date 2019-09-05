<?php

namespace EVEBiographies;

class Website_Screen_Contact extends Website_Screen
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
        return t('Contact');
    }
    
    public function getNavigationTitle()
    {
        return t('Contact');
    }
    
    public function getDispatcher()
    {
        return 'contact.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'contact';
    }
    
    protected function _render()
    {
        $tpl = $this->skin->createTemplate('contact');
        return $tpl->render();
    }
}