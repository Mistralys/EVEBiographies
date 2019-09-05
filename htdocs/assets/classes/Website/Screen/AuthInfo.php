<?php

namespace EVEBiographies;

class Website_Screen_AuthInfo extends Website_Screen
{
    protected function _start()
    {
        // the user has confirmed the login? We set the information
        // screen as done, and redirect to the target URL: the target
        // page will check the authentication again, and redirect to 
        // the EVE SSO.
        if($this->request->getBool('confirm')) 
        {
            $_SESSION['auth']['info_shown'] = true;
            
            $this->redirect($_SESSION['auth']['landing_url']);
        }
    }
    
    public function requiresAuthentication() : bool
    {
        return false;
    }
    
    public function useAuthInfoScreen() : bool
    {
        return false;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
    
    public function getPageTitle()
    {
        return t('EVE Online biographies');
    }
    
    public function getNavigationTitle()
    {
        return t('About');
    }
    
    public function getDispatcher()
    {
        return 'authinfo.php';
    }
    
    public function getPrettyDispatcher()
    {
        return 'authinfo.php';
    }
    
    protected function _render()
    {
        $tpl = $this->skin->createTemplate('authinfo');
        $tpl->addVar('admin-required', $_SESSION['auth']['admin_required']);
        
        return $tpl->render();
    }
}