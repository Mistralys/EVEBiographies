<?php

namespace EVEBiographies;

class Website_Screen_Authentication extends Website_Screen
{
    protected function _start()
    {
        $code = filter_input(INPUT_GET, 'code');
        $state = filter_input(INPUT_GET, 'state');

        if(empty($code) || empty($state)) {
            $this->redirect(APP_URL);
        }

        $userInfo = $this->website->createEVEAuth()->handleCallback($code, $state, $_SESSION);
        if(empty($userInfo['characterID'])) {
            $_SESSION = array();
            die('Error while logging in. Please retry: <a href="'.$this->getScreenURL('Write').'">Login</a>');
        }

        $chars = $this->website->createCharacters();
        $character = null;

        $this->startTransaction();

        if($chars->foreignIDExists($userInfo['characterID']))
        {
            $character = $chars->getByForeignID($userInfo['characterID']);
        }
        else
        {
            $character = $chars->createNew($userInfo['characterID'], $userInfo['characterName']);
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
