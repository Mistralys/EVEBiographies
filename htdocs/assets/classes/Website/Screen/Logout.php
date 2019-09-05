<?php

namespace EVEBiographies;

class Website_Screen_Logout extends Website_Screen
{
    protected function _start()
    {
        if(isset($_SESSION['character_id'])) {
            unset($_SESSION['character_id']);
        }
        
        $this->redirect($this->getScreenURL('About'));
    }
    
    public function requiresAuthentication()
    {
        return false;
    }
    
    public function getPageTitle()
    {
        return t('Logout');
    }
    
    public function getNavigationTitle()
    {
        return t('Logout');
    }
    
    public function getDispatcher()
    {
        return 'logout.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'logout';
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    protected function _render()
    {
        return '';
    }
}