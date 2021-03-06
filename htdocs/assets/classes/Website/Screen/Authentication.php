<?php

namespace EVEBiographies;

class Website_Screen_Authentication extends Website_Screen
{
    protected function _start()
    {
        $code = filter_input(INPUT_GET, 'code');
        $state = filter_input(INPUT_GET, 'state');

        if(empty($code) || empty($state)) 
        {
            $this->log('code or state parameters are empty.');
            $this->redirect(APP_URL);
        }

        $userInfo = $this->website->createEVEAuth()->handleCallback($code, $state, $_SESSION);
        if(empty($userInfo['characterID'])) 
        {
            $_SESSION = array();
            
            $this->log('Login error: userInfo array characterID missing');
            
            die('Error while logging in. Please retry: <a href="'.$this->getScreenURL('Write').'">Login</a>');
        }

        $chars = $this->website->createCharacters();
        $character = null;

        $this->startTransaction();

        if($chars->foreignIDExists($userInfo['characterID']))
        {
            $this->log('Retrieving character by foreign ID.');
            $character = $chars->getByForeignID($userInfo['characterID']);
        }
        else
        {
            $this->log('Creating the new character ['.$userInfo['characterName'].']');
            $character = $chars->createNew($userInfo['characterID'], $userInfo['characterName']);
        }
        
        if($character->countLogins() == 0) {
            $this->addSuccessMessage(t('Hello %1$s, welcome to %2$s.', $character->getName(), $this->website->getName()), true);
        } else {
            $this->addSuccessMessage(t('Welcome back, %1$s.', $character->getName()), true);
        }

        $character
        ->setLastLogin(new \DateTime())
        ->incrementAmountLogins()
        ->save();

        $this->endTransaction();

        $_SESSION['character_id'] = $character->getID();

        $url = $this->getScreenURL('Write');

        // is a landing URL set? If yes, we redirect there instead.
        if(!empty($_SESSION['auth']['landing_url'])) {
            $url = $_SESSION['auth']['landing_url'];
        }

        unset($_SESSION['auth']);

        $this->log('Auth done, redirecting to ['.$url.'].');
        
        $this->redirect($url);
    }

    public function requiresAuthentication()
    {
        return false;
    }

    public function getPageTitle()
    {
        return t('Authentication');
    }

    public function getNavigationTitle()
    {
        return t('Authentication');
    }

    public function getDispatcher()
    {
        return 'auth.php';
    }

    public function getPrettyDispatcher()
    {
        return 'auth.php';
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
