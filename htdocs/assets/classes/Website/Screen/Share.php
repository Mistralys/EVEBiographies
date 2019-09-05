<?php

namespace EVEBiographies;

require_once 'Website/Screen/Read.php';

class Website_Screen_Share extends Website_Screen_Read
{
    public function requiresAuthentication() : bool
    {
        return false;
    }

    public function getDispatcher()
    {
        return 'share.php';
    }

    public function getPrettyDispatcher()
    {
        return 'share/{char}';
    }

    protected function getSkinID()
    {
        return 'Website';
    }

    protected function _start()
    {
        parent::_start();

        if($this->isUserAuthenticated()) {
            $this->redirect($this->biography->getViewURL());
        }
    }

    protected function _render()
    {
        $tpl = $this->skin->createTemplate('share');
        $tpl->addVar('character', $this->character);
        return $tpl->render();
    }
}
